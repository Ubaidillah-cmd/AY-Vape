<?php
// ============================================================
//  proses/proses_create.php
//  Simpan produk baru dengan harga_beli dan harga_jual
// ============================================================

session_start();
if (!isset($_SESSION['login'])) { header("Location: ../admin/login.php"); exit; }
include "../config/db.php";

$nama       = mysqli_real_escape_string($conn, $_POST['nama']       ?? '');
$harga      = (int)($_POST['harga']      ?? 0);   // harga jual
$harga_beli = (int)($_POST['harga_beli'] ?? 0);   // harga beli / modal
$stok       = (int)($_POST['stok']       ?? 0);
$kategori   = (int)($_POST['kategori']   ?? 0);
$deskripsi  = mysqli_real_escape_string($conn, $_POST['deskripsi'] ?? '');

// Upload gambar
$gambar = '';
if (!empty($_FILES['gambar']['name'])) {
    $ext     = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','gif'];
    if (in_array($ext, $allowed) && $_FILES['gambar']['error'] === 0) {
        $gambar = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['gambar']['name']));
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/products/" . $gambar);
    }
}

mysqli_query($conn, "
    INSERT INTO produk (nama_produk, harga, harga_beli, stok, id_kategori, deskripsi, gambar)
    VALUES ('$nama', '$harga', '$harga_beli', '$stok', '$kategori', '$deskripsi', '$gambar')
");

header("Location: ../admin/products.php");
exit;