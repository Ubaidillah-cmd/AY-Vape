<?php
session_start();
include "../config/db.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
$data = mysqli_fetch_assoc($query);

if ($data) {
    $_SESSION['login'] = true;
    $_SESSION['admin'] = $data['username'];
    header("Location: ../admin/dashboard.php");
} else {
    echo "Login gagal!";
}