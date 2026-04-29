<!DOCTYPE html>
<html>
<head>
<title>Kontak Kami</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #0f172a;
    color: white;
}

/* CONTAINER */
.container {
    padding: 50px 20px;
    max-width: 1100px;
    margin: auto;
    animation: fadeUp 0.8s ease;
}

/* ANIMATION */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* TITLE */
.title {
    text-align: center;
    margin-bottom: 40px;
}

.title h2 {
    font-size: 30px;
}

.title p {
    color: #94a3b8;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

/* CARD PREMIUM */
.card {
    background: rgba(30,41,59,0.7);
    backdrop-filter: blur(12px);
    padding: 25px;
    border-radius: 16px;
    transition: 0.3s;
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

.card:hover {
    transform: translateY(-6px);
}

/* INFO */
.info {
    margin-bottom: 15px;
}

.info strong {
    display: block;
    margin-bottom: 5px;
    color: #60a5fa;
}

/* BUTTON */
.btn {
    display: inline-block;
    padding: 12px 16px;
    background: linear-gradient(45deg,#2563eb,#60a5fa);
    border-radius: 8px;
    color: white;
    text-decoration: none;
    margin-top: 10px;
    transition: 0.3s;
    position: relative;
    overflow: hidden;
}

.btn:hover {
    transform: scale(1.05);
}

/* SHINE EFFECT */
.btn::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(120deg,transparent,rgba(255,255,255,0.4),transparent);
    top: 0;
    left: -100%;
    transition: 0.5s;
}

.btn:hover::before {
    left: 100%;
}

/* FORM */
input, textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 12px;
    border-radius: 8px;
    border: none;
    outline: none;
    background: #020617;
    color: white;
}

/* MAP */
.map {
    margin-top: 20px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
}

/* FLOAT WA PREMIUM */
.wa-float {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #16a34a;
    padding: 14px 18px;
    border-radius: 50px;
    color: white;
    text-decoration: none;
    font-size: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    transition: 0.3s;
    animation: pulse 2s infinite;
}

.wa-float:hover {
    transform: scale(1.1);
}

/* PULSE EFFECT */
@keyframes pulse {
    0% {box-shadow: 0 0 0 0 rgba(22,163,74,0.7);}
    70% {box-shadow: 0 0 0 15px rgba(22,163,74,0);}
    100% {box-shadow: 0 0 0 0 rgba(22,163,74,0);}
}

/* RESPONSIVE */
@media(max-width:768px){
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<div class="container">

<div class="title">
    <h2>📞 Hubungi Kami</h2>
    <p>Kami siap membantu kamu kapan saja 🚀</p>
</div>

<div class="grid">

    <!-- INFO TOKO -->
    <div class="card">

        <h3>🏬 Vape Store</h3>

        <div class="info">
            <strong>📍 Alamat</strong>
            Lokasi sesuai Google Maps
        </div>

        <div class="info">
            <strong>📞 Telepon</strong>
            0823-3340-8651
        </div>

        <div class="info">
            <strong>📧 Email</strong>
            rahmanadur7511@gmail.com
        </div>

        <div class="info">
            <strong>🕒 Jam Operasional</strong>
            09:00 - 22:00 WIB
        </div>

        <a href="https://wa.me/6282333408651" class="btn">💬 Chat WhatsApp</a>

        <!-- 🔥 MAP (SUDAH DIGANTI PUNYAMU) -->
        <div class="map">
            <iframe 
                src="https://maps.google.com/maps?q=Masjid%20Jami%20Al-Muttaqiin%20Jember&t=&z=15&ie=UTF8&iwloc=&output=embed"
                width="100%" 
                height="220" 
                style="border:0;">
            </iframe>
        </div>


    </div>

    <!-- FORM -->
    <div class="card">

        <h3>✉️ Kirim Pesan</h3>

        <form>
            <input type="text" placeholder="Nama" required>
            <input type="email" placeholder="Email" required>
            <textarea rows="5" placeholder="Pesan..."></textarea>

            <button class="btn">Kirim Pesan</button>
        </form>

    </div>

</div>

</div>

<!-- FLOAT WA -->
<a href="https://wa.me/6282333408651" class="wa-float">
    💬 Chat WA
</a>

<?php include '../includes/footer.php'; ?>

</body>
</html>
