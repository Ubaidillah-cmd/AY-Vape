<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include "../config/db.php";

// 🔥 DATA DUMMY (nanti bisa kamu ganti query asli)
$total_pembeli = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pesanan"));
$total_produk = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produk"));
$total_stok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total FROM produk"))['total'];
$total_uang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as total FROM pesanan"))['total'];

// 🔥 AMBIL DATA PENJUALAN PER HARI
$chart = mysqli_query($conn, "
    SELECT DATE_FORMAT(tanggal, '%Y-%m-%d %H:00:00') as tanggal, SUM(total) as total
    FROM pesanan
    GROUP BY DATE_FORMAT(tanggal, '%Y-%m-%d %H:00:00')
    ORDER BY tanggal ASC;
");

$labels = [];
$data = [];

while($c = mysqli_fetch_assoc($chart)){
    $labels[] = date("d M", strtotime($c['tanggal']));
    $data[] = $c['total'];
}

// 🔥 DATA KATEGORI DINAMIS
$kategoriData = mysqli_query($conn, "
    SELECT 
        k.nama_kategori,
        COUNT(p.id) as total_produk,
        SUM(p.stok) as total_stok
    FROM kategori k
    LEFT JOIN produk p ON p.id_kategori = k.id
    GROUP BY k.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #0f172a;
    color: white;
    display: flex;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    background: #020617;
    height: 100vh;
    padding: 20px;
    position: fixed;
    transition: 0.3s;
    z-index: 999;
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
    transform: translateX(5px);
}

/* MAIN */
.main {
    margin-left: 260px;
    padding: 20px;
    width: 100%;
    transition: 0.3s;
}

/* TOPBAR */
.topbar {
    display: none;
    background: #020617;
    padding: 15px;
    font-size: 20px;
}

/* GRID */
.grid-top {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.grid-bottom {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

/* CARD PREMIUM */
.card {
    background: #1e293b;
    padding: 18px;
    border-radius: 14px;
    transition: all 0.3s ease;
    animation: fadeUp 0.5s ease;
}

.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.4);
}

/* TEXT */
.title {
    font-size: 14px;
    color: #94a3b8;
}

.value {
    font-size: 24px;
    font-weight: bold;
}

/* CLOCK */
.clock-card {
    background: #1e293b;
    padding: 15px;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 20px;
}

#clock {
    font-size: 16px;
}

/* ANIMASI */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* CALCULATOR */
.calc-modal {
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.7);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.calc-box {
    background:#020617;
    padding:20px;
    border-radius:12px;
    width:300px;
}

.calc-header {
    display:flex;
    justify-content:space-between;
    margin-bottom:10px;
}

.calc-header span {
    cursor:pointer;
}

#calcDisplay {
    width:100%;
    padding:12px;
    border-radius:8px;
    border:none;
    margin-bottom:10px;
    text-align:right;
}

.calc-buttons {
    display:grid;
    grid-template-columns: repeat(4,1fr);
    gap:10px;
}

.calc-buttons button {
    padding:12px;
    border:none;
    border-radius:8px;
    background:#1e293b;
    color:white;
    cursor:pointer;
}

.calc-buttons button:hover {
    background:#2563eb;
}

/* RESPONSIVE TABLET */
@media(max-width:1024px){
    .grid-top {
        grid-template-columns: repeat(2,1fr);
    }

    .grid-bottom {
        grid-template-columns: 1fr;
    }
}

