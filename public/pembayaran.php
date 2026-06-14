<?php
$jumlah      = 0;
$active_page = '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<style>

.info-hero {
  padding: 72px 28px 56px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.info-hero::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse 80% 50% at 50% -5%, rgba(245,158,11,0.1) 0%, transparent 65%);
  z-index: -1;
}

/* ── METHOD CARDS ── */
.method-cards {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.method-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  transition: all var(--t) var(--ease);
}

.method-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.6);
}

.method-card.qris:hover { border-color: rgba(124,58,237,0.4); }
.method-card.cod:hover  { border-color: rgba(16,185,129,0.35); }

.method-header {
  padding: 24px 24px 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  border-bottom: 1px solid var(--border);
}

.method-header.qris { background: linear-gradient(135deg, rgba(124,58,237,0.1), rgba(34,211,238,0.05)); }
.method-header.cod  { background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(245,158,11,0.04)); }

.method-header-icon {
  width: 52px; height: 52px;
  border-radius: var(--radius);
  display: flex; align-items: center; justify-content: center;
  font-size: 26px;
  flex-shrink: 0;
}

.method-header.qris .method-header-icon {
  background: rgba(124,58,237,0.15);
  border: 1px solid rgba(124,58,237,0.3);
}

.method-header.cod .method-header-icon {
  background: rgba(16,185,129,0.12);
  border: 1px solid rgba(16,185,129,0.25);
}

.method-name {
  font-family: 'Orbitron', monospace;
  font-size: 17px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 3px;
}

.method-sub {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--text-muted);
}

.method-body {
  padding: 22px 24px;
}

.method-feature-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 18px;
}

.method-feature {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  color: var(--text-dim);
}

.feat-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--neon-b);
  flex-shrink: 0;
}

.method-card.cod .feat-dot { background: var(--green); }

/* QRIS APPS */
.qris-apps {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 14px;
  padding-top: 14px;
  border-top: 1px solid var(--border);
}

.qris-app-tag {
  padding: 5px 12px;
  border-radius: var(--radius-pill);
  background: rgba(124,58,237,0.08);
  border: 1px solid rgba(124,58,237,0.2);
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.5px;
  color: var(--neon-b);
}

/* ── PROCESS STEPS ── */
.pay-steps {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0;
  position: relative;
}

.pay-steps::before {
  content: '';
  position: absolute;
  top: 24px; left: 12%; right: 12%;
  height: 1px;
  background: linear-gradient(90deg, var(--neon), var(--cyan));
  opacity: 0.3;
}

.pay-step {
  text-align: center;
  padding: 0 12px;
  position: relative;
}

.pay-step-num {
  width: 48px; height: 48px;
  border-radius: 50%;
  background: var(--card);
  border: 2px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Orbitron', monospace;
  font-size: 15px;
  font-weight: 700;
  color: var(--neon-b);
  margin: 0 auto 14px;
  position: relative;
  z-index: 1;
  transition: all var(--t) var(--ease);
}

.pay-step:hover .pay-step-num {
  border-color: var(--neon-b);
  box-shadow: 0 0 14px var(--glow);
  background: rgba(124,58,237,0.1);
}

.pay-step-title {
  font-family: 'Rajdhani', sans-serif;
  font-size: 14px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 5px;
}

.pay-step-desc {
  font-size: 12px;
  color: var(--text-muted);
  line-height: 1.6;
}

/* ── SECURITY CARD ── */
.security-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

.security-item {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 22px;
  display: flex;
  align-items: flex-start;
  gap: 14px;
  transition: all var(--t) var(--ease);
}

.security-item:hover {
  border-color: rgba(16,185,129,0.3);
  transform: translateY(-2px);
}

.security-icon {
  width: 40px; height: 40px;
  border-radius: var(--radius-sm);
  background: rgba(16,185,129,0.1);
  border: 1px solid rgba(16,185,129,0.2);
  display: flex; align-items: center; justify-content: center;
  font-size: 18px;
  flex-shrink: 0;
}

