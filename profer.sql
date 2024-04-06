-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 22, 2023 at 03:25 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `profer`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_plans`
--

CREATE TABLE `action_plans` (
  `id` bigint(20) NOT NULL,
  `tid` bigint(20) NOT NULL DEFAULT 0,
  `date` date DEFAULT NULL,
  `status` enum('pending','approved','review') NOT NULL DEFAULT 'pending',
  `status_note` varchar(255) DEFAULT NULL,
  `proposal_id` bigint(20) DEFAULT NULL,
  `programme_id` bigint(20) DEFAULT NULL,
  `main_assigned_to` varchar(50) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `action_plans`
--

INSERT INTO `action_plans` (`id`, `tid`, `date`, `status`, `status_note`, `proposal_id`, `programme_id`, `main_assigned_to`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-02-26', 'pending', NULL, 1, 3, 'Ambrose', 1, 1, '2023-02-26 05:07:55', '2023-03-08 02:58:37'),
(2, 2, '2023-03-20', 'pending', NULL, 2, 1, 'Kagiri', 1, 1, '2023-03-20 05:33:21', '2023-03-20 05:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `action_plan_activities`
--

CREATE TABLE `action_plan_activities` (
  `id` bigint(20) NOT NULL,
  `action_plan_id` bigint(20) DEFAULT NULL,
  `activity_id` bigint(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `resources` varchar(250) DEFAULT NULL,
  `assigned_to` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `action_plan_activities`
--

INSERT INTO `action_plan_activities` (`id`, `action_plan_id`, `activity_id`, `start_date`, `end_date`, `resources`, `assigned_to`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2023-02-01', '2023-02-28', 'Vehicles, Speakers, Plackards', 'Kagiri Antony', '2023-02-26 05:07:55', '2023-03-07 04:42:14'),
(2, 2, 11, '2023-03-20', '2023-03-31', 'Health Kits, Consultants', 'Kagiri', '2023-03-20 05:33:21', '2023-03-20 18:08:17'),
(3, 2, 10, '2023-03-20', '2023-03-31', 'Treated mosquito nets', 'Dancan', '2023-03-20 08:24:24', '2023-03-20 08:24:24');

-- --------------------------------------------------------

--
-- Table structure for table `action_plan_cohorts`
--

CREATE TABLE `action_plan_cohorts` (
  `id` bigint(20) NOT NULL,
  `action_plan_id` bigint(20) DEFAULT NULL,
  `plan_activity_id` bigint(20) DEFAULT NULL,
  `cohort_id` bigint(20) DEFAULT NULL,
  `activity_id` bigint(20) DEFAULT NULL,
  `target_no` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `action_plan_cohorts`
--

INSERT INTO `action_plan_cohorts` (`id`, `action_plan_id`, `plan_activity_id`, `cohort_id`, `activity_id`, `target_no`, `created_at`, `updated_at`) VALUES
(6, 1, 1, 1, NULL, 100, '2023-03-07 07:42:14', '2023-03-08 03:54:05'),
(10, 2, 2, 2, 6, 200, '2023-03-20 08:42:28', '2023-03-20 15:50:09'),
(11, 2, 3, 1, 10, 100, '2023-03-20 11:53:44', '2023-03-20 11:53:44');

-- --------------------------------------------------------

--
-- Table structure for table `action_plan_regions`
--

CREATE TABLE `action_plan_regions` (
  `id` bigint(20) NOT NULL,
  `action_plan_id` bigint(20) DEFAULT NULL,
  `plan_activity_id` bigint(20) DEFAULT NULL,
  `activity_id` bigint(20) DEFAULT NULL,
  `region_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `action_plan_regions`
--

