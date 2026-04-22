<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>

<h2>Dashboard Admin</h2>
<p>Selamat datang, <?= $_SESSION['admin']; ?></p>

<hr>

<ul>
    <li><a href="products.php">Kelola Produk</a></li>
    <li><a href="transactions.php">Transaksi</a></li>
    <li><a href="chat.php">Chat Customer</a></li>
    <li><a href="../proses/logout.php">Logout</a></li>
</ul>

</body>
</html>