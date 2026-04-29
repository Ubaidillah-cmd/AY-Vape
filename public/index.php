<?php
session_start();
include "../config/db.php";

$jumlah = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

// 🔍 ambil input
$search = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';

// 🔧 query dasar
$where = "WHERE 1=1";

if ($search != "") {
    $where .= " AND nama_produk LIKE '%$search%'";
}

if ($kategori != "") {
    $where .= " AND id_kategori='$kategori'";
}

// 🔥 query produk
$data = mysqli_query($conn, "
SELECT produk.*, kategori.nama_kategori
FROM produk
LEFT JOIN kategori ON produk.id_kategori = kategori.id
$where
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Vape Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #0f172a;
    color: white;
}

/* HEADER */
.header {
    background: #020617;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-btn {
    background: #2563eb;
    padding: 10px 15px;
    border-radius: 8px;
    color: white;
    text-decoration: none;
}

/* CONTAINER */
.container {
    padding: 20px;
}

/* FILTER */
.filter {
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

.btn {
    background: #2563eb;
    padding: 10px 15px;
    border-radius: 8px;
    transform: scale(1.05);
    color: white;
    border: none;
    cursor: pointer;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

/* CARD */
.card {
    position: relative;
    background: rgba(30,41,59,0.6);
    border-radius: 14px;
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.05);
    padding: 15px;
    transition: 0.3s;
    overflow: hidden;
    cursor: pointer;
}

.card:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
}

/* 🔥 LINK OVERLAY */
.card-link {
    position: absolute;
    inset: 0;
    z-index: 1;
}

/* 🔥 BIAR ISI DI ATAS LINK */
.card img,
.card h3,
.card .category,
.card .price,
.card p,
.card button {
    position: relative;
    z-index: 2;
}

.card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
    transition: 0.3s;
}

.price {
    color: #60a5fa;
    font-weight: bold;
}

.category {
    font-size: 12px;
    opacity: 0.7;
}

/* 🔥 BUTTON FIX */
.card button {
    cursor: pointer;
}

.card:hover img {
    transform: scale(1.05);
}

.card::after {
    content: "Lihat Detail";
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(37,99,235,0.9);
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 11px;
    opacity: 0;
    transition: 0.3s;
    z-index: 2;
}

.card:hover::after {
    opacity: 1;
}

/* 🔥 CONTACT SECTION */
.contact-section {
    margin-top: 50px;
    padding: 30px;
    background: linear-gradient(135deg,#020617,#1e293b);
    border-radius: 12px;
    text-align: center;
}

.contact-section h2 {
    margin-bottom: 10px;
}

.contact-section p {
    color: #94a3b8;
    margin-bottom: 25px;
}

/* GRID */
.contact-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

/* CARD */
.contact-card {
    background: rgba(30,41,59,0.6);
    padding: 20px;
    border-radius: 14px;
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.05);
    transition: 0.3s;
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
}

.contact-card h3 {
    margin-bottom: 8px;
}

/* 🔥 RESPONSIVE */
@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }

    .contact-section {
        padding: 20px;
    }
}

/* RESPONSIVE */
@media (max-width: 1024px) {
    .grid { grid-template-columns: repeat(3, 1fr); }
}

@media (max-width: 768px) {
    .grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 480px) {
    .grid { grid-template-columns: 1fr; }
}
</style>

<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <h2>AY Vape</h2>
    
        <a href="../customer/cart.php" class="cart-btn">🛒 Keranjang(<?= $jumlah ?>)</a>
    </div>

    <div class="container">

        <!-- 🔍 FILTER -->
        <form method="GET" class="filter">

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

        </form>

        <!-- GRID -->
        <div class="grid">

            <?php while ($p = mysqli_fetch_assoc($data)) { ?>
            <div class="card" data-aos="fade-up" data-aos-delay="100" onclick="goDetail(<?= $p['id'] ?>)">
                <!-- LINK KE DETAIL -->
                <a href="product_detail.php?id=<?= $p['id'] ?>" class="card-link"></a>

                <img src="../uploads/products/<?= $p['gambar'] ?>">

                <h3><?= $p['nama_produk'] ?></h3>

                <div class="category"><?= $p['nama_kategori'] ?? 'Tanpa kategori' ?></div>

                <div class="price">Rp <?= number_format($p['harga']) ?></div>

                <p>Stok: <?= $p['stok'] ?></p>

                <button onclick="event.stopPropagation(); addToCart(<?= $p['id'] ?>)" class="btn">
                    + Keranjang
                </button>
            </div>
            <?php } ?>
        </div>

        <!-- 🔥 HUBUNGI KAMI -->
        <div class="contact-section" data-aos="fade-up">

            <h2>📞 Hubungi Kami</h2>
            <p>Kami siap membantu kebutuhan vape kamu 🔥</p>

            <div class="contact-grid" data-aos="zoom-in">

                <div class="contact-card" data-aos="zoom-in" data-aos-delay="100">
                    <h3>📍 Alamat</h3>
                    <p>Masjid Jami Al-Muttaqiin, RP7J+9C9, Jl. MT. Haryono, Kalikotok, Jl. MT. Haryono No.46, Sumber Beringin, Karangrejo, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68124</p>
                </div>

                <div class="contact-card" data-aos="zoom-in" data-aos-delay="200">
                    <h3>📱 WhatsApp</h3>
                    <p>+62 823-3340-8651</p>
                </div>

                <div class="contact-card" data-aos="zoom-in" data-aos-delay="300">
                    <h3>📧 Email</h3>
                    <p>rahmanadur7511@gmail.com</p>
                </div>

            </div>

        </div>
    </div>
<script>
function addToCart(id) {

    fetch("../ajax/add_to_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id
    })
    .then(res => res.json())
    .then(data => {

        // 🔔 notifikasi
        showNotif(data.msg);

    });
}

// 🔥 NOTIFIKASI KEREN
function showNotif(text) {

    let notif = document.createElement("div");
    notif.innerText = text;

    notif.style.position = "fixed";
    notif.style.bottom = "20px";
    notif.style.right = "20px";
    notif.style.background = "#16a34a";
    notif.style.color = "white";
    notif.style.padding = "12px 18px";
    notif.style.borderRadius = "10px";
    notif.style.boxShadow = "0 5px 15px rgba(0,0,0,0.3)";
    notif.style.zIndex = "999";

    document.body.appendChild(notif);

    setTimeout(() => {
        notif.remove();
    }, 2000);
}
</script>

<script>
function goDetail(id){
    window.location.href = "product_detail.php?id=" + id;
}
</script>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({
    duration: 800,
    once: true
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