INSERT INTO `action_plan_regions` (`id`, `action_plan_id`, `plan_activity_id`, `activity_id`, `region_id`, `created_at`, `updated_at`) VALUES
(10, 1, 1, NULL, 1, '2023-03-07 07:42:14', '2023-03-07 07:42:14'),
(11, 1, 1, NULL, 2, '2023-03-07 07:42:14', '2023-03-07 07:42:14'),
(18, 2, 3, NULL, 1, '2023-03-20 11:24:24', '2023-03-20 11:24:24'),
(19, 2, 3, NULL, 2, '2023-03-20 11:24:24', '2023-03-20 11:24:24'),
(20, 2, 2, 11, 1, '2023-03-20 21:08:17', '2023-03-20 21:08:17'),
(21, 2, 2, 11, 2, '2023-03-20 21:08:17', '2023-03-20 21:08:17');

-- --------------------------------------------------------

--
-- Table structure for table `age_groups`
--

CREATE TABLE `age_groups` (
  `id` bigint(20) NOT NULL,
  `bracket` varchar(100) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `age_groups`
--

INSERT INTO `age_groups` (`id`, `bracket`, `ins`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '< 18', 1, 1, '2023-02-01 20:51:19', '2023-02-01 20:51:19'),
(2, '18-35', 1, 1, '2023-02-01 20:51:19', '2023-02-01 20:51:19'),
(3, '36 and above', 1, 1, '2023-02-01 20:52:05', '2023-02-01 20:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `cohorts`
--

CREATE TABLE `cohorts` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cohorts`
--

INSERT INTO `cohorts` (`id`, `name`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 'Youth', 1, 1, '2023-01-22 14:25:55', '2023-01-22 14:25:55'),
(2, 'Students', 1, 1, '2023-01-22 14:25:55', '2023-01-22 14:25:55'),
(3, 'Teachers', 1, 1, '2023-01-22 14:25:55', '2023-01-22 14:25:55'),
(4, 'Doctors', 1, 1, '2023-01-22 14:25:55', '2023-01-22 14:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `letter_head` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `email`, `phone`, `logo`, `letter_head`, `created_at`, `updated_at`) VALUES
(1, 'Eteral Solutions', 'eteral@gmail.com', '0200-00100', NULL, NULL, '2023-02-19 06:17:30', '2023-02-19 06:17:30');

-- --------------------------------------------------------

--
-- Table structure for table `disabilities`
--

CREATE TABLE `disabilities` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `disabilities`
--

INSERT INTO `disabilities` (`id`, `name`, `code`, `ins`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Intellectual Disability', 'ID', 1, 1, '2023-02-01 21:04:38', '2023-02-01 21:04:38'),
(2, 'Speech Disorder', 'SD', 1, 1, '2023-02-01 21:04:38', '2023-02-01 21:04:38'),
(3, 'Vision Impairment', 'VI', 1, 1, '2023-02-01 21:05:10', '2023-02-01 21:05:10'),
(4, 'Hearing Impairment', 'HI', 1, 1, '2023-02-01 21:05:10', '2023-02-01 21:05:10');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `contact_person` varchar(50) DEFAULT NULL,
  `alternative_email` varchar(20) DEFAULT NULL,
  `alternative_phone` varchar(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `name`, `phone`, `email`, `contact_person`, `alternative_email`, `alternative_phone`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 'County Government', '45256352', 'county@email.com', 'John Doe', 'john@account.com', '1234567892', 1, 1, '2023-02-03 13:36:35', '2023-02-11 14:48:06'),
(3, 'WHO', '00200333', 'who@account.com', 'John Doe', 'john@account.com', '12345', 1, 1, '2023-02-12 11:22:51', '2023-02-12 11:22:51');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `log_frames`
--

CREATE TABLE `log_frames` (
  `id` bigint(20) NOT NULL,
  `tid` bigint(20) NOT NULL DEFAULT 0,
  `proposal_id` bigint(20) DEFAULT NULL,
  `goal_summary` varchar(255) DEFAULT NULL,
  `goal_indicator` varchar(255) DEFAULT NULL,
  `goal_baseline` varchar(255) DEFAULT NULL,
  `goal_target` varchar(255) DEFAULT NULL,
  `goal_data_source` varchar(255) DEFAULT NULL,
  `goal_frequency` varchar(255) DEFAULT NULL,
  `goal_assign_to` varchar(20) DEFAULT NULL,
  `outcome_summary` varchar(255) DEFAULT NULL,
  `outcome_indicator` varchar(255) DEFAULT NULL,
  `outcome_baseline` varchar(255) DEFAULT NULL,
  `outcome_target` varchar(255) DEFAULT NULL,
  `outcome_data_source` varchar(255) DEFAULT NULL,
  `outcome_frequency` varchar(255) DEFAULT NULL,
  `outcome_assign_to` varchar(255) DEFAULT NULL,
  `result_summary` varchar(255) DEFAULT NULL,
  `result_indicator` varchar(255) DEFAULT NULL,
  `result_baseline` varchar(255) DEFAULT NULL,
  `result_target` varchar(255) DEFAULT NULL,
  `result_data_source` varchar(255) DEFAULT NULL,
  `result_frequency` varchar(255) DEFAULT NULL,
  `result_assign_to` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `log_frames`
--

INSERT INTO `log_frames` (`id`, `tid`, `proposal_id`, `goal_summary`, `goal_indicator`, `goal_baseline`, `goal_target`, `goal_data_source`, `goal_frequency`, `goal_assign_to`, `outcome_summary`, `outcome_indicator`, `outcome_baseline`, `outcome_target`, `outcome_data_source`, `outcome_frequency`, `outcome_assign_to`, `result_summary`, `result_indicator`, `result_baseline`, `result_target`, `result_data_source`, `result_frequency`, `result_assign_to`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'r', 'r', 'r', 'r', 'r', 'r', 'r', 1, 1, '2023-03-16 12:48:36', '2023-03-16 12:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `narratives`
--

CREATE TABLE `narratives` (
  `id` bigint(20) NOT NULL,
  `tid` int(11) NOT NULL DEFAULT 0,
  `date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `note` varchar(255) DEFAULT NULL,
  `proposal_id` bigint(20) DEFAULT NULL,
  `action_plan_id` bigint(20) DEFAULT NULL,
  `proposal_item_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `narratives`
--

INSERT INTO `narratives` (`id`, `tid`, `date`, `status`, `note`, `proposal_id`, `action_plan_id`, `proposal_item_id`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(3, 1, NULL, 'pending', 'Sample Narrative Report', 1, NULL, 3, 1, 1, '2023-02-05 08:26:40', '2023-02-12 15:21:14'),
(4, 2, '2023-03-21', 'pending', 'Note2', 1, 1, 2, 1, 1, '2023-03-20 04:09:20', '2023-03-20 04:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `narrative_items`
--

CREATE TABLE `narrative_items` (
  `id` bigint(20) NOT NULL,
  `narrative_id` bigint(20) DEFAULT NULL,
  `proposal_id` bigint(20) DEFAULT NULL,
  `proposal_item_id` bigint(20) DEFAULT NULL,
  `narrative_pointer_id` bigint(20) DEFAULT NULL,
  `response` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `narrative_items`
--

INSERT INTO `narrative_items` (`id`, `narrative_id`, `proposal_id`, `proposal_item_id`, `narrative_pointer_id`, `response`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 3, 1, 'Activity was inline with the action plan', '2023-02-05 11:26:40', '2023-02-12 14:57:14'),
(2, 3, 1, 3, 2, 'Self employment through talent', '2023-02-05 11:26:40', '2023-02-05 11:26:40'),
(3, 3, 1, 3, 3, '1', '2023-02-05 11:26:40', '2023-02-12 14:57:14'),
(4, 3, 1, 3, 4, 'Proved the efficacy of the activity', '2023-02-05 11:26:40', '2023-02-05 11:26:40'),
(5, 3, 1, 3, 5, 'none', '2023-02-05 11:26:40', '2023-02-12 14:57:14'),
(6, 3, 1, 3, 6, 'significance of naturing talents', '2023-02-05 11:26:40', '2023-02-05 11:26:40'),
(7, 3, 1, 3, 7, 'n/a', '2023-02-05 11:26:40', '2023-02-05 11:26:40'),
(8, 3, 1, 3, 8, 'n/a', '2023-02-05 11:26:40', '2023-02-05 11:26:40'),
(9, 3, 1, 3, 9, 'n/a', '2023-02-05 11:26:40', '2023-02-05 11:26:40'),
(10, 3, 1, 3, 10, 'Self employment', '2023-02-05 11:26:40', '2023-02-12 14:57:14'),
(11, 3, 1, 3, 11, 'n/a', '2023-02-05 11:26:40', '2023-02-05 11:26:40'),
(12, 4, 1, 2, 1, 'Deviation from original plan', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(13, 4, 1, 2, 2, 'Results attained', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(14, 4, 1, 2, 3, '10', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(15, 4, 1, 2, 4, 'How has number of attained results has affected the activity', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(16, 4, 1, 2, 5, 'Administrative & Logisitical Challenges', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(17, 4, 1, 2, 6, 'Lesson from the activity implementation', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(18, 4, 1, 2, 7, 'List of publications used in the implementation', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(19, 4, 1, 2, 8, 'Future prospects and emerging areas', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(20, 4, 1, 2, 9, 'Case Study', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(21, 4, 1, 2, 10, 'Activity Lasting Impact', '2023-03-20 07:09:20', '2023-03-20 07:09:20'),
(22, 4, 1, 2, 11, 'Future prospects to continue working on the activity', '2023-03-20 07:09:20', '2023-03-20 07:09:20');

-- --------------------------------------------------------

--
-- Table structure for table `narrative_pointers`
--

CREATE TABLE `narrative_pointers` (
  `id` bigint(20) NOT NULL,
  `pointer` varchar(20) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `narrative_pointers`
--

INSERT INTO `narrative_pointers` (`id`, `pointer`, `value`) VALUES
(1, 'pt_a', 'Deviation from original plan'),
(2, 'pt_b', 'Results attained'),
(3, 'pt_c', 'Number of attained results'),
(4, 'pt_d', 'How has number of attained results has affected the activity?'),
(5, 'pt_e', 'Administrative & Logisitical Challenges'),
(6, 'pt_f', 'Lesson from the activity implementation'),
(7, 'pt_g', 'List of publications used in the implementation'),
(8, 'pt_h', 'Future prospects and emerging areas'),
(9, 'pt_i', 'Case Study'),
(10, 'pt_j', 'Activity Lasting Impact'),
(11, 'pt_k', 'Future prospects to continue working on the activity');

-- --------------------------------------------------------

--
-- Table structure for table `participant_lists`
--

CREATE TABLE `participant_lists` (
  `id` bigint(20) NOT NULL,
  `tid` bigint(20) NOT NULL DEFAULT 0,
  `proposal_id` bigint(20) DEFAULT NULL,
  `action_plan_id` bigint(20) DEFAULT NULL,
  `proposal_item_id` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `region_id` bigint(20) DEFAULT NULL,
  `cohort_id` bigint(20) DEFAULT NULL,
  `prepared_by` varchar(50) DEFAULT NULL,
  `male_count` int(11) NOT NULL DEFAULT 0,
  `female_count` int(11) NOT NULL DEFAULT 0,
  `total_count` int(11) NOT NULL DEFAULT 0,
  `ins` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `participant_lists`
--

INSERT INTO `participant_lists` (`id`, `tid`, `proposal_id`, `action_plan_id`, `proposal_item_id`, `date`, `region_id`, `cohort_id`, `prepared_by`, `male_count`, `female_count`, `total_count`, `ins`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 1, 2, '2023-03-20', 1, 1, 'Anthony', 2, 1, 3, 1, 1, '2023-03-19 11:39:17', '2023-03-19 18:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `participant_list_items`
--

CREATE TABLE `participant_list_items` (
  `id` bigint(20) NOT NULL,
  `participant_list_id` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `age_group_id` bigint(20) DEFAULT NULL,
  `disability_id` bigint(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `designation` varchar(20) DEFAULT NULL,
  `organisation` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `participant_list_items`
--

INSERT INTO `participant_list_items` (`id`, `participant_list_id`, `date`, `name`, `gender`, `age_group_id`, `disability_id`, `phone`, `email`, `designation`, `organisation`, `created_at`, `updated_at`) VALUES
(1, 2, '2023-03-20', 'Dancan', 'male', 2, NULL, '1234567', 'dan@gmail.com', 'developer', 'Technoguru', '2023-03-19 14:39:17', '2023-03-19 14:39:17'),
(2, 2, '2023-03-20', 'Kagiri', 'male', 2, NULL, '1234567', 'kagiri@gmail.com', 'project manager', 'Lean', '2023-03-19 14:39:17', '2023-03-19 14:39:17'),
(3, 2, '2023-03-20', 'Sheila', 'male', 2, NULL, '1234567', 'sheial@account.com', 'petty cash', 'Lean', '2023-03-19 14:39:17', '2023-03-19 14:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` bigint(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prefixes`
--

CREATE TABLE `prefixes` (
  `id` bigint(20) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `sep` enum('-','/') DEFAULT '-',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prefixes`
--

INSERT INTO `prefixes` (`id`, `label`, `name`, `code`, `sep`, `created_at`, `updated_at`) VALUES
(1, 'Proposal', 'proposal', 'PR', '-', '2023-03-19 07:08:11', '2023-03-19 07:08:11'),
(2, 'Action Plan', 'action_plan', 'AP', '-', '2023-03-19 07:08:11', '2023-03-19 07:08:11'),
(3, 'Participant List', 'participant_list', 'PL', '-', '2023-03-19 07:09:12', '2023-03-19 07:09:12'),
(4, 'Activity Narrative', 'narrative', 'NR', '-', '2023-03-19 07:09:12', '2023-03-19 07:09:12');

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`id`, `name`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 'Inclusive Health and Medication', 1, 1, '2023-01-22 14:24:21', '2023-02-11 16:56:21'),
(2, 'Inclusive Education', 1, 1, '2023-01-22 14:24:21', '2023-01-22 14:24:21'),
(3, 'Youth Empowerment', 1, 1, '2023-01-22 14:36:39', '2023-01-22 14:36:39');

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` bigint(20) NOT NULL,
  `tid` bigint(20) NOT NULL DEFAULT 0,
  `title` varchar(250) DEFAULT NULL,
  `sector` varchar(50) DEFAULT NULL,
  `donor_id` bigint(20) DEFAULT NULL,
  `region_id` bigint(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(16,4) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `status_note` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `tid`, `title`, `sector`, `donor_id`, `region_id`, `start_date`, `end_date`, `budget`, `status`, `status_note`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 1, 'Talent Institution Project', 'Youth', 3, 1, '2023-01-01', '2023-01-31', '100000.0000', 'approved', NULL, 1, 1, '2023-01-22 04:28:54', '2023-03-04 09:14:38'),
(2, 2, 'Universal Health Care', 'Health', 1, 1, '2023-02-01', '2023-02-12', '5000000.0000', 'approved', NULL, 1, 1, '2023-02-12 04:33:45', '2023-02-26 02:35:24');

-- --------------------------------------------------------

--
-- Table structure for table `proposal_items`
--

CREATE TABLE `proposal_items` (
  `id` bigint(20) NOT NULL,
  `proposal_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_obj` int(11) NOT NULL DEFAULT 1,
  `row_num` int(11) DEFAULT NULL,
  `row_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `proposal_items`
--

INSERT INTO `proposal_items` (`id`, `proposal_id`, `name`, `is_obj`, `row_num`, `row_index`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nature Talent', 1, 1, 0, '2023-01-22 07:28:54', '2023-01-22 07:28:54'),
(2, 1, 'Youth awareness', 0, NULL, 1, '2023-01-22 07:28:54', '2023-01-22 07:28:54'),
(3, 1, 'Promote self employment through talent', 0, NULL, 2, '2023-01-22 07:28:54', '2023-01-22 07:28:54'),
(4, 2, 'Free Access to Hospitals', 1, 1, 0, '2023-02-12 07:33:45', '2023-02-12 07:33:45'),
(6, 2, 'Initiate youth chamas that foster health insurance', 0, NULL, 1, '2023-02-12 05:36:59', '2023-02-12 05:36:59'),
(9, 2, 'Reduce spread of Malaria', 1, 2, 2, '2023-03-20 07:17:15', '2023-03-20 07:17:15'),
(10, 2, 'Distribute mosquitoe nets', 0, NULL, 3, '2023-03-20 07:17:15', '2023-03-20 07:17:15'),
(11, 2, 'Educate community on ways of curbing malaria spread', 0, NULL, 4, '2023-03-20 07:17:15', '2023-03-20 07:17:15'),
(12, 2, 'Install proper drainage facilities', 0, NULL, 5, '2023-03-20 07:17:15', '2023-03-20 07:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `user_id`, `ins`, `created_at`, `updated_at`) VALUES
(1, 'Nairobi', 1, 1, '2023-01-22 14:22:32', '2023-01-22 14:22:32'),
(2, 'Nakuru', 1, 1, '2023-01-22 14:22:32', '2023-01-22 14:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) NOT NULL,
  `email_verified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ins` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `email_verified_at`, `ins`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'demo@admin.com', '$2a$10$1s62tafJHT/lAjKO4BJqcuy0W7GjBhD2ZwRKhKvFK9DjtwT0JG9zS', '', '2023-02-19 06:58:52', 1, '2023-02-19 06:58:52', '2023-02-19 06:58:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action_plans`
--
ALTER TABLE `action_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `action_plan_activities`
--
ALTER TABLE `action_plan_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action_plan_id` (`action_plan_id`);

--
-- Indexes for table `action_plan_cohorts`
--
ALTER TABLE `action_plan_cohorts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action_plan_id` (`action_plan_id`);

--
-- Indexes for table `action_plan_regions`
--
ALTER TABLE `action_plan_regions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action_plan_id` (`action_plan_id`);

--
-- Indexes for table `age_groups`
--
ALTER TABLE `age_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cohorts`
--
ALTER TABLE `cohorts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disabilities`
--
ALTER TABLE `disabilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_frames`
--
ALTER TABLE `log_frames`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `narratives`
--
ALTER TABLE `narratives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `narrative_items`
--
ALTER TABLE `narrative_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `narrative_pointers`
--
ALTER TABLE `narrative_pointers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participant_lists`
--
ALTER TABLE `participant_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `participant_list_items`
--
ALTER TABLE `participant_list_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prefixes`
--
ALTER TABLE `prefixes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposal_items`
--
ALTER TABLE `proposal_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action_plans`
--
ALTER TABLE `action_plans`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `action_plan_activities`
--
ALTER TABLE `action_plan_activities`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `action_plan_cohorts`
--
ALTER TABLE `action_plan_cohorts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `action_plan_regions`
--
ALTER TABLE `action_plan_regions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `age_groups`
--
ALTER TABLE `age_groups`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cohorts`
--
ALTER TABLE `cohorts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `disabilities`
--
ALTER TABLE `disabilities`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `log_frames`
--
ALTER TABLE `log_frames`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `narratives`
--
ALTER TABLE `narratives`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `narrative_items`
--
ALTER TABLE `narrative_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `narrative_pointers`
--
ALTER TABLE `narrative_pointers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `participant_lists`
--
ALTER TABLE `participant_lists`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `participant_list_items`
--
ALTER TABLE `participant_list_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prefixes`
--
ALTER TABLE `prefixes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proposal_items`
--
ALTER TABLE `proposal_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `action_plan_activities`
--
ALTER TABLE `action_plan_activities`
  ADD CONSTRAINT `action_plan_activities_ibfk_1` FOREIGN KEY (`action_plan_id`) REFERENCES `action_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `action_plan_cohorts`
--
ALTER TABLE `action_plan_cohorts`
  ADD CONSTRAINT `action_plan_cohorts_ibfk_1` FOREIGN KEY (`action_plan_id`) REFERENCES `action_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `action_plan_regions`
--
ALTER TABLE `action_plan_regions`
  ADD CONSTRAINT `action_plan_regions_ibfk_1` FOREIGN KEY (`action_plan_id`) REFERENCES `action_plans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
