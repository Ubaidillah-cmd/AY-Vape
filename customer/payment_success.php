<?php
session_start();
include "../config/db.php";

$jumlah      = 0;
$active_page = '';

$id_pesanan = (int)($_GET['id']     ?? 0);
$metode     = $_GET['metode']       ?? '';

$orderQ = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$id_pesanan' LIMIT 1");
$order  = mysqli_fetch_assoc($orderQ);

if (!$order) {
    header("Location: ../public/index.php");
    exit;
}

$orderNo = str_pad($id_pesanan, 4, '0', STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesanan Berhasil — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<style>
body {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  background: var(--black);
  overflow-y: auto;
  overflow-x: hidden;
  padding: 24px 16px 40px;
  position: relative;
}

/* Ambient glow */
body::before {
  content: '';
  position: fixed; inset: 0;
  background:
    radial-gradient(ellipse 60% 50% at 50% 30%, rgba(16,185,129,0.12) 0%, transparent 60%),
    radial-gradient(ellipse 40% 30% at 80% 80%, rgba(124,58,237,0.08) 0%, transparent 60%);
  pointer-events: none;
}

/* Confetti particles */
.confetti-wrap {
  position: fixed; inset: 0;
  pointer-events: none;
  z-index: 0;
  overflow: hidden;
}

.confetti-dot {
  position: absolute;
  top: -20px;
  border-radius: 50%;
  animation: confettiFall linear infinite;
}

@keyframes confettiFall {
  0%   { transform: translateY(-20px) rotate(0deg);   opacity: 1; }
  100% { transform: translateY(110vh) rotate(720deg);  opacity: 0; }
}

/* CARD */
.success-card {
  position: relative;
  z-index: 1;
  background: var(--card);
  border: 1px solid rgba(16,185,129,0.3);
  border-radius: var(--radius-lg);
  padding: 36px 28px 32px;
  max-width: 500px;
  width: 100%;
  text-align: center;
  box-shadow: 0 0 60px rgba(16,185,129,0.08), 0 24px 60px rgba(0,0,0,0.7);
  animation: cardIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
  margin-top: 16px;
}

@keyframes cardIn {
  from { opacity:0; transform: scale(0.88) translateY(20px); }
  to   { opacity:1; transform: scale(1) translateY(0); }
}

.success-card::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  border-radius: var(--radius-lg) var(--radius-lg) 0 0;
  background: linear-gradient(90deg, transparent, var(--green), #34d399, transparent);
}

/* CHECK ICON */
.success-icon-wrap {
  position: relative;
  width: 88px; height: 88px;
  margin: 0 auto 24px;
}

.success-icon {
  width: 88px; height: 88px;
  border-radius: 50%;
  background: rgba(16,185,129,0.12);
  border: 2px solid rgba(16,185,129,0.4);
  display: flex; align-items: center; justify-content: center;
  font-size: 38px;
  animation: iconPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
  box-shadow: 0 0 30px rgba(16,185,129,0.25);
}

@keyframes iconPop {
  from { transform: scale(0); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}

/* Pulse ring */
.pulse-ring {
  position: absolute; inset: -8px;
  border-radius: 50%;
  border: 2px solid rgba(16,185,129,0.3);
  animation: pulseRing 2s ease-out infinite;
}

.pulse-ring-2 {
  position: absolute; inset: -16px;
  border-radius: 50%;
  border: 2px solid rgba(16,185,129,0.15);
  animation: pulseRing 2s ease-out 0.4s infinite;
}

@keyframes pulseRing {
  0%   { transform: scale(1);    opacity: 1; }
  100% { transform: scale(1.3);  opacity: 0; }
}

/* TEXT */
.success-tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 14px;
  border-radius: var(--radius-pill);
  background: rgba(16,185,129,0.1);
  border: 1px solid rgba(16,185,129,0.25);
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: #34d399;
  margin-bottom: 16px;
  animation: fadeUp 0.5s var(--ease) 0.3s both;
}

.success-title {
  font-family: 'Orbitron', monospace;
  font-size: 24px;
  font-weight: 900;
  color: #fff;
  margin-bottom: 8px;
  animation: fadeUp 0.5s var(--ease) 0.35s both;
}

.success-desc {
  font-size: 14px;
  color: var(--text-muted);
  line-height: 1.7;
  margin-bottom: 28px;
  animation: fadeUp 0.5s var(--ease) 0.4s both;
}

/* ORDER INFO BOX */
.order-info-box {
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 18px 20px;
  margin-bottom: 24px;
  text-align: left;
  animation: fadeUp 0.5s var(--ease) 0.45s both;
}

.order-info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 6px 0;
}

