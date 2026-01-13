-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 02:55 PM
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
-- Database: `pif`
--

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `pk_collection` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `fk_user_creates` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contains`
--

CREATE TABLE `contains` (
  `pkfk_collection` int(11) NOT NULL,
  `pkfk_measurement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasaccess`
--

CREATE TABLE `hasaccess` (
  `pkfk_user` varchar(50) NOT NULL,
  `pkfk_collection` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `isfriend`
--

CREATE TABLE `isfriend` (
  `pkfk_user_user` varchar(50) NOT NULL,
  `pkfk_user_friend` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `measurement`
--

CREATE TABLE `measurement` (
  `pk_measurement` int(11) NOT NULL,
  `temperature` decimal(5,2) NOT NULL,
  `humidity` decimal(5,2) NOT NULL,
  `pressure` decimal(6,2) NOT NULL,
  `light` decimal(6,2) NOT NULL,
  `gas` decimal(6,2) NOT NULL,
  `timestamp` datetime NOT NULL,
  `fk_station_records` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `measurement`
--

INSERT INTO `measurement` (`pk_measurement`, `temperature`, `humidity`, `pressure`, `light`, `gas`, `timestamp`, `fk_station_records`) VALUES
(1, 23.50, 45.20, 1013.20, 320.00, 412.00, '2025-01-01 10:00:00', 'SN-0001'),
(2, 23.60, 45.10, 1013.10, 315.00, 415.00, '2025-01-02 10:05:00', 'SN-0001'),
(3, 23.40, 45.30, 1013.30, 325.00, 410.00, '2025-01-03 10:10:00', 'SN-0001'),
(4, 23.70, 44.90, 1013.00, 310.00, 418.00, '2025-01-04 10:15:00', 'SN-0001'),
(5, 23.80, 44.80, 1012.90, 305.00, 420.00, '2025-01-05 10:20:00', 'SN-0001'),
(6, 23.90, 44.70, 1012.80, 300.00, 422.00, '2025-01-06 10:25:00', 'SN-0001'),
(7, 24.00, 44.60, 1012.70, 295.00, 425.00, '2025-01-07 10:30:00', 'SN-0001'),
(8, 24.10, 44.50, 1012.60, 290.00, 428.00, '2025-01-08 10:35:00', 'SN-0001'),
(9, 24.20, 44.40, 1012.50, 285.00, 430.00, '2025-01-09 10:40:00', 'SN-0001'),
(10, 24.30, 44.30, 1012.40, 280.00, 432.00, '2025-01-10 10:45:00', 'SN-0001');

-- --------------------------------------------------------

--
-- Table structure for table `station`
--

CREATE TABLE `station` (
  `pk_serialNumber` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `fk_user_owns` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `station`
--

INSERT INTO `station` (`pk_serialNumber`, `name`, `description`, `fk_user_owns`) VALUES
('SN-0001', 'Living Room', 'Temperature of Living Room', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `pk_username` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` enum('User','Admin') NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`pk_username`, `firstName`, `lastName`, `password`, `email`, `role`) VALUES
('admin', 'Admin', 'User', 'admin123', 'admin@example.com', 'Admin'),
('testuser', 'Test', 'User', 'test123', 'test@example.com', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`pk_collection`),
  ADD KEY `fkc_user_creates_collection` (`fk_user_creates`);

--
-- Indexes for table `contains`
--
ALTER TABLE `contains`
  ADD PRIMARY KEY (`pkfk_collection`,`pkfk_measurement`),
  ADD KEY `fkc_contains_measurement` (`pkfk_measurement`);

--
-- Indexes for table `hasaccess`
--
ALTER TABLE `hasaccess`
  ADD PRIMARY KEY (`pkfk_user`,`pkfk_collection`),
  ADD KEY `fkc_hasaccess_collection` (`pkfk_collection`);

--
-- Indexes for table `isfriend`
--
ALTER TABLE `isfriend`
  ADD PRIMARY KEY (`pkfk_user_user`,`pkfk_user_friend`),
  ADD KEY `fkc_isfriend_friend` (`pkfk_user_friend`);

--
-- Indexes for table `measurement`
--
ALTER TABLE `measurement`
  ADD PRIMARY KEY (`pk_measurement`),
  ADD KEY `fkc_station_records_measurement` (`fk_station_records`);

--
-- Indexes for table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`pk_serialNumber`),
  ADD KEY `fkc_user_owns_station` (`fk_user_owns`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`pk_username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `pk_collection` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `measurement`
--
ALTER TABLE `measurement`
  MODIFY `pk_measurement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collection`
--
ALTER TABLE `collection`
  ADD CONSTRAINT `fkc_user_creates_collection` FOREIGN KEY (`fk_user_creates`) REFERENCES `user` (`pk_username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contains`
--
ALTER TABLE `contains`
  ADD CONSTRAINT `fkc_contains_collection` FOREIGN KEY (`pkfk_collection`) REFERENCES `collection` (`pk_collection`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkc_contains_measurement` FOREIGN KEY (`pkfk_measurement`) REFERENCES `measurement` (`pk_measurement`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hasaccess`
--
ALTER TABLE `hasaccess`
  ADD CONSTRAINT `fkc_hasaccess_collection` FOREIGN KEY (`pkfk_collection`) REFERENCES `collection` (`pk_collection`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkc_hasaccess_user` FOREIGN KEY (`pkfk_user`) REFERENCES `user` (`pk_username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `isfriend`
--
ALTER TABLE `isfriend`
  ADD CONSTRAINT `fkc_isfriend_friend` FOREIGN KEY (`pkfk_user_friend`) REFERENCES `user` (`pk_username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkc_isfriend_user` FOREIGN KEY (`pkfk_user_user`) REFERENCES `user` (`pk_username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `measurement`
--
ALTER TABLE `measurement`
  ADD CONSTRAINT `fkc_station_records_measurement` FOREIGN KEY (`fk_station_records`) REFERENCES `station` (`pk_serialNumber`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `station`
--
ALTER TABLE `station`
  ADD CONSTRAINT `fkc_user_owns_station` FOREIGN KEY (`fk_user_owns`) REFERENCES `user` (`pk_username`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
