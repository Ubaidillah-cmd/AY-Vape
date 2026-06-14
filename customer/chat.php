<?php
$jumlah      = 0;
$active_page = '';
$room        = (int)($_GET['room'] ?? 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Chat Admin — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<style>
body { overflow: hidden; }

.chat-page {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

.chat-header {
  background: rgba(7,7,15,0.9);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid var(--border);
  padding: 14px 20px;
  display: flex;
  align-items: center;
  gap: 14px;
  flex-shrink: 0;
  position: relative;
}

.chat-header::after {
  content: '';
  position: absolute; bottom:0;left:0;right:0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--neon), var(--cyan), transparent);
  opacity: 0.5;
}

.chat-admin-avatar {
  width: 44px; height: 44px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--neon), var(--cyan));
  display: flex; align-items: center; justify-content: center;
  font-family: 'Orbitron', monospace;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  box-shadow: 0 0 14px var(--glow);
  flex-shrink: 0;
}

.chat-admin-info {}
.chat-admin-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 16px;
  font-weight: 700;
  color: #fff;
}
.chat-admin-status {
  display: flex;
  align-items: center;
  gap: 5px;
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 1px;
  color: var(--green);
}
.status-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--green);
  box-shadow: 0 0 6px rgba(16,185,129,0.6);
  animation: blink 2s ease-in-out infinite;
}

/* MESSAGES */
.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  background: var(--black);
}

/* INPUT BAR */
.chat-bar {
  flex-shrink: 0;
  background: rgba(7,7,15,0.9);
  backdrop-filter: blur(16px);
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
  border-radius: var(--radius-pill);
  color: var(--text);
  padding: 11px 20px;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  outline: none;
  transition: all var(--t) var(--ease);
}

.chat-input:focus {
  border-color: var(--neon-b);
  background: rgba(124,58,237,0.05);
}

.chat-input::placeholder { color: var(--text-muted); }

.chat-btn {
  width: 42px; height: 42px;
  border-radius: 50%;
  background: rgba(124,58,237,0.12);
  border: 1px solid var(--border);
  color: var(--text-dim);
  display: flex; align-items: center; justify-content: center;
  font-size: 18px;
  transition: all var(--t) var(--ease);
  flex-shrink: 0;
}

.chat-btn:hover { background: rgba(124,58,237,0.2); border-color: var(--neon-b); color: #fff; }

.chat-btn.send {
  background: var(--neon);
  border-color: var(--neon);
  color: #fff;
  box-shadow: 0 0 14px var(--glow);
}

.chat-btn.send:hover {
  background: var(--neon-b);
  box-shadow: 0 0 22px var(--glow);
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
.chat-empty p { font-family: 'Rajdhani', sans-serif; font-size: 13px; letter-spacing: 1px; }

/* Typing indicator (untuk kesan admin sedang mengetik sblm auto-reply #1 muncul) */
.typing-indicator {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  background: rgba(99,102,241,0.08);
  border: 1px solid rgba(99,102,241,0.15);
  border-radius: 18px 18px 18px 4px;
  width: fit-content;
  margin-top: 4px;
}

.typing-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--neon);
  opacity: 0.6;
  animation: typingBounce 1.2s ease-in-out infinite;
}
.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes typingBounce {
  0%, 60%, 100% { transform: translateY(0); opacity: 0.6; }
  30%            { transform: translateY(-5px); opacity: 1; }
}
</style>
</head>
<body>

<div class="chat-page">

  <!-- HEADER -->
  <div class="chat-header">
    <a href="../customer/cart.php" style="color:var(--text-muted);font-family:'Rajdhani',sans-serif;font-size:13px;letter-spacing:1px;margin-right:4px;">← Kembali</a>
    <div class="chat-admin-avatar">AY</div>
    <div class="chat-admin-info">
      <div class="chat-admin-name">AY Vape Support</div>
      <div class="chat-admin-status">
        <span class="status-dot"></span>
        <span id="adminStatusText">Online</span>
      </div>
    </div>
  </div>

  <!-- MESSAGES -->
  <div class="chat-messages" id="chatBox">
    <div class="chat-empty" id="emptyState">
      <div class="icon">💬</div>
      <p>Mulai percakapan dengan admin</p>
    </div>
  </div>

  <!-- INPUT -->
  <div class="chat-bar">
    <input type="file" id="fileInput" style="display:none" onchange="sendFile()">
    <button class="chat-btn" onclick="document.getElementById('fileInput').click()" title="Kirim File">📎</button>
    <input type="text" id="msgInput" class="chat-input"
           placeholder="Ketik pesan..."
           onkeydown="if(event.key==='Enter') sendMsg()">
    <button class="chat-btn" onclick="sendLoc()" title="Kirim Lokasi">📍</button>
    <button class="chat-btn send" onclick="sendMsg()">➤</button>
  </div>

