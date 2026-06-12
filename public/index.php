<?php
session_start();
include "../config/db.php";

$jumlah      = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
$active_page = 'home';
$search      = $_GET['search']   ?? '';
$kategori    = $_GET['kategori'] ?? '';

$where = "WHERE 1=1";
if ($search   !== '') $where .= " AND nama_produk LIKE '%$search%'";
if ($kategori !== '') $where .= " AND id_kategori='$kategori'";

$data = mysqli_query($conn, "
  SELECT produk.*, kategori.nama_kategori
  FROM produk
  LEFT JOIN kategori ON produk.id_kategori = kategori.id
  $where
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>AY Vape — Premium Vape Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<style>

/* ══════════════════════════════════
   HERO
══════════════════════════════════ */
.hero {
  position: relative;
  height: 100vh;
  min-height: 600px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

#heroCanvas {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  z-index: 0;
}

.hero-content {
  position: relative;
  z-index: 2;
  text-align: center;
  padding: 0 24px;
  max-width: 760px;
}

/* VAPE DEVICE */
.vape-wrap {
  position: absolute;
  right: 8%;
  top: 50%;
  transform: translateY(-50%);
  z-index: 3;
  pointer-events: none;
  will-change: transform;
  filter: drop-shadow(0 0 20px rgba(124,58,237,0.45))
          drop-shadow(0 0 60px rgba(34,211,238,0.15));
}

/* EYEBROW */
.hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 18px;
  border: 1px solid rgba(168,85,247,0.4);
  border-radius: 999px;
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: #a855f7;
  margin-bottom: 26px;
  backdrop-filter: blur(8px);
  background: rgba(124,58,237,0.06);
  animation: fadeUp 1s ease 0.2s both;
}

.eyebrow-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: #a855f7;
  box-shadow: 0 0 8px #a855f7;
  animation: blink 1.5s ease-in-out infinite;
}

@keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.2} }

/* TITLE */
.hero-title {
  font-family: 'Orbitron', monospace;
  font-size: clamp(38px, 7vw, 88px);
  font-weight: 900;
  line-height: 1.02;
  letter-spacing: -3px;
  margin-bottom: 20px;
  animation: fadeUp 1s ease 0.35s both;
}

.hero-title-white    { color: #fff; display: block; }
.hero-title-gradient {
  display: block;
  background: linear-gradient(135deg, #a855f7 0%, #22d3ee 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero-desc {
  font-size: 15px;
  color: rgba(255,255,255,0.42);
  max-width: 440px;
  margin: 0 auto 32px;
  line-height: 1.75;
  animation: fadeUp 1s ease 0.5s both;
}

.hero-actions {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
  animation: fadeUp 1s ease 0.65s both;
}

/* STATS */
.hero-stats {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 28px;
  flex-wrap: wrap;
  margin-top: 48px;
  animation: fadeUp 1s ease 0.8s both;
}

.hero-stat-val {
  font-family: 'Orbitron', monospace;
  font-size: 26px;
  font-weight: 900;
  background: linear-gradient(135deg, #fff, #22d3ee);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  line-height: 1;
}

.hero-stat-label {
  font-family: 'Rajdhani', sans-serif;
  font-size: 9px;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  color: rgba(255,255,255,0.28);
  margin-top: 3px;
  text-align: center;
}

.hero-stat-divider {
  width: 1px;
  height: 32px;
  background: rgba(255,255,255,0.08);
}

/* SCROLL INDICATOR */
.scroll-ind {
  position: absolute;
  bottom: 28px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 3;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  opacity: 0;
  animation: fadeUp 1s ease 1.2s forwards;
}

.scroll-line {
  width: 1px;
  height: 44px;
  background: linear-gradient(to bottom, rgba(124,58,237,0.8), transparent);
  animation: scrollP 1.8s ease-in-out infinite;
}

.scroll-txt {
  font-family: 'Rajdhani', sans-serif;
  font-size: 9px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: rgba(255,255,255,0.28);
}

@keyframes scrollP {
  0%,100%{ transform:scaleY(1); opacity:.5; }
  50%{ transform:scaleY(1.3); opacity:1; }
}

/* ══════════════════════════════════
   SCROLL-DRIVEN 3D SECTION
══════════════════════════════════ */
.scroll-section {
  height: 300vh;
  position: relative;
  z-index: 1;
}

.scroll-sticky {
  position: sticky;
  top: 0;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: #03030a;
}

#scrollCanvas {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}

.scroll-text-wrap {
  position: absolute;
  left: 8%;
  z-index: 5;
  max-width: 380px;
}

.scroll-step {
  opacity: 0;
  transform: translateY(28px);
  transition: opacity 0.6s ease, transform 0.6s ease;
  pointer-events: none;
  position: absolute;
  top: 0; left: 0;
}

.scroll-step.active {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
  position: relative;
}

.scroll-step-num {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  letter-spacing: 3px;
  text-transform: uppercase;
  color: #a855f7;
  margin-bottom: 10px;
  font-weight: 700;
}

.scroll-step-title {
  font-family: 'Orbitron', monospace;
  font-size: clamp(24px, 3.5vw, 42px);
  font-weight: 900;
  color: #fff;
  line-height: 1.12;
  margin-bottom: 14px;
}

.scroll-step-desc {
  font-size: 14px;
  color: rgba(255,255,255,0.42);
  line-height: 1.75;
}

/* PROGRESS DOTS */
.scroll-dots {
  position: absolute;
  left: 8%;
  bottom: 40px;
  display: flex;
  gap: 8px;
  z-index: 5;
}

.scroll-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: rgba(255,255,255,0.2);
  transition: all 0.4s;
}

.scroll-dot.active {
  background: #a855f7;
  box-shadow: 0 0 8px rgba(168,85,247,0.6);
  width: 20px;
  border-radius: 3px;
}

/* GLOW DIVIDER */
.glow-div {
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(124,58,237,0.4), rgba(34,211,238,0.3), transparent);
}

/* ══════════════════════════════════
   PRODUCT SECTION
══════════════════════════════════ */
.product-section {
  padding: 80px 0;
  position: relative;
  z-index: 1;
  background: #03030a;
}

.cat-pills {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.cat-pill {
  padding: 6px 16px;
  border-radius: 999px;
  border: 1px solid var(--border);
  background: transparent;
  color: var(--text-dim);
  font-family: 'Rajdhani', sans-serif;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.5px;
  cursor: pointer;
  transition: all 0.25s;
  text-decoration: none;
}

.cat-pill:hover, .cat-pill.active {
  background: rgba(124,58,237,0.12);
  border-color: #a855f7;
  color: #fff;
}

.contact-mini {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 48px 40px;
  margin-top: 60px;
  position: relative;
  overflow: hidden;
}

.contact-mini::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  background: linear-gradient(90deg, transparent, #7c3aed, #22d3ee, transparent);
}

.contact-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 28px;
}

.contact-item {
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--border);
  border-radius: 14px;
  padding: 22px;
  transition: all 0.25s;
}

