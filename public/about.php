<?php
$jumlah      = 0;
$active_page = 'about';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tentang Kami — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<style>
/* HERO ABOUT */
.about-hero {
  padding: 80px 28px 60px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.about-hero::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.18) 0%, transparent 65%);
  z-index: -1;
}

/* VALUES GRID */
.values-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
}

.value-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 28px;
  transition: all var(--t) var(--ease);
  position: relative;
  overflow: hidden;
}

.value-card::after {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--neon-b), transparent);
  opacity: 0;
  transition: opacity var(--t);
}

.value-card:hover::after { opacity: 1; }
.value-card:hover {
  border-color: var(--border-glow);
  transform: translateY(-4px);
  box-shadow: var(--shadow-neon);
}

.value-icon {
  font-size: 36px;
  margin-bottom: 14px;
  display: block;
}

.value-title {
  font-family: 'Orbitron', monospace;
  font-size: 16px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 8px;
}

.value-desc {
  font-size: 14px;
  line-height: 1.7;
  color: var(--text-dim);
}

/* STATS FULL */
.stats-band {
  background: var(--card);
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  padding: 48px 28px;
  margin: 60px 0;
  position: relative;
}

.stats-band::before {
  content: '';
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(124,58,237,0.04), rgba(34,211,238,0.03));
}

.stats-inner {
  max-width: 1300px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.stat-item { text-align: center; }

.stat-num {
  font-family: 'Orbitron', monospace;
  font-size: 42px;
  font-weight: 900;
  background: linear-gradient(135deg, var(--neon-b), var(--cyan));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  line-height: 1;
  margin-bottom: 6px;
}

.stat-lbl {
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  color: var(--text-muted);
}

/* TEAM - CEO ONLY */
.team-ceo-wrap {
  display: flex;
  justify-content: center;
}

.team-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 40px 48px;
  text-align: center;
  transition: all var(--t) var(--ease);
  max-width: 560px;
  width: 100%;
  position: relative;
}

.team-card:hover {
  border-color: var(--border-glow);
  transform: translateY(-5px);
  box-shadow: var(--shadow-neon);
}

.team-avatar {
  width: 110px; height: 110px;
  border-radius: 50%;
  margin: 0 auto 16px;
  border: 2px solid transparent;
  background:
    linear-gradient(var(--card), var(--card)) padding-box,
    linear-gradient(135deg, var(--neon), var(--cyan)) border-box;
  overflow: hidden;
}

.team-avatar img { width: 100%; height: 100%; object-fit: cover; }

.team-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 20px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 4px;
}

.team-role {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--neon-b);
  margin-bottom: 0;
}

.ceo-quote {
  position: relative;
  margin-top: 24px;
  padding: 20px 24px;
  background: rgba(124,58,237,0.07);
  border-left: 3px solid var(--neon-b);
  border-radius: 0 var(--radius) var(--radius) 0;
  text-align: left;
}

.ceo-quote::before {
  content: '\201C';
  position: absolute;
  top: -10px; left: 14px;
  font-size: 56px;
  line-height: 1;
  color: var(--neon-b);
  opacity: 0.4;
  font-family: Georgia, serif;
}

.ceo-quote-text {
  font-size: 14px;
  line-height: 1.8;
  color: var(--text-dim);
  font-style: italic;
}

.ceo-quote-sig {
  margin-top: 10px;
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: var(--neon-b);
}

/* CTA BAND */
.cta-band {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 52px 40px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.cta-band::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--neon), var(--cyan), transparent);
}

/* Smoke orb for cta */
.cta-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(60px);
  pointer-events: none;
}
.cta-orb-1 { width:300px;height:300px;background:rgba(124,58,237,0.1);top:-100px;left:-80px; }
.cta-orb-2 { width:200px;height:200px;background:rgba(34,211,238,0.07);bottom:-60px;right:-60px; }

@media (max-width: 768px) {
  .values-grid { grid-template-columns: 1fr; }
  .stats-inner  { grid-template-columns: repeat(2,1fr); }
  .team-card    { padding: 28px 20px; }
  .cta-band     { padding: 36px 20px; }
}
</style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<!-- HERO -->
<div class="about-hero">
  <div class="smoke-layer">
    <div class="smoke-puff"></div>
    <div class="smoke-puff"></div>
    <div class="smoke-puff"></div>
  </div>
  <div class="hero-eyebrow" data-aos="fade-up">
    <span class="hero-eyebrow-dot"></span>
    Our Story
  </div>
  <h1 class="hero-title" data-aos="fade-up" data-aos-delay="80">
    <span class="hero-title-white">Tentang</span>
    <span class="hero-title-gradient">AY Vape</span>
  </h1>
  <p class="hero-desc" data-aos="fade-up" data-aos-delay="160">
    Kami menghadirkan pengalaman belanja vape modern dan terpercaya,
    dengan sistem transaksi langsung melalui admin dan komunikasi yang transparan.
  </p>
