<?php
session_start();
include "../config/db.php";

if (empty($_SESSION['cart'])) {
    echo "<h2 style='color:white;text-align:center'>Keranjang kosong 🛒</h2>";
    exit;
}

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Checkout</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: radial-gradient(circle at top, #020617, #0f172a);
    color: white;
}

/* 🔥 ANIMASI GLOBAL */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* CONTAINER */
.container {
    width: 90%;
    margin: auto;
    padding: 30px 20px;
    animation: fadeUp 0.7s ease;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

a {
    color: #60a5fa;
    text-decoration: none;
    transition: 0.3s;
}

a:hover {
    color: #93c5fd;
}

/* 🔥 GRID */
.grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
    margin-top: 25px;
}

/* 🔥 CARD PREMIUM */
.card {
    background: rgba(30,41,59,0.65);
    backdrop-filter: blur(14px);
    padding: 22px;
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    animation: fadeUp 0.6s ease;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-4px);
}

/* INPUT */
input {
    width: 100%;
    padding: 13px;
    border-radius: 10px;
    border: none;
    margin-top: 12px;
    background: #020617;
    color: white;
    outline: none;
    transition: 0.3s;
}

input:focus {
    box-shadow: 0 0 0 2px #2563eb;
}

/* TABLE */
table {
    width: 100%;
    margin-top: 12px;
    border-collapse: collapse;
}

td {
    padding: 10px;
    border-bottom: 1px solid #334155;
    font-size: 14px;
}

/* 🔥 HOVER ITEM */
tr:hover td {
    background: rgba(37,99,235,0.08);
}

/* BUTTON */
.btn {
    background: linear-gradient(45deg,#2563eb,#60a5fa);
    padding: 13px;
    border: none;
    border-radius: 10px;
    color: white;
    width: 100%;
    margin-top: 18px;
    cursor: pointer;
    transition: 0.3s;
    position: relative;
    overflow: hidden;
}

/* 🔥 SHINE EFFECT */
.btn::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(120deg,transparent,rgba(255,255,255,0.4),transparent);
    top: 0;
    left: -100%;
    transition: 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: scale(1.04);
}

/* TOTAL */
.total {
    font-size: 22px;
    font-weight: bold;
    margin-top: 15px;
    color: #60a5fa;
}

/* 🔥 RESPONSIVE */
@media(max-width:768px){
    .grid {
        grid-template-columns: 1fr;
    }
}

</style>
</head>

<body>

<div class="container">

<div class="header">
    <h2>🧾 Checkout</h2>
    <a href="cart.php">← Kembali ke Keranjang</a>
</div>

<div class="grid">

<!-- FORM -->
<div class="card" style="animation-delay:0.2s;">
    <h3>Data Pembeli</h3>

    <form action="../proses/proses_checkout.php" method="POST">
        <input type="text" name="nama" placeholder="Nama Pembeli" required>

        <button class="btn">Checkout Sekarang</button>
    </form>
</div>

<!-- RINGKASAN -->
<div class="card" style="animation-delay:0.4s;">

    <h3>Ringkasan Pesanan</h3>

    <table>
    <?php
    foreach ($_SESSION['cart'] as $id => $qty) {
        $data = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
        $p = mysqli_fetch_assoc($data);

        $subtotal = $p['harga'] * $qty;
        $total += $subtotal;
    ?>
    <tr>
        <td><?= $p['nama_produk'] ?> (x<?= $qty ?>)</td>
        <td align="right">Rp <?= number_format($subtotal) ?></td>
    </tr>
    <?php } ?>
    </table>

    <div class="total">
        Total: Rp <?= number_format($total) ?>
    </div>
</div>

</div>

</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
