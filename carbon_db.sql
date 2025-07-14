-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2025 at 04:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carbon_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `name`) VALUES
(1, 'India'),
(2, 'USA'),
(3, 'Canada'),
(4, 'Germany');

-- --------------------------------------------------------

--
-- Table structure for table `emissions`
--

CREATE TABLE `emissions` (
  `id` int(11) NOT NULL,
  `source` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  `date` date NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `year_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emissions`
--

INSERT INTO `emissions` (`id`, `source`, `amount`, `date`, `country_id`, `year_id`) VALUES
(1, 'Transportation', 120.5, '2025-05-02', 1, 4),
(2, 'Industrial Processes', 250, '2025-02-27', 2, 4),
(3, 'Electricity Generation', 300.2, '2023-07-01', 3, 2),
(4, 'Agriculture', 180.7, '2022-05-11', 1, 1),
(5, 'Deforestation', 450.3, '2023-08-01', 2, NULL),
(7, 'Mining Activities', 220.5, '2023-09-05', 1, NULL),
(8, 'Residential Emissions', 130.9, '2024-04-03', 2, 3),
(9, 'Air Travel', 410.6, '2025-10-07', 3, 4),
(10, 'Construction Sector', 290, '2022-06-29', 1, NULL),
(16, 'fire', 300, '2025-07-04', 4, 4),
(17, 'fire', -0.01, '2025-06-06', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

CREATE TABLE `years` (
  `year_id` int(11) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `years`
--

INSERT INTO `years` (`year_id`, `year`) VALUES
(1, 2022),
(2, 2023),
(3, 2024),
(4, 2025);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `emissions`
--
ALTER TABLE `emissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `year_id` (`year_id`);

--
-- Indexes for table `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`year_id`),
  ADD UNIQUE KEY `year` (`year`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `emissions`
--
ALTER TABLE `emissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `years`
--
ALTER TABLE `years`
  MODIFY `year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `emissions`
--
ALTER TABLE `emissions`
  ADD CONSTRAINT `emissions_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`country_id`),
  ADD CONSTRAINT `emissions_ibfk_2` FOREIGN KEY (`year_id`) REFERENCES `years` (`year_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
