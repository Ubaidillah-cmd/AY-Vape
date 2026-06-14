<?php
// ============================================================
//  panel/update_status.php
//  Update status pembayaran + notif Telegram ke customer via WA link
//  (dan ke admin sebagai konfirmasi)
// ============================================================

include "../config/db.php";
include "../config/telegram.php";

$id_pesanan = (int)($_GET['id']     ?? 0);
$status     = $_GET['status']       ?? '';

// Validasi status
$allowedStatus = ['dibayar', 'ditolak', 'pending'];
if (!in_array($status, $allowedStatus) || !$id_pesanan) {
    header("Location: transactions.php");
    exit;
}

// ── Ambil data pesanan + payment ──
$q = mysqli_query($conn, "
    SELECT p.*, pay.metode, pay.bukti
    FROM pesanan p
    LEFT JOIN payment pay ON p.id = pay.id_pesanan
    WHERE p.id = '$id_pesanan'
    LIMIT 1
");
$order = mysqli_fetch_assoc($q);

if (!$order) {
    header("Location: transactions.php");
    exit;
}

// ── Update status ──
mysqli_query($conn, "
    UPDATE payment SET status='$status' WHERE id_pesanan='$id_pesanan'
");

// ============================================================
//  NOTIFIKASI TELEGRAM — STATUS UPDATE
// ============================================================
date_default_timezone_set('Asia/Jakarta');
$waktu       = date('d/m/Y H:i');
$orderNo     = str_pad($id_pesanan, 4, '0', STR_PAD_LEFT);
$namaPembeli = htmlspecialchars($order['nama_pembeli']);
$totalFmt    = 'Rp ' . number_format($order['total']);
$metode      = $order['metode'] ?? '-';

if ($status === 'dibayar') {

    $text = "✅ <b>PEMBAYARAN DIKONFIRMASI</b>\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "🧾 <b>Order:</b> #" . $orderNo . "\n"
          . "👤 <b>Nama:</b> {$namaPembeli}\n"
          . "💰 <b>Total:</b> {$totalFmt}\n"
          . "💳 <b>Metode:</b> " . htmlspecialchars($metode) . "\n"
          . "⏰ <b>Dikonfirmasi:</b> {$waktu} WIB\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "📦 Pesanan siap untuk dikemas dan dikirim!\n"
          . "🚚 Segera proses pengiriman.";

    $buttons = [
        ['text' => '💬 Chat Customer',  'url' => ADMIN_URL . "/chat_room.php?room={$id_pesanan}"],
        ['text' => '🧾 Semua Transaksi', 'url' => ADMIN_URL . "/transactions.php"],
    ];

} else {

    $text = "❌ <b>PEMBAYARAN DITOLAK</b>\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "🧾 <b>Order:</b> #" . $orderNo . "\n"
          . "👤 <b>Nama:</b> {$namaPembeli}\n"
          . "💰 <b>Total:</b> {$totalFmt}\n"
          . "⏰ <b>Waktu:</b> {$waktu} WIB\n"
          . "━━━━━━━━━━━━━━━━━━━\n"
          . "⚠️ Hubungi customer untuk klarifikasi bukti pembayaran.";

    $buttons = [
        ['text' => '💬 Chat Customer',   'url' => ADMIN_URL . "/chat_room.php?room={$id_pesanan}"],
        ['text' => '🧾 Semua Transaksi',  'url' => ADMIN_URL . "/transactions.php"],
    ];
}

sendTelegram($text, $buttons);

header("Location: transactions.php");
exit;