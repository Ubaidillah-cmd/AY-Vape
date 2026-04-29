<?php
$room = $_GET['room'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: radial-gradient(circle at top, #020617, #0f172a);
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: hidden; /* 🔥 biar ga kepotong footer */
}

/* 🔥 ANIMASI GLOBAL */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* HEADER */
.header {
    background: rgba(2,6,23,0.8);
    backdrop-filter: blur(10px);
    color: white;
    padding: 16px;
    font-weight: bold;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

/* 🔥 CHAT AREA FULL */
.chat-box {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: transparent;
    display: flex;
    flex-direction: column;
}

/* 🔥 BUBBLE */
.msg {
    max-width: 65%;
    padding: 12px 16px;
    margin: 6px 0;
    border-radius: 14px;
    font-size: 14px;
    animation: fadeUp 0.3s ease;
    position: relative;
}

/* CUSTOMER */
.customer {
    background: linear-gradient(45deg,#2563eb,#60a5fa);
    color: white;
    margin-left: auto;
    border-bottom-right-radius: 4px;
    box-shadow: 0 5px 15px rgba(37,99,235,0.3);
}

/* ADMIN */
.admin {
    background: rgba(30,41,59,0.8);
    backdrop-filter: blur(10px);
    color: white;
    margin-right: auto;
    border-bottom-left-radius: 4px;
}

/* TIME */
.time {
    font-size: 10px;
    opacity: 0.6;
    margin-top: 5px;
    text-align: right;
}

/* 🔥 INPUT AREA FIX BAWAH */
.input-box {
    display: flex;
    padding: 12px;
    background: rgba(2,6,23,0.8);
    backdrop-filter: blur(10px);
    gap: 8px;
    border-top: 1px solid rgba(255,255,255,0.05);
}

/* INPUT */
.input-box input {
    flex: 1;
    padding: 13px;
    border-radius: 30px;
    border: none;
    outline: none;
    background: #020617;
    color: white;
}

/* 🔥 BUTTON */
.btn {
    background: linear-gradient(45deg,#2563eb,#60a5fa);
    border: none;
    padding: 10px 14px;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    transition: 0.3s;
}

/* HOVER */
.btn:hover {
    transform: scale(1.1);
}

/* 🔥 LINK LOKASI */
.loc {
    display: block;
    margin-top: 5px;
    color: #60a5fa;
    font-size: 12px;
}

/* 🔥 SCROLLBAR */
.chat-box::-webkit-scrollbar {
    width: 5px;
}

.chat-box::-webkit-scrollbar-thumb {
    background: #2563eb;
    border-radius: 10px;
}

/* MOBILE */
@media(max-width:768px){
    .msg { max-width: 85%; }
}

</style>

</head>

<body>

<!-- HEADER -->
<div class="header">
💬 Chat dengan Admin
</div>

<!-- CHAT -->
<div class="chat-box" id="chat"></div>

<!-- INPUT -->
<div class="input-box">
    <input type="text" id="msg" placeholder="Ketik pesan...">
    <button class="btn" onclick="sendMsg()">➤</button>
    <button class="btn" onclick="sendLoc()">📍</button>
</div>

<script>
let room = <?= $room ?>;
let lastLength = 0;

// 🔄 LOAD CHAT
function loadChat() {
    fetch(`../ajax/get_message.php?room=${room}`)
    .then(r=>r.text())
    .then(d=>{
        document.getElementById("chat").innerHTML = d;
        document.getElementById("chat").scrollTop = 999999;

        // 🔔 NOTIF
        if(d.length > lastLength){
            new Audio("https://www.soundjay.com/buttons/sounds/button-3.mp3").play();
        }

        lastLength = d.length;
    });
}

setInterval(loadChat, 1500);

// 💬 KIRIM PESAN
function sendMsg(){
    let input = document.getElementById("msg");
    let msg = input.value;

    if(msg.trim() === "") return;

    fetch("../ajax/send_message.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`room=${room}&sender=customer&message=${msg}`
    });

    input.value="";

    // auto focus + efek
    input.focus();
}


// 📍 LOKASI
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