.contact-item:hover {
  border-color: rgba(124,58,237,0.4);
  transform: translateY(-3px);
}

.contact-item-icon { font-size: 24px; margin-bottom: 10px; }
.contact-item-title {
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase;
  color: var(--text-muted); margin-bottom: 6px;
}
.contact-item-val { font-size: 14px; color: var(--text-dim); line-height: 1.5; }

@keyframes fadeUp {
  from { opacity:0; transform:translateY(18px); }
  to   { opacity:1; transform:translateY(0); }
}

@media (max-width: 900px) {
  .vape-wrap { display: none; }
  .scroll-text-wrap { left: 5%; max-width: 300px; }
}

@media (max-width: 768px) {
  .hero { height: 100svh; }
  .contact-grid { grid-template-columns: 1fr; }
  .contact-mini { padding: 32px 20px; }
  .hero-stats { gap: 16px; }
  .hero-stat-divider { display: none; }
  .scroll-section { height: 250vh; }
}
</style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<!-- ═══════════════════════
     HERO
════════════════════════ -->
<section class="hero" id="hero">
  <canvas id="heroCanvas"></canvas>

  <!-- FLOATING VAPE DEVICE SVG -->
  <div class="vape-wrap" id="vapeWrap">
    <svg width="110" viewBox="0 0 90 260" fill="none" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="vg1" x1="0" y1="0" x2="90" y2="260" gradientUnits="userSpaceOnUse">
          <stop stop-color="#1a1a2e"/><stop offset="1" stop-color="#070714"/>
        </linearGradient>
        <linearGradient id="vg2" x1="20" y1="0" x2="70" y2="40" gradientUnits="userSpaceOnUse">
          <stop stop-color="#7c3aed"/><stop offset="1" stop-color="#22d3ee"/>
        </linearGradient>
      </defs>
      <rect x="18" y="55" width="54" height="190" rx="14" fill="url(#vg1)" stroke="rgba(124,58,237,0.6)" stroke-width="1"/>
      <rect x="26" y="20" width="38" height="38" rx="10" fill="url(#vg2)" opacity="0.95"/>
      <rect x="22" y="75" width="46" height="36" rx="6" fill="rgba(34,211,238,0.08)" stroke="rgba(34,211,238,0.35)" stroke-width="0.8"/>
      <line x1="28" y1="87" x2="62" y2="87" stroke="#22d3ee" stroke-width="0.8" opacity="0.6"/>
      <line x1="28" y1="93" x2="55" y2="93" stroke="#a855f7" stroke-width="0.8" opacity="0.5"/>
      <line x1="28" y1="99" x2="48" y2="99" stroke="#22d3ee" stroke-width="0.6" opacity="0.3"/>
      <rect x="30" y="122" width="30" height="10" rx="5" fill="rgba(124,58,237,0.3)" stroke="rgba(124,58,237,0.6)" stroke-width="0.8"/>
      <circle cx="45" cy="155" r="5" fill="#22d3ee" opacity="0.9">
        <animate attributeName="opacity" values="0.9;0.2;0.9" dur="2s" repeatCount="indefinite"/>
      </circle>
      <rect x="30" y="225" width="30" height="4" rx="2" fill="rgba(255,255,255,0.06)"/>
      <rect x="30" y="232" width="30" height="4" rx="2" fill="rgba(255,255,255,0.04)"/>
      <line x1="18" y1="120" x2="18" y2="200" stroke="rgba(168,85,247,0.5)" stroke-width="1.5"/>
    </svg>
  </div>

  <!-- HERO CONTENT -->
  <div class="hero-content">
    <div class="hero-eyebrow">
      <span class="eyebrow-dot"></span>
      Premium Vape Store · Jember, Indonesia
    </div>
    <h1 class="hero-title">
      <span class="hero-title-white">Elevate Your</span>
      <span class="hero-title-gradient">Vape Experience</span>
    </h1>
    <p class="hero-desc">
      Produk vape berkualitas, tersedia barang original dan barang second pilihan.
      Transaksi bisa COD, bayar langsung ke admin, atau menggunakan rekber Shopee sesuai kesepakatan.
    </p>
    <div class="hero-actions">
      <a href="#produk" class="btn btn-primary btn-lg">Belanja Sekarang →</a>
      <a href="../public/about.php" class="btn btn-secondary btn-lg">Tentang Kami</a>
    </div>
    <div class="hero-stats">
      <div class="hero-stat">
        <div class="hero-stat-val">500+</div>
        <div class="hero-stat-label">Produk</div>
      </div>
      <div class="hero-stat-divider"></div>
      <div class="hero-stat">
        <div class="hero-stat-val">1K+</div>
        <div class="hero-stat-label">Pelanggan</div>
      </div>
      <div class="hero-stat-divider"></div>
      <div class="hero-stat">
        <div class="hero-stat-val">4.9★</div>
        <div class="hero-stat-label">Rating</div>
      </div>
      <div class="hero-stat-divider"></div>
      <div class="hero-stat">
        <div class="hero-stat-val">24/7</div>
        <div class="hero-stat-label">Support</div>
      </div>
    </div>
  </div>

  <div class="scroll-ind">
    <div class="scroll-txt">Scroll</div>
    <div class="scroll-line"></div>
  </div>
