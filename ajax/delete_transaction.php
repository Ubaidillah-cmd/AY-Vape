<?php
// ============================================================
//  ajax/delete_transaction.php
//  Hapus 1 transaksi (pesanan) beserta semua data terkait:
//  - detail_pesanan
//  - payment (+ file bukti)
//  - chat
//  - pesanan itu sendiri
//  Admin only.
// ============================================================

session_start();
include "../config/db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {
    echo json_encode(['ok' => false, 'msg' => 'Unauthorized']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['ok' => false, 'msg' => 'Invalid ID']);
    exit;
}

// ── 1. Hapus file bukti pembayaran dari disk (kalau ada) ──
$payQ = mysqli_query($conn, "SELECT bukti FROM payment WHERE id_pesanan='$id' LIMIT 1");
if ($pay = mysqli_fetch_assoc($payQ)) {
    if (!empty($pay['bukti'])) {
        $filePath = "../uploads/bukti_pembayaran/" . $pay['bukti'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}

// ── 2. Hapus file chat (gambar/file yang dikirim di chat) ──
$chatFiles = mysqli_query($conn, "SELECT file FROM chat WHERE room_id='$id' AND file != ''");
while ($cf = mysqli_fetch_assoc($chatFiles)) {
    $filePath = "../uploads/chat/" . $cf['file'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// ── 3. Hapus semua data terkait (urutan penting karena FK) ──
mysqli_query($conn, "DELETE FROM detail_pesanan WHERE id_pesanan='$id'");
mysqli_query($conn, "DELETE FROM payment WHERE id_pesanan='$id'");
mysqli_query($conn, "DELETE FROM chat WHERE room_id='$id'");
mysqli_query($conn, "DELETE FROM pesanan WHERE id='$id'");

$affected = mysqli_affected_rows($conn);

if ($affected > 0) {
    echo json_encode(['ok' => true, 'msg' => "Transaksi #{$id} berhasil dihapus"]);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Data tidak ditemukan']);
}
