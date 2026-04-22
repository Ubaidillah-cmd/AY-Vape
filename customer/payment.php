<?php
session_start();
include "../config/db.php";

$id = $_GET['id'];
$metode = $_GET['metode'] ?? "";

// ambil data pesanan
$data = mysqli_query($conn, "SELECT * FROM pesanan WHERE id=$id");
$p = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>

    <style>
    body {
        font-family: Arial;
        background: #f5f6fa;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 500px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
    }

    select, input, button {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
    }

    .payment-box {
        margin-top: 20px;
        padding: 20px;
        border-radius: 10px;
        background: #fafafa;
        text-align: center;
        border: 1px solid #ddd;
    }

    .qr-container {
        background: #eee;
        padding: 15px;
        border-radius: 10px;
        display: inline-block;
    }

    .total {
        margin-top: 10px;
        color: gray;
    }

    .info {
        text-align: left;
        margin-top: 10px;
        font-size: 14px;
    }

    .note {
        font-size: 12px;
        color: red;
        margin-top: 10px;
    }
    </style>

</head>
<body>

<div class="container">

<h2>Pembayaran</h2>

<p><b>Nama:</b> <?= $p['nama_pembeli'] ?></p>
<p><b>Total:</b> Rp <?= number_format($p['total']) ?></p>

<hr>

<h3>Pilih Metode Pembayaran</h3>

<!-- 🔥 PILIH METODE (AUTO RELOAD) -->
<form method="GET">
    <input type="hidden" name="id" value="<?= $id ?>">

    <select name="metode" onchange="this.form.submit()" required>
        <option value="">-- Pilih --</option>
        <option value="transfer" <?= $metode=="transfer"?"selected":"" ?>>Transfer</option>
        <option value="cod" <?= $metode=="cod"?"selected":"" ?>>COD</option>
    </select>
</form>

<!-- 🔥 QR PROFESSIONAL -->
<?php if ($metode == "transfer") { ?>
<div class="payment-box">

    <h3>Pembayaran QRIS</h3>

    <div class="qr-container">
        <img src="../assets/img/qris.jpeg" width="200">
    </div>

    <p class="total">Total Bayar</p>
    <h2>Rp <?= number_format($p['total']) ?></h2>

    <div class="info">
        <p>✔ Scan QR menggunakan:</p>
        <ul>
            <li>DANA</li>
            <li>OVO</li>
            <li>GoPay</li>
            <li>Mobile Banking</li>
        </ul>
    </div>

    <p class="note">*Pastikan nominal sesuai sebelum melakukan pembayaran</p>

</div>
<?php } ?>

<hr>

<!-- 🔥 FORM KIRIM -->
<form action="../proses/proses_payment.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id_pesanan" value="<?= $id ?>">
    <input type="hidden" name="metode" value="<?= $metode ?>">

    <?php if ($metode == "transfer") { ?>
        <p>Upload Bukti Pembayaran:</p>
        <input type="file" name="bukti" required>
    <?php } ?>

    <button type="submit">Kirim Pembayaran</button>

</form