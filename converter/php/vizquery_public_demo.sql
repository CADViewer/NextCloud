-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 30 mars 2017 kl 15:35
-- Serverversion: 10.1.21-MariaDB
-- PHP-version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `vizquery_public_demo`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `floor_id` int(11) DEFAULT NULL,
  `org2_id` int(11) DEFAULT NULL,
  `employee_id` varchar(25) DEFAULT NULL,
  `employeeName` varchar(50) DEFAULT NULL,
  `extension` char(10) DEFAULT NULL,
  `phone` char(10) DEFAULT NULL,
  `hotel` tinyint(4) DEFAULT NULL,
  `security_card` int(11) DEFAULT NULL,
  `employee_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `employees`
--

INSERT INTO `employees` (`id`, `room_id`, `floor_id`, `org2_id`, `employee_id`, `employeeName`, `extension`, `phone`, `hotel`, `security_card`, `employee_number`) VALUES
(1, 1, 1, 1, 'PARTRIDGE_ALAN', 'Alan Partridge', '4629', '377-4629', 0, 1, 9998),
(2, 2, 1, 1, 'AGUILERA_CHRISTINA', 'Christina Aguilera', '5770', '377-5770', 0, 2, 9997),
(3, 4, 1, 3, 'BLOOGS_JOE', 'Joe Bloogs', '4962', '377-4962', 0, 3, 9996),
(4, 5, 1, 3, 'ABBOT_ALAN', 'Alan Abbot', '6502', '377-6502', 0, 4, 9995),
(5, 6, 1, 4, 'ABRAHAM_ALAN', 'Alan Abraham', '7622', '377-7622', 0, 5, 9994),
(6, 8, 1, 5, 'Washington_Denzel', 'Denzil Washington', '8603', '377-8603', 0, 6, 9993),
(7, 9, 1, 4, 'ANDERSEN_MIKE', 'Mike Andersen', '1151', '377-1151', 0, 7, 9992),
(8, 10, 1, 6, 'MCLEOD_ANDREW', 'Andrew McLoed', '5944', '377-5944', 0, 8, 9991),
(9, 12, 1, 7, 'BAKER_TIM', 'Tim Baker', '7266', '377-7266', 0, 9, 9990),
(10, 13, 1, 8, 'WILLIAMS_ANDY', 'Andy Williams', '8500', '377-8500', 0, 10, 9989),
(11, 18, 1, 1, 'PHILLIPS_TERRY', 'Terry Phillips', '1703', '377-1703', 0, 11, 9988),
(12, 19, 1, 5, 'BUDWIESER_BARRY', 'Bary Budwieser', '9014', '377-9014', 0, 12, 9987),
(13, 20, 1, 9, 'CORR_ANDREA', 'Andrea Corr', '2959', '377-2959', 0, 13, 9986),
(14, 21, 1, 10, 'EDWARDS_MICHAEL', 'MICHAEL EDWARDS', '4756', '377-4756', 0, 14, 9985),
(15, 23, 1, 4, 'BEER_STELLA', 'Stella Beer', '4900', '377-4900', 0, 15, 9984),
(16, 24, 1, 10, 'EISENBERG_NEVILLE', 'NEVILLE EISENBERG', '9235', '377-9235', 0, 16, 9983),
(17, 25, 1, 10, 'EK_MARI', 'MARI EK', '3472', '377-3472', 0, 17, 9982),
(18, 26, 1, 10, 'ELIA_STELIOS', 'STELIOS ELIA', '6655', '377-6655', 0, 18, 9981),
(19, 27, 1, 10, 'ELIASOV_AVITAL', 'AVITAL ELIASOV', '3861', '377-3861', 0, 19, 9980),
(20, 28, 1, 10, 'ELWELL_SELINA', 'SELINA ELWELL', '7339', '377-7339', 0, 20, 9979),
(21, 29, 1, 10, 'EPISSINA_NATASHA', 'NATASHA EPISSINA', '6114', '377-6114', 0, 21, 9978),
(22, 30, 1, 8, 'BROWNE_JACK', 'Jack Browne', '7550', '377-7550', 0, 22, 9977),
(23, 31, 1, 11, 'DE NOBLET_GILLES', 'GILLES DE NOBLET', '9408', '377-9408', 0, 23, 9976),
(24, 32, 1, 10, 'ERHAHON_JACKY', 'JACKY ERHAHON', '5392', '377-5392', 0, 24, 9975),
(25, 34, 1, 10, 'ESCOBAR_JOHNY', 'JOHNY ESCOBAR', '6736', '377-6736', 0, 25, 9974),
(26, 36, 1, 6, 'CLARKSON_BOB', 'Bob Clarkson', '7505', '377-7505', 0, 26, 9973),
(27, 37, 1, 7, 'MONTALO_CONNEY', 'Conney Montalo', '7314', '377-7314', 0, 27, 9972),
(29, 41, 1, 12, 'DE SILVA_MARC', 'MARC DeSILVA', '6347', '377-6347', 0, 29, 9970),
(50, 65, 1, 2, 'None', 'Not Occupied', '', '', 0, 50, 0),
(51, 66, 1, 2, 'None', 'Not Occupied', '', '', 0, 51, 0),
(52, 67, 1, 2, 'None', 'Not Occupied', '', '', 0, 52, 0),
(53, 68, 1, 2, 'None', 'Not Occupied', '', '', 0, 53, 0),
(54, 69, 1, 2, 'None', 'Not Occupied', '', '', 0, 54, 0),
(55, 70, 1, 2, 'None', 'Not Occupied', '', '', 0, 55, 0),
(56, 71, 1, 2, 'None', 'Not Occupied', '', '', 0, 56, 0),
(57, 72, 1, 2, 'None', 'Not Occupied', '', '', 0, 57, 0),
(58, 73, 1, 2, 'None', 'Not Occupied', '', '', 0, 58, 0),
(60, 75, 1, 2, 'None', 'Not Occupied', '', '', 0, 60, 0),
(61, 77, 1, 2, 'None', 'Not Occupied', '', '', 0, 61, 0),
(62, 78, 1, 2, 'None', 'Not Occupied', '', '', 0, 62, 0),
(63, 79, 1, 2, 'None', 'Not Occupied', '', '', 0, 63, 0),
(64, 80, 1, 2, 'None', 'Not Occupied', '', '', 0, 64, 0),
(67, 83, 1, 22, 'FIGUEROA_ROBERTO', 'ROBERTO FIGUEROA', '2305', '377-2305', 0, 67, 9932),
(68, 84, 1, 23, 'HALLWAY_01', 'Main Hallway', '9918', '', 0, 68, 0),
(69, 85, 1, 22, 'FLURIO RUBES_ERIKA', 'ERIKA FLURIO RUBES', '5677', '377-5677', 0, 69, 9930),
(70, 86, 1, 24, 'FORDE_HALEY', 'HALEY FORDE', '6630', '377-6630', 0, 70, 9929),
(71, 87, 1, 2, 'GILL_ALEC_ALAN', 'Restrooms Men', '6118', '377-6118', 0, 71, 9928),
(72, 88, 1, 2, 'FRANCIS_JIMMY', 'Restrooms Women', '9701', '377-9701', 0, 72, 9927);

-- --------------------------------------------------------

--
-- Tabellstruktur `org2s`
--

CREATE TABLE `org2s` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `org1_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `org2s`
--

