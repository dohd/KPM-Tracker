-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 06, 2024 at 05:33 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `metric_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `assign_scores`
--

CREATE TABLE `assign_scores` (
  `id` bigint(20) NOT NULL,
  `programme_id` bigint(20) DEFAULT NULL,
  `rating_scale_id` bigint(20) DEFAULT NULL,
  `team_id` bigint(20) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `team_total` int(11) NOT NULL DEFAULT 0,
  `team_total_att` int(11) NOT NULL DEFAULT 0,
  `guest_total_att` int(11) NOT NULL DEFAULT 0,
  `days` int(11) NOT NULL DEFAULT 0,
  `avg_total_att` int(11) NOT NULL DEFAULT 0,
  `perc_score` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `accrued_amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `retreat_leader_total` int(11) NOT NULL DEFAULT 0,
  `retreat_meeting_total` int(11) DEFAULT 0,
  `online_meeting_total` int(11) NOT NULL DEFAULT 0,
  `tb_activities_total` int(11) NOT NULL DEFAULT 0,
  `summit_meetings_total` int(11) NOT NULL DEFAULT 0,
  `recruits_total` int(11) NOT NULL DEFAULT 0,
  `initiatives_total` int(11) NOT NULL DEFAULT 0,
  `team_missions_total` int(11) NOT NULL DEFAULT 0,
  `choir_members_total` int(11) NOT NULL DEFAULT 0,
  `other_activities_total` int(11) NOT NULL DEFAULT 0,
  `point` int(11) NOT NULL DEFAULT 0,
  `extra_points` int(11) NOT NULL DEFAULT 0,
  `net_points` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assign_scores`
--

INSERT INTO `assign_scores` (`id`, `programme_id`, `rating_scale_id`, `team_id`, `date_from`, `date_to`, `team_total`, `team_total_att`, `guest_total_att`, `days`, `avg_total_att`, `perc_score`, `accrued_amount`, `retreat_leader_total`, `retreat_meeting_total`, `online_meeting_total`, `tb_activities_total`, `summit_meetings_total`, `recruits_total`, `initiatives_total`, `team_missions_total`, `choir_members_total`, `other_activities_total`, `point`, `extra_points`, `net_points`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 12, 3, 1, '2024-02-17', '2024-02-17', 10, 7, 3, 1, 7, '70.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 0, 11, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(2, 12, 3, 2, '2024-02-17', '2024-02-17', 11, 9, 3, 1, 9, '81.8182', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 12, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(3, 12, 3, 3, '2024-02-17', '2024-02-17', 12, 11, 3, 1, 11, '91.6667', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, 0, 13, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(4, 12, 3, 4, '2024-02-17', '2024-02-17', 10, 3, 2, 1, 3, '30.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 0, 6, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(5, 12, 3, 5, '2024-02-17', '2024-02-17', 9, 7, 0, 1, 7, '77.7778', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 0, 8, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(6, 12, 3, 6, '2024-02-17', '2024-02-17', 12, 7, 3, 1, 7, '58.3333', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 0, 9, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(7, 12, 3, 7, '2024-02-17', '2024-02-17', 14, 11, 3, 1, 11, '78.5714', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 0, 11, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(8, 12, 3, 8, '2024-02-17', '2024-02-17', 7, 6, 1, 1, 6, '85.7143', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 0, 10, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(9, 12, 3, 9, '2024-02-17', '2024-02-17', 8, 3, 0, 1, 3, '37.5000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 0, 4, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(10, 12, 3, 10, '2024-02-17', '2024-02-17', 11, 5, 3, 1, 5, '45.4545', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 0, 8, 1, 1, '2024-03-24 08:00:22', '2024-03-24 08:00:22'),
(11, 21, NULL, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '500000.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 3, 8, 1, 1, '2024-04-05 14:21:32', '2024-04-05 14:21:32'),
(12, 21, NULL, 2, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '500000.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 3, 8, 1, 1, '2024-04-05 14:21:32', '2024-04-05 14:21:32'),
(13, 22, NULL, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '360000.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 0, 8, 1, 1, '2024-04-05 14:22:39', '2024-04-05 14:22:39'),
(14, 3, 3, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 4, 2, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2, 1, 1, '2024-04-06 05:06:11', '2024-04-06 05:06:11'),
(15, 3, 3, 2, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 2, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, '2024-04-06 05:06:11', '2024-04-06 05:06:11'),
(16, 24, 3, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2, 1, 1, '2024-04-06 05:33:49', '2024-04-06 05:33:49'),
(17, 24, 3, 2, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2, 1, 1, '2024-04-06 05:33:49', '2024-04-06 05:33:49'),
(18, 5, 3, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 4, 0, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, '2024-04-06 09:47:17', '2024-04-06 09:47:17'),
(19, 5, 3, 2, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 1, 1, 2, 1, 1, '2024-04-06 09:47:17', '2024-04-06 09:47:17'),
(20, 5, 3, 3, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 6, 0, 0, 0, 0, 0, 0, 1, 1, 2, 1, 1, '2024-04-06 09:47:17', '2024-04-06 09:47:17'),
(21, 5, 3, 4, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 1, 1, 2, 1, 1, '2024-04-06 09:47:17', '2024-04-06 09:47:17'),
(22, 6, 3, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 10, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, '2024-04-06 10:10:18', '2024-04-06 10:10:18'),
(23, 7, 3, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 4, 0, 0, 0, 0, 4, 0, 4, 1, 1, '2024-04-06 11:00:11', '2024-04-06 11:00:11'),
(24, 7, 3, 2, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 5, 0, 5, 1, 1, '2024-04-06 11:00:11', '2024-04-06 11:00:11'),
(25, 7, 3, 3, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 6, 0, 0, 0, 0, 6, 0, 6, 1, 1, '2024-04-06 11:00:11', '2024-04-06 11:00:11'),
(26, 7, 3, 4, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 8, 0, 0, 0, 0, 6, 0, 6, 1, 1, '2024-04-06 11:00:11', '2024-04-06 11:00:11'),
(27, 11, 3, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 4, 0, 0, 0, 4, 0, 4, 1, 1, '2024-04-06 11:25:14', '2024-04-06 11:25:14'),
(28, 11, 3, 2, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 6, 0, 0, 0, 6, 0, 6, 1, 1, '2024-04-06 11:25:14', '2024-04-06 11:25:14'),
(29, 11, 3, 3, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 8, 0, 0, 0, 6, 0, 6, 1, 1, '2024-04-06 11:25:14', '2024-04-06 11:25:14'),
(30, 14, 3, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 6, 0, 0, 6, 0, 6, 1, 1, '2024-04-06 11:52:00', '2024-04-06 11:52:00'),
(31, 14, 3, 2, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 4, 0, 0, 4, 0, 4, 1, 1, '2024-04-06 11:52:00', '2024-04-06 11:52:00'),
(32, 14, 3, 3, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 6, 0, 0, 6, 0, 6, 1, 1, '2024-04-06 11:52:00', '2024-04-06 11:52:00'),
(33, 19, 3, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 4, 0, 4, 0, 4, 1, 1, '2024-04-06 14:55:47', '2024-04-06 14:55:47'),
(34, 19, 3, 2, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2, 0, 2, 1, 1, '2024-04-06 14:55:47', '2024-04-06 14:55:47'),
(35, 19, 3, 3, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2, 0, 2, 1, 1, '2024-04-06 14:55:47', '2024-04-06 14:55:47'),
(36, 20, 3, 1, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 5, 0, 5, 1, 1, '2024-04-06 15:04:37', '2024-04-06 15:04:37'),
(37, 20, 3, 2, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 4, 0, 4, 1, 1, '2024-04-06 15:04:37', '2024-04-06 15:04:37'),
(38, 20, 3, 3, '2024-01-01', '2024-04-01', 0, 0, 0, 0, 0, '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 4, 0, 4, 1, 1, '2024-04-06 15:04:37', '2024-04-06 15:04:37');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` bigint(20) NOT NULL,
  `team_id` bigint(20) DEFAULT NULL,
  `programme_id` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `team_total` int(11) NOT NULL DEFAULT 0,
  `guest_total` int(11) NOT NULL DEFAULT 0,
  `grant_amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `retreat_leader_total` int(11) NOT NULL DEFAULT 0,
  `online_meeting_team_total` int(11) NOT NULL DEFAULT 0,
  `activities_total` int(11) NOT NULL DEFAULT 0,
  `summit_leader_total` int(11) NOT NULL DEFAULT 0,
  `recruit_total` int(11) NOT NULL DEFAULT 0,
  `initiative_total` int(11) NOT NULL DEFAULT 0,
  `team_mission_total` int(11) NOT NULL DEFAULT 0,
  `choir_member_total` int(11) NOT NULL DEFAULT 0,
  `other_activities_total` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `team_id`, `programme_id`, `date`, `memo`, `team_total`, `guest_total`, `grant_amount`, `retreat_leader_total`, `online_meeting_team_total`, `activities_total`, `summit_leader_total`, `recruit_total`, `initiative_total`, `team_mission_total`, `choir_member_total`, `other_activities_total`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 1, 12, '2024-02-17', NULL, 7, 3, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:15:27', '2024-03-22 04:15:27'),
(2, 2, 12, '2024-02-17', NULL, 9, 3, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:21:21', '2024-03-22 04:21:21'),
(3, 3, 12, '2024-02-17', NULL, 11, 3, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:22:01', '2024-03-22 04:22:01'),
(4, 4, 12, '2024-02-17', NULL, 3, 2, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:23:31', '2024-03-22 04:23:31'),
(5, 5, 12, '2024-02-17', NULL, 7, 0, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:29:19', '2024-03-22 04:29:19'),
(6, 6, 12, '2024-02-17', NULL, 7, 3, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:30:52', '2024-03-22 04:30:52'),
(7, 7, 12, '2024-02-17', NULL, 11, 3, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:32:42', '2024-03-22 04:32:42'),
(8, 8, 12, '2024-02-17', NULL, 6, 1, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:33:33', '2024-03-22 04:33:33'),
(9, 9, 12, '2024-02-17', '', 3, 0, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:34:29', '2024-03-24 08:09:32'),
(10, 10, 12, '2024-02-17', '', 5, 3, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-22 04:35:25', '2024-03-30 04:24:01'),
(11, 1, 21, '2024-03-30', '', 0, 0, '100000.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-30 04:16:29', '2024-03-30 04:16:29'),
(12, 1, 21, '2024-04-01', '', 0, 0, '100000.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-30 04:21:02', '2024-03-30 04:21:02'),
(13, 2, 21, '2024-03-30', '', 0, 0, '150000.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-30 04:21:51', '2024-03-30 04:21:51'),
(14, 2, 21, '2024-04-01', '', 0, 0, '150000.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-03-30 04:22:51', '2024-03-30 04:22:51'),
(15, 1, 22, '2024-01-05', '', 0, 0, '200000.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-05 13:57:40', '2024-04-05 13:57:40'),
(16, 1, 22, '2024-02-05', '', 0, 0, '160000.0000', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-05 13:58:31', '2024-04-05 13:58:31'),
(17, 1, 3, '2024-01-05', '', 0, 0, '0.0000', 2, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 04:51:22', '2024-04-06 04:51:22'),
(18, 2, 3, '2024-04-05', '', 0, 0, '0.0000', 2, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 04:51:50', '2024-04-06 04:51:50'),
(19, 1, 3, '2024-02-07', '', 0, 0, '0.0000', 2, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 04:52:19', '2024-04-06 04:52:19'),
(20, 2, 3, '2024-02-06', '', 0, 0, '0.0000', 2, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 04:52:48', '2024-04-06 04:52:48'),
(21, 1, 24, '2024-02-15', '', 0, 0, '0.0000', 0, 10, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 05:30:17', '2024-04-06 05:30:17'),
(22, 2, 24, '2024-02-21', '', 0, 0, '0.0000', 0, 11, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 05:30:57', '2024-04-06 05:30:57'),
(23, 1, 24, '2024-03-01', '', 0, 0, '0.0000', 0, 10, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 05:32:25', '2024-04-06 05:32:25'),
(24, 2, 24, '2024-03-06', '', 0, 0, '0.0000', 0, 11, 0, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 05:32:56', '2024-04-06 05:32:56'),
(25, 1, 5, '2024-02-08', '', 0, 0, '0.0000', 0, 0, 4, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 09:35:09', '2024-04-06 09:35:09'),
(26, 2, 5, '2024-02-12', '', 0, 0, '0.0000', 0, 0, 5, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 09:35:41', '2024-04-06 09:35:41'),
(27, 3, 5, '2024-02-22', '', 0, 0, '0.0000', 0, 0, 6, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 09:36:06', '2024-04-06 09:36:06'),
(28, 4, 5, '2024-03-08', '', 0, 0, '0.0000', 0, 0, 3, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 09:36:50', '2024-04-06 09:37:05'),
(29, 4, 5, '2024-03-28', '', 0, 0, '0.0000', 0, 0, 2, 0, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 09:38:03', '2024-04-06 09:38:03'),
(30, 1, 6, '2024-01-10', '', 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, 1, 1, '2024-04-06 09:58:48', '2024-04-06 09:58:48'),
(31, 1, 6, '2024-01-11', NULL, 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, '2024-04-06 10:03:33', '2024-04-06 10:03:33'),
(32, 1, 6, '2024-01-12', NULL, 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, '2024-04-06 10:03:33', '2024-04-06 10:03:33'),
(33, 1, 6, '2024-01-13', NULL, 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, '2024-04-06 10:03:33', '2024-04-06 10:03:33'),
(34, 1, 6, '2024-01-14', NULL, 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, '2024-04-06 10:03:33', '2024-04-06 10:03:33'),
(35, 1, 6, '2024-01-15', NULL, 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, '2024-04-06 10:03:33', '2024-04-06 10:03:33'),
(36, 1, 6, '2024-01-16', NULL, 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, '2024-04-06 10:03:33', '2024-04-06 10:03:33'),
(37, 1, 6, '2024-01-17', NULL, 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, '2024-04-06 10:03:33', '2024-04-06 10:03:33'),
(38, 1, 6, '2024-01-18', NULL, 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, '2024-04-06 10:03:33', '2024-04-06 10:03:33'),
(39, 1, 6, '2024-01-19', NULL, 0, 0, '0.0000', 0, 0, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, '2024-04-06 10:03:33', '2024-04-06 10:03:33'),
(40, 1, 7, '2024-01-10', '', 0, 0, '0.0000', 0, 0, 0, 0, 4, 0, 0, 0, 0, 1, 1, '2024-04-06 10:55:37', '2024-04-06 10:55:37'),
(41, 2, 7, '2024-01-10', '', 0, 0, '0.0000', 0, 0, 0, 0, 5, 0, 0, 0, 0, 1, 1, '2024-04-06 10:56:05', '2024-04-06 10:56:05'),
(42, 3, 7, '2024-02-02', '', 0, 0, '0.0000', 0, 0, 0, 0, 6, 0, 0, 0, 0, 1, 1, '2024-04-06 10:56:43', '2024-04-06 10:56:43'),
(43, 4, 7, '2024-02-16', '', 0, 0, '0.0000', 0, 0, 0, 0, 8, 0, 0, 0, 0, 1, 1, '2024-04-06 10:57:15', '2024-04-06 10:57:15'),
(44, 1, 11, '2024-01-16', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 4, 0, 0, 0, 1, 1, '2024-04-06 11:12:51', '2024-04-06 11:12:51'),
(45, 2, 11, '2024-01-18', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 6, 0, 0, 0, 1, 1, '2024-04-06 11:13:19', '2024-04-06 11:13:19'),
(46, 3, 11, '2024-01-25', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 8, 0, 0, 0, 1, 1, '2024-04-06 11:13:54', '2024-04-06 11:13:54'),
(47, 1, 14, '2024-02-07', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 0, 6, 0, 0, 1, 1, '2024-04-06 11:49:35', '2024-04-06 11:49:35'),
(48, 2, 14, '2024-01-10', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 0, 4, 0, 0, 1, 1, '2024-04-06 11:50:01', '2024-04-06 11:50:01'),
(49, 3, 14, '2024-01-18', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 0, 6, 0, 0, 1, 1, '2024-04-06 11:50:30', '2024-04-06 11:50:30'),
(50, 1, 19, '2024-01-10', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 0, 0, 4, 0, 1, 1, '2024-04-06 14:15:29', '2024-04-06 14:15:29'),
(51, 2, 19, '2024-02-07', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 0, 0, 2, 0, 1, 1, '2024-04-06 14:15:59', '2024-04-06 14:15:59'),
(52, 3, 19, '2024-02-08', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 0, 0, 2, 0, 1, 1, '2024-04-06 14:16:28', '2024-04-06 14:16:28'),
(53, 1, 20, '2024-02-06', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 5, 1, 1, '2024-04-06 15:01:59', '2024-04-06 15:01:59'),
(54, 2, 20, '2024-01-24', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 4, 1, 1, '2024-04-06 15:02:33', '2024-04-06 15:02:33'),
(55, 3, 20, '2024-02-08', '', 0, 0, '0.0000', 0, 0, 0, 0, 0, 0, 0, 0, 4, 1, 1, '2024-04-06 15:03:08', '2024-04-06 15:03:08');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) NOT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `connection` mediumtext DEFAULT NULL,
  `queue` mediumtext DEFAULT NULL,
  `payload` mediumtext DEFAULT NULL,
  `exception` mediumtext DEFAULT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_imports`
