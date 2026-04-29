<?php
include "../config/db.php";

$id = $_POST['id'];
$nama = $_POST['nama'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$deskripsi = $_POST['deskripsi'];
$kategori = $_POST['kategori']; // 🔥 TAMBAHAN

$gambar = $_FILES['gambar']['name'];

if ($gambar != "") {
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "../uploads/products/" . $gambar);

    mysqli_query($conn, "UPDATE produk SET 
        nama_produk='$nama',
        harga='$harga',
        stok='$stok',
        deskripsi='$deskripsi',
        gambar='$gambar',
        id_kategori='$kategori'  -- 🔥 TAMBAHAN
        WHERE id=$id
    ");
} else {
    mysqli_query($conn, "UPDATE produk SET 
        nama_produk='$nama',
        harga='$harga',
        stok='$stok',
        deskripsi='$deskripsi',
        id_kategori='$kategori' -- 🔥 TAMBAHAN
        WHERE id=$id
    ");
}

header("Location: ../admin/products.php");
