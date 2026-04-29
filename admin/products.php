<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include "../config/db.php";

// 🔍 SEARCH & FILTER
$search = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';

// PAGINATION
$limit = 6;
$page = $_GET['page'] ?? 1;
$start = ($page - 1) * $limit;

// QUERY
$where = "WHERE 1=1";

if ($search != "") {
    $where .= " AND produk.nama_produk LIKE '%$search%'";
}

if ($kategori != "") {
    $where .= " AND produk.id_kategori='$kategori'";
}

// DATA
$query = mysqli_query($conn, "
SELECT produk.*, kategori.nama_kategori 
FROM produk
LEFT JOIN kategori ON produk.id_kategori = kategori.id
$where
LIMIT $start,$limit
");

// TOTAL
$total = mysqli_query($conn, "
SELECT COUNT(*) as total 
FROM produk
LEFT JOIN kategori ON produk.id_kategori = kategori.id
$where
");

$t = mysqli_fetch_assoc($total);
$total_pages = ceil($t['total'] / $limit);
?>

<!DOCTYPE html>
<html>
<head>
<title>Produk</title>
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

.sidebar.active {
    left: 0;
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

/* MAIN */
.main {
    margin-left: 240px;
    padding: 20px;
}

/* HEADER */
.header {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

input, select {
    padding: 10px;
    border-radius: 8px;
    border: none;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(230px, 1fr));
    gap: 20px;
}

/* CARD */
.card {
    background: #1e293b;
    border-radius: 14px;
    overflow: hidden;
    transition: 0.3s;
    position: relative;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.5);
}

/* IMAGE */
.card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

/* CONTENT */
.card-body {
    padding: 15px;
}

.card h3 {
    margin: 5px 0;
    font-size: 16px;
}

/* CATEGORY */
.category {
    font-size: 12px;
    color: #94a3b8;
}

/* PRICE */
.price {
    color: #60a5fa;
    font-weight: bold;
    margin-top: 5px;
}

/* STOCK */
.stock {
    font-size: 12px;
    margin-top: 3px;
}

/* BUTTON GROUP */
.btn-group {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}

.btn {
    flex: 1;
    text-align: center;
    padding: 8px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
}

/* EDIT */
.btn-edit {
    background: #2563eb;
    color: white;
}

/* DELETE */
.btn-delete {
    background: #dc2626;
    color: white;
}

/* BADGE STOK */
.badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #2563eb;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
}

/* RESPONSIVE */
@media (max-width: 768px) {

.topbar {
    display: none;
    background: #020617;
    padding: 12px 15px;
    color: white;
    font-size: 18px;

    display: flex;
    justify-content: space-between; /* 🔥 ini kuncinya */
    align-items: center;
}

/* tombol hamburger */
.topbar button {
    background: #1e293b;
    border: none;
    color: white;
    font-size: 20px;
    padding: 6px 10px;
    border-radius: 8px;
    cursor: pointer;
}

.topbar button:hover {
    background: #2563eb;
}

    .sidebar {
        left: -250px;
        top: 0;
        z-index: 1000;
    }

    .main {
        margin-left: 0;
        padding: 15px;
    }

    .grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<!-- TOPBAR -->
<div class="topbar">
    <span>📦 Produk</span>
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

<h2>📦 Produk</h2>

<!-- SEARCH -->
<form method="GET" class="header">
    <input type="text" name="search" placeholder="Cari produk..." value="<?= $search ?>">

    <select name="kategori">
        <option value="">Semua Kategori</option>
        <?php
        $k = mysqli_query($conn, "SELECT * FROM kategori");
        while($row = mysqli_fetch_assoc($k)){
        ?>
        <option value="<?= $row['id']; ?>" <?= ($kategori == $row['id']) ? 'selected' : '' ?>>
            <?= $row['nama_kategori']; ?>
        </option>
        <?php } ?>
    </select>

    <button class="btn">Cari</button>
    <a href="create_product.php" class="btn">+ Tambah</a>
</form>

<!-- GRID -->
<div class="grid">
<?php while ($p = mysqli_fetch_assoc($query)) { ?>
    <div class="card">

        <span class="badge">Stok: <?= $p['stok'] ?></span>

        <img src="../uploads/products/<?= $p['gambar'] ?>">

        <div class="card-body">
            <h3><?= $p['nama_produk'] ?></h3>
            <div class="category"><?= $p['nama_kategori'] ?? '-' ?></div>

            <div class="price">
                Rp <?= number_format($p['harga']) ?>
            </div>

            <div class="stock">
                Sisa: <?= $p['stok'] ?>
            </div>

            <div class="btn-group">
                <a href="update_product.php?id=<?= $p['id'] ?>" class="btn btn-edit">Edit</a>
                <a href="../proses/proses_delete.php?id=<?= $p['id'] ?>" class="btn btn-delete">Hapus</a>
            </div>
        </div>

    </div>
<?php } ?>
</div>

<!-- PAGINATION -->
<div class="pagination">
<?php for ($i=1; $i <= $total_pages; $i++) { ?>
    <a href="?page=<?= $i ?>&search=<?= $search ?>&kategori=<?= $kategori ?>">
        <?= $i ?>
    </a>
<?php } ?>
</div>

</div>

<script>
function toggleSidebar() {
    document.querySelector(".sidebar").classList.toggle("active");
}
</script>

</body>
</html>
