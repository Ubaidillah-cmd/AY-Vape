-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jun 2026 pada 05.27
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vape_store`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `sender` enum('admin','customer') NOT NULL,
  `nama_pengirim` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `room_id` int(11) DEFAULT NULL,
  `status` enum('sent','read') DEFAULT 'sent',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `chat`
--

INSERT INTO `chat` (`id`, `sender`, `nama_pengirim`, `message`, `latitude`, `longitude`, `created_at`, `room_id`, `status`, `deleted`, `file`) VALUES
(1, 'admin', NULL, 'halo', '', '', '2026-04-18 06:01:18', NULL, 'sent', 0, NULL),
(2, 'admin', NULL, '', '-8.186509506566605', '113.73149184357412', '2026-04-18 06:01:49', NULL, 'sent', 0, NULL),
(3, 'customer', '', 'halo min', '', '', '2026-04-18 08:26:17', 9, 'read', 0, NULL),
(4, 'customer', '', 'iya halo ada yg bisa saya bantu', '', '', '2026-04-18 08:29:51', 9, 'read', 0, NULL),
(5, 'admin', 'Admin', 'iya halo', '', '', '2026-04-18 08:30:19', 9, 'sent', 0, NULL),
(6, 'customer', '', 'mas', '', '', '2026-04-18 08:39:36', 10, 'read', 0, NULL),
(7, 'admin', 'Admin', 'iya mas', '', '', '2026-04-18 08:39:57', 10, 'sent', 0, NULL),
(8, 'customer', '', 'sharlock mas', '', '', '2026-04-18 08:40:15', 10, 'read', 0, NULL),
(9, 'admin', 'Admin', '', '-8.186535310871443', '113.73142181608921', '2026-04-18 08:40:20', 10, 'sent', 0, NULL),
(10, 'customer', '', 'mas', '', '', '2026-04-22 02:29:38', 11, 'read', 0, NULL),
(11, 'admin', 'Admin', 'iya', '', '', '2026-04-22 02:30:11', 11, 'sent', 0, NULL),
(12, 'admin', 'Admin', '', '-8.174072', '113.717041', '2026-04-22 02:30:27', 11, 'sent', 0, NULL),
(13, 'admin', 'Admin', '', '-8.174072', '113.717041', '2026-04-22 02:30:27', 11, 'sent', 0, NULL),
(14, 'customer', '', 'mas apakah bisa di bungkus dengan rapi', '', '', '2026-04-22 14:22:54', 12, 'read', 0, NULL),
(15, 'admin', 'Admin', 'bisa mas', '', '', '2026-04-22 14:23:13', 12, 'read', 0, NULL),
(16, 'customer', '', 'mantap mas', '', '', '2026-04-22 14:32:24', 12, 'read', 0, NULL),
(17, 'admin', 'Admin', 'ok mas', '', '', '2026-04-22 14:52:54', 12, 'read', 0, NULL),
(18, 'admin', 'Admin', 'mas', '', '', '2026-04-22 15:04:51', 12, 'read', 0, ''),
(19, 'customer', '', 'uy', '', '', '2026-04-22 15:05:35', 12, 'read', 0, ''),
(20, 'customer', '', 'mas', '', '', '2026-04-22 23:40:54', 13, 'read', 0, ''),
(21, 'admin', 'Admin', 'iya', '', '', '2026-04-22 23:41:09', 13, 'read', 0, ''),
(22, 'customer', '', 'mas', '', '', '2026-04-23 08:17:43', 14, 'read', 0, ''),
(23, 'admin', 'Admin', 'iya', '', '', '2026-04-23 08:18:33', 14, 'read', 0, ''),
(24, 'customer', '', 'mas', '', '', '2026-04-24 00:38:23', 15, 'read', 0, ''),
(25, 'admin', 'Admin', 'oke', '', '', '2026-04-24 00:38:37', 15, 'read', 0, ''),
(26, 'customer', '', 'mas', '', '', '2026-04-24 05:26:38', 16, 'read', 0, ''),
(27, 'admin', 'Admin', 'yuiiya', '', '', '2026-04-24 05:27:15', 16, 'read', 0, ''),
(28, 'customer', '', 'knepp', '', '', '2026-04-24 05:27:26', 16, 'read', 0, ''),
(29, 'customer', '', 'mas', '', '', '2026-04-24 05:51:41', 17, 'read', 0, ''),
(30, 'admin', 'Admin', 'uiya', '', '', '2026-04-24 05:52:19', 17, 'read', 0, ''),
(31, 'customer', '', 'mas', '', '', '2026-04-29 03:38:21', 18, 'read', 0, ''),
(32, 'admin', 'Admin', '', '-8.175235728417963', '113.71725893702893', '2026-04-29 03:38:41', 18, 'read', 0, ''),
(34, 'customer', 'Customer', 'halo', NULL, NULL, '2026-05-07 06:43:12', 23, 'read', 0, ''),
(35, 'admin', 'Admin', 'halo', NULL, NULL, '2026-05-07 11:12:22', 23, 'sent', 0, ''),
(36, 'admin', 'Admin', '', '-8.186470349541', '113.73157040428', '2026-05-12 13:32:29', 23, 'sent', 0, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga_satuan` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `id_pesanan`, `id_produk`, `jumlah`, `harga_satuan`) VALUES
(13, 13, 7, 1, NULL),
(14, 14, 8, 1, NULL),
(15, 15, 7, 2, NULL),
(16, 16, 7, 1, NULL),
(17, 17, 7, 1, NULL),
(18, 18, 8, 1, NULL),
(19, 19, 7, 1, NULL),
(20, 22, 16, 1, 180000),
(21, 23, 13, 1, 110000),
(22, 23, 19, 1, 95000),
(23, 23, 20, 1, 950000),
(24, 24, 19, 2, 95000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'Vape'),
(2, 'Mod'),
(3, 'Pod'),
(4, 'Aio'),
(5, 'Liquid'),
(6, 'Baterai'),
(7, 'Kapas'),
(8, 'Coil'),
(9, 'CT'),
(10, 'Aksesoris');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) DEFAULT NULL,
  `metode` varchar(50) DEFAULT NULL,
  `status` enum('pending','dibayar','ditolak') DEFAULT 'pending',
  `bukti` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payment`
--

INSERT INTO `payment` (`id`, `id_pesanan`, `metode`, `status`, `bukti`, `created_at`) VALUES
(1, 2, 'transfer', 'dibayar', '', '2026-04-18 06:22:25'),
(2, 6, 'transfer', 'dibayar', '', '2026-04-18 07:24:37'),
(3, 7, 'transfer', 'dibayar', '', '2026-04-18 07:24:58'),
(4, 8, 'transfer', 'ditolak', 'Screenshot (28).png', '2026-04-18 08:00:29'),
(5, 9, 'cod', 'dibayar', '', '2026-04-18 08:22:24'),
(6, 9, 'cod', 'dibayar', '', '2026-04-18 08:23:33'),
(7, 9, 'cod', 'dibayar', '', '2026-04-18 08:26:04'),
(8, 10, 'transfer', 'dibayar', '1776501557_Screenshot (26).png', '2026-04-18 08:39:17'),
(9, 11, 'transfer', 'dibayar', '1776824967_OXVA Xlim GO – Simple, Stylish, Powerful.jpg', '2026-04-22 02:29:27'),
(10, 12, 'cod', 'dibayar', '', '2026-04-22 14:22:25'),
(11, 13, 'cod', 'dibayar', '', '2026-04-22 23:40:43'),
(12, 14, 'transfer', 'dibayar', '1776932149_Screenshot (60).png', '2026-04-23 08:15:49'),
(13, 15, 'transfer', 'dibayar', '1776991090_Screenshot (25).png', '2026-04-24 00:38:10'),
(14, 16, 'transfer', 'pending', '1777007841_Screenshot (27).png', '2026-04-24 05:17:21'),
(15, 17, 'cod', 'pending', '', '2026-04-24 05:51:23'),
(16, 18, 'transfer', 'ditolak', '1777433874_download.jpg', '2026-04-29 03:37:54'),
(17, 19, 'cod', 'pending', '', '2026-05-05 04:49:27'),
(18, 22, 'cod', 'dibayar', '', '2026-05-06 23:11:50'),
(19, 23, 'cod', 'dibayar', '', '2026-05-07 02:34:38'),
(20, 24, 'cod', 'pending', '', '2026-05-20 03:40:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `nama_pembeli` varchar(100) NOT NULL,
  `total` int(11) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','diproses','selesai') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id`, `nama_pembeli`, `total`, `alamat`, `no_wa`, `catatan`, `tanggal`, `status`, `created_at`) VALUES
