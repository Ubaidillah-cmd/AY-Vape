<?php
$jumlah      = 0;
$active_page = '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cara Order — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<style>

/* ── PAGE HERO ── */
.info-hero {
  padding: 72px 28px 56px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.info-hero::before {
  content: '';
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 80% 50% at 50% -5%, rgba(124,58,237,0.22) 0%, transparent 65%),
    radial-gradient(ellipse 40% 30% at 85% 90%, rgba(34,211,238,0.08) 0%, transparent 60%);
  z-index: -1;
}

/* ── STEP TIMELINE ── */
.step-timeline {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 0;
}

/* Vertical line */
.step-timeline::before {
  content: '';
  position: absolute;
  left: 28px;
  top: 28px;
  bottom: 28px;
  width: 2px;
  background: linear-gradient(to bottom, var(--neon), var(--cyan), transparent);
  opacity: 0.4;
}

.step-row {
  display: grid;
  grid-template-columns: 58px 1fr;
  gap: 24px;
  padding: 0 0 32px 0;
  align-items: flex-start;
  position: relative;
}

.step-row:last-child { padding-bottom: 0; }

/* Bubble */
.step-bubble {
  width: 58px; height: 58px;
  border-radius: 50%;
  background: rgba(124,58,237,0.12);
  border: 2px solid rgba(124,58,237,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  gap: 1px;
  flex-shrink: 0;
  position: relative;
  z-index: 1;
  transition: all var(--t) var(--ease);
}

.step-bubble::after {
  content: '';
  position: absolute;
  inset: -6px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(124,58,237,0.15), transparent 70%);
  opacity: 0;
  transition: opacity var(--t);
}

.step-row:hover .step-bubble {
  border-color: var(--neon-b);
  background: rgba(124,58,237,0.2);
  box-shadow: 0 0 20px var(--glow);
}

.step-row:hover .step-bubble::after { opacity: 1; }

.step-num {
  font-family: 'Orbitron', monospace;
  font-size: 18px;
  font-weight: 900;
  color: var(--neon-b);
  line-height: 1;
}

.step-emoji { font-size: 16px; line-height: 1; }

/* Step content */
.step-content {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px 22px;
  transition: all var(--t) var(--ease);
  position: relative;
  overflow: hidden;
}

.step-content::before {
  content: '';
  position: absolute; top:0; left:0; right:0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(124,58,237,0.6), transparent);
  opacity: 0;
  transition: opacity var(--t);
}

.step-row:hover .step-content {
  border-color: var(--border-glow);
  transform: translateX(4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.4);
}

.step-row:hover .step-content::before { opacity: 1; }

.step-title {
  font-family: 'Rajdhani', sans-serif;
  font-size: 17px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 6px;
  letter-spacing: 0.3px;
}

.step-desc {
  font-size: 14px;
  line-height: 1.7;
  color: var(--text-dim);
}

.step-tag {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  margin-top: 10px;
  padding: 4px 12px;
  border-radius: var(--radius-pill);
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  background: rgba(124,58,237,0.1);
  border: 1px solid rgba(124,58,237,0.25);
  color: var(--neon-b);
}

/* ── TIPS BOX ── */
.tips-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

.tip-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 22px;
  transition: all var(--t) var(--ease);
}

.tip-card:hover {
  border-color: rgba(34,211,238,0.35);
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.4);
}

.tip-icon {
  font-size: 28px;
  margin-bottom: 10px;
  display: block;
}

.tip-title {
  font-family: 'Rajdhani', sans-serif;
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 6px;
}

.tip-desc { font-size: 13px; color: var(--text-dim); line-height: 1.65; }

/* ── CTA ── */
.cta-strip {
  background: linear-gradient(135deg, rgba(124,58,237,0.12), rgba(34,211,238,0.06));
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
  background: linear-gradient(90deg, transparent, var(--neon), var(--cyan), transparent);
}

