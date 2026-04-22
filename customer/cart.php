<?php
session_start();
include "../config/db.php";

// tambah ke cart
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }
}

// hapus item
if (isset($_GET['hapus'])) {
    unset($_SESSION['cart'][$_GET['hapus']]);
}
?>

<h2>Keranjang</h2>
<a href="../public/index.php">← Kembali Belanja</a>
<hr>

<table border="1" cellpadding="10">
<tr>
    <th>Produk</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Aksi</th>
</tr>

<?php
$total = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {

        $data = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
        $p = mysqli_fetch_assoc($data);

        $subtotal = $p['harga'] * $qty;
        $total += $subtotal;
?>

<tr>
    <td><?= $p['nama_produk'] ?></td>
    <td>Rp <?= number_format($p['harga']) ?></td>
    <td><?= $qty ?></td>
    <td>Rp <?= number_format($subtotal) ?></td>
    <td>
        <a href="?hapus=<?= $id ?>">Hapus</a>
    </td>
</tr>

<?php } } else { ?>
<tr><td colspan="5">Keranjang kosong</td></tr>
<?php } ?>

</table>

<h3>Total: Rp <?= number_format($total) ?></h3>

<?php if (!empty($_SESSION['cart'])) { ?>
<a href="checkout.php">Checkout</a>
<?php } ?>