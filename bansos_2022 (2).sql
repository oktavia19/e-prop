-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2022 at 10:30 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eproposal2`
--

-- --------------------------------------------------------

--
-- Table structure for table `bansos_2022`
--

CREATE TABLE `bansos_2022` (
  `id` int(50) NOT NULL,
  `tahun_pengajuan` int(4) DEFAULT NULL,
  `jenis_pengajuan` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `desa` varchar(255) DEFAULT NULL,
  `peruntukan` varchar(255) DEFAULT NULL,
  `kelompok_penerima` varchar(255) DEFAULT NULL,
  `opd_rekomendasi` varchar(255) DEFAULT NULL,
  `opd_pelaksana` varchar(255) DEFAULT NULL,
  `program` varchar(255) DEFAULT NULL,
  `kegiatan` varchar(255) DEFAULT NULL,
  `sub_kegiatan` varchar(255) DEFAULT NULL,
  `uraian_keg_satuan` varchar(2000) DEFAULT NULL,
  `penerima` varchar(255) DEFAULT NULL,
  `pimpinan` varchar(255) DEFAULT NULL,
  `bhi` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `nominal` int(100) DEFAULT NULL,
  `nik` int(20) DEFAULT NULL,
  `kk` int(20) DEFAULT NULL,
  `tahun_terakhir_menerima` int(4) DEFAULT NULL,
  `tanggal_permohonan` date DEFAULT NULL,
  `nomor_permohonan` varchar(255) DEFAULT NULL,
  `nomor_penerbitan_rekomendasi` varchar(255) DEFAULT NULL,
  `pejabat_penerbitan_rekomendasi` varchar(255) DEFAULT NULL,
  `tanggal_penerbitan_rekomendasi` date DEFAULT NULL,
  `tanggal_disposisi_bupati` date DEFAULT NULL,
  `tanggal_pertimbangan_ketua_tapd` date DEFAULT NULL,
  `isi_disposisi_ketua_tapd` varchar(2000) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bansos_2022`
--

INSERT INTO `bansos_2022` (`id`, `tahun_pengajuan`, `jenis_pengajuan`, `kecamatan`, `desa`, `peruntukan`, `kelompok_penerima`, `opd_rekomendasi`, `opd_pelaksana`, `program`, `kegiatan`, `sub_kegiatan`, `uraian_keg_satuan`, `penerima`, `pimpinan`, `bhi`, `alamat`, `nominal`, `nik`, `kk`, `tahun_terakhir_menerima`, `tanggal_permohonan`, `nomor_permohonan`, `nomor_penerbitan_rekomendasi`, `pejabat_penerbitan_rekomendasi`, `tanggal_penerbitan_rekomendasi`, `tanggal_disposisi_bupati`, `tanggal_pertimbangan_ketua_tapd`, `isi_disposisi_ketua_tapd`, `created_at`, `created_by`) VALUES
(2147483647, 2022, 'BANSOS', '352305', '3523052003', 'uang', 'perseorangan', '2.15.2.11.0.00.02.0000', '2.15.2.11.0.00.02.0000', '3.31.01', '3.31.01.2.02', '3.31.01.2.02.01', 'wawan', 'wiwin', '723091893218', 'JALAN MAGER 1', '8000', 2147483647, 12730182, 2017, 3, '2021-05-13', 'fdb273091jkks', 'hamid', '05/14/2021', '2021-01-13', '2021-05-01', '1970-01-01', '', '2022-06-17 14:13:13', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bansos_2022`
--
ALTER TABLE `bansos_2022`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bansos_2022`
--
ALTER TABLE `bansos_2022`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483647;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
