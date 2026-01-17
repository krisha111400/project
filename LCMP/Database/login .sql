-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 17, 2026 at 06:02 AM
-- Server version: 8.0.43
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `business_owners`
--

DROP TABLE IF EXISTS `business_owners`;
CREATE TABLE IF NOT EXISTS `business_owners` (
  `business_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `business_name` varchar(150) DEFAULT NULL,
  `owner_full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`business_id`),
  KEY `fk_business_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `business_owners`
--

INSERT INTO `business_owners` (`business_id`, `user_id`, `business_name`, `owner_full_name`, `phone`, `created_at`) VALUES
(2, 3, 'mahesh01', 'mahesh patel', '8798767565', '2026-01-07 12:55:19'),
(3, 4, 'krishna02', 'krishna patel', '8796543267', '2026-01-07 15:59:47');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
CREATE TABLE IF NOT EXISTS `campaigns` (
  `CampaignId` int NOT NULL AUTO_INCREMENT,
  `OwnerId` int NOT NULL,
  `Title` varchar(150) DEFAULT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `Description` text,
  `Budget` decimal(10,2) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','blocked') DEFAULT 'active',
  `payment_status` enum('unpaid','paid') DEFAULT 'unpaid',
  PRIMARY KEY (`CampaignId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`CampaignId`, `OwnerId`, `Title`, `Category`, `Description`, `Budget`, `City`, `created_at`, `status`, `payment_status`) VALUES
(8, 3, 'kriiiiiiiiiiiiiiiiiiii', 'Tech', 'kriiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', 4000.00, 'Surat', '2026-01-10 10:51:25', 'active', 'unpaid'),
(9, 3, 'maheshhh', 'Fashion', '.................../', 5000.00, 'Ahmedabad', '2026-01-15 10:34:12', 'active', 'unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_applications`
--

DROP TABLE IF EXISTS `campaign_applications`;
CREATE TABLE IF NOT EXISTS `campaign_applications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `campaign_id` int NOT NULL,
  `creator_id` int NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `applied_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaign_id` (`campaign_id`,`creator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `campaign_applications`
--

INSERT INTO `campaign_applications` (`id`, `campaign_id`, `creator_id`, `status`, `applied_at`) VALUES
(8, 4, 1, 'pending', '2026-01-08 12:14:27'),
(9, 6, 1, 'approved', '2026-01-09 10:33:36'),
(10, 7, 1, 'rejected', '2026-01-09 10:48:01'),
(11, 8, 1, 'rejected', '2026-01-10 11:00:22'),
(12, 9, 1, 'pending', '2026-01-17 02:55:56');

-- --------------------------------------------------------

--
-- Table structure for table `contact_benefits`
--

DROP TABLE IF EXISTS `contact_benefits`;
CREATE TABLE IF NOT EXISTS `contact_benefits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('creator','promoter') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_benefits`
--

INSERT INTO `contact_benefits` (`id`, `type`, `title`, `description`, `created_at`) VALUES
(1, 'creator', 'Get discovered locally', 'Local brands can easily find you', '2026-01-17 03:00:13'),
(2, 'creator', 'Earn via campaigns', 'Paid collaborations with trusted brands.', '2026-01-17 03:00:13'),
(4, 'promoter', 'Run hyper-local ads', 'Target customers in specific areas', '2026-01-17 03:00:13');

-- --------------------------------------------------------

--
-- Table structure for table `creators`
--

DROP TABLE IF EXISTS `creators`;
CREATE TABLE IF NOT EXISTS `creators` (
  `creator_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `social_handle` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`creator_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `creators`
--

INSERT INTO `creators` (`creator_id`, `user_id`, `full_name`, `phone`, `city`, `social_handle`, `created_at`) VALUES
(1, 1, 'krishna01', '7456894685', 'LAKHANI', 'krisha__111', '2026-01-04 05:50:29');

-- --------------------------------------------------------

--
-- Table structure for table `users_login`
--

DROP TABLE IF EXISTS `users_login`;
CREATE TABLE IF NOT EXISTS `users_login` (
  `UserId` int NOT NULL AUTO_INCREMENT,
  `UserType` varchar(20) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `oauth_provider` varchar(50) DEFAULT NULL,
  `oauth_id` varchar(100) DEFAULT NULL,
  `force_password_change` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` enum('active','blocked') DEFAULT 'active',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `UserName` (`UserName`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users_login`
--

INSERT INTO `users_login` (`UserId`, `UserType`, `Email`, `UserName`, `Password`, `oauth_provider`, `oauth_id`, `force_password_change`, `created_at`, `Status`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'creator', 'krishna@gmail.com', 'krishna', '$2y$10$I66hhriB7N3/EZfElHcIrOg.vjPYfLYvOvi.AHfrz0lKEjJZ/H8Iq', NULL, NULL, 0, '2026-01-04 05:50:29', 'active', NULL, NULL),
(3, 'promoter', 'mahesh8@gmail.com', 'mahesh8', '$2y$10$UFS51zxkRC/t5NO6CzPWM.u3NEdnEpXr9XSeQKhl8b/BPmctVH10e', NULL, NULL, 0, '2026-01-07 12:55:19', 'active', NULL, NULL),
(4, 'promoter', 'krisha@gmail.com', 'krisha1', '$2y$10$/Uxhzezc43aJVB.RXIg2.ehH6w649ScAqrerVYC4LTL6XgGkA0QH2', NULL, NULL, 0, '2026-01-07 15:59:47', 'active', '07246e08902b55403d9bb3a5359de77ab0eadde8c0020d9c3ecec72b017696c6', '2026-01-17 05:41:15'),
(11, 'admin', 'krisha@gmail.com', 'krisha', '$2y$10$xJUNpLNcAdBqgdBTtNbhtuCW.LqeIzz5P0aWI07x62b2f.HXQa.Ka', NULL, NULL, 0, '2026-01-13 03:18:49', 'active', '07246e08902b55403d9bb3a5359de77ab0eadde8c0020d9c3ecec72b017696c6', '2026-01-17 05:41:15');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `business_owners`
--
ALTER TABLE `business_owners`
  ADD CONSTRAINT `fk_business_user` FOREIGN KEY (`user_id`) REFERENCES `users_login` (`UserId`) ON DELETE CASCADE;

--
-- Constraints for table `creators`
--
ALTER TABLE `creators`
  ADD CONSTRAINT `creators_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_login` (`UserId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