</section>

<div class="glow-div"></div>

<!-- ═══════════════════════
     SCROLL-DRIVEN 3D SECTION
════════════════════════ -->
<div class="scroll-section" id="scrollSection">
  <div class="scroll-sticky">
    <canvas id="scrollCanvas"></canvas>

    <!-- TEXT STEPS -->
    <div class="scroll-text-wrap">
      <div class="scroll-step active" id="step0">
        <div class="scroll-step-num">01 — Vape</div>
        <div class="scroll-step-title">Produk<br>Original & Second</div>
        <div class="scroll-step-desc">
          Produk yang tersedia terdiri dari barang original dan barang second pilihan.
          Semua kondisi dijelaskan secara jujur dan transparan sebelum transaksi.
        </div>
      </div>
      <div class="scroll-step" id="step1">
        <div class="scroll-step-num">02 — Fast</div>
        <div class="scroll-step-title">Pengiriman<br>Super Cepat</div>
        <div class="scroll-step-desc">
          Pengantaran dilakukan dengan sistem COD atau bertemu langsung dengan admin.
          Untuk jarak jauh, bisa menggunakan rekber Shopee sesuai kesepakatan bersama.
          Biaya dan detail dibicarakan lewat chat website atau WhatsApp.
        </div>
      </div>
      <div class="scroll-step" id="step2">
        <div class="scroll-step-num">03 — Support</div>
        <div class="scroll-step-title">Support<br>24/7 Nonstop</div>
        <div class="scroll-step-desc">
          Admin siap membantu kapan saja via chat, WhatsApp,
          atau email. Tidak ada pertanyaan yang diabaikan.
        </div>
      </div>
    </div>

    <!-- PROGRESS DOTS -->
    <div class="scroll-dots">
      <div class="scroll-dot active" id="dot0"></div>
      <div class="scroll-dot" id="dot1"></div>
      <div class="scroll-dot" id="dot2"></div>
    </div>
  </div>
</div>

<div class="glow-div"></div>

<!-- ═══════════════════════
     PRODUCTS
════════════════════════ -->
<section class="product-section" id="produk">
  <div class="container">

    <div class="section-header fade-up">
      <div class="section-eyebrow">Koleksi Produk</div>
      <div class="section-title">Temukan <span>Vape Kamu</span></div>
    </div>

    <!-- FILTER -->
    <form method="GET" style="margin-bottom:24px;">
      <div class="filter-bar fade-up">
        <div class="cat-pills">
          <a href="index.php" class="cat-pill <?= $kategori==='' ? 'active':'' ?>">Semua</a>
          <?php
          $k = mysqli_query($conn, "SELECT * FROM kategori");
          while ($row = mysqli_fetch_assoc($k)):
          ?>
          <a href="?kategori=<?= $row['id'] ?><?= $search?'&search='.urlencode($search):'' ?>"
             class="cat-pill <?= $kategori==$row['id']?'active':'' ?>">
            <?= htmlspecialchars($row['nama_kategori']) ?>
          </a>
          <?php endwhile; ?>
        </div>
        <div style="display:flex;gap:8px;margin-left:auto;flex-wrap:wrap;">
          <input type="text" name="search" class="form-control" style="width:200px;"
                 placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
          <?php if ($kategori): ?>
            <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori) ?>">
          <?php endif; ?>
          <button type="submit" class="btn btn-primary btn-sm">Cari</button>
        </div>
      </div>
    </form>

    <!-- GRID -->
    <div class="product-grid">
      <?php $count=0; while ($p = mysqli_fetch_assoc($data)): $count++; ?>
      <div class="product-card" data-aos="fade-up" data-aos-delay="<?= ($count%4)*80 ?>">
        <div class="product-img">
          <img src="../uploads/products/<?= htmlspecialchars($p['gambar']) ?>"
               alt="<?= htmlspecialchars($p['nama_produk']) ?>">
          <span class="product-badge">Stok <?= $p['stok'] ?></span>
          <div class="product-img-overlay">
            <a href="product_detail.php?id=<?= $p['id'] ?>" class="btn btn-secondary btn-sm">Lihat Detail →</a>
          </div>
        </div>
        <div class="product-body">
          <div class="product-cat"><?= htmlspecialchars($p['nama_kategori'] ?? 'Uncategorized') ?></div>
          <div class="product-name"><?= htmlspecialchars($p['nama_produk']) ?></div>
          <div class="product-price">Rp <?= number_format($p['harga']) ?></div>
          <div class="product-actions">
            <a href="product_detail.php?id=<?= $p['id'] ?>"
               class="btn btn-secondary btn-sm" style="flex:1;justify-content:center;">Detail</a>
            <button onclick="addToCart(<?= $p['id'] ?>, this)"
                    class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">
              + Keranjang
            </button>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>

    <?php if ($count===0): ?>
    <div style="text-align:center;padding:80px 20px;color:var(--text-muted);">
      <div style="font-size:48px;opacity:0.2;margin-bottom:12px;">🔍</div>
      <div style="font-family:'Rajdhani',sans-serif;font-size:14px;letter-spacing:1px;">Produk tidak ditemukan.</div>
    </div>
    <?php endif; ?>

    <!-- CONTACT MINI -->
    <div class="contact-mini" data-aos="fade-up">
      <div class="section-eyebrow">Ada Pertanyaan?</div>
      <div class="section-title" style="font-size:24px;">Hubungi <span>Kami</span></div>
      <div class="contact-grid">
        <div class="contact-item" data-aos="fade-up" data-aos-delay="80">
          <div class="contact-item-icon">📍</div>
          <div class="contact-item-title">Alamat</div>
          <div class="contact-item-val">Jl. MT. Haryono No.46, Sumber Beringin, Jember, Jawa Timur</div>
        </div>
        <div class="contact-item" data-aos="fade-up" data-aos-delay="160">
          <div class="contact-item-icon">📱</div>
          <div class="contact-item-title">WhatsApp</div>
          <div class="contact-item-val">
            <a href="https://wa.me/6282333408651" style="color:#10b981;">+62 823-3340-8651</a>
          </div>
        </div>
        <div class="contact-item" data-aos="fade-up" data-aos-delay="240">
          <div class="contact-item-icon">📧</div>
          <div class="contact-item-title">Email</div>
          <div class="contact-item-val">rahmanadur7511@gmail.com</div>
        </div>
      </div>
    </div>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
