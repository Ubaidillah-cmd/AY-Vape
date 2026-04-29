<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include "../config/db.php";

$id = $_GET['id'];

// 🔥 ambil data + kategori
$data = mysqli_query($conn, "
SELECT produk.*, kategori.id as id_kategori 
FROM produk
LEFT JOIN kategori ON produk.id_kategori = kategori.id
WHERE produk.id='$id'
");

$p = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Produk</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #0f172a;
    color: white;
    display: flex;
}

/* 🔥 TOPBAR */
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

.topbar button {
    background: #1e293b;
    border: none;
    color: white;
    font-size: 18px;
    padding: 6px 10px;
    border-radius: 8px;
    cursor: pointer;
}

.topbar button:hover {
    background: #2563eb;
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

/* CARD */
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

/* IMAGE */
.preview {
    text-align: center;
}

.preview img {
    border-radius: 10px;
    max-width: 120px;
    margin-bottom: 10px;
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
        position: fixed;
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
        padding: 15px;
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
    <span>✏️ Edit Produk</span>
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
<h2>✏️ Edit Produk</h2>

<div class="card">
<form action="../proses/proses_update.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $p['id'] ?>">

    <label>Nama Produk</label>
    <input type="text" name="nama" value="<?= $p['nama_produk'] ?>" required>

    <label>Harga</label>
    <input type="number" name="harga" value="<?= $p['harga'] ?>" required>

    <label>Stok</label>
    <input type="number" name="stok" value="<?= $p['stok'] ?>" required>

    <label>Kategori</label>
    <select name="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <?php
        $k = mysqli_query($conn, "SELECT * FROM kategori");
        while($row = mysqli_fetch_assoc($k)){
        ?>
        <option value="<?= $row['id']; ?>" 
            <?= ($p['id_kategori'] == $row['id']) ? 'selected' : '' ?>>
            <?= $row['nama_kategori']; ?>
        </option>
        <?php } ?>
    </select>

    <label>Deskripsi</label>
    <textarea name="deskripsi"><?= $p['deskripsi'] ?></textarea>

    <div class="preview">
        <p>Gambar Saat Ini:</p>
        <img src="../uploads/products/<?= $p['gambar'] ?>">
    </div>

    <label>Ganti Gambar (opsional)</label>
    <input type="file" name="gambar">

    <button type="submit" class="btn">💾 Update Produk</button>

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
