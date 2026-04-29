<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include "../config/db.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Produk</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #0f172a;
    color: white;
    display: flex;
}

.topbar {
    display: none;
    background: #020617;
    padding: 12px 15px;
    color: white;
    font-size: 18px;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #1e293b;
}

/* tombol hamburger */
.topbar button {
    background: #1e293b;
    border: none;
    color: white;
    font-size: 18px;
    padding: 6px 10px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.topbar button:hover {
    background: #2563eb;
}

/* judul biar lebih halus */
.topbar span {
    font-weight: 500;
    color: #e2e8f0;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    background: #020617;
    height: 100vh;
    padding: 20px;
    position: fixed;
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
    transition: 0.3s;
}

.sidebar a:hover {
    background: #1e293b;
}

/* MAIN */
.main {
    margin-left: 240px;
    padding: 20px;
    width: 100%;
}

/* CARD FORM */
.card {
    background: #1e293b;
    padding: 20px;
    border-radius: 12px;
    max-width: 600px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

/* INPUT */
input, textarea, select {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: none;
    outline: none;
}

/* BUTTON */
.btn {
    background: #2563eb;
    padding: 12px;
    border-radius: 8px;
    color: white;
    border: none;
    cursor: pointer;
    width: 100%;
    transition: 0.3s;
}

.btn:hover {
    background: #1d4ed8;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    body {
        flex-direction: column;
    }

    .topbar {
        display: flex;
    }

    .sidebar {
        position: fixed; /* 🔥 WAJIB */
        left: -250px;
        top: 0;
        height: 100vh;
        width: 220px;
        z-index: 1000;
        transition: 0.3s;
    }

    .sidebar.active {
        left: 0;
    }

    .main {
        margin-left: 0;
    }

    .card {
        width: 100%;
    }
}
</style>
</head>

<body>

<!-- TOPBAR -->
<div class="topbar">
    <span>➕ Tambah Produk</span>
    <button onclick="toggleSidebar()">☰</button>
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

<div class="header">
    <a href="products.php">← Kembali Ke Produk</a>
</div>

<h2>➕ Tambah Produk</h2>

<div class="card">
<form action="../proses/proses_create.php" method="POST" enctype="multipart/form-data">

    <label>Nama Produk</label>
    <input type="text" name="nama" required>

    <label>Harga</label>
    <input type="number" name="harga" required>

    <label>Stok</label>
    <input type="number" name="stok" required>

    <label>Kategori</label>
    <select name="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php
        $k = mysqli_query($conn, "SELECT * FROM kategori");
        while($row = mysqli_fetch_assoc($k)){
        ?>
        <option value="<?= $row['id']; ?>">
            <?= $row['nama_kategori']; ?>
        </option>
        <?php } ?>
    </select>

    <label>Deskripsi</label>
    <textarea name="deskripsi"></textarea>

    <label>Gambar</label>
    <input type="file" name="gambar" required>

    <button type="submit" class="btn">💾 Simpan Produk</button>

</form>
</div>

</div>
<script>
function toggleSidebar() {
    document.querySelector(".sidebar").classList.toggle("active");
}
</script>

</body>
</html>
