<?php
include "../config/db.php";

$room = $_GET['room'];

// 🔥 UPDATE STATUS JADI READ
mysqli_query($conn, "UPDATE chat SET status='read' WHERE room_id='$room'");

$data = mysqli_query($conn, "SELECT * FROM chat WHERE room_id='$room' ORDER BY id ASC");

while ($d = mysqli_fetch_assoc($data)) {

    $class = ($d['sender'] == 'admin') ? 'admin' : 'customer';

    echo "<div class='msg $class'>";

    // 🔥 TEXT
    if (!empty($d['message'])) {
        echo $d['message'];
    }

    // 📍 LOKASI
    if (!empty($d['latitude'])) {
        echo "<br><a target='_blank' href='https://maps.google.com/?q={$d['latitude']},{$d['longitude']}'>📍 Lokasi</a>";
    }

    // 🖼️ GAMBAR / FILE
    if (!empty($d['file'])) {
        $file = $d['file'];
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if (in_array($ext, ['jpg','png','jpeg','webp'])) {
            echo "<br><img src='../uploads/chat/$file' width='150' style='border-radius:10px'>";
        } else {
            echo "<br><a href='../uploads/chat/$file' target='_blank'>📎 Download File</a>";
        }
    }

    // ✅ STATUS (CENTANG)
    $statusIcon = ($d['status'] == 'read') ? "✔✔" : "✔";

    echo "<div class='time'>{$d['created_at']} $statusIcon</div>";

    echo "</div>";
}
