<?php
session_start();
include "../config/db.php";

if (empty($_SESSION['cart'])) {
    echo "Keranjang kosong!";
    exit;
}

$total = 0;

foreach ($_SESSION['cart'] as $id => $qty) {
    $data = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
    $p = mysqli_fetch_assoc($data);
    $total += $p['harga'] * $qty;
}
?>

<h2>Checkout</h2>

<form action="../proses/proses_checkout.php" method="POST">
    <input type="text" name="nama" placeholder="Nama Pembeli" required><br><br>

    <h3>Total: Rp <?= number_format($total) ?></h3>

    <button type="submit">Checkout</button>
</form>