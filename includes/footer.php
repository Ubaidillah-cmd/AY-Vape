<?php 
// includes/footer.php
$base = isset($base) ? $base : '../';
?>
<footer class="footer">
  <div class="footer-inner">

    <div class="footer-grid">

      <!-- BRAND -->
      <div>
        <div class="footer-brand-name">AY VAPE</div>
        <p class="footer-brand-desc">
          Toko vape terpercaya dengan produk original dan unit pilihan (bekas layak pakai).
          Seluruh transaksi ditangani langsung oleh admin.
          Pengantaran dilakukan dengan sistem COD (bertemu admin) atau rekber Shopee sesuai kesepakatan.
        </p>
        <div class="footer-socials">
          <a href="#" class="footer-social-btn">📷</a>
          <a href="#" class="footer-social-btn">🎵</a>
          <a href="#" class="footer-social-btn">📘</a>
          <a href="https://wa.me/6282333408651" class="footer-social-btn">💬</a>
        </div>
      </div>

      <!-- MENU -->
      <div>
        <div class="footer-col-title">Menu</div>
        <div class="footer-links">
          <a href="<?= $base ?>index.php"          class="footer-link">Home</a>
          <a href="<?= $base ?>public/about.php"   class="footer-link">Tentang Kami</a>
          <a href="<?= $base ?>public/contact.php" class="footer-link">Kontak</a>
          <a href="<?= $base ?>customer/cart.php"  class="footer-link">Keranjang</a>
        </div>
      </div>

      <!-- BANTUAN -->
      <div>
        <div class="footer-col-title">Bantuan</div>
        <div class="footer-links">
          <a href="<?= $base ?>public/cara_order.php" class="footer-link">Cara Order</a>
          <a href="<?= $base ?>public/pengiriman.php"  class="footer-link">Pengiriman</a>
          <a href="<?= $base ?>public/pembayaran.php"  class="footer-link">Pembayaran</a>
          <a href="<?= $base ?>public/faq.php"         class="footer-link">FAQ</a>
        </div>
      </div>

      <!-- NEWSLETTER -->
      <div>
        <div class="footer-col-title">Newsletter</div>
        <div class="footer-newsletter">

          <div id="newsletterForm" style="display:flex;flex-direction:column;gap:8px;">
            <input type="email" id="newsletterEmail"
                   class="footer-newsletter-input"
                   placeholder="Email kamu..."
                   onkeydown="if(event.key==='Enter') subscribeNewsletter()">
            <button class="btn btn-primary btn-sm"
                    id="subscriberBtn"
                    onclick="subscribeNewsletter()">
              Subscribe →
            </button>
          </div>

          <div id="newsletterSuccess"
               style="display:none;
                      background:rgba(16,185,129,0.08);
                      border:1px solid rgba(16,185,129,0.25);
                      border-radius:8px;
                      padding:12px 14px;">
            <div style="font-family:'Rajdhani',sans-serif;font-size:13px;font-weight:700;
                        color:#34d399;margin-bottom:3px;">
              ✅ Berhasil Subscribe!
            </div>
            <div style="font-size:12px;color:rgba(255,255,255,0.4);line-height:1.5;">
              Terima kasih! Kamu akan mendapat info promo terbaru dari AY Vape.
            </div>
          </div>

        </div>

        <div style="margin-top:20px;">
          <div class="footer-col-title">Kontak Cepat</div>
          <div style="font-size:13px;color:var(--text-muted);line-height:2;">
            <div>📞 0823-3340-8651 (WhatsApp)</div>
            <div>📧 rahmanadur7511@gmail.com</div>
            <div>🕒 09:00 – 22:00 WIB</div>
            <div>💬 Chat website tersedia (respon admin)</div>
          </div>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <div class="footer-copy">
        © <?= date("Y") ?> <span>AY Vape</span> — All Rights Reserved.
      </div>
      <div class="footer-copy">
        Made with 💨 for vapers everywhere
      </div>
    </div>

  </div>
</footer>