.order-info-row + .order-info-row {
  border-top: 1px solid rgba(255,255,255,0.04);
}

.order-info-key {
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--text-muted);
}

.order-info-val {
  font-family: 'Rajdhani', sans-serif;
  font-size: 14px;
  font-weight: 700;
  color: #fff;
}

.order-info-val.total {
  font-family: 'Orbitron', monospace;
  font-size: 16px;
  color: var(--cyan);
}

/* STATUS STEPS */
.next-steps {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 28px;
  text-align: left;
  animation: fadeUp 0.5s var(--ease) 0.5s both;
}

.next-step-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 14px;
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
}

.next-step-num {
  width: 28px; height: 28px;
  border-radius: 50%;
  background: rgba(124,58,237,0.15);
  border: 1px solid rgba(124,58,237,0.3);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Orbitron', monospace;
  font-size: 11px;
  font-weight: 700;
  color: var(--neon-b);
  flex-shrink: 0;
}

.next-step-text {
  font-size: 13px;
  color: var(--text-dim);
  line-height: 1.4;
}

/* ACTIONS */
.success-actions {
  display: flex;
  gap: 10px;
  animation: fadeUp 0.5s var(--ease) 0.55s both;
}

/* WA CTA */
.wa-cta {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background: rgba(22,163,74,0.1);
  border: 1px solid rgba(22,163,74,0.25);
  border-radius: var(--radius-sm);
  font-family: 'Rajdhani', sans-serif;
  font-size: 13px;
  font-weight: 600;
  color: #34d399;
  margin-bottom: 10px;
  transition: all var(--t) var(--ease);
  text-decoration: none;
  animation: fadeUp 0.5s var(--ease) 0.52s both;
}

.wa-cta:hover {
  background: rgba(22,163,74,0.18);
  border-color: rgba(22,163,74,0.4);
  transform: translateY(-1px);
}

/* SCREENSHOT BANNER */
.ss-banner {
  background: linear-gradient(135deg, rgba(245,158,11,0.1), rgba(124,58,237,0.08));
  border: 1px solid rgba(245,158,11,0.35);
  border-radius: var(--radius);
  padding: 16px 18px;
  margin-bottom: 16px;
  text-align: left;
  animation: fadeUp 0.5s var(--ease) 0.53s both;
  position: relative;
  overflow: hidden;
}

.ss-banner::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  background: linear-gradient(90deg, transparent, #f59e0b, transparent);
}

.ss-banner-top {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 8px;
}

.ss-banner-icon {
  font-size: 22px;
  flex-shrink: 0;
  animation: ssBounce 1s ease-in-out infinite alternate;
}

@keyframes ssBounce {
  from { transform: translateY(0); }
  to   { transform: translateY(-4px); }
}

.ss-banner-title {
  font-family: 'Rajdhani', sans-serif;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 0.5px;
  color: #fbbf24;
}

.ss-banner-desc {
  font-size: 12px;
  color: rgba(255,255,255,0.55);
  line-height: 1.6;
}

.ss-banner-steps {
  display: flex;
  flex-direction: column;
  gap: 5px;
  margin-top: 8px;
}

.ss-banner-step {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  color: rgba(255,255,255,0.5);
}