<a href="https://wa.me/6282333408651" class="wa-float">💬 Chat WA</a>

<!-- ═══════════════════════
     HERO CANVAS SCRIPT
════════════════════════ -->
<script>
(function(){
  const cvs = document.getElementById('heroCanvas');
  const ctx  = cvs.getContext('2d');
  const hero = document.getElementById('hero');
  let W, H, gridOff=0;
  let mouse={x:0,y:0}, orbX, orbY;

  function resize(){
    W=cvs.width=hero.offsetWidth;
    H=cvs.height=hero.offsetHeight;
    orbX=W/2; orbY=H/2;
  }
  resize();
  window.addEventListener('resize',resize);
  window.addEventListener('mousemove',e=>{mouse.x=e.clientX;mouse.y=e.clientY;});

  /* GRID */
  function drawGrid(){
    const vp={x:W/2+(mouse.x-W/2)*.03,y:H*.58};
    const spread=W*1.7, depth=H*.52;
    ctx.save();
    for(let i=0;i<=22;i++){
      const t=((i/22+gridOff)%1), e=t*t;
      const y=vp.y+e*depth,xL=vp.x-e*spread/2,xR=vp.x+e*spread/2;
      const a=Math.min(t*1.8,1)*.14;
      ctx.beginPath();ctx.moveTo(xL,y);ctx.lineTo(xR,y);
      ctx.strokeStyle=`rgba(124,58,237,${a})`;ctx.lineWidth=.4+t*.7;ctx.stroke();
    }
    for(let i=0;i<=16;i++){
      const t=i/16,xF=vp.x+(t-.5)*W*.55,xN=vp.x+(t-.5)*spread;
      const a=(0.5-Math.abs(t-.5))*.2;
      ctx.beginPath();ctx.moveTo(xF,vp.y);ctx.lineTo(xN,vp.y+depth);
      ctx.strokeStyle=`rgba(124,58,237,${a})`;ctx.lineWidth=.4;ctx.stroke();
    }
    ctx.restore();
  }

  /* SMOKE */
  class Smoke{
    constructor(r){this.reset(r??true)}
    reset(rand){
      this.x=W*.44+(Math.random()-.5)*W*.22;
      this.y=rand?Math.random()*H:H*.82+Math.random()*60;
      this.r=25+Math.random()*75;this.vx=(Math.random()-.5)*.22;
      this.vy=-(0.3+Math.random()*.65);this.life=rand?Math.random()*280:0;
      this.maxL=220+Math.random()*160;this.maxA=.025+Math.random()*.05;
      this.hue=Math.random()>.55?270:190;
    }
    update(){
      this.x+=this.vx+Math.sin(this.life*.018)*.22;this.y+=this.vy;
      this.r+=.2;this.life++;
      this.alpha=Math.min(this.life/50,1-(this.life/this.maxL))*this.maxA;
      if(this.life>=this.maxL)this.reset(false);
    }
    draw(){
      const g=ctx.createRadialGradient(this.x,this.y,0,this.x,this.y,this.r);
      g.addColorStop(0,`hsla(${this.hue},75%,65%,${this.alpha})`);
      g.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(this.x,this.y,this.r,0,Math.PI*2);
      ctx.fillStyle=g;ctx.fill();
    }
  }

  /* PARTICLE */
  class Particle{
    constructor(){this.reset(true)}
    reset(rand){
      this.x=Math.random()*W;this.y=rand?Math.random()*H:H+10;
      this.r=.8+Math.random()*2;this.vx=(Math.random()-.5)*.2;
      this.vy=-(0.08+Math.random()*.3);this.alpha=.2+Math.random()*.5;
      this.hue=Math.random()>.5?270:190;this.pulse=Math.random()*Math.PI*2;
    }
    update(){
      this.x+=this.vx;this.y+=this.vy;this.pulse+=.02;
      if(this.y<-10||this.x<-10||this.x>W+10)this.reset(false);
    }
    draw(){
      const a=this.alpha*(.5+.5*Math.sin(this.pulse));
      ctx.beginPath();ctx.arc(this.x,this.y,this.r,0,Math.PI*2);
      ctx.fillStyle=`hsla(${this.hue},90%,72%,${a})`;ctx.fill();
    }
  }

  /* STREAK */
  class Streak{
    constructor(){this.reset(true)}
    reset(rand){
      this.x=Math.random()*W;this.y=rand?Math.random()*H:-30;
      this.len=50+Math.random()*100;this.spd=1.2+Math.random()*2.2;
      this.hue=Math.random()>.5?270:190;this.w=.4+Math.random()*.8;
      this.ang=(Math.random()-.5)*.2+Math.PI/2;this.alpha=0;
    }
    update(){
      this.y+=this.spd;this.x+=Math.cos(this.ang)*this.spd*.2;
      this.alpha=Math.min(this.alpha+.025,.4);
      if(this.y>H+this.len)this.reset(false);
    }
    draw(){
      const x2=this.x-Math.cos(this.ang)*this.len,y2=this.y-Math.sin(this.ang)*this.len;
      const g=ctx.createLinearGradient(x2,y2,this.x,this.y);
      g.addColorStop(0,'transparent');g.addColorStop(1,`hsla(${this.hue},90%,70%,${this.alpha})`);
      ctx.beginPath();ctx.moveTo(x2,y2);ctx.lineTo(this.x,this.y);
      ctx.strokeStyle=g;ctx.lineWidth=this.w;ctx.stroke();
    }
  }

  function drawAmbient(){
    [{x:W*.12,y:H*.18,r:320,h:270,a:.05},{x:W*.88,y:H*.78,r:260,h:190,a:.04}].forEach(o=>{
      const g=ctx.createRadialGradient(o.x,o.y,0,o.x,o.y,o.r);
      g.addColorStop(0,`hsla(${o.h},75%,55%,${o.a})`);g.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(o.x,o.y,o.r,0,Math.PI*2);ctx.fillStyle=g;ctx.fill();
    });
  }

  function drawOrb(){
    orbX+=(mouse.x-orbX)*.04;orbY+=(mouse.y-orbY)*.04;
    const g=ctx.createRadialGradient(orbX,orbY,0,orbX,orbY,280);
    g.addColorStop(0,'rgba(124,58,237,0.07)');g.addColorStop(1,'transparent');
    ctx.beginPath();ctx.arc(orbX,orbY,280,0,Math.PI*2);ctx.fillStyle=g;ctx.fill();
  }

  const smokes    = Array.from({length:28},()=>new Smoke(true));
  const particles = Array.from({length:80},()=>new Particle());
  const streaks   = Array.from({length:12},()=>new Streak());
  const vapeWrap  = document.getElementById('vapeWrap');
  let   vapeY=0, vapeRot=0;

  function heroLoop(){
    ctx.fillStyle='#03030a';ctx.fillRect(0,0,W,H);
    drawAmbient();drawOrb();
    gridOff=(gridOff+.0018)%1;drawGrid();
    smokes.forEach(s=>{s.update();s.draw()});
    streaks.forEach(s=>{s.update();s.draw()});
    particles.forEach(p=>{p.update();p.draw()});
    vapeY+=(Math.sin(Date.now()*.0008)*18-vapeY)*.05;
    vapeRot+=(Math.sin(Date.now()*.0005)*4-vapeRot)*.04;
    vapeWrap.style.transform=`translateY(calc(-50% + ${vapeY}px)) rotate(${vapeRot}deg)`;
    requestAnimationFrame(heroLoop);
  }
  heroLoop();
})();
</script>

