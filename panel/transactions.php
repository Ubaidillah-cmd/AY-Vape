<?php
session_start();
include "../config/db.php";
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }

// ── FILTER STATUS ──
$filterStatus = $_GET['status'] ?? '';
$search       = $_GET['search'] ?? '';

$where = "WHERE 1=1";
if ($filterStatus !== '') {
    $fs     = mysqli_real_escape_string($conn, $filterStatus);
    $where .= " AND pay.status = '$fs'";
}
if ($search !== '') {
    $s      = mysqli_real_escape_string($conn, $search);
    $where .= " AND (p.nama_pembeli LIKE '%$s%' OR p.id LIKE '%$s%')";
}

// ── STATS ──
$statAll     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t, SUM(p.total) as rev FROM pesanan p LEFT JOIN payment pay ON p.id=pay.id_pesanan"))['t'];
$statPending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pesanan p LEFT JOIN payment pay ON p.id=pay.id_pesanan WHERE pay.status='pending' OR pay.status IS NULL"))['t'];
$statDibayar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pesanan p LEFT JOIN payment pay ON p.id=pay.id_pesanan WHERE pay.status='dibayar'"))['t'];
$statDitolak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pesanan p LEFT JOIN payment pay ON p.id=pay.id_pesanan WHERE pay.status='ditolak'"))['t'];
$totalRev    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(p.total) as rev FROM pesanan p LEFT JOIN payment pay ON p.id=pay.id_pesanan WHERE pay.status='dibayar'"))['rev'] ?? 0;

