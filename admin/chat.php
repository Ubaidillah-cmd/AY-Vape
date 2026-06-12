<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$data = mysqli_query($conn, "
    SELECT p.id, p.nama_pembeli,
        (SELECT message FROM chat WHERE chat.room_id = p.id ORDER BY id DESC LIMIT 1) as last_msg,
        (SELECT created_at FROM chat WHERE chat.room_id = p.id ORDER BY id DESC LIMIT 1) as last_time,
        (SELECT COUNT(*) FROM chat WHERE chat.room_id = p.id AND status='sent' AND sender != 'admin') as unread,
        (SELECT COUNT(*) FROM chat WHERE chat.room_id = p.id) as total_msg
    FROM pesanan p
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Chat — AY Vape Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/main.css">
<style>

/* CHAT LIST */
.chat-list { display: flex; flex-direction: column; gap: 8px; }

.chat-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 18px;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  text-decoration: none;
  color: inherit;
  transition: all var(--transition);
  position: relative;
  overflow: hidden;
}

.chat-item::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(99,102,241,0.5), transparent);
  opacity: 0;
  transition: opacity var(--transition);
}

.chat-item:hover::before { opacity: 1; }

.chat-item:hover {
  border-color: var(--border-glow);
  background: var(--card-hover);
  transform: translateX(3px);
}

/* Unread highlight */
.chat-item.has-unread {
  border-color: rgba(99,102,241,0.3);
  background: rgba(99,102,241,0.05);
}

.chat-avatar {
  width: 46px; height: 46px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--neon), var(--neon-cyan));
  display: flex; align-items: center; justify-content: center;
  font-family: 'Orbitron', monospace;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  flex-shrink: 0;
  box-shadow: 0 0 12px rgba(99,102,241,0.3);
  text-decoration: none;
}

.chat-body {
  flex: 1;
  min-width: 0;
  text-decoration: none;
  color: inherit;
}

.chat-name-row {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 3px;
}

.chat-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
}

.chat-order {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 1.5px;
  color: var(--text-muted);
  text-transform: uppercase;
}

.chat-preview {
  font-size: 12px;
  color: var(--text-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 400px;
}

.chat-preview.no-msg {
  font-style: italic;
  opacity: 0.5;
}

/* RIGHT SIDE */
.chat-right {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 6px;
  flex-shrink: 0;
}

.chat-time {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 0.5px;
  color: var(--text-muted);
}

.unread-badge {
  width: 22px; height: 22px;
  border-radius: 50%;
  background: var(--neon);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Orbitron', monospace;
  font-size: 10px;
  font-weight: 700;
  color: #fff;
  box-shadow: 0 0 8px rgba(99,102,241,0.5);
}

.msg-count {
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px;
  letter-spacing: 1px;
  color: var(--text-muted);
}

/* DELETE ROOM BUTTON */
.btn-del-room {
  width: 34px; height: 34px;
  border-radius: var(--radius-sm);
  background: rgba(239,68,68,0.08);
  border: 1px solid rgba(239,68,68,0.2);
  color: #f87171;
  font-size: 15px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: all var(--transition);
  flex-shrink: 0;
  opacity: 0;
  margin-left: 4px;
}

.chat-item:hover .btn-del-room { opacity: 1; }

.btn-del-room:hover {
  background: rgba(239,68,68,0.2);
  border-color: rgba(239,68,68,0.45);
  box-shadow: 0 0 10px rgba(239,68,68,0.25);
  transform: scale(1.1);
}

/* Empty state */
.empty-state {
  text-align: center;
  padding: 80px 20px;
}
.empty-state .icon { font-size: 52px; opacity: 0.15; margin-bottom: 12px; }
.empty-state p { font-family:'Rajdhani',sans-serif; font-size:14px; letter-spacing:1px; color:var(--text-muted); }

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
  padding: 28px;
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

.confirm-icon  { font-size: 40px; margin-bottom: 12px; }
.confirm-title {
  font-family: 'Orbitron', monospace;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 8px;
}
.confirm-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 16px;
  font-weight: 700;
  color: #f87171;
  margin-bottom: 6px;
}
.confirm-desc {
  font-size: 13px;
  color: var(--text-muted);
  margin-bottom: 22px;
  line-height: 1.6;
}
.confirm-actions { display: flex; gap: 10px; }

/* Animasi item hilang */
@keyframes itemOut {
  from { opacity:1; transform:translateX(0); max-height:100px; margin-bottom:8px; }
  to   { opacity:0; transform:translateX(30px); max-height:0; margin-bottom:0; padding:0; border:none; }
}

.chat-item.removing {
  animation: itemOut 0.35s ease forwards;
  pointer-events: none;
}
</style>
</head>
<body>

<!-- CONFIRM MODAL -->
<div class="confirm-modal" id="confirmModal">
  <div class="confirm-box">
    <div class="confirm-icon">🗑️</div>
    <div class="confirm-title">Hapus Chat Room?</div>
    <div class="confirm-name" id="confirmName">—</div>
    <div class="confirm-desc" id="confirmDesc">
      Semua pesan di room ini akan dihapus permanen.
    </div>
    <div class="confirm-actions">
      <button class="btn btn-danger" style="flex:1;justify-content:center;"
              onclick="doDeleteRoom()">🗑 Hapus</button>
      <button class="btn btn-ghost"  style="flex:1;justify-content:center;"
              onclick="closeConfirm()">Batal</button>
    </div>
  </div>
