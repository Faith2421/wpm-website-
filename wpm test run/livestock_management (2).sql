-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 28, 2025 at 02:50 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `livestock_management`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `animal_overview`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `animal_overview`;
CREATE TABLE IF NOT EXISTS `animal_overview` (
`animal_id` varchar(50)
,`breed` varchar(50)
,`breeding_count` bigint
,`contact_number` varchar(20)
,`date_of_birth` date
,`farm_name` varchar(100)
,`farmer_name` varchar(100)
,`gender` enum('Male','Female','Unknown')
,`last_health_check` date
,`species` enum('Cattle','Goat','Sheep','Pig','Poultry','Other')
,`status` enum('Active','Sold','Deceased','Transferred')
);

-- --------------------------------------------------------

--
-- Table structure for table `breeding_records`
--

DROP TABLE IF EXISTS `breeding_records`;
CREATE TABLE IF NOT EXISTS `breeding_records` (
  `animal_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `breeding_date` date NOT NULL,
  `sire_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expected_birth_date` date DEFAULT NULL,
  `pregnancy_status` enum('Confirmed','Not Confirmed','Failed') COLLATE utf8mb4_general_ci DEFAULT 'Not Confirmed',
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`animal_id`, `breeding_date`),
  KEY `sire_id` (`sire_id`),
  KEY `idx_breeding_animal` (`animal_id`),
  FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE,
  FOREIGN KEY (`sire_id`) REFERENCES `livestock` (`animal_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

DROP TABLE IF EXISTS `farmers`;
CREATE TABLE IF NOT EXISTS `farmers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `farm_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feeding_records`
--

DROP TABLE IF EXISTS `feeding_records`;
CREATE TABLE IF NOT EXISTS `feeding_records` (
  `animal_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `record_date` date NOT NULL,
  `feed_type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nutritional_info` text COLLATE utf8mb4_general_ci,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`animal_id`, `record_date`, `feed_type`),
  KEY `animal_id` (`animal_id`),
  FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `health_records`
--

DROP TABLE IF EXISTS `health_records`;
CREATE TABLE IF NOT EXISTS `health_records` (
  `animal_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `checkup_date` date NOT NULL,
  `veterinarian` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diagnosis` text COLLATE utf8mb4_general_ci,
  `treatment` text COLLATE utf8mb4_general_ci,
  `notes` text COLLATE utf8mb4_general_ci,
  `next_checkup` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`animal_id`, `checkup_date`),
  KEY `idx_health_animal` (`animal_id`),
  FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Table structure for table `livestock`
--

DROP TABLE IF EXISTS `livestock`;
CREATE TABLE IF NOT EXISTS `livestock` (
  `animal_id` int NOT NULL,
  `animal_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `species` enum('Cattle','Goat','Sheep','Pig','Poultry','Other') COLLATE utf8mb4_general_ci NOT NULL,
  `breed` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Unknown') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Active','Sold','Deceased','Transferred') COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`animal_id`),
  UNIQUE KEY `animal_id` (`animal_id`),
  KEY `idx_livestock_farmer` (`farmer_id`),
  KEY `idx_livestock_species` (`species`),
   FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mortality_records`
--

DROP TABLE IF EXISTS `mortality_records`;
CREATE TABLE IF NOT EXISTS `mortality_records` (
  `animal_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `date_of_death` date NOT NULL,
  `cause_of_death` text COLLATE utf8mb4_general_ci,
  `disposal_method` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`animal_id`),
  FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movement_records`
--

DROP TABLE IF EXISTS `movement_records`;
CREATE TABLE IF NOT EXISTS `movement_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `animal_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `movement_type` enum('Sale','Purchase','Transfer In','Transfer Out','Show','Other') COLLATE utf8mb4_general_ci NOT NULL,
  `movement_date` date NOT NULL,
  `destination` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_person` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contact_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_movement_animal` (`animal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccination_records`
--

DROP TABLE IF EXISTS `vaccination_records`;
CREATE TABLE IF NOT EXISTS `vaccination_records` (
  `animal_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `vaccine_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `administration_date` date NOT NULL,
  `next_due_date` date DEFAULT NULL,
  `administered_by` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`animal_id`, `vaccine_name`, `administration_date`),
  KEY `animal_id` (`animal_id`),
  FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `animal_overview`
--
DROP TABLE IF EXISTS `animal_overview`;

DROP VIEW IF EXISTS `animal_overview`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `animal_overview`  AS SELECT `l`.`animal_id` AS `animal_id`, `l`.`species` AS `species`, `l`.`breed` AS `breed`, `l`.`date_of_birth` AS `date_of_birth`, `l`.`gender` AS `gender`, `l`.`status` AS `status`, `f`.`name` AS `farmer_name`, `f`.`farm_name` AS `farm_name`, `f`.`contact_number` AS `contact_number`, (select max(`health_records`.`checkup_date`) from `health_records` where (`health_records`.`animal_id` = `l`.`animal_id`)) AS `last_health_check`, (select count(0) from `breeding_records` where (`breeding_records`.`animal_id` = `l`.`animal_id`)) AS `breeding_count` FROM (`livestock` `l` join `farmers` `f` on((`l`.`farmer_id` = `f`.`id`))) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `breeding_records`
--
ALTER TABLE `breeding_records`
  ADD CONSTRAINT `breeding_records_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `breeding_records_ibfk_2` FOREIGN KEY (`sire_id`) REFERENCES `livestock` (`animal_id`);

--
-- Constraints for table `feeding_records`
--
ALTER TABLE `feeding_records`
  ADD CONSTRAINT `feeding_records_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE;

--
-- Constraints for table `health_records`
--
ALTER TABLE `health_records`
  ADD CONSTRAINT `health_records_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE;

--
-- Constraints for table `livestock`
--
ALTER TABLE `livestock`
  ADD CONSTRAINT `livestock_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mortality_records`
--
ALTER TABLE `mortality_records`
  ADD CONSTRAINT `mortality_records_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE;

--
-- Constraints for table `movement_records`
--
ALTER TABLE `movement_records`
  ADD CONSTRAINT `movement_records_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE;

--
-- Constraints for table `vaccination_records`
--
ALTER TABLE `vaccination_records`
  ADD CONSTRAINT `vaccination_records_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `livestock` (`animal_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