.ss-banner-step-dot {
  width: 5px; height: 5px;
  border-radius: 50%;
  background: #f59e0b;
  flex-shrink: 0;
}

/* CHAT BUTTON BIG */
.chat-btn-big {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  width: 100%;
  padding: 14px;
  background: linear-gradient(135deg, var(--neon), #9333ea);
  border: none;
  border-radius: var(--radius-sm);
  color: #fff;
  font-family: 'Orbitron', monospace;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 1px;
  text-decoration: none;
  margin-bottom: 10px;
  transition: all var(--t) var(--ease);
  box-shadow: 0 0 20px rgba(124,58,237,0.4);
  animation: fadeUp 0.5s var(--ease) 0.54s both;
  position: relative;
  overflow: hidden;
}

.chat-btn-big::after {
  content: '';
  position: absolute; inset:0;
  background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.1) 50%, transparent 60%);
  transform: translateX(-100%);
  transition: transform 0.5s;
}

.chat-btn-big:hover::after { transform: translateX(100%); }

.chat-btn-big:hover {
  box-shadow: 0 0 35px rgba(124,58,237,0.6);
  transform: translateY(-2px);
}

.chat-btn-big .pulse-dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: #34d399;
  box-shadow: 0 0 8px rgba(52,211,153,0.8);
  animation: blink 1.2s ease-in-out infinite;
  flex-shrink: 0;
}

@keyframes blink {
  0%,100% { opacity:1; transform:scale(1); }
  50%      { opacity:0.3; transform:scale(0.7); }
}

@media (max-width: 480px) {
  .success-card { padding: 28px 16px; }
  .success-actions { flex-direction: column; }
}
</style>
</head>
<body>

<!-- CONFETTI -->
<div class="confetti-wrap" id="confetti"></div>

