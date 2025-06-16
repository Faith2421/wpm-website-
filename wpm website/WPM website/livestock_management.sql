-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 10, 2024 at 10:00 AM
-- Server version: 8.0.31
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `livestock_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `livestock_management`;

-- --------------------------------------------------------
-- Core Tables with Minimal Constraints
-- --------------------------------------------------------

-- Farmers table (no changes needed)
CREATE TABLE `farmers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `farm_name` varchar(100) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Livestock table (only keeps farmer relationship)
CREATE TABLE `livestock` (
  `farmer_id` int NOT NULL,
  `animal_id` varchar(50) NOT NULL,
  `species` enum('Cattle','Goat','Sheep','Pig','Poultry','Other') NOT NULL,
  `breed` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Unknown') DEFAULT NULL,
  `status` enum('Active','Sold','Deceased','Transferred') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`animal_id`),
  KEY `farmer_id` (`farmer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Health records (no foreign key constraint)
CREATE TABLE `health_records` (
  `animal_id` varchar(50) NOT NULL,
  `checkup_date` date NOT NULL,
  `veterinarian` varchar(100) DEFAULT NULL,
  `diagnosis` text,
  `treatment` text,
  `notes` text,
  `next_checkup` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`animal_id`,`checkup_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Feeding records (no foreign key constraint)
CREATE TABLE `feeding_records` (
  `animal_id` varchar(50) NOT NULL,
  `record_date` date NOT NULL,
  `feed_type` varchar(100) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `nutritional_info` text,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`animal_id`,`record_date`,`feed_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Breeding records (simplified)
CREATE TABLE `breeding_records` (
  `animal_id` varchar(50) NOT NULL,
  `breeding_date` date NOT NULL,
  `sire_id` varchar(50) DEFAULT NULL,
  `expected_birth_date` date DEFAULT NULL,
  `pregnancy_status` enum('Confirmed','Not Confirmed','Failed') DEFAULT 'Not Confirmed',
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`animal_id`,`breeding_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Mortality records (no foreign key constraint)
CREATE TABLE `mortality_records` (
  `animal_id` varchar(50) NOT NULL,
  `date_of_death` date NOT NULL,
  `cause_of_death` text,
  `disposal_method` varchar(100) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`animal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Movement records (no foreign key constraint)
CREATE TABLE `movement_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `animal_id` varchar(50) NOT NULL,
  `movement_type` enum('Sale','Purchase','Transfer In','Transfer Out','Show','Other') NOT NULL,
  `movement_date` date NOT NULL,
  `destination` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Only Keeping the Most Critical Constraint
-- --------------------------------------------------------

ALTER TABLE `livestock` 
ADD CONSTRAINT `livestock_ibfk_1` FOREIGN KEY (`farmer_id`) 
REFERENCES `farmers` (`id`);

-- --------------------------------------------------------
-- Sample Data (Optional)
-- --------------------------------------------------------

INSERT INTO `farmers` (`id`, `name`, `farm_name`, `contact_number`, `email`, `password`) VALUES
(1, 'John Doe', 'Green Valley Farm', '1234567890', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(2, 'Jane Smith', 'Sunny Acres', '0987654321', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO `livestock` (`farmer_id`, `animal_id`, `species`, `breed`, `date_of_birth`, `gender`) VALUES
(1, 'COW001', 'Cattle', 'Holstein', '2020-05-15', 'Female'),
(1, 'GOAT002', 'Goat', 'Boer', '2021-03-20', 'Male'),
(2, 'SHEEP003', 'Sheep', 'Dorper', '2022-01-10', 'Female');

COMMIT;