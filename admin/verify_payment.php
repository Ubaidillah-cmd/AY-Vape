<?php
session_start();
include "../config/db.php";

$data = mysqli_query($conn, "
SELECT payment.*, pesanan.nama_pembeli 
FROM payment 
JOIN pesanan ON payment.id_pesanan = pesanan.id
");

echo "<h2>Data Payment</h2>";

while ($p = mysqli_fetch_assoc($data)) {
?>

<div style="border:1px solid #000; padding:10px; margin:10px;">
    <p>Nama: <?= $p['nama_pembeli'] ?></p>
    <p>Metode: <?= $p['metode'] ?></p>
    <p>Status: <?= $p['status'] ?></p>

    <?php if ($p['bukti']) { ?>
        <img src="../uploads/bukti_pembayaran/<?= $p['bukti'] ?>" width="150">
    <?php } ?>

    <br>

    <a href="update_status.php?id=<?= $p['id'] ?>&status=dibayar">Terima</a> |
    <a href="update_status.php?id=<?= $p['id'] ?>&status=ditolak">Tolak</a>
</div>

<?php } ?>