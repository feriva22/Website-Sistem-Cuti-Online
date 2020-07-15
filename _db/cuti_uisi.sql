-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2019 at 01:25 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cuti_uisi`
--
DROP DATABASE IF EXISTS `cuti_uisi`;
CREATE DATABASE IF NOT EXISTS `cuti_uisi` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `cuti_uisi`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `adm_id` int(11) NOT NULL,
  `adm_username` varchar(45) DEFAULT NULL,
  `adm_password` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `admin`
--

TRUNCATE TABLE `admin`;
--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adm_id`, `adm_username`, `adm_password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `cuti`
--

DROP TABLE IF EXISTS `cuti`;
CREATE TABLE `cuti` (
  `cti_id` int(11) NOT NULL,
  `cti_karyawan` int(11) NOT NULL COMMENT 'Pk karyawan',
  `cti_tglpengajuan` datetime NOT NULL COMMENT 'Tanggal pengajuan cuti',
  `cti_hari` tinyint(2) NOT NULL COMMENT 'Berapa hari cutinya\n',
  `cti_mulai` date NOT NULL COMMENT 'tanggal mulai cuti',
  `cti_selesai` date NOT NULL COMMENT 'Tanggal selesai cuti',
  `cti_alasan` varchar(45) NOT NULL COMMENT 'alasan cuti',
  `cti_appr_sdmstat` tinyint(2) NOT NULL COMMENT 'status approval\n',
  `cti_appr_sdmpk` int(11) DEFAULT NULL COMMENT 'PK karyawan dengan level sdm',
  `cti_appr_sdmnote` text,
  `cti_appr_sdmdate` datetime DEFAULT NULL,
  `cti_appr_atlstat` tinyint(2) NOT NULL COMMENT 'Status approve atasan langsung',
  `cti_appr_atlpk` int(11) DEFAULT NULL COMMENT 'pk ke karyawan dengan level ataman langsung',
  `cti_appr_atlnote` text COMMENT 'Note approve Dari ataman langsung',
  `cti_appr_atldate` datetime DEFAULT NULL,
  `cti_appr_attlstat` tinyint(2) NOT NULL COMMENT 'Status atasan tidak langsung',
  `cti_appr_attlpk` int(11) DEFAULT NULL,
  `cti_appr_attlnote` text,
  `cti_appr_attldate` datetime DEFAULT NULL,
  `cti_ovrd_atasanpk` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `cuti`
--

TRUNCATE TABLE `cuti`;
--
-- Dumping data for table `cuti`
--

INSERT INTO `cuti` (`cti_id`, `cti_karyawan`, `cti_tglpengajuan`, `cti_hari`, `cti_mulai`, `cti_selesai`, `cti_alasan`, `cti_appr_sdmstat`, `cti_appr_sdmpk`, `cti_appr_sdmnote`, `cti_appr_sdmdate`, `cti_appr_atlstat`, `cti_appr_atlpk`, `cti_appr_atlnote`, `cti_appr_atldate`, `cti_appr_attlstat`, `cti_appr_attlpk`, `cti_appr_attlnote`, `cti_appr_attldate`, `cti_ovrd_atasanpk`) VALUES
(8, 14, '2019-08-06 09:08:55', 1, '2020-10-12', '2020-10-13', 'sakit', 1, 15, '', '2019-08-06 09:09:22', 1, 16, '', '2019-08-06 09:10:51', 1, 17, '', '2019-08-06 09:11:12', NULL),
(9, 14, '2019-08-06 12:02:09', 1, '2020-11-17', '2020-11-18', 'Hamil', 1, 15, '', '2019-08-06 12:05:58', 1, 16, '', '2019-08-06 12:04:19', 2, 17, '', '2019-08-06 12:06:31', NULL),
(10, 16, '2019-08-07 12:18:40', 1, '2020-11-24', '2020-11-25', 'tidak mau kerja', 3, NULL, NULL, NULL, 3, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL),
(14, 16, '2019-08-07 13:02:27', 1, '2020-11-01', '2020-11-02', 'asdf', 3, NULL, NULL, NULL, 3, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL),
(16, 18, '2019-08-07 13:12:46', 2, '2020-12-11', '2020-12-13', 'sdf', 1, 15, '', '2019-08-07 13:13:45', 1, 16, '', '2019-08-07 13:13:32', 1, 17, '', '2019-08-07 13:24:06', NULL),
(17, 16, '2019-08-07 13:14:33', 1, '2020-10-22', '2020-10-23', 'ntah', 1, 15, '', '2019-08-07 13:24:24', 1, NULL, NULL, NULL, 1, 17, '', '2019-08-07 13:24:03', 17);

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

DROP TABLE IF EXISTS `divisi`;
CREATE TABLE `divisi` (
  `dvs_id` int(11) NOT NULL,
  `dvs_nama` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `divisi`
--

TRUNCATE TABLE `divisi`;
--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`dvs_id`, `dvs_nama`) VALUES
(4, 'ICT'),
(5, 'SSC'),
(6, 'SDMO & TIK'),
(7, 'AKADEMIK'),
(8, 'PROGRAM STUDI');

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

DROP TABLE IF EXISTS `jabatan`;
CREATE TABLE `jabatan` (
  `jbt_id` int(11) NOT NULL,
  `jbt_nama` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `jabatan`
--

TRUNCATE TABLE `jabatan`;
--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`jbt_id`, `jbt_nama`) VALUES
(7, 'STAFF'),
(8, 'DOSEN');

-- --------------------------------------------------------

--
-- Table structure for table `jatah_cuti`
--

DROP TABLE IF EXISTS `jatah_cuti`;
CREATE TABLE `jatah_cuti` (
  `jtc_id` int(11) NOT NULL,
  `jtc_validdate` date NOT NULL,
  `jtc_jumlah` tinyint(2) DEFAULT NULL,
  `jtc_sisa` tinyint(2) DEFAULT NULL,
  `jtc_karyawan` int(11) NOT NULL,
  `jtc_delaystart` date NOT NULL,
  `jtc_delayend` date NOT NULL,
  `jtc_status` tinyint(1) DEFAULT NULL COMMENT 'Active/waiting until 3 bulan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `jatah_cuti`
--

TRUNCATE TABLE `jatah_cuti`;
--
-- Dumping data for table `jatah_cuti`
--

INSERT INTO `jatah_cuti` (`jtc_id`, `jtc_validdate`, `jtc_jumlah`, `jtc_sisa`, `jtc_karyawan`, `jtc_delaystart`, `jtc_delayend`, `jtc_status`) VALUES
(7, '2020-09-01', 12, 11, 11, '2021-09-02', '2021-12-02', 1),
(8, '2021-09-01', 12, 12, 11, '2022-09-02', '2022-12-02', 1),
(9, '2020-08-15', 12, 12, 12, '2021-08-16', '2021-11-16', 1),
(10, '2020-08-09', 12, 12, 13, '2021-08-10', '2021-11-10', 1),
(11, '2020-09-01', 12, 10, 14, '2021-09-02', '2021-12-02', 1),
(12, '2020-10-01', 12, 12, 15, '2021-10-02', '2022-01-02', 1),
(13, '2020-10-01', 12, 5, 16, '2021-10-02', '2022-01-02', 1),
(14, '2020-10-02', 12, 12, 17, '2021-10-03', '2022-01-03', 1),
(15, '2020-08-10', 12, 10, 18, '2021-08-11', '2021-11-11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

DROP TABLE IF EXISTS `karyawan`;
CREATE TABLE `karyawan` (
  `krw_id` int(11) NOT NULL,
  `krw_username` varchar(45) NOT NULL,
  `krw_password` varchar(45) NOT NULL,
  `krw_email` varchar(45) DEFAULT NULL,
  `krw_nama` varchar(45) NOT NULL,
  `krw_nik` varchar(20) NOT NULL,
  `krw_tgllahir` date DEFAULT NULL,
  `krw_jeniskelamin` bit(1) DEFAULT NULL,
  `krw_alamat` varchar(45) DEFAULT NULL,
  `krw_agama` tinyint(2) DEFAULT NULL,
  `krw_foto` varchar(45) DEFAULT NULL,
  `krw_jatahcuti` tinyint(4) DEFAULT NULL COMMENT 'Total jatah cuti , tapa hitung di tabel cuti',
  `krw_tglmasuk` date DEFAULT NULL,
  `krw_divisi` int(11) NOT NULL,
  `krw_jabatan` int(11) NOT NULL,
  `krw_ovrd_atasanpk` int(11) DEFAULT NULL,
  `krw_level` tinyint(2) DEFAULT NULL COMMENT 'karyawan,sdm, atasan atau atasan langsung'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `karyawan`
--

TRUNCATE TABLE `karyawan`;
--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`krw_id`, `krw_username`, `krw_password`, `krw_email`, `krw_nama`, `krw_nik`, `krw_tgllahir`, `krw_jeniskelamin`, `krw_alamat`, `krw_agama`, `krw_foto`, `krw_jatahcuti`, `krw_tglmasuk`, `krw_divisi`, `krw_jabatan`, `krw_ovrd_atasanpk`, `krw_level`) VALUES
(14, 'karyawan1', 'e10adc3949ba59abbe56e057f20f883e', 'karyawan1', 'karyawan1', '30123123131231', '1999-10-10', b'1', 'd', 1, NULL, 58, '2019-09-01', 5, 7, NULL, 1),
(15, 'sdm1', 'e10adc3949ba59abbe56e057f20f883e', 'sdm1', 'sdm1', '30123123131231', '1996-09-20', b'1', 'jj', 2, NULL, 12, '2019-10-01', 6, 7, NULL, 2),
(16, 'atasanlangsung1', 'e10adc3949ba59abbe56e057f20f883e', 'atasanlangsung1@gmail.com', 'atasanlangsung1', '3017123123123', '1996-09-27', b'1', 'd', 1, NULL, 49, '2019-10-01', 7, 8, 17, 3),
(17, 'atasantdklangsung1', 'e10adc3949ba59abbe56e057f20f883e', 'atasantdklangsung1@gmail.com', 'atasantdklangsung1', '3012312321', '1996-09-19', b'1', 'asdf', 1, NULL, 12, '2019-10-02', 6, 8, NULL, 4),
(18, 'dandi', 'e10adc3949ba59abbe56e057f20f883e', 'dandi@gmail.com', 'dandi', '3012312312312', '1999-08-06', b'1', ' asd', 1, NULL, 50, '2019-08-10', 4, 7, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adm_id`);

