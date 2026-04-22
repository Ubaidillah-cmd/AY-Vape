<?php
include "../config/db.php";

$nama = $_POST['nama'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$deskripsi = $_POST['deskripsi'];

// upload gambar
$gambar = $_FILES['gambar']['name'];
$tmp = $_FILES['gambar']['tmp_name'];

move_uploaded_file($tmp, "../uploads/products/" . $gambar);

mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok, deskripsi, gambar) 
VALUES ('$nama', '$harga', '$stok', '$deskripsi', '$gambar')");

header("Location: ../admin/products.php");