.security-text {}
.security-title { font-family: 'Rajdhani', sans-serif; font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 4px; }
.security-desc  { font-size: 12px; color: var(--text-muted); line-height: 1.6; }

.cta-strip {
  background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(124,58,237,0.08));
  border: 1px solid var(--border-glow);
  border-radius: var(--radius-lg);
  padding: 40px;
  text-align: center;
  position: relative;
  overflow: hidden;
  margin-top: 48px;
}

.cta-strip::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--amber), var(--neon-b), transparent);
}

@media (max-width: 768px) {
  .method-cards   { grid-template-columns: 1fr; }
  .pay-steps      { grid-template-columns: repeat(2, 1fr); gap: 20px; }
  .pay-steps::before { display: none; }
  .security-grid  { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<!-- HERO -->
<div class="info-hero">
  <div class="smoke-layer">
    <div class="smoke-puff"></div>
    <div class="smoke-puff"></div>
    <div class="smoke-puff"></div>
  </div>
  <div class="hero-eyebrow">
    <span class="hero-eyebrow-dot"></span>
    Metode Pembayaran
  </div>
  <h1 class="hero-title">
    <span class="hero-title-white">Info</span>
    <span class="hero-title-gradient">Pembayaran</span>
  </h1>
  <p class="hero-desc">
    Kami menyediakan metode pembayaran yang aman, mudah, dan dikonfirmasi langsung oleh admin.
  </p>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">

    <!-- METHOD CARDS -->
    <div class="section-header fade-up">
      <div class="section-eyebrow">Pilihan Bayar</div>
      <div class="section-title">Metode <span>Pembayaran</span></div>
    </div>

    <div class="method-cards" style="margin-bottom:56px;">

      <!-- QRIS -->
      <div class="method-card qris fade-up">
        <div class="method-header qris">
          <div class="method-header-icon">📱</div>
          <div>
            <div class="method-name">QRIS / Transfer</div>
            <div class="method-sub">Digital Payment</div>
          </div>
        </div>
        <div class="method-body">
          <div class="method-feature-list">
            <div class="method-feature"><span class="feat-dot"></span> Scan QRIS dari halaman pembayaran</div>
            <div class="method-feature"><span class="feat-dot"></span> Nominal transfer sesuai total order</div>
            <div class="method-feature"><span class="feat-dot"></span> Upload foto bukti transfer yang jelas</div>
            <div class="method-feature"><span class="feat-dot"></span> Pembayaran dikonfirmasi langsung oleh admin dalam 1×24 jam</div>
            <div class="method-feature"><span class="feat-dot"></span> Pastikan nama tujuan: <strong style="color:#fff">AY Vape</strong></div>
          </div>
          <div class="qris-apps">
            <span class="qris-app-tag">DANA</span>
            <span class="qris-app-tag">OVO</span>
            <span class="qris-app-tag">GoPay</span>
            <span class="qris-app-tag">ShopeePay</span>
            <span class="qris-app-tag">M-Banking</span>
            <span class="qris-app-tag">i-Banking</span>
          </div>
        </div>
      </div>

      <!-- COD -->
      <div class="method-card cod fade-up-1">
        <div class="method-header cod">
          <div class="method-header-icon">💵</div>
          <div>
            <div class="method-name">Cash / COD</div>
            <div class="method-sub">Bayar di Tempat</div>
          </div>
        </div>
        <div class="method-body">
          <div class="method-feature-list">
            <div class="method-feature">
              <span class="feat-dot" style="background:var(--green);"></span>
              Bayar langsung saat barang diterima sesuai kesepakatan dengan admin
            </div>
            <div class="method-feature">
              <span class="feat-dot" style="background:var(--green);"></span>
              Tersedia untuk area Jember Kota saja
            </div>
            <div class="method-feature">
              <span class="feat-dot" style="background:var(--green);"></span>
              Siapkan uang pas sesuai total pesanan
            </div>
            <div class="method-feature">
              <span class="feat-dot" style="background:var(--green);"></span>
              Tidak perlu upload bukti transfer
            </div>
            <div class="method-feature">
              <span class="feat-dot" style="background:var(--green);"></span>
              Pengantaran dilakukan dengan sistem COD (bertemu langsung dengan admin).
              Untuk jarak jauh, tersedia rekber Shopee sesuai kesepakatan via chat atau WhatsApp.
            </div>
          </div>
          <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border);">
            <div style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:var(--radius-pill);background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);">
              <span style="font-size:10px;color:var(--green);">📍</span>
              <span style="font-family:'Rajdhani',sans-serif;font-size:12px;font-weight:700;letter-spacing:1px;color:var(--green);">Jember Kota Only</span>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- PAYMENT PROCESS STEPS -->
    <div class="section-header fade-up">
      <div class="section-eyebrow">Alur Pembayaran</div>
      <div class="section-title">Cara <span>Bayar QRIS</span></div>
    </div>

    <div class="card fade-up" style="padding:36px;margin-bottom:56px;">
      <div class="pay-steps">
        <div class="pay-step">
          <div class="pay-step-num">01</div>
          <div class="pay-step-title">Checkout</div>
          <div class="pay-step-desc">Isi data diri dan pilih metode Transfer/QRIS</div>
        </div>
        <div class="pay-step">
          <div class="pay-step-num">02</div>
          <div class="pay-step-title">Scan QRIS</div>
          <div class="pay-step-desc">Buka e-wallet atau m-banking, scan kode QRIS kami</div>
        </div>
        <div class="pay-step">
          <div class="pay-step-num">03</div>
          <div class="pay-step-title">Transfer</div>
          <div class="pay-step-desc">Bayar sesuai nominal yang tertera, pastikan nominal tepat</div>
        </div>
        <div class="pay-step">
          <div class="pay-step-num">04</div>
          <div class="pay-step-title">Upload Bukti</div>
          <div class="pay-step-desc">Screenshot bukti bayar dan kirim ke admin untuk proses verifikasi</div>
        </div>
      </div>
    </div>

    <!-- SECURITY -->
    <div class="section-header fade-up">
      <div class="section-eyebrow">Keamanan</div>
      <div class="section-title">Transaksi <span>Aman</span></div>
    </div>

    <div class="security-grid" style="margin-bottom:0;">
      <div class="security-item fade-up">
        <div class="security-icon">🔐</div>
        <div class="security-text">
          <div class="security-title">Data Terenkripsi</div>
          <div class="security-desc">Semua data transaksi dienkripsi dan tidak pernah disimpan sembarangan.</div>
        </div>
      </div>
      <div class="security-item fade-up-1">
        <div class="security-icon">✅</div>
        <div class="security-text">
          <div class="security-title">Verifikasi Manual</div>
          <div class="security-desc">Setiap pembayaran diverifikasi langsung oleh admin sebelum pesanan diproses.</div>
        </div>
      </div>
      <div class="security-item fade-up-2">
        <div class="security-icon">📞</div>
        <div class="security-text">
          <div class="security-title">Support 24/7</div>
          <div class="security-desc">Ada masalah pembayaran? Hubungi kami langsung via WhatsApp kapan saja.</div>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="cta-strip fade-up">
      <div class="section-eyebrow" style="margin-bottom:8px;">Ada kendala bayar?</div>
      <div class="section-title" style="font-size:26px;margin-bottom:14px;">
        Hubungi <span>Admin Kami</span>
      </div>
      <p style="color:var(--text-dim);font-size:14px;margin-bottom:24px;">
        Admin kami siap membantu dan memastikan pembayaran kamu aman. Jangan ragu untuk bertanya atau minta bantuan kapan saja!
      </p>
      <a href="https://wa.me/6282333408651" class="btn btn-green btn-lg">💬 Chat WhatsApp</a>
    </div>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
<a href="https://wa.me/6282333408651" class="wa-float">💬 Chat WA</a>
</body>
</html>
