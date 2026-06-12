<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include "../config/db.php";

$total_pembeli = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pesanan"));
$total_produk  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM produk"));
$total_stok    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total FROM produk"))['total'];
$total_uang    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as total FROM pesanan"))['total'];

$chart = mysqli_query($conn, "
    SELECT DATE_FORMAT(tanggal, '%Y-%m-%d %H:00:00') as tanggal, SUM(total) as total
    FROM pesanan
    GROUP BY DATE_FORMAT(tanggal, '%Y-%m-%d %H:00:00')
    ORDER BY tanggal ASC;
");
$labels = []; $data = [];
while ($c = mysqli_fetch_assoc($chart)) {
    $labels[] = date("d M", strtotime($c['tanggal']));
    $data[]   = $c['total'];
}

$kategoriData = mysqli_query($conn, "
    SELECT k.nama_kategori, COUNT(p.id) as total_produk, SUM(p.stok) as total_stok
    FROM kategori k
    LEFT JOIN produk p ON p.id_kategori = k.id
    GROUP BY k.id
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard — AY Vape Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/main.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.stat-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}
.bottom-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 16px;
}
.side-grid { display: flex; flex-direction: column; gap: 16px; }
.chart-card { padding: 22px; }
.chart-title {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 16px;
}
.calc-trigger {
  padding: 22px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 16px;
}
.calc-trigger-icon {
  width: 48px; height: 48px;
  border-radius: var(--radius-sm);
  background: rgba(6,182,212,0.12);
  border: 1px solid rgba(6,182,212,0.25);
  display: flex; align-items: center; justify-content: center;
  font-size: 22px;
}
.calc-trigger-text h4 {
  font-family: 'Rajdhani', sans-serif;
  font-size: 15px;
  font-weight: 600;
  color: #fff;
  margin-bottom: 2px;
}
.calc-trigger-text p {
  font-size: 12px;
  color: var(--text-muted);
}
@media (max-width: 1024px) {
  .stat-grid  { grid-template-columns: repeat(2, 1fr); }
  .bottom-grid { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
  .stat-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-logo">
      <div class="brand-icon">💨</div>
      <div><div class="brand-name">AY VAPE</div><div class="brand-tagline">Admin Panel</div></div>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Menu Utama</div>
    <a href="dashboard.php"    class="nav-item"><span class="nav-icon">⊞</span> Dashboard</a>
    <a href="products.php"     class="nav-item"><span class="nav-icon">◈</span> Produk</a>
    <a href="transactions.php" class="nav-item"><span class="nav-icon">◎</span> Transaksi</a>
    <a href="chat.php"         class="nav-item"><span class="nav-icon">◷</span> Chat</a>
    <a href="subscribers.php"  class="nav-item"><span class="nav-icon">📧</span> Subscribers</a>
  </nav>
  <div class="sidebar-footer">
    <a href="../proses/logout.php" class="nav-logout"><span class="nav-icon">⏻</span> Logout</a>
  </div>
  <div class="smoke-container">
    <div class="smoke-particle"></div>
    <div class="smoke-particle"></div>
    <div class="smoke-particle"></div>
  </div>
</aside>

<!-- TOPBAR MOBILE -->
<div class="topbar" id="topbar">
  <span class="topbar-title">AY VAPE</span>
  <button class="hamburger" onclick="toggleSidebar()">☰</button>
</div>

<!-- MAIN -->
<main class="main">

  <!-- CLOCK -->
  <div class="clock-widget fade-up">
    <div>
      <div class="clock-time" id="clock-time">00:00:00</div>
      <div class="clock-date" id="clock-date"></div>
    </div>
    <div style="text-align:right;">
      <div style="font-family:'Rajdhani',sans-serif;font-size:11px;letter-spacing:2px;color:var(--text-muted);text-transform:uppercase;">Admin</div>
      <div style="font-family:'Orbitron',monospace;font-size:15px;font-weight:700;color:#fff;"><?= htmlspecialchars($_SESSION['admin']) ?></div>
    </div>
  </div>

  <!-- PAGE HEADER -->
  <div class="page-header fade-up">
    <div class="page-title">Dashboard</div>
    <div class="page-sub">Selamat datang kembali. Berikut ringkasan toko hari ini.</div>
  </div>

  <!-- STAT CARDS -->
  <div class="stat-grid">
    <div class="card stat-card fade-up">
      <div class="stat-icon purple">👥</div>
      <div class="stat-body">
        <div class="stat-label">Total Order</div>
        <div class="stat-value"><?= $total_pembeli ?></div>
      </div>
    </div>
    <div class="card stat-card fade-up">
      <div class="stat-icon cyan">📦</div>
      <div class="stat-body">
        <div class="stat-label">Produk</div>
        <div class="stat-value"><?= $total_produk ?></div>
      </div>
    </div>
    <div class="card stat-card fade-up">
      <div class="stat-icon pink">🗃️</div>
      <div class="stat-body">
        <div class="stat-label">Total Stok</div>
        <div class="stat-value"><?= $total_stok ?? 0 ?></div>
      </div>
    </div>
    <div class="card stat-card fade-up">
      <div class="stat-icon green">💰</div>
      <div class="stat-body">
        <div class="stat-label">Pendapatan</div>
        <div class="stat-value" style="font-size:16px;">Rp <?= number_format($total_uang) ?></div>
      </div>
    </div>
  </div>

  <!-- BOTTOM GRID -->
  <div class="bottom-grid">

    <!-- LINE CHART -->
    <div class="card chart-card fade-up">
      <div class="chart-title">Grafik Penjualan</div>
      <canvas id="salesChart" height="110"></canvas>
    </div>

    <!-- SIDE -->
    <div class="side-grid">

      <!-- KALKULATOR -->
      <div class="card calc-trigger fade-up" onclick="openCalc()">
        <div class="calc-trigger-icon">🧮</div>
        <div class="calc-trigger-text">
          <h4>Kalkulator</h4>
          <p>Klik untuk membuka</p>
        </div>
      </div>

      <!-- BAR CHART -->
      <div class="card chart-card fade-up">
        <div class="chart-title">Kategori Produk</div>
        <canvas id="catChart" height="120"></canvas>
      </div>

    </div>
  </div>

</main>

<!-- CALCULATOR MODAL -->
<div class="modal-overlay" id="calcModal">
  <div class="calc-box">
    <div class="calc-header">
      <span class="calc-title">KALKULATOR</span>
      <button class="calc-close" onclick="closeCalc()">✕</button>
    </div>
    <input type="text" id="calcDisplay" readonly placeholder="0">
    <div class="calc-grid">
      <button class="calc-btn clear" onclick="clearCalc()">C</button>
      <button class="calc-btn op" onclick="delCalc()">⌫</button>
      <button class="calc-btn op" onclick="addCalc('%')">%</button>
      <button class="calc-btn op" onclick="addCalc('/')">÷</button>

      <button class="calc-btn" onclick="addCalc('7')">7</button>
      <button class="calc-btn" onclick="addCalc('8')">8</button>
      <button class="calc-btn" onclick="addCalc('9')">9</button>
      <button class="calc-btn op" onclick="addCalc('*')">×</button>

      <button class="calc-btn" onclick="addCalc('4')">4</button>
      <button class="calc-btn" onclick="addCalc('5')">5</button>
      <button class="calc-btn" onclick="addCalc('6')">6</button>
      <button class="calc-btn op" onclick="addCalc('-')">−</button>

      <button class="calc-btn" onclick="addCalc('1')">1</button>
      <button class="calc-btn" onclick="addCalc('2')">2</button>
      <button class="calc-btn" onclick="addCalc('3')">3</button>
      <button class="calc-btn op" onclick="addCalc('+')">+</button>

      <button class="calc-btn span2" onclick="addCalc('0')">0</button>
      <button class="calc-btn" onclick="addCalc('.')">.</button>
      <button class="calc-btn eq" onclick="hitung()">=</button>
    </div>
  </div>
</div>

<script>
// CLOCK
function updateClock(){
  const now = new Date();
  const t = now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
  const d = now.toLocaleDateString('id-ID', {weekday:'long',year:'numeric',month:'long',day:'numeric'});
  document.getElementById('clock-time').textContent = t;
  document.getElementById('clock-date').textContent = d;
}
setInterval(updateClock, 1000);
updateClock();

// CHARTS
const chartColors = {
  neon: '#6366f1',
  cyan: '#06b6d4',
  pink: '#e879f9',
};

new Chart(document.getElementById('salesChart'), {
  type: 'line',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets:[{
      label: 'Penjualan (Rp)',
      data: <?= json_encode($data) ?>,
      borderColor: chartColors.neon,
      backgroundColor: 'rgba(99,102,241,0.08)',
      borderWidth: 2,
      tension: 0.45,
      fill: true,
      pointBackgroundColor: chartColors.cyan,
      pointBorderColor: 'transparent',
      pointRadius: 4,
    }]
  },
  options: {
    plugins: { legend: { labels: { color:'#64748b', font:{ family:'Rajdhani',size:12 } } } },
    scales: {
      x: { ticks:{ color:'#64748b', font:{family:'Rajdhani'} }, grid:{ color:'rgba(255,255,255,0.04)' } },
      y: { ticks:{ color:'#64748b', font:{family:'Rajdhani'} }, grid:{ color:'rgba(255,255,255,0.04)' } },
    }
  }
});

let kLabels=[],kProduk=[],kStok=[];
<?php while($k = mysqli_fetch_assoc($kategoriData)){ ?>
kLabels.push("<?= $k['nama_kategori'] ?>");
kProduk.push(<?= $k['total_produk'] ?? 0 ?>);
kStok.push(<?= $k['total_stok'] ?? 0 ?>);
<?php } ?>

new Chart(document.getElementById('catChart'), {
  type: 'bar',
  data: {
    labels: kLabels,
    datasets: [
      { label:'Produk', data:kProduk, backgroundColor:'rgba(99,102,241,0.5)', borderColor:chartColors.neon, borderWidth:1 },
      { label:'Stok',   data:kStok,   backgroundColor:'rgba(6,182,212,0.4)',   borderColor:chartColors.cyan, borderWidth:1 },
    ]
  },
  options: {
    plugins: { legend: { labels: { color:'#64748b', font:{family:'Rajdhani',size:11} } } },
    scales: {
      x: { ticks:{ color:'#64748b', font:{family:'Rajdhani'} }, grid:{ display:false } },
      y: { ticks:{ color:'#64748b', font:{family:'Rajdhani'} }, grid:{ color:'rgba(255,255,255,0.04)' } },
    }
  }
});

// CALC
function openCalc() { document.getElementById('calcModal').classList.add('open'); }
function closeCalc(){ document.getElementById('calcModal').classList.remove('open'); }
function addCalc(v) { document.getElementById('calcDisplay').value += v; }
function clearCalc(){ document.getElementById('calcDisplay').value = ''; }
function delCalc()  { let v = document.getElementById('calcDisplay').value; document.getElementById('calcDisplay').value = v.slice(0,-1); }
function hitung()   { try { document.getElementById('calcDisplay').value = eval(document.getElementById('calcDisplay').value); } catch(e) { document.getElementById('calcDisplay').value = 'Error'; } }

// SIDEBAR
function toggleSidebar(){ document.getElementById('sidebar').classList.toggle('open'); document.getElementById('overlay').classList.toggle('active'); }
function closeSidebar()  { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('active'); }
</script>
</body>
</html>