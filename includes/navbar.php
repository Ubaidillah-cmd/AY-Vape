<?php
// includes/navbar.php
// Usage: include '../includes/navbar.php';
// Requires: $jumlah (cart count), $active_page (string: 'home','about','contact','cart')

$jumlah      = isset($jumlah)      ? $jumlah      : 0;
$active_page = isset($active_page) ? $active_page : '';
?>
<nav class="navbar">
  <div class="navbar-inner">

    <!-- BRAND -->
    <a href="../public/index.php" class="navbar-brand">
      <div class="navbar-brand-icon">💨</div>
      <div class="navbar-brand-text">
        <span class="navbar-brand-name">AY VAPE</span>
        <span class="navbar-brand-tag">Premium Store</span>
      </div>
    </a>

    <!-- LINKS (desktop) -->
    <div class="navbar-links">
      <a href="../public/index.php"   class="nav-link <?= $active_page==='home'    ? 'active' : '' ?>">Home</a>
      <a href="../public/about.php"   class="nav-link <?= $active_page==='about'   ? 'active' : '' ?>">About</a>
      <a href="../public/contact.php" class="nav-link <?= $active_page==='contact' ? 'active' : '' ?>">Kontak</a>
    </div>

    <!-- RIGHT -->
    <div class="navbar-right">

      <!-- SEARCH (desktop only) -->
      <form method="GET" action="../public/index.php" class="navbar-search">
        <input type="text" name="search"
               placeholder="Cari produk..."
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <span class="navbar-search-icon">⌕</span>
      </form>

      <!-- CART -->
      <a href="../customer/cart.php" class="cart-btn">
        <span>🛒</span>
        <span>Keranjang</span>
        <?php if ($jumlah > 0): ?>
          <span class="cart-count"><?= $jumlah ?></span>
        <?php endif; ?>
      </a>

      <!-- HAMBURGER -->
      <button class="hamburger" onclick="toggleMobileMenu()" id="hamburger">☰</button>
    </div>

  </div>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-menu" id="mobileMenu">
  <a href="../public/index.php"   class="nav-link <?= $active_page==='home'    ? 'active' : '' ?>">🏠 Home</a>
  <a href="../public/about.php"   class="nav-link <?= $active_page==='about'   ? 'active' : '' ?>">💡 About</a>
  <a href="../public/contact.php" class="nav-link <?= $active_page==='contact' ? 'active' : '' ?>">📞 Kontak</a>
  <div class="mobile-menu-bottom">
    <form method="GET" action="../public/index.php" style="display:flex;gap:8px;">
      <input type="text" name="search" class="form-control" style="flex:1;"
             placeholder="Cari produk..."
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
      <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    </form>
  </div>
</div>

<script>
function toggleMobileMenu() {
  const menu = document.getElementById('mobileMenu');
  const btn  = document.getElementById('hamburger');
  const open = menu.classList.toggle('open');
  btn.textContent = open ? '✕' : '☰';
}

// Close on outside click
document.addEventListener('click', function(e) {
  const menu = document.getElementById('mobileMenu');
  const btn  = document.getElementById('hamburger');
  if (!menu.contains(e.target) && !btn.contains(e.target)) {
    menu.classList.remove('open');
    btn.textContent = '☰';
  }
});
</script>