<?php
$jumlah      = 0;
$active_page = '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>FAQ — AY Vape</title>
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
  background: radial-gradient(ellipse 80% 50% at 50% -5%, rgba(124,58,237,0.2) 0%, transparent 65%);
  z-index: -1;
}

/* ── SEARCH ── */
.faq-search-wrap {
  max-width: 520px;
  margin: 0 auto 48px;
  position: relative;
}

.faq-search {
  width: 100%;
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-pill);
  color: var(--text);
  padding: 14px 48px 14px 20px;
  font-family: 'DM Sans', sans-serif;
  font-size: 15px;
  outline: none;
  transition: all var(--t) var(--ease);
}

.faq-search:focus {
  border-color: var(--neon-b);
  background: rgba(124,58,237,0.06);
  box-shadow: 0 0 0 4px rgba(124,58,237,0.1);
}

.faq-search::placeholder { color: var(--text-muted); }

.faq-search-icon {
  position: absolute;
  right: 18px; top: 50%;
  transform: translateY(-50%);
  font-size: 18px;
  opacity: 0.35;
  pointer-events: none;
}

/* ── CATEGORY TABS ── */
.faq-tabs {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 32px;
}

.faq-tab {
  padding: 8px 18px;
  border-radius: var(--radius-pill);
  border: 1px solid var(--border);
  background: transparent;
  color: var(--text-dim);
  font-family: 'Rajdhani', sans-serif;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.5px;
  cursor: pointer;
  transition: all var(--t) var(--ease);
}

