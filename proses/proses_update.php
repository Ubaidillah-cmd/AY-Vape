<?php
// ============================================================
//  proses/proses_update.php
//  Update produk dengan harga_beli dan harga_jual
// ============================================================

session_start();
if (!isset($_SESSION['login'])) { header("Location: ../admin/login.php"); exit; }
include "../config/db.php";

$id         = (int)$_POST['id'];
$nama       = mysqli_real_escape_string($conn, $_POST['nama']       ?? '');
$harga      = (int)($_POST['harga']      ?? 0);   // harga jual
$harga_beli = (int)($_POST['harga_beli'] ?? 0);   // harga beli / modal
$stok       = (int)($_POST['stok']       ?? 0);
$kategori   = (int)($_POST['kategori']   ?? 0);
$deskripsi  = mysqli_real_escape_string($conn, $_POST['deskripsi'] ?? '');

// Ambil gambar lama
$oldQ   = mysqli_query($conn, "SELECT gambar FROM produk WHERE id='$id'");
$oldP   = mysqli_fetch_assoc($oldQ);
$gambar = $oldP['gambar'] ?? '';

// Ganti gambar jika ada upload baru
if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === 0) {
    $ext     = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','gif'];
    if (in_array($ext, $allowed)) {
        $newFile = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['gambar']['name']));
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/products/" . $newFile);

        // Hapus gambar lama
        if ($gambar && file_exists("../uploads/products/" . $gambar)) {
            unlink("../uploads/products/" . $gambar);
        }
        $gambar = $newFile;
    }
}

$gambarSafe = mysqli_real_escape_string($conn, $gambar);

mysqli_query($conn, "
    UPDATE produk SET
        nama_produk = '$nama',
        harga       = '$harga',
        harga_beli  = '$harga_beli',
        stok        = '$stok',
        id_kategori = '$kategori',
        deskripsi   = '$deskripsi',
        gambar      = '$gambarSafe'
    WHERE id = '$id'
");

header("Location: ../admin/products.php");
exit;