<?php
// ============================================================
//  ajax/send_message.php
//  Kirim pesan chat + notifikasi Telegram ke admin
//  (hanya jika pengirim = customer)
// ============================================================

include "../config/db.php";
include "../config/telegram.php";

$room    = (int)($_POST['room']    ?? 0);
$sender  = $_POST['sender']        ?? '';
$nama    = $_POST['nama']          ?? 'Customer';
$message = $_POST['message']       ?? '';
$lat     = $_POST['lat']           ?? null;
$lng     = $_POST['lng']           ?? null;

$fileName = '';

// ── Handle file upload ──
if (!empty($_FILES['file']['name'])) {
    $ext      = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    $allowed  = ['jpg','jpeg','png','webp','gif','pdf','doc','docx'];

    if (in_array($ext, $allowed)) {
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['file']['name']));
        move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/chat/" . $fileName);
    }
}

// ── Sanitize ──
$messageSafe  = mysqli_real_escape_string($conn, $message);
$senderSafe   = mysqli_real_escape_string($conn, $sender);
$namaSafe     = mysqli_real_escape_string($conn, $nama);
$fileNameSafe = mysqli_real_escape_string($conn, $fileName);
$latVal       = $lat  ? (float)$lat  : null;
$lngVal       = $lng  ? (float)$lng  : null;
$latSQL       = $latVal !== null ? "'$latVal'" : 'NULL';
$lngSQL       = $lngVal !== null ? "'$lngVal'" : 'NULL';

// ── Insert pesan customer ke DB ──
mysqli_query($conn, "
    INSERT INTO chat (room_id, sender, nama_pengirim, message, latitude, longitude, file, status)
    VALUES ('$room','$senderSafe','$namaSafe','$messageSafe',$latSQL,$lngSQL,'$fileNameSafe','sent')
");

// ============================================================
//  AUTO-REPLY #1 — Pesan sambutan otomatis dari admin
//  Hanya dikirim SEKALI: saat customer pertama kali kirim pesan
//  di room ini (belum ada pesan sebelumnya dari customer)
// ============================================================
if ($senderSafe === 'customer') {

    // Cek apakah ini pesan PERTAMA customer di room ini
    // (sebelum insert tadi, tidak ada pesan dari customer)
    $cekPertama = mysqli_query($conn, "
        SELECT COUNT(*) as total
        FROM chat
        WHERE room_id='$room' AND sender='customer'
    ");
    $rowCek = mysqli_fetch_assoc($cekPertama);
    $jumlahPesanCustomer = (int)($rowCek['total'] ?? 0);

    // Jika ini pesan pertama customer (total = 1 setelah insert)
    if ($jumlahPesanCustomer === 1) {
        $autoReply1 = "Halo 👋\nTerima kasih sudah menghubungi AY Vape.\n\nMohon tunggu sebentar ya,\nadmin akan membalas pesan kamu untuk konfirmasi COD / pengantaran.";
        $autoReply1Safe = mysqli_real_escape_string($conn, $autoReply1);

        // Delay kecil agar urutan pesan lebih natural (1 detik setelah pesan customer)
        // Gunakan created_at dengan +1 detik via INTERVAL
        mysqli_query($conn, "
            INSERT INTO chat (room_id, sender, nama_pengirim, message, status, created_at)
            VALUES ('$room', 'admin', 'AY Vape', '$autoReply1Safe', 'read',
                    DATE_ADD(NOW(), INTERVAL 1 SECOND))
        ");
    }
}

// ============================================================
//  NOTIFIKASI TELEGRAM
//  Hanya kirim jika pengirim adalah customer (bukan admin)
// ============================================================
if ($senderSafe === 'customer') {

    // Ambil data pesanan untuk info nama pembeli
    $orderQ = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$room' LIMIT 1");
    $order  = mysqli_fetch_assoc($orderQ);
    $namaPembeli = $order['nama_pembeli'] ?? $namaSafe;
    $orderNo     = str_pad($room, 4, '0', STR_PAD_LEFT);

    // Susun preview pesan
    $preview = '';
    if (!empty($message)) {
        $preview = htmlspecialchars(mb_substr($message, 0, 200));
    } elseif ($latVal !== null) {
        $preview = "📍 <i>Mengirim lokasi</i>";
    } elseif (!empty($fileName)) {
        $preview = "📎 <i>Mengirim file: " . htmlspecialchars($fileName) . "</i>";
    }

    // Waktu sekarang WIB
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date('d/m/Y H:i');

    // Susun teks notif
    $text = "🔔 <b>PESAN BARU — AY VAPE</b>\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "👤 <b>Pembeli:</b> {$namaPembeli}\n"
          . "🧾 <b>Order:</b> #" . $orderNo . "\n"
          . "⏰ <b>Waktu:</b> {$waktu} WIB\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "💬 <b>Pesan:</b>\n{$preview}\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "⚡ Segera balas pesan customer!";

    // Tombol langsung buka chat admin
    $buttons = [
        [
            'text' => '💬 Buka Chat Admin',
            'url'  => ADMIN_URL . "/chat_room.php?room={$room}"
        ]
    ];

    // Kirim ke Telegram (non-blocking, tidak memblok response)
    sendTelegram($text, $buttons);

    // Jika ada file gambar, kirim juga fotonya
    if (!empty($fileName)) {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
            $photoPath = "../uploads/chat/" . $fileName;
            sendTelegramPhoto(
                $photoPath,
                "📸 File dari <b>{$namaPembeli}</b> (Order #{$orderNo})"
            );
        }
    }
}

// Response JSON
echo json_encode(['ok' => true]);