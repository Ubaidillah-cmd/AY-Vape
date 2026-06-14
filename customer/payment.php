<?php
session_start();
include "../config/db.php";

$jumlah      = 0;
$active_page = '';

$id     = (int)($_GET['id'] ?? 0);
$metode = $_GET['metode'] ?? '';

$data = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$id'");
$p    = mysqli_fetch_assoc($data);

if (!$p) {
  echo "<div style='text-align:center;padding:80px 20px;color:var(--text-muted);font-family:Rajdhani,sans-serif;'>Data tidak ditemukan.</div>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran — AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/customer.css">
<style>
.payment-wrap {
  max-width: 600px;
  margin: 0 auto;
}

/* METHOD SELECTOR */
.method-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-top: 8px;
}

.method-option {
  display: none;
}

.method-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  padding: 18px;
  background: rgba(255,255,255,0.03);
  border: 2px solid var(--border);
  border-radius: var(--radius);
  cursor: pointer;
  transition: all var(--t) var(--ease);
  font-family: 'Rajdhani', sans-serif;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: var(--text-dim);
  user-select: none;
}

.method-label:hover {
  border-color: var(--border-glow);
  background: rgba(124,58,237,0.06);
  color: #fff;
}

.method-option:checked + .method-label {
  border-color: var(--neon-b);
  background: rgba(124,58,237,0.12);
  color: #fff;
  box-shadow: 0 0 0 3px rgba(124,58,237,0.15);
}

.method-icon { font-size: 28px; }

/* QRIS BOX */
.qris-box {
  text-align: center;
  padding: 28px;
  background: rgba(255,255,255,0.02);
  border: 1px solid var(--border);
  border-radius: var(--radius);
}

.qris-box img {
  width: 200px;
  height: 200px;
  object-fit: contain;
  border-radius: var(--radius-sm);
  margin: 14px auto;
  border: 1px solid var(--border);
  background: #fff;
  padding: 8px;
}

.qris-amount {
  font-family: 'Orbitron', monospace;
  font-size: 28px;
  font-weight: 900;
  background: linear-gradient(135deg, var(--neon-b), var(--cyan));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin: 12px 0;
}

.qris-note {
  font-family: 'Rajdhani', sans-serif;
  font-size: 12px;
  letter-spacing: 1px;
  color: var(--amber);
  margin-top: 6px;
}

/* ORDER INFO */
.order-info-card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px 22px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
  gap: 16px;
}

.order-info-id {
  font-family: 'Orbitron', monospace;
  font-size: 13px;
  color: var(--text-muted);
  letter-spacing: 1px;
}

.order-info-name {
  font-family: 'Rajdhani', sans-serif;
  font-size: 18px;
  font-weight: 700;
  color: #fff;
}

.order-info-total {
  font-family: 'Orbitron', monospace;
  font-size: 24px;
  font-weight: 900;
  background: linear-gradient(135deg, var(--neon-b), var(--cyan));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  white-space: nowrap;
}

/* UPLOAD AREA */
.upload-area {
  border: 2px dashed var(--border);
  border-radius: var(--radius);
  padding: 28px;
  text-align: center;
  cursor: pointer;
  transition: all var(--t) var(--ease);
  position: relative;
}

.upload-area:hover {
  border-color: var(--neon-b);
  background: rgba(124,58,237,0.04);
}

.upload-icon { font-size: 32px; opacity: 0.4; margin-bottom: 8px; }
.upload-text {
  font-family: 'Rajdhani', sans-serif;
  font-size: 13px;
  letter-spacing: 1px;
  color: var(--text-muted);
}

.upload-area input { display: none; }

#previewImg {
  max-width: 100%;
  border-radius: var(--radius-sm);
  margin-top: 14px;
  display: none;
  border: 1px solid var(--border);
}

