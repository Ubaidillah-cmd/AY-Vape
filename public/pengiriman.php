<?php
$jumlah      = 0;
$active_page = '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengiriman — AY Vape</title>
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
  background: radial-gradient(ellipse 80% 50% at 50% -5%, rgba(34,211,238,0.15) 0%, transparent 65%);
  z-index: -1;
}

/* ── ZONE CARDS ── */
.zone-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

.zone-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 24px;
  transition: all var(--t) var(--ease);
  position: relative;
  overflow: hidden;
  text-align: center;
}

.zone-card::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  opacity: 0;
  transition: opacity var(--t);
}

.zone-card.zone-local::before   { background: linear-gradient(90deg, transparent, var(--green), transparent); }
.zone-card.zone-near::before    { background: linear-gradient(90deg, transparent, var(--cyan), transparent); }
.zone-card.zone-far::before     { background: linear-gradient(90deg, transparent, var(--neon-b), transparent); }

.zone-card:hover::before { opacity: 1; }

.zone-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 40px rgba(0,0,0,0.5);
}

.zone-card.zone-local:hover  { border-color: rgba(16,185,129,0.3); }
.zone-card.zone-near:hover   { border-color: rgba(34,211,238,0.3); }
.zone-card.zone-far:hover    { border-color: rgba(124,58,237,0.4); }

.zone-icon  { font-size: 36px; margin-bottom: 12px; display: block; }
.zone-name  {
  font-family: 'Orbitron', monospace;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 1px;
  color: #fff;
  margin-bottom: 8px;
}
.zone-area  { font-size: 13px; color: var(--text-muted); margin-bottom: 14px; line-height: 1.6; }
.zone-eta   {
  font-family: 'Orbitron', monospace;
  font-size: 22px;
  font-weight: 900;
  margin-bottom: 4px;
}
.zone-card.zone-local .zone-eta  { color: var(--green); }
.zone-card.zone-near  .zone-eta  { color: var(--cyan); }
.zone-card.zone-far   .zone-eta  { color: var(--neon-b); }

.zone-label { font-family: 'Rajdhani', sans-serif; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: var(--text-muted); }

.zone-price-tag {
  display: inline-block;
  margin-top: 12px;
  padding: 5px 14px;
  border-radius: var(--radius-pill);
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 1px;
}

.zone-card.zone-local .zone-price-tag { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); color: var(--green); }
.zone-card.zone-near  .zone-price-tag { background: rgba(34,211,238,0.1); border: 1px solid rgba(34,211,238,0.25); color: var(--cyan); }
.zone-card.zone-far   .zone-price-tag { background: rgba(124,58,237,0.1); border: 1px solid rgba(124,58,237,0.3); color: var(--neon-b); }

/* ── COURIER TABLE ── */
.courier-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
}

.courier-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 18px;
  text-align: center;
  transition: all var(--t) var(--ease);
}

.courier-card:hover {
  border-color: var(--border-glow);
  transform: translateY(-3px);
}

.courier-name {
  font-family: 'Orbitron', monospace;
  font-size: 13px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 4px;
}

.courier-service {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 8px;
}

.courier-eta {
  font-family: 'Rajdhani', sans-serif;
  font-size: 14px;
  font-weight: 700;
  color: var(--cyan);
}

/* ── INFO BANNER ── */
.info-banner {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  background: rgba(34,211,238,0.06);
  border: 1px solid rgba(34,211,238,0.2);
  border-radius: var(--radius);
  padding: 18px 20px;
}

