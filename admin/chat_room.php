<?php
session_start();
include "../config/db.php";

$room = $_GET['room'];

// ambil nama customer
$data = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$room'");
$d = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat Admin</title>

<style>
body {
    font-family: Arial;
    background: #ece5dd;
}

.header {
    background: #075E54;
    color: white;
    padding: 10px;
}

.chat-box {
    height: 400px;
    overflow-y: scroll;
    padding: 10px;
}

.msg {
    max-width: 60%;
    padding: 10px;
    margin: 5px;
    border-radius: 10px;
}

.admin {
    background: #dcf8c6;
    margin-left: auto;
}

.customer {
    background: white;
    margin-right: auto;
}

.input-box {
    display: flex;
    padding: 10px;
    background: white;
}

input {
    flex: 1;
    padding: 10px;
}

button {
    padding: 10px;
}
</style>
</head>

<body>

<div class="header">
    Chat Order #<?= $room ?> (<?= $d['nama_pembeli'] ?>)
</div>

<div class="chat-box" id="chat"></div>

<div class="input-box">
    <input type="text" id="msg" placeholder="Ketik pesan...">
    <button onclick="sendMsg()">➤</button>
    <button onclick="sendLoc()">📍</button>
</div>

<script>
let room = <?= $room ?>;

// 🔄 LOAD CHAT
function loadChat() {
    fetch(`../ajax/get_message.php?room=${room}`)
    .then(r => r.text())
    .then(data => {
        document.getElementById("chat").innerHTML = data;
        document.getElementById("chat").scrollTop = 999999;
    });
}
setInterval(loadChat, 1500);

// 🔔 NOTIFIKASI
let last = 0;
function notif() {
    fetch(`../ajax/get_message.php?room=${room}`)
    .then(r => r.text())
    .then(data => {
        if (data.length > last) {
            new Audio("https://www.soundjay.com/buttons/sounds/button-3.mp3").play();
        }
        last = data.length;
    });
}
setInterval(notif, 2000);

// 💬 KIRIM PESAN
function sendMsg(){
    let msg = document.getElementById("msg").value;

    fetch("../ajax/send_message.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`room=${room}&sender=admin&nama=Admin&message=${msg}`
    });

    document.getElementById("msg").value="";
}

// 📍 KIRIM LOKASI
function sendLoc(){
navigator.geolocation.getCurrentPosition(pos=>{
    fetch("../ajax/send_message.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`room=${room}&sender=admin&nama=Admin&lat=${pos.coords.latitude}&lng=${pos.coords.longitude}`
    });
});
}
</script>

</body>
</html>