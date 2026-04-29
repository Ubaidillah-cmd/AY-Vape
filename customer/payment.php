<?php
session_start();
include "../config/db.php";

$id = $_GET['id'] ?? 0;
$metode = $_GET['metode'] ?? "";

// ambil data pesanan
$data = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$id'");
$p = mysqli_fetch_assoc($data);

if (!$p) {
    echo "<h2 style='color:white;text-align:center'>Data tidak ditemukan</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Pembayaran</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: radial-gradient(circle at top, #020617, #0f172a);
    color: white;
}

/* 🔥 ANIMASI MASUK */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* CONTAINER */
.container {
    max-width: 600px;
    margin: auto;
    padding: 30px 20px;
    animation: fadeUp 0.7s ease;
}

/* 🔥 CARD PREMIUM */
.card {
    background: rgba(30,41,59,0.65);
    backdrop-filter: blur(14px);
    padding: 22px;
    border-radius: 16px;
    margin-bottom: 20px;
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    animation: fadeUp 0.6s ease;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-4px);
}

/* TEXT */
h2, h3 {
    margin-top: 0;
}

/* INPUT */
select, input {
    width: 100%;
    padding: 13px;
    border-radius: 10px;
    border: none;
    margin-top: 12px;
    background: #020617;
    color: white;
    outline: none;
    transition: 0.3s;
}

select:focus, input:focus {
    box-shadow: 0 0 0 2px #2563eb;
}

/* 🔥 BUTTON */
.btn {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(45deg,#2563eb,#60a5fa);
    color: white;
    cursor: pointer;
    margin-top: 18px;
    transition: 0.3s;
    position: relative;
    overflow: hidden;
}

/* SHINE EFFECT */
.btn::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(120deg,transparent,rgba(255,255,255,0.4),transparent);
    top: 0;
    left: -100%;
    transition: 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: scale(1.05);
}

/* 🔥 QR BOX */
.qr-box {
    text-align: center;
}

.qr-box img {
    width: 200px;
    border-radius: 12px;
    margin-top: 10px;
    transition: 0.3s;
}

.qr-box img:hover {
    transform: scale(1.05);
}

/* INFO */
.info {
    font-size: 14px;
    margin-top: 10px;
    color: #94a3b8;
}

/* NOTE */
.note {
    font-size: 12px;
    color: #f87171;
}

/* 🔥 TOTAL STYLE */
.total-highlight {
    font-size: 26px;
    font-weight: bold;
    color: #60a5fa;
    margin-top: 10px;
}

/* 🔥 RESPONSIVE */
@media(max-width:768px){
    .container {
        padding: 20px;
    }
}

</style>

</head>
<body>

<div class="container">

<!-- 🔥 INFO PESANAN -->
<div class="card" style="animation-delay:0.1s;">
    <h2>💳 Pembayaran</h2>
    <p><b>Nama:</b> <?= $p['nama_pembeli'] ?></p>
    <div class="total-highlight">
        Rp <?= number_format($p['total']) ?>
    </div>
</div>

<!-- 🔥 PILIH METODE -->
<div class="card" style="animation-delay:0.2s;">
    <h3>Pilih Metode</h3>

    <form method="GET">
        <input type="hidden" name="id" value="<?= $id ?>">

        <select name="metode" onchange="this.form.submit()" required>
            <option value="">-- Pilih Metode --</option>
            <option value="transfer" <?= $metode=="transfer"?"selected":"" ?>>Transfer (QRIS)</option>
            <option value="cod" <?= $metode=="cod"?"selected":"" ?>>Cash</option>
        </select>
    </form>
</div>

<!-- 🔥 QRIS -->
<?php if ($metode == "transfer") { ?>
<div class="card qr-box" style="animation-delay:0.3s;">

    <h3>Scan QRIS</h3>

    <img src="../assets/img/qris.jpeg">

    <p class="info">Scan pakai DANA, OVO, GoPay, atau Mobile Banking</p>

    <h2>Rp <?= number_format($p['total']) ?></h2>

    <p class="note">*Pastikan nominal sesuai</p>

</div>
<?php } ?>

<!-- 🔥 FORM -->
<div class="card" style="animation-delay:0.4s;">
    <form action="../proses/proses_payment.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id_pesanan" value="<?= $id ?>">
        <input type="hidden" name="metode" value="<?= $metode ?>">

        <?php if ($metode == "transfer") { ?>
            <label>Upload Bukti Pembayaran</label>
            <input type="file" name="bukti" required>
        <?php } ?>

        <button class="btn">Kirim Pembayaran</button>
    </form>
</div>

</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
