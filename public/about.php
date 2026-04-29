<!DOCTYPE html>
<html>
<head>
<title>Tentang Kami - AY Vape</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- AOS ANIMATION -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #0f172a;
    color: white;
}

/* 🔥 HEADER */
.header {
    background: rgba(2,6,23,0.8);
    backdrop-filter: blur(10px);
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 999;
}

.logo {
    font-weight: bold;
    font-size: 20px;
}

.nav a {
    margin-left: 15px;
    color: white;
    text-decoration: none;
    opacity: 0.8;
    transition: 0.3s;
}

.nav a:hover {
    opacity: 1;
    color: #60a5fa;
}

/* 🔥 HERO */
.hero {
    text-align: center;
    padding: 80px 20px;
    background: radial-gradient(circle at top, #1e293b, #020617);
}

.hero h1 {
    font-size: 40px;
    background: linear-gradient(45deg,#60a5fa,#2563eb);
    -webkit-background-clip: text;
    color: transparent;
}

.hero p {
    max-width: 600px;
    margin: auto;
    color: #94a3b8;
}

/* 🔥 CONTAINER */
.container {
    padding: 50px 20px;
    max-width: 1100px;
    margin: auto;
}

/* 🔥 GRID */
.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

/* 🔥 GLASS CARD */
.card {
    background: rgba(30,41,59,0.6);
    backdrop-filter: blur(12px);
    border-radius: 15px;
    padding: 25px;
    border: 1px solid rgba(255,255,255,0.05);
    transition: 0.4s;
}

.card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
}

/* ICON */
.icon {
    font-size: 30px;
    margin-bottom: 10px;
}

/* 🔥 STATS */
.stats {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 20px;
    margin-top: 40px;
}

.stat-box {
    background: rgba(30,41,59,0.7);
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    transition: 0.3s;
}

.stat-box:hover {
    transform: scale(1.05);
    background: #1e293b;
}

.stat-box h2 {
    color: #60a5fa;
}

/* 🔥 TEAM */
.team {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 20px;
}

.team-card {
    background: rgba(30,41,59,0.6);
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    transition: 0.3s;
}

.team-card:hover {
    transform: translateY(-6px);
}

.team-card img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    margin-bottom: 10px;
    border: 3px solid #2563eb;
}

/* 🔥 CTA */
.cta {
    text-align: center;
    margin-top: 50px;
}

.btn {
    background: linear-gradient(45deg,#2563eb,#60a5fa);
    padding: 12px 20px;
    border-radius: 10px;
    color: white;
    text-decoration: none;
    transition: 0.3s;
}

.btn:hover {
    transform: scale(1.05);
}

/* 🔥 RESPONSIVE */
@media(max-width:768px){

    .grid {
        grid-template-columns: 1fr;
    }

    .stats {
        grid-template-columns: 1fr 1fr;
    }

    .team {
        grid-template-columns: 1fr;
    }

    .hero h1 {
        font-size: 28px;
    }
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <div class="logo">AY Vape</div>
    <div class="nav">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="kontak.php">Kontak</a>
    </div>
</div>

<!-- HERO -->
<div class="hero" data-aos="fade-down">
    <h1>Tentang Kami</h1>
    <p>
        Kami menghadirkan pengalaman belanja vape modern, cepat, dan terpercaya.
    </p>
</div>

<!-- ABOUT -->
<div class="container">

<div class="grid">

    <div class="card" data-aos="fade-right">
        <div class="icon">🏪</div>
        <h3>Siapa Kami</h3>
        <p>
            AY Vape adalah toko vape terpercaya dengan produk original dan berkualitas tinggi.
        </p>
    </div>

    <div class="card" data-aos="fade-left">
        <div class="icon">🚀</div>
        <h3>Misi Kami</h3>
        <p>
            Memberikan layanan terbaik dengan harga kompetitif dan pengiriman cepat.
        </p>
    </div>

</div>

<!-- STATS -->
<div class="stats">

    <div class="stat-box" data-aos="zoom-in">
        <h2>500+</h2>
        <p>Produk</p>
    </div>

    <div class="stat-box" data-aos="zoom-in" data-aos-delay="100">
        <h2>1K+</h2>
        <p>Pelanggan</p>
    </div>

    <div class="stat-box" data-aos="zoom-in" data-aos-delay="200">
        <h2>4.9⭐</h2>
        <p>Rating</p>
    </div>

    <div class="stat-box" data-aos="zoom-in" data-aos-delay="300">
        <h2>24/7</h2>
        <p>Support</p>
    </div>

</div>

<!-- TEAM -->
<div class="container">
<h2 data-aos="fade-up">Tim Kami</h2>

<div class="team">

    <div class="team-card" data-aos="flip-left">
        <img src="https://i.pravatar.cc/100?img=1">
        <h4>Admin</h4>
        <p>Support</p>
    </div>

    <div class="team-card" data-aos="flip-up">
        <img src="https://i.pravatar.cc/100?img=2">
        <h4>Owner</h4>
        <p>Founder</p>
    </div>

    <div class="team-card" data-aos="flip-right">
        <img src="https://i.pravatar.cc/100?img=3">
        <h4>Staff</h4>
        <p>Warehouse</p>
    </div>

</div>

<!-- CTA -->
<div class="cta" data-aos="fade-up">
    <h3>Siap Belanja?</h3>
    <p>Temukan produk terbaik sekarang</p>
    <a href="index.php" class="btn">Lihat Produk</a>
</div>

</div>

<!-- AOS SCRIPT -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({
    duration: 800,
    once: true
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>