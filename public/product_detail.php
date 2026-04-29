<?php
session_start();
include "../config/db.php";

$id = $_GET['id'];

$data = mysqli_query($conn, "
SELECT produk.*, kategori.nama_kategori 
FROM produk
LEFT JOIN kategori ON produk.id_kategori = kategori.id
WHERE produk.id='$id'
");

$p = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Produk</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #0f172a;
    color: white;
}

/* 🔥 TOPBAR CUSTOMER */
.topbar {
    background: #020617;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.topbar a {
    background: #1e293b;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    transition: 0.3s;
}

.topbar a:hover {
    background: #2563eb;
}

/* 🔥 CONTAINER */
.container {
    padding: 20px;
}

/* 🔥 DETAIL */
.detail {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    background: #1e293b;
    padding: 20px;
    border-radius: 12px;
}

/* 🔥 IMAGE ZOOM */
.zoom-container {
    overflow: hidden;
    border-radius: 10px;
    cursor: zoom-in;
}

.zoom-img {
    width: 100%;
    transition: transform 0.4s ease;
}

.zoom-container:hover .zoom-img {
    transform: scale(1.4);
}

/* 🔥 TEXT */
.price {
    color: #60a5fa;
    font-size: 24px;
    font-weight: bold;
}

.category {
    color: #94a3b8;
}

.desc {
    margin-top: 10px;
    line-height: 1.6;
}

/* 🔥 MOBILE */
@media(max-width:768px){
    .detail {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<!-- 🔥 TOPBAR -->
<div class="topbar">
    <a href="index.php">← Kembali</a>
    <span>📦 Detail Produk</span>
</div>

<div class="container">

<div class="detail">

    <!-- GAMBAR -->
    <div class="zoom-container">
        <img src="../uploads/products/<?= $p['gambar'] ?>" class="zoom-img">
    </div>

    <!-- DETAIL -->
    <div>
        <h2><?= $p['nama_produk'] ?></h2>

        <div class="category">
            Kategori: <?= $p['nama_kategori'] ?>
        </div>

        <div class="price">
            Rp <?= number_format($p['harga']) ?>
        </div>

        <p>Stok: <?= $p['stok'] ?></p>

        <div class="desc">
            <?= $p['deskripsi'] ?: 'Tidak ada deskripsi.' ?>
        </div>
    </div>

</div>

</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>