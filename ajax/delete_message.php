<?php
// ============================================================
//  ajax/delete_message.php
//  Hapus satu pesan chat (hanya boleh admin)
// ============================================================

session_start();
include "../config/db.php";

header('Content-Type: application/json');

// Hanya admin yang boleh hapus
if (!isset($_SESSION['login'])) {
    echo json_encode(['ok' => false, 'msg' => 'Unauthorized']);
    exit;
}

$id   = (int)($_POST['id']   ?? 0);
$room = (int)($_POST['room'] ?? 0);

if (!$id || !$room) {
    echo json_encode(['ok' => false, 'msg' => 'Invalid params']);
    exit;
}

// Pastikan pesan milik room yang benar
$cek = mysqli_query($conn, "SELECT id FROM chat WHERE id='$id' AND room_id='$room' LIMIT 1");
if (!mysqli_fetch_assoc($cek)) {
    echo json_encode(['ok' => false, 'msg' => 'Not found']);
    exit;
}

// Hard delete
mysqli_query($conn, "DELETE FROM chat WHERE id='$id' AND room_id='$room'");

echo json_encode(['ok' => true, 'msg' => 'Pesan dihapus']);