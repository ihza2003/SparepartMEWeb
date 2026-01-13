-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 13, 2026 at 02:10 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbsparepart`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `iduser` int NOT NULL,
  `user` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`iduser`, `user`, `password`, `created_at`) VALUES
(1, 'Yogi', '$2y$10$1ok.So2W1e1E34dGRiUlPO0rmlWMJxUT9CI5FBXLC5t9qdp.yb4AK', '2026-01-13 08:22:25');

-- --------------------------------------------------------

--
-- Table structure for table `tb_akses`
--

CREATE TABLE `tb_akses` (
  `nomorakses` int NOT NULL,
  `nomorkartu` varchar(50) NOT NULL,
  `namalengkap` varchar(50) NOT NULL,
  `departement` varchar(50) NOT NULL,
  `jam` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_akses`
--

INSERT INTO `tb_akses` (`nomorakses`, `nomorkartu`, `namalengkap`, `departement`, `jam`) VALUES
(1, '11473', 'Rahman Rusmawan', 'Maintenance', '2026-01-13 10:41:25');

-- --------------------------------------------------------

--
-- Table structure for table `tb_keluar`
--

CREATE TABLE `tb_keluar` (
  `idkeluar` int NOT NULL,
  `idbarang` int NOT NULL,
  `iduser` int NOT NULL,
  `jumlah` int NOT NULL,
  `penerima` varchar(100) DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_masuk`
--

CREATE TABLE `tb_masuk` (
  `idmasuk` int NOT NULL,
  `idbarang` int NOT NULL,
  `iduser` int NOT NULL,
  `jumlah` int NOT NULL,
  `pengirim` varchar(100) DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_masuk`
--

INSERT INTO `tb_masuk` (`idmasuk`, `idbarang`, `iduser`, `jumlah`, `pengirim`, `tanggal`) VALUES
(6, 6, 1, 5, 'Rahman', '2026-01-13 14:01:01'),
(7, 6, 1, 2, 'Abdan', '2026-01-13 14:01:26');

-- --------------------------------------------------------

--
-- Table structure for table `tb_stok`
--

CREATE TABLE `tb_stok` (
  `idbarang` int NOT NULL,
  `nomorbarang` varchar(50) NOT NULL,
  `namabarang` varchar(100) NOT NULL,
  `mesin` varchar(50) DEFAULT NULL,
  `norak` varchar(50) DEFAULT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_stok`
--

INSERT INTO `tb_stok` (`idbarang`, `nomorbarang`, `namabarang`, `mesin`, `norak`, `stok`, `created_at`) VALUES
(6, '44111', 'Heater Spiral  500W', 'Cup Sealer', 'A1', 7, '2026-01-13 14:01:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Indexes for table `tb_akses`
--
ALTER TABLE `tb_akses`
  ADD PRIMARY KEY (`nomorakses`);

--
-- Indexes for table `tb_keluar`
--
ALTER TABLE `tb_keluar`
  ADD PRIMARY KEY (`idkeluar`),
  ADD KEY `fk_keluar_barang` (`idbarang`),
  ADD KEY `fk_keluar_admin` (`iduser`);

--
-- Indexes for table `tb_masuk`
--
ALTER TABLE `tb_masuk`
  ADD PRIMARY KEY (`idmasuk`),
  ADD KEY `fk_masuk_barang` (`idbarang`),
  ADD KEY `fk_masuk_admin` (`iduser`);

--
-- Indexes for table `tb_stok`
--
ALTER TABLE `tb_stok`
  ADD PRIMARY KEY (`idbarang`),
  ADD UNIQUE KEY `nomorbarang` (`nomorbarang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `iduser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_akses`
--
ALTER TABLE `tb_akses`
  MODIFY `nomorakses` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_keluar`
--
ALTER TABLE `tb_keluar`
  MODIFY `idkeluar` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_masuk`
--
ALTER TABLE `tb_masuk`
  MODIFY `idmasuk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_stok`
--
ALTER TABLE `tb_stok`
  MODIFY `idbarang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_keluar`
--
ALTER TABLE `tb_keluar`
  ADD CONSTRAINT `fk_keluar_admin` FOREIGN KEY (`iduser`) REFERENCES `admin` (`iduser`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_keluar_barang` FOREIGN KEY (`idbarang`) REFERENCES `tb_stok` (`idbarang`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `tb_masuk`
--
ALTER TABLE `tb_masuk`
  ADD CONSTRAINT `fk_masuk_admin` FOREIGN KEY (`iduser`) REFERENCES `admin` (`iduser`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_masuk_barang` FOREIGN KEY (`idbarang`) REFERENCES `tb_stok` (`idbarang`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
