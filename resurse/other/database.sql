-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 10:13 PM
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
  `nume` varchar(255) NOT NULL,
  `descriere` varchar(255) DEFAULT NULL,
  `dataPostarii` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorii`
--

INSERT INTO `categorii` (`categorieID`, `nume`, `descriere`, `dataPostarii`) VALUES
(1, 'Matematică', '', '2025-05-21'),
(2, 'Română', '', '2025-05-21'),
(3, 'Istorie', '', '2025-05-21'),
(4, 'Fizică', '', '2025-05-21'),
(7, 'Chimie', '', '2025-05-22'),
(8, 'Biologie', '', '2025-05-22'),
(9, 'Geografie', '', '2025-05-22');

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
(2, 1, 3, NULL, NULL, '2025-05-22'),
(3, 1, 1, NULL, NULL, '2025-05-22'),
(4, 2, 1, NULL, NULL, '2025-05-22'),
(5, 8, 4, NULL, NULL, '2025-05-22');

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
(4, 1, 'Care sunt grupele de sânge?', 8, '', NULL, '2025-05-22 00:00:00');

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
(14, 1, 2, NULL, NULL, '2025-05-22'),
(15, 2, 4, NULL, NULL, '2025-05-22'),
(16, 2, 3, NULL, NULL, '2025-05-22'),
(17, 2, 2, NULL, NULL, '2025-05-22'),
(19, 8, 2, NULL, NULL, '2025-05-22'),
(20, 8, 3, NULL, NULL, '2025-05-22'),
(21, 8, 1, NULL, NULL, '2025-05-22'),
(26, 1, 4, NULL, NULL, '2025-05-22'),
(60, 3, NULL, NULL, 3, '2025-05-22'),
(63, 3, 2, NULL, NULL, '2025-05-22'),
(64, 3, 4, NULL, NULL, '2025-05-22');

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
(1, 1, 'Evaporarea și condensarea', 4, '', '', 'Evaporare și Condensare.docx', '2025-05-22 13:43:00'),
(2, 1, 'Tabel cu derivate', 1, 'Tabelul cu derivatele functiilor', 'derivate, formule', 'formule derivate.jpg', '2025-05-22 15:43:00'),
(3, 2, 'Fuzionare și Înghețare', 4, '', 'fuzionare, înghețare', 'Fuzionare și Înghețare.docx', '2025-05-22 17:43:00'),
(4, 8, 'Vaporizare și Lichefiere', 4, '', '', 'Vaporizare și Lichefiere.docx', '2025-05-22 19:11:07');

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
(2, 'Gigel', 2, '$2y$10$NmTcF64snIDw5SAomR.PHeJ581ICs3lGTyUGKW6l8hxUzRr83U21W', 'default.jpg', 'Gigel', 'Popescu', 'gigel.popescu@gmail.com', '1988-01-12', '2025-05-15', NULL, 0),
(3, 'Andrei', 2, '$2y$10$myQqGGokJvLc7PeOksWy1.0mZhVQLg6rvZ9Yvv.CWCZ/2FG1nbkZa', 'default.jpg', 'Andrei', 'Ion', 'andrei.ion@gmail.com', '1993-02-16', '2025-05-15', NULL, 0),
(8, 'admin3', 1, '$2y$10$m.oq1q2x1CIBYYfb5fRQL.UPK8PMlQOaY7ZSEqhp0FD3aycrUt1YG', 'default.jpg', 'Admin', 'Admin', 'admin3@gmail.com', '1998-11-11', '2025-05-16', NULL, 0),
(9, 'Matei', 2, '$2y$10$HCVbx8nrak91Hqvtj3HYaOW/dB5ZqR293OUkqIbdqr/ROcWih.XGq', 'default.jpg', 'Matei', 'George', 'matei.george@gmail.com', '1993-06-25', '2025-05-22', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorii`
--
ALTER TABLE `categorii`
  ADD PRIMARY KEY (`categorieID`),
  ADD UNIQUE KEY `nume` (`nume`);

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
  MODIFY `categorieID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comentarii`
--
ALTER TABLE `comentarii`
  MODIFY `comentariuID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dislikes`
--
ALTER TABLE `dislikes`
  MODIFY `dislikeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `intrebari`
--
ALTER TABLE `intrebari`
  MODIFY `intrebareID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `likeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `materiale`
--
ALTER TABLE `materiale`
  MODIFY `materialID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
