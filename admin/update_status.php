<?php
include "../config/db.php";

$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query($conn, "UPDATE payment SET status='$status' WHERE id=$id");

header("Location: verify_payment.php");

$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query($conn, "UPDATE payment SET status='$status' WHERE id_pesanan=$id");

header("Location: transactions.php");