/* RESPONSIVE HP */
@media(max-width:768px){

    body {
        flex-direction: column;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar {
        left: -260px;
    }

    .sidebar.active {
        left: 0;
    }

    .main {
        margin-left: 0;
    }

    .grid-top {
        grid-template-columns: 1fr;
    }

    .grid-bottom {
        grid-template-columns: 1fr;
    }
}

</style>

</head>
<body>

<div class="topbar">
    <span>📊 Dashboard</span>
    <button onclick="toggleSidebar()">☰</button>
</div>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Admin AY Vape</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="products.php">📦 Produk</a>
    <a href="transactions.php">💳 Transaksi</a>
    <a href="chat.php">💬 Chat</a>
    <a href="../proses/logout.php">🚪 Logout</a>
</div>

<!-- MAIN -->
<div class="main">

    <div class="clock-card">
        <div id="clock"></div>
    </div>

    <p id="clock"></p>
    <h2>Dashboard</h2>
    <p>Selamat datang, <b><?= $_SESSION['admin']; ?></b></p>

    <!-- 🔥 TOP CARDS -->
    <div class="grid-top">

        <div class="card">
            <div class="title">Total Pembeli</div>
            <div class="value"><?= $total_pembeli ?></div>
        </div>

        <div class="card">
            <div class="title">Total Produk Terjual</div>
            <div class="value"><?= $total_produk ?></div>
        </div>

        <div class="card">
            <div class="title">Total Stok Produk</div>
            <div class="value"><?= $total_stok ?? 0 ?></div>
        </div>

        <div class="card">
            <div class="title">Total Pendapatan</div>
            <div class="value">Rp <?= number_format($total_uang) ?></div>
        </div>

    </div>

    <!-- 🔥 BOTTOM -->
    <div class="grid-bottom">

        <!-- GRAFIK -->
        <div class="card">
            <h3>Grafik Penjualan</h3>
            <canvas id="chart"></canvas>
        </div>

        <!-- KANAN -->
        <div style="display:flex; flex-direction:column; gap:20px;">

            <!-- 🔥 KALKULATOR -->
            <div class="card" onclick="openCalc()" style="cursor:pointer;">
                <h3>🧮 Kalkulator</h3>
                <p style="color:#94a3b8;">Klik untuk membuka kalkulator</p>
            </div>

            <!-- KATEGORI -->
            <div class="card">
                <h3>Kategori</h3>
                <canvas id="pie"></canvas>
            </div>

        </div>

    </div>

</div>

<div id="calcModal" class="calc-modal">
    <div class="calc-box">

        <div class="calc-header">
            Kalkulator
            <span onclick="closeCalc()">✖</span>
        </div>

        <input type="text" id="calcDisplay" readonly>

        <div class="calc-buttons">
            <button onclick="clearCalc()">C</button>
            <button onclick="addCalc('/')">÷</button>
            <button onclick="addCalc('*')">×</button>
            <button onclick="delCalc()">⌫</button>

            <button onclick="addCalc('7')">7</button>
            <button onclick="addCalc('8')">8</button>
            <button onclick="addCalc('9')">9</button>
            <button onclick="addCalc('-')">-</button>

            <button onclick="addCalc('4')">4</button>
            <button onclick="addCalc('5')">5</button>
            <button onclick="addCalc('6')">6</button>
            <button onclick="addCalc('+')">+</button>

            <button onclick="addCalc('1')">1</button>
            <button onclick="addCalc('2')">2</button>
            <button onclick="addCalc('3')">3</button>
            <button onclick="hitung()" class="equal">=</button>

            <button onclick="addCalc('0')" style="grid-column: span 2;">0</button>
            <button onclick="addCalc('.')">.</button>
        </div>

    </div>
</div>

<script>
function updateClock(){
    let now = new Date();

    let options = {
        timeZone: "Asia/Jakarta",
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit"
    };

    document.getElementById("clock").innerText =
        now.toLocaleString("id-ID", options);
}

setInterval(updateClock, 1000);
updateClock();
</script>

<script>
let labels = <?= json_encode($labels) ?>;
let dataPenjualan = <?= json_encode($data) ?>;

// 🔥 LINE CHART (PENJUALAN)
new Chart(document.getElementById("chart"), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: "Penjualan (Rp)",
            data: dataPenjualan,
            borderWidth: 3,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        plugins: {
            legend: {
                labels: { color: "white" }
            }
        },
        scales: {
            x: {
                ticks: { color: "white" }
            },
            y: {
                ticks: { color: "white" }
            }
        }
    }
});


// 🔥 DATA KATEGORI (PRODUK + STOK)
let kategoriLabels = [];
let kategoriProduk = [];
let kategoriStok = [];

<?php while($k = mysqli_fetch_assoc($kategoriData)){ ?>
    kategoriLabels.push("<?= $k['nama_kategori'] ?>");
    kategoriProduk.push(<?= $k['total_produk'] ?? 0 ?>);
    kategoriStok.push(<?= $k['total_stok'] ?? 0 ?>);
<?php } ?>

// 🔥 BAR CHART (LEBIH JELAS DARI PIE)
new Chart(document.getElementById("pie"), {
    type: 'bar',
    data: {
        labels: kategoriLabels,
        datasets: [
            {
                label: "Jumlah Produk",
                data: kategoriProduk
            },
            {
                label: "Total Stok",
                data: kategoriStok
            }
        ]
    },
    options: {
        plugins: {
            legend: {
                labels: { color: "white" }
            }
        },
        scales: {
            x: {
                ticks: { color: "white" }
            },
            y: {
                ticks: { color: "white" }
            }
        }
    }
});

const ctx = document.getElementById("chart").getContext("2d");
const gradient = ctx.createLinearGradient(0,0,0,300);
gradient.addColorStop(0,"rgba(37,99,235,0.5)");
gradient.addColorStop(1,"rgba(37,99,235,0)");

datasets: [{
    label: "Penjualan (Rp)",
    data: dataPenjualan,
    borderWidth: 3,
    tension: 0.4,
    fill: true,
    backgroundColor: gradient
}]

</script>

<script>
function openCalc(){
    document.getElementById("calcModal").style.display = "flex";
}

function closeCalc(){
    document.getElementById("calcModal").style.display = "none";
}

function addCalc(val){
    document.getElementById("calcDisplay").value += val;
}

function clearCalc(){
    document.getElementById("calcDisplay").value = "";
}

function delCalc(){
    let val = document.getElementById("calcDisplay").value;
    document.getElementById("calcDisplay").value = val.slice(0,-1);
}

function hitung(){
    try{
        let result = eval(document.getElementById("calcDisplay").value);
        document.getElementById("calcDisplay").value = result;
    }catch{
        alert("Error");
    }
}
</script>

<!-- SIDEBAR TOGGLE -->
<script>
function toggleSidebar() {
    document.querySelector(".sidebar").classList.toggle("active");
}
</script>

</body>
</html>
