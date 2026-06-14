<?php
// ============================================================
//  proses/proses_payment.php
//  Proses upload bukti bayar + notifikasi Telegram ke admin
// ============================================================

session_start();
include "../config/db.php";
include "../config/telegram.php";

$id_pesanan = (int)($_POST['id_pesanan'] ?? 0);
$metode     = mysqli_real_escape_string($conn, $_POST['metode'] ?? '');

if (!$id_pesanan) {
    header("Location: ../public/index.php");
    exit;
}

// ── Ambil data pesanan ──
$orderQ = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$id_pesanan' LIMIT 1");
$order  = mysqli_fetch_assoc($orderQ);

if (!$order) {
    header("Location: ../public/index.php");
    exit;
}

$buktiFile    = '';
$buktiPath    = '';

// ── Handle upload bukti ──
if (!empty($_FILES['bukti']['name'])) {
    $ext     = strtolower(pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];

    if (in_array($ext, $allowed) && $_FILES['bukti']['error'] === 0) {
        $buktiFile = time() . '_bukti_' . $id_pesanan . '.' . $ext;
        $buktiPath = "../uploads/bukti_pembayaran/" . $buktiFile;
        move_uploaded_file($_FILES['bukti']['tmp_name'], $buktiPath);
    }
}

// ── Insert / Update payment ──
// Cek apakah sudah ada record payment
$cekQ  = mysqli_query($conn, "SELECT id FROM payment WHERE id_pesanan='$id_pesanan' LIMIT 1");
$cekP  = mysqli_fetch_assoc($cekQ);

if ($cekP) {
    // Update
    $updateBukti = $buktiFile ? ", bukti='$buktiFile'" : "";
    mysqli_query($conn, "
        UPDATE payment
        SET metode='$metode', status='pending' $updateBukti
        WHERE id_pesanan='$id_pesanan'
    ");
} else {
    // Insert
    mysqli_query($conn, "
        INSERT INTO payment (id_pesanan, metode, bukti, status)
        VALUES ('$id_pesanan', '$metode', '$buktiFile', 'pending')
    ");
}

// ============================================================
//  NOTIFIKASI TELEGRAM — BUKTI PEMBAYARAN MASUK
// ============================================================
date_default_timezone_set('Asia/Jakarta');
$waktu   = date('d/m/Y H:i');
$orderNo = str_pad($id_pesanan, 4, '0', STR_PAD_LEFT);
$namaPembeli = htmlspecialchars($order['nama_pembeli']);
$totalFmt    = 'Rp ' . number_format($order['total']);

if ($metode === 'transfer') {

    // ── Teks notif ──
    $text = "💳 <b>BUKTI BAYAR MASUK!</b>\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "🧾 <b>Order:</b> #" . $orderNo . "\n"
          . "👤 <b>Nama:</b> {$namaPembeli}\n"
          . "💰 <b>Total:</b> {$totalFmt}\n"
          . "💳 <b>Metode:</b> Transfer / QRIS\n"
          . "⏰ <b>Waktu:</b> {$waktu} WIB\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "📸 <i>Bukti transfer terlampir di bawah.</i>\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "✅ Silakan verifikasi dan konfirmasi pembayaran!";

    $buttons = [
        ['text' => '✅ Terima Pembayaran',
         'url'  => ADMIN_URL . "/update_status.php?id={$id_pesanan}&status=dibayar"],
        ['text' => '❌ Tolak',
         'url'  => ADMIN_URL . "/update_status.php?id={$id_pesanan}&status=ditolak"],
        ['text' => '🧾 Lihat Transaksi',
         'url'  => ADMIN_URL . "/transactions.php"],
    ];

    // Kirim teks dulu
    sendTelegram($text, $buttons);

    // Kirim foto bukti jika ada
    if ($buktiPath && file_exists($buktiPath)) {
        $caption = "📸 <b>Bukti Transfer</b>\n"
                 . "Order #{$orderNo} — {$namaPembeli}\n"
                 . "💰 {$totalFmt}";
        sendTelegramPhoto($buktiPath, $caption);
    }

} elseif ($metode === 'cod') {

    $text = "💵 <b>ORDER COD SIAP KIRIM!</b>\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "🧾 <b>Order:</b> #" . $orderNo . "\n"
          . "👤 <b>Nama:</b> {$namaPembeli}\n"
          . "💰 <b>Total COD:</b> {$totalFmt}\n"
          . "📍 <b>Alamat:</b> " . htmlspecialchars($order['alamat'] ?? '-') . "\n"
          . "📱 <b>WA:</b> " . htmlspecialchars($order['no_wa'] ?? '-') . "\n"
          . "⏰ <b>Waktu:</b> {$waktu} WIB\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "🚚 Siapkan pesanan untuk dikirim!\n"
          . "💵 Tagih <b>{$totalFmt}</b> saat barang diterima.";

    $buttons = [
        ['text' => '✅ Konfirmasi COD',
         'url'  => ADMIN_URL . "/update_status.php?id={$id_pesanan}&status=dibayar"],
        ['text' => '💬 Chat Customer',
         'url'  => ADMIN_URL . "/chat_room.php?room={$id_pesanan}"],
    ];

    sendTelegram($text, $buttons);
}

// ── Redirect ke halaman sukses ──
header("Location: ../customer/payment_success.php?id={$id_pesanan}&metode={$metode}");
exit;