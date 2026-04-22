<?php
include "../config/db.php";

$id = $_POST['id_pesanan'];
$metode = $_POST['metode'];
$nama = $_POST['nama'] ?? "Customer"; // ambil nama kalau ada

$bukti = "";

// upload bukti jika transfer
if ($metode == "transfer" && isset($_FILES['bukti']) && $_FILES['bukti']['name'] != "") {

    $nama_file = $_FILES['bukti']['name'];
    $tmp = $_FILES['bukti']['tmp_name'];

    $bukti = time() . "_" . $nama_file;

    move_uploaded_file($tmp, "../uploads/bukti_pembayaran/" . $bukti);
}

// simpan ke DB
mysqli_query($conn, "INSERT INTO payment (id_pesanan, metode, bukti, status)
VALUES ('$id','$metode','$bukti','pending')");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Berhasil</title>
    <meta http-equiv="refresh" content="2;url=../customer/chat.php?room=<?= $id ?>&nama=<?= urlencode($nama) ?>">
</head>
<body>

<h2>✅ Pembayaran berhasil dikirim!</h2>
<p>Kamu akan diarahkan ke chat admin...</p>

</body>
</html>