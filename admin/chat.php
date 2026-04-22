<?php
include "../config/db.php";

// ambil semua room (pesanan)
$data = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY id DESC");
?>

<h2>Chat Customer</h2>

<?php while($d = mysqli_fetch_assoc($data)) { ?>
    <p>
        <a href="chat_room.php?room=<?= $d['id'] ?>">
            Chat Order #<?= $d['id'] ?> (<?= $d['nama_pembeli'] ?>)
        </a>
    </p>
<?php } ?>