// ── DATA ──
$data = mysqli_query($conn, "
    SELECT p.*, pay.status, pay.metode, pay.bukti
    FROM pesanan p
    LEFT JOIN payment pay ON p.id = pay.id_pesanan
    $where
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Transaksi — AY Vape Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/main.css">
<style>

/* ── STAT BAR ── */
.trx-stats {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 14px;
  margin-bottom: 24px;
}

.trx-stat {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 16px 18px;
  position: relative;
  overflow: hidden;
  transition: all var(--t);
  cursor: pointer;
  text-decoration: none;
  display: block;
}

.trx-stat::before {
  content: '';
  position: absolute; top:0;left:0;right:0; height:2px;
  opacity: 0;
  transition: opacity var(--t);
}

.trx-stat:hover::before, .trx-stat.active::before { opacity: 1; }
.trx-stat:hover, .trx-stat.active {
  border-color: var(--border-glow);
  transform: translateY(-2px);
}

.trx-stat.s-all::before     { background:linear-gradient(90deg,transparent,var(--neon),transparent); }
.trx-stat.s-pending::before { background:linear-gradient(90deg,transparent,#f59e0b,transparent); }
.trx-stat.s-dibayar::before { background:linear-gradient(90deg,transparent,#34d399,transparent); }
.trx-stat.s-ditolak::before { background:linear-gradient(90deg,transparent,#f87171,transparent); }
.trx-stat.s-rev::before     { background:linear-gradient(90deg,transparent,#22d3ee,transparent); }

.trx-stat.active.s-all     { border-color: rgba(124,58,237,0.4); background: rgba(124,58,237,0.06); }
.trx-stat.active.s-pending { border-color: rgba(245,158,11,0.4); background: rgba(245,158,11,0.06); }
.trx-stat.active.s-dibayar { border-color: rgba(52,211,153,0.4); background: rgba(52,211,153,0.06); }
.trx-stat.active.s-ditolak { border-color: rgba(248,113,113,0.4); background: rgba(248,113,113,0.06); }

.trx-stat-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase;
  margin-bottom: 8px;
}

.trx-stat.s-all     .trx-stat-label { color: #a855f7; }
.trx-stat.s-pending .trx-stat-label { color: #f59e0b; }
.trx-stat.s-dibayar .trx-stat-label { color: #34d399; }
.trx-stat.s-ditolak .trx-stat-label { color: #f87171; }
.trx-stat.s-rev     .trx-stat-label { color: #22d3ee; }

.trx-stat-val {
  font-family: 'Orbitron', monospace;
  font-size: 24px; font-weight: 900; color: #fff; line-height: 1;
}

.trx-stat.s-rev .trx-stat-val { font-size: 16px; }

/* ── TOOLBAR ── */
.trx-toolbar {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 20px;
  padding: 14px 18px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
}

.trx-toolbar .form-control { width: auto; min-width: 200px; }

/* ── TRX CARDS ── */
.trx-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  margin-bottom: 14px;
  overflow: hidden;
  transition: all var(--t);
  position: relative;
}

.trx-card:hover {
  border-color: var(--border-glow);
  box-shadow: 0 8px 28px rgba(0,0,0,0.4);
}

/* CARD TOP COLOR LINE BY STATUS */
.trx-card.status-pending::before { content:''; position:absolute; top:0;left:0;right:0; height:2px; background:linear-gradient(90deg,transparent,#f59e0b,transparent); }
.trx-card.status-dibayar::before { content:''; position:absolute; top:0;left:0;right:0; height:2px; background:linear-gradient(90deg,transparent,#34d399,transparent); }
.trx-card.status-ditolak::before { content:''; position:absolute; top:0;left:0;right:0; height:2px; background:linear-gradient(90deg,transparent,#f87171,transparent); }

/* CARD HEADER */
.trx-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
  background: rgba(255,255,255,0.01);
}

.trx-order-num {
  font-family: 'Orbitron', monospace;
  font-size: 15px; font-weight: 700; color: #fff;
  display: flex; align-items: center; gap: 10px;
}

.trx-date {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px; letter-spacing: 1px; color: var(--text-muted);
}

/* CARD BODY */
.trx-body {
  padding: 18px 20px;
}

/* INFO GRID */
.trx-info-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 16px;
}

.trx-info-col {}

.trx-info-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase;
  color: var(--text-muted); margin-bottom: 4px;
}

.trx-info-val {
  font-size: 14px; color: var(--text); font-weight: 500;
}

.trx-info-val.amount {
  font-family: 'Orbitron', monospace;
  font-size: 16px; font-weight: 700; color: #22d3ee;
}

/* PRODUCTS ROW */
.trx-products-row {
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  padding: 12px 16px;
  margin-bottom: 14px;
}

.trx-products-title {
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase;
  color: var(--text-muted); margin-bottom: 8px;
}

.trx-product-pills {
  display: flex; flex-wrap: wrap; gap: 6px;
}

.trx-product-pill {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 12px;
  background: rgba(124,58,237,0.08);
  border: 1px solid rgba(124,58,237,0.18);
  border-radius: 99px;
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px; font-weight: 600;
  color: var(--text-dim);
}

.trx-product-pill .qty {
  background: rgba(124,58,237,0.2);
  color: #a855f7;
  padding: 1px 6px;
  border-radius: 99px;
  font-size: 10px;
}

/* BUKTI */
.bukti-wrap {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 14px;
}

.bukti-thumb {
  width: 56px; height: 56px;
  border-radius: var(--radius-sm);
  object-fit: cover;
  border: 1px solid var(--border);
  cursor: pointer;
  transition: all var(--t);
}

.bukti-thumb:hover {
  border-color: var(--border-glow);
  transform: scale(1.05);
}

.bukti-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px; letter-spacing: 1.5px;
  text-transform: uppercase; color: var(--text-muted);
}

/* ACTIONS */
.trx-actions {
  display: flex; gap: 8px; flex-wrap: wrap;
  padding-top: 14px;
  border-top: 1px solid var(--border);
}

/* MODAL BUKTI BESAR */
.bukti-modal {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.85);
  backdrop-filter: blur(8px);
  display: none; align-items: center; justify-content: center;
  z-index: 999;
}

.bukti-modal.open { display: flex; }

.bukti-modal img {
  max-width: 90vw; max-height: 85vh;
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: 0 20px 60px rgba(0,0,0,0.8);
}

.bukti-modal-close {
  position: absolute; top: 20px; right: 20px;
  width: 40px; height: 40px;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.15);
  border-radius: 50%;
  color: #fff; font-size: 20px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: all var(--t);
}

.bukti-modal-close:hover { background: rgba(239,68,68,0.3); }

/* TOMBOL HAPUS TRANSAKSI */
.btn-del-trx {
  width: 32px; height: 32px;
  border-radius: var(--radius-sm);
  background: rgba(239,68,68,0.07);
  border: 1px solid rgba(239,68,68,0.18);
  color: #f87171;
  font-size: 14px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: all var(--t);
  flex-shrink: 0;
  opacity: 0;
}
.trx-card:hover .btn-del-trx { opacity: 1; }
.btn-del-trx:hover {
  background: rgba(239,68,68,0.2);
  border-color: rgba(239,68,68,0.45);
  box-shadow: 0 0 10px rgba(239,68,68,0.25);
  transform: scale(1.1);
}

/* MODAL KONFIRMASI HAPUS */
.del-modal {
  position: fixed; inset: 0;
  background: rgba(0,0,0,0.78);
  backdrop-filter: blur(6px);
  display: none; align-items: center; justify-content: center;
  z-index: 998;
}
.del-modal.open { display: flex; }
.del-modal-box {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 30px 28px;
  max-width: 360px;
  width: calc(100% - 32px);
  text-align: center;
  box-shadow: 0 20px 60px rgba(0,0,0,0.8);
  animation: delModalIn 0.25s cubic-bezier(0.34,1.56,0.64,1);
  position: relative;
  overflow: hidden;
}
.del-modal-box::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  background: linear-gradient(90deg, transparent, #f87171, transparent);
}
@keyframes delModalIn {
  from { opacity:0; transform:scale(0.88) translateY(12px); }
  to   { opacity:1; transform:scale(1) translateY(0); }
}
.del-modal-icon  { font-size: 42px; margin-bottom: 12px; }
.del-modal-title {
  font-family: 'Orbitron', monospace;
  font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 6px;
}
.del-modal-order {
  font-family: 'Rajdhani', sans-serif;
  font-size: 17px; font-weight: 700; color: #f87171; margin-bottom: 4px;
}
.del-modal-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 14px; color: var(--text-muted); margin-bottom: 18px; line-height: 1.5;
}
.del-modal-actions { display: flex; gap: 10px; }
@keyframes trxCardOut {
  from { opacity:1; transform:translateX(0); max-height:600px; margin-bottom:14px; }
  to   { opacity:0; transform:translateX(40px); max-height:0; margin-bottom:0; padding:0; border:none; }
}
.trx-card.removing {
  animation: trxCardOut 0.38s ease forwards;
  pointer-events: none;
  overflow: hidden;
}

/* EMPTY STATE */
.empty-state {
  padding: 80px 20px; text-align: center;
  color: var(--text-muted);
}
.empty-state .icon { font-size: 52px; opacity: .15; margin-bottom: 12px; }
.empty-state p { font-family:'Rajdhani',sans-serif; font-size:14px; letter-spacing:1px; }

/* INFO GRID — varian 5 kolom untuk desktop */
.trx-info-grid--5 {
  grid-template-columns: repeat(5, 1fr);
}

/* TOMBOL WA */
.btn-wa {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 14px;
  background: rgba(37,211,102,0.1);
  border: 1px solid rgba(37,211,102,0.25);
  border-radius: 99px;
  color: #25d366;
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.5px;
  text-decoration: none;
  transition: all var(--t);
  white-space: nowrap;
}

.btn-wa:hover {
  background: rgba(37,211,102,0.2);
  border-color: rgba(37,211,102,0.5);
  box-shadow: 0 0 12px rgba(37,211,102,0.2);
  color: #25d366;
  transform: translateY(-1px);
}

.wa-num {
  font-family: 'Rajdhani', sans-serif;
  font-size: 14px;
  font-weight: 600;
  color: var(--text);
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
  .trx-stats { grid-template-columns: repeat(3, 1fr); }
  .trx-info-grid { grid-template-columns: repeat(2, 1fr) !important; }
}

@media (max-width: 768px) {
  /* Stats: 2 kolom di tablet kecil */
  .trx-stats { grid-template-columns: repeat(2, 1fr); gap: 10px; }
  .trx-stat-val { font-size: 20px; }
  .trx-stat.s-rev .trx-stat-val { font-size: 13px; }

  /* Toolbar */
  .trx-toolbar { flex-direction: column; align-items: stretch; gap: 8px; }
  .trx-toolbar .form-control { width: 100%; min-width: unset; }
  .trx-toolbar .btn { width: 100%; justify-content: center; }

  /* Card header: susun vertikal */
  .trx-head { flex-direction: column; align-items: flex-start; gap: 10px; padding: 14px 16px; }

  /* Tombol-tombol actions di header: wrap rapi */
  .trx-head > div:last-child {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 6px !important;
    width: 100%;
  }

  /* Tombol hapus selalu visible di HP (tidak pakai hover) */
  .btn-del-trx { opacity: 1 !important; }

  /* Info grid: 2 kolom di HP */
  .trx-info-grid,
  .trx-info-grid--5 { grid-template-columns: repeat(2, 1fr) !important; gap: 12px; }

  /* Body padding lebih kecil */
  .trx-body { padding: 14px 16px; }

  /* WA button: lebih kompak */
  .btn-wa { font-size: 11px; padding: 4px 10px; }
  .wa-num { font-size: 13px; }

  /* Bukti wrap */
  .bukti-wrap { flex-wrap: wrap; }
}

@media (max-width: 480px) {
  /* Stats: 2 kolom tetap, lebih compact */
  .trx-stats { grid-template-columns: repeat(2, 1fr); gap: 8px; margin-bottom: 16px; }
  .trx-stat { padding: 12px 14px; }
  .trx-stat-label { font-size: 9px; letter-spacing: 1px; }
  .trx-stat-val { font-size: 18px; }
  .trx-stat.s-rev .trx-stat-val { font-size: 12px; }

  /* Info grid: 1 kolom di HP kecil */
  .trx-info-grid,
  .trx-info-grid--5 { grid-template-columns: 1fr 1fr !important; gap: 10px; }

  /* Order num lebih kecil */
  .trx-order-num { font-size: 13px; }

  /* Sembunyikan label status teks "Sudah Dikonfirmasi" jadi singkat */
  .trx-head .confirmed-label { display: none; }
}
</style>
</head>
<body>

<!-- DEL TRANSACTION MODAL -->
<div class="del-modal" id="delModal">
  <div class="del-modal-box">
    <div class="del-modal-icon">🗑️</div>
    <div class="del-modal-title">Hapus Transaksi?</div>
    <div class="del-modal-order" id="delModalOrder">—</div>
    <div class="del-modal-name" id="delModalName">—</div>
    <div class="del-modal-actions">
      <button class="btn btn-danger" style="flex:1;justify-content:center;"
              onclick="doDeleteTrx()">🗑 Hapus</button>
      <button class="btn btn-ghost"  style="flex:1;justify-content:center;"
              onclick="closeDelModal()">Batal</button>
    </div>
  </div>
</div>


<div class="bukti-modal" id="buktiModal" onclick="closeBukti()">
  <button class="bukti-modal-close" onclick="closeBukti()">✕</button>
  <img id="buktiImg" src="" alt="Bukti Pembayaran">
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
    <a href="transactions.php" class="nav-item active"><span class="nav-icon">◎</span> Transaksi</a>
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

<div class="topbar">
  <span class="topbar-title">TRANSAKSI</span>
  <button class="hamburger" onclick="toggleSidebar()">☰</button>
</div>

<main class="main">

  <div class="page-header fade-up">
    <div class="page-title">Transaksi</div>
    <div class="page-sub">Riwayat dan manajemen semua order masuk.</div>
  </div>

  <!-- STAT CARDS (klik untuk filter) -->
  <div class="trx-stats fade-up">

    <a href="transactions.php"
       class="trx-stat s-all <?= $filterStatus==='' && $search==='' ? 'active' : '' ?>">
      <div class="trx-stat-label">◎ Semua Order</div>
      <div class="trx-stat-val"><?= $statAll ?></div>
    </a>

    <a href="?status=pending"
       class="trx-stat s-pending <?= $filterStatus==='pending' ? 'active' : '' ?>">
      <div class="trx-stat-label">⏳ Pending</div>
      <div class="trx-stat-val"><?= $statPending ?></div>
    </a>

    <a href="?status=dibayar"
       class="trx-stat s-dibayar <?= $filterStatus==='dibayar' ? 'active' : '' ?>">
      <div class="trx-stat-label">✅ Dibayar</div>
      <div class="trx-stat-val"><?= $statDibayar ?></div>
    </a>

    <a href="?status=ditolak"
       class="trx-stat s-ditolak <?= $filterStatus==='ditolak' ? 'active' : '' ?>">
      <div class="trx-stat-label">✕ Ditolak</div>
      <div class="trx-stat-val"><?= $statDitolak ?></div>
    </a>

    <div class="trx-stat s-rev" style="cursor:default;">
      <div class="trx-stat-label">💰 Total Revenue</div>
      <div class="trx-stat-val">Rp <?= number_format($totalRev) ?></div>
    </div>

  </div>

  <!-- SEARCH TOOLBAR -->
  <form method="GET">
    <?php if ($filterStatus): ?>
      <input type="hidden" name="status" value="<?= htmlspecialchars($filterStatus) ?>">
    <?php endif; ?>
    <div class="trx-toolbar fade-up">
      <input type="text" name="search" class="form-control"
             placeholder="Cari nama pembeli atau no. order..."
             value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-ghost btn-sm">🔍 Cari</button>
      <?php if ($search || $filterStatus): ?>
        <a href="transactions.php" class="btn btn-ghost btn-sm">✕ Reset</a>
      <?php endif; ?>
      <div style="margin-left:auto;font-family:'Rajdhani',sans-serif;font-size:12px;letter-spacing:1px;color:var(--text-muted);">
        <?= mysqli_num_rows($data) ?> order ditemukan
      </div>
    </div>
  </form>

  <!-- TRANSACTION CARDS -->
  <?php if (mysqli_num_rows($data) === 0): ?>
  <div class="card empty-state fade-up">
    <div class="icon">◎</div>
    <p>Belum ada transaksi<?= $filterStatus ? " dengan status <strong>$filterStatus</strong>" : '' ?>.</p>
  </div>

  <?php else:
    while ($d = mysqli_fetch_assoc($data)):
      $status   = $d['status'] ?? 'pending';
      $badgeCls = 'badge-' . $status;
      $orderNo  = str_pad($d['id'], 4, '0', STR_PAD_LEFT);
  ?>

  <div class="trx-card status-<?= $status ?> fade-up" id="trx-<?= $d['id'] ?>">

    <!-- CARD HEADER -->
    <div class="trx-head">
      <div>
        <div class="trx-order-num">
          ORDER #<?= $orderNo ?>
          <span class="badge <?= $badgeCls ?>"><?= strtoupper($status) ?></span>
        </div>
        <div class="trx-date">
          📅 <?= date("d M Y, H:i", strtotime($d['tanggal'])) ?> WIB
        </div>
      </div>

      <!-- QUICK ACTIONS di header -->
      <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <?php
          $waRawHead  = trim($d['no_wa'] ?? '');
          $waLinkHead = '';
          if ($waRawHead !== '') {
              $waCleanHead = preg_replace('/[^0-9]/', '', $waRawHead);
              if (str_starts_with($waCleanHead, '0')) {
                  $waCleanHead = '62' . substr($waCleanHead, 1);
              }
              $waLinkHead = 'https://wa.me/' . $waCleanHead;
          }
        ?>
        <a href="chat_room.php?room=<?= $d['id'] ?>"
           class="btn btn-ghost btn-sm" title="Chat customer">
          💬 Chat
        </a>
        <?php if ($waLinkHead): ?>
        <a href="<?= $waLinkHead ?>" target="_blank" class="btn-wa" title="Hubungi via WhatsApp">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
          <?= htmlspecialchars($waRawHead) ?>
        </a>
        <?php endif; ?>
        <?php if ($status === 'pending'): ?>
          <a href="update_status.php?id=<?= $d['id'] ?>&status=dibayar"
             class="btn btn-cyan btn-sm">✔ Terima</a>
          <a href="update_status.php?id=<?= $d['id'] ?>&status=ditolak"
             class="btn btn-danger btn-sm"
             onclick="return confirm('Tolak order ini?')">✕ Tolak</a>
        <?php elseif ($status === 'dibayar'): ?>
          <span class="confirmed-label" style="font-family:'Rajdhani',sans-serif;font-size:12px;letter-spacing:1px;color:#34d399;">
            ✅ Sudah Dikonfirmasi
          </span>
        <?php elseif ($status === 'ditolak'): ?>
          <a href="update_status.php?id=<?= $d['id'] ?>&status=dibayar"
             class="btn btn-cyan btn-sm">↩ Terima Lagi</a>
        <?php endif; ?>

        <!-- TOMBOL HAPUS -->
        <button class="btn-del-trx"
                onclick="openDelModal(<?= $d['id'] ?>, '<?= addslashes(htmlspecialchars($d['nama_pembeli'])) ?>', '<?= $status ?>')"
                title="Hapus transaksi ini">🗑</button>
      </div>
    </div>

    <!-- CARD BODY -->
    <div class="trx-body">

      <!-- INFO GRID -->
      <div class="trx-info-grid trx-info-grid--5">
        <div class="trx-info-col">
          <div class="trx-info-label">👤 Nama Pembeli</div>
          <div class="trx-info-val"><?= htmlspecialchars($d['nama_pembeli']) ?></div>
        </div>
        <div class="trx-info-col">
          <div class="trx-info-label">📱 WhatsApp</div>
          <?php
            // Normalisasi nomor: hilangkan spasi/strip, ganti 0 awal → 62
            $waRaw  = trim($d['no_wa'] ?? '');
            $waLink = '';
            if ($waRaw !== '') {
                $waClean = preg_replace('/[^0-9]/', '', $waRaw);
                if (str_starts_with($waClean, '0')) {
                    $waClean = '62' . substr($waClean, 1);
                }
                $waLink = 'https://wa.me/' . $waClean;
            }
          ?>
          <?php if ($waLink): ?>
            <div class="wa-num"><?= htmlspecialchars($waRaw) ?></div>
            <a href="<?= $waLink ?>" target="_blank" class="btn-wa" style="margin-top:6px;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
              Chat WA
            </a>
          <?php else: ?>
            <div class="trx-info-val" style="color:var(--text-muted);">—</div>
          <?php endif; ?>
        </div>
        <div class="trx-info-col">
          <div class="trx-info-label">💰 Total Pembayaran</div>
          <div class="trx-info-val amount">Rp <?= number_format($d['total']) ?></div>
        </div>
        <div class="trx-info-col">
          <div class="trx-info-label">💳 Metode</div>
          <div class="trx-info-val">
            <?php
            $metode = $d['metode'] ?? '';
            if ($metode === 'transfer')     echo '📱 QRIS / Transfer';
            elseif ($metode === 'cod')      echo '💵 Cash (COD)';
            else                            echo '—';
            ?>
          </div>
        </div>
        <div class="trx-info-col">
          <div class="trx-info-label">📍 Alamat</div>
          <div class="trx-info-val" style="font-size:13px;">
            <?= htmlspecialchars($d['alamat'] ?? '—') ?>
          </div>
        </div>
      </div>

      <!-- PRODUK PILLS -->
      <div class="trx-products-row">
        <div class="trx-products-title">🛒 Detail Produk</div>
        <div class="trx-product-pills">
          <?php
          $detail = mysqli_query($conn, "
            SELECT dp.*, pr.nama_produk FROM detail_pesanan dp
            JOIN produk pr ON dp.id_produk = pr.id
            WHERE dp.id_pesanan = " . (int)$d['id']
          );
          while ($item = mysqli_fetch_assoc($detail)):
          ?>
          <span class="trx-product-pill">
            <?= htmlspecialchars($item['nama_produk']) ?>
            <span class="qty">×<?= $item['jumlah'] ?></span>
          </span>
          <?php endwhile; ?>
        </div>
      </div>

      <!-- BUKTI PEMBAYARAN -->
      <?php if (!empty($d['bukti'])): ?>
      <div class="bukti-wrap">
        <img class="bukti-thumb"
             src="../uploads/bukti_pembayaran/<?= htmlspecialchars($d['bukti']) ?>"
             alt="Bukti"
             onclick="openBukti('../uploads/bukti_pembayaran/<?= htmlspecialchars($d['bukti']) ?>')"
             title="Klik untuk perbesar">
        <div>
          <div class="bukti-label">Bukti Pembayaran</div>
          <a href="../uploads/bukti_pembayaran/<?= htmlspecialchars($d['bukti']) ?>"
             target="_blank"
             class="btn btn-ghost btn-sm" style="margin-top:6px;">
            🔍 Lihat Full
          </a>
        </div>
      </div>
      <?php endif; ?>

    </div><!-- end trx-body -->
  </div>

  <?php endwhile; endif; ?>

</main>

<script>
// BUKTI MODAL
function openBukti(src) {
  document.getElementById('buktiImg').src = src;
  document.getElementById('buktiModal').classList.add('open');
}
function closeBukti() {
  document.getElementById('buktiModal').classList.remove('open');
}

// ── DELETE TRANSACTION MODAL ──
let pendingDelId = null;

function openDelModal(id, nama, status) {
  pendingDelId = id;
  const orderNo = String(id).padStart(4, '0');
  document.getElementById('delModalOrder').textContent = 'ORDER #' + orderNo;

  const statusLabel = { pending: 'Pending', dibayar: 'Sudah Dikonfirmasi', ditolak: 'Ditolak' };
  document.getElementById('delModalName').textContent =
    nama + ' · ' + (statusLabel[status] || status) + '\n\nSemua data pesanan, detail produk, dan chat akan dihapus permanen.';

  document.getElementById('delModal').classList.add('open');
}

function closeDelModal() {
  pendingDelId = null;
  document.getElementById('delModal').classList.remove('open');
}

function doDeleteTrx() {
  if (!pendingDelId) return;
  const id = pendingDelId;
  closeDelModal();

  const card = document.getElementById('trx-' + id);
  if (card) card.classList.add('removing');

  fetch('../ajax/delete_transaction.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id=' + id
  })
  .then(r => r.json())
  .then(d => {
    if (d.ok) {
      setTimeout(() => {
        card?.remove();
        // Cek apakah sudah tidak ada card tersisa
        const remaining = document.querySelectorAll('.trx-card').length;
        if (remaining === 0) {
          document.querySelector('main').insertAdjacentHTML('beforeend', `
            <div class="card empty-state fade-up">
              <div class="icon">◎</div>
              <p>Belum ada transaksi.</p>
            </div>`);
        }
      }, 400);
    } else {
      card?.classList.remove('removing');
      alert('Gagal hapus: ' + d.msg);
    }
  })
  .catch(() => {
    card?.classList.remove('removing');
    alert('Terjadi error. Coba lagi.');
  });
}

// Klik backdrop tutup modal
document.getElementById('delModal').addEventListener('click', function(e) {
  if (e.target === this) closeDelModal();
});

// SIDEBAR
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('active');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('overlay').classList.remove('active');
}
</script>
</body>
</html>