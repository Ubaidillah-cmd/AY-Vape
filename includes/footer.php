<style>
.footer {
    background: #020617;
    color: #cbd5f5;
    padding: 50px 20px 20px;
    margin-top: 50px;
    border-top: 1px solid #1e293b;
}

.footer-container {
    max-width: 1200px;
    margin: auto;
}

/* GRID */
.footer-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
}

/* BRAND */
.footer-brand h2 {
    color: #60a5fa;
    margin-bottom: 10px;
}

.footer-brand p {
    font-size: 14px;
    line-height: 1.6;
    opacity: 0.8;
}

/* TITLE */
.footer h3 {
    margin-bottom: 12px;
    font-size: 16px;
    color: white;
}

/* LINKS */
.footer a {
    display: block;
    text-decoration: none;
    color: #cbd5f5;
    font-size: 14px;
    margin-bottom: 6px;
    transition: 0.3s;
}

.footer a:hover {
    color: #60a5fa;
    transform: translateX(5px);
}

/* SOSMED */
.socials {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.socials a {
    width: 40px;
    height: 40px;
    background: #1e293b;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 18px;
    transition: 0.3s;
}

.socials a:hover {
    background: #2563eb;
    transform: scale(1.1);
}

/* NEWSLETTER */
.newsletter input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: none;
    margin-bottom: 10px;
}

.newsletter button {
    width: 100%;
    padding: 10px;
    background: #2563eb;
    border: none;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    transition: 0.3s;
}

.newsletter button:hover {
    background: #1d4ed8;
}

/* BOTTOM */
.footer-bottom {
    text-align: center;
    margin-top: 30px;
    padding-top: 15px;
    border-top: 1px solid #1e293b;
    font-size: 13px;
    opacity: 0.7;
}

/* RESPONSIVE */
@media(max-width: 1024px){
    .footer-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 600px){
    .footer-grid {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .socials {
        justify-content: center;
    }
}
</style>

<footer class="footer">
<div class="footer-container">

    <div class="footer-grid">

        <!-- BRAND -->
        <div class="footer-brand">
            <h2>AY Vape</h2>
            <p>
                Toko vape terpercaya dengan produk original,
                harga bersahabat, dan kualitas terbaik untuk daily use.
            </p>
        </div>

        <!-- MENU -->
        <div>
            <h3>Menu</h3>
            <a href="index.php">Home</a>
            <a href="products.php">Produk</a>
            <a href="about.php">Tentang Kami</a>
            <a href="contact.php">Kontak</a>
        </div>

        <!-- BANTUAN -->
        <div>
            <h3>Bantuan</h3>
            <a href="#">Cara Order</a>
            <a href="#">Pengiriman</a>
            <a href="#">Pembayaran</a>
            <a href="#">FAQ</a>
        </div>

        <!-- NEWSLETTER -->
        <div class="newsletter">
            <h3>Newsletter</h3>
            <input type="email" placeholder="Email kamu...">
            <button>Subscribe</button>

            <div class="socials">
                <a href="#">📷</a>
                <a href="#">🎵</a>
                <a href="#">📘</a>
                <a href="#">💬</a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        © <?= date("Y") ?> AY Vape — All Rights Reserved.
    </div>

</div>
</footer>
