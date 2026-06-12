<?php
// ============================================================
//  ajax/check_auto_reply.php
//  Cek apakah admin belum membalas > 5 menit sejak pesan
//  terakhir customer → jika iya, insert auto-reply #2
//
//  Dipanggil oleh halaman chat customer (polling setiap 30 dtk)
//  Parameter GET: ?room=ID
// ============================================================

include "../config/db.php";

header('Content-Type: application/json');

$room = (int)($_GET['room'] ?? 0);
if (!$room) {
    echo json_encode(['ok' => false]);
    exit;
}

// ── 1. Pastikan ada pesan dari customer di room ini ──
$cekCustomer = mysqli_query($conn, "
    SELECT COUNT(*) as total FROM chat
    WHERE room_id='$room' AND sender='customer'
");
$rowC = mysqli_fetch_assoc($cekCustomer);
if ((int)$rowC['total'] === 0) {
    echo json_encode(['ok' => false, 'reason' => 'no_customer_msg']);
    exit;
}

// ── 2. Cek apakah auto-reply #2 sudah pernah dikirim ──
//    Kita tandai auto-reply #2 dengan message yang spesifik
$markerText = 'Admin belum sempat membalas';
$markerSafe = mysqli_real_escape_string($conn, $markerText);

$cekAR2 = mysqli_query($conn, "
    SELECT id FROM chat
    WHERE room_id='$room'
      AND sender='admin'
      AND message LIKE '%$markerSafe%'
    LIMIT 1
");
if (mysqli_fetch_assoc($cekAR2)) {
    // Auto-reply #2 sudah pernah terkirim, tidak perlu lagi
    echo json_encode(['ok' => false, 'reason' => 'already_sent']);
    exit;
}

// ── 3. Cek apakah admin sudah membalas SECARA NYATA ──
//    (bukan auto-reply #1 yang kita insert sendiri)
$autoReply1Marker = 'Mohon tunggu sebentar ya';
$ar1Safe = mysqli_real_escape_string($conn, $autoReply1Marker);

$cekAdminBalas = mysqli_query($conn, "
    SELECT id FROM chat
    WHERE room_id='$room'
      AND sender='admin'
      AND message NOT LIKE '%$ar1Safe%'
      AND message NOT LIKE '%$markerSafe%'
    LIMIT 1
");
if (mysqli_fetch_assoc($cekAdminBalas)) {
    // Admin sudah balas secara nyata, tidak perlu auto-reply #2
    echo json_encode(['ok' => false, 'reason' => 'admin_replied']);
    exit;
}

// ── 4. Cek waktu pesan TERAKHIR dari customer ──
$lastCustomerMsg = mysqli_query($conn, "
    SELECT created_at FROM chat
    WHERE room_id='$room' AND sender='customer'
    ORDER BY id DESC LIMIT 1
");
$rowLast = mysqli_fetch_assoc($lastCustomerMsg);
if (!$rowLast) {
    echo json_encode(['ok' => false, 'reason' => 'no_msg']);
    exit;
}

$lastMsgTime  = strtotime($rowLast['created_at']);
$nowTime      = time();
$selisihMenit = ($nowTime - $lastMsgTime) / 60;

// ── 5. Jika > 5 menit, insert auto-reply #2 ──
if ($selisihMenit >= 5) {
    $autoReply2 = "Admin belum sempat membalas 🙏\n\nSilakan tunggu di WhatsApp ya,\nadmin akan segera menghubungi kamu melalui WA untuk kelanjutan pesanan.\nTerima kasih atas pengertiannya.";
    $ar2Safe = mysqli_real_escape_string($conn, $autoReply2);

    mysqli_query($conn, "
        INSERT INTO chat (room_id, sender, nama_pengirim, message, status)
        VALUES ('$room', 'admin', 'AY Vape', '$ar2Safe', 'read')
    ");

    echo json_encode(['ok' => true, 'action' => 'auto_reply_2_sent']);
} else {
    // Belum 5 menit
    $sisaMenit = round(5 - $selisihMenit, 1);
    echo json_encode(['ok' => false, 'reason' => 'not_yet', 'sisa_menit' => $sisaMenit]);
}
