<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include "../config/db.php";
?>

<h2>Data Produk</h2>
<a href="create_product.php">+ Tambah Produk</a>
<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>No</th>
    <th>Gambar</th>
    <th>Nama</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>

<?php
$no = 1;
$data = mysqli_query($conn, "SELECT * FROM produk");

while ($p = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td><img src="../uploads/products/<?= $p['gambar'] ?>" width="80"></td>
    <td><?= $p['nama_produk'] ?></td>
    <td>Rp <?= number_format($p['harga']) ?></td>
    <td><?= $p['stok'] ?></td>
    <td>
        <a href="update_product.php?id=<?= $p['id'] ?>">Edit</a> |
        <a href="../proses/proses_delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>
<?php } ?>
</table>