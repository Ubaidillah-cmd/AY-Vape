<?php
session_start();
include "../config/db.php";

// cek login admin
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f6fa;
        }
        .container {
            width: 90%;
            margin: auto;
        }
        .card {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            display: inline-block;
        }
        .pending { background: orange; }
        .dibayar { background: green; }
        .ditolak { background: red; }
    </style>
</head>
<body>

<div class="container">
<h2>📦 Data Transaksi</h2>

<?php
$data = mysqli_query($conn, "
    SELECT p.*, pay.status, pay.metode, pay.bukti
    FROM pesanan p
    LEFT JOIN payment pay ON p.id = pay.id_pesanan
    ORDER BY p.id DESC
");

while ($d = mysqli_fetch_assoc($data)) {
?>

<div class="card">

    <h3>Order #<?= $d['id'] ?></h3>

    <p><b>Nama:</b> <?= $d['nama_pembeli'] ?></p>
    <p><b>Total:</b> Rp <?= number_format($d['total']) ?></p>
    <p><b>Metode:</b> <?= $d['metode'] ?? '-' ?></p>

    <p>
        <b>Status:</b>
        <span class="status <?= $d['status'] ?? 'pending' ?>">
            <?= $d['status'] ?? 'pending' ?>
        </span>
    </p>

    <hr>

    <h4>Detail Produk:</h4>

    <ul>
    <?php
    $detail = mysqli_query($conn, "
        SELECT dp.*, pr.nama_produk
        FROM detail_pesanan dp
        JOIN produk pr ON dp.id_produk = pr.id
        WHERE dp.id_pesanan = ".$d['id']
    );

    while ($item = mysqli_fetch_assoc($detail)) {
        echo "<li>{$item['nama_produk']} (x{$item['jumlah']})</li>";
    }
    ?>
    </ul>

    <?php if (!empty($d['bukti'])) { ?>
        <p><b>Bukti Pembayaran:</b></p>
        <img src="../uploads/bukti_pembayaran/<?= $d['bukti'] ?>" width="150">
    <?php } ?>

    <br><br>

    <!-- tombol verifikasi -->
    <a href="update_status.php?id=<?= $d['id'] ?>&status=dibayar">✔ Terima</a> |
    <a href="update_status.php?id=<?= $d['id'] ?>&status=ditolak">❌ Tolak</a>

</div>

<?php } ?>

</div>

</body>
</html>