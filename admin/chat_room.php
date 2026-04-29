<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$room = $_GET['room'];

// ambil data pesanan
$data = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$room'");
$d = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #0f172a;
    display: flex;
    color: white;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    background: #020617;
    height: 100vh;
    padding: 20px;
    position: fixed;
    transition: 0.3s;
}

.sidebar h2 {
    text-align: center;
    color: #60a5fa;
}

.sidebar a {
    display: block;
    padding: 12px;
    margin: 10px 0;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: 0.3s;
}

.sidebar a:hover {
    background: #1e293b;
}

/* MAIN */
.main {
    margin-left: 240px;
    width: 100%;
    display: flex;
    flex-direction: column;
    height: 100vh;
}

/* TOPBAR MOBILE */
.topbar {
    display: none;
    background: #020617;
    color: white;
    padding: 15px;
    font-weight: bold;
    justify-content: space-between;
    align-items: center;
}

/* HEADER */
.header {
    background: linear-gradient(90deg, #1e3a8a, #2563eb);
    color: white;
    padding: 14px;
    font-weight: bold;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

/* CHAT AREA */
.chat-box {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #020617;
}

/* MESSAGE */
.msg {
    max-width: 70%;
    padding: 10px 14px;
    margin: 8px 0;
    border-radius: 12px;
    font-size: 14px;
    position: relative;
    animation: fadeIn 0.25s ease;
}

/* ADMIN */
.admin {
    background: #2563eb;
    color: white;
    margin-left: auto;
    border-bottom-right-radius: 4px;
}

/* CUSTOMER */
.customer {
    background: #1e293b;
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

/* INPUT AREA */
.input-box {
    display: flex;
    padding: 10px;
    background: #020617;
    gap: 8px;
    border-top: 1px solid #1e293b;
}

/* INPUT */
.input-box input {
    flex: 1;
    padding: 12px;
    border-radius: 20px;
    border: none;
    outline: none;
    background: #1e293b;
    color: white;
}

/* BUTTON */
.btn {
    background: #2563eb;
    border: none;
    padding: 10px 14px;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    transition: 0.2s;
}

.btn:hover {
    background: #1d4ed8;
}

/* FILE PREVIEW */
.msg img {
    margin-top: 8px;
    border-radius: 10px;
    max-width: 150px;
}

/* ANIMATION */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* RESPONSIVE */
@media(max-width:768px){

    .sidebar {
        left: -220px;
    }

    .sidebar.active {
        left: 0;
    }

    .main {
        margin-left: 0;
    }

    .topbar {
        display: flex;
    }

    .msg {
        max-width: 90%;
    }
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <h2>🔥 Admin</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="products.php">📦 Produk</a>
    <a href="transactions.php">💳 Transaksi</a>
    <a href="chat.php">💬 Chat</a>
    <a href="../proses/logout.php">🚪 Logout</a>
</div>

<div class="main">

<div class="header">
    <a href="../admin/chat.php">← Kembali Ke Chat</a>
</div>

<!-- TOPBAR -->
<div class="topbar">
    💬 Chat
    <button onclick="toggleSidebar()">☰</button>
</div>

<!-- HEADER -->
<div class="header">
    Chat Order #<?= $room ?> (<?= $d['nama_pembeli'] ?>)
</div>

<!-- CHAT -->
<div class="chat-box" id="chat"></div>

<!-- INPUT -->
<div class="input-box">
    <input type="text" id="msg" placeholder="Ketik pesan...">

    <input type="file" id="file" style="display:none">

    <button class="btn" onclick="document.getElementById('file').click()">📎</button>
    <button class="btn" onclick="sendMsg()">➤</button>
    <button class="btn" onclick="sendLoc()">📍</button>
</div>

</div>

<script>
let room = <?= $room ?>;

// 🔄 LOAD CHAT (WA STYLE)
function loadChat() {
    fetch(`../ajax/get_message.php?room=${room}`)
    .then(res => res.text())
    .then(data => {
        document.getElementById("chat").innerHTML = data;
        document.getElementById("chat").scrollTop = 999999;
    });
}

setInterval(loadChat, 1500);

// 💬 SEND
function sendMsg(){
    let msg = document.getElementById("msg").value;
    if(msg.trim() === "") return;

    fetch("../ajax/send_message.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`room=${room}&sender=admin&nama=Admin&message=${msg}`
    });

    document.getElementById("msg").value="";
}

// 📍 LOCATION
function sendLoc(){
navigator.geolocation.getCurrentPosition(pos=>{
    fetch("../ajax/send_message.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`room=${room}&sender=admin&nama=Admin&lat=${pos.coords.latitude}&lng=${pos.coords.longitude}`
    });
});
}

// SIDEBAR
function toggleSidebar(){
    document.getElementById("sidebar").classList.toggle("active");
}
</script>

</body>
</html>