<!-- ═══════════════════════
     SCROLL 3D CANVAS SCRIPT — Full 3D Vape Device
════════════════════════ -->
<script>
(function(){
  const cvs    = document.getElementById('scrollCanvas');
  const ctx    = cvs.getContext('2d');
  const sticky = cvs.parentElement;
  let W, H, rotY=0, progress=0;

  function resize(){
    W=cvs.width=sticky.offsetWidth;
    H=cvs.height=sticky.offsetHeight;
  }
  resize();
  window.addEventListener('resize',resize);

  /* ── 3D PROJECTION ─────────────────────────────── */
  function project(x,y,z,cpx,cpy,rotYaw,rotPitch=0){
    // Y-axis rotation (yaw)
    const cy=Math.cos(rotYaw), sy=Math.sin(rotYaw);
    const x1=x*cy+z*sy, z1=-x*sy+z*cy;
    // X-axis rotation (pitch)
    const cp=Math.cos(rotPitch), sp=Math.sin(rotPitch);
    const y2=y*cp-z1*sp, z2=y*sp+z1*cp;
    const fov=560, sc=fov/(fov+z2);
    return { sx:cpx+x1*sc, sy:cpy+y2*sc, sc, z:z2 };
  }

  /* ── DRAW BOX (6 faces, painter sorted) ─────────── */
  function drawBox(cpx,cpy,ox,oy,oz,w,h,d,fillFn,strokeCol,rotYaw,rotPitch=0){
    const hw=w/2,hh=h/2,hd=d/2;
    const raw=[
      [ox-hw,oy-hh,oz-hd],[ox+hw,oy-hh,oz-hd],
      [ox+hw,oy+hh,oz-hd],[ox-hw,oy+hh,oz-hd],
      [ox-hw,oy-hh,oz+hd],[ox+hw,oy-hh,oz+hd],
      [ox+hw,oy+hh,oz+hd],[ox-hw,oy+hh,oz+hd],
    ];
    const v=raw.map(([x,y,z])=>project(x,y,z,cpx,cpy,rotYaw,rotPitch));
    // faces: [indices, brightness]
    const faces=[
      [[0,1,2,3],.80], // front
      [[5,4,7,6],.35], // back
      [[4,0,3,7],.55], // left
      [[1,5,6,2],.70], // right
      [[4,5,1,0],.50], // top
      [[3,2,6,7],.30], // bottom
    ];
    // sort back→front by average z
    const sorted=faces.map(([idx,br])=>({
      idx, br,
      avgZ: idx.reduce((s,i)=>s+v[i].z,0)/4
    })).sort((a,b)=>b.avgZ-a.avgZ);

    sorted.forEach(({idx,br})=>{
      const pts=idx.map(i=>v[i]);
      // back-face cull
      const ax=pts[1].sx-pts[0].sx, ay=pts[1].sy-pts[0].sy;
      const bx=pts[3].sx-pts[0].sx, by=pts[3].sy-pts[0].sy;
      if(ax*by-ay*bx>0) return;
      ctx.beginPath();
      ctx.moveTo(pts[0].sx,pts[0].sy);
      pts.slice(1).forEach(p=>ctx.lineTo(p.sx,p.sy));
      ctx.closePath();
      ctx.fillStyle  = fillFn(br);
      ctx.strokeStyle= strokeCol;
      ctx.lineWidth  = .5;
      ctx.fill(); ctx.stroke();
    });
  }

  /* ── DRAW RING (ellipse projected) ──────────────── */
  function drawRing(cpx,cpy,ox,oy,oz,r,flatY,segs,col,lw,rotYaw,rotPitch=0){
    ctx.beginPath();
    for(let i=0;i<=segs;i++){
      const a=(i/segs)*Math.PI*2;
      const p=project(ox+Math.cos(a)*r, oy, oz+Math.sin(a)*r*flatY, cpx,cpy, rotYaw, rotPitch);
      i===0?ctx.moveTo(p.sx,p.sy):ctx.lineTo(p.sx,p.sy);
    }
    ctx.strokeStyle=col; ctx.lineWidth=lw; ctx.stroke();
  }

  /* ── DRAW CYLINDER ──────────────────────────────── */
  function drawCylinder(cpx,cpy,ox,oy,oz,r,h,segs,fillFn,strokeCol,rotYaw,rotPitch=0){
    const tops=[], bots=[];
    for(let i=0;i<segs;i++){
      const a=(i/segs)*Math.PI*2;
      tops.push(project(ox+Math.cos(a)*r, oy-h/2, oz+Math.sin(a)*r, cpx,cpy, rotYaw, rotPitch));
      bots.push(project(ox+Math.cos(a)*r, oy+h/2, oz+Math.sin(a)*r, cpx,cpy, rotYaw, rotPitch));
    }
    // side quads
    for(let i=0;i<segs;i++){
      const n=(i+1)%segs;
      const pts=[tops[i],tops[n],bots[n],bots[i]];
      const ax=pts[1].sx-pts[0].sx,ay=pts[1].sy-pts[0].sy;
      const bx=pts[3].sx-pts[0].sx,by_=pts[3].sy-pts[0].sy;
      if(ax*by_-ay*bx>0) continue;
      ctx.beginPath();pts.forEach((p,j)=>j===0?ctx.moveTo(p.sx,p.sy):ctx.lineTo(p.sx,p.sy));
      ctx.closePath();
      ctx.fillStyle=fillFn(.55); ctx.strokeStyle=strokeCol; ctx.lineWidth=.4; ctx.fill(); ctx.stroke();
    }
    // caps
    [tops,bots].forEach((ring,ri)=>{
      ctx.beginPath();ring.forEach((p,j)=>j===0?ctx.moveTo(p.sx,p.sy):ctx.lineTo(p.sx,p.sy));
      ctx.closePath();
      ctx.fillStyle=fillFn(ri===0?.75:.28); ctx.strokeStyle=strokeCol; ctx.lineWidth=.4; ctx.fill(); ctx.stroke();
    });
  }

  /* ── ORBIT PARTICLES ─────────────────────────────── */
  const pts=Array.from({length:110},()=>({
    theta:Math.random()*Math.PI*2, phi:Math.random()*Math.PI,
    r:120+Math.random()*100,
    speed:(.002+Math.random()*.004)*(Math.random()>.5?1:-1),
    size:1+Math.random()*2.2, hue:Math.random()>.5?270:190, alpha:.3+Math.random()*.5,
  }));

  /* ── ORBITAL RINGS ──────────────────────────────── */
  const orbitRings=[
    {r:115,tilt:.28,rot:0,sp:.009,col:'rgba(168,85,247,.38)',lw:1.4},
    {r:160,tilt:.62,rot:1,sp:-.006,col:'rgba(34,211,238,.26)',lw:.9},
    {r:205,tilt:1.05,rot:2,sp:.004,col:'rgba(168,85,247,.17)',lw:.7},
  ];

  /* ── SMOKE ──────────────────────────────────────── */
  class DS{
    constructor(){this.reset()}
    reset(){
      this.x=(Math.random()-.5)*18; this.y=-(230+progress*50); this.z=(Math.random()-.5)*14;
      this.vx=(Math.random()-.5)*.45; this.vy=-(0.5+Math.random()*.75); this.vz=(Math.random()-.5)*.25;
      this.r=9+Math.random()*13; this.life=0; this.maxL=85+Math.random()*65;
      this.hue=Math.random()>.5?270:190;
    }
    update(){
      this.x+=this.vx; this.y+=this.vy; this.z+=this.vz; this.r+=.4; this.life++;
      this.alpha=Math.min(this.life/22,1-(this.life/this.maxL))*.14;
      if(this.life>=this.maxL)this.reset();
    }
  }
  const devSmokes=Array.from({length:16},()=>{const s=new DS();s.life=Math.random()*s.maxL;return s;});

  /* ── BACKGROUND GRID ─────────────────────────────── */
  let sgOff=0;
  function drawScrollGrid(cpx,cpy){
    const spread=W*1.55, depth=H*.5;
    for(let i=0;i<=20;i++){
      const t=((i/20+sgOff)%1),e=t*t;
      const y=cpy+e*depth, xL=cpx-e*spread/2, xR=cpx+e*spread/2;
      const a=Math.min(t*2,1)*.09;
      ctx.beginPath();ctx.moveTo(xL,y);ctx.lineTo(xR,y);
      ctx.strokeStyle=`rgba(124,58,237,${a})`;ctx.lineWidth=.3+t*.55;ctx.stroke();
    }
    for(let i=0;i<=14;i++){
      const t=i/14, xF=cpx+(t-.5)*W*.15, xN=cpx+(t-.5)*spread;
      const a=(0.5-Math.abs(t-.5))*.14;
      ctx.beginPath();ctx.moveTo(xF,cpy);ctx.lineTo(xN,cpy+depth);
      ctx.strokeStyle=`rgba(124,58,237,${a})`;ctx.lineWidth=.28;ctx.stroke();
    }
  }

  /* ── DRAW FULL VAPE ─────────────────────────────── */
  function drawVape(cpx,cpy,rotYaw,sc){
    const rp=0; // pitch
    const S=sc; // global scale multiplier applied via coordinate scaling

    // Color factories
    const BODY  = br=>`hsla(240,35%,${10+br*8}%,${br})`;
    const FACE  = br=>`hsla(240,45%,${8+br*7}%,${br})`;
    const PUR   = br=>`hsla(268,70%,${45+br*15}%,${br})`;
    const CYAN  = br=>`hsla(186,82%,${50+br*15}%,${br})`;
    const COILC = br=>`hsla(258,48%,${15+br*10}%,${br})`;
    const WIRE  = 'rgba(168,85,247,.65)';
    const EDGE  = 'rgba(124,58,237,.22)';
    const EDGEC = 'rgba(34,211,238,.25)';

    // ── MAIN BODY
    drawBox(cpx,cpy,  0,  0,0,  100*S,300*S,36*S,  BODY, EDGE,  rotYaw,rp);
    drawBox(cpx,cpy,  0,  0,2*S, 96*S,296*S,28*S,  FACE, EDGE,  rotYaw,rp);

    // ── SIDE ACCENT STRIPE
    drawBox(cpx,cpy, 51*S, 0,0,  6*S,240*S,38*S,  PUR, 'rgba(168,85,247,.45)', rotYaw,rp);

    // ── SCREEN BEZEL
    drawBox(cpx,cpy,  0,-72*S,20*S,  68*S,80*S,10*S,  br=>`hsla(230,55%,${8+br*6}%,${br})`, EDGEC, rotYaw,rp);
    // Screen glass (emissive)
    drawBox(cpx,cpy,  0,-72*S,26*S,  62*S,74*S,5*S,  br=>`hsla(190,80%,${12+br*10}%,${br*.9})`, EDGEC, rotYaw,rp);
    // Screen scanlines
    for(let i=0;i<4;i++){
      const sy=-84*S+i*14*S;
      const L=project(-28*S,sy,28*S,cpx,cpy,rotYaw,rp);
      const R=project( 28*S,sy,28*S,cpx,cpy,rotYaw,rp);
      ctx.beginPath();ctx.moveTo(L.sx,L.sy);ctx.lineTo(R.sx,R.sy);
      ctx.strokeStyle=i===0?'rgba(34,211,238,.5)':'rgba(168,85,247,.28)';
      ctx.lineWidth=.8*L.sc;ctx.stroke();
    }

    // ── POWER BUTTON (side)
    drawBox(cpx,cpy, -52*S, 20*S, 0,  8*S,32*S,40*S,  PUR, WIRE, rotYaw,rp);

    // ── ADJUST BUTTONS (side)
    drawBox(cpx,cpy, -52*S,-28*S, 0,  8*S,18*S,40*S,  br=>`hsla(220,40%,${12+br*8}%,${br})`, EDGE, rotYaw,rp);
    drawBox(cpx,cpy, -52*S,-50*S, 0,  8*S,18*S,40*S,  br=>`hsla(220,40%,${12+br*8}%,${br})`, EDGE, rotYaw,rp);

    // ── LED INDICATOR
    const ledPulse=0.65+Math.sin(Date.now()*.0028)*.35;
    drawBox(cpx,cpy, 0,12*S,20*S,  14*S,14*S,6*S,  br=>`rgba(34,211,238,${br*ledPulse})`, 'rgba(34,211,238,.8)', rotYaw,rp);
    const ledP=project(0,12*S,20*S,cpx,cpy,rotYaw,rp);
    const lg=ctx.createRadialGradient(ledP.sx,ledP.sy,0,ledP.sx,ledP.sy,26*S*ledPulse);
    lg.addColorStop(0,`rgba(34,211,238,${.5*ledPulse})`);lg.addColorStop(1,'transparent');
    ctx.beginPath();ctx.arc(ledP.sx,ledP.sy,26*S*ledPulse,0,Math.PI*2);ctx.fillStyle=lg;ctx.fill();

    // ── COIL HOUSING
    drawCylinder(cpx,cpy, 0,105*S,0, 30*S,55*S, 18, COILC, EDGE, rotYaw,rp);
    // Coil wire rings (6 rings)
    for(let i=0;i<6;i++){
      drawRing(cpx,cpy, 0, 83*S+i*9*S, 0, 24*S,.42, 32, `rgba(168,85,247,${.55+i*.04})`, 1.5, rotYaw,rp);
    }
    // Coil center glow
    const coilP=project(0,105*S,0,cpx,cpy,rotYaw,rp);
    const cg=ctx.createRadialGradient(coilP.sx,coilP.sy,0,coilP.sx,coilP.sy,20*S);
    cg.addColorStop(0,'rgba(168,85,247,.4)');cg.addColorStop(1,'transparent');
    ctx.beginPath();ctx.arc(coilP.sx,coilP.sy,20*S,0,Math.PI*2);ctx.fillStyle=cg;ctx.fill();

    // ── E-LIQUID WINDOW (glass side strip)
    drawBox(cpx,cpy, -52*S, 60*S, 0,  7*S,110*S,36*S,
      br=>`rgba(34,211,238,${br*.22})`, 'rgba(34,211,238,.38)', rotYaw,rp);

    // ── AIR VENTS (bottom)
    for(let i=0;i<4;i++){
      drawBox(cpx,cpy, 0, 128*S+i*11*S, 20*S,  72*S,6*S,4*S,
        ()=>'rgba(10,5,25,.92)', 'rgba(124,58,237,.2)', rotYaw,rp);
    }

    // ── BOTTOM CAP
    drawBox(cpx,cpy, 0,155*S,0, 100*S,16*S,36*S, PUR, 'rgba(168,85,247,.5)', rotYaw,rp);
    // USB port
    drawBox(cpx,cpy, 0,163*S,19*S, 22*S,8*S,6*S,
      br=>`hsla(220,40%,${10+br*6}%,${br})`, EDGEC, rotYaw,rp);

    // ── MOUTHPIECE (tapered)
    drawCylinder(cpx,cpy, 0,-215*S,0, 24*S,60*S, 16,  PUR, WIRE, rotYaw,rp);
    // Mouthpiece tip (narrower, cyan)
    drawCylinder(cpx,cpy, 0,-256*S,0, 11*S,32*S, 12,  CYAN, EDGEC, rotYaw,rp);
    // Tip glow
    const tipP=project(0,-256*S,0,cpx,cpy,rotYaw,rp);
    const tg=ctx.createRadialGradient(tipP.sx,tipP.sy,0,tipP.sx,tipP.sy,30*S);
    tg.addColorStop(0,'rgba(34,211,238,.3)');tg.addColorStop(1,'transparent');
    ctx.beginPath();ctx.arc(tipP.sx,tipP.sy,30*S,0,Math.PI*2);ctx.fillStyle=tg;ctx.fill();
    // Mouthpiece glow ring
    drawRing(cpx,cpy, 0,-256*S,0, 14*S,.42, 24, 'rgba(34,211,238,.55)', 1.2, rotYaw,rp);
  }

  /* ── MAIN RENDER LOOP ────────────────────────────── */
  function loop(){
    ctx.fillStyle='#03030a';ctx.fillRect(0,0,W,H);

    const cpx=W*.64, cpy=H*.5;
    rotY+=.012;
    const rot=rotY + progress*Math.PI*1.5;

    sgOff=(sgOff+.0015)%1;
    drawScrollGrid(cpx, cpy+H*.1);

    /* Ambient glow blobs */
    const ga=ctx.createRadialGradient(cpx,cpy,0,cpx,cpy,340);
    ga.addColorStop(0,`rgba(124,58,237,${.05+progress*.08})`);ga.addColorStop(1,'transparent');
    ctx.beginPath();ctx.arc(cpx,cpy,340,0,Math.PI*2);ctx.fillStyle=ga;ctx.fill();

    if(progress>.35){
      const pa=progress-.35;
      const gc=ctx.createRadialGradient(cpx,cpy-100,0,cpx,cpy-100,200);
      gc.addColorStop(0,`rgba(34,211,238,${pa*.08})`);gc.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(cpx,cpy-100,200,0,Math.PI*2);ctx.fillStyle=gc;ctx.fill();
    }

    /* Orbit rings */
    orbitRings.forEach(rg=>{
      rg.rot+=rg.sp;
      drawRing(cpx,cpy, 0,0,0, rg.r,.38, 64, rg.col, rg.lw,
        rot + rg.rot,
        rg.tilt
      );
    });

    /* Orbiting particles */
    pts.forEach(p=>{
      p.theta+=p.speed;
      const x=Math.sin(p.phi)*Math.cos(p.theta)*p.r;
      const y=Math.cos(p.phi)*p.r*.42;
      const z=Math.sin(p.phi)*Math.sin(p.theta)*p.r;
      const pr=project(x,y,z,cpx,cpy,rot);
      ctx.beginPath();ctx.arc(pr.sx,pr.sy,p.size*pr.sc,0,Math.PI*2);
      ctx.fillStyle=`hsla(${p.hue},90%,72%,${p.alpha*pr.sc})`;ctx.fill();
    });

    /* Smoke from mouthpiece (step 3 onwards) */
    if(progress>.65){
      devSmokes.forEach(s=>{
        s.update();
        const pr=project(s.x,s.y,s.z,cpx,cpy,rot);
        const sg=ctx.createRadialGradient(pr.sx,pr.sy,0,pr.sx,pr.sy,s.r*pr.sc);
        sg.addColorStop(0,`hsla(${s.hue},70%,72%,${s.alpha})`);sg.addColorStop(1,'transparent');
        ctx.beginPath();ctx.arc(pr.sx,pr.sy,s.r*pr.sc,0,Math.PI*2);ctx.fillStyle=sg;ctx.fill();
      });
    }

    /* ── DRAW THE FULL 3D VAPE ── */
    // Scale: 1 at step0, grows slightly with progress
    const devScale = (1 + progress*.25) * (Math.min(W,H) / 780);
    // Float Y offset
    const floatOff = Math.sin(Date.now()*.0007)*14;
    const savedTransform = ctx.getTransform();
    ctx.translate(0, floatOff);
    drawVape(cpx, cpy, rot, devScale);
    ctx.setTransform(savedTransform);

    /* Full glow burst at step 3 */
    if(progress>.75){
      const pa=progress-.75;
      const fg=ctx.createRadialGradient(cpx,cpy,0,cpx,cpy,250);
      fg.addColorStop(0,`rgba(168,85,247,${pa*.18})`);fg.addColorStop(1,'transparent');
      ctx.beginPath();ctx.arc(cpx,cpy,250,0,Math.PI*2);ctx.fillStyle=fg;ctx.fill();
    }

    requestAnimationFrame(loop);
  }
  loop();

  /* ── SCROLL HANDLER ─────────────────────────────── */
  const section = document.getElementById('scrollSection');
  const steps   = [
    document.getElementById('step0'),
    document.getElementById('step1'),
    document.getElementById('step2'),
  ];
  const dots = [
    document.getElementById('dot0'),
    document.getElementById('dot1'),
    document.getElementById('dot2'),
  ];

  function onScroll(){
    const rect    = section.getBoundingClientRect();
    const total   = section.offsetHeight - window.innerHeight;
    const scrolled= -rect.top;
    progress = Math.max(0, Math.min(1, scrolled / total));

    const idx = Math.min(Math.floor(progress * 3), 2);
    steps.forEach((s,i)=> s.classList.toggle('active', i===idx));
    dots.forEach((d,i)=>  d.classList.toggle('active', i===idx));
  }

  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();
})();
</script>

<!-- AOS + CART -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({duration:700, once:true, offset:60});

function addToCart(id, btn){
  const orig = btn.textContent;
  btn.textContent='...'; btn.disabled=true;
  fetch('../ajax/add_to_cart.php',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'id='+id
  })
  .then(r=>r.json())
  .then(d=>{
    showToast(d.msg||'Ditambahkan!','success');
    const badge=document.querySelector('.cart-count');
    if(d.total!==undefined){
      if(badge) badge.textContent=d.total;
      else{
        const cb=document.querySelector('.cart-btn');
        if(cb){const sp=document.createElement('span');sp.className='cart-count';sp.textContent=d.total;cb.appendChild(sp);}
      }
    }
  })
  .finally(()=>{btn.textContent='✔ Ditambah';setTimeout(()=>{btn.textContent=orig;btn.disabled=false;},1500);});
}

function showToast(text,type='info'){
  const t=document.createElement('div');
  t.className='toast toast-'+type; t.textContent=text;
  document.body.appendChild(t);
  setTimeout(()=>{t.style.opacity='0';t.style.transition='.3s';},2200);
  setTimeout(()=>t.remove(),2600);
}
</script>

</body>
</html>