<?php
session_start();
include "../config/db.php";

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
$p = mysqli_fetch_assoc($data);
?>

<h2>Edit Produk</h2>

<form action="../proses/proses_update.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $p['id'] ?>">

    <input type="text" name="nama" value="<?= $p['nama_produk'] ?>"><br><br>
    <input type="number" name="harga" value="<?= $p['harga'] ?>"><br><br>
    <input type="number" name="stok" value="<?= $p['stok'] ?>"><br><br>

    <textarea name="deskripsi"><?= $p['deskripsi'] ?></textarea><br><br>

    <img src="../uploads/products/<?= $p['gambar'] ?>" width="80"><br><br>

    <input type="file" name="gambar"><br><br>

    <button type="submit">Update</button>
</form>