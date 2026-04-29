<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Data Transaksi</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #0f172a;
    color: white;
}

/* TOPBAR */
.topbar {
    display: none;
    background: #020617;
    padding: 12px;
    color: white;
    font-size: 18px;
    align-items: center;
    gap: 10px;
}

.topbar button {
    background: none;
    border: none;
    color: white;
    font-size: 22px;
    cursor: pointer;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    background: #020617;
    height: 100vh;
    padding: 20px;
    position: fixed;
    transition: 0.3s;
}

.sidebar h2 {
    text-align: center;
}

.sidebar a {
    display: block;
    padding: 12px;
    margin: 10px 0;
    color: white;
    text-decoration: none;
    border-radius: 8px;
}

.sidebar a:hover {
    background: #1e293b;
}

.sidebar.active {
    left: 0;
}

/* MAIN */
.main {
    margin-left: 240px;
    padding: 20px;
}

/* CARD */
.card {
    background: #1e293b;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

/* HEADER */
.header-card {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

/* INFO */
.info {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-top: 10px;
}

/* STATUS */
.status {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    color: white;
}

.pending { background: orange; }
.dibayar { background: #16a34a; }
.ditolak { background: #dc2626; }

/* LIST */
ul {
    padding-left: 20px;
}

/* IMAGE */
.bukti img {
    border-radius: 10px;
    max-width: 150px;
}

/* BUTTON */
.btn {
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    margin-right: 5px;
}

.btn-accept { background: #16a34a; }
.btn-reject { background: #dc2626; }

/* RESPONSIVE */
@media (max-width: 768px) {

    .topbar {
        display: flex;
    }

    .sidebar {
        left: -250px;
        top: 0;
        height: 100%;
        z-index: 1000;
    }

    .main {
        margin-left: 0;
        padding: 15px;
    }

    .info {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<!-- TOPBAR (HP) -->
<div class="topbar">
    <button onclick="toggleSidebar()">☰</button>
    <span>💳 Transaksi</span>
</div>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>🔥 Admin</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="products.php">📦 Produk</a>
    <a href="transactions.php">💳 Transaksi</a>
    <a href="chat.php">💬 Chat</a>
    <a href="../proses/logout.php">🚪 Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h2>💳 Data Transaksi</h2>

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

    <!-- HEADER -->
    <div class="header-card">
        <h3>Order #<?= $d['id'] ?></h3>
        <span class="status <?= $d['status'] ?? 'pending' ?>">
            <?= $d['status'] ?? 'pending' ?>
        </span>
    </div>

    <!-- INFO -->
    <div class="info">
        <p><b>Nama:</b> <?= $d['nama_pembeli'] ?></p>
        <p><b>Total:</b> Rp <?= number_format($d['total']) ?></p>
        <p><b>Metode:</b> <?= $d['metode'] ?? '-' ?></p>
        <p><b>📅 Tanggal:</b> <?= date("d M Y H:i", strtotime($d['tanggal'])) ?></p>
    </div>

    <hr>

    <h4>🛒 Detail Produk:</h4>
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
        <div class="bukti">
            <p><b>Bukti Pembayaran:</b></p>
            <img src="../uploads/bukti_pembayaran/<?= $d['bukti'] ?>">
        </div>
    <?php } ?>

    <br>

    <!-- BUTTON -->
    <a href="update_status.php?id=<?= $d['id'] ?>&status=dibayar" class="btn btn-accept">✔ Terima</a>
    <a href="update_status.php?id=<?= $d['id'] ?>&status=ditolak" class="btn btn-reject">❌ Tolak</a>

</div>

<?php } ?>

</div>

<!-- JS -->
<script>
function toggleSidebar() {
    document.querySelector(".sidebar").classList.toggle("active");
}
</script>

</body>
</html>
