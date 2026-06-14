<?php

$host = "sql309.infinityfree.com";
$user = "if0_42169372";
$pass = "muhammadarif26";
$db   = "if0_42169372_vape_store";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

date_default_timezone_set('Asia/Jakarta');

?>