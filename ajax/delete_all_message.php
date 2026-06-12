<?php
// ============================================================
//  ajax/delete_all_messages.php
//  Hapus SEMUA pesan di 1 room (hanya admin)
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

mysqli_query($conn, "DELETE FROM chat WHERE room_id='$room'");
$affected = mysqli_affected_rows($conn);

echo json_encode(['ok' => true, 'msg' => "$affected pesan dihapus"]);