/* STEPS */
.steps {
  display: flex;
  align-items: center;
  gap: 0;
  margin-bottom: 32px;
}
.step { display: flex; align-items: center; gap: 8px; flex: 1; }
.step-num {
  width: 32px; height: 32px;
  border-radius: 50%;
  background: rgba(124,58,237,0.12);
  border: 1px solid var(--border-glow);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Orbitron', monospace;
  font-size: 13px; font-weight: 700;
  color: var(--neon-b); flex-shrink: 0;
}
.step.done .step-num { background: rgba(16,185,129,0.2); border-color: rgba(16,185,129,0.4); color: #34d399; }
.step.active .step-num { background: var(--neon); border-color: var(--neon); color: #fff; box-shadow: 0 0 12px var(--glow); }
.step-label { font-family: 'Rajdhani', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--text-muted); }
.step.active .step-label { color: #fff; }
.step.done .step-label { color: #34d399; }
.step-line { flex: 1; height: 1px; background: var(--border); margin: 0 8px; }
</style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<section class="section">
  <div class="container">
    <div class="payment-wrap">

      <!-- STEPS -->
      <div class="steps fade-up">
        <div class="step done">
          <div class="step-num">✓</div>
          <div class="step-label">Data Pembeli</div>
        </div>
        <div class="step-line"></div>
        <div class="step active">
          <div class="step-num">2</div>
          <div class="step-label">Pembayaran</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
          <div class="step-num">3</div>
          <div class="step-label">Selesai</div>
        </div>
      </div>

      <!-- ORDER INFO -->
      <div class="order-info-card fade-up">
        <div>
          <div class="order-info-id">ORDER #<?= str_pad($id, 4, '0', STR_PAD_LEFT) ?></div>
          <div class="order-info-name"><?= htmlspecialchars($p['nama_pembeli']) ?></div>
        </div>
        <div class="order-info-total">Rp <?= number_format($p['total']) ?></div>
      </div>

      <!-- PILIH METODE -->
      <div class="card fade-up" style="padding:24px;margin-bottom:20px;">
        <div class="form-label" style="margin-bottom:14px;">Pilih Metode Pembayaran</div>

        <form method="GET" id="metodeForm">
          <input type="hidden" name="id" value="<?= $id ?>">
          <div class="method-grid">
            <input type="radio" name="metode" id="m_transfer" value="transfer"
                   class="method-option" <?= $metode==='transfer' ? 'checked' : '' ?>
                   onchange="document.getElementById('metodeForm').submit()">
            <label for="m_transfer" class="method-label">
              <span class="method-icon">📱</span>
              QRIS / Transfer
            </label>

            <input type="radio" name="metode" id="m_cod" value="cod"
                   class="method-option" <?= $metode==='cod' ? 'checked' : '' ?>
                   onchange="document.getElementById('metodeForm').submit()">
            <label for="m_cod" class="method-label">
              <span class="method-icon">💵</span>
              Cash (COD)
            </label>
          </div>
        </form>
      </div>

      <!-- QRIS -->
      <?php if ($metode === 'transfer'): ?>
      <div class="qris-box fade-up">
        <div style="font-family:'Rajdhani',sans-serif;font-size:11px;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--text-muted);">
          Scan QRIS untuk Membayar
        </div>
        <img src="../assets/img/qris.jpeg" alt="QRIS">
        <div class="qris-amount">Rp <?= number_format($p['total']) ?></div>
        <div style="font-family:'Rajdhani',sans-serif;font-size:13px;color:var(--text-muted);">
          Bisa via DANA · OVO · GoPay · Mobile Banking
        </div>
        <div class="qris-note">* Pastikan nominal transfer sesuai</div>
      </div>
      <?php endif; ?>

      <!-- FORM PEMBAYARAN -->
      <?php if ($metode): ?>
      <div class="card fade-up" style="padding:24px;margin-top:20px;">
        <form action="../proses/proses_payment.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_pesanan" value="<?= $id ?>">
          <input type="hidden" name="metode" value="<?= htmlspecialchars($metode) ?>">

          <?php if ($metode === 'transfer'): ?>
          <div class="form-group">
            <label class="form-label">Upload Bukti Pembayaran</label>
            <div class="upload-area" onclick="document.getElementById('buktiFile').click()">
              <div class="upload-icon">📎</div>
              <div class="upload-text">Klik untuk upload foto bukti transfer</div>
              <input type="file" id="buktiFile" name="bukti" accept="image/*"
                     required onchange="previewBukti(this)">
              <img id="previewImg" alt="Preview">
            </div>
          </div>
          <?php endif; ?>

          <?php if ($metode === 'cod'): ?>
          <div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:20px;">
            <div style="font-family:'Rajdhani',sans-serif;font-size:13px;color:var(--amber);letter-spacing:0.5px;line-height:1.6;">
              💵 Pembayaran tunai dilakukan saat bertemu admin (COD).<br>
              Siapkan uang pas sebesar <strong>Rp <?= number_format($p['total']) ?></strong>.
            </div>
          </div>
          <?php endif; ?>

          <button type="submit" class="btn btn-primary btn-full btn-lg">
            ✅ Konfirmasi Pembayaran
          </button>
        </form>
      </div>
      <?php else: ?>
      <div style="text-align:center;padding:24px;color:var(--text-muted);font-family:'Rajdhani',sans-serif;letter-spacing:1px;">
        ← Pilih metode pembayaran di atas
      </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>

<script>
function previewBukti(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById('previewImg');
    img.src = e.target.result;
    img.style.display = 'block';
  };
  reader.readAsDataURL(file);
}
</script>
</body>
</html>