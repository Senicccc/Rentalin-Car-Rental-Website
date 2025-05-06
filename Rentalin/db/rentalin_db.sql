-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Bulan Mei 2025 pada 15.38
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
-- Database: `rentalin_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `mobil`
--

CREATE TABLE `mobil` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `harga_per_hari` int(11) DEFAULT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mobil`
--

INSERT INTO `mobil` (`id`, `nama`, `harga_per_hari`, `kuantitas`, `foto`) VALUES
(1, 'Honda Brio', 250000, 4, 'honda-brio.png'),
(2, 'Toyota Avanza', 400000, 6, 'toyota-avanza.png'),
(3, 'Mitsubishi Xpander', 800000, 4, 'mitsubishi-xpander.png'),
(4, 'Suzuki Ertiga', 370000, 4, 'suzuki-ertiga.png'),
(5, 'Daihatsu Xenia', 360000, 4, 'daihatsu-xenia.png'),
(6, 'Toyota Rush', 420000, 4, 'toyota-rush.png'),
(7, 'Honda Jazz', 450000, 0, 'honda-jazz.png'),
(8, 'Nissan Livina', 390000, 1, 'nissan-livina.png'),
(9, 'Daihatsu Terios', 400000, 4, 'daihatsu-terios.png'),
(10, 'Daihatsu Sigra', 300000, 4, 'daihatsu-sigra.png'),
(11, 'Toyota Calya', 280000, 4, 'toyota-calya.png'),
(12, 'Honda Mobilio', 340000, 4, 'honda-mobilio.png'),
(13, 'Honda BRV', 410000, 6, 'honda-brv.png'),
(14, 'Toyota Innova', 550000, 4, 'toyota-innova.png'),
(15, 'Toyota Fortuner', 800000, 4, 'toyota-fortuner.png'),
(16, 'Suzuki Carry', 300000, 4, 'suzuki-carry.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mobil_id` int(11) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `mobil_id`, `tanggal_mulai`, `tanggal_selesai`, `total_harga`, `order_id`, `created_at`) VALUES
(37, 9, 8, '2025-05-06', '2025-05-08', 1170000, 'ORDER-6818afbf9155d', '2025-05-05 12:33:16'),
(38, 9, 7, '2025-05-12', '2025-05-16', 2250000, 'ORDER-6818bb5b70ea2', '2025-05-05 13:21:52'),
(39, 8, 7, '2025-05-08', '2025-05-10', 1350000, 'ORDER-6818bc23346a6', '2025-05-05 13:26:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `no_ktp` varchar(30) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `nama`, `email`, `password`, `no_hp`, `no_ktp`, `alamat`, `created_at`) VALUES
(8, 'nichol', 'nicholas', 'nichol@gmail.com', '$2y$10$.FqcTWoZH50aLvt7g4RohOPtrUpQntzjZsRC78BjrYlX0KViaKkNa', '1230987654', '0987654321765432', 'Just St.,Bandung', '2025-05-04 03:31:12'),
(9, 'mamad', 'mamad', 'mamad@gmail.com', '$2y$10$mbCkLA0U4/hCrgl2E9JBj.rIP8I05DysESaVS2/kb5i1zjOts0T9S', '234823749234', '12312381738137818137', 'West Bekasi', '2025-05-04 03:33:19');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
