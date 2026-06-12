<?php
$jumlah      = 0;
$active_page = 'contact';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kontak — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<style>
.contact-hero {
  padding: 72px 28px 52px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.contact-hero::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(34,211,238,0.1) 0%, transparent 65%);
  z-index: -1;
}

/* CONTACT LAYOUT */
.contact-main-grid {
  display: grid;
  grid-template-columns: 1fr 1.4fr;
  gap: 28px;
  align-items: start;
}

/* INFO SIDE */
.contact-info-stack { display: flex; flex-direction: column; gap: 12px; }

.contact-info-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px 22px;
  display: flex;
  align-items: flex-start;
  gap: 16px;
  transition: all var(--t) var(--ease);
}

.contact-info-card:hover {
  border-color: var(--border-glow);
  transform: translateX(4px);
}

.contact-info-icon {
  width: 44px; height: 44px;
  border-radius: var(--radius-sm);
  background: rgba(124,58,237,0.12);
  border: 1px solid rgba(124,58,237,0.2);
  display: flex; align-items: center; justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
}

.contact-info-body {}
.contact-info-lbl {
  font-family: 'Rajdhani', sans-serif;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: var(--text-muted);
  margin-bottom: 3px;
}
.contact-info-val {
  font-size: 14px;
  color: var(--text);
  line-height: 1.5;
}
.contact-info-val a { color: var(--neon-b); transition: color var(--t); }
.contact-info-val a:hover { color: var(--cyan); }

/* MAP */
.map-wrap {
  margin-top: 12px;
  border-radius: var(--radius);
  overflow: hidden;
  border: 1px solid var(--border);
}

.map-wrap iframe { display: block; }

/* FORM SIDE */
.contact-form-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 32px;
  position: relative;
  overflow: hidden;
}

.contact-form-card::before {
  content: '';
  position: absolute; top:0;left:0;right:0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--cyan), var(--neon-b), transparent);
}

.contact-form-title {
  font-family: 'Orbitron', monospace;
  font-size: 20px;
  font-weight: 700;
  color: #fff;
  margin-bottom: 6px;
}

.contact-form-sub {
  font-size: 13px;
  color: var(--text-muted);
  margin-bottom: 24px;
}

/* Success message */
.form-success {
  background: rgba(16,185,129,0.1);
  border: 1px solid rgba(16,185,129,0.25);
  border-radius: var(--radius-sm);
  padding: 14px 18px;
  color: #34d399;
  font-family: 'Rajdhani', sans-serif;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 0.5px;
  display: none;
  margin-bottom: 16px;
}

@media (max-width: 900px) {
  .contact-main-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<!-- HERO -->
<div class="contact-hero">
  <div class="smoke-layer">
    <div class="smoke-puff"></div>
    <div class="smoke-puff"></div>
    <div class="smoke-puff"></div>
  </div>
  <div class="hero-eyebrow">
    <span class="hero-eyebrow-dot"></span>
    Get In Touch
  </div>
  <h1 class="hero-title">
    <span class="hero-title-white">Hubungi</span>
    <span class="hero-title-gradient">Kami</span>
  </h1>
  <p class="hero-desc">
    Kami siap membantu kamu kapan saja. Pilih channel yang paling nyaman.
  </p>
</div>

<section class="section" style="padding-top:0;">
  <div class="container">

    <div class="contact-main-grid">

      <!-- INFO -->
      <div>
        <div style="font-family:'Rajdhani',sans-serif;font-size:11px;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;color:var(--text-muted);margin-bottom:14px;">Informasi Toko</div>

        <div class="contact-info-stack">
          <div class="contact-info-card">
            <div class="contact-info-icon">📍</div>
            <div class="contact-info-body">
              <div class="contact-info-lbl">Alamat</div>
              <div class="contact-info-val">Jl. MT. Haryono No.46, Sumber Beringin, Karangrejo, Sumbersari, Kabupaten Jember, Jawa Timur 68124</div>
            </div>
          </div>

          <div class="contact-info-card">
            <div class="contact-info-icon">📞</div>
            <div class="contact-info-body">
              <div class="contact-info-lbl">Telepon / WhatsApp</div>
              <div class="contact-info-val">
                <a href="https://wa.me/6282333408651">+62 823-3340-8651</a>
              </div>
            </div>
          </div>

          <div class="contact-info-card">
            <div class="contact-info-icon">📧</div>
            <div class="contact-info-body">
              <div class="contact-info-lbl">Email</div>
              <div class="contact-info-val">
                <a href="mailto:rahmanadur7511@gmail.com">rahmanadur7511@gmail.com</a>
              </div>
            </div>
          </div>

          <div class="contact-info-card">
            <div class="contact-info-icon">🕒</div>
            <div class="contact-info-body">
              <div class="contact-info-lbl">Jam Operasional</div>
              <div class="contact-info-val">Senin – Minggu, 09:00 – 22:00 WIB</div>
            </div>
          </div>
        </div>

        <!-- MAP -->
        <div class="map-wrap" style="margin-top:20px;">
          <iframe
            src="https://maps.google.com/maps?q=Masjid+Jami+Al-Muttaqiin+Jember&t=&z=15&ie=UTF8&iwloc=&output=embed"
            width="100%"
            height="220"
            style="border:0;"
            loading="lazy">
          </iframe>
        </div>

        <div style="margin-top:14px;">
          <a href="https://wa.me/6282333408651" class="btn btn-green btn-full">
            💬 Chat WhatsApp Sekarang
          </a>
        </div>
      </div>

      <!-- FORM -->
      <div class="contact-form-card">
        <div class="contact-form-title">Kirim Pesan</div>
        <div class="contact-form-sub">Isi form di bawah — admin akan membalas melalui website atau WhatsApp dalam 1×24 jam.</div>

        <div class="form-success" id="formSuccess">
          ✅ Pesan berhasil dikirim! Admin akan menghubungi kamu melalui chat atau WhatsApp.
        </div>

        <form onsubmit="submitForm(event)">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div class="form-group">
              <label class="form-label">Nama</label>
              <input type="text" class="form-control" placeholder="Nama kamu" required>
            </div>
            <div class="form-group">
              <label class="form-label">Nomor HP</label>
              <input type="tel" class="form-control" placeholder="08xx-xxxx-xxxx">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="email@kamu.com" required>
          </div>

          <div class="form-group">
            <label class="form-label">Topik</label>
            <select class="form-control">
              <option value="">— Pilih Topik —</option>
              <option>Pertanyaan Produk</option>
              <option>Status Order</option>
              <option>Pembayaran</option>
              <option>Pengantaran / COD</option>
              <option>Lainnya</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Pesan</label>
            <textarea class="form-control" rows="5" placeholder="Tulis pesanmu di sini..." required></textarea>
          </div>

          <button type="submit" class="btn btn-primary btn-full" style="margin-top:4px;">
            Kirim Pesan →
          </button>
        </form>
      </div>

    </div>

  </div>
</section>

<?php include '../includes/footer.php'; ?>
<a href="https://wa.me/6282333408651" class="wa-float">💬 Chat WA</a>

<script>
function submitForm(e) {
  e.preventDefault();
  const btn = e.target.querySelector('button[type=submit]');
  btn.textContent = 'Mengirim...';
  btn.disabled = true;
  setTimeout(() => {
    document.getElementById('formSuccess').style.display = 'block';
    e.target.reset();
    btn.textContent = 'Kirim Pesan →';
    btn.disabled = false;
  }, 1200);
}
</script>
</body>
</html>