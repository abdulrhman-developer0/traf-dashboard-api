-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 12:39 PM
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
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `reference_id` int(10) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('pending','canceled','confirmed','done') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `client_id`, `service_id`, `reference_id`, `date`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2024-12-24 00:00:00', NULL, 'confirmed', '2024-12-23 22:20:30', '2024-12-23 22:20:30'),
(2, 2, 1, NULL, '2024-12-24 00:00:00', NULL, 'confirmed', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(3, 3, 1, NULL, '2024-12-24 00:00:00', NULL, 'confirmed', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(4, 1, 3, NULL, '2024-12-24 00:00:00', NULL, 'confirmed', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(5, 2, 3, NULL, '2024-12-24 00:00:00', NULL, 'confirmed', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(6, 3, 3, NULL, '2024-12-24 00:00:00', NULL, 'confirmed', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(7, 1, 4, NULL, '2024-12-24 00:00:00', NULL, 'confirmed', '2024-12-23 22:20:32', '2024-12-23 22:20:32'),
(8, 2, 4, NULL, '2024-12-24 00:00:00', NULL, 'confirmed', '2024-12-23 22:20:32', '2024-12-23 22:20:32'),
(9, 3, 4, NULL, '2024-12-24 00:00:00', NULL, 'confirmed', '2024-12-23 22:20:32', '2024-12-23 22:20:32');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_members`
--

CREATE TABLE `chat_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` longtext NOT NULL,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'الرياض', NULL, NULL),
(2, 'جدة', NULL, NULL),
(3, 'مكة المكرمة', NULL, NULL),
(4, 'المدينة المنورة', NULL, NULL),
(5, 'الدمام', NULL, NULL),
(6, 'الخبر', NULL, NULL),
(7, 'القصيم', NULL, NULL),
(8, 'تبوك', NULL, NULL),
(9, 'الطائف', NULL, NULL),
(10, 'الأحساء', NULL, NULL),
(11, 'نجران', NULL, NULL),
(12, 'عسير', NULL, NULL),
(13, 'جازان', NULL, NULL),
(14, 'بريدة', NULL, NULL),
(15, 'المدينة الصناعية بجدة', NULL, NULL),
(16, 'خميس مشيط', NULL, NULL),
(17, 'الحدود الشمالية', NULL, NULL),
(18, 'بيشة', NULL, NULL),
(19, 'الزلفي', NULL, NULL),
(20, 'الجبيل', NULL, NULL),
(21, 'رابغ', NULL, NULL),
(22, 'حائل', NULL, NULL),
(23, 'ابها', NULL, NULL),
(24, 'القنفذة', NULL, NULL),
(25, 'الدوادمي', NULL, NULL),
(26, 'المجمعة', NULL, NULL),
(27, 'الدرعية', NULL, NULL),
(28, 'الجبيل الصناعية', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rating` float NOT NULL DEFAULT 0,
  `area` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `user_id`, `phone`, `address`, `rating`, `area`, `city`, `created_at`, `updated_at`) VALUES
(1, 5, '01012345678', 'شارع النيل، الجيزة', 4.5, NULL, NULL, '2024-12-23 22:20:19', '2024-12-23 22:20:19'),
(2, 6, '01098765432', 'شارع فيصل، القاهرة', 4, NULL, NULL, '2024-12-23 22:20:19', '2024-12-23 22:20:19'),
(3, 7, '01234567890', 'مدينة نصر، القاهرة', 3.8, NULL, NULL, '2024-12-23 22:20:19', '2024-12-23 22:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `custom_work_dates`
--

CREATE TABLE `custom_work_dates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_work_times`
--

CREATE TABLE `custom_work_times` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `custom_work_date_id` bigint(20) UNSIGNED NOT NULL,
  `time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorits`
--

CREATE TABLE `favorits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `platform_version` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `browser_version` varchar(255) DEFAULT NULL,
  `is_desktop` tinyint(1) NOT NULL DEFAULT 0,
  `is_phone` tinyint(1) NOT NULL DEFAULT 0,
  `is_robot` tinyint(1) NOT NULL DEFAULT 0,
  `device_name` varchar(255) DEFAULT NULL,
  `user_agent` text NOT NULL,
  `is_success` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `banned_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Worker', 1, 'f31d2764-d485-41c2-afea-7804a77f8c73', 'photo', 'dr-elham', 'dr-elham.jpg', 'image/jpeg', 'public', 'public', 80750, '[]', '[]', '[]', '[]', 1, '2024-12-23 22:20:27', '2024-12-23 22:20:27'),
(2, 'App\\Models\\Worker', 2, 'e5727a29-4028-4d28-987d-4b09272a74a7', 'photo', 'norayousef', 'norayousef.jpg', 'image/jpeg', 'public', 'public', 124170, '[]', '[]', '[]', '[]', 1, '2024-12-23 22:20:28', '2024-12-23 22:20:28'),
(3, 'App\\Models\\Worker', 3, '2edcad46-0a44-4da4-8bf5-c3bb1ad553a3', 'photo', 'rahmaAli', 'rahmaAli.jpg', 'image/jpeg', 'public', 'public', 70450, '[]', '[]', '[]', '[]', 1, '2024-12-23 22:20:28', '2024-12-23 22:20:28'),
(4, 'App\\Models\\Worker', 4, '8ab268e7-e343-414a-90c2-a48a649c12b2', 'photo', 'sarahHasan', 'sarahHasan.jpeg', 'image/jpeg', 'public', 'public', 14249, '[]', '[]', '[]', '[]', 1, '2024-12-23 22:20:29', '2024-12-23 22:20:29'),
(5, 'App\\Models\\Worker', 5, 'd7f5fe62-a269-431f-b3fa-fb444c2d46fa', 'photo', 'noraAbdallah', 'noraAbdallah.jpg', 'image/jpeg', 'public', 'public', 92925, '[]', '[]', '[]', '[]', 1, '2024-12-23 22:20:29', '2024-12-23 22:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_10_03_163206_create_media_table', 1),
(5, '2024_10_03_163517_create_activity_log_table', 1),
(6, '2024_10_03_163518_add_event_column_to_activity_log_table', 1),
(7, '2024_10_03_163519_add_batch_uuid_column_to_activity_log_table', 1),
(8, '2024_10_03_164723_create_permission_tables', 1),
(9, '2024_10_03_205353_create_system_logs_table', 1),
(10, '2024_10_04_113243_creat_login_attempt_table', 1),
(11, '2024_10_05_091326_add_last_activity_column_to_users_table', 1),
(12, '2024_10_27_172239_create_permission_groups_table', 1),
(13, '2024_10_27_172348_add_fields_to_permissions_table', 1),
(14, '2024_11_11_165418_create_clients_table', 1),
(15, '2024_11_11_165444_create_service_providers_table', 1),
(16, '2024_11_11_180748_create_service_categories_table', 1),
(17, '2024_11_11_180847_create_services_table', 1),
(18, '2024_11_11_181459_create_chats_table', 1),
(19, '2024_11_11_181745_create_chat_members_table', 1),
(20, '2024_11_11_181906_create_chat_messages_table', 1),
(21, '2024_11_11_181906_create_workers_table', 1),
(22, '2024_11_11_182359_create_service_schedules_table', 1),
(23, '2024_11_11_182730_create_bookings_table', 1),
(24, '2024_11_11_183240_create_serivce_provider_portfolios_table', 1),
(25, '2024_11_11_191441_create_reviews_table', 1),
(26, '2024_11_11_202403_create_personal_access_tokens_table', 1),
(27, '2024_11_13_163346_create_cities_table', 1),
(28, '2024_11_19_205215_create_one_time_passwords_table', 1),
(29, '2024_11_19_211532_create_reset_tokens_table', 1),
(30, '2024_11_23_164900_create_favorits_table', 1),
(31, '2024_11_26_182706_create_schedule_excluded_dates_table', 1),
(32, '2024_11_26_183548_create_schedule_work_times_table', 1),
(33, '2024_11_30_190513_create_offers_table', 1),
(34, '2024_11_30_220721_create_service_workers_table', 1),
(35, '2024_12_01_001056_create_notifications_table', 1),
(36, '2024_12_03_001558_create_packages_table', 1),
(37, '2024_12_03_001559_create_subscriptions_table', 1),
(38, '2024_12_08_161425_create_custom_work_dates_table', 1),
(39, '2024_12_09_132743_create_custom_work_times_table', 1),
(40, '2024_12_10_191919_add_deleted_at_to_workers_table', 1),
(41, '2024_12_14_125941_create_payments_table', 1),
(42, '2024_12_18_152147_add_fcm_token_to_users_table', 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` enum('poster','short_video') NOT NULL DEFAULT 'poster',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `one_time_passwords`
--

CREATE TABLE `one_time_passwords` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_in_days` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `price`, `duration_in_days`, `created_at`, `updated_at`) VALUES
(1, 'باقة الشهر الواحد', 15.00, 30, NULL, NULL),
(2, 'باقة 6 شهور', 75.00, 180, NULL, NULL),
(3, 'باقة 12 شهر', 150.00, 365, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `amount` varchar(255) NOT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `payment_status`, `amount`, `transaction_reference`, `created_at`, `updated_at`) VALUES
(1, 1, 'paid', '200', '1f2s135d', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(2, 2, 'paid', '200', '1f2s135d', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(3, 3, 'paid', '200', '1f2s135d', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(4, 4, 'paid', '250', '1f2s135d', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(5, 5, 'paid', '250', '1f2s135d', '2024-12-23 22:20:31', '2024-12-23 22:20:31'),
(6, 6, 'paid', '250', '1f2s135d', '2024-12-23 22:20:32', '2024-12-23 22:20:32'),
(7, 7, 'paid', '250', '1f2s135d', '2024-12-23 22:20:32', '2024-12-23 22:20:32'),
(8, 8, 'paid', '250', '1f2s135d', '2024-12-23 22:20:32', '2024-12-23 22:20:32'),
(9, 9, 'paid', '250', '1f2s135d', '2024-12-23 22:20:32', '2024-12-23 22:20:32');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `permission_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_groups`
--

CREATE TABLE `permission_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reset_tokens`
--

CREATE TABLE `reset_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `reviewable_type` varchar(255) NOT NULL,
  `reviewable_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_excluded_dates`
--

CREATE TABLE `schedule_excluded_dates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_work_times`
--

CREATE TABLE `schedule_work_times` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `serivce_provider_portfolios`
--

CREATE TABLE `serivce_provider_portfolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_provider_id` bigint(20) UNSIGNED NOT NULL,
  `website_url` text NOT NULL,
  `description` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_category_id` bigint(20) UNSIGNED NOT NULL,
  `service_provider_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `rating` decimal(8,2) NOT NULL DEFAULT 0.00,
  `price_before` decimal(10,2) NOT NULL,
  `price_after` decimal(10,2) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_home_service` tinyint(1) NOT NULL DEFAULT 0,
  `is_offer` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_category_id`, `service_provider_id`, `name`, `duration`, `description`, `rating`, `price_before`, `price_after`, `address`, `is_home_service`, `is_offer`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'جلسة تنظيف بشرة', 60, 'جلسة متخصصة لتنظيف البشرة بعمق باستخدام أفضل المنتجات.', 4.50, 0.00, 200.00, NULL, 0, 1, '2024-12-23 22:20:30', '2024-12-23 22:20:30'),
(2, 2, 2, 'جلسة مكياج سوارية', 90, 'جلسة مكياج سوارية باستخدام أحدث التقنيات لإطلالة رائعة.', 4.80, 300.00, NULL, NULL, 0, 0, '2024-12-23 22:20:30', '2024-12-23 22:20:30'),
(3, 3, 3, 'جلسة مساج', 75, 'جلسة مساج تساعد على الاسترخاء وتحسين الدورة الدموية.', 4.70, 0.00, 250.00, NULL, 0, 1, '2024-12-23 22:20:30', '2024-12-23 22:20:30'),
(4, 3, 4, 'جلسة باديكير ومناكير ورسم حنة  ', 75, 'جلسة مناكير وباديكير ورسم حنة باحدث الاشكال والطلاءات .', 4.70, 0.00, 250.00, NULL, 0, 1, '2024-12-23 22:20:30', '2024-12-23 22:20:30');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `image_path`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'رسم الحنة', 'logos/Hana-Drawing.svg', 1, '2024-12-23 22:20:29', '2024-12-23 22:20:29'),
(2, 'مكياج', 'logos/makeup.svg', 1, '2024-12-23 22:20:29', '2024-12-23 22:20:29'),
(3, 'باديكير ومناكير ', 'logos/nails.svg', 1, '2024-12-23 22:20:29', '2024-12-23 22:20:29'),
(4, 'مساج', 'logos/spa-svgrepo-com.svg', 1, '2024-12-23 22:20:30', '2024-12-23 22:20:30'),
(5, 'بشرة', 'logos/skincare-icon.svg', 1, '2024-12-23 22:20:30', '2024-12-23 22:20:30'),
(6, 'عناية بالشعر', 'logos/hear-care.svg', 1, '2024-12-23 22:20:30', '2024-12-23 22:20:30'),
(7, 'عروض', 'logos/offers.svg', 1, '2024-12-23 22:20:30', '2024-12-23 22:20:30');

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_personal` tinyint(1) NOT NULL DEFAULT 1,
  `tax_registeration_number` varchar(255) DEFAULT NULL,
  `job` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rating` float NOT NULL DEFAULT 0,
  `area` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `status` enum('pending','rejected','approved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`id`, `user_id`, `is_personal`, `tax_registeration_number`, `job`, `phone`, `address`, `rating`, `area`, `city`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '1234567890', 'فنان مكياج', '0501234567', 'الرياض، حي الروضة', 4.9, NULL, NULL, 'pending', '2024-12-23 22:20:19', '2024-12-23 22:20:19'),
(2, 2, 0, '1122334455', 'مصفف شعر', '0509876543', 'جدة، حي الصفا', 4.6, NULL, NULL, 'pending', '2024-12-23 22:20:20', '2024-12-23 22:20:20'),
(3, 3, 1, NULL, 'فني أظافر', '0561234321', 'الدمام، حي الشاطئ', 4.7, NULL, NULL, 'pending', '2024-12-23 22:20:21', '2024-12-23 22:20:21'),
(4, 4, 1, NULL, 'فني أظافر', '0561234321', 'الدمام، حي الشاطئ', 4.7, NULL, NULL, 'pending', '2024-12-23 22:20:21', '2024-12-23 22:20:21');

-- --------------------------------------------------------

--
-- Table structure for table `service_schedules`
--

CREATE TABLE `service_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_id` int(10) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_workers`
--

CREATE TABLE `service_workers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dBq6sYD10ylPcLbFKWEG51IfjArNjG4yDvs3wH71', NULL, '127.0.0.1', 'PostmanRuntime/7.43.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibVp2UEdYVTUxQ2pHZXBYcHQ1YWR5aUVVUXdOejNuSWlLdE5WMUxlcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC90ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1734993384);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_provider_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `transaction_reference` varchar(255) DEFAULT NULL,
  `amount` varchar(255) NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `service_provider_id`, `package_id`, `payment_status`, `transaction_reference`, `amount`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'paid', 's1e1e1d1e1r', '15.00', '2024-12-23 22:20:19', '2025-01-23 21:59:59', '2024-12-23 22:20:20', '2024-12-23 22:20:20'),
(2, 2, 3, 'paid', 's1e1e1d1e1r', '150.00', '2024-12-23 22:20:20', '2025-01-23 21:59:59', '2024-12-23 22:20:20', '2024-12-23 22:20:20'),
(3, 3, 3, 'paid', 's1e1e1d1e1r', '150.00', '2024-12-23 22:20:21', '2025-01-23 21:59:59', '2024-12-23 22:20:21', '2024-12-23 22:20:21'),
(4, 4, 3, 'paid', 's1e1e1d1e1r', '150.00', '2024-12-23 22:20:21', '2025-01-23 21:59:59', '2024-12-23 22:20:21', '2024-12-23 22:20:21');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operation` text NOT NULL,
  `description` longtext NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `expire_at` datetime DEFAULT NULL,
  `code_verified` tinyint(1) NOT NULL DEFAULT 0,
  `account_type` varchar(255) NOT NULL DEFAULT '',
  `remember_token` varchar(100) DEFAULT NULL,
  `last_activity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `code`, `expire_at`, `code_verified`, `account_type`, `remember_token`, `last_activity`, `created_at`, `updated_at`, `deleted_at`, `fcm_token`) VALUES
(1, 'أحمد علي', 'ahmed.ali@example.com', NULL, '$2y$12$n7o847U4qnQ325hrganUS.12nnoa7zMlsrM1Q43Bc7TkLIAjEdrea', NULL, NULL, 0, 'service-provider', NULL, NULL, '2024-12-23 22:20:14', '2024-12-23 22:20:14', NULL, NULL),
(2, 'سارة محمد', 'sara.mohamed@example.com', NULL, '$2y$12$FEtMKGo/xNB8bgTFej5cOeRBQS3VLKWqaEY8xUgKzbpFwOJtxvr82', NULL, NULL, 0, 'service-provider', NULL, NULL, '2024-12-23 22:20:14', '2024-12-23 22:20:14', NULL, NULL),
(3, 'خالد حسن', 'khaled.hassan@example.com', NULL, '$2y$12$7n6oWP05TNxO/.fkMpMtuuOMjMYLYgUTuM7ikGF5UmZefTsRy9hUK', NULL, NULL, 0, 'service-provider', NULL, NULL, '2024-12-23 22:20:15', '2024-12-23 22:20:15', NULL, NULL),
(4, 'منى محمود', 'mona.mahmoud@example.com', NULL, '$2y$12$TImNy7S6meYUKjoLyq7vp.c2nFEqL5AQgRyvszgllrBPB/TmSdsfW', NULL, NULL, 0, 'service-provider', NULL, NULL, '2024-12-23 22:20:15', '2024-12-23 22:20:15', NULL, NULL),
(5, 'يوسف إبراهيم', 'youssef.ibrahim@example.com', NULL, '$2y$12$o58r65voLtR5rm.ENj7qxe8xGWRx1aFW3/ZArDtUlFpScvCw1yR22', NULL, NULL, 0, 'client', NULL, NULL, '2024-12-23 22:20:16', '2024-12-23 22:20:16', NULL, NULL),
(6, 'هدى أحمد', 'hoda.ahmed@example.com', NULL, '$2y$12$1/UaI.BCOFkt6z4XPVkwo.EPbTMgbxznG62vu1hDKuVkIrr3HJeaS', NULL, NULL, 0, 'client', NULL, NULL, '2024-12-23 22:20:16', '2024-12-23 22:20:16', NULL, NULL),
(7, 'علي سعيد', 'ali.saeed@example.com', NULL, '$2y$12$eCH/5lKUqh9iUELWMlL9fulpuvW2tbR/G/auyhnL6BPjtDIW33E9S', NULL, NULL, 0, 'client', NULL, NULL, '2024-12-23 22:20:17', '2024-12-23 22:20:17', NULL, NULL),
(8, 'نورا سامي', 'nora.samy@example.com', NULL, '$2y$12$IPJbYjuX1.8S.VJk1ZmLHOhAkfuCRPPklByQZDmgnaoDHtnW9cRa2', NULL, NULL, 0, 'client', NULL, NULL, '2024-12-23 22:20:17', '2024-12-23 22:20:17', NULL, NULL),
(9, 'عمر فؤاد', 'omar.fouad@example.com', NULL, '$2y$12$e9iZVJYmUhSZwXtwO8Nln.kx6G2jctj0pJv.hyegwijfZjIK1hHUS', NULL, NULL, 0, 'client', NULL, NULL, '2024-12-23 22:20:18', '2024-12-23 22:20:18', NULL, NULL),
(10, 'ليلى سمير', 'laila.samir@example.com', NULL, '$2y$12$WEGILDs9U2QjEynPDEO1/.SJoN1KzytKX/x6d5YjvA9vKWClr4YsW', NULL, NULL, 0, 'client', NULL, NULL, '2024-12-23 22:20:18', '2024-12-23 22:20:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_provider_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`id`, `service_provider_id`, `name`, `phone`, `address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'د/الهام ماضي', '012345674', 'Saudi Arabia, ElReyad', '2024-12-23 22:20:21', '2024-12-23 22:20:21', NULL),
(2, 1, 'د/نورا يوسف', '010987654', 'Egypt, Cairo', '2024-12-23 22:20:28', '2024-12-23 22:20:28', NULL),
(3, 1, 'د/رحمة علي', '011234567', 'Saudi Arabia, Jeddah', '2024-12-23 22:20:28', '2024-12-23 22:20:28', NULL),
(4, 1, 'د/سارة حسن', '012345678', 'UAE, Dubai', '2024-12-23 22:20:29', '2024-12-23 22:20:29', NULL),
(5, 1, 'د/نورا عبد الله', '010123456', 'Qatar, Doha', '2024-12-23 22:20:29', '2024-12-23 22:20:29', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_client_id_foreign` (`client_id`),
  ADD KEY `bookings_service_id_foreign` (`service_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_members`
--
ALTER TABLE `chat_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_members_chat_id_foreign` (`chat_id`),
  ADD KEY `chat_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_chat_id_foreign` (`chat_id`),
  ADD KEY `chat_messages_user_id_foreign` (`user_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_user_id_foreign` (`user_id`);

--
-- Indexes for table `custom_work_dates`
--
ALTER TABLE `custom_work_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_work_dates_service_schedule_id_foreign` (`service_schedule_id`);

--
-- Indexes for table `custom_work_times`
--
ALTER TABLE `custom_work_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_work_times_custom_work_date_id_foreign` (`custom_work_date_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorits`
--
ALTER TABLE `favorits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorits_client_id_foreign` (`client_id`),
  ADD KEY `favorits_service_id_foreign` (`service_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_attempts_user_id_index` (`user_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offers_service_id_foreign` (`service_id`);

--
-- Indexes for table `one_time_passwords`
--
ALTER TABLE `one_time_passwords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`),
  ADD KEY `permissions_permission_group_id_foreign` (`permission_group_id`);

--
-- Indexes for table `permission_groups`
--
ALTER TABLE `permission_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `reset_tokens`
--
ALTER TABLE `reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reset_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_booking_id_foreign` (`booking_id`),
  ADD KEY `reviews_reviewable_type_reviewable_id_index` (`reviewable_type`,`reviewable_id`);

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
-- Indexes for table `schedule_excluded_dates`
--
ALTER TABLE `schedule_excluded_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_excluded_dates_service_schedule_id_foreign` (`service_schedule_id`);

--
-- Indexes for table `schedule_work_times`
--
ALTER TABLE `schedule_work_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_work_times_service_schedule_id_foreign` (`service_schedule_id`);

--
-- Indexes for table `serivce_provider_portfolios`
--
ALTER TABLE `serivce_provider_portfolios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serivce_provider_portfolios_service_provider_id_foreign` (`service_provider_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_service_category_id_foreign` (`service_category_id`),
  ADD KEY `services_service_provider_id_foreign` (`service_provider_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_providers_user_id_foreign` (`user_id`);

--
-- Indexes for table `service_schedules`
--
ALTER TABLE `service_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_schedules_service_id_foreign` (`service_id`);

--
-- Indexes for table `service_workers`
--
ALTER TABLE `service_workers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_workers_service_id_foreign` (`service_id`),
  ADD KEY `service_workers_worker_id_foreign` (`worker_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_package_id_foreign` (`package_id`),
  ADD KEY `subscriptions_payment_status_index` (`payment_status`),
  ADD KEY `subscriptions_transaction_reference_index` (`transaction_reference`),
  ADD KEY `subscriptions_service_provider_id_index` (`service_provider_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_last_activity_index` (`last_activity`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workers_service_provider_id_foreign` (`service_provider_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_members`
--
ALTER TABLE `chat_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `custom_work_dates`
--
ALTER TABLE `custom_work_dates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_work_times`
--
ALTER TABLE `custom_work_times`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorits`
--
ALTER TABLE `favorits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `one_time_passwords`
--
ALTER TABLE `one_time_passwords`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission_groups`
--
ALTER TABLE `permission_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reset_tokens`
--
ALTER TABLE `reset_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_excluded_dates`
--
ALTER TABLE `schedule_excluded_dates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_work_times`
--
ALTER TABLE `schedule_work_times`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `serivce_provider_portfolios`
--
ALTER TABLE `serivce_provider_portfolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_schedules`
--
ALTER TABLE `service_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_workers`
--
ALTER TABLE `service_workers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_members`
--
ALTER TABLE `chat_members`
  ADD CONSTRAINT `chat_members_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_work_dates`
--
ALTER TABLE `custom_work_dates`
  ADD CONSTRAINT `custom_work_dates_service_schedule_id_foreign` FOREIGN KEY (`service_schedule_id`) REFERENCES `service_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_work_times`
--
ALTER TABLE `custom_work_times`
  ADD CONSTRAINT `custom_work_times_custom_work_date_id_foreign` FOREIGN KEY (`custom_work_date_id`) REFERENCES `custom_work_dates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorits`
--
ALTER TABLE `favorits`
  ADD CONSTRAINT `favorits_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorits_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_permission_group_id_foreign` FOREIGN KEY (`permission_group_id`) REFERENCES `permission_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reset_tokens`
--
ALTER TABLE `reset_tokens`
  ADD CONSTRAINT `reset_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule_excluded_dates`
--
ALTER TABLE `schedule_excluded_dates`
  ADD CONSTRAINT `schedule_excluded_dates_service_schedule_id_foreign` FOREIGN KEY (`service_schedule_id`) REFERENCES `service_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule_work_times`
--
ALTER TABLE `schedule_work_times`
  ADD CONSTRAINT `schedule_work_times_service_schedule_id_foreign` FOREIGN KEY (`service_schedule_id`) REFERENCES `service_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `serivce_provider_portfolios`
--
ALTER TABLE `serivce_provider_portfolios`
  ADD CONSTRAINT `serivce_provider_portfolios_service_provider_id_foreign` FOREIGN KEY (`service_provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_service_provider_id_foreign` FOREIGN KEY (`service_provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD CONSTRAINT `service_providers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_schedules`
--
ALTER TABLE `service_schedules`
  ADD CONSTRAINT `service_schedules_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_workers`
--
ALTER TABLE `service_workers`
  ADD CONSTRAINT `service_workers_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_workers_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_service_provider_id_foreign` FOREIGN KEY (`service_provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `workers_service_provider_id_foreign` FOREIGN KEY (`service_provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
