<?php
session_start();
include "../config/db.php";

// tambah ke cart
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }
}

// hapus item
if (isset($_GET['hapus'])) {
    unset($_SESSION['cart'][$_GET['hapus']]);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Keranjang</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #0f172a;
    color: white;
}

/* CONTAINER */
.container {
    width: 90%;
    margin: auto;
    padding: 30px 20px;
    animation: fadeUp 0.7s ease;
}

/* 🔥 ANIMASI MASUK */
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

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

a {
    color: #60a5fa;
    text-decoration: none;
    transition: 0.3s;
}

a:hover {
    color: #93c5fd;
}

/* 🔥 CARD GLASS */
.card {
    background: rgba(30,41,59,0.7);
    backdrop-filter: blur(12px);
    padding: 20px;
    border-radius: 16px;
    overflow-x: auto;
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: 0 15px 35px rgba(0,0,0,0.5);
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

/* 🔥 HEADER TABLE */
th {
    background: #020617;
    padding: 14px;
    font-size: 14px;
    color: #93c5fd;
}

/* 🔥 ROW */
td {
    padding: 12px;
    transition: 0.3s;
}

/* 🔥 HOVER ROW */
tr:hover td {
    background: rgba(37,99,235,0.1);
}

/* BORDER */
tr {
    border-bottom: 1px solid #334155;
}

/* 🔥 BUTTON */
.btn {
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-size: 14px;
    transition: 0.3s;
    position: relative;
    overflow: hidden;
}

/* DELETE */
.btn-delete {
    background: #dc2626;
}

.btn-delete:hover {
    transform: scale(1.05);
}

/* CHECKOUT */
.btn-checkout {
    background: linear-gradient(45deg,#2563eb,#60a5fa);
    display: inline-block;
    margin-top: 15px;
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

/* TOTAL */
.total {
    text-align: right;
    margin-top: 20px;
    font-size: 20px;
    color: #60a5fa;
}

/* EMPTY */
.empty {
    text-align: center;
    padding: 25px;
    color: #94a3b8;
    animation: fadeUp 0.5s ease;
}

/* 🔥 RESPONSIVE */
@media(max-width:768px){
    th, td {
        font-size: 12px;
        padding: 8px;
    }

    .header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}

</style>
</head>

<body>

<div class="container">

<div class="header">
    <h2>🛒 Keranjang</h2>
    <a href="../public/index.php">← Kembali Belanja</a>
</div>

<div class="card">

<table>
<tr>
    <th>Produk</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Aksi</th>
</tr>

<?php
$total = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {

        $data = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
        $p = mysqli_fetch_assoc($data);

        $subtotal = $p['harga'] * $qty;
        $total += $subtotal;
?>

<tr style="animation: fadeUp 0.5s ease;">
    <td><?= $p['nama_produk'] ?></td>
    <td>Rp <?= number_format($p['harga']) ?></td>
    <td><?= $qty ?></td>
    <td>Rp <?= number_format($subtotal) ?></td>
    <td>
        <a href="?hapus=<?= $id ?>" class="btn btn-delete">Hapus</a>
    </td>
</tr>

<?php } } else { ?>
<tr>
    <td colspan="5" class="empty">Keranjang kosong 🛒</td>
</tr>
<?php } ?>

</table>

<div class="total">
    <b>Total: Rp <?= number_format($total) ?></b>
</div>

<?php if (!empty($_SESSION['cart'])) { ?>
<a href="checkout.php" class="btn btn-checkout">Checkout</a>
<?php } ?>

</div>

</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