</div>

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

<div class="topbar">
  <span class="topbar-title">CHAT</span>
  <button class="hamburger" onclick="toggleSidebar()">☰</button>
</div>

<main class="main">

  <div class="page-header fade-up">
    <div class="page-title">Chat Customer</div>
    <div class="page-sub">Balas pesan dari pembeli secara real-time.</div>
  </div>

  <?php $rows = mysqli_num_rows($data); ?>

  <?php if ($rows === 0): ?>
    <div class="empty-state card fade-up">
      <div class="icon">💬</div>
      <p>Belum ada chat masuk.</p>
    </div>
  <?php else: ?>

  <div class="chat-list" id="chatList">
    <?php while ($d = mysqli_fetch_assoc($data)):
      $initials  = strtoupper(substr($d['nama_pembeli'], 0, 2));
      $hasUnread = $d['unread'] > 0;
      $totalMsg  = (int)$d['total_msg'];
      $orderNo   = str_pad($d['id'], 4, '0', STR_PAD_LEFT);
      $preview   = $d['last_msg'] ?? null;
    ?>

    <div class="chat-item <?= $hasUnread ? 'has-unread' : '' ?>"
         id="room-<?= $d['id'] ?>">

      <!-- AVATAR → klik buka chat -->
      <a href="chat_room.php?room=<?= $d['id'] ?>" class="chat-avatar"
         style="text-decoration:none;">
        <?= $initials ?>
      </a>

      <!-- BODY → klik buka chat -->
      <a href="chat_room.php?room=<?= $d['id'] ?>" class="chat-body"
         style="text-decoration:none;">
        <div class="chat-name-row">
          <span class="chat-name"><?= htmlspecialchars($d['nama_pembeli']) ?></span>
          <span class="chat-order">Order #<?= $orderNo ?></span>
        </div>
        <div class="chat-preview <?= $preview ? '' : 'no-msg' ?>">
          <?= $preview
              ? htmlspecialchars(mb_substr($preview, 0, 60))
              : 'Belum ada pesan' ?>
        </div>
      </a>

      <!-- RIGHT -->
      <div class="chat-right">
        <?php if ($d['last_time']): ?>
          <div class="chat-time">
            <?= date("H:i", strtotime($d['last_time'])) ?>
          </div>
        <?php endif; ?>

        <?php if ($hasUnread): ?>
          <div class="unread-badge"><?= $d['unread'] ?></div>
        <?php elseif ($totalMsg > 0): ?>
          <div class="msg-count"><?= $totalMsg ?> msg</div>
        <?php endif; ?>
      </div>

      <!-- TOMBOL HAPUS ROOM -->
      <button class="btn-del-room"
              onclick="openConfirm(<?= $d['id'] ?>, '<?= addslashes(htmlspecialchars($d['nama_pembeli'])) ?>', <?= $totalMsg ?>)"
              title="Hapus chat room ini">
        🗑
      </button>

    </div>

    <?php endwhile; ?>
  </div>

  <?php endif; ?>

</main>

<script>
let pendingRoom = null;

// OPEN CONFIRM MODAL
function openConfirm(roomId, nama, msgCount) {
  pendingRoom = roomId;
  document.getElementById('confirmName').textContent  = nama + ' — Order #' + String(roomId).padStart(4,'0');
  document.getElementById('confirmDesc').textContent  =
    msgCount > 0
      ? `${msgCount} pesan akan dihapus permanen dan tidak bisa dikembalikan.`
      : 'Room ini tidak memiliki pesan, tetap hapus?';
  document.getElementById('confirmModal').classList.add('open');
}

// CLOSE MODAL
function closeConfirm() {
  pendingRoom = null;
  document.getElementById('confirmModal').classList.remove('open');
}

// DO DELETE
function doDeleteRoom() {
  if (!pendingRoom) return;
  const roomId = pendingRoom;
  closeConfirm();

  // Animasi item hilang
  const el = document.getElementById('room-' + roomId);
  if (el) el.classList.add('removing');

  fetch('../ajax/delete_room.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `room=${roomId}`
  })
  .then(r => r.json())
  .then(d => {
    if (d.ok) {
      setTimeout(() => {
        el?.remove();

        // Cek apakah list kosong
        const remaining = document.querySelectorAll('.chat-item').length;
        if (remaining === 0) {
          document.getElementById('chatList').innerHTML = `
            <div class="empty-state card fade-up">
              <div class="icon">💬</div>
              <p>Belum ada chat masuk.</p>
            </div>`;
        }
      }, 380);
    } else {
      el?.classList.remove('removing');
      alert('Gagal menghapus: ' + d.msg);
    }
  })
  .catch(() => {
    el?.classList.remove('removing');
    alert('Terjadi error. Coba lagi.');
  });
}

// SIDEBAR
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('active');
}

function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('overlay').classList.remove('active');
}

// Close modal on backdrop click
document.getElementById('confirmModal').addEventListener('click', function(e) {
  if (e.target === this) closeConfirm();
});
</script>

</body>
</html>