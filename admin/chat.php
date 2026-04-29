<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 🔥 ambil semua room + last chat
$data = mysqli_query($conn, "
SELECT p.id, p.nama_pembeli,

(
    SELECT message FROM chat 
    WHERE chat.room_id = p.id 
    ORDER BY id DESC LIMIT 1
) as last_msg,

(
    SELECT created_at FROM chat 
    WHERE chat.room_id = p.id 
    ORDER BY id DESC LIMIT 1
) as last_time

FROM pesanan p
ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat Customer</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #0f172a;
    color: white;
}

/* TOPBAR (HP) */
.topbar {
    display: none;
    background: #020617;
    padding: 12px;
    align-items: center;
    gap: 10px;
}

.topbar button {
    background: none;
    border: none;
    color: white;
    font-size: 22px;
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
}

.sidebar a {
    display: block;
    padding: 12px;
    margin: 10px 0;
    color: white;
    text-decoration: none;
    border-radius: 8px;
}

.sidebar a:hover {
    background: #1e293b;
}

/* MAIN */
.main {
    margin-left: 240px;
    padding: 20px;
}

/* CHAT LIST */
.chat-list {
    margin-top: 20px;
}

.chat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #1e293b;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 10px;
    text-decoration: none;
    color: white;
    transition: 0.3s;
}

.chat-item:hover {
    background: #334155;
}

.chat-left {
    display: flex;
    flex-direction: column;
}

.chat-name {
    font-weight: bold;
}

.chat-msg {
    font-size: 13px;
    opacity: 0.7;
}

.chat-time {
    font-size: 12px;
    opacity: 0.6;
}

/* RESPONSIVE */
@media (max-width: 768px) {

    .topbar {
        display: flex;
    }

    .sidebar {
        left: -250px;
        z-index: 1000;
    }

    .sidebar.active {
        left: 0;
    }

    .main {
        margin-left: 0;
    }
}
</style>
</head>

<body>

<!-- TOPBAR -->
<div class="topbar">
    <button onclick="toggleSidebar()">☰</button>
    <span>💬 Chat Customer</span>
</div>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="products.php">📦 Produk</a>
    <a href="transactions.php">💳 Transaksi</a>
    <a href="chat.php">💬 Chat</a>
    <a href="../proses/logout.php">🚪 Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h2>💬 Chat Customer</h2>

<div class="chat-list">

<?php while($d = mysqli_fetch_assoc($data)) { ?>

<a href="chat_room.php?room=<?= $d['id'] ?>" class="chat-item">

    <div class="chat-left">
        <div class="chat-name">
            <?= $d['nama_pembeli'] ?> (Order #<?= $d['id'] ?>)
        </div>

        <div class="chat-msg">
            <?= $d['last_msg'] ?? 'Belum ada pesan' ?>
        </div>
    </div>

    <div class="chat-time">
        <?= $d['last_time'] ? date("H:i", strtotime($d['last_time'])) : '' ?>
    </div>

</a>

<?php } ?>

</div>

</div>

<script>
function toggleSidebar() {
    document.querySelector(".sidebar").classList.toggle("active");
}
</script>

</body>
</html>