INSERT INTO `org2s` (`id`, `name`, `color_id`, `org1_id`) VALUES
(1, 'CORP_EXECUTIVES', 1, 1),
(2, 'NONE', 2, 2),
(3, 'FINANCE SYSTEMS', 3, 3),
(4, 'CORP_LEGAL', 4, 1),
(5, 'I.T. ENG', 5, 4),
(6, 'PROD_LOGISTICS', 6, 5),
(7, 'I.T. TECH', 7, 4),
(8, 'I.T. TRAINING', 8, 4),
(9, 'UK IT', 9, 6),
(10, 'POS_DEPT', 10, 7),
(11, 'ATM_DEPT_001', 11, 8),
(12, 'PROD_ENGINEERING', 12, 5),
(13, 'CONSULT_TRAINING', 13, 9),
(14, 'SALES_J', 14, 10),
(15, 'SALES_D', 15, 10),
(16, 'SUPPORT_SERVICES', 16, 11),
(17, 'SALES_AFRICA', 17, 10),
(18, 'CONSULT_SERVICES', 18, 9),
(19, 'SALES_F', 19, 10),
(20, 'SUPPORT_FM', 20, 12),
(21, 'CORP_ACCOUNTS', 21, 1),
(22, 'I.T.', 22, 4),
(23, 'MARKETING', 23, 13),
(24, 'FINANCE', 24, 14),
(25, 'PROD_DEV', 25, 5),
(26, 'CONSULT_SUPPORT', 26, 9),
(27, 'ATM_DEPT_002', 27, 8),
(28, 'ATM_DEPT_003', 28, 8),
(29, 'MERCH_SERV_001', 29, 15),
(30, 'SALES_SK', 30, 10),
(31, 'SALES', 31, 16),
(32, 'CONTACT_DEPT_002', 32, 17),
(33, 'CONTACT_DEPT_001', 33, 17),
(34, 'ADMINISTRATION', 34, 18),
(35, 'PURCHASING', 35, 12),
(36, 'SUPPORT_TECH', 36, 12),
(37, 'OPERATIONS-MAINT', 37, 18),
(38, 'PRODUCTION', 38, 18),
(39, 'MANAGEMENT', 39, 20),
(40, 'DOMESTIC', 40, 3),
(41, 'MID ATLANTIC', 41, 21),
(42, 'MANAGER', 42, 10),
(43, 'REAL ESTATE MGMT', 43, 22),
(44, 'PLANNING', 44, 22),
(45, 'ENVIRON-SAFETY', 45, 22),
(46, 'REGIONAL', 46, 22),
(47, 'CONSTRUCTION', 47, 22),
(48, 'NEW ENGLAND', 48, 21),
(49, 'ACCOUNTS', 49, 12),
(50, 'FIELD', 50, 10),
(51, 'INTERNAL', 51, 10);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `org2s`
--
ALTER TABLE `org2s`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=323;
--
-- AUTO_INCREMENT för tabell `org2s`
--
ALTER TABLE `org2s`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