.faq-tab:hover   { background: rgba(124,58,237,0.08); color: #fff; border-color: var(--border-glow); }
.faq-tab.active  { background: rgba(124,58,237,0.15); color: #fff; border-color: var(--neon-b); }

/* ── ACCORDION ── */
.faq-section {
  margin-bottom: 36px;
}

.faq-section-title {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: 'Orbitron', monospace;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 1px;
  color: var(--text-muted);
  text-transform: uppercase;
  margin-bottom: 14px;
  padding-bottom: 10px;
  border-bottom: 1px solid var(--border);
}

.faq-section-title::before {
  content: '';
  width: 3px; height: 18px;
  border-radius: 99px;
  background: linear-gradient(to bottom, var(--neon), var(--cyan));
  flex-shrink: 0;
}

.faq-item {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  margin-bottom: 8px;
  overflow: hidden;
  transition: border-color var(--t) var(--ease);
}

.faq-item.open { border-color: var(--border-glow); }

.faq-question {
  width: 100%;
  background: transparent;
  border: none;
  padding: 18px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  cursor: pointer;
  text-align: left;
  transition: background var(--t);
}

.faq-question:hover { background: rgba(124,58,237,0.04); }

.faq-item.open .faq-question { background: rgba(124,58,237,0.06); }

.faq-q-text {
  font-family: 'Rajdhani', sans-serif;
  font-size: 15px;
  font-weight: 600;
  color: #fff;
  line-height: 1.4;
  flex: 1;
}

.faq-chevron {
  width: 28px; height: 28px;
  border-radius: 50%;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  color: var(--text-muted);
  font-size: 12px;
  flex-shrink: 0;
  transition: all var(--t) var(--ease);
}

.faq-item.open .faq-chevron {
  background: rgba(124,58,237,0.15);
  border-color: var(--neon-b);
  color: var(--neon-b);
  transform: rotate(180deg);
}

.faq-answer {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.faq-item.open .faq-answer { max-height: 400px; }

.faq-answer-inner {
  padding: 0 20px 18px;
  font-size: 14px;
  line-height: 1.75;
  color: var(--text-dim);
  border-top: 1px solid rgba(255,255,255,0.04);
}

.faq-answer-inner a {
  color: var(--neon-b);
  text-decoration: underline;
  text-decoration-color: rgba(168,85,247,0.4);
}

.faq-answer-inner strong { color: #fff; }

.faq-answer-inner ul {
  list-style: none;
  margin: 10px 0 0;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.faq-answer-inner ul li::before {
  content: '▸ ';
  color: var(--neon-b);
}

/* ── NO RESULT ── */
.faq-empty {
  text-align: center;
  padding: 60px 20px;
  color: var(--text-muted);
  display: none;
}
.faq-empty .icon { font-size: 48px; opacity: 0.2; margin-bottom: 12px; }
.faq-empty p { font-family: 'Rajdhani', sans-serif; font-size: 14px; letter-spacing: 1px; }

/* ── STILL NEED HELP ── */
.help-band {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 36px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
  margin-top: 56px;
  position: relative;
  overflow: hidden;
}

.help-band::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--neon), var(--cyan), transparent);
}

.help-text {}
.help-title {
  font-family: 'Orbitron', monospace;
  font-size: 18px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 6px;
}
.help-desc { font-size: 14px; color: var(--text-dim); }

.help-actions { display: flex; gap: 10px; flex-wrap: wrap; }

/* Highlight badge on answer */
.faq-highlight {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 10px;
  border-radius: var(--radius-pill);
  font-family: 'Rajdhani', sans-serif;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1px;
  background: rgba(124,58,237,0.1);
  border: 1px solid rgba(124,58,237,0.25);
  color: var(--neon-b);
  margin-left: 6px;
  vertical-align: middle;
}

/* hidden FAQ item */
.faq-item.faq-hidden { display: none; }
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
    Pertanyaan Umum
  </div>
  <h1 class="hero-title">
    <span class="hero-title-white">FAQ</span>
    <span class="hero-title-gradient">Lengkap</span>
  </h1>
  <p class="hero-desc">
    Cari jawaban cepat dari pertanyaan yang paling sering ditanyakan pelanggan kami.
  </p>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">

    <!-- SEARCH -->
    <div class="faq-search-wrap fade-up">
      <input type="text" class="faq-search" id="faqSearch"
             placeholder="Cari pertanyaan..." oninput="filterFAQ()">
      <span class="faq-search-icon">⌕</span>
    </div>

    <!-- TABS -->
    <div class="faq-tabs fade-up">
      <button class="faq-tab active" onclick="filterTab('all', this)">Semua</button>
      <button class="faq-tab" onclick="filterTab('order', this)">Order</button>
      <button class="faq-tab" onclick="filterTab('pembayaran', this)">Pembayaran</button>
      <button class="faq-tab" onclick="filterTab('pengiriman', this)">Pengiriman</button>
      <button class="faq-tab" onclick="filterTab('produk', this)">Produk</button>
      <button class="faq-tab" onclick="filterTab('akun', this)">Akun &amp; Lainnya</button>
    </div>

    <div id="faqEmpty" class="faq-empty">
      <div class="icon">🔍</div>
      <p>Tidak ada pertanyaan yang cocok.</p>
    </div>

    <!-- ─────────── ORDER ─────────── -->
    <div class="faq-section" data-category="order">
      <div class="faq-section-title">🛒 Pemesanan</div>

      <div class="faq-item" data-category="order">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Bagaimana cara memesan produk di AY Vape?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Pemesanan sangat mudah! Pilih produk → klik <strong>+ Keranjang</strong> → buka halaman Keranjang →
            klik <strong>Checkout</strong> → isi data diri → pilih metode pembayaran → konfirmasi.
            Pesanan akan diproses dalam 1×24 jam. Lihat panduan lengkap di halaman
            <a href="cara_order.php">Cara Order</a>.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="order">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apakah saya harus daftar akun untuk memesan?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Tidak perlu! Kamu bisa langsung memesan tanpa registrasi akun. Cukup isi nama dan
            nomor WhatsApp saat checkout, admin kami akan menghubungi kamu untuk konfirmasi pesanan.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="order">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Bisakah saya memesan lebih dari satu produk sekaligus?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Tentu bisa! Tambahkan beberapa produk ke keranjang sebelum checkout. Semua item akan diproses dalam satu pesanan dan diserahkan
            dalam satu kali pengantaran sesuai kesepakatan dengan admin.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="order">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Bisakah saya membatalkan pesanan setelah checkout?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Pembatalan bisa dilakukan <strong>sebelum pembayaran dikonfirmasi</strong> admin.
            Hubungi kami secepatnya via <a href="https://wa.me/6282333408651">WhatsApp</a> dengan
            menyebutkan nomor order kamu. Setelah pembayaran dikonfirmasi, pembatalan tidak bisa dilakukan.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="order">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apakah produk second/bekas masih layak pakai?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Produk second/bekas dijual dalam kondisi layak pakai dan telah dicek oleh admin.
            Detail kondisi, kelengkapan, dan minus produk akan dijelaskan secara transparan
            sebelum transaksi dilakukan.
          </div>
        </div>
      </div>

    </div>

    <!-- ─────────── PEMBAYARAN ─────────── -->
    <div class="faq-section" data-category="pembayaran">
      <div class="faq-section-title">💳 Pembayaran</div>

      <div class="faq-item" data-category="pembayaran">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Metode pembayaran apa saja yang diterima?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Kami menerima:
            <ul>
              <li><strong>QRIS</strong> — DANA, OVO, GoPay, ShopeePay, LinkAja</li>
              <li><strong>Transfer Bank</strong> — via Mobile Banking / Internet Banking</li>
              <li><strong>COD (Cash)</strong> — bayar tunai saat bertemu langsung dengan admin</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="pembayaran">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Berapa lama konfirmasi pembayaran diproses?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Konfirmasi pembayaran diproses dalam <strong>1×24 jam</strong> setelah bukti transfer
            dikirim. Pada jam operasional (09:00–22:00 WIB), biasanya dikonfirmasi dalam
            <strong>1–2 jam</strong>. Admin akan menghubungi kamu via WhatsApp setelah konfirmasi.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="pembayaran">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Bagaimana jika saya sudah transfer tapi belum dikonfirmasi?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Jika sudah lebih dari 3 jam dan belum ada konfirmasi, segera hubungi kami via
            <a href="https://wa.me/6282333408651">WhatsApp</a> dengan menyertakan:
            <ul>
              <li>Nomor order</li>
              <li>Screenshot bukti transfer</li>
              <li>Nominal transfer</li>
            </ul>
            Kami akan segera memproses verifikasi kamu.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="pembayaran">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apakah nominal transfer harus tepat?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            <strong>Ya, nominal harus tepat</strong> sesuai total order yang tertera.
            Kelebihan bayar tidak bisa dikembalikan secara otomatis — harus menghubungi admin
            untuk proses pengembalian manual.
          </div>
        </div>
      </div>

    </div>

    <!-- ─────────── PENGIRIMAN ─────────── -->
    <div class="faq-section" data-category="pengiriman">
      <div class="faq-section-title">🚚 Pengiriman</div>

      <div class="faq-item" data-category="pengiriman">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Berapa lama estimasi pengiriman?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            <ul>
              <li><strong>Area Jember</strong> — Pengantaran sesuai kesepakatan dengan admin (COD)</li>
              <li><strong>Jarak jauh</strong> — Waktu pengantaran menyesuaikan proses rekber Shopee</li>
            </ul>
            PPenyerahan barang dilakukan setelah pembayaran atau kesepakatan dengan admin.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="pengiriman">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Jasa kurir apa saja yang tersedia?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Pengantaran dilakukan dengan sistem <strong>COD (bertemu langsung dengan admin)</strong>.
            Untuk pembeli jarak jauh, pengiriman dapat menggunakan <strong>rekber Shopee</strong>
            sesuai kesepakatan melalui chat website atau WhatsApp.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="pengiriman">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apakah ongkos kirim gratis?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Biaya pengantaran ditentukan berdasarkan jarak dan kesepakatan dengan admin.
            Untuk transaksi menggunakan rekber Shopee, biaya rekber dan pengiriman
            dibicarakan terlebih dahulu melalui chat website atau WhatsApp.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="pengiriman">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Bagaimana cara melacak paket saya?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Pengantaran COD tidak menggunakan nomor resi.
            Admin akan menghubungi kamu langsung melalui WhatsApp
            untuk koordinasi waktu dan lokasi penyerahan barang.
          </div>
        </div>
      </div>

    </div>

    <!-- ─────────── PRODUK ─────────── -->
    <div class="faq-section" data-category="produk">
      <div class="faq-section-title">📦 Produk</div>

      <div class="faq-item" data-category="produk">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apakah semua produk yang dijual original?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Produk yang dijual tersedia dalam kondisi <strong>baru (original)</strong>
            dan <strong>second/bekas</strong>.
            Setiap produk telah dicek oleh admin, dan kondisi barang
            dijelaskan secara transparan melalui deskripsi atau chat admin sebelum pembelian.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="produk">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apa yang harus saya lakukan jika produk rusak saat diterima?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Jika produk rusak atau tidak sesuai saat diterima, segera:
            <ul>
              <li>Dokumentasikan kondisi produk (foto/video)</li>
              <li>Hubungi kami dalam <strong>1×24 jam</strong> setelah barang diterima</li>
              <li>Kirim bukti via WhatsApp ke admin</li>
            </ul>
            Kami akan proses pengembalian / penggantian produk sesuai kebijakan toko.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="produk">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apakah stok produk selalu tersedia?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Stok produk ditampilkan secara real-time di halaman produk. Produk dengan stok <span class="faq-highlight">🟡 Stok Rendah</span>
            berarti hampir habis. Kami sarankan segera memesan sebelum kehabisan.
            Untuk pre-order produk yang sedang habis, hubungi admin kami.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="produk">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apakah harga sudah termasuk pajak?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Ya, harga yang tertera di website sudah termasuk semua biaya. Tidak ada biaya
            tersembunyi. Total yang kamu bayar adalah sesuai yang tertera di halaman checkout.
          </div>
        </div>
      </div>

    </div>

    <!-- ─────────── AKUN ─────────── -->
    <div class="faq-section" data-category="akun">
      <div class="faq-section-title">⚙️ Akun &amp; Lainnya</div>

      <div class="faq-item" data-category="akun">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Bagaimana cara menghubungi customer service?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Kamu bisa menghubungi kami melalui:
            <ul>
              <li><strong>WhatsApp</strong> — <a href="https://wa.me/6282333408651">+62 823-3340-8651</a></li>
              <li><strong>Chat Admin</strong> — fitur chat langsung di website</li>
              <li><strong>Email</strong> — rahmanadur7511@gmail.com</li>
            </ul>
            Jam operasional: <strong>09:00 – 22:00 WIB</strong> setiap hari.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="akun">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apakah data pribadi saya aman?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Data pribadi kamu (nama, nomor HP, alamat) hanya digunakan untuk keperluan pengiriman
            dan komunikasi terkait pesanan. Kami tidak membagikan data kamu ke pihak ketiga manapun.
          </div>
        </div>
      </div>

      <div class="faq-item" data-category="akun">
        <button class="faq-question" onclick="toggleFAQ(this)">
          <span class="faq-q-text">Apakah ada program loyalitas atau diskon member?</span>
          <span class="faq-chevron">▾</span>
        </button>
        <div class="faq-answer">
          <div class="faq-answer-inner">
            Saat ini kami sedang mengembangkan program member. Untuk info promo terbaru,
            follow Instagram dan TikTok kami, atau tanyakan langsung ke admin via WhatsApp.
            Pelanggan setia kami sering mendapatkan penawaran spesial! 🔥
          </div>
        </div>
      </div>

    </div>

    <!-- STILL NEED HELP -->
    <div class="help-band fade-up">
      <div class="help-text">
        <div class="help-title">Masih ada pertanyaan?</div>
        <div class="help-desc">Tim kami siap membantu kamu 09:00 – 22:00 WIB setiap hari.</div>
      </div>
      <div class="help-actions">
        <a href="https://wa.me/6282333408651" class="btn btn-green">💬 WhatsApp</a>
        <a href="contact.php" class="btn btn-secondary">Kirim Pesan</a>
      </div>
    </div>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
<a href="https://wa.me/6282333408651" class="wa-float">💬 Chat WA</a>

<script>
// ACCORDION
function toggleFAQ(btn) {
  const item = btn.closest('.faq-item');
  const isOpen = item.classList.contains('open');

  // close all others in same section
  document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));

  if (!isOpen) item.classList.add('open');
}

