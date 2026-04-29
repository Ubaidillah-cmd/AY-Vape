<?php
include "../config/db.php";

$nama = $_POST['nama'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$deskripsi = $_POST['deskripsi'];
$kategori = $_POST['kategori']; // 🔥 TAMBAHAN

// upload gambar
$gambar = $_FILES['gambar']['name'];
$tmp = $_FILES['gambar']['tmp_name'];

move_uploaded_file($tmp, "../uploads/products/" . $gambar);

// 🔥 FIX QUERY
mysqli_query($conn, "INSERT INTO produk 
(nama_produk, harga, stok, deskripsi, gambar, id_kategori) 
VALUES 
('$nama', '$harga', '$stok', '$deskripsi', '$gambar', '$kategori')");

header("Location: ../admin/products.php");