@media (max-width: 768px) {
  .tips-grid { grid-template-columns: 1fr; }
  .step-timeline::before { left: 22px; }
  .step-bubble { width: 46px; height: 46px; }
  .step-row { grid-template-columns: 46px 1fr; gap: 14px; }
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
    Panduan Belanja
  </div>
  <h1 class="hero-title">
    <span class="hero-title-white">Cara</span>
    <span class="hero-title-gradient">Order</span>
  </h1>
  <p class="hero-desc">
    Ikuti langkah-langkah mudah di bawah ini dan pesanan kamu akan segera diproses.
  </p>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">

    <!-- STEP TIMELINE -->
    <div class="section-header fade-up">
      <div class="section-eyebrow">Step by Step</div>
      <div class="section-title">Langkah <span>Pemesanan</span></div>
    </div>

    <div class="step-timeline" style="max-width:700px;">

      <div class="step-row fade-up">
        <div class="step-bubble">
          <span class="step-num">01</span>
          <span class="step-emoji">🔍</span>
        </div>
        <div class="step-content">
          <div class="step-title">Pilih Produk</div>
            <div class="step-desc">
              Browse koleksi produk kami di halaman utama. Gunakan filter kategori atau search bar
              untuk menemukan produk yang kamu inginkan dengan cepat.

              <br><br>
              <strong>Catatan:</strong> Produk yang tersedia merupakan produk <strong>original</strong>
              dan produk <strong>second / bekas</strong> yang dibeli dan dijual kembali langsung oleh admin.
              Setiap produk akan dijelaskan secara transparan mengenai kondisi dan kelengkapannya
              pada deskripsi produk atau melalui chat dengan admin.
            </div>
          <span class="step-tag">⌕ Search &amp; Filter tersedia</span>
        </div>
      </div>

      <div class="step-row fade-up-1">
        <div class="step-bubble">
          <span class="step-num">02</span>
          <span class="step-emoji">🛒</span>
        </div>
        <div class="step-content">
          <div class="step-title">Tambah ke Keranjang</div>
          <div class="step-desc">
            Klik tombol <strong style="color:#fff;">+ Keranjang</strong> pada produk yang diinginkan.
            Kamu bisa menambahkan beberapa produk sekaligus sebelum checkout.
            Jumlah item di keranjang akan diupdate otomatis di navbar.
          </div>
          <span class="step-tag">🛒 Multi-item didukung</span>
        </div>
      </div>

      <div class="step-row fade-up-2">
        <div class="step-bubble">
          <span class="step-num">03</span>
          <span class="step-emoji">📋</span>
        </div>
        <div class="step-content">
          <div class="step-title">Isi Data Pembeli</div>
          <div class="step-desc">
            Masuk ke halaman Checkout dan isi data diri kamu: nama lengkap, nomor WhatsApp,
            dan alamat atau titik temu yang disepakati. Pastikan semua informasi benar
            agar proses COD dengan admin berjalan lancar.
          </div>
          <span class="step-tag">✏️ Isi data dengan benar</span>
        </div>
      </div>

      <div class="step-row fade-up-3">
        <div class="step-bubble">
          <span class="step-num">04</span>
          <span class="step-emoji">💳</span>
        </div>
        <div class="step-content">
          <div class="step-title">Pilih Metode Pembayaran</div>
          <div class="step-desc">
            Pembayaran dapat dilakukan dengan <strong>Cash</strong> atau <strong>Transfer</strong>
            dan tetap dilakukan saat bertemu langsung dengan admin (COD).
            Jika jarak pengantaran jauh, bisa menggunakan <strong>Rekber Shopee</strong>.
            Biaya rekber dapat dibicarakan langsung melalui chat website atau WhatsApp admin.
          </div>
          <span class="step-tag">💳 QRIS · COD tersedia</span>
        </div>
      </div>

      <div class="step-row fade-up-4">
        <div class="step-bubble">
          <span class="step-num">05</span>
          <span class="step-emoji">📸</span>
        </div>
        <div class="step-content">
          <div class="step-title">Upload Bukti Transfer</div>
          <div class="step-desc">
            Jika memilih transfer, upload foto bukti pembayaran yang jelas. Pastikan nominal,
            nama penerima, dan tanggal transaksi terlihat. Klik <strong style="color:#fff;">Konfirmasi Pembayaran</strong>
            untuk mengirim bukti ke admin.
          </div>
          <span class="step-tag">📎 Format JPG / PNG / WEBP</span>
        </div>
      </div>

      <div class="step-row fade-up">
        <div class="step-bubble">
          <span class="step-num">06</span>
          <span class="step-emoji">✅</span>
        </div>
        <div class="step-content">
          <div class="step-title">Pesanan Diproses</div>
          <div class="step-desc">
            Setelah order dibuat, kamu bisa menekan tombol <strong>Chat Admin</strong>
            di halaman struk untuk menghubungi admin.
            Jika belum ada balasan, silakan menunggu.
            Apabila lebih dari 5 menit belum dibalas, sistem akan mengarahkan kamu
            untuk melanjutkan komunikasi melalui WhatsApp.
          </div>
          <span class="step-tag">⚡ Proses 1×24 jam</span>
        </div>
      </div>

    </div>

    <!-- TIPS -->
    <div class="section-header fade-up" style="margin-top:60px;">
      <div class="section-eyebrow">Pro Tips</div>
      <div class="section-title">Tips <span>Belanja</span></div>
    </div>

    <div class="tips-grid">
      <div class="tip-card fade-up">
        <span class="tip-icon">📱</span>
        <div class="tip-title">Simpan Nomor Kami</div>
        <div class="tip-desc">
          Simpan nomor WA AY Vape di kontak kamu untuk memudahkan komunikasi order dan update status pengantaran.
        </div>
      </div>
      <div class="tip-card fade-up-1">
        <span class="tip-icon">ℹ️</span>
        <div class="tip-title">Perhatikan Keterangan Produk</div>
        <div class="tip-desc">
          Beberapa produk merupakan barang original resmi dan sebagian lainnya adalah produk
          second / bekas yang dijual langsung oleh admin.
          Pastikan membaca deskripsi atau menanyakan kondisi produk melalui chat admin
          sebelum melakukan pembayaran.
        </div>
      </div>
      <div class="tip-card fade-up-2">
        <span class="tip-icon">💬</span>
        <div class="tip-title">Gunakan Fitur Chat</div>
        <div class="tip-desc">
          Gunakan fitur chat untuk menghubungi admin kami.
          Jika admin belum sempat membalas, kamu akan diarahkan untuk melanjutkan
          komunikasi melalui WhatsApp.
      </div>
    </div>

    <!-- CTA -->
    <div class="cta-strip fade-up">
      <div class="section-eyebrow" style="margin-bottom:8px;">Sudah siap?</div>
      <div class="section-title" style="font-size:26px;margin-bottom:14px;">
        Mulai <span>Belanja Sekarang</span>
      </div>
      <p style="color:var(--text-dim);font-size:14px;margin-bottom:24px;">
        Ribuan produk vape original menunggu kamu.
      </p>
      <a href="index.php" class="btn btn-primary btn-lg">Lihat Produk →</a>
    </div>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
<a href="https://wa.me/6282333408651" class="wa-float">💬 Chat WA</a>
</body>
</html>