// SEARCH
function filterFAQ() {
  const q = document.getElementById('faqSearch').value.toLowerCase().trim();
  const items = document.querySelectorAll('.faq-item');
  let visibleCount = 0;

  items.forEach(item => {
    const text = item.querySelector('.faq-q-text').textContent.toLowerCase();
    const answerText = item.querySelector('.faq-answer-inner').textContent.toLowerCase();
    const match = text.includes(q) || answerText.includes(q);
    item.classList.toggle('faq-hidden', !match);
    if (match) visibleCount++;
  });

  // hide empty sections
  document.querySelectorAll('.faq-section').forEach(sec => {
    const visible = [...sec.querySelectorAll('.faq-item')].some(i => !i.classList.contains('faq-hidden'));
    sec.style.display = visible ? '' : 'none';
  });

  document.getElementById('faqEmpty').style.display = visibleCount === 0 ? 'block' : 'none';
}

// TAB FILTER
let currentTab = 'all';

function filterTab(cat, btn) {
  currentTab = cat;
  document.querySelectorAll('.faq-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');

  document.querySelectorAll('.faq-item').forEach(item => {
    const itemCat = item.getAttribute('data-category');
    item.classList.toggle('faq-hidden', cat !== 'all' && itemCat !== cat);
  });

  document.querySelectorAll('.faq-section').forEach(sec => {
    const secCat = sec.getAttribute('data-category');
    sec.style.display = (cat === 'all' || secCat === cat) ? '' : 'none';
  });
}

// Open first item by default
document.querySelector('.faq-item')?.classList.add('open');
</script>
</body>
</html>
