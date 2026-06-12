<?php
// ============================================================
//  ajax/get_message.php
//  Tampilkan pesan + tombol hapus per bubble (admin only)
//  Parameter: ?room=ID&admin=1 (admin mode)
// ============================================================

include "../config/db.php";

$room    = (int)$_GET['room'];
$isAdmin = isset($_GET['admin']) && $_GET['admin'] == '1';

// Mark as read
mysqli_query($conn, "UPDATE chat SET status='read' WHERE room_id='$room' AND sender != 'admin'");

$data = mysqli_query($conn, "SELECT * FROM chat WHERE room_id='$room' ORDER BY id ASC");

while ($d = mysqli_fetch_assoc($data)) {
    $class   = ($d['sender'] === 'admin') ? 'admin' : 'customer';
    $msgId   = (int)$d['id'];
    $waktu   = htmlspecialchars($d['created_at']);
    $statusIcon = ($d['status'] === 'read')
        ? '<span style="color:#6366f1">✔✔</span>'
        : '<span style="color:#64748b">✔</span>';

    echo "<div class='msg $class' id='msg-{$msgId}' style='position:relative;'>";

    // ── Konten pesan ──
    if (!empty($d['message'])) {
        echo "<div class='msg-text'>" . nl2br(htmlspecialchars($d['message'])) . "</div>";
    }

    if (!empty($d['latitude'])) {
        $lat = $d['latitude'];
        $lng = $d['longitude'];
        echo "<a target='_blank' href='https://maps.google.com/?q={$lat},{$lng}' "
           . "style='color:#22d3ee;font-size:13px;display:block;margin-top:5px;'>📍 Lihat Lokasi</a>";
    }

    if (!empty($d['file'])) {
        $file = htmlspecialchars($d['file']);
        $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','png','jpeg','webp','gif'])) {
            echo "<img src='../uploads/chat/{$file}' "
               . "style='margin-top:8px;border-radius:8px;max-width:150px;"
               . "border:1px solid rgba(99,102,241,0.2);display:block;'>";
        } else {
            echo "<a href='../uploads/chat/{$file}' target='_blank' "
               . "style='color:#22d3ee;font-size:13px;'>📎 Download File</a>";
        }
    }

    // ── Waktu + status ──
    echo "<div class='time'>{$waktu} {$statusIcon}</div>";

    // ── Tombol hapus (hanya admin mode) ──
    if ($isAdmin) {
        echo "<button class='msg-delete-btn' "
           . "onclick=\"deleteMsg({$msgId}, {$room})\" "
           . "title='Hapus pesan ini'>🗑</button>";
    }

    echo "</div>";
}