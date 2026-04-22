<?php
$room = $_GET['room']; // ambil dari pesanan
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat</title>

<style>
body { font-family: Arial; background:#ece5dd; }

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

.customer {
    background: #dcf8c6;
    margin-left: auto;
}

.admin {
    background: white;
    margin-right: auto;
}

.input-box {
    display:flex;
}
</style>
</head>

<body>

<h3>Chat Admin</h3>

<div class="chat-box" id="chat"></div>

<div class="input-box">
<input type="text" id="msg" placeholder="Ketik pesan..." style="flex:1;">
<button onclick="sendMsg()">➤</button>
<button onclick="sendLoc()">📍</button>
</div>

<script>
let room = <?= $room ?>;

function loadChat() {
    fetch(`../ajax/get_message.php?room=${room}`)
    .then(r=>r.text())
    .then(d=>{
        document.getElementById("chat").innerHTML = d;
    });
}

setInterval(loadChat, 1500);

// 🔔 NOTIFIKASI
let lastCount = 0;
function checkNotif() {
    fetch(`../ajax/get_message.php?room=${room}`)
    .then(r=>r.text())
    .then(d=>{
        let count = d.length;
        if(count > lastCount){
            new Audio("https://www.soundjay.com/buttons/sounds/button-3.mp3").play();
        }
        lastCount = count;
    });
}
setInterval(checkNotif,2000);

function sendMsg(){
    let msg = document.getElementById("msg").value;

    fetch("../ajax/send_message.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`room=${room}&sender=customer&message=${msg}`
    });

    document.getElementById("msg").value="";
}

function sendLoc(){
navigator.geolocation.getCurrentPosition(pos=>{
    fetch("../ajax/send_message.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`room=${room}&sender=customer&lat=${pos.coords.latitude}&lng=${pos.coords.longitude}`
    });
});
}
</script>

</body>
</html>