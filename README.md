# 🛒 AY Vape — Premium Vape Store

Aplikasi web toko vape berbasis PHP native dengan fitur manajemen produk, transaksi, live chat, dan notifikasi Telegram.

---

## 📋 Deskripsi

**AY Vape** adalah sistem e-commerce sederhana yang dirancang khusus untuk toko vape. Dibangun menggunakan PHP murni (tanpa framework), MySQL sebagai database, dan dilengkapi integrasi bot Telegram untuk notifikasi pesanan secara real-time.

---

## ✨ Fitur Utama

### 👤 Halaman Publik (Customer)
- Katalog produk dengan filter kategori dan pencarian
- Halaman detail produk
- Keranjang belanja (session-based)
- Proses checkout & pembayaran (upload bukti transfer)
- Live chat dengan admin
- Halaman informasi: About, FAQ, Cara Order, Pembayaran, Pengiriman, Kontak
- Langganan newsletter / subscriber

### 🔐 Panel Admin
- Login admin
- Dashboard ringkasan
- Manajemen produk (tambah, edit, hapus)
- Manajemen transaksi & verifikasi pembayaran
- Update status pesanan
- Live chat dengan customer
- Manajemen subscribers

### 🔔 Integrasi Telegram
- Notifikasi pesanan masuk ke bot Telegram
- Kirim foto bukti pembayaran via Telegram
- Tombol inline untuk akses cepat ke dashboard admin

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Detail |
|---|---|
| Backend | PHP 8.2 (Native) |
| Database | MySQL / MariaDB 10.4 |
| Frontend | HTML, CSS, JavaScript |
| Animasi | AOS (Animate On Scroll) |
| Notifikasi | Telegram Bot API |
| Server | Apache (XAMPP/Laragon) |

---

## 📁 Struktur Direktori

```
vape_store/
│
├── admin/                  # Panel admin
│   ├── login.php
│   ├── dashboard.php
│   ├── chat.php
│   ├── chat_room.php
│   ├── products.php
│   ├── create_product.php
│   ├── update_product.php
│   ├── update_status.php
│   ├── subscribers.php
│   └── transactions.php
│
├── customer/               # Halaman khusus customer (login)
│   ├── cart.php
│   ├── checkout.php
│   ├── payment.php
│   ├── payment_success.php
│   └── chat.php
│
├── public/                 # Halaman publik (tanpa login)
│   ├── index.php
│   ├── product_detail.php
│   ├── about.php
│   ├── contact.php
│   ├── faq.php
│   ├── cara_order.php
│   ├── pembayaran.php
│   └── pengiriman.php
│
├── proses/                 # Logika pemrosesan form
│   ├── proses_login.php
│   ├── proses_create.php
│   ├── proses_update.php
│   ├── proses_delete.php
│   ├── proses_checkout.php
│   ├── proses_payment.php
│   ├── upload_bukti.php
│   └── logout.php
│
├── ajax/                   # Endpoint AJAX
│   ├── get_message.php
│   ├── send_message.php
│   ├── add_to_cart.php
│   ├── subscribe.php
│   ├── check_auto_reply.php
│   ├── delete_message.php
│   ├── delete_all_message.php
│   ├── delete_room.php
│   └── delete_transaction.php
│
├── config/                 # Konfigurasi aplikasi
│   ├── db.php              # Koneksi database
│   └── telegram.php        # Konfigurasi bot Telegram
│
├── includes/               # Komponen reusable
│   ├── navbar.php
│   └── footer.php
│
├── assets/                 # Aset statis
│   ├── img/
│   └── js/
│
├── css/                    # File stylesheet
│   ├── main.css
│   └── customer.css
│
├── uploads/                # File yang diupload user
│   ├── products/           # Foto produk
│   └── bukti_pembayaran/   # Bukti transfer customer
│
└── vape_store.sql          # File database (import ini)
```

---

## 🗄️ Struktur Database

| Tabel | Keterangan |
|---|---|
| `admin` | Data akun admin |
| `produk` | Data produk |
| `kategori` | Kategori produk |
| `pesanan` | Header transaksi / pesanan |
| `detail_pesanan` | Detail item per pesanan |
| `payment` | Data pembayaran & bukti transfer |
| `chat` | Pesan live chat |
| `subscribers` | Data subscriber newsletter |

---

## ⚙️ Cara Instalasi

### 1. Persiapan
Pastikan sudah menginstall **XAMPP** atau **Laragon** dengan komponen:
- PHP >= 8.0
- MySQL / MariaDB
- Apache

### 2. Clone / Salin Project
Salin folder `vape_store` ke direktori root server:
```
# XAMPP
C:/xampp/htdocs/vape_store/

# Laragon
C:/laragon/www/vape_store/
```

### 3. Import Database
1. Buka **phpMyAdmin** → `http://localhost/phpmyadmin`
2. Buat database baru dengan nama `vape_store`
3. Klik tab **Import** → pilih file `vape_store.sql`
4. Klik **Go**

### 4. Konfigurasi Database
Edit file `config/db.php` sesuai pengaturan lokal:
```php
$host = "localhost";
$user = "root";      // username MySQL kamu
$pass = "";          // password MySQL kamu
$db   = "vape_store";
```

### 5. Konfigurasi Telegram (Opsional)
Edit file `config/telegram.php`:
```php
define('TG_BOT_TOKEN', 'ISI_TOKEN_BOT_KAMU');
define('TG_CHAT_ID',   'ISI_CHAT_ID_KAMU');
define('ADMIN_URL',    'http://localhost/vape_store/admin');
```

> **Cara mendapatkan token:** Buka Telegram → cari `@BotFather` → ketik `/newbot` → ikuti instruksi.  
> **Cara mendapatkan Chat ID:** Buka `@userinfobot` di Telegram.

### 6. Jalankan Aplikasi
Buka browser dan akses:
```
http://localhost/vape_store/public/index.php
```

---

## 🔑 Akun Default Admin

| Field | Value |
|---|---|
| Username | `admin` |
| Password | `123` |

> ⚠️ **Segera ganti password** setelah instalasi pertama kali.

URL Login Admin: `http://localhost/vape_store/admin/login.php`

---

## 📸 Halaman Aplikasi

| Halaman | URL |
|---|---|
| Beranda / Katalog | `/public/index.php` |
| Detail Produk | `/public/product_detail.php` |
| Keranjang | `/customer/cart.php` |
| Checkout | `/customer/checkout.php` |
| Pembayaran | `/customer/payment.php` |
| Live Chat | `/customer/chat.php` |
| Admin Dashboard | `/admin/dashboard.php` |

---

## 🚀 Catatan Pengembangan

- Pastikan folder `uploads/products/` dan `uploads/bukti_pembayaran/` memiliki permission **write** (`chmod 755` atau `777` di Linux).
- Aplikasi menggunakan **session PHP** untuk manajemen keranjang belanja.
- Live chat menggunakan **polling AJAX** (tanpa WebSocket).
- Timezone default diset ke `Asia/Jakarta`.

---

## 📄 Lisensi

Project ini dibuat untuk keperluan belajar dan pengembangan. Bebas digunakan dan dimodifikasi.
