<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
include "../config/db.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Produk — AY Vape Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/main.css">
<style>
.form-card { max-width: 660px; padding: 32px; }

/* HARGA GROUP */
.harga-group {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.harga-box {
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 16px;
  position: relative;
  overflow: hidden;
  transition: border-color var(--t);
}

.harga-box:focus-within { border-color: var(--border-glow); }

.harga-box::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
}

.harga-box.beli::before  { background: linear-gradient(90deg, transparent, #f59e0b, transparent); }
.harga-box.jual::before  { background: linear-gradient(90deg, transparent, var(--neon), var(--cyan), transparent); }

.harga-box-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  margin-bottom: 6px;
}

.harga-box.beli .harga-box-label { color: #f59e0b; }
.harga-box.jual .harga-box-label { color: var(--neon-b, #a855f7); }

.harga-box-icon {
  position: absolute;
  top: 14px; right: 14px;
  font-size: 18px;
  opacity: 0.3;
}

.harga-box .form-control {
  border: none;
  background: transparent;
  padding: 6px 0;
  font-family: 'Orbitron', monospace;
  font-size: 17px;
  font-weight: 700;
}

.harga-box.beli .form-control  { color: #fbbf24; }
.harga-box.jual .form-control  { color: #a855f7; }

.harga-box .form-control:focus { box-shadow: none; background: transparent; }

.harga-box-prefix {
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  color: var(--text-muted);
  margin-bottom: 2px;
}

/* PROFIT PREVIEW */
.profit-preview {
  background: rgba(16,185,129,0.06);
  border: 1px solid rgba(16,185,129,0.2);
  border-radius: var(--radius-sm);
  padding: 12px 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 12px;
  flex-wrap: wrap;
  gap: 8px;
}

.profit-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: var(--text-muted);
}

.profit-val {
  font-family: 'Orbitron', monospace;
  font-size: 18px;
  font-weight: 700;
  color: #34d399;
  transition: all 0.2s;
}

.profit-pct {
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 99px;
  background: rgba(16,185,129,0.12);
  border: 1px solid rgba(16,185,129,0.25);
  color: #34d399;
  transition: all 0.2s;
}

.profit-val.negative { color: #f87171; }
.profit-pct.negative { color: #f87171; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); }

/* IMAGE UPLOAD */
.img-preview-box {
  margin-top: 10px;
  width: 100%;
  height: 160px;
  border: 2px dashed var(--border);
  border-radius: var(--radius);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all var(--t);
  overflow: hidden;
  position: relative;
}

.img-preview-box:hover {
  border-color: var(--neon-b, #a855f7);
  background: rgba(124,58,237,0.04);
}

.img-preview-box img { width:100%; height:100%; object-fit:cover; border-radius: calc(var(--radius) - 2px); }
.img-placeholder { text-align:center; color:var(--text-muted); font-family:'Rajdhani',sans-serif; font-size:13px; letter-spacing:1px; pointer-events:none; }
.img-placeholder .icon { font-size:32px; margin-bottom:6px; opacity:0.4; }
.file-hidden { display:none; }

@media (max-width: 600px) {
  .harga-group { grid-template-columns: 1fr; }
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
    <a href="products.php"     class="nav-item active"><span class="nav-icon">◈</span> Produk</a>
    <a href="transactions.php" class="nav-item"><span class="nav-icon">◎</span> Transaksi</a>
    <a href="chat.php"         class="nav-item"><span class="nav-icon">◷</span> Chat</a>
  </nav>
  <div class="sidebar-footer">
    <a href="../proses/logout.php" class="nav-logout"><span class="nav-icon">⏻</span> Logout</a>
  </div>
  <div class="smoke-container">
    <div class="smoke-particle"></div><div class="smoke-particle"></div><div class="smoke-particle"></div>
  </div>
</aside>

<div class="topbar">
  <span class="topbar-title">TAMBAH PRODUK</span>
  <button class="hamburger" onclick="toggleSidebar()">☰</button>
</div>

<main class="main">

  <a href="products.php" class="back-link fade-up">← Kembali ke Produk</a>

  <div class="page-header fade-up">
    <div class="page-title">Tambah Produk</div>
    <div class="page-sub">Isi detail produk baru. Harga beli hanya terlihat oleh admin.</div>
  </div>

  <div class="card form-card fade-up">
    <form action="../proses/proses_create.php" method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label class="form-label">Nama Produk</label>
        <input type="text" name="nama" class="form-control"
               placeholder="Cth: Vape Pod 5000 Puffs" required>
      </div>

      <!-- HARGA BELI & JUAL -->
      <div class="form-group">
        <label class="form-label">Harga</label>
        <div class="harga-group">

          <!-- HARGA BELI -->
          <div class="harga-box beli">
            <span class="harga-box-icon">💸</span>
            <div class="harga-box-label">Harga Beli (Modal)</div>
            <div class="harga-box-prefix">Rp</div>
            <input type="number" name="harga_beli" id="harga_beli"
                   class="form-control"
                   placeholder="0"
                   min="0"
                   oninput="hitungProfit()"
                   required>
          </div>

          <!-- HARGA JUAL -->
          <div class="harga-box jual">
            <span class="harga-box-icon">🏷️</span>
            <div class="harga-box-label">Harga Jual (Customer)</div>
            <div class="harga-box-prefix">Rp</div>
            <input type="number" name="harga" id="harga_jual"
                   class="form-control"
                   placeholder="0"
                   min="0"
                   oninput="hitungProfit()"
                   required>
          </div>

        </div>

        <!-- PROFIT PREVIEW -->
        <div class="profit-preview">
          <div>
            <div class="profit-label">Estimasi Profit per Unit</div>
          </div>
          <div style="display:flex;align-items:center;gap:10px;">
            <div class="profit-val" id="profitVal">Rp 0</div>
            <div class="profit-pct" id="profitPct">0%</div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Stok</label>
        <input type="number" name="stok" class="form-control"
               placeholder="50" min="0" required>
      </div>

      <div class="form-group">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-control" required>
          <option value="">— Pilih Kategori —</option>
          <?php
          $k = mysqli_query($conn, "SELECT * FROM kategori");
          while ($row = mysqli_fetch_assoc($k))
            echo "<option value='{$row['id']}'>{$row['nama_kategori']}</option>";
          ?>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control"
                  placeholder="Deskripsi produk..." rows="4"></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Gambar Produk</label>
        <input type="file" name="gambar" class="file-hidden" id="fileInput"
               accept="image/*" required onchange="previewImg(this)">
        <div class="img-preview-box" onclick="document.getElementById('fileInput').click()">
          <div class="img-placeholder" id="imgPlaceholder">
            <div class="icon">🖼️</div>
            <div>Klik untuk upload gambar</div>
          </div>
          <img id="imgPreview" src="" alt="" style="display:none;">
        </div>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px;">
        💾 Simpan Produk
      </button>

    </form>
  </div>

</main>

<script>
function hitungProfit() {
  const beli = parseFloat(document.getElementById('harga_beli').value) || 0;
  const jual = parseFloat(document.getElementById('harga_jual').value) || 0;
  const profit = jual - beli;
  const pct    = beli > 0 ? ((profit / beli) * 100).toFixed(1) : 0;

  const valEl = document.getElementById('profitVal');
  const pctEl = document.getElementById('profitPct');

  valEl.textContent = 'Rp ' + profit.toLocaleString('id-ID');
  pctEl.textContent = pct + '%';

  if (profit < 0) {
    valEl.classList.add('negative');
    pctEl.classList.add('negative');
  } else {
    valEl.classList.remove('negative');
    pctEl.classList.remove('negative');
  }
}

function previewImg(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('imgPreview').src = e.target.result;
    document.getElementById('imgPreview').style.display = 'block';
    document.getElementById('imgPlaceholder').style.display = 'none';
  };
  reader.readAsDataURL(file);
}

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