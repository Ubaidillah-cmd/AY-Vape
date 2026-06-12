<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$room = (int)$_GET['room'];
$data = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$room'");
$d    = mysqli_fetch_assoc($data);
if (!$d) { header("Location: chat.php"); exit; }

// Hitung jumlah pesan
$totalMsg = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) as total FROM chat WHERE room_id='$room'")
)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Chat Room — AY Vape Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/main.css">
<style>
body { overflow: hidden; }

.chat-panel {
  display: flex;
  flex-direction: column;
  height: 100vh;
  margin-left: var(--sidebar-w);
}

/* CHAT HEADER */
.chat-header {
  background: var(--deep);
  border-bottom: 1px solid var(--border);
  padding: 12px 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
  position: relative;
}

.chat-header::after {
  content: '';
  position: absolute; bottom:0;left:0;right:0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--neon), var(--cyan), transparent);
  opacity: 0.4;
}

.chat-header-back {
  font-family: 'Rajdhani', sans-serif;
  font-size: 13px;
  letter-spacing: 1px;
  color: var(--text-muted);
  transition: color var(--transition);
  text-decoration: none;
  flex-shrink: 0;
}
.chat-header-back:hover { color: var(--neon-cyan); }

.chat-header-avatar {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--neon), var(--neon-cyan));
  display: flex; align-items: center; justify-content: center;
  font-family: 'Orbitron', monospace;
  font-size: 13px;
  font-weight: 700;
  color: #fff;
  box-shadow: 0 0 10px var(--neon-glow);
  flex-shrink: 0;
}

.chat-header-info { flex: 1; }

.chat-header-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 16px;
  font-weight: 700;
  color: #fff;
}

.chat-header-sub {
  font-size: 11px;
  color: var(--text-muted);
  font-family: 'Rajdhani', sans-serif;
  letter-spacing: 1px;
}

/* DELETE ALL BUTTON */
.btn-delete-all {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  background: rgba(239,68,68,0.08);
  border: 1px solid rgba(239,68,68,0.25);
  border-radius: var(--radius-sm);
  color: #f87171;
  font-family: 'Rajdhani', sans-serif;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.5px;
  cursor: pointer;
  transition: all var(--transition);
  flex-shrink: 0;
}

.btn-delete-all:hover {
  background: rgba(239,68,68,0.16);
  border-color: rgba(239,68,68,0.45);
  box-shadow: 0 0 12px rgba(239,68,68,0.2);
}

.msg-count-badge {
  background: rgba(239,68,68,0.15);
  border: 1px solid rgba(239,68,68,0.2);
  color: #f87171;
  padding: 2px 7px;
  border-radius: 99px;
  font-size: 11px;
  font-family: 'Orbitron', monospace;
  font-weight: 700;
}

/* MESSAGES */
.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
  background: var(--black);
  display: flex;
  flex-direction: column;
  gap: 8px;
}

/* MSG BUBBLES */
.msg {
  max-width: 65%;
  padding: 11px 15px;
  border-radius: 14px;
  font-size: 14px;
  line-height: 1.5;
  animation: fadeUp 0.2s ease;
  position: relative;
}

.msg.admin {
  background: rgba(99,102,241,0.15);
  border: 1px solid rgba(99,102,241,0.25);
  border-bottom-right-radius: 4px;
  margin-left: auto;
  color: #fff;
}

.msg.customer {
  background: var(--card);
  border: 1px solid var(--border);
  border-bottom-left-radius: 4px;
  margin-right: auto;
  color: var(--text-dim);
}

.msg .time {
  font-size: 10px;
  color: var(--text-muted);
  margin-top: 6px;
  text-align: right;
  font-family: 'Rajdhani', sans-serif;
}

/* DELETE BUTTON PER MSG */
.msg-delete-btn {
  position: absolute;
  top: 6px;
  opacity: 0;
  background: rgba(239,68,68,0.12);
  border: 1px solid rgba(239,68,68,0.25);
  border-radius: 6px;
  color: #f87171;
  font-size: 12px;
  padding: 3px 7px;
  cursor: pointer;
  transition: all 0.2s;
  line-height: 1;
}