<div class="success-card">

  <!-- ICON -->
  <div class="success-icon-wrap">
    <div class="pulse-ring"></div>
    <div class="pulse-ring-2"></div>
    <div class="success-icon">
      <?= $metode === 'cod' ? '🚚' : '✅' ?>
    </div>
  </div>

  <div class="success-tag">
    <span style="width:6px;height:6px;border-radius:50%;background:#34d399;"></span>
    <?= $metode === 'cod' ? 'COD TERDAFTAR' : 'PEMBAYARAN DIKIRIM' ?>
  </div>

  <div class="success-title">
    <?= $metode === 'cod' ? 'Pesanan Diterima!' : 'Terima Kasih! 🎉' ?>
  </div>

  <p class="success-desc">
    <?php if ($metode === 'cod'): ?>
      Pesanan COD kamu sudah masuk. Admin akan menghubungi kamu
      untuk konfirmasi jadwal temu (COD). Siapkan uang pas ya!
    <?php else: ?>
      Bukti pembayaran kamu sudah kami terima. Admin akan memverifikasi
      dan menghubungi kamu untuk konfirmasi pesanan dalam<strong style="color:#fff;">1×24 jam</strong>.
    <?php endif; ?>
  </p>

  <!-- CHAT BUTTON BIG — di sini supaya langsung kelihatan -->
  <a href="../customer/chat.php?room=<?= $id_pesanan ?>" class="chat-btn-big">
    <span class="pulse-dot"></span>
    💬 CHAT ADMIN SEKARANG
  </a>

  <!-- ORDER INFO -->
  <div class="order-info-box">
    <div class="order-info-row">
      <span class="order-info-key">No. Order</span>
      <span class="order-info-val">#<?= $orderNo ?></span>
    </div>
    <div class="order-info-row">
      <span class="order-info-key">Nama</span>
      <span class="order-info-val"><?= htmlspecialchars($order['nama_pembeli']) ?></span>
    </div>
    <div class="order-info-row">
      <span class="order-info-key">Metode</span>
      <span class="order-info-val"><?= $metode === 'cod' ? 'Cash (COD)' : 'Transfer / QRIS' ?></span>
    </div>
    <div class="order-info-row">
      <span class="order-info-key">Total</span>
      <span class="order-info-val total">Rp <?= number_format($order['total']) ?></span>
    </div>
  </div>

  <!-- NEXT STEPS -->
  <div class="next-steps">
    <?php if ($metode === 'transfer'): ?>
    <div class="next-step-item">
      <div class="next-step-num">1</div>
      <div class="next-step-text">Admin verifikasi bukti transfer kamu (1×24 jam)</div>
    </div>
    <div class="next-step-item">
      <div class="next-step-num">2</div>
      <div class="next-step-text">Admin menyiapkan barang sesuai pesanan</div>
    </div>
    <div class="next-step-item">
      <div class="next-step-num">3</div>
      <div class="next-step-text">Admin menghubungi kamu untuk metode lanjutan (COD / Rekber)</div>
    </div>
    <?php else: ?>
    <div class="next-step-item">
      <div class="next-step-num">1</div>
      <div class="next-step-text">Admin konfirmasi pesanan dan siapkan barang</div>
    </div>
    <div class="next-step-item">
      <div class="next-step-num">2</div>
      <div class="next-step-text">Admin menghubungi kamu untuk jadwal temu (COD)</div>
    </div>
    <div class="next-step-item">
      <div class="next-step-num">3</div>
      <div class="next-step-text">Bayar langsung saat bertemu admin — siapkan uang pas!</div>
    </div>
    <?php endif; ?>
  </div>

  <!-- SCREENSHOT BANNER -->
  <div class="ss-banner">
    <div class="ss-banner-top">
      <span class="ss-banner-icon">📸</span>
      <div class="ss-banner-title">Screenshot Halaman Ini!</div>
    </div>
    <div class="ss-banner-desc">
      Simpan bukti order ini sebagai pegangan kamu. Tunjukkan screenshot ke admin
      saat bertemu (COD) atau saat konfirmasi pesanan.
    </div>
    <div class="ss-banner-steps">
      <div class="ss-banner-step">
        <span class="ss-banner-step-dot"></span>
        Tekan <strong style="color:#fbbf24;">Power + Volume Bawah</strong> (Android)
      </div>
      <div class="ss-banner-step">
        <span class="ss-banner-step-dot"></span>
        Atau <strong style="color:#fbbf24;">Power + Home</strong> (iPhone)
      </div>
      <div class="ss-banner-step">
        <span class="ss-banner-step-dot"></span>
        SS harus terlihat: No. Order, Nama, dan Total
      </div>
    </div>
  </div>

  <!-- WA + BACK -->
  <div class="success-actions">
    <a href="https://wa.me/6282333408651?text=Halo+AY+Vape!+Cek+order+%23<?= $orderNo ?>"
       class="btn btn-green" style="flex:1;justify-content:center;">
      💬 WhatsApp
    </a>
    <a href="../public/index.php"
       class="btn btn-secondary" style="flex:1;justify-content:center;">
      🏠 Kembali
    </a>
  </div>

</div>

<script>
// Generate confetti
const colors = ['#7c3aed','#a855f7','#22d3ee','#34d399','#f59e0b','#e879f9'];
const wrap   = document.getElementById('confetti');

for (let i = 0; i < 40; i++) {
  const dot  = document.createElement('div');
  const size = Math.random() * 10 + 4;
  dot.className = 'confetti-dot';
  Object.assign(dot.style, {
    left:             Math.random() * 100 + 'vw',
    width:            size + 'px',
    height:           size + 'px',
    background:       colors[Math.floor(Math.random() * colors.length)],
    animationDuration: (Math.random() * 4 + 3) + 's',
    animationDelay:   (Math.random() * 3) + 's',
    opacity:          (Math.random() * 0.6 + 0.4).toString(),
    borderRadius:     Math.random() > 0.5 ? '50%' : '2px',
  });
  wrap.appendChild(dot);
}
</script>

</body>
</html>