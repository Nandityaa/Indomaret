-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Nov 2025 pada 07.27
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indomaret_rpl4`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_produk` smallint(4) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` int(10) NOT NULL,
  `discount` decimal(5,2) DEFAULT 0.00,
  `sub_total` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_transaksi`, `id_produk`, `qty`, `harga_satuan`, `discount`, `sub_total`) VALUES
(110, 8, 1, 9000, 0.00, 9000),
(111, 9, 2, 3000, 0.00, 6000),
(112, 23, 2, 19800, 0.00, 39600),
(113, 24, 1, 10000, 50.00, 10000),
(114, 1, 2, 10125, 25.00, 20250);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kasir`
--

CREATE TABLE `kasir` (
  `id` char(3) NOT NULL,
  `nama_kasir` varchar(50) NOT NULL,
  `status` enum('Aktif','Tidak aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kasir`
--

INSERT INTO `kasir` (`id`, `nama_kasir`, `status`) VALUES
('1', 'Ditya', 'Aktif'),
('3', 'Gia', 'Aktif'),
('4', 'Harta', 'Aktif'),
('5', 'Kore', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` smallint(4) NOT NULL,
  `id_voucher` char(6) DEFAULT NULL,
  `nama_produk` varchar(50) NOT NULL,
  `harga_satuan` int(10) NOT NULL,
  `stok` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `id_voucher`, `nama_produk`, `harga_satuan`, `stok`) VALUES
(1, 'VC0001', 'ABC ORANGE 525ML', 13500, 17),
(6, NULL, 'INDOMILK SUSU CAIR UHT VANILLA 180ML', 5300, 200),
(7, NULL, 'INDOMIE ACEH', 3000, 1000),
(8, NULL, 'SPRITE BOTOL 390ML', 9000, 32),
(9, NULL, 'PENGHAPUS JOYKO', 3000, 998),
(21, NULL, 'CHITATO SNACK POTATO CHIPS SAPI PANGGANG 68G', 18000, 40),
(23, NULL, 'KOPIKO 78C 240ML', 19800, 4),
(24, 'VC0002', 'ULTRA MILK FULL CREAM 1L', 20000, 99),
(26, NULL, 'INDOMARET INSTANT CUP NOODLE AYAM PEDAS CUP 90G', 8000, 50),
(27, 'VC0001', 'BARBER DAILY ACNE CARE & OIL CONTROL FACE WASH 100', 36500, 42);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `kode_transaksi` varchar(10) NOT NULL,
  `id_kasir` char(3) DEFAULT NULL,
  `total_harga` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `tanggal`, `kode_transaksi`, `id_kasir`, `total_harga`) VALUES
(110, '2025-11-28 07:26:08', 'TRX0001', '1', 9000),
(111, '2025-11-28 07:26:29', 'TRX0002', '4', 6000),
(112, '2025-11-28 07:27:06', 'TRX0003', '3', 39600),
(113, '2025-11-28 07:27:40', 'TRX0004', '5', 10000),
(114, '2025-11-28 08:47:52', 'TRX0005', '5', 20250);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'kasir', '$2y$10$NwKJc2w9X1waSLa4pJItoeZP8yyDAGKCgf/9j61GzS6lMnSqItyDS', 'kasir', '2025-11-28 11:09:30'),
(2, 'admin', '$2y$10$gfi2vqw.ZrMrOuQeayOT/uX9ZwJ3JMDCXoCW77DAFyqM47Ja9KiQu', 'admin', '2025-11-28 11:32:05'),
(3, 'budi', '$2y$10$rSzTr9TvcSpXDF1bEJdf6OihvQqrqoKXUoPoQVpsYl4hGKLnUduAi', 'user', '2025-11-28 11:40:27'),
(4, 'roni', '$2y$10$NyhLBAuGeLXqbfmT733czu54fwEeXpFBUSYFOkI56yfhlCLrXw1xK', 'user', '2025-11-28 11:42:53'),
(5, 'joni', '$2y$10$89G1oYwfBoXvfoW1NLwnHOxc.V5PCnb1.Kr0JEYdTh/jSVhthTQgu', 'user', '2025-11-28 11:47:46'),
(6, 'user', '$2y$10$buIWrzZrTUJfZuf5lu1aC.uxEJI7Ru5dXcQckfXWP/lms9/3qtDdW', 'user', '2025-11-28 11:53:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir') NOT NULL DEFAULT 'kasir',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$HHG7Bw7VngSqJaQzQVZxV.dqqqtTua.G0FRvy3Tk95PgBJuI0UKVO', 'admin', '2025-11-28 05:25:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `voucher`
--

CREATE TABLE `voucher` (
  `id` char(6) NOT NULL,
  `nama_voucher` varchar(35) NOT NULL,
  `diskon` double DEFAULT 0,
  `maks_diskon` int(7) DEFAULT 0,
  `tanggal_kadaluarsa` datetime NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `voucher`
--

INSERT INTO `voucher` (`id`, `nama_voucher`, `diskon`, `maks_diskon`, `tanggal_kadaluarsa`, `status`) VALUES
('VC0001', 'Voucher ABC Orange Squash', 0.25, 25000, '2025-12-31 23:59:59', 'aktif'),
('VC0002', 'Voucher Indofood Wonderland', 0.5, 35000, '2025-12-31 23:59:59', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_transaksi`,`id_produk`),
  ADD KEY `fk_detail_produk` (`id_produk`);

--
-- Indeks untuk tabel `kasir`
--
ALTER TABLE `kasir`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produk_voucher` (`id_voucher`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaksi_kasir` (`id_kasir`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_voucher` FOREIGN KEY (`id_voucher`) REFERENCES `voucher` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_voucher`) REFERENCES `voucher` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_kasir` FOREIGN KEY (`id_kasir`) REFERENCES `kasir` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_kasir`) REFERENCES `kasir` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
