<?php
include "../config/db.php";

$room = $_POST['room'];
$sender = $_POST['sender'];
$nama = $_POST['nama'];
$message = $_POST['message'] ?? '';
$lat = $_POST['lat'] ?? null;
$lng = $_POST['lng'] ?? null;

mysqli_query($conn, "INSERT INTO chat 
(room_id, sender, nama_pengirim, message, latitude, longitude) 
VALUES 
('$room','$sender','$nama','$message','$lat','$lng')");