<?php
// ============================================================
//  ajax/delete_room.php
//  Hapus 1 room chat beserta semua pesannya (admin only)
// ============================================================

session_start();
include "../config/db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {
    echo json_encode(['ok' => false, 'msg' => 'Unauthorized']);
    exit;
}

$room = (int)($_POST['room'] ?? 0);

if (!$room) {
    echo json_encode(['ok' => false, 'msg' => 'Invalid room']);
    exit;
}

// Hapus file attachment chat dari disk
$chatFiles = mysqli_query($conn, "SELECT file FROM chat WHERE room_id='$room' AND file != ''");
while ($cf = mysqli_fetch_assoc($chatFiles)) {
    $fp = "../uploads/chat/" . $cf['file'];
    if (file_exists($fp)) unlink($fp);
}

// Hapus semua pesan di room ini
mysqli_query($conn, "DELETE FROM chat WHERE room_id='$room'");
$deleted = mysqli_affected_rows($conn);

// Hapus data transaksi terkait (agar room tidak muncul lagi di list)
$payQ = mysqli_query($conn, "SELECT bukti FROM payment WHERE id_pesanan='$room' LIMIT 1");
if ($pay = mysqli_fetch_assoc($payQ)) {
    if (!empty($pay['bukti'])) {
        $fp = "../uploads/bukti_pembayaran/" . $pay['bukti'];
        if (file_exists($fp)) unlink($fp);
    }
}
mysqli_query($conn, "DELETE FROM detail_pesanan WHERE id_pesanan='$room'");
mysqli_query($conn, "DELETE FROM payment WHERE id_pesanan='$room'");
mysqli_query($conn, "DELETE FROM pesanan WHERE id='$room'");

echo json_encode([
    'ok'      => true,
    'msg'     => "Room #{$room} dihapus ({$deleted} pesan)",
    'room_id' => $room
]);