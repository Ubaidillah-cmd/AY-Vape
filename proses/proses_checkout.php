<?php
session_start();
include "../config/db.php";

$nama = $_POST['nama'];
$total = 0;

// hitung total
foreach ($_SESSION['cart'] as $id => $qty) {
    $data = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
    $p = mysqli_fetch_assoc($data);
    $total += $p['harga'] * $qty;
}

// simpan pesanan
mysqli_query($conn, "INSERT INTO pesanan (nama_pembeli, total) VALUES ('$nama', '$total')");
$id_pesanan = mysqli_insert_id($conn);

// simpan detail + update stok
foreach ($_SESSION['cart'] as $id => $qty) {

    mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah) 
    VALUES ('$id_pesanan', '$id', '$qty')");

    mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id=$id");
}

// kosongkan cart
unset($_SESSION['cart']);

header("Location: ../customer/payment.php?id=$id_pesanan");
