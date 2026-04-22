<?php
include "../config/db.php";

$room = $_GET['room'];

$data = mysqli_query($conn, "SELECT * FROM chat WHERE room_id='$room' ORDER BY id ASC");

while ($d = mysqli_fetch_assoc($data)) {

    $class = ($d['sender'] == 'admin') ? 'admin' : 'customer';

    if (!empty($d['latitude'])) {
        echo "<div class='msg $class'>
        📍 <a target='_blank' href='https://maps.google.com/?q={$d['latitude']},{$d['longitude']}'>Lokasi</a>
        </div>";
    } else {
        echo "<div class='msg $class'>{$d['message']}</div>";
    }
}