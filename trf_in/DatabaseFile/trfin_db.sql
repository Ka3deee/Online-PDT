-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2022 at 03:00 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trfin_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `spClearData` ()   BEGIN
SET @tbl = NULL;
SELECT GROUP_CONCAT('`',table_name,'`') INTO @tbl
FROM INFORMATION_SCHEMA.TABLES
WHERE table_schema = 'trfin_db' and TABLE_NAME NOT IN ('users','stores','received_batch_trf_tbl','iupc_tbl','batchtransfer_tbl','batchtransfer_status_tbl','batchtransfer_download_logs_tbl');

SET @tbl= CONCAT('DROP TABLE ', @tbl,';');
SELECT @tbl;
PREPARE stmt1 FROM @tbl;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spGenerateReport` (`trfbch` INT, `refid` INT)   BEGIN
	
	select
	a.inumbr,
	a.idescr,
	a.trfshp,
	a.istdpk,
	a.rcvqty,
	a.expqty,
	a.trfbch
	from tranfiles as a
	where a.reference = refid and a.trfbch = trfbch;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spSearch` (`barcode` VARCHAR(50), `store` INT, `iupctbl` VARCHAR(50), `trfbchtable` VARCHAR(50))   BEGIN
	select
	b.id,
	b.trfbch,
	b.idescr
	from iupctable as a
	inner join trfbchtable as b on a.strcode = b.strcode and a.inumbr = b.inumbr
	where a.iupc = barcode and a.strcode =  store;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spSearchTRF` (`barcode` VARCHAR(50), `refid` INT)   BEGIN
	select
	a.id,
	a.trfbch,
	a.inumbr,
	a.idescr,
	a.reference,
	barcode
	from tranfiles as a
	where a.reference = refid and barcode in (select iupc from upc_ls where inumbr = a.inumbr and reference = refid) and (select forPrint from downloaded_trfs where trfbch=a.trfbch and reference=a.reference) is null;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spSearchTRF_copy1` (`barcode` VARCHAR(50), `refid` INT)   BEGIN
	select
	a.id,
	a.trfbch,
	a.inumbr,
	a.idescr,
	a.reference
	from tranfiles as a
	where a.reference = refid and barcode in (select iupc from upc_ls where inumbr = a.inumbr and reference = refid and iupc = barcode) and (select forPrint from downloaded_trfs where trfbch = a.trfbch and reference=a.reference) is null;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_chcktblifexist` (IN `tblnam` VARCHAR(50))   BEGIN
	SELECT count(*) as count
	FROM information_schema.TABLES
	WHERE (TABLE_SCHEMA = 'trfin_db') AND (TABLE_NAME = tblnam);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `batchtransfer_download_logs_tbl`
--

CREATE TABLE `batchtransfer_download_logs_tbl` (
  `id` int(11) NOT NULL,
  `trfbch` bigint(20) NOT NULL,
  `strcode` int(11) NOT NULL,
  `downloaded_date` datetime NOT NULL,
  `isPrinted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `batchtransfer_status_tbl`
--