--
-- Indexes for table `cuti`
--
ALTER TABLE `cuti`
  ADD PRIMARY KEY (`cti_id`,`cti_karyawan`),
  ADD KEY `fk_cuti_karyawan1_idx` (`cti_karyawan`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`dvs_id`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`jbt_id`);

--
-- Indexes for table `jatah_cuti`
--
ALTER TABLE `jatah_cuti`
  ADD PRIMARY KEY (`jtc_id`,`jtc_karyawan`),
  ADD KEY `fk_jatah_cuti_karyawan1_idx` (`jtc_karyawan`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`krw_id`,`krw_divisi`,`krw_jabatan`),
  ADD KEY `fk_karyawan_jabatan_idx` (`krw_jabatan`),
  ADD KEY `fk_karyawan_divisi1_idx` (`krw_divisi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cuti`
--
ALTER TABLE `cuti`
  MODIFY `cti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `dvs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `jbt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jatah_cuti`
--
ALTER TABLE `jatah_cuti`
  MODIFY `jtc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `krw_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cuti`
--
ALTER TABLE `cuti`
  ADD CONSTRAINT `fk_cuti_karyawan1` FOREIGN KEY (`cti_karyawan`) REFERENCES `karyawan` (`krw_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `jatah_cuti`
--
ALTER TABLE `jatah_cuti`
  ADD CONSTRAINT `fk_jatah_cuti_karyawan1` FOREIGN KEY (`jtc_karyawan`) REFERENCES `karyawan` (`krw_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `fk_karyawan_divisi1` FOREIGN KEY (`krw_divisi`) REFERENCES `divisi` (`dvs_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_karyawan_jabatan` FOREIGN KEY (`krw_jabatan`) REFERENCES `jabatan` (`jbt_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
