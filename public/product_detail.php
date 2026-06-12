<?php
session_start();
include "../config/db.php";

$id          = (int)$_GET['id'];
$jumlah      = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
$active_page = '';

$data = mysqli_query($conn, "
  SELECT produk.*, kategori.nama_kategori
  FROM produk
  LEFT JOIN kategori ON produk.id_kategori = kategori.id
  WHERE produk.id='$id'
");
$p = mysqli_fetch_assoc($data);

if (!$p) { header("Location: index.php"); exit; }

// Related products
$related = mysqli_query($conn, "
  SELECT produk.*, kategori.nama_kategori
  FROM produk
  LEFT JOIN kategori ON produk.id_kategori = kategori.id
  WHERE produk.id_kategori = '{$p['id_kategori']}' AND produk.id != '$id'
  LIMIT 4
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($p['nama_produk']) ?> — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<style>
/* BREADCRUMB */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  letter-spacing: 1px;
  color: var(--text-muted);
  margin-bottom: 28px;
}
.breadcrumb a { color: var(--text-muted); transition: color var(--t); }
.breadcrumb a:hover { color: var(--neon-b); }
.breadcrumb-sep { opacity: 0.3; }
.breadcrumb-current { color: var(--text-dim); }

/* DETAIL LAYOUT */
.detail-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: start;
}

/* IMAGE SIDE */
.detail-img-main {
  position: relative;
  border-radius: var(--radius-lg);
  overflow: hidden;
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--border);
  aspect-ratio: 1;
  cursor: zoom-in;
}

.detail-img-main img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s var(--ease);
}

.detail-img-main:hover img { transform: scale(1.12); }

.detail-img-badge {
  position: absolute;
  top: 14px; left: 14px;
  background: rgba(3,3,10,0.85);
  border: 1px solid var(--border);
  backdrop-filter: blur(8px);
  padding: 5px 12px;
  border-radius: var(--radius-pill);
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--cyan);
  z-index: 2;
}

/* Zoom hint */
.zoom-hint {
  position: absolute;
  bottom: 12px; right: 12px;
  background: rgba(3,3,10,0.7);
  border: 1px solid var(--border);
  backdrop-filter: blur(8px);
  padding: 4px 10px;
  border-radius: var(--radius-pill);
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px;
  letter-spacing: 1px;
  color: var(--text-muted);
  z-index: 2;
}

/* DETAIL INFO SIDE */
.detail-cat {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: var(--neon-b);
  margin-bottom: 10px;
}

.detail-name {
  font-family: 'Orbitron', monospace;
  font-size: clamp(20px, 3vw, 32px);
  font-weight: 700;
  color: #fff;
  line-height: 1.15;
  margin-bottom: 16px;
}

.detail-price-row {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 20px;
}

