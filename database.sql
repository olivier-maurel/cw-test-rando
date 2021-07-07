-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2021 at 03:02 PM
-- Server version: 8.0.25-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cw-test-rando_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `hiking`
--

CREATE TABLE `hiking` (
  `id` int NOT NULL,
  `difficulty_id` int NOT NULL,
  `type_id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` time NOT NULL,
  `distance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `elevation_gain` int NOT NULL,
  `highest_point` int NOT NULL,
  `lowest_point` int NOT NULL,
  `return_start_point` tinyint(1) DEFAULT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `municipality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hiking_difficulty`
--

CREATE TABLE `hiking_difficulty` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hiking_type`
--

CREATE TABLE `hiking_type` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `way_point`
--

CREATE TABLE `way_point` (
  `id` int NOT NULL,
  `hiking_id` int NOT NULL,
  `step` int NOT NULL,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hiking`
--
ALTER TABLE `hiking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_42CE0BD7FCFA9DAE` (`difficulty_id`),
  ADD KEY `IDX_42CE0BD7C54C8C93` (`type_id`);

--
-- Indexes for table `hiking_difficulty`
--
ALTER TABLE `hiking_difficulty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hiking_type`
--
ALTER TABLE `hiking_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `way_point`
--
ALTER TABLE `way_point`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9B25317471AFD0BB` (`hiking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hiking`
--
ALTER TABLE `hiking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hiking_difficulty`
--
ALTER TABLE `hiking_difficulty`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hiking_type`
--
ALTER TABLE `hiking_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `way_point`
--
ALTER TABLE `way_point`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hiking`
--
ALTER TABLE `hiking`
  ADD CONSTRAINT `FK_42CE0BD7C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `hiking_type` (`id`),
  ADD CONSTRAINT `FK_42CE0BD7FCFA9DAE` FOREIGN KEY (`difficulty_id`) REFERENCES `hiking_difficulty` (`id`);

--
-- Constraints for table `way_point`
--
ALTER TABLE `way_point`
  ADD CONSTRAINT `FK_9B25317471AFD0BB` FOREIGN KEY (`hiking_id`) REFERENCES `hiking` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
