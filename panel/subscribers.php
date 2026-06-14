<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
include "../config/db.php";

// ══════════════════════════════════════════════════
//  EXPORT CSV — WAJIB DI ATAS SEBELUM ADA OUTPUT
// ══════════════════════════════════════════════════
if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="subscribers_ayvape_' . date('Ymd_His') . '.csv"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');

    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 agar Excel tidak rusak

    fputcsv($out, ['No', 'Email', 'Dari Halaman', 'Device', 'IP Address', 'Waktu Subscribe']);

    $all = mysqli_query($conn, "SELECT * FROM subscribers ORDER BY id DESC");
    $n   = 1;
    while ($r = mysqli_fetch_assoc($all)) {
        $ua  = $r['user_agent'] ?? '';
        $dev = 'Desktop';
        if (preg_match('/Mobile|Android|iPhone/i', $ua)) $dev = 'Mobile';
        if (preg_match('/Tablet|iPad/i', $ua))           $dev = 'Tablet';
        fputcsv($out, [
            $n++,
            $r['email'],
            $r['halaman']    ?? '-',
            $dev,
            $r['ip_address'] ?? '-',
            $r['created_at'],
        ]);
    }
    fclose($out);
    exit;
}

// ══════════════════════════════════════════════════
//  HANDLE HAPUS SINGLE
// ══════════════════════════════════════════════════
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $hid = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM subscribers WHERE id='$hid'");
    header("Location: subscribers.php?deleted=1");
    exit;
}

// ══════════════════════════════════════════════════
//  HANDLE HAPUS SEMUA
// ══════════════════════════════════════════════════
if (isset($_POST['hapus_semua'])) {
    mysqli_query($conn, "TRUNCATE TABLE subscribers");
    header("Location: subscribers.php?cleared=1");
    exit;
}

// ══════════════════════════════════════════════════
//  STATS
// ══════════════════════════════════════════════════
$total    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM subscribers"))['t'];
$today    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM subscribers WHERE DATE(created_at)=CURDATE()"))['t'];
$thisWeek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM subscribers WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"))['t'];

// ══════════════════════════════════════════════════
//  SEARCH & PAGINATION
// ══════════════════════════════════════════════════
$search = $_GET['search'] ?? '';
$where  = '';
if ($search !== '') {
    $s     = mysqli_real_escape_string($conn, $search);
    $where = "WHERE email LIKE '%$s%' OR halaman LIKE '%$s%'";
}

$limit       = 15;
$page        = max(1, (int)($_GET['page'] ?? 1));
$start       = ($page - 1) * $limit;
$data        = mysqli_query($conn, "SELECT * FROM subscribers $where ORDER BY id DESC LIMIT $start,$limit");
$totalFilter = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM subscribers $where"))['t'];
$totalPages  = ceil($totalFilter / $limit);