</div>

<script>
const room   = <?= $room ?>;
let lastLen  = 0;
let lastHtml = '';

// ── LOAD CHAT (polling setiap 1.5 detik) ──
function loadChat() {
  fetch(`../ajax/get_message.php?room=${room}`)
    .then(r => r.text())
    .then(html => {
      const box = document.getElementById('chatBox');
      if (html.trim()) {
        document.getElementById('emptyState')?.remove();

        // Hapus typing indicator sebelum render ulang
        document.getElementById('typingIndicator')?.remove();

        if (html !== lastHtml) {
          box.innerHTML = html;
          box.scrollTop = box.scrollHeight;

          if (html.length > lastLen) {
            document.title = '🔔 Pesan baru — AY Vape';
            setTimeout(() => document.title = 'Chat Admin — AY Vape', 2500);
          }
          lastLen  = html.length;
          lastHtml = html;
        }
      }
    });
}

setInterval(loadChat, 1500);
loadChat();

// ── CEK AUTO-REPLY #2 (polling setiap 30 detik) ──
// Jika admin belum balas > 5 menit, server akan insert auto-reply #2
function checkAutoReply() {
  fetch(`../ajax/check_auto_reply.php?room=${room}`)
    .then(r => r.json())
    .then(d => {
      if (d.ok && d.action === 'auto_reply_2_sent') {
        // Langsung reload chat agar pesan #2 muncul
        loadChat();
      }
    })
    .catch(() => {}); // silent fail
}

// Mulai polling auto-reply #2 setelah 5 menit pertama (300000 ms)
// lalu ulangi setiap 30 detik
setTimeout(() => {
  checkAutoReply();
  setInterval(checkAutoReply, 30000);
}, 300000); // 5 menit = 300.000 ms

// ── KIRIM PESAN ──
function sendMsg() {
  const input = document.getElementById('msgInput');
  const msg   = input.value.trim();
  if (!msg) return;

  // Tampilkan typing indicator sementara (hanya untuk pesan pertama customer)
  showTypingIfFirst();

  fetch('../ajax/send_message.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `room=${room}&sender=customer&nama=Customer&message=${encodeURIComponent(msg)}`
  }).then(() => {
    // Reload segera setelah kirim agar tampil + auto-reply #1 juga langsung muncul
    setTimeout(loadChat, 800);
    setTimeout(loadChat, 1600); // sekali lagi untuk pastikan auto-reply #1 sudah masuk DB
  });

  input.value = '';
  input.focus();
}

function sendFile() {
  const file = document.getElementById('fileInput').files[0];
  if (!file) return;
  const fd = new FormData();
  fd.append('room', room);
  fd.append('sender', 'customer');
  fd.append('nama', 'Customer');
  fd.append('file', file);
  fetch('../ajax/send_message.php', { method: 'POST', body: fd })
    .then(() => setTimeout(loadChat, 800));
}

function sendLoc() {
  navigator.geolocation.getCurrentPosition(pos => {
    fetch('../ajax/send_message.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `room=${room}&sender=customer&nama=Customer&lat=${pos.coords.latitude}&lng=${pos.coords.longitude}`
    }).then(() => setTimeout(loadChat, 800));
  }, () => alert('Tidak bisa mengakses lokasi.'));
}

// ── TYPING INDICATOR ──
// Tampil sebentar (1.5 detik) sebelum auto-reply #1 muncul
// Hanya tampil jika belum ada pesan sama sekali
let typingShown = false;
function showTypingIfFirst() {
  if (typingShown) return;
  const box = document.getElementById('chatBox');
  const existingMsgs = box.querySelectorAll('.msg');
  if (existingMsgs.length > 0) return; // sudah ada pesan sebelumnya

  typingShown = true;

  // Buat elemen typing indicator
  const typingEl = document.createElement('div');
  typingEl.id = 'typingIndicator';
  typingEl.className = 'typing-indicator';
  typingEl.innerHTML = `
    <div class="typing-dot"></div>
    <div class="typing-dot"></div>
    <div class="typing-dot"></div>
  `;
  box.appendChild(typingEl);
  box.scrollTop = box.scrollHeight;

  // Hapus setelah 2 detik (auto-reply #1 sudah masuk)
  setTimeout(() => typingEl.remove(), 2000);
}
</script>
</body>
</html>