.info-banner-icon { font-size: 22px; flex-shrink: 0; margin-top: 1px; }
.info-banner-text { font-size: 14px; color: var(--text-dim); line-height: 1.7; }
.info-banner-text strong { color: #fff; }

/* ── TRACK SECTION ── */
.track-box {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 28px;
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}

.track-input {
  flex: 1;
  min-width: 200px;
}

/* ── STATUS ROW ── */
.status-list {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.status-item {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 18px 0;
  border-bottom: 1px solid rgba(255,255,255,0.04);
  position: relative;
}
.status-item:last-child { border-bottom: none; }

.status-dot-wrap {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0;
  flex-shrink: 0;
  width: 32px;
}

.status-circle {
  width: 32px; height: 32px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px;
  flex-shrink: 0;
}

.status-circle.done    { background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); }
.status-circle.active  { background: rgba(124,58,237,0.15); border: 2px solid var(--neon-b); box-shadow: 0 0 10px var(--glow); }
.status-circle.pending { background: rgba(255,255,255,0.04); border: 1px solid var(--border); opacity: 0.5; }

.status-vline {
  width: 1px; flex: 1; min-height: 20px;
  background: linear-gradient(to bottom, rgba(16,185,129,0.3), rgba(255,255,255,0.05));
}

.status-info { flex: 1; }
.status-title {
  font-family: 'Rajdhani', sans-serif;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 3px;
}
.status-detail { font-size: 13px; color: var(--text-muted); line-height: 1.5; }

.cta-strip {
  background: linear-gradient(135deg, rgba(34,211,238,0.08), rgba(124,58,237,0.08));
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
  background: linear-gradient(90deg, transparent, var(--cyan), var(--neon), transparent);
}

@media (max-width: 900px) {
  .zone-grid    { grid-template-columns: 1fr; }
  .courier-grid { grid-template-columns: repeat(2, 1fr); }
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
    Informasi Pengiriman
  </div>
  <h1 class="hero-title">
    <span class="hero-title-white">Info</span>
    <span class="hero-title-gradient">Pengiriman</span>
  </h1>
  <p class="hero-desc">
    Pengantaran dilakukan secara langsung melalui sistem COD.
    Untuk jarak jauh, transaksi dapat menggunakan rekber Shopee sesuai kesepakatan dengan admin.
  </p>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">

    <!-- INFO BANNER -->
    <div class="info-banner fade-up" style="margin-bottom:32px;">
      <span class="info-banner-icon">🚀</span>
      <div class="info-banner-text">
        <strong>Pengantaran dilakukan dengan sistem COD</strong> untuk area Jember Kota
        dengan pertemuan langsung bersama admin.
        Untuk pembeli di luar kota, pengiriman dapat dilakukan menggunakan
        rekber Shopee sesuai kesepakatan. Biaya dan teknis dibicarakan melalui chat atau WhatsApp.
      </div>
    </div>

    <!-- ZONE CARDS -->
    <div class="section-header fade-up">
      <div class="section-eyebrow">Area Pengiriman</div>
      <div class="section-title">Zona <span>Pengiriman</span></div>
    </div>

    <div class="zone-grid" style="margin-bottom:48px;">

      <div class="zone-card zone-local fade-up">
        <span class="zone-icon">⚡</span>
        <div class="zone-name">Jember Kota</div>
        <div class="zone-area">Sumbersari · Patrang · Kaliwates · Pakusari · Umbulsari</div>
        <div class="zone-eta">COD</div>
        <div class="zone-label">Metode Pengantaran</div>
        <div class="zone-price-tag">Bayar Di tempat</div>
      </div>

      <div class="zone-card zone-near fade-up-1">
        <span class="zone-icon">🚚</span>
        <div class="zone-name">Jawa Timur</div>
        <div class="zone-area">Surabaya · Malang · Banyuwangi · Bondowoso · Situbondo · Lumajang</div>
        <div class="zone-eta">Kesepakatan</div>
        <div class="zone-label">Metode Pengantaran</div>
        <div class="zone-price-tag">Via admin</div>
      </div>

      <div class="zone-card zone-far fade-up-2">
        <span class="zone-icon">🛫</span>
        <div class="zone-name">Seluruh Indonesia</div>
        <div class="zone-area">Jawa · Bali · Kalimantan · Sulawesi · Sumatra · Papua</div>
        <div class="zone-eta">Rekber Shopee</div>
        <div class="zone-label">Metode Pengantaran</div>
        <div class="zone-price-tag">Sesuai kesepakatan</div>
      </div>

    </div>

    <!-- COURIER GRID -->
    <div class="section-header fade-up">
      <div class="section-eyebrow">Metode Transaksi</div>
      <div class="section-title">Sistem <span>Pengantaran</span></div>
    </div>

    <div class="courier-grid" style="margin-bottom:48px;">
      <div class="courier-card fade-up">
        <div class="courier-name">COD</div>
        <div class="courier-service">Cash / Transfer</div>
        <div class="courier-eta">Bertemu Admin</div>
      </div>
      <div class="courier-card fade-up-1">
        <div class="courier-name">Rekber Shopee</div>
        <div class="courier-service">Via Marketplace</div>
        <div class="courier-eta">Jarak Jauh</div>
      </div>
      <div class="courier-card fade-up-2">
        <div class="courier-name">Kesepakatan</div>
        <div class="courier-service">Chat Website / WA</div>
        <div class="courier-eta">Fleksibel</div>
      </div>
      <div class="courier-card fade-up-3">
        <div class="courier-name">Admin AY Vape</div>
        <div class="courier-service">Manual Handling</div>
        <div class="courier-eta">Tanpa Ekspedisi</div>
      </div>
    </div>

    <!-- STATUS ALUR -->
    <div class="section-header fade-up">
      <div class="section-eyebrow">Tracking</div>
      <div class="section-title">Alur <span>Pengiriman</span></div>
    </div>

    <div class="card fade-up" style="padding:24px;max-width:600px;margin-bottom:48px;">
      <div class="status-list">

        <div class="status-item">
          <div class="status-dot-wrap">
            <div class="status-circle done">✓</div>
            <div class="status-vline"></div>
          </div>
          <div class="status-info">
            <div class="status-title">Order Diterima</div>
            <div class="status-detail">Pesanan masuk dan menunggu verifikasi pembayaran oleh admin.</div>
          </div>
        </div>

        <div class="status-item">
          <div class="status-dot-wrap">
            <div class="status-circle done">✓</div>
            <div class="status-vline"></div>
          </div>
          <div class="status-info">
            <div class="status-title">Pembayaran Dikonfirmasi</div>
            <div class="status-detail">Admin telah memverifikasi pembayaran. Proses packing dimulai.</div>
          </div>
        </div>

        <div class="status-item">
          <div class="status-dot-wrap">
            <div class="status-circle active">📦</div>
            <div class="status-vline"></div>
          </div>
          <div class="status-info">
            <div class="status-title">Packing &amp; Proses</div>
            <div class="status-detail">Produk dikemas dengan aman. Estimasi proses 1–3 jam setelah konfirmasi.</div>
          </div>
        </div>

        <div class="status-item">
          <div class="status-dot-wrap">
            <div class="status-circle pending">🚚</div>
            <div class="status-vline"></div>
          </div>
          <div class="status-info">
            <div class="status-title">Barang Dikirim</div>
            <div class="status-detail">Pengantaran dilakukan dengan pertemuan langsung atau sesuai kesepakatan dengan admin.
              Detail disampaikan melalui chat website atau WhatsApp.</div>
          </div>
        </div>

        <div class="status-item">
          <div class="status-dot-wrap">
            <div class="status-circle pending">🎉</div>
          </div>
          <div class="status-info">
            <div class="status-title">Paket Tiba</div>
            <div class="status-detail">Paket sampai di tangan kamu. Selamat menikmati! 💨</div>
          </div>
        </div>

      </div>
    </div>

    <!-- CTA -->
    <div class="cta-strip fade-up">
      <div class="section-eyebrow" style="margin-bottom:8px;">Masih ada pertanyaan?</div>
      <div class="section-title" style="font-size:26px;margin-bottom:14px;">
        Hubungi <span>Kami</span>
      </div>
      <p style="color:var(--text-dim);font-size:14px;margin-bottom:24px;">
        Tim admin siap membantu terkait pengantaran, COD, dan rekber Shopee.
      </p>
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a href="https://wa.me/6282333408651" class="btn btn-green btn-lg">💬 Chat WhatsApp</a>
        <a href="contact.php" class="btn btn-secondary btn-lg">Kontak Lainnya</a>
      </div>
    </div>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
<a href="https://wa.me/6282333408651" class="wa-float">💬 Chat WA</a>
</body>
</html>