--

CREATE TABLE `file_imports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_dir` varchar(199) DEFAULT NULL,
  `file_name` varchar(199) DEFAULT NULL,
  `origin_name` varchar(199) DEFAULT NULL,
  `category` varchar(199) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_stats`
--

CREATE TABLE `meeting_stats` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2023_11_22_163725_create_permission_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(8, 'App\\Models\\User', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'create-proposal', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(2, 'edit-proposal', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(3, 'delete-proposal', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(4, 'view-proposal', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(5, 'create-budgeting', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(6, 'edit-budgeting', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(7, 'delete-budgeting', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(8, 'view-budgeting', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(9, 'create-log-frame', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(10, 'edit-log-frame', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(11, 'delete-log-frame', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(12, 'view-log-frame', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(13, 'create-action-plan', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(14, 'edit-action-plan', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(15, 'delete-action-plan', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(16, 'view-action-plan', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(17, 'create-agenda', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(18, 'edit-agenda', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(19, 'delete-agenda', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(20, 'view-agenda', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(21, 'create-attendance', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(22, 'edit-attendance', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(23, 'delete-attendance', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(24, 'view-attendance', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(25, 'create-activity-narrative', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(26, 'edit-activity-narrative', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(27, 'delete-activity-narrative', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(28, 'view-activity-narrative', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(29, 'create-case-study', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(30, 'edit-case-study', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(31, 'delete-case-study', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(32, 'view-case-study', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(33, 'create-user', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(34, 'edit-user', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(35, 'delete-user', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(36, 'view-user', 'web', '2023-11-23 10:18:39', '2023-11-23 10:18:39'),
(37, 'create-role', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(38, 'edit-role', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(39, 'delete-role', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(40, 'view-role', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(41, 'create-donor', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(42, 'edit-donor', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(43, 'delete-donor', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(44, 'view-donor', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(45, 'create-programme', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(46, 'edit-programme', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(47, 'delete-programme', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(48, 'view-programme', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(49, 'create-region', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(50, 'edit-region', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(51, 'delete-region', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(52, 'view-region', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(53, 'create-cohort', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(54, 'edit-cohort', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(55, 'delete-cohort', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(56, 'view-cohort', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(65, 'create-age-group', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(66, 'edit-age-group', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(67, 'delete-age-group', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(68, 'view-age-group', 'web', '2023-11-23 14:20:36', '2023-11-23 14:20:36'),
(74, 'create-disability', 'web', '2023-11-27 07:42:29', '2023-11-27 07:42:29'),
(75, 'edit-disability', 'web', '2023-11-27 07:42:29', '2023-11-27 07:42:29'),
(76, 'delete-disability', 'web', '2023-11-27 07:42:29', '2023-11-27 07:42:29'),
(77, 'view-disability', 'web', '2023-11-27 07:42:29', '2023-11-27 07:42:29'),
(78, 'approve-proposal', 'web', '2023-11-27 08:02:35', '2023-11-27 08:02:35'),
(79, 'approve-budgeting', 'web', '2023-11-27 08:02:35', '2023-11-27 08:02:35'),
(80, 'approve-action-plan', 'web', '2023-11-27 08:02:35', '2023-11-27 08:02:35'),
(81, 'approve-agenda', 'web', '2023-11-27 08:02:35', '2023-11-27 08:02:35'),
(82, 'approve-activity-narrative', 'web', '2023-11-27 08:02:35', '2023-11-27 08:02:35'),
(83, 'edit-code-prefix', 'web', '2023-12-01 10:44:13', '2023-12-01 10:44:13'),
(84, 'view-code-prefix', 'web', '2023-12-01 10:44:13', '2023-12-01 10:44:13'),
(85, 'create-deadline', 'web', '2024-02-15 13:35:13', '2024-02-15 13:35:13'),
(86, 'edit-deadline', 'web', '2024-02-15 13:35:13', '2024-02-15 13:35:13'),
(87, 'delete-deadline', 'web', '2024-02-15 13:35:13', '2024-02-15 13:35:13'),
(88, 'view-deadline', 'web', '2024-02-15 13:35:13', '2024-02-15 13:35:13');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `id` bigint(20) NOT NULL,
  `tid` bigint(20) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `metric` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `target_amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `amount_perc` decimal(16,2) NOT NULL DEFAULT 0.00,
  `amount_perc_by` date DEFAULT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `extra_score` int(11) NOT NULL DEFAULT 0,
  `every_amount_perc` decimal(16,2) NOT NULL DEFAULT 0.00,
  `above_amount_perc` decimal(16,2) NOT NULL DEFAULT 0.00,
  `max_extra_score` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`id`, `tid`, `is_active`, `metric`, `name`, `memo`, `target_amount`, `amount_perc`, `amount_perc_by`, `score`, `extra_score`, `every_amount_perc`, `above_amount_perc`, `max_extra_score`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Attendance', 'MOG Group Mission 2', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:45:41', '2024-03-20 10:45:41'),