.detail-price {
  font-family: 'Orbitron', monospace;
  font-size: 32px;
  font-weight: 700;
  background: linear-gradient(135deg, var(--neon-b), var(--cyan));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.detail-stock-badge {
  padding: 5px 14px;
  border-radius: var(--radius-pill);
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.stock-ok  { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
.stock-low { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }
.stock-out { background: rgba(239,68,68,0.12);  color: #f87171; border: 1px solid rgba(239,68,68,0.25); }

.detail-divider { height: 1px; background: var(--border); margin: 20px 0; }

.detail-desc-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 10px;
}

.detail-desc {
  font-size: 14px;
  line-height: 1.75;
  color: var(--text-dim);
}

/* QTY SELECTOR */
.qty-row {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 20px 0;
}

.qty-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--text-muted);
  min-width: 60px;
}

.qty-ctrl {
  display: flex;
  align-items: center;
  gap: 0;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  overflow: hidden;
}

.qty-btn {
  width: 38px; height: 38px;
  background: transparent;
  border: none;
  color: var(--text-dim);
  font-size: 18px;
  transition: all var(--t);
  display: flex; align-items: center; justify-content: center;
}
.qty-btn:hover { background: rgba(124,58,237,0.12); color: #fff; }

.qty-val {
  width: 48px;
  text-align: center;
  font-family: 'Orbitron', monospace;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  border-left: 1px solid var(--border);
  border-right: 1px solid var(--border);
  padding: 8px 0;
}

/* ACTION BUTTONS */
.detail-actions { display: flex; gap: 12px; }

/* FEATURES */
.feature-row {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
  margin-top: 20px;
}

.feature-item {
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 12px;
  text-align: center;
  font-family: 'Rajdhani', sans-serif;
}

.feature-item .icon { font-size: 20px; margin-bottom: 4px; }
.feature-item .label { font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--text-muted); }

/* RELATED */
.related-section { margin-top: 60px; }

@media (max-width: 768px) {
  .detail-grid { grid-template-columns: 1fr; gap: 28px; }
  .detail-img-main { aspect-ratio: 4/3; }
  .feature-row { grid-template-columns: repeat(3, 1fr); }
  .detail-price { font-size: 26px; }
}
</style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<section class="section">
  <div class="container">

    <!-- BREADCRUMB -->
    <div class="breadcrumb fade-up">
      <a href="index.php">Home</a>
      <span class="breadcrumb-sep">/</span>
      <span class="breadcrumb-current"><?= htmlspecialchars($p['nama_produk']) ?></span>
    </div>

    <!-- DETAIL GRID -->
    <div class="detail-grid">

      <!-- IMAGE -->
      <div class="fade-up">
        <div class="detail-img-main" id="imgBox">
          <span class="detail-img-badge"><?= htmlspecialchars($p['nama_kategori'] ?? '—') ?></span>
          <img id="mainImg" src="../uploads/products/<?= htmlspecialchars($p['gambar']) ?>"
               alt="<?= htmlspecialchars($p['nama_produk']) ?>">
          <span class="zoom-hint">🔍 Hover to zoom</span>
        </div>
      </div>

      <!-- INFO -->
      <div class="fade-up-2">

        <div class="detail-cat"><?= htmlspecialchars($p['nama_kategori'] ?? '—') ?></div>
        <div class="detail-name"><?= htmlspecialchars($p['nama_produk']) ?></div>

        <div class="detail-price-row">
          <div class="detail-price">Rp <?= number_format($p['harga']) ?></div>
          <?php
          $stok = (int)$p['stok'];
          if ($stok <= 0)       echo '<span class="detail-stock-badge stock-out">Habis</span>';
          elseif ($stok <= 5)   echo '<span class="detail-stock-badge stock-low">Sisa '.$stok.'</span>';
          else                  echo '<span class="detail-stock-badge stock-ok">Stok '.$stok.'</span>';
          ?>
        </div>

        <div class="detail-divider"></div>

        <div class="detail-desc-label">Deskripsi Produk</div>
        <div class="detail-desc">
          <?= nl2br(htmlspecialchars($p['deskripsi'] ?: 'Tidak ada deskripsi tersedia untuk produk ini.')) ?>
        </div>

        <?php if ($stok > 0): ?>
        <div class="qty-row">
          <span class="qty-label">Jumlah</span>
          <div class="qty-ctrl">
            <button class="qty-btn" onclick="changeQty(-1)">−</button>
            <div class="qty-val" id="qtyVal">1</div>
            <button class="qty-btn" onclick="changeQty(1)">+</button>
          </div>
        </div>

        <div class="detail-actions">
          <button onclick="addToCartDetail(<?= $p['id'] ?>)"
                  class="btn btn-primary" style="flex:1;justify-content:center;">
            🛒 Tambah ke Keranjang
          </button>
          <a href="../customer/checkout.php" class="btn btn-cyan" style="flex:1;justify-content:center;">
            ⚡ Beli Sekarang
          </a>
        </div>
        <?php else: ?>
        <div class="btn btn-danger" style="width:100%;justify-content:center;margin-top:20px;cursor:default;">
          Stok Habis
        </div>
        <?php endif; ?>

        <div class="feature-row">
          <div class="feature-item">
            <div class="icon">🚚</div>
            <div class="label">Fast Delivery</div>
          </div>
          <div class="feature-item">
            <div class="icon">✅</div>
            <div class="label">Original</div>
          </div>
          <div class="feature-item">
            <div class="icon">💬</div>
            <div class="label">24/7 Support</div>
          </div>
        </div>

      </div>
    </div>

    <!-- RELATED PRODUCTS -->
    <?php if (mysqli_num_rows($related) > 0): ?>
    <div class="related-section">
      <div class="section-header">
        <div class="section-eyebrow">Produk Serupa</div>
        <div class="section-title">Rekomendasi <span>Untukmu</span></div>
      </div>
      <div class="product-grid">
        <?php while ($r = mysqli_fetch_assoc($related)): ?>
        <div class="product-card">
          <div class="product-img">
            <img src="../uploads/products/<?= htmlspecialchars($r['gambar']) ?>"
                 alt="<?= htmlspecialchars($r['nama_produk']) ?>">
            <span class="product-badge">Stok <?= $r['stok'] ?></span>
            <div class="product-img-overlay">
              <a href="product_detail.php?id=<?= $r['id'] ?>" class="btn btn-secondary btn-sm">Lihat →</a>
            </div>
          </div>
          <div class="product-body">
            <div class="product-cat"><?= htmlspecialchars($r['nama_kategori'] ?? '—') ?></div>
            <div class="product-name"><?= htmlspecialchars($r['nama_produk']) ?></div>
            <div class="product-price">Rp <?= number_format($r['harga']) ?></div>
            <div class="product-actions">
              <a href="product_detail.php?id=<?= $r['id'] ?>" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center;">Detail</a>
              <button onclick="addToCart(<?= $r['id'] ?>, this)" class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">+ Keranjang</button>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
<a href="https://wa.me/6282333408651" class="wa-float">💬 Chat WA</a>

<script>
let qty = 1;
const maxQty = <?= $stok ?>;

function changeQty(delta) {
  qty = Math.max(1, Math.min(maxQty, qty + delta));
  document.getElementById('qtyVal').textContent = qty;
}

function addToCartDetail(id) {
  const btn = event.currentTarget;
  btn.textContent = '...';
  btn.disabled = true;

  fetch('../ajax/add_to_cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id=' + id + '&qty=' + qty
  })
  .then(r => r.json())
  .then(d => {
    showToast(d.msg || 'Ditambahkan!', 'success');
    const badge = document.querySelector('.cart-count');
    if (d.total !== undefined) {
      if (badge) badge.textContent = d.total;
    }
  })
  .finally(() => {
    btn.textContent = '✔ Ditambahkan';
    setTimeout(() => { btn.innerHTML = '🛒 Tambah ke Keranjang'; btn.disabled = false; }, 1500);
  });
}

function addToCart(id, btn) {
  const orig = btn.textContent;
  btn.disabled = true;
  fetch('../ajax/add_to_cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id=' + id
  })
  .then(r => r.json())
  .then(d => { showToast(d.msg || 'Ditambahkan!', 'success'); })
  .finally(() => { btn.textContent = '✔'; setTimeout(() => { btn.textContent = orig; btn.disabled = false; }, 1400); });
}

function showToast(text, type='info') {
  const t = document.createElement('div');
  t.className = 'toast toast-' + type;
  t.textContent = text;
  document.body.appendChild(t);
  setTimeout(() => { t.style.opacity='0'; t.style.transition='0.3s'; }, 2200);
  setTimeout(() => t.remove(), 2600);
}
</script>
</body>
</html>