CREATE TABLE `batchtransfer_status_tbl` (
  `id` int(11) NOT NULL,
  `trfbch` bigint(20) NOT NULL,
  `isPrinted` int(11) NOT NULL,
  `isDownloaded` int(11) NOT NULL,
  `isDone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `batchtransfer_tbl`
--

CREATE TABLE `batchtransfer_tbl` (
  `id` int(11) NOT NULL,
  `trfbch` bigint(20) NOT NULL,
  `inumbr` bigint(20) NOT NULL,
  `idescr` varchar(200) NOT NULL,
  `trfshp` decimal(18,2) NOT NULL,
  `istdpk` decimal(18,2) NOT NULL,
  `rcvqty` decimal(18,2) NOT NULL,
  `expqty` decimal(18,2) NOT NULL,
  `strcode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `iupc_tbl`
--

CREATE TABLE `iupc_tbl` (
  `id` int(11) NOT NULL,
  `iupc` bigint(25) NOT NULL,
  `inumbr` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `received_batch_trf_tbl`
--

CREATE TABLE `received_batch_trf_tbl` (
  `id` int(11) NOT NULL,
  `trfbch` bigint(20) NOT NULL,
  `inumbr` bigint(20) NOT NULL,
  `idescr` varchar(200) NOT NULL,
  `trfshp` decimal(18,2) NOT NULL,
  `istdpk` decimal(18,2) NOT NULL,
  `rcvqty` decimal(18,2) NOT NULL,
  `expqty` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `store_code` int(11) NOT NULL,
  `store_desc` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `store_code`, `store_desc`, `created_at`) VALUES
(1, 100, 'Tabaco Department Store       ', '2022-07-06 18:53:42'),
(2, 102, 'INACTIVE TA DS-Store SA       ', '2022-07-06 18:53:42'),
(3, 106, 'Tabaco MTD DS-Booking         ', '2022-07-06 18:53:42'),
(4, 107, 'TCL Central- Tabaco- DC       ', '2022-07-06 18:53:42'),
(5, 108, 'Tabaco MTD DS-Whse WHL        ', '2022-07-06 18:53:42'),
(6, 105, 'Tabaco MTD DS-Store WHL       ', '2022-07-06 18:53:42'),
(7, 109, 'Bacacay Expressmart-Store     ', '2022-07-06 18:53:42'),
(8, 103, 'INACTIVE Tabaco(Market Savers)', '2022-07-06 18:53:42'),
(9, 101, 'Tabaco TCM Bread Express Store', '2022-07-06 18:53:42'),
(10, 104, 'TCL Central Tabaco-Whse       ', '2022-07-06 18:53:42'),
(11, 110, 'Tabaco Department Store-Whse  ', '2022-07-06 18:53:42'),
(12, 111, 'Tabaco CTS Property Management', '2022-07-06 18:53:43'),
(13, 112, 'Tabaco TCM Property Management', '2022-07-06 18:53:43'),
(14, 113, 'Tabaco PNS-Whse               ', '2022-07-06 18:53:43'),
(15, 115, 'Tabaco Department Store-DC    ', '2022-07-06 18:53:43'),
(16, 116, 'Tabaco PNS-Store              ', '2022-07-06 18:53:43'),
(17, 114, 'Tabaco PNS-DC                 ', '2022-07-06 18:53:43'),
(18, 117, 'Tabaco MCL                    ', '2022-07-06 18:53:43'),
(19, 118, 'Tabaco Dept. Store MCD-Store  ', '2022-07-06 18:53:43'),
(20, 119, 'Tabaco Dept. Store MCD-Whse   ', '2022-07-06 18:53:43'),
(21, 121, 'Tabaco Property Management    ', '2022-07-06 18:53:43'),
(22, 120, 'Tabaco MCD-Store              ', '2022-07-06 18:53:43'),
(23, 122, 'Tabaco MFP                    ', '2022-07-06 18:53:43'),
(24, 124, 'Tabaco TCL MCD-Whse           ', '2022-07-06 18:53:43'),
(25, 123, 'Tabaco MCD-Whse               ', '2022-07-06 18:53:43'),
(26, 126, 'INACTIVE Tabaco Store Plus    ', '2022-07-06 18:53:43'),
(27, 125, 'Tabaco MCD-DC                 ', '2022-07-06 18:53:43'),
(28, 127, 'Tabaco Store Plus-Whse        ', '2022-07-06 18:53:43'),
(29, 128, 'Tabaco Store Plus-DC          ', '2022-07-06 18:53:43'),
(30, 129, 'FTG Central Whse- Tabaco      ', '2022-07-06 18:53:43'),
(31, 130, 'Tabaco Expressmart MCD-Store  ', '2022-07-06 18:53:43'),
(32, 131, 'Tabaco MTD EMR MCD-Whse       ', '2022-07-06 18:53:43'),
(33, 133, 'Tabaco MTD EMR MCD-DC         ', '2022-07-06 18:53:43'),
(34, 134, 'Tabaco Store Plus MCD-DC      ', '2022-07-06 18:53:43'),
(35, 135, 'INACTIVE TA Printing Office   ', '2022-07-06 18:53:43'),
(36, 132, 'Tabaco Store Plus MCD-Store   ', '2022-07-06 18:53:43'),
(37, 136, 'Tabaco Printing Office        ', '2022-07-06 18:53:43'),
(38, 138, 'Lion Central Tabaco-Whse      ', '2022-07-06 18:53:43'),
(39, 140, 'Tabaco Supermarket-Store      ', '2022-07-06 18:53:43'),
(40, 141, 'Tabaco Bakery Store           ', '2022-07-06 18:53:43'),
(41, 137, 'Tabaco MarketSaver Supermarket', '2022-07-06 18:53:43'),
(42, 139, 'Lion Central Tabaco-DC        ', '2022-07-06 18:53:43'),
(43, 142, 'Tabaco Supermarket MCD-Store  ', '2022-07-06 18:53:44'),
(44, 143, 'Tabaco Concourse 2 FO Store   ', '2022-07-06 18:53:44'),
(45, 144, 'Tabaco Supermarket MCD-Whse   ', '2022-07-06 18:53:44'),
(46, 145, 'INACTIVE TA MTD SMR-Store WHL ', '2022-07-06 18:53:44'),
(47, 146, 'Tabaco MTD SMR-Booking        ', '2022-07-06 18:53:44'),
(48, 147, 'Concourse Park Suites         ', '2022-07-06 18:53:44'),
(49, 148, 'Tabaco MTD SMR-Whse WHL       ', '2022-07-06 18:53:44'),
(50, 149, 'Tabaco PNS-Store              ', '2022-07-06 18:53:44'),
(51, 151, 'Tabaco MTD Food Ops-Store     ', '2022-07-06 18:53:44'),
(52, 150, 'Tabaco Supermarket-Whse       ', '2022-07-06 18:53:44'),
(53, 152, 'Tabaco MTD Food Ops-Whse      ', '2022-07-06 18:53:44'),
(54, 154, 'Tabaco Supermarket VL         ', '2022-07-06 18:53:44'),
(55, 153, 'Tabaco MTD Food Ops-DC        ', '2022-07-06 18:53:44'),
(56, 157, 'TabacoMarketSaversSupermarket ', '2022-07-06 18:53:44'),
(57, 155, 'Tabaco Repacking              ', '2022-07-06 18:53:44'),
(58, 156, 'Tabaco Food Express           ', '2022-07-06 18:53:44'),
(59, 159, 'Tabaco LFR Food Ops Store     ', '2022-07-06 18:53:44'),
(60, 158, 'Tabaco TCM MTD Food Ops-Store ', '2022-07-06 18:53:44'),
(61, 160, 'Tabaco Supermarket-DC         ', '2022-07-06 18:53:44'),
(62, 161, 'Tabaco Express Link           ', '2022-07-06 18:53:44'),
(63, 162, 'MSG MCD Store                 ', '2022-07-06 18:53:44'),
(64, 163, 'Tabaco LION MCD-Whse          ', '2022-07-06 18:53:44'),
(65, 167, 'INACTIVE TA EMR-DC            ', '2022-07-06 18:53:44'),
(66, 165, 'INACTIVE TA EMR-Store         ', '2022-07-06 18:53:44'),
(67, 164, 'Central Bakery Warehouse      ', '2022-07-06 18:53:44'),
(68, 166, 'INACTIVE TA EMR-Whse          ', '2022-07-06 18:53:44'),
(69, 168, 'Tabaco MCD IT-Store           ', '2022-07-06 18:53:44'),
(70, 169, 'Tabaco LION MCD-DC            ', '2022-07-06 18:53:44'),
(71, 170, 'Tabaco MTD Food Ops MCD-Store ', '2022-07-06 18:53:44'),
(72, 171, 'Tabaco MTD Food Ops MCD-Whse  ', '2022-07-06 18:53:44'),
(73, 173, 'Tabaco MTD Food Ops MCD-DC    ', '2022-07-06 18:53:44'),
(74, 172, 'MWD MCD Store                 ', '2022-07-06 18:53:44'),
(75, 174, 'LION Central- Tabaco- Whse    ', '2022-07-06 18:53:44'),
(76, 175, 'INACTIVE TabacoExpressmart STR', '2022-07-06 18:53:44'),
(77, 176, 'Tabaco Expressmart-Whse       ', '2022-07-06 18:53:44'),
(78, 180, 'TAC Food Production MCD-Store ', '2022-07-06 18:53:44'),
(79, 177, 'Tabaco Expressmart-DC         ', '2022-07-06 18:53:44'),
(80, 179, 'Bacacay Expressmart MCD-Store ', '2022-07-06 18:53:44'),
(81, 178, 'LION Central- Tabaco- DC      ', '2022-07-06 18:53:44'),
(82, 181, 'TAC Spice Room-MPCOM          ', '2022-07-06 18:53:45'),
(83, 184, 'Daraga Business Center        ', '2022-07-06 18:53:45'),
(84, 182, 'TAC Spice Room-PRR            ', '2022-07-06 18:53:45'),
(85, 183, 'TAC Food Production Store     ', '2022-07-06 18:53:45'),
(86, 185, 'TAC Food Production Whse      ', '2022-07-06 18:53:45'),
(87, 186, 'TAC Food Production MCD-Whse  ', '2022-07-06 18:53:45'),
(88, 187, 'TAC Food Production MCD-DC    ', '2022-07-06 18:53:45'),
(89, 188, 'TAC Finished Products Store   ', '2022-07-06 18:53:45'),
(90, 189, 'TAC Finished Products-MPCOM   ', '2022-07-06 18:53:45'),
(91, 190, 'TAC Food Production DC        ', '2022-07-06 18:53:45'),
(92, 191, 'TAC Food Production R1        ', '2022-07-06 18:53:45'),
(93, 193, 'Polangui Business Center      ', '2022-07-06 18:53:45'),
(94, 192, 'Tabaco TCM MTD FO MCD-Store   ', '2022-07-06 18:53:45'),
(95, 194, 'LTS MCD Store                 ', '2022-07-06 18:53:45'),
(96, 195, 'Tabaco Theatre MCD-Store      ', '2022-07-06 18:53:45'),
(97, 196, 'Tabaco Concourse FO Store     ', '2022-07-06 18:53:45'),
(98, 198, 'Sorsogon Business Center      ', '2022-07-06 18:53:45'),
(99, 199, 'L Market Choice MCD-Store     ', '2022-07-06 18:53:45'),
(100, 197, 'Tabaco Concourse MCD Store    ', '2022-07-06 18:53:45'),
(101, 200, 'Sorsogon Department Store     ', '2022-07-06 18:53:45'),
(102, 201, 'Sorsogon Property Management  ', '2022-07-06 18:53:45'),
(103, 202, 'Sorsogon MFP                  ', '2022-07-06 18:53:45'),
(104, 203, 'Daraga MTD Food Ops-Store     ', '2022-07-06 18:53:45'),
(105, 205, 'Daraga MTD Food Ops-DC        ', '2022-07-06 18:53:45'),
(106, 206, 'TCL Central Bulan-Whse        ', '2022-07-06 18:53:45'),
(107, 204, 'Daraga MTD Food Ops-Whse      ', '2022-07-06 18:53:45'),
(108, 207, 'Daraga MTD Food Ops MCD-Store ', '2022-07-06 18:53:45'),
(109, 208, 'Daraga MTD Food Ops MCD-DC    ', '2022-07-06 18:53:45'),
(110, 209, 'TCL Bulan-DC                  ', '2022-07-06 18:53:45'),
(111, 212, 'Ligao MFP                     ', '2022-07-06 18:53:45'),
(112, 211, 'Daraga Property Management    ', '2022-07-06 18:53:45'),
(113, 210, 'Sorsogon Department Store-Whse', '2022-07-06 18:53:45'),
(114, 213, 'Bulan Store Plus              ', '2022-07-06 18:53:45'),
(115, 214, 'Bulan Store Plus-Whse         ', '2022-07-06 18:53:46'),
(116, 215, 'Sorsogon Department Store-DC  ', '2022-07-06 18:53:46'),
(117, 216, 'Bulan PNS-Store               ', '2022-07-06 18:53:46'),
(118, 217, 'Bulan PNS-Whse                ', '2022-07-06 18:53:46'),
(119, 219, 'Bulan SP MCD-DC               ', '2022-07-06 18:53:46'),
(120, 218, 'Bulan SP MCD-Store            ', '2022-07-06 18:53:46'),
(121, 222, 'Polangui Supermarket MCD-DC   ', '2022-07-06 18:53:46'),
(122, 221, 'Polangui Supermarket MCD-Store', '2022-07-06 18:53:46'),
(123, 220, 'Ligao Supermartket MCD-Store  ', '2022-07-06 18:53:46'),
(124, 223, 'Ligao Supermarket MCD-Whse    ', '2022-07-06 18:53:46'),
(125, 225, 'Ligao Supermarket MCD-DC      ', '2022-07-06 18:53:46'),
(126, 224, 'Daraga DepartmentStore MCD-Str', '2022-07-06 18:53:46'),
(127, 226, 'Daraga Store Plus             ', '2022-07-06 18:53:46'),
(128, 227, 'Daraga Store Plus-Whse        ', '2022-07-06 18:53:46'),
(129, 229, 'Daraga Department Store MCD-DC', '2022-07-06 18:53:46'),
(130, 230, 'Sorsogon MCD-Store            ', '2022-07-06 18:53:46'),
(131, 231, 'Sorsogon MCD-Whse             ', '2022-07-06 18:53:46'),
(132, 228, 'Daraga Store Plus-DC          ', '2022-07-06 18:53:46'),
(133, 232, 'Sorsogon Store Plus MCD-Store ', '2022-07-06 18:53:46'),
(134, 233, 'Sorsogon MCD-DC               ', '2022-07-06 18:53:46'),
(135, 234, 'Sorsogon Store Plus MCD-DC    ', '2022-07-06 18:53:46'),
(136, 235, 'Sorsogon Store Plus           ', '2022-07-06 18:53:46'),
(137, 237, 'Sorsogon Store Plus-DC        ', '2022-07-06 18:53:46'),
(138, 238, 'Sorsogon Store Plus BLG-Whse  ', '2022-07-06 18:53:46'),
(139, 239, 'Bulan Supermarket-Store       ', '2022-07-06 18:53:46'),
(140, 236, 'Sorsogon Store Plus B4-Whse   ', '2022-07-06 18:53:46'),
(141, 240, 'Sorsogon Supermarket-Store    ', '2022-07-06 18:53:46'),
(142, 241, 'Sorsogon Bakery Store         ', '2022-07-06 18:53:46'),
(143, 242, 'PolanguiDepartmentStoreMCD-Str', '2022-07-06 18:53:46'),
(144, 244, 'Polangui DepartmentStoreMCD-DC', '2022-07-06 18:53:46'),
(145, 245, 'Polangui Store Plus           ', '2022-07-06 18:53:46'),
(146, 243, 'INACTIVE Polangui Essentials  ', '2022-07-06 18:53:46'),
(147, 246, 'Polangui Store Plus-Whse      ', '2022-07-06 18:53:47'),
(148, 247, 'Polangui Store Plus-DC        ', '2022-07-06 18:53:47'),
(149, 248, 'Polangui Essentials-Whse      ', '2022-07-06 18:53:47'),
(150, 249, 'Polangui Essentials-DC        ', '2022-07-06 18:53:47'),
(151, 250, 'Shopmore Central Sorsogon-Whse', '2022-07-06 18:53:47'),
(152, 251, 'Polangui MTD Food Ops-Store   ', '2022-07-06 18:53:47'),
(153, 252, 'Polangui MTD Food Ops-Whse    ', '2022-07-06 18:53:47'),
(154, 253, 'Polangui MTD Food Ops-DC      ', '2022-07-06 18:53:47'),
(155, 254, 'Daraga Supermarket MCD-Store  ', '2022-07-06 18:53:47'),
(156, 255, 'Daraga Supermarket MCD-DC     ', '2022-07-06 18:53:47'),
(157, 256, 'Polangui Food Express         ', '2022-07-06 18:53:47'),
(158, 258, 'Daraga Supermarket-Whse       ', '2022-07-06 18:53:47'),
(159, 257, 'Daraga Supermarket Store      ', '2022-07-06 18:53:47'),
(160, 259, 'Daraga Supermarket-DC         ', '2022-07-06 18:53:47'),
(161, 261, 'Polangui Property Management  ', '2022-07-06 18:53:47'),
(162, 260, 'Shopmore Central Sorsogon-DC  ', '2022-07-06 18:53:47'),
(163, 263, 'Polangui PM MCD-DC            ', '2022-07-06 18:53:47'),
(164, 265, 'Ligao Expressmart-Store       ', '2022-07-06 18:53:47'),
(165, 262, 'Polangui PM MCD-Store         ', '2022-07-06 18:53:47'),
(166, 264, 'Sorsogon Supermarket2-Store   ', '2022-07-06 18:53:47'),
(167, 266, 'Ligao Expressmart-Whse        ', '2022-07-06 18:53:47'),
(168, 267, 'Ligao Expressmart-DC          ', '2022-07-06 18:53:47'),
(169, 268, 'SorsogonSupermarket2 MCD-Store', '2022-07-06 18:53:47'),
(170, 269, 'INACTIVE Sorsogon Expressmart ', '2022-07-06 18:53:47'),
(171, 273, 'Polangui MTD Food Ops MCD-DC  ', '2022-07-06 18:53:47'),
(172, 271, 'Polangui MTD Food Ops MCD-Whse', '2022-07-06 18:53:47'),
(173, 272, 'Polangui MFP                  ', '2022-07-06 18:53:47'),
(174, 270, 'Polangui MTD FoodOps MCD-Store', '2022-07-06 18:53:47'),
(175, 275, 'Polangui Supermarket-Store    ', '2022-07-06 18:53:47'),
(176, 274, 'Shopmore Central Bulan-Whse   ', '2022-07-06 18:53:47'),
(177, 276, 'Lion Central Polangui-Whse    ', '2022-07-06 18:53:47'),
(178, 278, 'Shopmore Bulan-DC             ', '2022-07-06 18:53:47'),
(179, 277, 'Polangui Supermarket-DC       ', '2022-07-06 18:53:47'),
(180, 279, 'Bulan SMR MCD-Store           ', '2022-07-06 18:53:47'),
(181, 280, 'Bulan SMR MCD-Whse            ', '2022-07-06 18:53:47'),
(182, 283, 'Camalig MCD DC                ', '2022-07-06 18:53:47'),
(183, 282, 'Camalig MCD Store             ', '2022-07-06 18:53:47'),
(184, 284, 'Camalig Bread Express Store   ', '2022-07-06 18:53:47'),
(185, 281, 'Polangui Bakery Store         ', '2022-07-06 18:53:47'),
(186, 286, 'Camalig Expressmart Whse      ', '2022-07-06 18:53:48'),
(187, 285, 'Camalig Expressmart Store     ', '2022-07-06 18:53:48'),
(188, 287, 'Camalig Expressmart DC        ', '2022-07-06 18:53:48'),
(189, 288, 'Bulan SMR MCD-DC              ', '2022-07-06 18:53:48'),
(190, 289, 'Guinobatan Store Plus         ', '2022-07-06 18:53:48'),
(191, 290, 'Guinobatan MCD-Store          ', '2022-07-06 18:53:48'),
(192, 291, 'Guinobatan MCD-DC             ', '2022-07-06 18:53:48'),
(193, 292, 'Guinobatan MFP                ', '2022-07-06 18:53:48'),
(194, 294, 'Sorsogon CTS Property Mgt.    ', '2022-07-06 18:53:48'),
(195, 293, 'Guinobatan Store Plus-Whse    ', '2022-07-06 18:53:48'),
(196, 295, 'Guinobatan Supermarket Store  ', '2022-07-06 18:53:48'),
(197, 296, 'Guinobatan Supermarket Whse   ', '2022-07-06 18:53:48'),
(198, 297, 'Guinobatan Supermarket DC     ', '2022-07-06 18:53:48'),
(199, 298, 'Guinobatan StorePlus MCD-Store', '2022-07-06 18:53:48'),
(200, 299, 'Bulan PNS-DC                  ', '2022-07-06 18:53:48'),
(201, 300, 'Legaspi Department Store      ', '2022-07-06 18:53:48'),
(202, 301, 'Daraga Bakery Store           ', '2022-07-06 18:53:48'),
(203, 302, 'Daraga MFP                    ', '2022-07-06 18:53:48'),
(204, 303, 'Luminary MCD-Store            ', '2022-07-06 18:53:48'),
(205, 304, 'Luminary MCD-DC               ', '2022-07-06 18:53:48'),
(206, 305, 'INACTIVE Leg. MTD DS-Store WHL', '2022-07-06 18:53:48'),
(207, 306, 'Legaspi MTD DS-Booking        ', '2022-07-06 18:53:48'),
(208, 307, 'TCL Central Legazpi- Whse     ', '2022-07-06 18:53:48'),
(209, 309, 'TCL Central- Legazpi- DC      ', '2022-07-06 18:53:48'),
(210, 310, 'Legaspi Department Store-Whse ', '2022-07-06 18:53:48'),
(211, 308, 'Legaspi MTD DS-Whse WHL       ', '2022-07-06 18:53:48'),
(212, 311, 'Legazpi LLC RZL Property Mgt  ', '2022-07-06 18:53:48'),
(213, 312, 'Camalig MFP                   ', '2022-07-06 18:53:48'),
(214, 313, 'Legazpi GZM RZL Property Mgt. ', '2022-07-06 18:53:48'),
(215, 314, 'Tabaco MWD Property Management', '2022-07-06 18:53:48'),
(216, 315, 'Legaspi Department Store-DC   ', '2022-07-06 18:53:49'),
(217, 317, 'Lion Central Legazpi- DS Whse ', '2022-07-06 18:53:49'),
(218, 316, 'JLB CTC MCD Store             ', '2022-07-06 18:53:49'),
(219, 318, 'Legaspi Dept. Store MCD-Store ', '2022-07-06 18:53:49'),
(220, 319, 'Legaspi Dept. Store MCD-Whse  ', '2022-07-06 18:53:49'),
(221, 320, 'Legaspi MCD-Store             ', '2022-07-06 18:53:49'),
(222, 321, 'Legaspi Property Management   ', '2022-07-06 18:53:49'),
(223, 322, 'Legaspi MFP                   ', '2022-07-06 18:53:49'),
(224, 323, 'Legaspi MCD-Whse              ', '2022-07-06 18:53:49'),
(225, 324, 'EBC MCD Store                 ', '2022-07-06 18:53:49'),
(226, 325, 'Legaspi MCD-DC                ', '2022-07-06 18:53:49'),
(227, 326, 'Legaspi Food Express          ', '2022-07-06 18:53:49'),
(228, 327, 'Legaspi SupermarketRizal-Store', '2022-07-06 18:53:49'),
(229, 329, 'Central MetroTrade Legazpi-DC ', '2022-07-06 18:53:49'),
(230, 328, 'CentralMetroTradeLegazpi-Whse ', '2022-07-06 18:53:49'),
(231, 332, 'Legazpi TCL MCD-Whse          ', '2022-07-06 18:53:49'),
(232, 333, 'Legaspi SMR Rizal MCD-DC      ', '2022-07-06 18:53:49'),
(233, 331, 'Legaspi SMR Rizal MCD-Whse    ', '2022-07-06 18:53:49'),
(234, 330, 'Legaspi SMR Rizal MCD-Store   ', '2022-07-06 18:53:49'),
(235, 334, 'Legazpi TCL MCD-DC            ', '2022-07-06 18:53:49'),
(236, 335, 'INACTIVE Legaspi Store Plus   ', '2022-07-06 18:53:49'),
(237, 337, 'Legaspi Store Plus-DC         ', '2022-07-06 18:53:49'),
(238, 336, 'Legaspi Store Plus-Whse       ', '2022-07-06 18:53:49'),
(239, 338, 'LION Cold Storage-Whse        ', '2022-07-06 18:53:49'),
(240, 339, 'FTG Polangui                  ', '2022-07-06 18:53:49'),
(241, 341, 'Legaspi Bakery Store          ', '2022-07-06 18:53:49'),
(242, 340, 'Legaspi Supermarket-Store     ', '2022-07-06 18:53:49'),
(243, 342, 'Legaspi Supermarket MCD-Store ', '2022-07-06 18:53:49'),
(244, 343, 'Legazpi Supermarket CTC-Store ', '2022-07-06 18:53:49'),
(245, 344, 'Legaspi Supermarket MCD-Whse  ', '2022-07-06 18:53:49'),
(246, 346, 'Legazpi CTC PM MCD-Store      ', '2022-07-06 18:53:49'),
(247, 347, 'Legaspi Department Store-CTC  ', '2022-07-06 18:53:49'),
(248, 345, 'INACTIVE LC MTD SMR-Store WHL ', '2022-07-06 18:53:49'),
(249, 349, 'FTG Legazpi- ALB1             ', '2022-07-06 18:53:49'),
(250, 348, 'Legaspi MTD SMR-Whse WHL      ', '2022-07-06 18:53:49'),
(251, 350, 'Legaspi Supermarket-Whse      ', '2022-07-06 18:53:49'),
(252, 351, 'Legaspi MTD Food Ops-Store    ', '2022-07-06 18:53:49'),
(253, 352, 'Legaspi MTD Food Ops-Whse     ', '2022-07-06 18:53:49'),
(254, 353, 'Legaspi MTD Food Ops-DC       ', '2022-07-06 18:53:49'),
(255, 354, 'LION Central- Legazpi- Whse   ', '2022-07-06 18:53:49'),
(256, 356, 'Legaspi Food Express-Tahao    ', '2022-07-06 18:53:49'),
(257, 355, 'Legaspi Repacking             ', '2022-07-06 18:53:49'),
(258, 358, 'Legaspi LION MCD-Whse         ', '2022-07-06 18:53:49'),
(259, 357, 'LION Central- Legazpi- DC     ', '2022-07-06 18:53:49'),
(260, 360, 'Legaspi Supermarket-DC        ', '2022-07-06 18:53:49'),
(261, 359, 'Legaspi LION MCD-DC           ', '2022-07-06 18:53:49'),
(262, 361, 'Legaspi Express Link          ', '2022-07-06 18:53:49'),
(263, 362, 'Legaspi Expressmart ALB-Store ', '2022-07-06 18:53:49'),
(264, 363, 'Legaspi Royal Court           ', '2022-07-06 18:53:50'),
(265, 364, 'Central Bakery Warehouse      ', '2022-07-06 18:53:50'),
(266, 365, 'INACTIVE Legaspi Expressmart  ', '2022-07-06 18:53:50'),
(267, 366, 'Legaspi Expressmart-Whse      ', '2022-07-06 18:53:50'),
(268, 367, 'Legaspi Expressmart-DC        ', '2022-07-06 18:53:50'),
(269, 368, 'LegazpiCTC Property Management', '2022-07-06 18:53:50'),
(270, 370, 'Legaspi MTD Food Ops MCD-Store', '2022-07-06 18:53:50'),
(271, 372, 'Legazpi Expressmart ALB2-Store', '2022-07-06 18:53:50'),
(272, 371, 'Legaspi MTD Food Ops MCD-Whse ', '2022-07-06 18:53:50'),
(273, 369, 'FTG Legazpi- Rizal            ', '2022-07-06 18:53:50'),
(274, 373, 'Legaspi MTD Food Ops MCD-DC   ', '2022-07-06 18:53:50'),
(275, 374, 'LegazpiExpresmartALB2MCD-Store', '2022-07-06 18:53:50'),
(276, 376, 'Legaspi SMR Tahao-Whse        ', '2022-07-06 18:53:50'),
(277, 377, 'Legaspi SMR Tahao-DC          ', '2022-07-06 18:53:50'),
(278, 379, 'CK CTC MCD Store              ', '2022-07-06 18:53:50'),
(279, 378, 'Legaspi LFR Food Ops Store    ', '2022-07-06 18:53:50'),
(280, 375, 'Legaspi SMR Tahao-Store       ', '2022-07-06 18:53:50'),
(281, 380, 'Legaspi Store Plus MCD-Store  ', '2022-07-06 18:53:50'),
(282, 381, 'Legaspi Store Plus MCD-Whse   ', '2022-07-06 18:53:50'),
(283, 384, 'GZM MCD Store                 ', '2022-07-06 18:53:50'),
(284, 385, 'Legazpi SMR CTC GM-Store      ', '2022-07-06 18:53:50'),
(285, 383, 'Legaspi Store Plus MCD-DC     ', '2022-07-06 18:53:50'),
(286, 382, 'Rawis EMR MCD-Store           ', '2022-07-06 18:53:50'),
(287, 386, 'Legaspi Food Express-Rizal    ', '2022-07-06 18:53:50'),
(288, 387, 'Rawis Expressmart-Store       ', '2022-07-06 18:53:50'),
(289, 391, 'Legaspi SMR Tahao MCD-Whse    ', '2022-07-06 18:53:50'),
(290, 389, 'Rawis EMR MCD-DC              ', '2022-07-06 18:53:50'),
(291, 388, 'Rawis EMR MCD-Whse            ', '2022-07-06 18:53:50'),
(292, 390, 'Legaspi SMR Tahao MCD-Store   ', '2022-07-06 18:53:50'),
(293, 392, 'LION Fruits & Veg. Depot-Store', '2022-07-06 18:53:50'),
(294, 393, 'Legaspi SMR Tahao MCD-DC      ', '2022-07-06 18:53:50'),
(295, 394, 'Guinobatan Store Plus MCD-DC  ', '2022-07-06 18:53:50'),
(296, 395, 'Legaspi Theatre MCD-Store     ', '2022-07-06 18:53:50'),
(297, 396, 'Legazpi Dept Store CTC-Whse   ', '2022-07-06 18:53:50'),
(298, 397, 'MTL MCD Store                 ', '2022-07-06 18:53:50'),
(299, 398, 'Elite Best Choice- Jollibee   ', '2022-07-06 18:53:50'),
(300, 399, 'Elite Best Choice- Chowking   ', '2022-07-06 18:53:50'),
(301, 400, 'Masbate Department Store      ', '2022-07-06 18:53:50'),
(302, 402, 'MasbateDepartmentStoreMCD-Str ', '2022-07-06 18:53:50'),
(303, 403, 'Masbate Department StoreMCD-DC', '2022-07-06 18:53:50'),
(304, 401, 'Masbate Department Store-Whse ', '2022-07-06 18:53:50'),
(305, 404, 'TCL Central Masbate-Whse      ', '2022-07-06 18:53:50'),
(306, 406, 'Naga Department Store Centro  ', '2022-07-06 18:53:50'),
(307, 405, 'Legaspi Concourse FO Store    ', '2022-07-06 18:53:50'),
(308, 407, 'NagaDepartmentStoreCentro-Whse', '2022-07-06 18:53:50'),
(309, 408, 'TCL Masbate-DC                ', '2022-07-06 18:53:50'),
(310, 409, 'Masbate Supermarket MCD-Store ', '2022-07-06 18:53:50'),
(311, 410, 'Masbate Supermarket MCD-DC    ', '2022-07-06 18:53:50'),
(312, 412, 'Iriga MFP                     ', '2022-07-06 18:53:50'),
(313, 411, 'Iriga Property Management     ', '2022-07-06 18:53:50'),
(314, 414, 'Nabua PNS-DC                  ', '2022-07-06 18:53:50'),
(315, 413, 'Nabua PNS-Whse                ', '2022-07-06 18:53:50'),
(316, 415, 'INACTIVE Legaspi SMR-Whse VL  ', '2022-07-06 18:53:51'),
(317, 416, 'Nabua PNS-Store               ', '2022-07-06 18:53:51'),
(318, 418, 'Shopmore Central-Masbate-Whse ', '2022-07-06 18:53:51'),
(319, 417, 'Masbate Supermarket-Store     ', '2022-07-06 18:53:51'),
(320, 419, 'Shopmore Central-Masbate-DC   ', '2022-07-06 18:53:51'),
(321, 420, 'NagaDepartmentStore-Centro MCD', '2022-07-06 18:53:51'),
(322, 421, 'Shopmore Central-Legaspi-Whse ', '2022-07-06 18:53:51'),
(323, 422, 'Sorsogon2 MFP                 ', '2022-07-06 18:53:51'),
(324, 424, 'INACTIVE SPMCentral LC-Whse VL', '2022-07-06 18:53:51'),
(325, 426, 'Daraga Department Store       ', '2022-07-06 18:53:51'),
(326, 423, 'INACTIVE LionCentralLC-Whse VL', '2022-07-06 18:53:51'),
(327, 425, 'INACTIVE MTD Legazpi-Whse VL  ', '2022-07-06 18:53:51'),
(328, 427, 'Daraga Department Store-Whse  ', '2022-07-06 18:53:51'),
(329, 428, 'TCL CENTRAL Naga-Whse         ', '2022-07-06 18:53:51'),
(330, 429, 'FTG Pili                      ', '2022-07-06 18:53:51'),
(331, 430, 'Iriga Department Store        ', '2022-07-06 18:53:51'),
(332, 432, 'IrigaDepartmentStore MCD-Store', '2022-07-06 18:53:51'),
(333, 431, 'Iriga Department Store-Whse   ', '2022-07-06 18:53:51'),
(334, 433, 'Iriga Department Store MCD-DC ', '2022-07-06 18:53:51'),
(335, 436, 'Pili Department Store         ', '2022-07-06 18:53:51'),
(336, 435, 'Bulan Department Store        ', '2022-07-06 18:53:51'),
(337, 434, 'TCL Central IRIGA-Whse        ', '2022-07-06 18:53:51'),
(338, 437, 'Pili Department Store-Whse    ', '2022-07-06 18:53:51'),
(339, 438, 'TCL Naga Central-DC           ', '2022-07-06 18:53:51'),
(340, 439, 'Pili LCC Home Plus-Store      ', '2022-07-06 18:53:51'),
(341, 440, 'Pili LCC Home Plus-Whse       ', '2022-07-06 18:53:51'),
(342, 441, 'Pili Bakery Store             ', '2022-07-06 18:53:51'),
(343, 442, 'Bulan MFP                     ', '2022-07-06 18:53:51'),
(344, 443, 'INACTIVETabacoDeptStorePNS-Str', '2022-07-06 18:53:51'),
(345, 444, 'TabacoDepartmentStore PNS-Whse', '2022-07-06 18:53:51'),
(346, 445, 'Polangui Department Store     ', '2022-07-06 18:53:51'),
(347, 446, 'Polangui Department Store-Whse', '2022-07-06 18:53:51'),
(348, 447, 'Aroroy LCC Home Plus- Store   ', '2022-07-06 18:53:51'),
(349, 448, 'Aroroy LCC Home Plus-Whse     ', '2022-07-06 18:53:51'),
(350, 449, 'Polangui Home Gallery Store   ', '2022-07-06 18:53:51'),
(351, 451, 'Masbate Business Center       ', '2022-07-06 18:53:51'),
(352, 452, 'Bacacay MFP                   ', '2022-07-06 18:53:51'),
(353, 450, 'Polangui Home Gallery-Whse    ', '2022-07-06 18:53:51'),
(354, 453, 'Aroroy DS MCD-Store           ', '2022-07-06 18:53:51'),
(355, 454, 'Aroroy DS MCD-Whse            ', '2022-07-06 18:53:51'),
(356, 455, 'Aroroy Department Store       ', '2022-07-06 18:53:51'),
(357, 457, 'Polangui Expressmart-Store    ', '2022-07-06 18:53:51'),
(358, 459, 'Shopmore Central-Aroroy-DC    ', '2022-07-06 18:53:51'),
(359, 458, 'Shopmore Central-Aroroy-Whse  ', '2022-07-06 18:53:51'),
(360, 460, 'Aroroy MarketSavers SMKT-Store', '2022-07-06 18:53:51'),
(361, 456, 'Aroroy Dept. Store-Warehouse  ', '2022-07-06 18:53:51'),
(362, 461, 'Masbate Property Management   ', '2022-07-06 18:53:51'),
(363, 462, 'Masbate Food Express          ', '2022-07-06 18:53:51'),
(364, 463, 'Masbate Food Production Store ', '2022-07-06 18:53:51'),
(365, 464, 'Central Bakery Warehouse      ', '2022-07-06 18:53:51'),
(366, 465, 'Legazpi Expressmart ALB3-Store', '2022-07-06 18:53:51'),
(367, 467, 'Legaspi EMR ALB3 MCD-Store    ', '2022-07-06 18:53:51'),
(368, 466, 'LegazpiSupermarketCTCMCD-Store', '2022-07-06 18:53:51'),
(369, 469, 'FTG Nabua                     ', '2022-07-06 18:53:51'),
(370, 470, 'Aroroy MSV MCD-Store          ', '2022-07-06 18:53:51'),
(371, 468, 'Masbate Finished Product Store', '2022-07-06 18:53:51'),
(372, 471, 'Aroroy MSV MCD-Whse           ', '2022-07-06 18:53:52'),
(373, 473, 'Irosin Supermarket-Store      ', '2022-07-06 18:53:52'),
(374, 474, 'Central Bakery Warehouse      ', '2022-07-06 18:53:52'),
(375, 472, 'Tahao MFP                     ', '2022-07-06 18:53:52'),
(376, 475, 'Masbate Expressmart-Store     ', '2022-07-06 18:53:52'),
(377, 476, 'Masbate Expressmart MCD-Store ', '2022-07-06 18:53:52'),
(378, 477, 'Masbate Expressmart MCD-Whse  ', '2022-07-06 18:53:52'),
(379, 478, 'Pioduran MarketPlus MCD-Store ', '2022-07-06 18:53:52'),
(380, 481, 'FC Rolling Store              ', '2022-07-06 18:53:52'),
(381, 480, 'Luckytech MCD- Whse           ', '2022-07-06 18:53:52'),
(382, 479, 'Masbate Expressmart2 Store    ', '2022-07-06 18:53:52'),
(383, 482, 'Tiwi Expressmart MCD-Store    ', '2022-07-06 18:53:52'),
(384, 483, 'Legazpi Property Management-B4', '2022-07-06 18:53:52'),
(385, 484, 'Legazpi EMR ALB4 MCD-Store    ', '2022-07-06 18:53:52'),
(386, 487, 'Tiwi Expressmart-Store        ', '2022-07-06 18:53:52'),
(387, 486, 'Legazpi EMR ALB4 MCD-Whse     ', '2022-07-06 18:53:52'),
(388, 485, 'Legazpi Expressmart ALB4-Store', '2022-07-06 18:53:52'),
(389, 488, 'Bulan Department Store-Whse   ', '2022-07-06 18:53:52'),
(390, 489, 'FTG Iriga                     ', '2022-07-06 18:53:52'),
(391, 490, 'Polangui Expressmart MCD-Store', '2022-07-06 18:53:52'),
(392, 491, 'Legazpi DeptStoreCTC MCD-Store', '2022-07-06 18:53:52'),
(393, 493, 'Aroroy MCL                    ', '2022-07-06 18:53:52'),
(394, 492, 'Tiwi MFP                      ', '2022-07-06 18:53:52'),
(395, 494, 'Pio Duran LCC MarketPlus-Store', '2022-07-06 18:53:52'),
(396, 495, 'LC HRD-SMR Store              ', '2022-07-06 18:53:52'),
(397, 496, 'LC HRD-DS Store               ', '2022-07-06 18:53:52'),
(398, 498, 'Bldg. 1 CTC (LCC Ayala)       ', '2022-07-06 18:53:52'),
(399, 497, 'LC HRD-SSU Store              ', '2022-07-06 18:53:52'),
(400, 499, 'Legazpi Concourse MCD Store   ', '2022-07-06 18:53:52'),
(401, 500, 'INACTIVE Naga Department Store', '2022-07-06 18:53:52'),
(402, 501, 'INACTIVE Naga Dept. Store-HC  ', '2022-07-06 18:53:52'),
(403, 502, 'Naga Department Store FLX-HC  ', '2022-07-06 18:53:52'),
(404, 503, 'Naga Terminal                 ', '2022-07-06 18:53:52'),
(405, 505, 'Iriga MTD Food Ops-Whse       ', '2022-07-06 18:53:52'),
(406, 504, 'Iriga MTD Food Ops-Store      ', '2022-07-06 18:53:52'),
(407, 508, 'Iriga MTD Food Ops MCD-DC     ', '2022-07-06 18:53:52'),
(408, 507, 'Iriga MTD Food Ops MCD-Store  ', '2022-07-06 18:53:52'),
(409, 506, 'Iriga MTD Food Ops-DC         ', '2022-07-06 18:53:52'),
(410, 509, 'Libertad MCD-Whse             ', '2022-07-06 18:53:52'),
(411, 510, 'INACTIVE Naga Dept. Store-Whse', '2022-07-06 18:53:52'),
(412, 511, 'Naga IGU Property Management  ', '2022-07-06 18:53:52'),
(413, 513, 'Iriga Supermarket MCD-Store   ', '2022-07-06 18:53:52'),
(414, 512, 'Naga IGU MFP                  ', '2022-07-06 18:53:52'),
(415, 514, 'Iriga Supermarket MCD-DC      ', '2022-07-06 18:53:52'),
(416, 517, 'Iriga Supermarket-Store       ', '2022-07-06 18:53:52'),
(417, 515, 'INACTIVE Naga Dept.Store-DC   ', '2022-07-06 18:53:52'),
(418, 516, 'Naga Expressmart MCD-DC       ', '2022-07-06 18:53:52'),
(419, 519, 'Iriga Supermarket-DC          ', '2022-07-06 18:53:52'),
(420, 520, 'Naga MCD-Store                ', '2022-07-06 18:53:52'),
(421, 518, 'Libertad Central- Iriga- Whse ', '2022-07-06 18:53:52'),
(422, 523, 'Nabua Property Management     ', '2022-07-06 18:53:52'),
(423, 522, 'Naga FLX MFP                  ', '2022-07-06 18:53:52'),
(424, 521, 'Naga FLX Property Management  ', '2022-07-06 18:53:52'),
(425, 524, 'Nabua Store Plus MCD-Store    ', '2022-07-06 18:53:52'),
(426, 526, 'Nabua Department Store        ', '2022-07-06 18:53:52'),
(427, 525, 'Naga MCD-DC                   ', '2022-07-06 18:53:52'),
(428, 527, 'Nabua Department Store - Whse ', '2022-07-06 18:53:53'),
(429, 528, 'Nabua Store Plus-DC           ', '2022-07-06 18:53:53'),
(430, 529, 'Nabua Store Plus MCD-DC       ', '2022-07-06 18:53:53'),
(431, 530, 'Naga Department Store FLX     ', '2022-07-06 18:53:53'),
(432, 531, 'Naga Department Store FLX-Whse', '2022-07-06 18:53:53'),
(433, 534, 'Naga DS FLX MCD-Whse          ', '2022-07-06 18:53:53'),
(434, 535, 'Naga DS FLX MCD-DC            ', '2022-07-06 18:53:53'),
(435, 532, 'Naga DS FLX MCD-Store         ', '2022-07-06 18:53:53'),
(436, 533, 'Naga Department Store FLX-DC  ', '2022-07-06 18:53:53'),
(437, 536, 'Pili Store Plus               ', '2022-07-06 18:53:53'),
(438, 537, 'Pili Store Plus-Whse          ', '2022-07-06 18:53:53'),
(439, 539, 'Pili MFP                      ', '2022-07-06 18:53:53'),
(440, 540, 'Naga Supermarket-Store        ', '2022-07-06 18:53:53'),
(441, 541, 'INACTIVE Naga Bakery Store    ', '2022-07-06 18:53:53'),
(442, 538, 'Pili Store Plus-DC            ', '2022-07-06 18:53:53'),
(443, 542, 'Pili DepartmentStore MCD-Store', '2022-07-06 18:53:53'),
(444, 543, 'Pili Department Store MCD-DC  ', '2022-07-06 18:53:53'),
(445, 544, 'Iriga Bakery Store            ', '2022-07-06 18:53:53'),
(446, 546, 'NG MTD Food Ops GLU-Store     ', '2022-07-06 18:53:53'),
(447, 547, 'Libertad Central Pili-DC      ', '2022-07-06 18:53:53'),
(448, 545, 'Libertad Central Pili-Whse    ', '2022-07-06 18:53:53'),
(449, 548, 'Naga MTD SMR-Whse WHL         ', '2022-07-06 18:53:53'),
(450, 549, 'Naga Expressmart-Store        ', '2022-07-06 18:53:53'),
(451, 551, 'Naga MTD Food Ops-Store       ', '2022-07-06 18:53:53'),
(452, 550, 'INACTIVE Naga Supermarket-Whse', '2022-07-06 18:53:53'),
(453, 552, 'Nabua MFP                     ', '2022-07-06 18:53:53'),
(454, 554, 'Nabua Bakery Store            ', '2022-07-06 18:53:53'),
(455, 553, 'Naga MTD Food Ops-DC          ', '2022-07-06 18:53:53'),
(456, 555, 'INACTIVE Naga Repacking       ', '2022-07-06 18:53:53'),
(457, 556, 'Naga Food Express-IGU         ', '2022-07-06 18:53:53'),
(458, 559, 'Nabua Supermarket-DC          ', '2022-07-06 18:53:53'),
(459, 558, 'Nabua Supermarket-Whse        ', '2022-07-06 18:53:53'),
(460, 557, 'Nabua Supermarket-Store       ', '2022-07-06 18:53:53'),
(461, 561, 'Naga FLX Bakery Store         ', '2022-07-06 18:53:53'),
(462, 560, 'INACTIVE Naga Supermarket-DC  ', '2022-07-06 18:53:53'),
(463, 563, 'Nabua Supermarket MCD-DC      ', '2022-07-06 18:53:54'),
(464, 564, 'Nabua MTD Foos Ops MCD-Store  ', '2022-07-06 18:53:54'),
(465, 565, 'Nabua MTD Food Ops MCD-DC     ', '2022-07-06 18:53:54'),
(466, 562, 'Nabua Supermarket MCD-Store   ', '2022-07-06 18:53:54'),
(467, 567, 'Nabua MTD Food Ops-Store      ', '2022-07-06 18:53:54'),
(468, 566, 'Naga Expressmart MCD-Store    ', '2022-07-06 18:53:54'),
(469, 570, 'Naga MTD Food Ops MCD-Store   ', '2022-07-06 18:53:54'),
(470, 571, 'Naga MTD Food Ops MCD-Whse    ', '2022-07-06 18:53:54'),
(471, 568, 'Nabua MTD Food Ops-Whse       ', '2022-07-06 18:53:54'),
(472, 569, 'Nabua MTD Food Ops-DC         ', '2022-07-06 18:53:54'),
(473, 572, 'Pili Property Management      ', '2022-07-06 18:53:54'),
(474, 573, 'INACTIVE NG MTD FO-DC         ', '2022-07-06 18:53:54'),
(475, 574, 'Naga MTD Food Ops MCD-DC      ', '2022-07-06 18:53:54'),
(476, 575, 'Naga Supermarket IGU-Store    ', '2022-07-06 18:53:54'),
(477, 576, 'Naga Supermarket IGU-Whse     ', '2022-07-06 18:53:54'),
(478, 578, 'Pili MTD Food Ops-Store       ', '2022-07-06 18:53:54'),
(479, 577, 'Naga Supermarket IGU-DC       ', '2022-07-06 18:53:54'),
(480, 579, 'Pili MTD Food Ops-Whse        ', '2022-07-06 18:53:54'),
(481, 580, 'Pili MTD Food Ops-DC          ', '2022-07-06 18:53:54'),
(482, 581, 'Naga IGU Bakery Store         ', '2022-07-06 18:53:54'),
(483, 582, 'Naga Supermarket FLX MCD-Store', '2022-07-06 18:53:54'),
(484, 584, 'Naga Supermarket FLX MCD-DC   ', '2022-07-06 18:53:54'),
(485, 583, 'Nabua Terminal                ', '2022-07-06 18:53:54'),
(486, 585, 'Naga Supermarket FLX-Store    ', '2022-07-06 18:53:54'),
(487, 587, 'Naga Supermarket FLX-DC       ', '2022-07-06 18:53:55'),
(488, 586, 'Naga Supermarket FLX-Whse     ', '2022-07-06 18:53:55'),
(489, 590, 'Naga Supermarket IGU MCD-Store', '2022-07-06 18:53:55'),
(490, 589, 'Pili MTD Food Ops MCD-DC      ', '2022-07-06 18:53:55'),
(491, 588, 'Pili MTD Food Ops MCD-Store   ', '2022-07-06 18:53:55'),
(492, 591, 'Naga Supermarket IGU MCD-Whse ', '2022-07-06 18:53:55'),
(493, 593, 'Naga Supermarket IGU MCD-DC   ', '2022-07-06 18:53:55'),
(494, 592, 'Pili Supermarket MCD-Store    ', '2022-07-06 18:53:55'),
(495, 594, 'Pili Supermarket MCD-DC       ', '2022-07-06 18:53:55'),
(496, 595, 'Pili Supermarket-Store        ', '2022-07-06 18:53:55'),
(497, 596, 'Pili Supermarket-Whse         ', '2022-07-06 18:53:55'),
(498, 597, 'Pili Supermarket-DC           ', '2022-07-06 18:53:55'),
(499, 598, 'Naga TCL MCD-Whse             ', '2022-07-06 18:53:55'),
(500, 599, 'Libertad MCD-DC               ', '2022-07-06 18:53:55'),
(501, 601, 'Baybay Department Store-Whse  ', '2022-07-06 18:53:55'),
(502, 600, 'Baybay Department Store       ', '2022-07-06 18:53:55'),
(503, 602, 'Baybay Department Store-DC    ', '2022-07-06 18:53:55'),
(504, 603, 'Sorsogon Expressmart MCD-DC   ', '2022-07-06 18:53:55'),
(505, 605, 'Sorsogon Expressmart MCD-Whse ', '2022-07-06 18:53:55'),
(506, 604, 'Sorsogon Expressmart MCD-Store', '2022-07-06 18:53:55'),
(507, 606, 'Goa Department Store MCD-Store', '2022-07-06 18:53:55'),
(508, 608, 'Goa Supermarket MCD-Store     ', '2022-07-06 18:53:55'),
(509, 607, 'Goa Department Store MCD-Whse ', '2022-07-06 18:53:55'),
(510, 609, 'Goa Supermarket MCD-Whse      ', '2022-07-06 18:53:55'),
(511, 610, 'GoaPropertyManagementMCD-Store', '2022-07-06 18:53:55'),
(512, 612, 'Goa Supermarket-Store         ', '2022-07-06 18:53:55'),
(513, 613, 'Goa Supermarket-Whse          ', '2022-07-06 18:53:55'),
(514, 611, 'Goa PropertyManagementMCD-Whse', '2022-07-06 18:53:55'),
(515, 614, 'Goa Supermarket-DC            ', '2022-07-06 18:53:55'),
(516, 615, 'Baybay MCD Store              ', '2022-07-06 18:53:55'),
(517, 616, 'Baybay MCD-Whse               ', '2022-07-06 18:53:55'),
(518, 618, 'Sorsogon Supermarket2 MCD-DC  ', '2022-07-06 18:53:55'),
(519, 619, 'Goa MFP                       ', '2022-07-06 18:53:55'),
(520, 617, 'Baybay MCD-DC                 ', '2022-07-06 18:53:55'),
(521, 620, 'MC Department Store           ', '2022-07-06 18:53:55'),
(522, 621, 'MC Department Store-Whse      ', '2022-07-06 18:53:56'),
(523, 622, 'MC Department Store-DC        ', '2022-07-06 18:53:56'),
(524, 623, 'Calabanga SMR2 MCD-Store      ', '2022-07-06 18:53:56'),
(525, 627, 'MC MCD-DC                     ', '2022-07-06 18:53:56'),
(526, 628, 'LaConsumidoresCentralDaet-Whse', '2022-07-06 18:53:56'),
(527, 624, 'Goa Bakery store              ', '2022-07-06 18:53:56'),
(528, 625, 'MC MCD Store                  ', '2022-07-06 18:53:56'),
(529, 626, 'MC MCD-Whse                   ', '2022-07-06 18:53:56'),
(530, 629, 'LaConsumidores Central Daet-DC', '2022-07-06 18:53:56'),
(531, 630, 'Goa Department Store          ', '2022-07-06 18:53:56'),
(532, 633, 'CalabangaMarketSaversMCD-Store', '2022-07-06 18:53:56'),
(533, 634, 'Calabanga MarketSaversMCD-Whse', '2022-07-06 18:53:56'),
(534, 631, 'Goa Department Store-Whse     ', '2022-07-06 18:53:56'),
(535, 632, 'Calabanga Market Savers Smkt  ', '2022-07-06 18:53:56'),
(536, 635, 'Calabanga LCC Home Plus -Store', '2022-07-06 18:53:56'),
(537, 636, 'Calabanga LCC Home Plus-Whse  ', '2022-07-06 18:53:56'),
(538, 637, 'Calabanga LCC Home Plus MCD   ', '2022-07-06 18:53:56'),
(539, 638, 'Calabanga Store Plus MCD-Whse ', '2022-07-06 18:53:56'),
(540, 639, 'Calabanga MFP                 ', '2022-07-06 18:53:56'),
(541, 643, 'Sucat Department Store MCD-WHS', '2022-07-06 18:53:56'),
(542, 642, 'Sucat Department Store MCD-STR', '2022-07-06 18:53:56'),
(543, 640, 'Sucat Department Store        ', '2022-07-06 18:53:56'),
(544, 641, 'Sucat Department Store-Whse   ', '2022-07-06 18:53:56'),
(545, 644, 'Naga Expressmart GLU-Store    ', '2022-07-06 18:53:56'),
(546, 645, 'Labo LCC Market SP-Store      ', '2022-07-06 18:53:56'),
(547, 647, 'Labo SP MCD-Store             ', '2022-07-06 18:53:56'),
(548, 648, 'Labo SP MCD-Whse              ', '2022-07-06 18:53:56'),
(549, 646, 'Labo LCC Market SP-Whse       ', '2022-07-06 18:53:56'),
(550, 649, 'Goa MTD Food Ops Store        ', '2022-07-06 18:53:56'),
(551, 650, 'Labo LCC Market Plus-Store    ', '2022-07-06 18:53:56'),
(552, 651, 'Labo LCC MKP MCD-Store        ', '2022-07-06 18:53:56'),
(553, 653, 'Labo PM MCD-Store             ', '2022-07-06 18:53:56'),
(554, 652, 'Labo LCC MKP MCD-Whse         ', '2022-07-06 18:53:56'),
(555, 654, 'Labo PM MCD-Whse              ', '2022-07-06 18:53:56'),
(556, 656, 'Pilar Market Savers MCD-Whse  ', '2022-07-06 18:53:56'),
(557, 655, 'Pilar Market Savers MCD-Store ', '2022-07-06 18:53:56'),
(558, 657, 'Pilar Market Savers Smkt-Store', '2022-07-06 18:53:56'),
(559, 659, 'Libertad Central- Naga- DC    ', '2022-07-06 18:53:56'),
(560, 661, 'Naga Express Link             ', '2022-07-06 18:53:56'),
(561, 662, 'Daet SMR MCD-Store            ', '2022-07-06 18:53:56'),
(562, 658, 'Libertad Central- Naga- Whse  ', '2022-07-06 18:53:56'),
(563, 660, 'Daet LCC MARKET PLUS-STORE    ', '2022-07-06 18:53:56'),
(564, 663, 'Bato Supermarket MCD-Store    ', '2022-07-06 18:53:57'),
(565, 664, 'Central Bakery Warehouse      ', '2022-07-06 18:53:57'),
(566, 665, 'Ligao Supermarket-Store       ', '2022-07-06 18:53:57'),
(567, 666, 'Ligao Supermarket-Whse        ', '2022-07-06 18:53:57'),
(568, 669, 'Calabanga MTD Food Ops-Store  ', '2022-07-06 18:53:57'),
(569, 670, 'Ligao Supermarket2 - Store    ', '2022-07-06 18:53:57'),
(570, 667, 'Ligao Supermarket-DC          ', '2022-07-06 18:53:57'),
(571, 668, 'Daet SMR MCD-Whse             ', '2022-07-06 18:53:57'),
(572, 671, 'Daet Property Management      ', '2022-07-06 18:53:57'),
(573, 672, 'Labo Property Management      ', '2022-07-06 18:53:57'),
(574, 674, 'Daet PM MCD-Whse              ', '2022-07-06 18:53:57'),
(575, 673, 'Daet PM MCD-Store             ', '2022-07-06 18:53:57'),
(576, 675, 'Legazpi CSM-Repacking         ', '2022-07-06 18:53:57'),
(577, 676, 'Ligao Food Express            ', '2022-07-06 18:53:57'),
(578, 678, 'Daet DS MCD-Whse              ', '2022-07-06 18:53:57'),
(579, 677, 'Daet DS MCD-Store             ', '2022-07-06 18:53:57'),
(580, 679, 'FTG Ligao                     ', '2022-07-06 18:53:57'),
(581, 682, 'LABO MFP                      ', '2022-07-06 18:53:57'),
(582, 681, 'Goa Property Management       ', '2022-07-06 18:53:57'),
(583, 680, 'Daet Department Store         ', '2022-07-06 18:53:57'),
(584, 684, 'Ligao Department Store-Whse   ', '2022-07-06 18:53:57'),
(585, 683, 'Ligao Department Store        ', '2022-07-06 18:53:57'),
(586, 686, 'Ligao Supermarket2 MCD-Store  ', '2022-07-06 18:53:57'),
(587, 687, 'Ligao Dept. Store MCD-Store   ', '2022-07-06 18:53:57'),
(588, 685, 'Libon Expressmart-Store       ', '2022-07-06 18:53:57'),
(589, 688, 'Ligao Property Mgt.MCD-Store  ', '2022-07-06 18:53:57'),
(590, 689, 'Ligao Home Gallery-Store      ', '2022-07-06 18:53:57'),
(591, 690, 'Ligao Home Gallery-Whse       ', '2022-07-06 18:53:57'),
(592, 693, 'Ligao Property Management     ', '2022-07-06 18:53:57'),
(593, 691, 'Ligao Bakery Store            ', '2022-07-06 18:53:57'),
(594, 692, 'DAET MFP                      ', '2022-07-06 18:53:57'),
(595, 694, 'Naga Supermarket VLA-Store    ', '2022-07-06 18:53:57'),
(596, 695, 'Gubat Expressmart- Store      ', '2022-07-06 18:53:57'),
(597, 696, 'Daet Department Store-Whse    ', '2022-07-06 18:53:57'),
(598, 697, 'Gubat Expressmart MCD-Store   ', '2022-07-06 18:53:57'),
(599, 698, 'South Ocean Villa             ', '2022-07-06 18:53:58'),
(600, 699, 'Shopmore Commercial Corp      ', '2022-07-06 18:53:58'),
(601, 701, 'Baao Property Management      ', '2022-07-06 18:53:58'),
(602, 700, 'Masbate Department Store-TRA  ', '2022-07-06 18:53:58'),
(603, 702, 'Baao Property Mgt.MCD-Store   ', '2022-07-06 18:53:58'),
(604, 703, 'Baao MKP MCD-Store            ', '2022-07-06 18:53:58'),
(605, 704, 'Baao LCC Market Plus-Store    ', '2022-07-06 18:53:58'),
(606, 706, 'LaConsumidoresCentralNGDS-Whse', '2022-07-06 18:53:58'),
(607, 707, 'Baao Food Garden              ', '2022-07-06 18:53:58'),
(608, 705, 'Libertad Central Naga DS-Whse ', '2022-07-06 18:53:58'),
(609, 708, 'LaConsumidoresCentralLABDS-Whs', '2022-07-06 18:53:58'),
(610, 709, 'Lagonoy MTD Food Ops-Store    ', '2022-07-06 18:53:58'),
(611, 710, 'Masbate Dept. Store-TRA Whse  ', '2022-07-06 18:53:58'),
(612, 711, 'Libon Expressmart MCD-Store   ', '2022-07-06 18:53:58'),
(613, 712, 'Goa Department Store-CCN      ', '2022-07-06 18:53:58'),
(614, 713, 'Libon Expressmart MCD-Whse    ', '2022-07-06 18:53:58'),
(615, 714, 'Naga Expressmart BBN-Store    ', '2022-07-06 18:53:58'),
(616, 715, 'Naga Expressmart BBS-Store    ', '2022-07-06 18:53:58'),
(617, 716, 'Buhi Expressmart-Store        ', '2022-07-06 18:53:58'),
(618, 717, 'Iriga Expressmart-Store       ', '2022-07-06 18:53:58'),
(619, 719, 'Gubat MCL                     ', '2022-07-06 18:53:58'),
(620, 720, 'MO MCD-Store                  ', '2022-07-06 18:53:58'),
(621, 718, 'Lagonoy Supermarket - Store   ', '2022-07-06 18:53:58'),
(622, 721, 'Masbate Supermarket TRA-Store ', '2022-07-06 18:53:58'),
(623, 722, 'Pilar MCL                     ', '2022-07-06 18:53:58'),
(624, 723, 'Buhi Expressmart MCD-Store    ', '2022-07-06 18:53:58'),
(625, 724, 'Naga Expressmart BBN MCD-Store', '2022-07-06 18:53:58'),
(626, 726, 'IrigaExpressmartIRG2 MCD-Store', '2022-07-06 18:53:58'),
(627, 728, 'MasbateSupermarketTARMCD-Store', '2022-07-06 18:53:58'),
(628, 727, 'Lagonoy Supermarket MCD-Store ', '2022-07-06 18:53:58'),
(629, 725, 'Naga Expressmart BBS MCD-Store', '2022-07-06 18:53:58'),
(630, 729, 'MasbateSupermarketTAR MCD-Whse', '2022-07-06 18:53:58'),
(631, 730, 'Irosin Department Store       ', '2022-07-06 18:53:58'),
(632, 733, 'Irosin Dept Store-Legazpi Whse', '2022-07-06 18:53:58'),
(633, 734, 'Lagonoy Bakery Store          ', '2022-07-06 18:53:58'),
(634, 732, 'South Ocean Villa-Whse        ', '2022-07-06 18:53:58'),
(635, 731, 'South Ocean Villa             ', '2022-07-06 18:53:58'),
(636, 735, 'Calabanga Department Store    ', '2022-07-06 18:53:58'),
(637, 736, 'Irosin Supermarket MCD-Store  ', '2022-07-06 18:53:58'),
(638, 737, 'Irosin Supermarket MCD-Whse   ', '2022-07-06 18:53:58'),
(639, 738, 'TCL Central Sorsogon-Whse     ', '2022-07-06 18:53:58'),
(640, 742, 'Naga Expressmart GLU MCD-Store', '2022-07-06 18:53:58'),
(641, 740, 'Sorsogon Department Store-CTS ', '2022-07-06 18:53:58'),
(642, 741, 'Sorsogon DS-Legazpi Whse      ', '2022-07-06 18:53:58'),
(643, 739, 'Baao MCL                      ', '2022-07-06 18:53:58'),
(644, 743, 'Naga Expressmart GLU MCD-Whse ', '2022-07-06 18:53:58'),
(645, 744, 'Henlin Franchise MCD-Store    ', '2022-07-06 18:53:58'),
(646, 745, 'Sto. Domingo Expressmart Store', '2022-07-06 18:53:58'),
(647, 746, 'Sto. Domingo EMR MCD Store    ', '2022-07-06 18:53:58'),
(648, 747, 'Naga Supermarket VLA MCD-Store', '2022-07-06 18:53:58'),
(649, 748, 'ECL MCD-Store                 ', '2022-07-06 18:53:58'),
(650, 749, 'Legaspi Choobi-Choobi Store   ', '2022-07-06 18:53:58'),
(651, 750, 'Pioduran Department Store     ', '2022-07-06 18:53:58'),
(652, 751, 'Pioduran MTD Food Ops-Store   ', '2022-07-06 18:53:58'),
(653, 752, 'Pioduran MCL                  ', '2022-07-06 18:53:58'),
(654, 753, 'Pioduran Department Store-Whse', '2022-07-06 18:53:58'),
(655, 754, 'LRH MCD-Store                 ', '2022-07-06 18:53:58'),
(656, 755, 'Calabanga Supermarket-Store   ', '2022-07-06 18:53:59'),
(657, 758, 'CLB ECL BS1 Store             ', '2022-07-06 18:53:59'),
(658, 760, 'NG ECL CB3 Store              ', '2022-07-06 18:53:59'),
(659, 759, 'TA ECL CB2 Store              ', '2022-07-06 18:53:59'),
(660, 756, 'Calabanga Bentesilog MCD-Store', '2022-07-06 18:53:59'),
(661, 757, 'Bato Supermarket-Store        ', '2022-07-06 18:53:59'),
(662, 761, 'Daet Business Center          ', '2022-07-06 18:53:59'),
(663, 763, 'Legazpi Express Link MCD-Store', '2022-07-06 18:53:59'),
(664, 762, 'Daet Department Store- CCN    ', '2022-07-06 18:53:59'),
(665, 765, 'LionCentralLegazpiWhse (GMD B)', '2022-07-06 18:53:59'),
(666, 764, 'Tabaco LFR FG To Go Store     ', '2022-07-06 18:53:59'),
(667, 766, 'Calabanga Dept Store MCD-Store', '2022-07-06 18:53:59'),
(668, 767, 'LaConsumidoresCntrlLCWhs(GMDB)', '2022-07-06 18:53:59'),
(669, 768, 'Legaspi CN Hall LFR Store     ', '2022-07-06 18:53:59'),
(670, 771, 'Labo Business Center          ', '2022-07-06 18:53:59'),
(671, 772, 'ShopmoreCentralSORWhse (GMD B)', '2022-07-06 18:53:59'),
(672, 770, 'Tabaco LCC Market Plus-Store  ', '2022-07-06 18:53:59'),
(673, 769, 'Daet MCL                      ', '2022-07-06 18:53:59'),
(674, 774, 'Legaspi LFR Food Garden Store ', '2022-07-06 18:53:59'),
(675, 773, 'LibertadCentralPLIWhse (GMD B)', '2022-07-06 18:53:59'),
(676, 777, 'Pioduran LFR Breaktime Store  ', '2022-07-06 18:53:59'),
(677, 775, 'Legaspi LFR FG To Go Store    ', '2022-07-06 18:53:59'),
(678, 776, 'Naga LFR Food Garden Store    ', '2022-07-06 18:53:59'),
(679, 778, 'LaConsumidoresCentralPili-Whse', '2022-07-06 18:53:59'),
(680, 779, 'Naga LFR GLU Breaktime Store  ', '2022-07-06 18:53:59'),
(681, 780, 'Goa LFR Breaktime Store       ', '2022-07-06 18:53:59'),
(682, 782, 'Nabua PM MCD Whse             ', '2022-07-06 18:53:59'),
(683, 781, 'Nabua PM MCD Store            ', '2022-07-06 18:53:59'),
(684, 783, 'Libertad Central LC-Whse      ', '2022-07-06 18:53:59'),
(685, 784, 'Legazpi SPM-Repacking         ', '2022-07-06 18:53:59'),
(686, 785, 'Legazpi LBT-Repacking         ', '2022-07-06 18:53:59'),
(687, 786, 'CTC Business Center           ', '2022-07-06 18:53:59'),
(688, 787, 'Calabanga Dept Store 2-Whse   ', '2022-07-06 18:53:59'),
(689, 790, 'Polangui Dept. Store 3-Whse   ', '2022-07-06 18:53:59'),
(690, 789, 'Polangui Department Store 3   ', '2022-07-06 18:53:59'),
(691, 788, 'Tabaco MKP MCD-Store          ', '2022-07-06 18:53:59'),
(692, 791, 'Pili Business Center          ', '2022-07-06 18:53:59'),
(693, 793, 'Ligao Business Center         ', '2022-07-06 18:53:59'),
(694, 795, 'Polangui Dept Store3-MCD Store', '2022-07-06 18:53:59'),
(695, 796, 'Pili Whse LBT-Repacking       ', '2022-07-06 18:53:59'),
(696, 792, 'Legazpi MCL MCD-Store         ', '2022-07-06 18:53:59'),
(697, 794, 'Irosin Dept. Store MCD-Store  ', '2022-07-06 18:53:59'),
(698, 797, 'TCL Central MCD Tabaco-Whse   ', '2022-07-06 18:53:59'),
(699, 798, 'Pili Central CSM Warehouse    ', '2022-07-06 18:53:59'),
(700, 799, 'Pili Whse CSM-Repacking       ', '2022-07-06 18:53:59'),
(701, 801, 'Legazpi Home Plus-Store       ', '2022-07-06 18:53:59'),
(702, 800, 'Libmanan Supermarket-Store    ', '2022-07-06 18:53:59'),
(703, 802, 'Legazpi Home Plus-whse        ', '2022-07-06 18:53:59'),
(704, 803, 'Food Caravan Store            ', '2022-07-06 18:53:59'),
(705, 804, 'FO Canteen TA                 ', '2022-07-06 18:54:00'),
(706, 805, 'Libmanan SMR MCD-Store        ', '2022-07-06 18:54:00'),
(707, 806, 'Bula Expressmart Store        ', '2022-07-06 18:54:00'),
(708, 807, 'Tabaco LCC Home-Store         ', '2022-07-06 18:54:00'),
(709, 808, 'Tabaco LCC Home-Whse          ', '2022-07-06 18:54:00'),
(710, 809, 'Daet Expressmart- Store       ', '2022-07-06 18:54:00'),
(711, 812, 'MSG Naga MCD Whse             ', '2022-07-06 18:54:00'),
(712, 814, 'IMSI LC Main MCD-Store        ', '2022-07-06 18:54:00'),
(713, 811, 'MSG Legaspi MCD Whse          ', '2022-07-06 18:54:00'),
(714, 813, 'FTG LC MCD-Store              ', '2022-07-06 18:54:00'),
(715, 810, 'GLU LFR Food Train Store      ', '2022-07-06 18:54:00'),
(716, 815, 'CTC LC Main MCD-Store         ', '2022-07-06 18:54:00'),
(717, 816, 'MSVMI LC Main MCD-Store       ', '2022-07-06 18:54:00'),
(718, 818, 'Bula Expressmart MCD-Store    ', '2022-07-06 18:54:00'),
(719, 817, 'Donsol Expressmart- Store     ', '2022-07-06 18:54:00'),
(720, 819, 'LFI MCD Store                 ', '2022-07-06 18:54:00'),
(721, 820, 'TCL MO MCD-Store              ', '2022-07-06 18:54:00'),
(722, 821, 'TCL Central MCD Legaspi Whse  ', '2022-07-06 18:54:00'),
(723, 823, 'TCL Central MCD Naga Whse     ', '2022-07-06 18:54:00'),
(724, 822, 'TCL Central MCD Masbate Whse  ', '2022-07-06 18:54:00'),
(725, 824, 'LAC Central MCD Naga Whse     ', '2022-07-06 18:54:00'),
(726, 825, 'TCL Central MCD Sorsogon Whse ', '2022-07-06 18:54:00'),
(727, 826, 'Daet Expressmart MCD - Store  ', '2022-07-06 18:54:00'),
(728, 827, 'Polangui Department Store 2   ', '2022-07-06 18:54:00'),
(729, 828, 'Polangui Department Whse 2    ', '2022-07-06 18:54:00'),
(730, 829, 'Baao Business Center          ', '2022-07-06 18:54:00'),
(731, 832, 'Matacon Expressmart Store     ', '2022-07-06 18:54:00'),
(732, 830, 'Elite Best Choice Mang Inasal ', '2022-07-06 18:54:00'),
(733, 831, 'Donsol Expressmart MCD        ', '2022-07-06 18:54:00'),
(734, 833, 'Tinambac LCC Market Plus Store', '2022-07-06 18:54:00'),
(735, 835, 'Masbate LFR FG To Go Store    ', '2022-07-06 18:54:00'),
(736, 836, 'Polangui Department Store2 MCD', '2022-07-06 18:54:00'),
(737, 834, 'Polangui Supermarket 2 - Store', '2022-07-06 18:54:00'),
(738, 839, 'Polangui Bakery Store 2       ', '2022-07-06 18:54:00'),
(739, 837, 'LMSI MCD - Store              ', '2022-07-06 18:54:00'),
(740, 840, 'Bato Bakery Store             ', '2022-07-06 18:54:00'),
(741, 841, 'Baao Bakery Store             ', '2022-07-06 18:54:00'),
(742, 838, 'MSSI MCD - Store              ', '2022-07-06 18:54:00'),
(743, 843, 'LMS MCD - Store               ', '2022-07-06 18:54:01'),
(744, 844, 'DLCC MCD - Store              ', '2022-07-06 18:54:01'),
(745, 845, 'FRC MCD - Store               ', '2022-07-06 18:54:01'),
(746, 842, 'PM PLG 2                      ', '2022-07-06 18:54:01');
INSERT INTO `stores` (`id`, `store_code`, `store_desc`, `created_at`) VALUES
(747, 846, 'Pioduran Dept Store MCD -Store', '2022-07-06 18:54:01'),
(748, 847, 'Pioduran Dept Store MCD- Whse ', '2022-07-06 18:54:01'),
(749, 848, 'Matacon Expressmart MCD -Store', '2022-07-06 18:54:01'),
(750, 849, 'Polangui Supermarket2 MCD     ', '2022-07-06 18:54:01'),
(751, 852, 'Sangay Expressmart - Store    ', '2022-07-06 18:54:01'),
(752, 851, 'Tinambac LFR FG To Go         ', '2022-07-06 18:54:01'),
(753, 850, 'Tinambac Supermarket MCD Store', '2022-07-06 18:54:01'),
(754, 853, 'Tinambac Department Store     ', '2022-07-06 18:54:01'),
(755, 854, 'Tinambac Department Store-Whse', '2022-07-06 18:54:01'),
(756, 855, 'Tinambac Department Store MCD ', '2022-07-06 18:54:01'),
(757, 856, 'Tinambac DS MCD - Whse        ', '2022-07-06 18:54:01'),
(758, 859, 'Sangay Expressmart MCD - Store', '2022-07-06 18:54:01'),
(759, 858, 'Central Kitchen LFR Stockroom ', '2022-07-06 18:54:01'),
(760, 857, 'Central Kitchen LFR Finished P', '2022-07-06 18:54:01'),
(761, 860, 'Rizal Business Center         ', '2022-07-06 18:54:01'),
(762, 861, 'Pioduran Business Center      ', '2022-07-06 18:54:01'),
(763, 862, 'Tabaco IP Warehouse           ', '2022-07-06 18:54:01'),
(764, 863, 'Tabaco Annex                  ', '2022-07-06 18:54:01'),
(765, 867, 'SMR 3rd Party Tenants         ', '2022-07-06 18:54:01'),
(766, 865, 'LCC Phase 1 Annex             ', '2022-07-06 18:54:01'),
(767, 866, 'Manila Office                 ', '2022-07-06 18:54:01'),
(768, 864, 'Tabaco Annex 2                ', '2022-07-06 18:54:01'),
(769, 868, 'Cumadcad Expressmart Store    ', '2022-07-06 18:54:01'),
(770, 870, 'Tabaco Expressmart TGS-Store  ', '2022-07-06 18:54:01'),
(771, 871, 'Tabaco Expressmart TGS MCD-STR', '2022-07-06 18:54:01'),
(772, 873, 'Ragay Supermarket MCD-Store   ', '2022-07-06 18:54:01'),
(773, 869, 'Polangui 2 LFR FG To Go Store ', '2022-07-06 18:54:01'),
(774, 872, 'Ragay Supermarket - Store     ', '2022-07-06 18:54:01'),
(775, 874, 'GOA BUSINESS CENTER (HUB)     ', '2022-07-06 18:54:01'),
(776, 875, 'Cumadcad Expressmart MCD-Store', '2022-07-06 18:54:01'),
(777, 876, 'Jose Panganiban Supermarket-St', '2022-07-06 18:54:01'),
(778, 877, 'Jose Panganiban SMR MCD-Store ', '2022-07-06 18:54:01'),
(779, 878, 'Naga Supermarket SFL-Store    ', '2022-07-06 18:54:01'),
(780, 879, 'Naga Supermarket SFL MCD-Store', '2022-07-06 18:54:01'),
(781, 880, 'Daraga Expressmart TGS-Store  ', '2022-07-06 18:54:01'),
(782, 881, 'Daraga EMR TGS MCD-Store      ', '2022-07-06 18:54:01'),
(783, 882, 'CLB Business Center (HUB)     ', '2022-07-06 18:54:01'),
(784, 883, 'IGU Business Center (HUB)     ', '2022-07-06 18:54:01'),
(785, 884, 'Legazpi Macao Imperial Tea-STR', '2022-07-06 18:54:01'),
(786, 885, 'IRS BUSINESS CENTER (HUB)     ', '2022-07-06 18:54:01'),
(787, 886, 'BLN BUSINESS CENTER (HUB)     ', '2022-07-06 18:54:01'),
(788, 887, 'Placer Supermarket-Store      ', '2022-07-06 18:54:02'),
(789, 890, 'Bacacay Supermarket MCD-Store ', '2022-07-06 18:54:02'),
(790, 889, 'Bacacay Supermarket-Store     ', '2022-07-06 18:54:02'),
(791, 891, 'Tagkawayan Supermarket-Store  ', '2022-07-06 18:54:02'),
(792, 888, 'Placer Supermarket MCD-Store  ', '2022-07-06 18:54:02'),
(793, 892, 'Tagkawayan Supermarket MCD-Sto', '2022-07-06 18:54:02'),
(794, 893, 'Iriga PM MCD Store            ', '2022-07-06 18:54:02'),
(795, 897, 'MO MCD Whse                   ', '2022-07-06 18:54:02'),
(796, 896, 'Naga MCD Whse                 ', '2022-07-06 18:54:02'),
(797, 895, 'MSG Tabaco MCD Whse           ', '2022-07-06 18:54:02'),
(798, 894, 'Iriga PM MCD Whse             ', '2022-07-06 18:54:02'),
(799, 898, 'LFR Tabaco MCD Whse           ', '2022-07-06 18:54:02'),
(800, 901, 'DS SBU Support                ', '2022-07-06 18:54:02'),
(801, 902, 'PM SBU Support                ', '2022-07-06 18:54:02'),
(802, 899, 'LFR Legazpi MCD Whse          ', '2022-07-06 18:54:02'),
(803, 900, 'SMR SBU Support               ', '2022-07-06 18:54:02'),
(804, 903, 'FO SBU Support                ', '2022-07-06 18:54:02'),
(805, 904, 'FP-FC SBU Support             ', '2022-07-06 18:54:02'),
(806, 905, 'LFR Naga MCD Whse             ', '2022-07-06 18:54:02'),
(807, 907, 'EXL Legazpi MCD Whse          ', '2022-07-06 18:54:02'),
(808, 906, 'EXL Tabaco MCD Whse           ', '2022-07-06 18:54:02'),
(809, 908, 'LMR Tabaco MCD Whse           ', '2022-07-06 18:54:02'),
(810, 911, 'EXL Naga MCD Whse             ', '2022-07-06 18:54:02'),
(811, 909, 'LMR Legazpi MCD Whse          ', '2022-07-06 18:54:02'),
(812, 912, 'LMR Naga MCD Whse             ', '2022-07-06 18:54:02'),
(813, 986, 'TA MLD MCD Store              ', '2022-07-06 18:54:02'),
(814, 987, 'TA MLD MCD Whse               ', '2022-07-06 18:54:02'),
(815, 77251, 'MTD EMR TA HO                 ', '2022-07-06 18:54:02'),
(816, 88251, 'MTD FO TA HO                  ', '2022-07-06 18:54:02'),
(817, 77252, 'MTD EMR LC HO                 ', '2022-07-06 18:54:02'),
(818, 88252, 'MTD FO LC HO                  ', '2022-07-06 18:54:02'),
(819, 88253, 'MTD FO NG HO                  ', '2022-07-06 18:54:02'),
(820, 99100, 'Tabaco Liberty Comml Ctr Inc  ', '2022-07-06 18:54:02'),
(821, 99160, 'Liberty City Center, Inc.     ', '2022-07-06 18:54:02'),
(822, 99150, 'LCC Liberty Comml Ctr Inc     ', '2022-07-06 18:54:02'),
(823, 99170, 'Longrich Consumers Corporation', '2022-07-06 18:54:02'),
(824, 99180, 'Liferich Consolidated Corp.   ', '2022-07-06 18:54:02'),
(825, 99200, 'Licomcen Inc                  ', '2022-07-06 18:54:02'),
(826, 99260, 'FTG Food-To-Go Mktg.          ', '2022-07-06 18:54:03'),
(827, 99250, 'Central Metro Trade Dist, Inc ', '2022-07-06 18:54:03'),
(828, 99270, 'Elite Best Choice Cuisine, Inc', '2022-07-06 18:54:03'),
(829, 99300, 'ECL Foods and Catering Corp   ', '2022-07-06 18:54:03'),
(830, 99350, 'TA Central Metro Foods, Inc   ', '2022-07-06 18:54:03'),
(831, 99400, 'TCL Merchandise Brokerage Inc ', '2022-07-06 18:54:03'),
(832, 99450, 'Metroland Prop. and Mgt. Corp ', '2022-07-06 18:54:03'),
(833, 99460, 'Metroworld 101, Inc.          ', '2022-07-06 18:54:03'),
(834, 99500, 'Metro Star Superama, Inc.     ', '2022-07-06 18:54:03'),
(835, 99550, 'Metro Star Vision & Mgt Inc   ', '2022-07-06 18:54:03'),
(836, 99600, 'INACTIVE TA MFPI              ', '2022-07-06 18:54:03'),
(837, 99620, 'Metro Funds Providers Inc_1   ', '2022-07-06 18:54:03'),
(838, 99640, 'Luckytech Consolidated Co. Inc', '2022-07-06 18:54:03'),
(839, 99630, 'Luminary Consolidated Co. Inc.', '2022-07-06 18:54:03'),
(840, 99650, 'INACTIVE LC MOSCC             ', '2022-07-06 18:54:03'),
(841, 99660, 'Falkirk Resources Corp.       ', '2022-07-06 18:54:03'),
(842, 99670, 'Metrolink Credit and Loans,Inc', '2022-07-06 18:54:03'),
(843, 99680, 'Lion Tech Shared Services, Inc', '2022-07-06 18:54:03'),
(844, 99700, 'Lion Commercial Corporation   ', '2022-07-06 18:54:03'),
(845, 99720, 'LamverConsolidatdComplxDvtCor ', '2022-07-06 18:54:03'),
(846, 99726, 'Excellent MSG, Inc.           ', '2022-07-06 18:54:03'),
(847, 99729, 'Generation Marketing Corp     ', '2022-07-06 18:54:03'),
(848, 99730, 'Shopmore Commercial Corp.     ', '2022-07-06 18:54:03'),
(849, 99725, 'Infinitrics Mgt Specialist Inc', '2022-07-06 18:54:03'),
(850, 99740, 'Libertad Consumers Corp.      ', '2022-07-06 18:54:03'),
(851, 99750, 'Gerizim Realty Devt Corp-Rizal', '2022-07-06 18:54:03'),
(852, 99780, 'La Consumidores Corp.         ', '2022-07-06 18:54:03'),
(853, 99760, 'Laconcepcion Commercial Corp. ', '2022-07-06 18:54:03'),
(854, 99770, 'Big L Market, Inc.            ', '2022-07-06 18:54:03'),
(855, 99800, 'Limsa Commercial Corporation  ', '2022-07-06 18:54:04'),
(856, 99790, 'LCC Market Savers, Inc.       ', '2022-07-06 18:54:04'),
(857, 99820, 'L Market Plus, Inc.           ', '2022-07-06 18:54:04'),
(858, 99810, 'LCC Express Link, INC.        ', '2022-07-06 18:54:04'),
(859, 99900, 'LCC Foundation, Inc.          ', '2022-07-06 18:54:04'),
(860, 99910, 'Mayon Livelihood Dev Ctr Inc. ', '2022-07-06 18:54:04'),
(861, 3001, 'PLI SMR - DS GMD Whse         ', '2022-07-06 18:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `eeid` int(11) NOT NULL,
  `secret_key` varchar(100) NOT NULL,
  `access_lvl` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `eeid`, `secret_key`, `access_lvl`) VALUES
(1, 2285, '21232f297a57a5a743894a0e4a801fc3', 1),
(3, 1234, 'ee11cbb19052e40b07aac0ca060c23ee', 0),
(18, 5678, 'ee11cbb19052e40b07aac0ca060c23ee', 0),
(19, 1212, 'ee11cbb19052e40b07aac0ca060c23ee', 0),
(20, 5555, '6074c6aa3488f3c2dddff2a7ca821aab', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batchtransfer_download_logs_tbl`
--
ALTER TABLE `batchtransfer_download_logs_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batchtransfer_status_tbl`
--
ALTER TABLE `batchtransfer_status_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batchtransfer_tbl`
--
ALTER TABLE `batchtransfer_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iupc_tbl`
--
ALTER TABLE `iupc_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `received_batch_trf_tbl`
--
ALTER TABLE `received_batch_trf_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `store_code` (`store_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eeid` (`eeid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batchtransfer_download_logs_tbl`
--
ALTER TABLE `batchtransfer_download_logs_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `batchtransfer_status_tbl`
--
ALTER TABLE `batchtransfer_status_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `batchtransfer_tbl`
--
ALTER TABLE `batchtransfer_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iupc_tbl`
--
ALTER TABLE `iupc_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `received_batch_trf_tbl`
--
ALTER TABLE `received_batch_trf_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5167;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
