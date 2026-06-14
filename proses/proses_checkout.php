<?php
// ============================================================
//  proses/proses_checkout.php
//  Proses checkout + notifikasi Telegram order baru
// ============================================================

session_start();
include "../config/db.php";
include "../config/telegram.php";

if (empty($_SESSION['cart'])) {
    header("Location: ../customer/cart.php");
    exit;
}

$nama    = mysqli_real_escape_string($conn, $_POST['nama']    ?? '');
$wa      = mysqli_real_escape_string($conn, $_POST['wa']      ?? '');
$alamat  = mysqli_real_escape_string($conn, $_POST['alamat']  ?? '');
$catatan = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

// ── Hitung total ──
$total = 0;
$itemLines = [];

foreach ($_SESSION['cart'] as $id => $qty) {
    $q = mysqli_query($conn, "SELECT * FROM produk WHERE id=" . (int)$id);
    $p = mysqli_fetch_assoc($q);
    if (!$p) continue;

    $sub    = $p['harga'] * $qty;
    $total += $sub;
    $itemLines[] = "  • " . htmlspecialchars($p['nama_produk'])
                 . " ×{$qty} = <b>Rp " . number_format($sub) . "</b>";
}

// ── Insert pesanan ──
$tanggal = date('Y-m-d H:i:s');
mysqli_query($conn, "
    INSERT INTO pesanan (nama_pembeli, total, tanggal, alamat, no_wa, catatan)
    VALUES ('$nama', '$total', '$tanggal', '$alamat', '$wa', '$catatan')
");

$id_pesanan = mysqli_insert_id($conn);

// ── Insert detail pesanan ──
foreach ($_SESSION['cart'] as $id => $qty) {
    $q = mysqli_query($conn, "SELECT * FROM produk WHERE id=" . (int)$id);
    $p = mysqli_fetch_assoc($q);
    if (!$p) continue;

    $harga_satuan = $p['harga'];
    mysqli_query($conn, "
        INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga_satuan)
        VALUES ('$id_pesanan', '$id', '$qty', '$harga_satuan')
    ");

    // Kurangi stok
    mysqli_query($conn, "UPDATE produk SET stok = stok - $qty WHERE id = $id AND stok >= $qty");
}

// ── Kosongkan cart ──
$_SESSION['cart'] = [];

// ============================================================
//  NOTIFIKASI TELEGRAM — ORDER BARU
// ============================================================
date_default_timezone_set('Asia/Jakarta');
$waktu   = date('d/m/Y H:i');
$orderNo = str_pad($id_pesanan, 4, '0', STR_PAD_LEFT);
$itemStr = implode("\n", $itemLines);

$text = "🛒 <b>ORDER BARU MASUK!</b>\n"
      . "━━━━━━━━━━━━━━━━━━━\n"
      . "🧾 <b>Order:</b> #" . $orderNo . "\n"
      . "👤 <b>Nama:</b> " . htmlspecialchars($nama) . "\n"
      . "📱 <b>WhatsApp:</b> " . htmlspecialchars($wa ?: '-') . "\n"
      . "📍 <b>Alamat:</b> " . htmlspecialchars($alamat ?: '-') . "\n"
      . "⏰ <b>Waktu:</b> {$waktu} WIB\n"
      . "━━━━━━━━━━━━━━━━━━━\n"
      . "🛍️ <b>Detail Produk:</b>\n"
      . $itemStr . "\n"
      . "━━━━━━━━━━━━━━━━━━━\n"
      . "💰 <b>TOTAL: Rp " . number_format($total) . "</b>\n"
      . "━━━━━━━━━━━━━━━━━━━\n"
      . ($catatan ? "📝 <b>Catatan:</b> " . htmlspecialchars($catatan) . "\n" : "")
      . "⚡ Segera proses pesanan ini!";

$buttons = [
    ['text' => '🧾 Lihat Transaksi', 'url' => ADMIN_URL . "/transactions.php"],
    ['text' => '💬 Chat Customer',   'url' => ADMIN_URL . "/chat_room.php?room={$id_pesanan}"],
];

sendTelegram($text, $buttons);

// ── Redirect ke halaman pembayaran ──
header("Location: ../customer/payment.php?id={$id_pesanan}");
exit;