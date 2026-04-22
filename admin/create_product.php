<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<h2>Tambah Produk</h2>

<form action="../proses/proses_create.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="nama" placeholder="Nama Produk" required><br><br>
    <input type="number" name="harga" placeholder="Harga" required><br><br>
    <input type="number" name="stok" placeholder="Stok" required><br><br>
    <textarea name="deskripsi" placeholder="Deskripsi"></textarea><br><br>
    <input type="file" name="gambar" required><br><br>
    <button type="submit">Simpan</button>
</form>