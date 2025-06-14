-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2025 at 01:18 PM
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
-- Database: `countfit`
--

-- --------------------------------------------------------

--
-- Table structure for table `bodyparts`
--

CREATE TABLE `bodyparts` (
  `bodypartId` int(11) NOT NULL,
  `bodypartName` varchar(80) DEFAULT NULL,
  `bodypartDesc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bodyparts2exercise`
--

CREATE TABLE `bodyparts2exercise` (
  `bp2exerId` int(11) NOT NULL,
  `bodypartID` int(11) NOT NULL,
  `exerciseId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `exerciseId` int(11) NOT NULL,
  `exerciseName` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sets`
--

CREATE TABLE `sets` (
  `setID` int(11) NOT NULL,
  `setDate` varchar(10) NOT NULL,
  `setWeight` double DEFAULT NULL,
  `setReps` int(11) NOT NULL,
  `usersId` int(11) NOT NULL,
  `exerciseId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainingsession`
--

CREATE TABLE `trainingsession` (
  `tsID` int(11) NOT NULL,
  `tsName` varchar(100) NOT NULL,
  `tsDesc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainingsession2bodypart`
--

CREATE TABLE `trainingsession2bodypart` (
  `tsbpID` int(11) NOT NULL,
  `tsID` int(11) NOT NULL,
  `bodypartId` int(11) NOT NULL,
  `usersId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idUsers` int(11) NOT NULL,
  `uidUsers` tinytext NOT NULL,
  `emailUsers` tinytext NOT NULL,
  `pwdUsers` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users2trainingsession`
--

CREATE TABLE `users2trainingsession` (
  `users2tsID` int(11) NOT NULL,
  `usersId` int(11) NOT NULL,
  `tsID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bodyparts`
--
ALTER TABLE `bodyparts`
  ADD PRIMARY KEY (`bodypartId`);

--
-- Indexes for table `bodyparts2exercise`
--
ALTER TABLE `bodyparts2exercise`
  ADD PRIMARY KEY (`bp2exerId`),
  ADD KEY `bodypartID` (`bodypartID`),
  ADD KEY `exerciseId` (`exerciseId`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`exerciseId`);

--
-- Indexes for table `sets`
--
ALTER TABLE `sets`
  ADD PRIMARY KEY (`setID`),
  ADD KEY `usersId` (`usersId`),
  ADD KEY `exerciseId` (`exerciseId`);

--
-- Indexes for table `trainingsession`
--
ALTER TABLE `trainingsession`
  ADD PRIMARY KEY (`tsID`);

--
-- Indexes for table `trainingsession2bodypart`
--
ALTER TABLE `trainingsession2bodypart`
  ADD PRIMARY KEY (`tsbpID`),
  ADD KEY `tsID` (`tsID`),
  ADD KEY `bodypartId` (`bodypartId`),
  ADD KEY `usersId` (`usersId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUsers`);

--
-- Indexes for table `users2trainingsession`
--
ALTER TABLE `users2trainingsession`
  ADD PRIMARY KEY (`users2tsID`),
  ADD KEY `usersId` (`usersId`),
  ADD KEY `tsID` (`tsID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sets`
--
ALTER TABLE `sets`
  MODIFY `setID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bodyparts2exercise`
--
ALTER TABLE `bodyparts2exercise`
  ADD CONSTRAINT `bodyparts2exercise_ibfk_1` FOREIGN KEY (`bodypartID`) REFERENCES `bodyparts` (`bodypartId`),
  ADD CONSTRAINT `bodyparts2exercise_ibfk_2` FOREIGN KEY (`exerciseId`) REFERENCES `exercises` (`exerciseId`);

--
-- Constraints for table `sets`
--
ALTER TABLE `sets`
  ADD CONSTRAINT `sets_ibfk_1` FOREIGN KEY (`usersId`) REFERENCES `users` (`idUsers`),
  ADD CONSTRAINT `sets_ibfk_2` FOREIGN KEY (`exerciseId`) REFERENCES `exercises` (`exerciseId`);

--
-- Constraints for table `trainingsession2bodypart`
--
ALTER TABLE `trainingsession2bodypart`
  ADD CONSTRAINT `trainingsession2bodypart_ibfk_1` FOREIGN KEY (`tsID`) REFERENCES `trainingsession` (`tsID`),
  ADD CONSTRAINT `trainingsession2bodypart_ibfk_2` FOREIGN KEY (`bodypartId`) REFERENCES `bodyparts` (`bodypartId`),
  ADD CONSTRAINT `trainingsession2bodypart_ibfk_3` FOREIGN KEY (`usersId`) REFERENCES `users` (`idUsers`);

--
-- Constraints for table `users2trainingsession`
--
ALTER TABLE `users2trainingsession`
  ADD CONSTRAINT `users2trainingsession_ibfk_1` FOREIGN KEY (`usersId`) REFERENCES `users` (`idUsers`),
  ADD CONSTRAINT `users2trainingsession_ibfk_2` FOREIGN KEY (`tsID`) REFERENCES `trainingsession` (`tsID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
