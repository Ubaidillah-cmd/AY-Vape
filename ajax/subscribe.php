<?php
// ============================================================
//  ajax/subscribe.php
//  Simpan subscriber ke DB + kirim notif Telegram ke admin
// ============================================================

include "../config/db.php";
include "../config/telegram.php";

header('Content-Type: application/json');

$email     = trim($_POST['email']     ?? '');
$halaman   = trim($_POST['halaman']   ?? '');  // halaman asal
$ip        = $_SERVER['REMOTE_ADDR']  ?? '';
$ua        = $_SERVER['HTTP_USER_AGENT'] ?? '';

// ── Validasi ──
if (empty($email)) {
    echo json_encode(['ok' => false, 'msg' => 'Email tidak boleh kosong']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'msg' => 'Format email tidak valid']);
    exit;
}

$emailSafe   = mysqli_real_escape_string($conn, $email);
$halamanSafe = mysqli_real_escape_string($conn, $halaman);
$ipSafe      = mysqli_real_escape_string($conn, $ip);
$uaSafe      = mysqli_real_escape_string($conn, substr($ua, 0, 500));

// ── Cek duplikasi ──
$cek = mysqli_query($conn, "SELECT id FROM subscribers WHERE email='$emailSafe' LIMIT 1");
if (mysqli_fetch_assoc($cek)) {
    echo json_encode(['ok' => false, 'type' => 'duplicate', 'msg' => 'Email ini sudah terdaftar!']);
    exit;
}

// ── Simpan ke DB ──
mysqli_query($conn, "
    INSERT INTO subscribers (email, ip_address, user_agent, halaman)
    VALUES ('$emailSafe', '$ipSafe', '$uaSafe', '$halamanSafe')
");

if (mysqli_affected_rows($conn) === 0) {
    echo json_encode(['ok' => false, 'msg' => 'Gagal menyimpan, coba lagi']);
    exit;
}

// ── Deteksi device dari user agent ──
$device = 'Desktop 🖥️';
if (preg_match('/Mobile|Android|iPhone|iPad/i', $ua)) {
    $device = 'Mobile 📱';
}
if (preg_match('/Tablet|iPad/i', $ua)) {
    $device = 'Tablet 📟';
}

// ── Deteksi browser ──
$browser = 'Unknown';
if (str_contains($ua, 'Chrome'))  $browser = 'Chrome';
elseif (str_contains($ua, 'Firefox')) $browser = 'Firefox';
elseif (str_contains($ua, 'Safari'))  $browser = 'Safari';
elseif (str_contains($ua, 'Edge'))    $browser = 'Edge';
elseif (str_contains($ua, 'Opera'))   $browser = 'Opera';

// ── Total subscriber ──
$totalQ = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM subscribers"));
$total  = $totalQ['total'];

// ── Waktu ──
date_default_timezone_set('Asia/Jakarta');
$waktu = date('d/m/Y H:i');

// ── Notifikasi Telegram ──
$halamanLabel = $halaman ?: 'Tidak diketahui';

$text = "📧 <b>SUBSCRIBER BARU!</b>\n"
      . "━━━━━━━━━━━━━━━━━━━\n"
      . "📩 <b>Email:</b> " . htmlspecialchars($email) . "\n"
      . "📄 <b>Dari Halaman:</b> " . htmlspecialchars($halamanLabel) . "\n"
      . "📱 <b>Device:</b> {$device}\n"
      . "🌐 <b>Browser:</b> {$browser}\n"
      . "🌍 <b>IP:</b> <code>{$ip}</code>\n"
      . "⏰ <b>Waktu:</b> {$waktu} WIB\n"
      . "━━━━━━━━━━━━━━━━━━━\n"
      . "👥 <b>Total Subscriber:</b> {$total} orang";

$buttons = [
    ['text' => '👥 Lihat Semua Subscriber', 'url' => ADMIN_URL . "/subscribers.php"],
];

sendTelegram($text, $buttons);

echo json_encode([
    'ok'    => true,
    'msg'   => 'Subscribe berhasil!',
    'total' => $total,
]);