</div>

<!-- VALUES -->
<section class="section">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="section-eyebrow">Nilai Kami</div>
      <div class="section-title">Kenapa Pilih <span>AY Vape?</span></div>
    </div>

    <div class="values-grid">
      <div class="value-card" data-aos="fade-right">
        <span class="value-icon">🏪</span>
        <div class="value-title">Siapa Kami</div>
        <div class="value-desc">
          AY Vape adalah toko vape di Jember, Jawa Timur.
          Kami menyediakan produk vape dalam kondisi <strong>baru (original)</strong>
          maupun <strong>second/bekas</strong> yang dipilih dan dicek langsung oleh admin.            Transparansi dan kepercayaan pelanggan adalah prioritas utama kami.
        </div>  
      </div>

      <div class="value-card" data-aos="fade-left">
        <span class="value-icon">🚀</span>
        <div class="value-title">Misi Kami</div>
        <div class="value-desc">
          Memberikan layanan terbaik dengan harga kompetitif,
          proses transaksi yang jelas, serta penyerahan barang melalui
          <strong>COD atau kesepakatan langsung dengan admin</strong>.
        </div>
      </div>

      <div class="value-card" data-aos="fade-right" data-aos-delay="80">
        <span class="value-icon">🔐</span>
        <div class="value-title">Kualitas Terjamin</div>
        <div class="value-desc">
          Semua produk kami melewati verifikasi keaslian sebelum sampai ke tangan pelanggan.
          Garansi originalitas produk ada di setiap pembelian.
        </div>
      </div>

      <div class="value-card" data-aos="fade-left" data-aos-delay="80">
        <span class="value-icon">💬</span>
        <div class="value-title">Support</div>
        <div class="value-desc">
          Tim admin kami siap membantu melalui fitur chat website atau WhatsApp.
          Semua pertanyaan akan dibalas sesuai jam operasional admin.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS BAND -->
<div class="stats-band">
  <div class="stats-inner">
    <div class="stat-item" data-aos="zoom-in">
      <div class="stat-num">500+</div>
      <div class="stat-lbl">Produk</div>
    </div>
    <div class="stat-item" data-aos="zoom-in" data-aos-delay="80">
      <div class="stat-num">1K+</div>
      <div class="stat-lbl">Pelanggan</div>
    </div>
    <div class="stat-item" data-aos="zoom-in" data-aos-delay="160">
      <div class="stat-num">4.9★</div>
      <div class="stat-lbl">Rating</div>
    </div>
    <div class="stat-item" data-aos="zoom-in" data-aos-delay="240">
      <div class="stat-num">24/7</div>
      <div class="stat-lbl">Support</div>
    </div>
  </div>
</div>

<!-- TEAM -->
<section class="section">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <div class="section-eyebrow">CEO</div>
      <div class="section-title">Chief Executive <span>Office</span></div>
    </div>

    <div class="team-ceo-wrap">
      <div class="team-card" data-aos="fade-up">
        <div class="team-avatar">
          <img src="../uploads/CEO.jpeg" alt="Owner">
        </div>
        <div class="team-name">Muhammad Arip</div>
        <div class="team-role">Founder &amp; CEO</div>
        <div class="ceo-quote">
          <p class="ceo-quote-text">
            AY Vape lahir dari kecintaan kami terhadap dunia vaping dan keinginan untuk 
            menghadirkan produk berkualitas dengan harga yang jujur. Kepercayaan pelanggan 
            adalah segalanya — setiap produk yang kami jual telah kami seleksi sendiri 
            untuk memastikan kualitas terbaik sampai ke tangan Anda.
          </p>
          <div class="ceo-quote-sig">— Owner, AY Vape</div>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="cta-band" style="margin-top:48px;" data-aos="fade-up">
      <div class="cta-orb cta-orb-1"></div>
      <div class="cta-orb cta-orb-2"></div>
      <div class="section-eyebrow" style="margin-bottom:10px;">Ready to vape?</div>
      <div class="section-title" style="margin-bottom:16px;">Siap <span>Belanja?</span></div>
      <p style="color:var(--text-dim);font-size:15px;margin-bottom:28px;">
        Temukan koleksi terbaik kami sekarang dan rasakan perbedaannya.
      </p>
      <a href="index.php" class="btn btn-primary btn-lg">Lihat Produk →</a>
    </div>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
<a href="https://wa.me/6282333408651" class="wa-float">💬 Chat WA</a>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true, offset: 50 });</script>
</body>
</html>