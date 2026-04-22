<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "vape_store";

// koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// set timezone (optional)
date_default_timezone_set('Asia/Jakarta');

?>