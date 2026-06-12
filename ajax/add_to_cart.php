<?php
session_start();

$id = $_POST['id'];

// buat cart jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// jika produk sudah ada → tambah qty
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] += 1;
} else {
    $_SESSION['cart'][$id] = 1;
}

echo json_encode([
    "status" => "success",
    "msg" => "Produk masuk ke keranjang!"
]);