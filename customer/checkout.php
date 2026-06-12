<?php
session_start();
include "../config/db.php";

$jumlah      = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
$active_page = '';

if (empty($_SESSION['cart'])) {
  header("Location: cart.php");
  exit;
}

$total = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Checkout — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<style>
.checkout-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 24px;
  align-items: start;
}

/* STEPS */
.steps {
  display: flex;
  align-items: center;
  gap: 0;
  margin-bottom: 32px;
}

.step {
  display: flex;
  align-items: center;
  gap: 8px;
  flex: 1;
}

.step-num {
  width: 32px; height: 32px;
  border-radius: 50%;
  background: rgba(124,58,237,0.12);
  border: 1px solid var(--border-glow);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Orbitron', monospace;
  font-size: 13px;
  font-weight: 700;
  color: var(--neon-b);
  flex-shrink: 0;
}

.step.active .step-num {
  background: var(--neon);
  border-color: var(--neon);
  color: #fff;
  box-shadow: 0 0 12px var(--glow);
}

.step-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--text-muted);
}

.step.active .step-label { color: #fff; }

.step-line {
  flex: 1;
  height: 1px;
  background: var(--border);
  margin: 0 8px;
}

/* FORM CARD */
.checkout-form-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 28px;
  position: relative;
  overflow: hidden;
}

.checkout-form-card::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--neon), var(--cyan), transparent);
}

.form-section-title {
  font-family: 'Orbitron', monospace;
  font-size: 16px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

/* ORDER SUMMARY */
.order-summary-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 24px;
  position: sticky;
  top: calc(var(--nav-h) + 20px);
}

.order-summary-card::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: linear-gradient(90deg, transparent, var(--cyan), var(--neon-b), transparent);
}

.order-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 0;
  border-bottom: 1px solid rgba(255,255,255,0.04);
}
.order-item:last-child { border-bottom: none; }

.order-item-img {
  width: 48px; height: 48px;
  border-radius: var(--radius-sm);
  object-fit: cover;
  border: 1px solid var(--border);
  flex-shrink: 0;
}

.order-item-info { flex: 1; }
.order-item-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 14px;
  font-weight: 600;
  color: #fff;
  margin-bottom: 2px;
}
.order-item-qty { font-size: 12px; color: var(--text-muted); }

.order-item-price {
  font-family: 'Orbitron', monospace;
  font-size: 13px;
  color: var(--cyan);
  white-space: nowrap;
  flex-shrink: 0;
}

.order-total-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 0 0;
  margin-top: 8px;
  border-top: 1px solid var(--border);
}

.order-total-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--text-muted);
}

.order-total-val {
  font-family: 'Orbitron', monospace;
  font-size: 22px;
  font-weight: 900;
  background: linear-gradient(135deg, var(--neon-b), var(--cyan));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

@media (max-width: 900px) {
  .checkout-grid { grid-template-columns: 1fr; }
  .order-summary-card { position: static; }
}
</style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<section class="section">
  <div class="container">

    <!-- STEPS -->
    <div class="steps fade-up">
      <div class="step active">
        <div class="step-num">1</div>
        <div class="step-label">Data Pembeli</div>
      </div>
      <div class="step-line"></div>
      <div class="step">
        <div class="step-num">2</div>
        <div class="step-label">Pembayaran</div>
      </div>
      <div class="step-line"></div>
      <div class="step">
        <div class="step-num">3</div>
        <div class="step-label">Selesai</div>
      </div>
    </div>

    <div class="checkout-grid">

      <!-- FORM -->
      <div class="checkout-form-card fade-up">
        <div class="form-section-title">
          <span>👤</span> Data Pembeli
        </div>

        <form action="../proses/proses_checkout.php" method="POST">

          <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control"
                   placeholder="Masukkan nama lengkap" required>
          </div>

          <div class="form-group">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="tel" name="no_wa" class="form-control"
                   placeholder="08xx-xxxx-xxxx">
          </div>

          <div class="form-group">
            <label class="form-label">Alamat / Lokasi Temu (COD)</label>
            <textarea name="alamat" class="form-control" rows="3"
                placeholder="Contoh: Alamat lengkap atau lokasi janjian COD"></textarea>
          </div>

          <div class="divider"></div>

          <div class="form-section-title" style="margin-bottom:16px;">
            <span>📝</span> Catatan (opsional)
          </div>

          <div class="form-group">
            <textarea name="catatan" class="form-control" rows="2"
                      placeholder="Catatan khusus untuk pesanan..."></textarea>
          </div>

          <button type="submit" class="btn btn-primary btn-full btn-lg" style="margin-top:8px;">
            Lanjut ke Pembayaran →
          </button>

          <a href="cart.php" class="btn btn-secondary btn-full" style="margin-top:10px;">
            ← Kembali ke Keranjang
          </a>

        </form>
      </div>

      <!-- ORDER SUMMARY -->
      <div class="order-summary-card fade-up-2">
        <div class="summary-title" style="font-family:'Orbitron',monospace;font-size:14px;font-weight:700;color:#fff;margin-bottom:16px;">
          Ringkasan Pesanan
        </div>

        <?php foreach ($_SESSION['cart'] as $id => $qty):
          $q   = mysqli_query($conn, "SELECT * FROM produk WHERE id=".(int)$id);
          $p   = mysqli_fetch_assoc($q);
          $sub = $p['harga'] * $qty;
          $total += $sub;
        ?>
        <div class="order-item">
          <img class="order-item-img"
               src="../uploads/products/<?= htmlspecialchars($p['gambar']) ?>"
               alt="">
          <div class="order-item-info">
            <div class="order-item-name"><?= htmlspecialchars($p['nama_produk']) ?></div>
            <div class="order-item-qty">× <?= $qty ?></div>
          </div>
          <div class="order-item-price">Rp <?= number_format($sub) ?></div>
        </div>
        <?php endforeach; ?>

        <div class="order-total-row">
          <div class="order-total-label">Total</div>
          <div class="order-total-val">Rp <?= number_format($total) ?></div>
        </div>

        <div style="margin-top:14px;padding:12px;background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.15);border-radius:var(--radius-sm);">
          <div style="font-family:'Rajdhani',sans-serif;font-size:12px;color:var(--green);letter-spacing:1px;">
            ✅ COD langsung dengan admin · Rekber Shopee tersedia via chat
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
</body>
</html>