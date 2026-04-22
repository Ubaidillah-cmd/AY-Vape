<?php
session_start();
include "../config/db.php";
?>

<h2>Vape Store</h2>
<a href="../customer/cart.php">🛒 Lihat Keranjang</a>
<hr>

<?php
$data = mysqli_query($conn, "SELECT * FROM produk");

while ($p = mysqli_fetch_assoc($data)) {
?>

<div style="border:1px solid #ccc; padding:10px; width:200px; display:inline-block; margin:10px;">
    <img src="../uploads/products/<?= $p['gambar'] ?>" width="100"><br>
    <b><?= $p['nama_produk'] ?></b><br>
    Rp <?= number_format($p['harga']) ?><br>
    Stok: <?= $p['stok'] ?><br><br>

    <a href="../customer/cart.php?id=<?= $p['id'] ?>">Tambah ke Keranjang</a>
</div>

<?php } ?>