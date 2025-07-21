-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2025 at 10:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `learnsphere`
--
CREATE DATABASE IF NOT EXISTS `learnsphere` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `learnsphere`;

-- --------------------------------------------------------

--
-- Table structure for table `categorii`
--

CREATE TABLE `categorii` (
  `categorieID` int(11) NOT NULL,
  `materieID` int(11) NOT NULL,
  `nume` varchar(255) NOT NULL,
  `descriere` varchar(255) DEFAULT NULL,
  `dataPostarii` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorii`
--

INSERT INTO `categorii` (`categorieID`, `materieID`, `nume`, `descriere`, `dataPostarii`) VALUES
(1, 1, 'Derivare', 'Derivarea este un concept fundamental al analizei matematice, ce reprezintă operația de aflare a derivatei unei funcții.', '2025-05-21'),
(4, 2, 'Transformari de stare de agregare', '', '2025-05-21'),
(7, 3, 'Aminoacizi', '', '2025-05-22'),
(8, 4, 'Grupe sanguine', '', '2025-05-22'),
(10, 1, 'Numere complexe', '', '2025-07-13');

-- --------------------------------------------------------

--
-- Table structure for table `comentarii`
--

CREATE TABLE `comentarii` (
  `comentariuID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `materialID` int(11) DEFAULT NULL,
  `intrebareID` int(11) DEFAULT NULL,
  `comentariu` varchar(255) NOT NULL,
  `dataPostarii` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comentarii`
--

INSERT INTO `comentarii` (`comentariuID`, `userID`, `materialID`, `intrebareID`, `comentariu`, `dataPostarii`) VALUES
(1, 1, 4, NULL, 'M-a ajutat foarte mult!', '2025-05-22 21:35:42'),
(2, 2, 4, NULL, 'Minunat!', '2025-05-22 21:47:02'),
(3, 3, 4, NULL, 'Informativ!', '2025-05-22 21:50:04');

-- --------------------------------------------------------

--
-- Table structure for table `dislikes`
--

CREATE TABLE `dislikes` (
  `dislikeID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `materialID` int(11) DEFAULT NULL,
  `intrebareID` int(11) DEFAULT NULL,
  `comentariuID` int(11) DEFAULT NULL,
  `dataPostarii` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dislikes`
--

INSERT INTO `dislikes` (`dislikeID`, `userID`, `materialID`, `intrebareID`, `comentariuID`, `dataPostarii`) VALUES
(5, 8, 4, NULL, NULL, '2025-05-22'),
(54, 36, 10, NULL, NULL, '2025-07-20'),
(55, 36, 2, NULL, NULL, '2025-07-20'),
(56, 12, 11, NULL, NULL, '2025-07-20'),
(58, 12, 8, NULL, NULL, '2025-07-20'),
(59, 12, 5, NULL, NULL, '2025-07-20'),
(60, 30, 5, NULL, NULL, '2025-07-20'),
(61, 30, 3, NULL, NULL, '2025-07-20'),
(62, 34, 9, NULL, NULL, '2025-07-20'),
(63, 34, 4, NULL, NULL, '2025-07-20'),
(64, 34, 5, NULL, NULL, '2025-07-20'),
(66, 1, 10, NULL, NULL, '2025-07-20');

-- --------------------------------------------------------

--
-- Table structure for table `folowers`
--

CREATE TABLE `folowers` (
  `followerID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `followingID` int(11) NOT NULL,
  `dataPostarii` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `intrebari`
--

CREATE TABLE `intrebari` (
  `intrebareID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `intrebare` varchar(255) NOT NULL,
  `categorieID` int(11) NOT NULL,
  `detalii` varchar(255) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `dataPostarii` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `intrebari`
--

INSERT INTO `intrebari` (`intrebareID`, `userID`, `intrebare`, `categorieID`, `detalii`, `material`, `dataPostarii`) VALUES
(3, 1, 'Ce sunt aminoacizii?', 7, '', NULL, '2025-05-22 00:00:00'),
(4, 1, 'Care sunt grupele de sânge?', 8, '', NULL, '2025-05-22 00:00:00'),
(6, 30, 'Ce înseamnă derivata unei funcții într-un punct?', 1, '', NULL, '2025-07-20 03:56:49'),
(7, 30, 'Ce rol are derivata a doua într-o problemă?', 1, '', NULL, '2025-07-20 03:57:32'),
(8, 30, 'Cum se interpretează geometric derivata?', 1, '', NULL, '2025-07-20 03:57:47'),
(9, 30, 'Ce este un aminoacid? Care este structura generală?', 7, '', NULL, '2025-07-20 03:58:04'),
(10, 32, 'Ce rol biologic au aminoacizii?', 7, '', NULL, '2025-07-20 03:58:46'),
(11, 32, 'De ce glicina este diferită de ceilalți aminoacizi?', 7, '', NULL, '2025-07-20 03:58:55'),
(12, 32, 'Ce este o proteină? Din ce este formată?', 7, '', NULL, '2025-07-20 03:59:08'),
(13, 35, 'Ce este factorul Rh?', 8, '', NULL, '2025-07-20 03:59:37'),
(14, 35, 'Cum se determină grupa sanguină?', 8, '', NULL, '2025-07-20 03:59:47'),
(15, 37, 'Ce diferență există între vaporizare și evaporare?', 4, '', NULL, '2025-07-20 04:00:29'),
(16, 37, 'Ce factori influențează temperatura de fierbere a unui lichid?', 4, '', NULL, '2025-07-20 04:00:41'),
(17, 34, 'Ce transformare de fază are loc la topirea gheții?', 4, '', NULL, '2025-07-20 04:01:15');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `likeID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `materialID` int(11) DEFAULT NULL,
  `intrebareID` int(11) DEFAULT NULL,
  `comentariuID` int(11) DEFAULT NULL,
  `dataPostarii` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`likeID`, `userID`, `materialID`, `intrebareID`, `comentariuID`, `dataPostarii`) VALUES
(15, 2, 4, NULL, NULL, '2025-05-22'),
(16, 2, 3, NULL, NULL, '2025-05-22'),
(17, 2, 2, NULL, NULL, '2025-05-22'),
(19, 8, 2, NULL, NULL, '2025-05-22'),
(20, 8, 3, NULL, NULL, '2025-05-22'),
(60, 3, NULL, NULL, 3, '2025-05-22'),
(63, 3, 2, NULL, NULL, '2025-05-22'),
(64, 3, 4, NULL, NULL, '2025-05-22'),
(78, 1, NULL, NULL, 2, '2025-07-18'),
(79, 1, 2, NULL, NULL, '2025-07-18'),
(81, 1, NULL, NULL, 1, '2025-07-19'),
(89, 1, 3, NULL, NULL, '2025-07-20'),
(90, 1, NULL, NULL, 3, '2025-07-20'),
(92, 1, 5, NULL, NULL, '2025-07-20'),
(95, 1, 4, NULL, NULL, '2025-07-20'),
(97, 1, NULL, 4, NULL, '2025-07-20'),
(98, 2, 7, NULL, NULL, '2025-07-20'),
(99, 36, 12, NULL, NULL, '2025-07-20'),
(100, 36, 11, NULL, NULL, '2025-07-20'),
(101, 36, 9, NULL, NULL, '2025-07-20'),
(102, 36, 8, NULL, NULL, '2025-07-20'),
(103, 36, 7, NULL, NULL, '2025-07-20'),
(104, 36, 4, NULL, NULL, '2025-07-20'),
(105, 36, 5, NULL, NULL, '2025-07-20'),
(106, 36, 3, NULL, NULL, '2025-07-20'),
(108, 2, 12, NULL, NULL, '2025-07-20'),
(109, 2, 10, NULL, NULL, '2025-07-20'),
(110, 2, 9, NULL, NULL, '2025-07-20'),
(111, 2, 11, NULL, NULL, '2025-07-20'),
(112, 2, 8, NULL, NULL, '2025-07-20'),
(113, 2, 5, NULL, NULL, '2025-07-20'),
(114, 12, 12, NULL, NULL, '2025-07-20'),
(115, 12, 9, NULL, NULL, '2025-07-20'),
(116, 12, 10, NULL, NULL, '2025-07-20'),
(117, 12, 7, NULL, NULL, '2025-07-20'),
(118, 12, 4, NULL, NULL, '2025-07-20'),
(119, 12, 3, NULL, NULL, '2025-07-20'),
(120, 12, 2, NULL, NULL, '2025-07-20'),
(121, 30, 12, NULL, NULL, '2025-07-20'),
(122, 30, 11, NULL, NULL, '2025-07-20'),
(123, 30, 10, NULL, NULL, '2025-07-20'),
(124, 30, 9, NULL, NULL, '2025-07-20'),
(125, 30, 4, NULL, NULL, '2025-07-20'),
(126, 30, 7, NULL, NULL, '2025-07-20'),
(127, 30, 8, NULL, NULL, '2025-07-20'),
(128, 30, 2, NULL, NULL, '2025-07-20'),
(129, 34, 12, NULL, NULL, '2025-07-20'),
(130, 34, 11, NULL, NULL, '2025-07-20'),
(131, 34, 10, NULL, NULL, '2025-07-20'),
(132, 34, 8, NULL, NULL, '2025-07-20'),
(133, 1, 12, NULL, NULL, '2025-07-20'),
(135, 1, 9, NULL, NULL, '2025-07-20'),
(136, 1, NULL, 17, NULL, '2025-07-20');

-- --------------------------------------------------------

--
-- Table structure for table `materiale`
--

CREATE TABLE `materiale` (
  `materialID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `titlu` varchar(255) NOT NULL,
  `categorieID` int(11) NOT NULL,
  `descriere` varchar(255) DEFAULT NULL,
  `cuvinteCheie` varchar(255) DEFAULT NULL,
  `material` varchar(255) NOT NULL,
  `dataPostarii` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materiale`
--

INSERT INTO `materiale` (`materialID`, `userID`, `titlu`, `categorieID`, `descriere`, `cuvinteCheie`, `material`, `dataPostarii`) VALUES
(2, 1, 'Tabel cu derivate', 1, 'Tabelul cu derivatele functiilor', 'derivate, formule', 'formule derivate.jpg', '2025-05-22 15:43:00'),
(3, 2, 'Fuzionare și Înghețare', 4, '', 'fuzionare, înghețare', 'Fuzionare și Înghețare.docx', '2025-05-22 17:43:00'),
(4, 8, 'Vaporizare și Lichefiere', 4, '', '', 'Vaporizare și Lichefiere.docx', '2025-05-22 19:11:07'),
(5, 1, 'Compatibilitatea grupelor de sânge', 8, 'Imaginea explică într-un mod vizual clar care grupe de sânge pot dona și primi una de la cealaltă, fiind un ghid util pentru înțelegerea compatibilității în transfuzia de sânge.', '', 'grupe sanguine.png', '2025-07-13 21:00:15'),
(7, 2, 'Evaporarea și condensarea', 4, '', '', 'Evaporare și Condensare.docx', '2025-07-20 03:12:30'),
(8, 2, 'Topirea și solidificarea', 4, '', '', 'Topirea si solidificarea.docx', '2025-07-20 03:13:20'),
(9, 11, 'Sublimare și depunere', 4, '', '', 'Sublimare și Depunere.docx', '2025-07-20 03:14:35'),
(10, 11, 'Derivata unei funcții într-un punct', 1, 'Acest material explică noțiunea de derivată într-un punct ca fiind limita unui raport de variație, care exprimă viteza de schimbare a unei funcții într-un anumit punct. ', '', 'Derivata unei funcții într-un punct.docx', '2025-07-20 03:33:07'),
(11, 36, 'Derivabilitate și continuitate', 1, 'Materialul explică diferența și legătura dintre continuitatea și derivabilitatea unei funcții într-un punct.', '', 'Derivabilitate și Continuitate.docx', '2025-07-20 03:41:38'),
(12, 36, 'Derivate laterale', 1, '', '', 'Derivate laterale.docx', '2025-07-20 03:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `materii`
--

CREATE TABLE `materii` (
  `materiiID` int(11) NOT NULL,
  `nume` varchar(255) NOT NULL,
  `descriere` varchar(255) DEFAULT NULL,
  `dataPostarii` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materii`
--

INSERT INTO `materii` (`materiiID`, `nume`, `descriere`, `dataPostarii`) VALUES
(1, 'Matematica', NULL, '2025-05-04'),
(2, 'Fizica', NULL, '2025-05-06'),
(3, 'Chimie', NULL, '2025-05-07'),
(4, 'Biologie', NULL, '2025-05-07'),
(5, 'Informatică', 'Informatica este știința prelucrării sistematice a informației, în special prin intermediul calculatoarelor.', '2025-07-13');

-- --------------------------------------------------------

--
-- Table structure for table `resetare_parola`
--

CREATE TABLE `resetare_parola` (
  `resetareID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `dataExpirarii` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resetare_parola`
--

INSERT INTO `resetare_parola` (`resetareID`, `userID`, `token`, `dataExpirarii`) VALUES
(3, 38, '499de5142d9263f72aa992c2b5fe22418bfd32a9ea69ab5e0456fd9a2ce9b43c', '2025-07-19 02:25:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `rol` int(3) NOT NULL,
  `parola` varchar(255) NOT NULL,
  `pozaProfil` varchar(255) NOT NULL,
  `nume` varchar(50) DEFAULT NULL,
  `prenume` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `dataNasterii` date NOT NULL,
  `dataInregistrarii` date NOT NULL,
  `biografie` varchar(255) DEFAULT NULL,
  `darkMode` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `rol`, `parola`, `pozaProfil`, `nume`, `prenume`, `email`, `dataNasterii`, `dataInregistrarii`, `biografie`, `darkMode`) VALUES
(1, 'admin', 1, '$2y$10$be/3CHiF6G5zXcRKaZscCOB8ccDNo9VizTmNeKbSSCTRmpuyo8Ecy', 'pfp.jpg', '', '', 'admin@gmail.com', '1983-01-01', '2025-05-11', NULL, 0),
(2, 'Gigel', 2, '$2y$10$NmTcF64snIDw5SAomR.PHeJ581ICs3lGTyUGKW6l8hxUzRr83U21W', 'default.jpg', 'Popescu', 'Gigel', 'gigel.popescu@gmail.com', '1988-01-12', '2025-05-15', NULL, 0),
(3, 'Andrei', 2, '$2y$10$myQqGGokJvLc7PeOksWy1.0mZhVQLg6rvZ9Yvv.CWCZ/2FG1nbkZa', 'default.jpg', 'Ion', 'Andrei', 'andrei.ion@gmail.com', '1993-02-16', '2025-05-15', NULL, 0),
(8, 'admin3', 1, '$2y$10$m.oq1q2x1CIBYYfb5fRQL.UPK8PMlQOaY7ZSEqhp0FD3aycrUt1YG', 'default.jpg', 'Admin', 'Admin', 'admin3@gmail.com', '1998-11-11', '2025-05-16', NULL, 0),
(11, 'Mihai', 2, '$2y$10$37EDDHCT5pwlFnPswygCn.HBgWRWkGIuUOIQ1oHsH33hlr2vR2zV6', 'default.jpg', 'George', 'Mihai', 'mihai.george@gmail.com', '1991-01-16', '2025-07-14', NULL, 0),
(12, 'Matei', 2, '$2y$10$ywZ4wAdXvsZk4U/EvyHm/evqTfSsdw4FBFV20JUySFtzVBrP87886', 'default.jpg', 'Georgescu', 'Matei', 'matei.george@gmail.com', '2000-07-11', '2025-07-14', NULL, 0),
(30, 'Alexandru', 2, '$2y$10$YJ3HKm/.Gm0/475skNceYO7pDYuuzwRXCUvXlWGggDBktxbYLV916', 'default.jpg', 'Radu ', 'Alexandru', 'alexandru.radu@gmail.com', '1990-12-05', '2025-07-16', NULL, 0),
(31, 'Ștefan', 2, '$2y$10$QdfP5noz32WchdnoSKyieuYh/YWFU7/GRU8otA.dvO1H5t36HeZXi', 'default.jpg', 'Dumitrescu ', 'Ștefan', 'stefan.dumitrescu@gmail.com', '2001-10-02', '2025-07-16', NULL, 0),
(32, 'Daniela', 2, '$2y$10$IIGh2o2YxdkGH0RPJ9MVh.GS1ptXTHM7TPymjlI0Ze3afQefMYY9W', 'default.jpg', 'Neagu ', 'Daniela', 'daniela.neagu@gmail.com', '2000-10-10', '2025-07-16', NULL, 0),
(33, 'Adriana', 2, '$2y$10$u7MA9OPvSlOEkiIFF9T6ROvY.z0UzvkA9cLXE63DNnPXdgKbZUXlK', 'default.jpg', 'Barbu ', 'Adriana', 'adriana.barbu@gmail.com', '1997-07-17', '2025-07-16', NULL, 0),
(34, 'Simona', 2, '$2y$10$WjkJtm.pcqbBxE4k5nI5juqVevnRLcDGzqMXzPFWnNq85vUsIDPju', 'pfp_687d53c032f246.00119628.jpg', 'Gheorghe', 'Simona', 'simona.gheorghiu@gmail.com', '2000-12-06', '2025-07-16', NULL, 0),
(35, 'Nicolae', 2, '$2y$10$y/Yl0axkGR0Kb2RzG778xuhsQY4iWZNYrFVE8TV3UAJ4aTIs3A33m', 'default.jpg', 'Enache ', 'Nicolae', 'nicolae.enache@gmail.com', '1992-07-16', '2025-07-16', NULL, 0),
(36, 'Gabriel', 2, '$2y$10$I9ST3Oh6xPlW5951qNAuJOlXrW2r2v0eAg87NSIza3eUEAw7fNqri', 'default.jpg', 'Dinu ', 'Gabriel', 'gabriel.dinu@gmail.com', '1998-01-16', '2025-07-16', NULL, 0),
(37, 'Roxana', 2, '$2y$10$KIA78FDDEGdr0NxhCCvsLujIbkOY4FcB18IbQVFvvToZsKk81iwBu', 'pfp_687d554aa49c81.81139681.jpg', 'Stoica ', 'Roxana', 'roxana.stoica@gmail.com', '1998-10-14', '2025-07-16', NULL, 0),
(38, 'Ioana', 2, '$2y$10$96aSUjhM2ah5NI0p14LwIulyQuZDaMZtqr7n6s/JLzShSHAmKMKTa', 'default.jpg', 'P', 'Ioana', 'ioana@gmail.com', '2007-04-23', '2025-07-19', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorii`
--
ALTER TABLE `categorii`
  ADD PRIMARY KEY (`categorieID`),
  ADD UNIQUE KEY `nume` (`nume`),
  ADD KEY `materieID` (`materieID`);

--
-- Indexes for table `comentarii`
--
ALTER TABLE `comentarii`
  ADD PRIMARY KEY (`comentariuID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `materialID` (`materialID`),
  ADD KEY `intrebareID` (`intrebareID`);

--
-- Indexes for table `dislikes`
--
ALTER TABLE `dislikes`
  ADD PRIMARY KEY (`dislikeID`),
  ADD KEY `materialID` (`materialID`),
  ADD KEY `intrebareID` (`intrebareID`),
  ADD KEY `comentariuID` (`comentariuID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `folowers`
--
ALTER TABLE `folowers`
  ADD PRIMARY KEY (`followerID`),
  ADD UNIQUE KEY `followersID` (`userID`),
  ADD UNIQUE KEY `followingID` (`followingID`);

--
-- Indexes for table `intrebari`
--
ALTER TABLE `intrebari`
  ADD PRIMARY KEY (`intrebareID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `categorie` (`categorieID`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeID`),
  ADD KEY `materialID` (`materialID`),
  ADD KEY `intrebareID` (`intrebareID`),
  ADD KEY `comentariuID` (`comentariuID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `materiale`
--
ALTER TABLE `materiale`
  ADD PRIMARY KEY (`materialID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `categorie` (`categorieID`),
  ADD KEY `categorieID` (`categorieID`);

--
-- Indexes for table `materii`
--
ALTER TABLE `materii`
  ADD PRIMARY KEY (`materiiID`),
  ADD UNIQUE KEY `nume` (`nume`);

--
-- Indexes for table `resetare_parola`
--
ALTER TABLE `resetare_parola`
  ADD PRIMARY KEY (`resetareID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorii`
--
ALTER TABLE `categorii`
  MODIFY `categorieID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comentarii`
--
ALTER TABLE `comentarii`
  MODIFY `comentariuID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `dislikes`
--
ALTER TABLE `dislikes`
  MODIFY `dislikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `intrebari`
--
ALTER TABLE `intrebari`
  MODIFY `intrebareID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `materiale`
--
ALTER TABLE `materiale`
  MODIFY `materialID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `materii`
--
ALTER TABLE `materii`
  MODIFY `materiiID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `resetare_parola`
--
ALTER TABLE `resetare_parola`
  MODIFY `resetareID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categorii`
--
ALTER TABLE `categorii`
  ADD CONSTRAINT `categorii_ibfk_1` FOREIGN KEY (`materieID`) REFERENCES `materii` (`materiiID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comentarii`
--
ALTER TABLE `comentarii`
  ADD CONSTRAINT `comentarii_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarii_ibfk_2` FOREIGN KEY (`materialID`) REFERENCES `materiale` (`materialID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarii_ibfk_3` FOREIGN KEY (`intrebareID`) REFERENCES `intrebari` (`intrebareID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dislikes`
--
ALTER TABLE `dislikes`
  ADD CONSTRAINT `dislikes_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dislikes_ibfk_2` FOREIGN KEY (`intrebareID`) REFERENCES `intrebari` (`intrebareID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dislikes_ibfk_3` FOREIGN KEY (`comentariuID`) REFERENCES `comentarii` (`comentariuID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dislikes_ibfk_4` FOREIGN KEY (`materialID`) REFERENCES `materiale` (`materialID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `folowers`
--
ALTER TABLE `folowers`
  ADD CONSTRAINT `folowers_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `folowers_ibfk_2` FOREIGN KEY (`followingID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `intrebari`
--
ALTER TABLE `intrebari`
  ADD CONSTRAINT `intrebari_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `intrebari_ibfk_2` FOREIGN KEY (`categorieID`) REFERENCES `categorii` (`categorieID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`intrebareID`) REFERENCES `intrebari` (`intrebareID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_3` FOREIGN KEY (`comentariuID`) REFERENCES `comentarii` (`comentariuID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_4` FOREIGN KEY (`materialID`) REFERENCES `materiale` (`materialID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `materiale`
--
ALTER TABLE `materiale`
  ADD CONSTRAINT `materiale_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `materiale_ibfk_2` FOREIGN KEY (`categorieID`) REFERENCES `categorii` (`categorieID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resetare_parola`
--
ALTER TABLE `resetare_parola`
  ADD CONSTRAINT `resetare_parola_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