(1, 'ubaidillah', 250000, NULL, NULL, NULL, '2026-04-18 06:17:40', 'pending', '2026-04-22 23:11:29'),
(2, 'ubaidillah', 250000, NULL, NULL, NULL, '2026-04-18 06:22:05', 'pending', '2026-04-22 23:11:29'),
(3, 'ubaidillah', 250000, NULL, NULL, NULL, '2026-04-18 06:39:41', 'pending', '2026-04-22 23:11:29'),
(4, 'ubaidillah', 250000, NULL, NULL, NULL, '2026-04-18 06:57:48', 'pending', '2026-04-22 23:11:29'),
(5, 'ubaidillah', 250000, NULL, NULL, NULL, '2026-04-18 07:04:00', 'pending', '2026-04-22 23:11:29'),
(6, 'ubaidillah', 250000, NULL, NULL, NULL, '2026-04-18 07:16:22', 'pending', '2026-04-22 23:11:29'),
(7, 'ubaidillah', 250000, NULL, NULL, NULL, '2026-04-18 07:24:54', 'pending', '2026-04-22 23:11:29'),
(8, 'ubaidillah', 250000, NULL, NULL, NULL, '2026-04-18 07:43:58', 'pending', '2026-04-22 23:11:29'),
(9, 'aku', 250000, NULL, NULL, NULL, '2026-04-18 08:22:12', 'pending', '2026-04-22 23:11:29'),
(10, 'putra', 250000, NULL, NULL, NULL, '2026-04-18 08:36:40', 'pending', '2026-04-22 23:11:29'),
(11, 'ayu', 250000, NULL, NULL, NULL, '2026-04-22 02:28:56', 'pending', '2026-04-22 23:11:29'),
(12, 'abdur', 250000, NULL, NULL, NULL, '2026-04-22 14:22:20', 'pending', '2026-04-22 23:11:29'),
(13, 'dillah', 150000, NULL, NULL, NULL, '2026-04-22 23:40:37', 'pending', '2026-04-22 23:40:37'),
(14, 'faruq', 120000, NULL, NULL, NULL, '2026-04-23 08:13:45', 'pending', '2026-04-23 08:13:45'),
(15, 'tasya', 300000, NULL, NULL, NULL, '2026-04-24 00:37:49', 'pending', '2026-04-24 00:37:49'),
(16, 'rohman', 150000, NULL, NULL, NULL, '2026-04-24 05:13:22', 'pending', '2026-04-24 05:13:22'),
(17, 'farhan', 150000, NULL, NULL, NULL, '2026-04-24 05:51:07', 'pending', '2026-04-24 05:51:07'),
(18, 'dillah', 120000, NULL, NULL, NULL, '2026-04-29 03:37:08', 'pending', '2026-04-29 03:37:08'),
(19, 'arifan', 150000, NULL, NULL, NULL, '2026-05-05 04:49:05', 'pending', '2026-05-05 04:49:05'),
(20, 'dody', 180000, 'tanggul', '081455090805', '', '2026-05-06 23:07:26', 'pending', '2026-05-06 23:07:26'),
(21, 'dody', 180000, 'tanggul', '081455090805', '', '2026-05-06 23:07:29', 'pending', '2026-05-06 23:07:29'),
(22, 'dody', 180000, 'tanggul', '081455090805', '', '2026-05-06 23:11:24', 'pending', '2026-05-06 23:11:24'),
(23, 'sholeh', 1155000, 'jln karimata', '083427689243', '', '2026-05-07 02:34:27', 'pending', '2026-05-07 02:34:27'),
(24, 'ega', 190000, 'jl karimata sumbersari\r\n', '0827346838', 'pakcingnya di rapikan mas untuk hadiah', '2026-05-20 03:40:40', 'pending', '2026-05-20 03:40:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `harga_beli` bigint(20) NOT NULL DEFAULT 0,
  `stok` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deskripsi` text DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `harga`, `harga_beli`, `stok`, `gambar`, `created_at`, `deskripsi`, `id_kategori`) VALUES
(7, 'Vape Starter Kit', 150000, 130000, 13, 'Vape Starter Kit.jpg', '2026-04-22 22:35:29', 'Perangkat vape lengkap cocok untuk pemula hingga pengguna berpengalaman. Desain modern, praktis, dan mudah digunakan.', 1),
(8, 'Smok RPM40 Open Pod Kit', 120000, 100000, 10, 'Smok RPM40 Open Pod Kit.jpg', '2026-04-22 22:48:54', 'Pod system ringan dan simpel, cocok untuk penggunaan sehari-hari. Hemat liquid dan praktis dibawa.', 3),
(9, 'Vape Mod Vape Mod', 300000, 250000, 6, 'Vape Mod Vape Mod.jpg', '2026-04-22 22:50:26', 'Mod vape dengan performa tinggi untuk pengalaman vaping maksimal. Dilengkapi fitur pengaturan watt dan keamanan.', 2),
(10, 'Vape Starter Kits & Vape Kits ', 200000, 170000, 3, 'Vape Starter Kits & Vape Kits.jpg', '2026-04-22 22:53:06', 'Perangkat vape all-in-one yang menggabungkan kemudahan pod dan performa mod. Praktis dan efisien.', 4),
(11, 'Voopoo Drag Nano 2', 185000, 150000, 5, 'Voopoo Drag Nano 2.jpg', '2026-04-28 12:43:13', 'Pod mini dengan performa stabil, baterai tahan lama, dan mudah dibawa ke mana saja.', 3),
(12, 'Silicone Case Pod', 20000, 15000, 16, 'aksesoris pod.jpg', '2026-05-05 05:05:10', 'Pelindung pod berbahan silikon agar tidak mudah lecet.', 10),
(13, 'Molicel P26A Battery', 110000, 90000, 9, 'Molicel P26A Battery.jpg', '2026-05-05 05:07:48', 'Baterai high drain cocok untuk mod dual battery.', 6),
(14, 'Smok RPM Coil Mesh 0.4', 30000, 23000, 18, 'Smok RPM Coil Mesh 0.4.jpg', '2026-05-05 05:10:03', 'Coil mesh untuk Smok RPM dengan uap tebal dan rasa maksimal.', 8),
(15, 'RTA Zeus X Mesh', 220000, 170000, 4, 'RTA Zeus X Mesh.jpg', '2026-05-05 05:12:55', 'Tank rebuildable dengan sistem anti bocor dan produksi uap besar.', 9),
(16, 'RDA Dead Rabbit V3', 180000, 155000, 5, 'RDA Dead Rabbit V3.jpg', '2026-05-05 05:14:26', 'Atomizer RDA populer dengan airflow maksimal dan rasa mantap.', 9),
(17, 'Holy Fiber Premium Wicking Cotton', 60000, 40000, 10, 'kapas holy fiber.webp', '2026-05-05 05:20:11', 'Kapas premium untuk vaping dengan daya serap tinggi, tahan panas, dan menghasilkan rasa lebih bersih. Cocok untuk RDA, RTA, dan RDTA.', 7),
(18, 'Cotton Bacon Prime', 45000, 40000, 15, 'Cotton Bacon Prime.jpg', '2026-05-05 05:21:57', 'Kapas premium untuk coil RDA/RTA, daya serap tinggi dan tahan panas.', 7),
(19, 'Oat Drip Original', 95000, 80000, 9, 'liquid oat drip.jpg', '2026-05-05 05:34:43', 'Liquid creamy dengan rasa oat (sereal) yang lembut dan sedikit manis, cocok untuk penggunaan harian.', 5),
(20, 'Oxva Velocity LE', 950000, 880000, 4, 'Oxva Velocity LE.webp', '2026-05-05 05:46:46', 'Perangkat vape premium dengan desain mewah dan performa tinggi.', 1),
(21, 'Lanyard Vape Premium', 25000, 20000, 15, 'Lanyard Vape Premium.jpg', '2026-05-05 06:45:50', 'Tali gantungan vape dengan desain stylish, memudahkan dibawa sehari-hari.', 10),
(22, 'Vape Tool Kit Mini', 50000, 43000, 10, 'Vape Tool Kit Mini.jpg', '2026-05-05 07:29:24', 'Set alat lengkap untuk rebuild coil seperti obeng, gunting, dan pinset.', 10),
(23, 'Dual 26650 Case', 25000, 20000, 12, 'Dual 26650 Case.jpg', '2026-05-05 07:31:33', 'Tempat penyimpanan baterai 26650 kapasitas 2 slot, melindungi baterai dari benturan dan konsleting saat dibawa.', 10),
(24, 'Dotmod DotAIO Mini Kit', 890000, 800000, 3, 'Dotmod DotAIO Mini Kit.webp', '2026-05-05 07:35:04', 'Perangkat AIO premium dengan desain compact, performa stabil, dan rasa maksimal. Cocok untuk pengguna yang ingin praktis tanpa ribet.', 4),
(25, 'Vandy Vape Pulse AIO Kit', 750000, 700000, 4, 'Vandy Vape Pulse AIO Kit.jpg', '2026-05-05 07:38:19', 'AIO dengan sistem squonk dan desain modern, memberikan pengalaman vaping unik dan powerful.', 4),
(26, 'Nasty Juice Mango Ice', 90000, 70000, 10, 'liquid Nasty Juice Mango Ice.webp', '2026-05-05 08:02:05', 'Rasa mangga segar dengan sensasi dingin yang menyegarkan.', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `halaman` varchar(255) DEFAULT NULL COMMENT 'Halaman asal subscribe',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `ip_address`, `user_agent`, `halaman`, `created_at`) VALUES
(1, 'ubaidillah101106@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'index.php', '2026-05-12 10:00:13');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