.msg.admin    .msg-delete-btn { left: -40px; }
.msg.customer .msg-delete-btn { right: -40px; }

.msg:hover .msg-delete-btn { opacity: 1; }

.msg-delete-btn:hover {
  background: rgba(239,68,68,0.25);
  border-color: rgba(239,68,68,0.5);
  box-shadow: 0 0 8px rgba(239,68,68,0.3);
  transform: scale(1.1);
}

/* Animasi hapus */
@keyframes msgOut {
  from { opacity:1; transform:scale(1); max-height:200px; margin:4px 0; }
  to   { opacity:0; transform:scale(0.8); max-height:0;   margin:0;     padding:0; }
}

.msg.deleting {
  animation: msgOut 0.3s ease forwards;
  pointer-events: none;
}

/* CONFIRM MODAL */
.confirm-modal {
  position: fixed; inset:0;
  background: rgba(0,0,0,0.75);
  backdrop-filter: blur(6px);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.confirm-modal.open { display: flex; }

.confirm-box {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 28px 28px 24px;
  max-width: 360px;
  width: calc(100% - 32px);
  text-align: center;
  box-shadow: 0 20px 60px rgba(0,0,0,0.8);
  animation: modalIn 0.25s cubic-bezier(0.34,1.56,0.64,1);
  position: relative;
  overflow: hidden;
}

.confirm-box::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  background: linear-gradient(90deg, transparent, #f87171, transparent);
}

@keyframes modalIn {
  from { opacity:0; transform:scale(0.9) translateY(10px); }
  to   { opacity:1; transform:scale(1) translateY(0); }
}

.confirm-icon  { font-size: 42px; margin-bottom: 12px; }
.confirm-title {
  font-family: 'Orbitron', monospace;
  font-size: 16px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 8px;
}
.confirm-desc  {
  font-size: 13px;
  color: var(--text-muted);
  margin-bottom: 22px;
  line-height: 1.6;
}

.confirm-actions { display: flex; gap: 10px; }

/* INPUT BAR */
.chat-input-bar {
  flex-shrink: 0;
  background: var(--deep);
  border-top: 1px solid var(--border);
  padding: 12px 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.chat-input {
  flex: 1;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--border);
  border-radius: 24px;
  color: var(--text);
  padding: 10px 18px;
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  outline: none;
  transition: all var(--transition);
}

.chat-input:focus {
  border-color: var(--neon);
  background: rgba(99,102,241,0.04);
}

.chat-action-btn {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: rgba(99,102,241,0.12);
  border: 1px solid var(--border);
  color: var(--text-dim);
  display: flex; align-items: center; justify-content: center;
  font-size: 17px;
  transition: all var(--transition);
  flex-shrink: 0;
}

.chat-action-btn:hover, .chat-action-btn.send {
  background: var(--neon);
  border-color: var(--neon);
  color: #fff;
  box-shadow: 0 0 12px var(--neon-glow);
}

/* Empty state */
.chat-empty {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: var(--text-muted);
  gap: 10px;
}

.chat-empty .icon { font-size: 48px; opacity: 0.15; }
.chat-empty p { font-family:'Rajdhani',sans-serif; font-size:13px; letter-spacing:1px; }

@keyframes fadeUp {
  from { opacity:0; transform:translateY(6px); }
  to   { opacity:1; transform:translateY(0); }
}

@media (max-width: 768px) {
  .chat-panel { margin-left: 0; }
  .msg { max-width: 88%; }
  .msg-delete-btn { opacity: 1; }
  .msg.admin    .msg-delete-btn { left: -36px; }
  .msg.customer .msg-delete-btn { right: -36px; }
}
</style>
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- CONFIRM MODAL — hapus semua -->
<div class="confirm-modal" id="confirmAllModal">
  <div class="confirm-box">
    <div class="confirm-icon">🗑️</div>
    <div class="confirm-title">Hapus Semua Pesan?</div>
    <div class="confirm-desc">
      Semua <span id="confirmMsgCount" style="color:#f87171;font-weight:700;"></span> pesan
      di chat ini akan dihapus permanen dan tidak bisa dikembalikan.
    </div>
    <div class="confirm-actions">
      <button class="btn btn-danger" style="flex:1;justify-content:center;"
              onclick="doDeleteAll()">🗑 Ya, Hapus Semua</button>
      <button class="btn btn-ghost" style="flex:1;justify-content:center;"
              onclick="closeConfirmAll()">Batal</button>
    </div>
  </div>
</div>

<!-- CONFIRM MODAL — hapus satu -->
<div class="confirm-modal" id="confirmOneModal">
  <div class="confirm-box">
    <div class="confirm-icon">💬</div>
    <div class="confirm-title">Hapus Pesan Ini?</div>
    <div class="confirm-desc">
      Pesan akan dihapus permanen dari percakapan ini.
    </div>
    <div class="confirm-actions">
      <button class="btn btn-danger" style="flex:1;justify-content:center;"
              onclick="doDeleteOne()">🗑 Hapus</button>
      <button class="btn btn-ghost" style="flex:1;justify-content:center;"
              onclick="closeConfirmOne()">Batal</button>
    </div>
  </div>
</div>

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
  <span class="topbar-title">CHAT</span>
  <button class="hamburger" onclick="toggleSidebar()">☰</button>
</div>

<!-- CHAT PANEL -->
<div class="chat-panel">

  <!-- HEADER -->
  <div class="chat-header">
    <a href="chat.php" class="chat-header-back">← Kembali</a>

    <div class="chat-header-avatar">
      <?= strtoupper(substr($d['nama_pembeli'], 0, 2)) ?>
    </div>

    <div class="chat-header-info">
      <div class="chat-header-name"><?= htmlspecialchars($d['nama_pembeli']) ?></div>
      <div class="chat-header-sub">
        ORDER #<?= str_pad($room, 4, '0', STR_PAD_LEFT) ?>
      </div>
    </div>

    <!-- TOMBOL HAPUS SEMUA -->
    <button class="btn-delete-all" onclick="openConfirmAll()">
      🗑 Hapus Semua
      <span class="msg-count-badge" id="msgCountBadge"><?= $totalMsg ?></span>
    </button>
  </div>

  <!-- MESSAGES -->
  <div class="chat-messages" id="chat">
    <?php if ($totalMsg == 0): ?>
    <div class="chat-empty" id="emptyState">
      <div class="icon">💬</div>
      <p>Belum ada pesan di percakapan ini</p>
    </div>
    <?php endif; ?>
  </div>

  <!-- INPUT -->
  <div class="chat-input-bar">
    <input type="file" id="fileInput" style="display:none" onchange="sendFile()">
    <button class="chat-action-btn"
            onclick="document.getElementById('fileInput').click()"
            title="Kirim File">📎</button>
    <input type="text" id="msgInput" class="chat-input"
           placeholder="Ketik pesan..."
           onkeydown="if(event.key==='Enter') sendMsg()">
    <button class="chat-action-btn"
            onclick="sendLoc()" title="Kirim Lokasi">📍</button>
    <button class="chat-action-btn send"
            onclick="sendMsg()" title="Kirim">➤</button>
  </div>

</div>

<script>
const room        = <?= $room ?>;
let   pendingDelId = null;   // ID pesan yang mau dihapus (single)

// ── LOAD CHAT ──
function loadChat() {
  fetch(`../ajax/get_message.php?room=${room}&admin=1`)
    .then(r => r.text())
    .then(html => {
      const box = document.getElementById('chat');
      if (html.trim()) {
        document.getElementById('emptyState')?.remove();
        box.innerHTML = html;
        box.scrollTop  = box.scrollHeight;
      } else {
        box.innerHTML = `
          <div class="chat-empty" id="emptyState">
            <div class="icon">💬</div>
            <p>Belum ada pesan di percakapan ini</p>
          </div>`;
      }
      // Update badge count
      const count = box.querySelectorAll('.msg').length;
      document.getElementById('msgCountBadge').textContent = count;
      document.getElementById('confirmMsgCount').textContent = count + ' pesan';
    });
}

setInterval(loadChat, 1500);
loadChat();

// ── SEND MSG ──
function sendMsg() {
  const input = document.getElementById('msgInput');
  const msg   = input.value.trim();
  if (!msg) return;

  fetch('../ajax/send_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `room=${room}&sender=admin&nama=Admin&message=${encodeURIComponent(msg)}`
  });

  input.value = '';
  input.focus();
}

