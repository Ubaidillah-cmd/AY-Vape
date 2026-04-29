<?php
include "../config/db.php";

$room = $_POST['room'];
$sender = $_POST['sender'];
$nama = $_POST['nama'] ?? '';
$message = $_POST['message'] ?? '';
$lat = $_POST['lat'] ?? null;
$lng = $_POST['lng'] ?? null;

$fileName = "";

// 🔥 HANDLE FILE
if (!empty($_FILES['file']['name'])) {
    $fileName = time() . "_" . $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/chat/" . $fileName);
}

// 🔥 INSERT
mysqli_query($conn, "INSERT INTO chat 
(room_id, sender, nama_pengirim, message, latitude, longitude, file, status)
VALUES 
('$room','$sender','$nama','$message','$lat','$lng','$fileName','sent')");
