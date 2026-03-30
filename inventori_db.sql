-- phpMyAdmin SQL Dump
-- Sistem Manajemen Inventori
-- Database: `inventori_db`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `inventori_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `inventori_db`;

-- --------------------------------------------------------
-- Table structure for table `kategori`
-- --------------------------------------------------------

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `stok_minimum` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `harga_satuan`, `stok_minimum`, `foto`) VALUES
(1, 'Elektronik', 1500000.00, 5, NULL),
(2, 'Peralatan Kantor', 250000.00, 10, NULL),
(3, 'Furnitur', 800000.00, 3, NULL);

-- --------------------------------------------------------
-- Table structure for table `produk`
-- --------------------------------------------------------

CREATE TABLE `produk` (
  `kode_produk` varchar(10) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `lokasi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `produk` (`kode_produk`, `id_kategori`, `lokasi`) VALUES
('P001', 1, 'Rak A-1'),
('P002', 1, 'Rak A-1'),
('P003', 1, 'Rak A-2'),
('P004', 2, 'Rak B-1'),
('P005', 2, 'Rak B-1'),
('P006', 2, 'Rak B-2'),
('P007', 3, 'Rak C-1'),
('P008', 3, 'Rak C-1'),
('P009', 3, 'Rak C-2'),
('P010', 1, 'Rak A-3');

-- --------------------------------------------------------
-- Table structure for table `supplier`
-- --------------------------------------------------------

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `no_npwp` varchar(20) DEFAULT NULL,
  `jenis_supplier` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supplier` (`id_supplier`, `nama`, `alamat`, `no_telp`, `no_npwp`, `jenis_supplier`) VALUES
(1, 'PT Teknologi Maju', 'Jl. Sudirman No. 123, Jakarta Pusat', '021-12345678', '01.234.567.8-901.000', 'Distributor'),
(2, 'CV Kantor Sejahtera', 'Jl. Gatot Subroto No. 45, Jakarta Selatan', '021-23456789', '02.345.678.9-012.000', 'Produsen'),
(3, 'PT Furni Nusantara', 'Jl. Thamrin No. 88, Jakarta Pusat', '021-34567890', '03.456.789.0-123.000', 'Importir'),
(4, 'UD Elektronik Jaya', 'Jl. Merdeka No. 56, Bandung', '022-45678901', '04.567.890.1-234.000', 'Agen'),
(5, 'PT Alat Tulis Prima', 'Jl. Diponegoro No. 78, Surabaya', '031-56789012', '05.678.901.2-345.000', 'Distributor');

-- --------------------------------------------------------
-- Table structure for table `transaksi_stok`
-- --------------------------------------------------------

CREATE TABLE `transaksi_stok` (
  `id_transaksi` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `kode_produk` varchar(10) NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `tgl_kadaluarsa` date DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `total_nilai` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transaksi_stok` (`id_transaksi`, `id_supplier`, `kode_produk`, `tgl_transaksi`, `tgl_kadaluarsa`, `jumlah`, `total_nilai`) VALUES
(1, 1, 'P001', '2025-07-05', '2026-07-05', 10, 15000000.00),
(2, 2, 'P004', '2025-07-10', NULL, 20, 5000000.00),
(3, 3, 'P007', '2025-08-01', NULL, 5, 4000000.00),
(4, 1, 'P002', '2025-08-15', '2026-08-15', 8, 12000000.00),
(5, 4, 'P001', '2025-09-03', '2026-09-03', 15, 22500000.00),
(6, 2, 'P005', '2025-09-20', NULL, 30, 7500000.00),
(7, 5, 'P006', '2025-10-05', NULL, 25, 6250000.00),
(8, 3, 'P008', '2025-10-18', NULL, 7, 5600000.00),
(9, 1, 'P003', '2025-11-02', '2026-11-02', 12, 18000000.00),
(10, 4, 'P010', '2025-11-14', '2026-11-14', 20, 30000000.00),
(11, 2, 'P004', '2025-11-25', NULL, 15, 3750000.00),
(12, 5, 'P005', '2025-12-03', NULL, 40, 10000000.00),
(13, 1, 'P001', '2025-12-10', '2026-12-10', 5, 7500000.00),
(14, 3, 'P009', '2025-12-18', NULL, 8, 6400000.00),
(15, 4, 'P002', '2026-01-05', '2027-01-05', 10, 15000000.00),
(16, 2, 'P006', '2026-01-12', NULL, 20, 5000000.00),
(17, 5, 'P007', '2026-01-20', NULL, 6, 4800000.00),
(18, 1, 'P003', '2026-02-03', '2027-02-03', 8, 12000000.00),
(19, 3, 'P008', '2026-02-14', NULL, 10, 8000000.00),
(20, 4, 'P010', '2026-03-01', '2027-03-01', 15, 22500000.00);

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `email`, `created_at`, `foto`) VALUES
(3, 'nabil', '$2y$10$jKEdkAhK4kcQHUw9Qjij8evKcubN5Zlts7ucElGN.FskTtmPooWbO', 'NABIL LABQINO', 'nabil@example.com', '2026-01-16 18:14:56', 'foto_696a80203f7cb.jpg'),
(4, 'tyler', '$2y$10$In6Me7lWJIpNwMelgvm4ke8BEuqUYx/8lWq8uYSsB3KXVMYMrdujK', 'Ratno Anung', 'tyler@example.com', '2026-01-17 07:40:14', 'foto_696b3cde04a6f.jpg'),
(5, 'carti', '$2y$10$oGGc/SUzMQh9NnBlam8JlOi.4N5wwYe2bYKzhmyNv1XPVl8b4y3zW', 'jordan teler', 'carti@example.com', '2026-01-17 12:38:53', 'foto_696b82ddb7ce1.jpg');

-- --------------------------------------------------------
-- Indexes
-- --------------------------------------------------------

ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

ALTER TABLE `produk`
  ADD PRIMARY KEY (`kode_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

ALTER TABLE `transaksi_stok`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `kode_produk` (`kode_produk`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

-- --------------------------------------------------------
-- AUTO_INCREMENT
-- --------------------------------------------------------

ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `transaksi_stok`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

-- --------------------------------------------------------
-- Foreign Keys
-- --------------------------------------------------------

ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE;

ALTER TABLE `transaksi_stok`
  ADD CONSTRAINT `transaksi_stok_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_stok_ibfk_2` FOREIGN KEY (`kode_produk`) REFERENCES `produk` (`kode_produk`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