// ── SEND FILE ──
function sendFile() {
  const file = document.getElementById('fileInput').files[0];
  if (!file) return;
  const fd = new FormData();
  fd.append('room', room);
  fd.append('sender', 'admin');
  fd.append('nama', 'Admin');
  fd.append('file', file);
  fetch('../ajax/send_message.php', { method:'POST', body:fd });
}

// ── SEND LOCATION ──
function sendLoc() {
  navigator.geolocation.getCurrentPosition(pos => {
    fetch('../ajax/send_message.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `room=${room}&sender=admin&nama=Admin&lat=${pos.coords.latitude}&lng=${pos.coords.longitude}`
    });
  });
}

// ══════════════════════════════════
//  HAPUS SATU PESAN
// ══════════════════════════════════
function deleteMsg(id, roomId) {
  pendingDelId = id;
  document.getElementById('confirmOneModal').classList.add('open');
}

function closeConfirmOne() {
  pendingDelId = null;
  document.getElementById('confirmOneModal').classList.remove('open');
}

function doDeleteOne() {
  if (!pendingDelId) return;
  const id = pendingDelId;
  closeConfirmOne();

  // Animasi hapus dulu
  const el = document.getElementById('msg-' + id);
  if (el) el.classList.add('deleting');

  fetch('../ajax/delete_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}&room=${room}`
  })
  .then(r => r.json())
  .then(d => {
    if (d.ok) {
      setTimeout(() => {
        el?.remove();
        // Update count badge
        const count = document.querySelectorAll('.msg').length;
        document.getElementById('msgCountBadge').textContent  = count;
        document.getElementById('confirmMsgCount').textContent = count + ' pesan';

        // Tampilkan empty state jika tidak ada pesan
        if (count === 0) {
          document.getElementById('chat').innerHTML = `
            <div class="chat-empty" id="emptyState">
              <div class="icon">💬</div>
              <p>Belum ada pesan di percakapan ini</p>
            </div>`;
        }
      }, 310);
    } else {
      el?.classList.remove('deleting');
      alert('Gagal menghapus pesan: ' + d.msg);
    }
  });
}

// ══════════════════════════════════
//  HAPUS SEMUA PESAN
// ══════════════════════════════════
function openConfirmAll() {
  const count = document.querySelectorAll('.msg').length;
  document.getElementById('confirmMsgCount').textContent = count + ' pesan';
  document.getElementById('confirmAllModal').classList.add('open');
}

function closeConfirmAll() {
  document.getElementById('confirmAllModal').classList.remove('open');
}

function doDeleteAll() {
  closeConfirmAll();

  // Animasi semua pesan
  document.querySelectorAll('.msg').forEach(el => el.classList.add('deleting'));

  fetch('../ajax/delete_all_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `room=${room}`
  })
  .then(r => r.json())
  .then(d => {
    setTimeout(() => {
      document.getElementById('chat').innerHTML = `
        <div class="chat-empty" id="emptyState">
          <div class="icon">💬</div>
          <p>Belum ada pesan di percakapan ini</p>
        </div>`;
      document.getElementById('msgCountBadge').textContent  = '0';
      document.getElementById('confirmMsgCount').textContent = '0 pesan';
    }, 350);
  });
}

// ── SIDEBAR ──
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