<style>
@keyframes footerShake {
  0%,100%{ transform:translateX(0); }
  20%    { transform:translateX(-6px); }
  40%    { transform:translateX(6px); }
  60%    { transform:translateX(-4px); }
  80%    { transform:translateX(4px); }
}
@keyframes footerToastIn {
  from{ opacity:0; transform:translateY(10px) scale(.95); }
  to  { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes subFadeUp {
  from{ opacity:0; transform:translateY(8px); }
  to  { opacity:1; transform:translateY(0); }
}
#newsletterSuccess { animation: subFadeUp .4s ease both; }
</style>

<script>
var _footerBase = "<?= $base ?>";

function subscribeNewsletter() {
  const input   = document.getElementById('newsletterEmail');
  const btn     = document.getElementById('subscriberBtn');
  const form    = document.getElementById('newsletterForm');
  const success = document.getElementById('newsletterSuccess');
  const email   = input.value.trim();

  if (!email) { shakeInput(input); showFooterToast('⚠️ Masukkan email kamu dulu!', 'warn'); input.focus(); return; }
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) { shakeInput(input); showFooterToast('⚠️ Format email tidak valid!', 'warn'); input.focus(); return; }

  btn.innerHTML = '⏳ Mendaftar...'; btn.disabled = true; input.disabled = true;
  const halaman = window.location.pathname.split('/').pop() || 'index.php';

  fetch(_footerBase + 'ajax/subscribe.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `email=${encodeURIComponent(email)}&halaman=${encodeURIComponent(halaman)}`
  })
  .then(r => r.json())
  .then(d => {
    if (d.ok) {
      form.style.display = 'none'; success.style.display = 'block';
      showFooterToast('🎉 Subscribe berhasil!', 'success');
    } else if (d.type === 'duplicate') {
      btn.innerHTML = 'Subscribe →'; btn.disabled = false; input.disabled = false;
      shakeInput(input); showFooterToast('ℹ️ Email ini sudah terdaftar!', 'info');
    } else {
      btn.innerHTML = 'Subscribe →'; btn.disabled = false; input.disabled = false;
      showFooterToast('❌ ' + (d.msg || 'Gagal, coba lagi'), 'warn');
    }
  })
  .catch(() => {
    btn.innerHTML = 'Subscribe →'; btn.disabled = false; input.disabled = false;
    showFooterToast('❌ Koneksi error, coba lagi', 'warn');
  });
}

function shakeInput(el) {
  el.style.animation = 'none'; el.offsetHeight; el.style.animation = 'footerShake 0.4s ease';
  el.style.borderColor = 'rgba(239,68,68,0.6)'; el.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.12)';
  setTimeout(() => { el.style.borderColor = ''; el.style.boxShadow = ''; }, 1600);
}

function showFooterToast(text, type) {
  document.querySelectorAll('.footer-toast').forEach(t => t.remove());
  const toast = document.createElement('div');
  toast.className = 'footer-toast';
  const colors = {
    success: { bg:'rgba(16,185,129,0.12)', border:'rgba(16,185,129,0.3)', color:'#34d399' },
    warn:    { bg:'rgba(245,158,11,0.12)', border:'rgba(245,158,11,0.3)', color:'#fbbf24' },
    info:    { bg:'rgba(99,102,241,0.12)', border:'rgba(99,102,241,0.3)', color:'#a855f7' },
  };
  const c = colors[type] || colors.info;
  Object.assign(toast.style, {
    position:'fixed', bottom:'90px', right:'24px', padding:'12px 18px',
    background:c.bg, border:`1px solid ${c.border}`, borderRadius:'10px',
    color:c.color, fontFamily:'Rajdhani, sans-serif', fontSize:'14px',
    fontWeight:'600', letterSpacing:'.5px', zIndex:'9999',
    boxShadow:'0 8px 24px rgba(0,0,0,0.4)', animation:'footerToastIn .3s cubic-bezier(0.34,1.56,0.64,1)',
    whiteSpace:'nowrap', maxWidth:'calc(100vw - 48px)',
  });
  toast.textContent = text;
  document.body.appendChild(toast);
  setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateY(8px)'; toast.style.transition='.3s ease'; }, 3200);
  setTimeout(() => toast.remove(), 3600);
}
</script>