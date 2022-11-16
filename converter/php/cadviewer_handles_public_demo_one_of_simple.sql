-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 12 nov 2020 kl 11:46
-- Serverversion: 10.1.30-MariaDB
-- PHP-version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `cadviewer_handles_public_demo`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `one_of_simple`
--

CREATE TABLE `one_of_simple` (
  `ID` int(11) NOT NULL,
  `CV_ID` varchar(100) DEFAULT NULL,
  `Handle` varchar(100) DEFAULT NULL,
  `Entity` varchar(100) DEFAULT NULL,
  `Block` varchar(100) DEFAULT NULL,
  `CustomGroup1` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `one_of_simple`
--

INSERT INTO `one_of_simple` (`ID`, `CV_ID`, `Handle`, `Entity`, `Block`, `CustomGroup1`) VALUES
(1, 'cv_2', '12A', 'Polyline', '', ''),
(2, 'cv_3', '12E', 'Spline', '', ''),
(3, 'cv_4', '135', 'Polyline', '', 'Group01'),
(4, 'cv_5', '139', 'Polyline', '', 'Group01'),
(5, 'cv_6', '157', '3dPolyline', '', ''),
(6, 'cv_7', '15E', 'Mline', '', 'Group02'),
(7, 'cv_8', '12B', 'Line', '', 'Group02'),
(8, 'cv_9', '138', 'Hatch', '', 'Group02'),
(9, 'cv_10', '130', 'Arc', '', ''),
(10, 'cv_11', '12C', 'Circle', '', ''),
(11, 'cv_12', '12D', 'Ellipse', '', ''),
(12, 'cv_13', '143', 'Text', '', ''),
(13, 'cv_14', '144', 'MText', '', ''),
(14, 'cv_15', '144', 'MText', '', ''),
(15, 'cv_17', '14A', 'Polyline', 'Block1_1', 'Group03'),
(16, 'cv_18', '149', 'Circle', 'Block1_1', 'Group03'),
(17, 'cv_19', '14C', 'Attribute', 'Block1_1', 'Group03'),
(18, 'cv_21', '14A', 'Polyline', 'Block1_2', 'Group03'),
(19, 'cv_22', '149', 'Circle', 'Block1_2', 'Group03'),
(20, 'cv_23', '155', 'Attribute', 'Block1_2', 'Group03');

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `one_of_simple`
--
ALTER TABLE `one_of_simple`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `one_of_simple`
--
ALTER TABLE `one_of_simple`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
