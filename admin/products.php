<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
include "../config/db.php";

$search   = $_GET['search']   ?? '';
$kategori = $_GET['kategori'] ?? '';
$limit    = 8;
$page     = $_GET['page']     ?? 1;
$start    = ($page - 1) * $limit;

$where = "WHERE 1=1";
if ($search   !== '') $where .= " AND produk.nama_produk LIKE '%$search%'";
if ($kategori !== '') $where .= " AND produk.id_kategori='$kategori'";

$query = mysqli_query($conn, "
  SELECT produk.*, kategori.nama_kategori
  FROM produk
  LEFT JOIN kategori ON produk.id_kategori = kategori.id
  $where LIMIT $start,$limit
");

$totalRow    = mysqli_fetch_assoc(mysqli_query($conn,
  "SELECT COUNT(*) as total FROM produk LEFT JOIN kategori ON produk.id_kategori=kategori.id $where"));
$total_pages = ceil($totalRow['total'] / $limit);

// Ringkasan profit total
$profitQ = mysqli_query($conn, "SELECT
  SUM(harga)        as total_jual,
  SUM(harga_beli)   as total_beli,
  SUM(harga - harga_beli) as total_profit
  FROM produk");
$profitSum = mysqli_fetch_assoc($profitQ);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Produk — AY Vape Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/main.css">
<style>
/* PROFIT SUMMARY BAR */
.profit-bar {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  margin-bottom: 24px;
}

.profit-bar-item {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 16px 20px;
  position: relative;
  overflow: hidden;
  transition: all var(--t);
}

.profit-bar-item::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
}

.profit-bar-item.modal::before  { background: linear-gradient(90deg,transparent,#f59e0b,transparent); }
.profit-bar-item.jual::before   { background: linear-gradient(90deg,transparent,var(--neon),var(--cyan),transparent); }
.profit-bar-item.profit::before { background: linear-gradient(90deg,transparent,#34d399,transparent); }

.profit-bar-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  margin-bottom: 6px;
}

.profit-bar-item.modal  .profit-bar-label { color: #f59e0b; }
.profit-bar-item.jual   .profit-bar-label { color: #a855f7; }
.profit-bar-item.profit .profit-bar-label { color: #34d399; }

.profit-bar-val {
  font-family: 'Orbitron', monospace;
  font-size: 18px;
  font-weight: 700;
  line-height: 1;
}

.profit-bar-item.modal  .profit-bar-val { color: #fbbf24; }
.profit-bar-item.jual   .profit-bar-val { color: #a855f7; }
.profit-bar-item.profit .profit-bar-val { color: #34d399; }

/* TOOLBAR */
.toolbar {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 24px;
  padding: 16px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
}

.toolbar .form-control { width: auto; min-width: 160px; }
.toolbar select.form-control { min-width: 150px; }

/* PRODUCT GRID */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: 20px;
}

.product-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
  transition: all var(--t);
}

.product-card:hover {
  border-color: var(--border-glow);
  transform: translateY(-4px);
  box-shadow: 0 16px 40px rgba(0,0,0,0.6);
}

.product-img {
  height: 180px;
  overflow: hidden;
  background: rgba(255,255,255,0.02);
  position: relative;
}

.product-img img { width:100%;height:100%;object-fit:cover;transition:transform 0.4s; }
.product-card:hover .product-img img { transform: scale(1.06); }

.stock-badge {
  position: absolute; top:10px;right:10px;
  background: rgba(3,3,10,0.85);
  border: 1px solid var(--border);
  backdrop-filter: blur(8px);
  padding: 3px 9px;
  border-radius: 99px;
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px;
  letter-spacing: 1px;
  color: var(--cyan, #22d3ee);
}

.product-body { padding: 14px; }

.product-cat {
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--neon);
  margin-bottom: 4px;
}

.product-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 10px;
  line-height: 1.2;
}

/* HARGA ROWS */
.harga-rows {
  display: flex;
  flex-direction: column;
  gap: 4px;
  margin-bottom: 12px;
  padding: 10px;
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
}

.harga-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.harga-row-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
}

.harga-row-val {
  font-family: 'Orbitron', monospace;
  font-size: 12px;
  font-weight: 700;
}

.harga-row.beli   .harga-row-label { color: #f59e0b; }
.harga-row.beli   .harga-row-val   { color: #fbbf24; }
.harga-row.jual   .harga-row-label { color: #a855f7; }
.harga-row.jual   .harga-row-val   { color: #a855f7; }

.harga-divider { height: 1px; background: var(--border); margin: 4px 0; }

.harga-row.profit .harga-row-label { color: #34d399; }
.harga-row.profit .harga-row-val   { color: #34d399; }
.harga-row.profit.rugi .harga-row-label { color: #f87171; }
.harga-row.profit.rugi .harga-row-val   { color: #f87171; }

.product-actions { display: flex; gap: 8px; }

@media (max-width: 768px) {
  .profit-bar { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-logo">
      <div class="brand-icon">💨</div>
      <div><div class="brand-name">AY VAPE</div><div class="brand-tagline">Admin Panel</div></div>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Menu Utama</div>
    <a href="dashboard.php"    class="nav-item"><span class="nav-icon">⊞</span> Dashboard</a>
    <a href="products.php"     class="nav-item"><span class="nav-icon">◈</span> Produk</a>
    <a href="transactions.php" class="nav-item"><span class="nav-icon">◎</span> Transaksi</a>
    <a href="chat.php"         class="nav-item"><span class="nav-icon">◷</span> Chat</a>
    <a href="subscribers.php"  class="nav-item"><span class="nav-icon">📧</span> Subscribers</a>
  </nav>
  <div class="sidebar-footer">
    <a href="../proses/logout.php" class="nav-logout"><span class="nav-icon">⏻</span> Logout</a>
  </div>
  <div class="smoke-container">
    <div class="smoke-particle"></div>
    <div class="smoke-particle"></div>
    <div class="smoke-particle"></div>
  </div>
</aside>

<div class="topbar">
  <span class="topbar-title">PRODUK</span>
  <button class="hamburger" onclick="toggleSidebar()">☰</button>
</div>

<main class="main">

  <div class="page-header fade-up">
    <div class="page-title">Produk</div>
    <div class="page-sub">Kelola produk dan pantau profit tiap item.</div>
  </div>

  <!-- PROFIT SUMMARY BAR -->
  <div class="profit-bar fade-up">
    <div class="profit-bar-item modal">
      <div class="profit-bar-label">💸 Total Modal</div>
      <div class="profit-bar-val">Rp <?= number_format($profitSum['total_beli'] ?? 0) ?></div>
    </div>
    <div class="profit-bar-item jual">
      <div class="profit-bar-label">🏷️ Total Harga Jual</div>
      <div class="profit-bar-val">Rp <?= number_format($profitSum['total_jual'] ?? 0) ?></div>
    </div>
    <div class="profit-bar-item profit">
      <div class="profit-bar-label">📈 Estimasi Profit</div>
      <div class="profit-bar-val">Rp <?= number_format($profitSum['total_profit'] ?? 0) ?></div>
    </div>
  </div>

  <!-- TOOLBAR -->
  <form method="GET">
    <div class="toolbar fade-up">
      <input type="text" name="search" class="form-control"
             placeholder="Cari produk..."
             value="<?= htmlspecialchars($search) ?>">
      <select name="kategori" class="form-control">
        <option value="">Semua Kategori</option>
        <?php
        $k = mysqli_query($conn, "SELECT * FROM kategori");
        while ($row = mysqli_fetch_assoc($k)) {
          $sel = ($kategori == $row['id']) ? 'selected' : '';
          echo "<option value='{$row['id']}' $sel>{$row['nama_kategori']}</option>";
        }
        ?>
      </select>
      <button type="submit" class="btn btn-ghost">🔍 Cari</button>
      <div style="margin-left:auto;">
        <a href="create_product.php" class="btn btn-primary">+ Tambah Produk</a>
      </div>
    </div>
  </form>

  <!-- GRID -->
  <?php if (mysqli_num_rows($query) === 0): ?>
  <div class="card" style="padding:60px;text-align:center;color:var(--text-muted);">
    <div style="font-size:40px;opacity:.2;margin-bottom:12px;">📦</div>
    <div style="font-family:'Rajdhani',sans-serif;font-size:14px;letter-spacing:1px;">Produk tidak ditemukan.</div>
  </div>
  <?php else: ?>
  <div class="product-grid">
    <?php while ($p = mysqli_fetch_assoc($query)):
      $beli   = (int)($p['harga_beli'] ?? 0);
      $jual   = (int)$p['harga'];
      $profit = $jual - $beli;
      $pct    = $beli > 0 ? round(($profit / $beli) * 100, 1) : 0;
      $isRugi = $profit < 0;
    ?>
    <div class="product-card fade-up">
      <div class="product-img">
        <img src="../uploads/products/<?= htmlspecialchars($p['gambar']) ?>"
             alt="<?= htmlspecialchars($p['nama_produk']) ?>">
        <span class="stock-badge">Stok <?= $p['stok'] ?></span>
      </div>
      <div class="product-body">
        <div class="product-cat"><?= htmlspecialchars($p['nama_kategori'] ?? '—') ?></div>
        <div class="product-name"><?= htmlspecialchars($p['nama_produk']) ?></div>

        <!-- HARGA ROWS -->
        <div class="harga-rows">
          <div class="harga-row beli">
            <span class="harga-row-label">💸 Harga Beli</span>
            <span class="harga-row-val">Rp <?= number_format($beli) ?></span>
          </div>
          <div class="harga-row jual">
            <span class="harga-row-label">🏷️ Harga Jual</span>
            <span class="harga-row-val">Rp <?= number_format($jual) ?></span>
          </div>
          <div class="harga-divider"></div>
          <div class="harga-row profit <?= $isRugi ? 'rugi' : '' ?>">
            <span class="harga-row-label"><?= $isRugi ? '⚠️ Rugi' : '📈 Profit' ?></span>
            <span class="harga-row-val">
              <?= $isRugi ? '-' : '+' ?>Rp <?= number_format(abs($profit)) ?>
              <span style="font-size:10px;opacity:.7;">(<?= $pct ?>%)</span>
            </span>
          </div>
        </div>

        <div class="product-actions">
          <a href="update_product.php?id=<?= $p['id'] ?>"
             class="btn btn-cyan btn-sm" style="flex:1;justify-content:center;">✏ Edit</a>
          <a href="../proses/proses_delete.php?id=<?= $p['id'] ?>"
             class="btn btn-danger btn-sm"
             style="flex:1;justify-content:center;"
             onclick="return confirm('Hapus produk ini?')">✕ Hapus</a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>

  <!-- PAGINATION -->
  <?php if ($total_pages > 1): ?>
  <div class="pagination" style="margin-top:24px;">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&kategori=<?= urlencode($kategori) ?>"
         class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>

</main>

<script>
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('active');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('overlay').classList.remove('active');
}
</script>
</body>
</html>