<?php
session_start();
include "../config/db.php";

$jumlah      = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
$active_page = '';

if (isset($_GET['hapus'])) {
  unset($_SESSION['cart'][(int)$_GET['hapus']]);
  header("Location: cart.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<style>
.cart-layout {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 24px;
  align-items: start;
}

/* CART ITEM ROW */
.cart-item {
  display: grid;
  grid-template-columns: 72px 1fr auto auto;
  align-items: center;
  gap: 16px;
  padding: 16px 20px;
  border-bottom: 1px solid rgba(255,255,255,0.04);
  transition: background var(--t);
}
.cart-item:last-child { border-bottom: none; }
.cart-item:hover { background: rgba(124,58,237,0.03); }

.cart-item-img {
  width: 72px; height: 72px;
  border-radius: var(--radius-sm);
  object-fit: cover;
  border: 1px solid var(--border);
  background: rgba(255,255,255,0.02);
}

.cart-item-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 16px;
  font-weight: 600;
  color: #fff;
  margin-bottom: 3px;
}

.cart-item-price {
  font-family: 'Orbitron', monospace;
  font-size: 13px;
  color: var(--text-muted);
}

.cart-item-subtotal {
  font-family: 'Orbitron', monospace;
  font-size: 15px;
  font-weight: 700;
  color: var(--cyan);
  text-align: right;
  white-space: nowrap;
}

/* SUMMARY CARD */
.summary-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 24px;
  position: sticky;
  top: calc(var(--nav-h) + 20px);
}

.summary-card::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: linear-gradient(90deg, transparent, var(--neon), var(--cyan), transparent);
}

.summary-title {
  font-family: 'Orbitron', monospace;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 1px;
  color: #fff;
  margin-bottom: 20px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  font-size: 14px;
  color: var(--text-dim);
}

.summary-row.total {
  border-top: 1px solid var(--border);
  padding-top: 14px;
  margin-top: 14px;
  font-family: 'Orbitron', monospace;
  color: #fff;
}

.summary-row.total .val {
  font-size: 20px;
  font-weight: 700;
  background: linear-gradient(135deg, var(--neon-b), var(--cyan));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* EMPTY STATE */
.empty-cart {
  text-align: center;
  padding: 80px 20px;
}
.empty-cart .icon { font-size: 64px; opacity: 0.15; margin-bottom: 16px; }
.empty-cart h3 {
  font-family: 'Orbitron', monospace;
  font-size: 18px;
  color: var(--text-dim);
  margin-bottom: 8px;
}
.empty-cart p { color: var(--text-muted); font-size: 14px; margin-bottom: 24px; }

@media (max-width: 900px) {
  .cart-layout { grid-template-columns: 1fr; }
  .summary-card { position: static; }
}

@media (max-width: 600px) {
  .cart-item { grid-template-columns: 56px 1fr; gap: 12px; }
  .cart-item-subtotal { display: none; }
}
</style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<section class="section">
  <div class="container">

    <div class="page-header fade-up">
      <div class="page-title" style="font-family:'Orbitron',monospace;font-size:28px;font-weight:700;color:#fff;">
        Keranjang
      </div>
      <div style="font-size:13px;color:var(--text-muted);font-family:'Rajdhani',sans-serif;letter-spacing:1px;margin-top:4px;">
        <?= $jumlah ?> item dalam keranjang
      </div>
    </div>

    <?php
    $total = 0;
    $hasItems = !empty($_SESSION['cart']);
    ?>

    <?php if ($hasItems): ?>
    <div class="cart-layout">

      <!-- ITEMS -->
      <div class="card fade-up" style="padding:0;overflow:hidden;">
        <?php
        foreach ($_SESSION['cart'] as $id => $qty):
          $q   = mysqli_query($conn, "SELECT * FROM produk WHERE id=".(int)$id);
          $p   = mysqli_fetch_assoc($q);
          $sub = $p['harga'] * $qty;
          $total += $sub;
        ?>
        <div class="cart-item">
          <img class="cart-item-img"
               src="../uploads/products/<?= htmlspecialchars($p['gambar']) ?>"
               alt="<?= htmlspecialchars($p['nama_produk']) ?>">

          <div>
            <div class="cart-item-name"><?= htmlspecialchars($p['nama_produk']) ?></div>
            <div class="cart-item-price">Rp <?= number_format($p['harga']) ?> × <?= $qty ?></div>
          </div>

          <div class="cart-item-subtotal">Rp <?= number_format($sub) ?></div>

          <a href="?hapus=<?= $id ?>"
             onclick="return confirm('Hapus produk ini?')"
             class="btn btn-danger btn-sm">✕</a>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- SUMMARY -->
      <div class="summary-card fade-up-2">
        <div class="summary-title">Ringkasan Order</div>

        <div class="summary-row">
          <span>Subtotal</span>
          <span>Rp <?= number_format($total) ?></span>
        </div>
        <div class="summary-row">
          <span>Ongkos Kirim</span>
          <span class="badge badge-green">Gratis</span>
        </div>

        <div class="summary-row total">
          <span>Total</span>
          <span class="val">Rp <?= number_format($total) ?></span>
        </div>

        <a href="checkout.php" class="btn btn-primary btn-full" style="margin-top:20px;">
          Checkout Sekarang →
        </a>

        <a href="../index.php" class="btn btn-secondary btn-full" style="margin-top:10px;">
          ← Lanjut Belanja
        </a>
      </div>

    </div>

    <?php else: ?>
    <div class="card empty-cart fade-up">
      <div class="icon">🛒</div>
      <h3>Keranjang Kosong</h3>
      <p>Belum ada produk yang ditambahkan ke keranjang.</p>
      <a href="../index.php" class="btn btn-primary">Mulai Belanja →</a>
    </div>
    <?php endif; ?>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
<a href="https://wa.me/6282333408651" class="wa-float">💬 Chat WA</a>
</body>
</html>