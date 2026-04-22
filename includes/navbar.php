<?php
require_once __DIR__ . '/functions.php';
?>

<nav style="padding:10px; background:#222; color:white;">
    <a href="/vape_store/public/index.php" style="color:white; margin-right:10px;">Home</a>

    <?php if (isLoggedIn()): ?>
        
        <?php if (isAdmin()): ?>
            <a href="/vape_store/admin/dashboard.php" style="color:white;">Admin</a>
        <?php endif; ?>

        <?php if (isCustomer()): ?>
            <a href="/vape_store/customer/cart.php" style="color:white;">Cart</a>
        <?php endif; ?>

        <a href="/vape_store/public/logout.php" style="color:red; margin-left:10px;">Logout</a>

    <?php else: ?>
        <a href="/vape_store/public/login.php" style="color:white;">Login</a>
        <a href="/vape_store/public/register.php" style="color:white; margin-left:10px;">Register</a>
    <?php endif; ?>
</nav>