// ══════════════════════════════════════════════════
//  TOP HALAMAN
// ══════════════════════════════════════════════════
$topHalaman = mysqli_query($conn, "
    SELECT halaman, COUNT(*) as cnt
    FROM subscribers
    WHERE halaman != ''
    GROUP BY halaman
    ORDER BY cnt DESC
    LIMIT 5
");

// ══════════════════════════════════════════════════
//  GRAFIK 7 HARI
// ══════════════════════════════════════════════════
$chartLabels = [];
$chartData   = [];
for ($i = 6; $i >= 0; $i--) {
    $date  = date('Y-m-d', strtotime("-$i days"));
    $label = date('d/m', strtotime("-$i days"));
    $cnt   = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) as t FROM subscribers WHERE DATE(created_at)='$date'"))['t'];
    $chartLabels[] = $label;
    $chartData[]   = (int)$cnt;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Subscribers — AY Vape Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/main.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.sub-stats {
  display: grid; grid-template-columns: repeat(3,1fr);
  gap: 16px; margin-bottom: 24px;
}

.sub-stat {
  background: var(--card); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 20px 22px;
  position: relative; overflow: hidden; transition: all var(--t);
}

.sub-stat::before {
  content:''; position:absolute; top:0;left:0;right:0; height:2px;
}

.sub-stat.s-total::before { background:linear-gradient(90deg,transparent,var(--neon),transparent); }
.sub-stat.s-today::before { background:linear-gradient(90deg,transparent,#22d3ee,transparent); }
.sub-stat.s-week::before  { background:linear-gradient(90deg,transparent,#34d399,transparent); }
.sub-stat:hover { transform:translateY(-3px); border-color:var(--border-glow); }

.sub-stat-label {
  font-family:'Rajdhani',sans-serif; font-size:10px;
  font-weight:700; letter-spacing:2.5px; text-transform:uppercase; margin-bottom:8px;
}
.sub-stat.s-total .sub-stat-label { color:#a855f7; }
.sub-stat.s-today .sub-stat-label { color:#22d3ee; }
.sub-stat.s-week  .sub-stat-label { color:#34d399; }

.sub-stat-val {
  font-family:'Orbitron',monospace; font-size:36px;
  font-weight:900; color:#fff; line-height:1;
}

.sub-layout { display:grid; grid-template-columns:1fr 280px; gap:20px; align-items:start; }

.table-card { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; }

.table-toolbar {
  padding:14px 18px; display:flex; align-items:center;
  gap:10px; flex-wrap:wrap; border-bottom:1px solid var(--border);
}

.table-toolbar .form-control { width:220px; }

table { width:100%; border-collapse:collapse; }
thead tr { background:rgba(99,102,241,0.06); border-bottom:1px solid var(--border); }
thead th {
  padding:12px 16px; text-align:left;
  font-family:'Rajdhani',sans-serif; font-size:10px;
  font-weight:700; letter-spacing:2px; text-transform:uppercase;
  color:var(--text-muted); white-space:nowrap;
}
tbody tr { border-bottom:1px solid rgba(255,255,255,0.04); transition:background var(--t); }
tbody tr:last-child { border-bottom:none; }
tbody tr:hover { background:rgba(99,102,241,0.04); }
tbody td { padding:12px 16px; font-size:13px; color:var(--text-dim); vertical-align:middle; }

.email-cell { font-family:'Rajdhani',sans-serif; font-size:14px; font-weight:600; color:#fff; }

.halaman-badge {
  display:inline-flex; align-items:center; padding:3px 10px;
  border-radius:99px; background:rgba(99,102,241,0.1);
  border:1px solid rgba(99,102,241,0.2);
  font-family:'Rajdhani',sans-serif; font-size:11px;
  font-weight:700; letter-spacing:1px; color:#a855f7;
}

.side-card { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:20px; margin-bottom:16px; }
.side-card-title {
  font-family:'Rajdhani',sans-serif; font-size:11px;
  font-weight:700; letter-spacing:2px; text-transform:uppercase;
  color:var(--text-muted); margin-bottom:14px;
}

.top-hal-item {
  display:flex; align-items:center; justify-content:space-between;
  padding:8px 0; border-bottom:1px solid rgba(255,255,255,0.04);
}
.top-hal-item:last-child { border-bottom:none; }
.top-hal-name { font-family:'Rajdhani',sans-serif; font-size:13px; font-weight:600; color:#fff; }
.top-hal-cnt  { font-family:'Orbitron',monospace; font-size:13px; font-weight:700; color:#a855f7; }

.danger-card {
  background:rgba(239,68,68,0.05); border:1px solid rgba(239,68,68,0.2);
  border-radius:var(--radius); padding:18px; margin-top:16px;
}
.danger-card-title { font-family:'Rajdhani',sans-serif; font-size:12px; font-weight:700; letter-spacing:1px; color:#f87171; margin-bottom:6px; }
.danger-card-desc  { font-size:12px; color:var(--text-muted); margin-bottom:12px; line-height:1.5; }

.alert {
  padding:12px 16px; border-radius:var(--radius-sm);
  font-family:'Rajdhani',sans-serif; font-size:13px;
  font-weight:600; letter-spacing:.5px; margin-bottom:16px;
  display:flex; align-items:center; gap:8px;
}
.alert-success { background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.25); color:#34d399; }
.alert-info    { background:rgba(99,102,241,0.1);  border:1px solid rgba(99,102,241,0.25); color:#a855f7; }

.chart-card { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:20px; margin-bottom:24px; }
.chart-card-title { font-family:'Rajdhani',sans-serif; font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--text-muted); margin-bottom:14px; }

.pagination { display:flex; gap:6px; flex-wrap:wrap; padding:14px 18px; border-top:1px solid var(--border); }
.pagination a {
  width:32px; height:32px; display:flex; align-items:center; justify-content:center;
  border-radius:var(--radius-sm); background:var(--card);
  border:1px solid var(--border); font-family:'Rajdhani',sans-serif;
  font-size:13px; font-weight:600; color:var(--text-dim);
  transition:all var(--t); text-decoration:none;
}
.pagination a:hover, .pagination a.active { background:rgba(99,102,241,0.15); border-color:var(--neon); color:#fff; }

.confirm-modal { position:fixed;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(6px);display:none;align-items:center;justify-content:center;z-index:999; }
.confirm-modal.open { display:flex; }
.confirm-box { background:var(--card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:28px;max-width:360px;width:calc(100% - 32px);text-align:center;position:relative;overflow:hidden;animation:modalIn .25s cubic-bezier(0.34,1.56,0.64,1); }
.confirm-box::before { content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,#f87171,transparent); }
@keyframes modalIn { from{opacity:0;transform:scale(0.9)} to{opacity:1;transform:scale(1)} }
.confirm-icon  { font-size:38px;margin-bottom:10px; }
.confirm-title { font-family:'Orbitron',monospace;font-size:15px;font-weight:700;color:#fff;margin-bottom:8px; }
.confirm-desc  { font-size:13px;color:var(--text-muted);margin-bottom:20px;line-height:1.6; }
.confirm-actions { display:flex;gap:10px; }
.empty-row td  { text-align:center;padding:60px 20px !important;color:var(--text-muted); }

@media (max-width:900px) {
  .sub-layout { grid-template-columns:1fr; }
  .sub-stats  { grid-template-columns:1fr; }
}
</style>
</head>
<body>

<!-- CONFIRM MODAL -->
<div class="confirm-modal" id="confirmModal">
  <div class="confirm-box">
    <div class="confirm-icon">🗑️</div>
    <div class="confirm-title">Hapus Semua Subscriber?</div>
    <div class="confirm-desc">
      Seluruh <strong style="color:#f87171"><?= number_format($total) ?> subscriber</strong>
      akan dihapus permanen dan tidak bisa dikembalikan.
    </div>
    <div class="confirm-actions">
      <form method="POST" style="flex:1;">
        <input type="hidden" name="hapus_semua" value="1">
        <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;">
          🗑 Ya, Hapus Semua
        </button>
      </form>
      <button class="btn btn-ghost" style="flex:1;justify-content:center;"
              onclick="document.getElementById('confirmModal').classList.remove('open')">
        Batal
      </button>
    </div>
  </div>
</div>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

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
    <a href="subscribers.php"  class="nav-item active"><span class="nav-icon">📧</span> Subscribers</a>
  </nav>
  <div class="sidebar-footer">
    <a href="../proses/logout.php" class="nav-logout"><span class="nav-icon">⏻</span> Logout</a>
  </div>
  <div class="smoke-container">
    <div class="smoke-particle"></div><div class="smoke-particle"></div><div class="smoke-particle"></div>
  </div>
</aside>

<div class="topbar">
  <span class="topbar-title">SUBSCRIBERS</span>
  <button class="hamburger" onclick="toggleSidebar()">☰</button>
</div>

<main class="main">

  <div class="page-header fade-up">
    <div class="page-title">Subscribers</div>
    <div class="page-sub">Daftar pelanggan yang subscribe newsletter AY Vape.</div>
  </div>

  <?php if (isset($_GET['deleted'])): ?>
  <div class="alert alert-success fade-up">✅ Subscriber berhasil dihapus.</div>
  <?php endif; ?>
  <?php if (isset($_GET['cleared'])): ?>
  <div class="alert alert-info fade-up">🗑 Semua subscriber berhasil dihapus.</div>
  <?php endif; ?>

  <!-- STATS -->
  <div class="sub-stats fade-up">
    <div class="sub-stat s-total">
      <div class="sub-stat-label">📧 Total Subscriber</div>
      <div class="sub-stat-val"><?= number_format($total) ?></div>
    </div>
    <div class="sub-stat s-today">
      <div class="sub-stat-label">📅 Hari Ini</div>
      <div class="sub-stat-val"><?= $today ?></div>
    </div>
    <div class="sub-stat s-week">
      <div class="sub-stat-label">📆 7 Hari Terakhir</div>
      <div class="sub-stat-val"><?= $thisWeek ?></div>
    </div>
  </div>

  <!-- CHART -->
  <div class="chart-card fade-up">
    <div class="chart-card-title">📈 Grafik Subscriber 7 Hari Terakhir</div>
    <canvas id="subChart" height="80"></canvas>
  </div>

  <!-- LAYOUT -->
  <div class="sub-layout">

    <!-- TABLE -->
    <div class="table-card fade-up">
      <div class="table-toolbar">
        <form method="GET" style="display:flex;gap:8px;flex:1;flex-wrap:wrap;">
          <input type="text" name="search" class="form-control"
                 placeholder="Cari email atau halaman..."
                 value="<?= htmlspecialchars($search) ?>">
          <button type="submit" class="btn btn-ghost btn-sm">🔍 Cari</button>
          <?php if ($search): ?>
            <a href="subscribers.php" class="btn btn-ghost btn-sm">✕ Reset</a>
          <?php endif; ?>
        </form>
        <?php if ($total > 0): ?>
        <a href="subscribers.php?export=1" class="btn btn-cyan btn-sm">⬇ Export CSV</a>
        <?php endif; ?>
      </div>

      <div style="overflow-x:auto;">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Email</th>
              <th>Dari Halaman</th>
              <th>Device</th>
              <th>IP</th>
              <th>Waktu</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php if (mysqli_num_rows($data) === 0): ?>
            <tr class="empty-row">
              <td colspan="7">
                <div style="font-size:36px;opacity:.15;margin-bottom:8px;">📧</div>
                <div style="font-family:'Rajdhani',sans-serif;font-size:13px;letter-spacing:1px;">
                  <?= $search ? 'Tidak ada hasil pencarian.' : 'Belum ada subscriber.' ?>
                </div>
              </td>
            </tr>
          <?php else:
            $no = $start + 1;
            while ($s = mysqli_fetch_assoc($data)):
              $ua    = $s['user_agent'] ?? '';
              $dev   = '🖥️';
              if (preg_match('/Mobile|Android|iPhone/i', $ua)) $dev = '📱';
              if (preg_match('/Tablet|iPad/i', $ua))           $dev = '📟';
              $waktu = date("d/m/Y H:i", strtotime($s['created_at']));
          ?>
            <tr>
              <td style="color:var(--text-muted);font-size:12px;"><?= $no++ ?></td>
              <td>
                <div class="email-cell">
                  <a href="mailto:<?= htmlspecialchars($s['email']) ?>"
                     style="color:#fff;text-decoration:none;">
                    <?= htmlspecialchars($s['email']) ?>
                  </a>
                </div>
              </td>
              <td>
                <span class="halaman-badge">
                  <?= htmlspecialchars($s['halaman'] ?: '-') ?>
                </span>
              </td>
              <td style="font-size:18px;" title="<?= htmlspecialchars($ua) ?>">
                <?= $dev ?>
              </td>
              <td style="font-family:'Rajdhani',sans-serif;font-size:12px;color:var(--text-muted);">
                <?= htmlspecialchars($s['ip_address'] ?? '-') ?>
              </td>
              <td style="font-family:'Rajdhani',sans-serif;font-size:12px;white-space:nowrap;">
                <?= $waktu ?>
              </td>
              <td>
                <a href="?hapus=<?= $s['id'] ?><?= $search?'&search='.urlencode($search):'' ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Hapus subscriber ini?')">✕</a>
              </td>
            </tr>
          <?php endwhile; endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php for ($i=1;$i<=$totalPages;$i++): ?>
          <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
             class="<?= $i==$page?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- SIDE -->
    <div>
      <div class="side-card fade-up">
        <div class="side-card-title">📄 Top Halaman Subscribe</div>
        <?php
        $hasTop = false;
        while ($th = mysqli_fetch_assoc($topHalaman)): $hasTop = true;
        ?>
        <div class="top-hal-item">
          <div class="top-hal-name"><?= htmlspecialchars($th['halaman'] ?: '-') ?></div>
          <div class="top-hal-cnt"><?= $th['cnt'] ?></div>
        </div>
        <?php endwhile;
        if (!$hasTop): ?>
        <div style="font-family:'Rajdhani',sans-serif;font-size:13px;color:var(--text-muted);text-align:center;padding:16px 0;">
          Belum ada data
        </div>
        <?php endif; ?>
      </div>

      <?php if ($total > 0): ?>
      <div class="danger-card fade-up">
        <div class="danger-card-title">⚠️ Danger Zone</div>
        <div class="danger-card-desc">
          Hapus semua <?= number_format($total) ?> subscriber dari database secara permanen.
        </div>
        <button class="btn btn-danger" style="width:100%;justify-content:center;"
                onclick="document.getElementById('confirmModal').classList.add('open')">
          🗑 Hapus Semua
        </button>
      </div>
      <?php endif; ?>
    </div>

  </div>

</main>

<script>
new Chart(document.getElementById('subChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($chartLabels) ?>,
    datasets: [{
      label: 'Subscriber',
      data:  <?= json_encode($chartData) ?>,
      backgroundColor: 'rgba(124,58,237,0.35)',
      borderColor: '#a855f7',
      borderWidth: 1,
      borderRadius: 4,
    }]
  },
  options: {
    plugins: { legend: { labels: { color:'#64748b', font:{ family:'Rajdhani' } } } },
    scales: {
      x: { ticks:{ color:'#64748b', font:{family:'Rajdhani'} }, grid:{ color:'rgba(255,255,255,0.04)' } },
      y: { beginAtZero:true, ticks:{ color:'#64748b', font:{family:'Rajdhani'}, stepSize:1 }, grid:{ color:'rgba(255,255,255,0.04)' } }
    }
  }
});

function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('active');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('overlay').classList.remove('active');
}
document.getElementById('confirmModal').addEventListener('click', function(e) {
  if (e.target === this) this.classList.remove('open');
});
</script>
</body>
</html>