(2, 2, 1, 'Attendance', 'MOG Breakfast', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:46:01', '2024-03-20 10:46:01'),
(3, 3, 1, 'Leader-Retreat', 'Leaders Retreat', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:46:20', '2024-03-20 10:46:20'),
(4, 4, 1, 'Attendance', 'MOG Group Online Meeting', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:46:42', '2024-03-20 10:46:42'),
(5, 5, 1, 'Team-Bonding', 'Individual Team Bonding Initiatives', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:54:41', '2024-03-20 10:54:41'),
(6, 6, 1, 'Summit-Meeting', 'Summit Meetings', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:54:41', '2024-03-20 10:54:41'),
(7, 7, 1, 'Member-Recruitment', 'New Member Recruitment', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:54:41', '2024-03-20 10:54:41'),
(8, 8, 1, 'Attendance', 'MOG Prayers', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:54:41', '2024-03-20 10:54:41'),
(9, 9, 1, 'Attendance', 'All Church Conferences', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:54:41', '2024-03-20 10:54:41'),
(10, 10, 1, 'Attendance', 'MOG Conference', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:54:41', '2024-03-20 10:54:41'),
(11, 11, 1, 'New-Initiative', 'New Initiative', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:54:41', '2024-03-20 10:54:41'),
(12, 12, 1, 'Attendance', 'Lovers Dinner', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:54:41', '2024-03-20 10:54:41'),
(13, 13, 1, 'Attendance', 'Members Serving In Church', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:57:06', '2024-03-20 10:57:06'),
(14, 14, 1, 'Team-Mission', 'Team Missions', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:57:06', '2024-03-20 10:57:06'),
(15, 15, 1, 'Attendance', 'MOG Retreat 1', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:57:06', '2024-03-20 10:57:06'),
(16, 16, 1, 'Attendance', 'Church Prayer Rally', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:57:06', '2024-03-20 10:57:06'),
(17, 17, 1, 'Attendance', 'Church Kesha - Monthly', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:57:06', '2024-03-20 10:57:06'),
(18, 18, 1, 'Attendance', 'Serving At Destiny World', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:57:06', '2024-03-20 10:57:06'),
(19, 19, 1, 'Choir-Member', 'MOG Choir', NULL, '0.0000', '0.00', '2024-04-06', 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:57:06', '2024-04-06 14:14:28'),
(20, 20, 1, 'Other-Activities', 'Any Other Activities', NULL, '0.0000', '0.00', NULL, 0, 0, '0.00', '0.00', 0, 1, 1, '2024-03-20 10:57:06', '2024-03-20 10:57:06'),
(21, 21, 1, 'Finance', 'Finance Target 1', 'Finance Target 1 by 30/07/2024', '400000.0000', '50.00', '2024-07-30', 5, 1, '5.00', '50.00', 3, 1, 1, '2024-03-30 03:53:07', '2024-03-30 03:53:07'),
(22, 22, 1, 'Finance', 'Finance Target 2', 'Finance Target 2 by 30/11/2024', '400000.0000', '90.00', '2024-11-30', 8, 0, '0.00', '0.00', 0, 1, 1, '2024-03-30 03:55:45', '2024-04-05 13:52:21'),
(23, 23, 1, 'Finance', 'Finance Overall', 'Finance Overall by 2nd Dec', '400000.0000', '100.00', '2024-12-02', 3, 1, '50.00', '100.00', 3, 1, 1, '2024-03-30 04:10:41', '2024-03-30 04:10:41'),
(24, 24, 1, 'Online-Meeting', 'Online Meeting', 'At least per month for 10 months', '0.0000', '0.00', '2024-04-06', 0, 0, '0.00', '0.00', 0, 1, 1, '2024-04-06 05:20:05', '2024-04-06 05:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `rating_scales`
--

CREATE TABLE `rating_scales` (
  `id` bigint(20) NOT NULL,
  `tid` bigint(20) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `retreat_meeting_no` int(11) NOT NULL DEFAULT 0,
  `retreat_leader_no` int(11) NOT NULL DEFAULT 0,
  `retreat_score` int(11) NOT NULL DEFAULT 0,
  `online_meeting_no` int(11) NOT NULL DEFAULT 0,
  `online_meeting_score` int(11) NOT NULL DEFAULT 0,
  `tb_activities_no` int(11) NOT NULL DEFAULT 0,
  `tb_activities_score` int(11) NOT NULL DEFAULT 0,
  `tb_activities_extra_score` int(11) NOT NULL DEFAULT 0,
  `tb_activities_extra_min_no` int(11) NOT NULL DEFAULT 0,
  `tb_activities_extra_max_no` int(11) NOT NULL DEFAULT 0,
  `summit_meeting_no` int(11) NOT NULL DEFAULT 0,
  `summit_leaders_no` int(11) NOT NULL DEFAULT 0,
  `summit_meeting_score` int(11) NOT NULL DEFAULT 0,
  `recruit_no` int(11) NOT NULL DEFAULT 0,
  `recruit_score` int(11) NOT NULL DEFAULT 0,
  `recruit_max_points` int(11) NOT NULL DEFAULT 0,
  `initiative_no` int(11) NOT NULL DEFAULT 0,
  `initiative_max_no` int(11) NOT NULL DEFAULT 0,
  `initiative_score` int(11) NOT NULL DEFAULT 0,
  `mission_no` int(11) NOT NULL DEFAULT 0,
  `mission_score` int(11) NOT NULL DEFAULT 0,
  `choir_no` int(11) NOT NULL DEFAULT 0,
  `choir_score` int(11) NOT NULL DEFAULT 0,
  `other_activities_no` int(11) NOT NULL DEFAULT 0,
  `other_activities_score` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating_scales`
--

INSERT INTO `rating_scales` (`id`, `tid`, `is_active`, `retreat_meeting_no`, `retreat_leader_no`, `retreat_score`, `online_meeting_no`, `online_meeting_score`, `tb_activities_no`, `tb_activities_score`, `tb_activities_extra_score`, `tb_activities_extra_min_no`, `tb_activities_extra_max_no`, `summit_meeting_no`, `summit_leaders_no`, `summit_meeting_score`, `recruit_no`, `recruit_score`, `recruit_max_points`, `initiative_no`, `initiative_max_no`, `initiative_score`, `mission_no`, `mission_score`, `choir_no`, `choir_score`, `other_activities_no`, `other_activities_score`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(3, 1, 1, 1, 2, 1, 1, 1, 4, 1, 1, 4, 6, 10, 1, 1, 1, 1, 6, 1, 6, 1, 6, 6, 4, 4, 1, 1, 1, 1, '2024-03-23 21:50:14', '2024-04-06 15:01:24');

-- --------------------------------------------------------

--
-- Table structure for table `rating_scale_items`
--

CREATE TABLE `rating_scale_items` (
  `id` bigint(20) NOT NULL,
  `rating_scale_id` bigint(20) DEFAULT NULL,
  `min` int(11) NOT NULL DEFAULT 0,
  `max` int(11) NOT NULL DEFAULT 0,
  `point` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating_scale_items`
--

INSERT INTO `rating_scale_items` (`id`, `rating_scale_id`, `min`, `max`, `point`, `created_at`, `updated_at`) VALUES
(41, 3, 0, 9, 1, '2024-04-06 15:01:24', '2024-04-06 15:01:24'),
(42, 3, 10, 19, 2, '2024-04-06 15:01:24', '2024-04-06 15:01:24'),
(43, 3, 20, 29, 3, '2024-04-06 15:01:24', '2024-04-06 15:01:24'),
(44, 3, 30, 39, 4, '2024-04-06 15:01:24', '2024-04-06 15:01:24'),
(45, 3, 40, 49, 5, '2024-04-06 15:01:24', '2024-04-06 15:01:24'),
(46, 3, 50, 59, 6, '2024-04-06 15:01:24', '2024-04-06 15:01:24'),
(47, 3, 60, 69, 7, '2024-04-06 15:01:24', '2024-04-06 15:01:24'),
(48, 3, 70, 79, 8, '2024-04-06 15:01:24', '2024-04-06 15:01:24'),
(49, 3, 80, 89, 9, '2024-04-06 15:01:24', '2024-04-06 15:01:24'),
(50, 3, 90, 100, 10, '2024-04-06 15:01:24', '2024-04-06 15:01:24');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 'Super-Admin', 'web', NULL, NULL, '2023-11-23 10:29:25', '2023-11-23 10:29:25'),
(8, 'Administrator', 'web', 1, 1, '2023-11-23 10:29:25', '2023-11-23 10:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 8),
(2, 1),
(2, 8),
(3, 1),
(3, 8),
(4, 1),
(4, 8),
(5, 1),
(5, 8),
(6, 1),
(6, 8),
(7, 1),
(7, 8),
(8, 1),
(8, 8),
(9, 1),
(9, 8),
(10, 1),
(10, 8),
(11, 1),
(11, 8),
(12, 1),
(12, 8),
(13, 1),
(13, 8),
(14, 1),
(14, 8),
(15, 1),
(15, 8),
(16, 1),
(16, 8),
(17, 1),
(17, 8),
(18, 1),
(18, 8),
(19, 1),
(19, 8),
(20, 1),
(20, 8),
(21, 1),
(21, 8),
(22, 1),
(22, 8),
(23, 1),
(23, 8),
(24, 1),
(24, 8),
(25, 1),
(25, 8),
(26, 1),
(26, 8),
(27, 1),
(27, 8),
(28, 1),
(28, 8),
(29, 1),
(29, 8),
(30, 1),
(30, 8),
(31, 1),
(31, 8),
(32, 1),
(32, 8),
(33, 1),
(33, 8),
(34, 1),
(34, 8),
(35, 1),
(35, 8),
(36, 1),
(36, 8),
(37, 1),
(37, 8),
(38, 1),
(38, 8),
(39, 1),
(39, 8),
(40, 1),
(40, 8),
(41, 1),
(41, 8),
(42, 1),
(42, 8),
(43, 1),
(43, 8),
(44, 1),
(44, 8),
(45, 1),
(45, 8),
(46, 1),
(46, 8),
(47, 1),
(47, 8),
(48, 1),
(48, 8),
(49, 1),
(49, 8),
(50, 1),
(50, 8),
(51, 1),
(51, 8),
(52, 1),
(52, 8),
(53, 1),
(53, 8),
(54, 1),
(54, 8),
(55, 1),
(55, 8),
(56, 1),
(56, 8),
(65, 1),
(65, 8),
(66, 1),
(66, 8),
(67, 1),
(67, 8),
(68, 1),
(68, 8),
(74, 1),
(74, 8),
(75, 1),
(75, 8),
(76, 1),
(76, 8),
(77, 1),
(77, 8),
(78, 1),
(78, 8),
(79, 1),
(79, 8),
(80, 1),
(80, 8),
(81, 1),
(81, 8),
(82, 1),
(82, 8),
(83, 1),
(83, 8),
(84, 1),
(84, 8),
(85, 1),
(85, 8),
(86, 1),
(86, 8),
(87, 1),
(87, 8),
(88, 1),
(88, 8);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint(20) NOT NULL,
  `tid` bigint(20) NOT NULL DEFAULT 0,
  `name` varchar(50) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `total` int(11) NOT NULL DEFAULT 0,
  `diasp_total` int(11) NOT NULL DEFAULT 0,
  `member_list` varchar(250) DEFAULT NULL,
  `max_guest` int(11) NOT NULL DEFAULT 0,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `tid`, `name`, `is_active`, `total`, `diasp_total`, `member_list`, `max_guest`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 1, 'Team John', 1, 10, 0, NULL, 3, 1, 1, '2024-03-20 12:09:22', '2024-03-23 05:49:02'),
(2, 2, 'Team Mathew', 1, 11, 0, NULL, 3, 1, 1, '2024-03-20 12:09:56', '2024-03-23 05:49:02'),
(3, 3, 'Team James', 1, 12, 0, NULL, 3, 1, 1, '2024-03-20 12:10:12', '2024-03-23 05:49:02'),
(4, 4, 'Team Titus', 1, 10, 0, 'Titus', 2, 1, 1, '2024-03-20 12:12:21', '2024-03-30 03:47:57'),
(5, 5, 'Team Timothy', 1, 9, 0, NULL, 0, 1, 1, '2024-03-20 12:12:21', '2024-03-20 12:12:21'),
(6, 6, 'Team Peter', 1, 12, 0, NULL, 3, 1, 1, '2024-03-20 12:12:21', '2024-03-23 05:49:02'),
(7, 7, 'Team Jude', 1, 14, 0, NULL, 3, 1, 1, '2024-03-20 12:12:21', '2024-03-23 05:49:02'),
(8, 8, 'Team Andrew', 1, 7, 0, NULL, 1, 1, 1, '2024-03-20 12:12:21', '2024-03-23 05:49:02'),
(9, 9, 'Team Paul', 1, 8, 0, NULL, 0, 1, 1, '2024-03-20 12:12:21', '2024-03-20 12:12:21'),
(10, 10, 'Team Luke', 1, 11, 0, NULL, 3, 1, 1, '2024-03-20 12:12:21', '2024-03-23 05:49:02');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `letter_head` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `name`, `email`, `phone`, `logo`, `letter_head`, `created_at`, `updated_at`) VALUES
(1, 'PROFFER SYSTEMS', 'info@proffersystems.org', '0207789948', NULL, NULL, '2023-02-19 06:17:30', '2023-02-19 06:17:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `profile_pic` varchar(199) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `role_id`, `profile_pic`, `phone`, `password`, `is_active`, `remember_token`, `created_by`, `ins`, `created_at`, `updated_at`) VALUES
(1, 'Super', 'Admin', 'admin@gmail.com', 1, NULL, '100100', '$2a$10$1s62tafJHT/lAjKO4BJqcuy0W7GjBhD2ZwRKhKvFK9DjtwT0JG9zS', 1, '', NULL, 1, '2023-02-19 06:58:52', '2024-01-16 08:40:45'),
(5, 'Peter', 'Owaga', 'powaga@gmail.com', NULL, NULL, '123456', '$2y$10$9p133UjdcLlM.FOqpaeqMemRYzE.vi3Z4BXRa4hwq3R83nwLUtgH6', 1, NULL, 1, 1, '2024-03-20 10:05:29', '2024-03-20 12:22:39'),
(6, 'Luke', 'Doe', 'luke@gmail.com', NULL, NULL, '123456', '$2y$10$xu5LMOY4KUoKXg7AQoysO.jn83bSu5tMKyS08XlNOfikwar10QIVG', 1, NULL, 1, 1, '2024-03-20 12:54:12', '2024-03-20 12:54:12'),
(7, 'Paul', 'Doe', 'paul@gmail.com', NULL, NULL, '123456', '$2y$10$NGYYkr3GgWGgUAgfcHLOheOXSXNwJvM3rlG2WSDSpag9UbNhHdlg6', 1, NULL, 1, 1, '2024-03-20 12:54:35', '2024-03-20 12:54:35'),
(8, 'Andrew', 'Doe', 'andrew@gmail.com', NULL, NULL, '123456', '$2y$10$ph.TvR7DLgxjnOltidoh0eYwHomNcNBue5D8RQ.UIIiva2Ng2.OX2', 1, NULL, 1, 1, '2024-03-20 12:55:02', '2024-03-20 12:55:02'),
(9, 'Jude', 'Doe', 'jude@gmail.com', NULL, NULL, '123456', '$2y$10$.Ji.pQDT2N1zVvD5t8tNfOmUdlhKxsUqaLZ7a0BgtYytGdlVp2sHy', 1, NULL, 1, 1, '2024-03-20 12:55:34', '2024-03-20 12:55:34'),
(10, 'Timothy', 'Doe', 'timothy@gmail.com', NULL, NULL, '123456', '$2y$10$5bAdBuROT65vWcovRO3FFe1d6n8BiCbEpOf0vR.AavGnem0PMJA4m', 1, NULL, 1, 1, '2024-03-20 12:56:04', '2024-03-20 12:56:04'),
(11, 'Titus', 'Doe', 'titus@gmail.com', NULL, NULL, '123456', '$2y$10$Tklc1tR4.z8yJdo/lEwVW.OnWrjH2FL6Qx.HfMpjMhHehwH2f4Gr6', 1, NULL, 1, 1, '2024-03-20 12:56:28', '2024-03-20 12:56:28'),
(12, 'James', 'Doe', 'james@gmail.com', NULL, NULL, '123456', '$2y$10$gdtM.3lRh9ZxflXT5Ai9QezuEQBWSjL0wn8PK6BE/JlwxsOPgnA2S', 1, NULL, 1, 1, '2024-03-20 12:56:55', '2024-03-20 12:56:55'),
(13, 'Mathew', 'Doe', 'mathew@gmail.com', NULL, NULL, '123456', '$2y$10$L8L/w06wpfXMjkKYkUyM/eyZec.RXYtUzqOarlzqsjPtjlNRVsh3a', 1, NULL, 1, 1, '2024-03-20 12:57:38', '2024-03-20 12:57:38');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) NOT NULL,
  `rel_id` bigint(20) DEFAULT NULL,
  `is_super` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `rel_id`, `is_super`, `name`, `phone`, `email`, `address`, `country`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Super Admin', NULL, 'demo@admin.com', NULL, NULL, 1, 1, '2023-05-22 07:59:42', '2023-05-22 07:59:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assign_scores`
--
ALTER TABLE `assign_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `programme_id` (`programme_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `rating_scale_id` (`rating_scale_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_imports`
--
ALTER TABLE `file_imports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meeting_stats`
--
ALTER TABLE `meeting_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rating_scales`
--
ALTER TABLE `rating_scales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rating_scale_items`
--
ALTER TABLE `rating_scale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rating_scale_id` (`rating_scale_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assign_scores`
--
ALTER TABLE `assign_scores`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `file_imports`
--
ALTER TABLE `file_imports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meeting_stats`
--
ALTER TABLE `meeting_stats`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `rating_scales`
--
ALTER TABLE `rating_scales`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rating_scale_items`
--
ALTER TABLE `rating_scale_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assign_scores`
--
ALTER TABLE `assign_scores`
  ADD CONSTRAINT `assign_scores_ibfk_1` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assign_scores_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `assign_scores_ibfk_3` FOREIGN KEY (`rating_scale_id`) REFERENCES `rating_scales` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rating_scale_items`
--
ALTER TABLE `rating_scale_items`
  ADD CONSTRAINT `rating_scale_items_ibfk_1` FOREIGN KEY (`rating_scale_id`) REFERENCES `rating_scales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
