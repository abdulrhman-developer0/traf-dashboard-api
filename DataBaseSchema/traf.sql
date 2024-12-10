-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 18, 2024 at 07:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `traf`
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
  `service_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','paid','cancceled','done') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rating` float NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `user_id`, `city_id`, `phone`, `address`, `rating`, `created_at`, `updated_at`) VALUES
(1, 5, 101, '01012345678', 'شارع النيل، الجيزة', 4.5, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(2, 6, 102, '01098765432', 'شارع فيصل، القاهرة', 4, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(3, 7, 103, '01234567890', 'مدينة نصر، القاهرة', 3.8, '2024-11-18 19:24:26', '2024-11-18 19:24:26');

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
(12, '2024_10_12_001225_create_notifications_table', 1),
(13, '2024_10_27_172239_create_permission_groups_table', 1),
(14, '2024_10_27_172348_add_fields_to_permissions_table', 1),
(15, '2024_11_11_165418_create_clients_table', 1),
(16, '2024_11_11_165444_create_service_providers_table', 1),
(17, '2024_11_11_165455_create_service_provider_partners_table', 1),
(18, '2024_11_11_180748_create_service_categories_table', 1),
(19, '2024_11_11_180847_create_services_table', 1),
(20, '2024_11_11_181459_create_chats_table', 1),
(21, '2024_11_11_181745_create_chat_members_table', 1),
(22, '2024_11_11_181906_create_chat_messages_table', 1),
(23, '2024_11_11_182359_create_service_schedules_table', 1),
(24, '2024_11_11_182730_create_bookings_table', 1),
(25, '2024_11_11_183110_create_serivce_offers_table', 1),
(26, '2024_11_11_183240_create_serivce_provider_portfolios_table', 1),
(27, '2024_11_11_191441_create_reviews_table', 1),
(28, '2024_11_11_202403_create_personal_access_tokens_table', 1),
(29, '2024_11_11_224153_create_payments_table', 1),
(30, '2024_11_11_224209_create_transactions_table', 1),
(31, '2024_11_13_163346_create_cities_table', 1);

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
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `method` varchar(255) NOT NULL DEFAULT '',
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `permission_group_id`, `created_at`, `updated_at`) VALUES
(1, 'dashboard-dashboard-view', 'web', 1, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(2, 'dashboard-dashboard-add', 'web', 1, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(3, 'dashboard-dashboard-edit', 'web', 1, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(4, 'dashboard-dashboard-delete', 'web', 1, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(5, 'users-users-view', 'web', 2, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(6, 'users-users-add', 'web', 2, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(7, 'users-users-edit', 'web', 2, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(8, 'users-users-delete', 'web', 2, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(9, 'roles-and-permissions-roles-view', 'web', 3, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(10, 'roles-and-permissions-roles-add', 'web', 3, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(11, 'roles-and-permissions-roles-edit', 'web', 3, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(12, 'roles-and-permissions-roles-delete', 'web', 3, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(13, 'roles-and-permissions-permissions-view', 'web', 4, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(14, 'roles-and-permissions-permissions-add', 'web', 4, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(15, 'roles-and-permissions-permissions-edit', 'web', 4, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(16, 'roles-and-permissions-permissions-delete', 'web', 4, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(17, 'system-logs-and-trash-system-trash-view', 'web', 5, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(18, 'system-logs-and-trash-system-trash-add', 'web', 5, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(19, 'system-logs-and-trash-system-trash-edit', 'web', 5, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(20, 'system-logs-and-trash-system-trash-delete', 'web', 5, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(21, 'system-logs-and-trash-activity-log-view', 'web', 6, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(22, 'system-logs-and-trash-activity-log-add', 'web', 6, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(23, 'system-logs-and-trash-activity-log-edit', 'web', 6, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(24, 'system-logs-and-trash-activity-log-delete', 'web', 6, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(25, 'system-logs-and-trash-system-log-view', 'web', 7, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(26, 'system-logs-and-trash-system-log-add', 'web', 7, '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(27, 'system-logs-and-trash-system-log-edit', 'web', 7, '2024-11-18 19:24:25', '2024-11-18 19:24:25'),
(28, 'system-logs-and-trash-system-log-delete', 'web', 7, '2024-11-18 19:24:25', '2024-11-18 19:24:25');

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

--
-- Dumping data for table `permission_groups`
--

INSERT INTO `permission_groups` (`id`, `name`, `module`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'Dashboard', '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(2, 'Users', 'Users', '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(3, 'Roles', 'Roles and Permissions', '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(4, 'Permissions', 'Roles and Permissions', '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(5, 'System Trash', 'System Logs and Trash', '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(6, 'Activity log', 'System Logs and Trash', '2024-11-18 19:24:24', '2024-11-18 19:24:24'),
(7, 'System log', 'System Logs and Trash', '2024-11-18 19:24:24', '2024-11-18 19:24:24');

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
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reviewable_type` varchar(255) NOT NULL,
  `reviewable_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `reviewable_type`, `reviewable_id`, `comment`, `rating`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Service', 1, 'الخدمة كانت رائعة، العاملين محترفين جداً.', 5, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(2, 2, 'App\\Models\\Service', 2, 'لم أكن راضيًا عن الخدمة، كنت أتوقع أفضل.', 2, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(3, 3, 'App\\Models\\Service', 3, 'الخدمة كانت جيدة ولكن أعتقد أنه كان من الممكن أن تكون أسرع.', 4, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(4, 4, 'App\\Models\\Service', 4, 'أحببت الخدمة والنتيجة كانت مرضية جداً.', 5, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(5, 5, 'App\\Models\\Service', 5, 'الخدمة كانت بطيئة، لا أعتقد أنني سأعيد استخدامها.', 2, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(6, 6, 'App\\Models\\Service', 6, 'كانت الخدمة ممتازة، وكنت سعيداً بالنتيجة.', 5, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(7, 7, 'App\\Models\\Service', 7, 'الخدمة كانت جيدة، لكن السعر كان مرتفعاً بالنسبة للجودة.', 3, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(8, 8, 'App\\Models\\Service', 8, 'النتيجة لم تكن كما توقعت، وأعتقد أنني سأبحث عن خيارات أخرى.', 2, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(9, 9, 'App\\Models\\Service', 9, 'الخدمة كانت رائعة وكان فريق العمل محترفاً جداً.', 5, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(10, 10, 'App\\Models\\Service', 10, 'الخدمة كانت معقولة ولكن يمكن تحسين بعض التفاصيل.', 3, '2024-11-18 19:24:26', '2024-11-18 19:24:26');

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

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2024-11-18 19:24:24', '2024-11-18 19:24:24');

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
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1);

-- --------------------------------------------------------

--
-- Table structure for table `serivce_offers`
--

CREATE TABLE `serivce_offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `discount_percentage` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `price_after` decimal(10,2) NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `serivce_offers`
--

INSERT INTO `serivce_offers` (`id`, `service_id`, `discount_percentage`, `description`, `price_after`, `start_at`, `end_at`, `created_at`, `updated_at`) VALUES
(1, 1, 15, 'خصم 15% على جميع خدمات المكياج في صالوننا، لا تفوت الفرصة!', 85.00, '2024-11-18 21:24:27', '2024-11-28 21:24:27', '2024-11-18 19:24:27', '2024-11-18 19:24:27'),
(2, 2, 20, 'خصم 20% على جميع جلسات تدليك الجسم، استمتع بالراحة والاسترخاء.', 100.00, '2024-11-18 21:24:27', '2024-11-25 21:24:27', '2024-11-18 19:24:27', '2024-11-18 19:24:27'),
(3, 3, 10, 'خصم 10% على تنظيف البشرة، للحصول على بشرة ناعمة ومتألقة.', 72.00, '2024-11-18 21:24:27', '2024-12-02 21:24:27', '2024-11-18 19:24:27', '2024-11-18 19:24:27');

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
  `partner_service_provider_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `rating` double DEFAULT NULL,
  `price_before` decimal(10,2) NOT NULL,
  `is_offer` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_category_id`, `partner_service_provider_id`, `name`, `duration`, `description`, `rating`, `price_before`, `is_offer`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'جلسة تنظيف بشرة', 60, 'جلسة متخصصة لتنظيف البشرة بعمق باستخدام أفضل المنتجات.', 4.5, 200.00, 1, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(2, 2, 2, 'جلسة مكياج سوارية', 90, 'جلسة مكياج سوارية باستخدام أحدث التقنيات لإطلالة رائعة.', 4.8, 300.00, 0, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(3, 3, 3, 'جلسة مساج', 75, 'جلسة مساج تساعد على الاسترخاء وتحسين الدورة الدموية.', 4.7, 250.00, 1, '2024-11-18 19:24:26', '2024-11-18 19:24:26');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'مكياج', 1, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(2, 'تقليم أظافر', 1, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(3, 'مساج', 1, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(4, 'تنظيف بشرة', 1, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(5, 'باديكير', 1, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(6, 'مناكير', 1, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(7, 'عناية بالشعر', 1, '2024-11-18 19:24:26', '2024-11-18 19:24:26');

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_personal` tinyint(1) NOT NULL DEFAULT 1,
  `tax_registeration_number` varchar(255) DEFAULT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL,
  `years_of_experience` int(11) NOT NULL DEFAULT 0,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rating` float NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`id`, `user_id`, `is_personal`, `tax_registeration_number`, `city_id`, `years_of_experience`, `phone`, `address`, `rating`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '1234567890', 301, 10, '0501234567', 'الرياض، حي الروضة', 4.9, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(2, 2, 0, '1122334455', 302, 8, '0509876543', 'جدة، حي الصفا', 4.6, '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(3, 3, 1, NULL, 303, 12, '0561234321', 'الدمام، حي الشاطئ', 4.7, '2024-11-18 19:24:26', '2024-11-18 19:24:26');

-- --------------------------------------------------------

--
-- Table structure for table `service_provider_partners`
--

CREATE TABLE `service_provider_partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_provider_id` bigint(20) UNSIGNED NOT NULL,
  `partner_service_provider_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_provider_partners`
--

INSERT INTO `service_provider_partners` (`id`, `service_provider_id`, `partner_service_provider_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 3, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_schedules`
--

CREATE TABLE `service_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partner_service_provider_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('available','off','booked') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_schedules`
--

INSERT INTO `service_schedules` (`id`, `partner_service_provider_id`, `service_id`, `date`, `time`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-11-20', '10:00:00', 'available', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(2, 2, 2, '2024-11-21', '12:00:00', 'booked', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(3, 1, 1, '2024-11-22', '15:00:00', 'off', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(4, 2, 1, '2024-11-23', '09:30:00', 'available', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(5, 3, 1, '2024-11-24', '14:00:00', 'booked', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(6, 1, 2, '2024-11-25', '11:00:00', 'available', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(7, 2, 3, '2024-11-26', '16:00:00', 'off', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(8, 3, 3, '2024-11-27', '08:00:00', 'booked', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(9, 2, 3, '2024-11-28', '17:30:00', 'available', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(10, 3, 1, '2024-11-29', '13:00:00', 'off', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(11, 1, 1, '2024-11-30', '09:00:00', 'available', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(12, 2, 2, '2024-12-01', '18:00:00', 'booked', '2024-11-18 19:24:26', '2024-11-18 19:24:26'),
(13, 3, 3, '2024-12-02', '10:30:00', 'available', '2024-11-18 19:24:26', '2024-11-18 19:24:26');

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
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('payment','refund') NOT NULL DEFAULT 'payment',
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `amount` decimal(10,2) NOT NULL,
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
  `remember_token` varchar(100) DEFAULT NULL,
  `last_activity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `code`, `expire_at`, `code_verified`, `remember_token`, `last_activity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'أحمد علي', 'ahmed.ali@example.com', NULL, '$2y$12$/UYlm9vrVFBrUq4LwV4wnuDe7UWlkZV2tNI79dCcrW6i/d0j2U2q.', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:25', '2024-11-18 19:24:25', NULL),
(2, 'سارة محمد', 'sara.mohamed@example.com', NULL, '$2y$12$Kail/V77Z2tZ/doWHFia9uzgH5vqnPobqmASOdwXim.T2zjWwhuiq', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:25', '2024-11-18 19:24:25', NULL),
(3, 'خالد حسن', 'khaled.hassan@example.com', NULL, '$2y$12$yU7f3QSrWrrYhxMUDxYZsuBJ8B7.CR4cOrXVn55RVb.xJYXlPo.1q', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:25', '2024-11-18 19:24:25', NULL),
(4, 'منى محمود', 'mona.mahmoud@example.com', NULL, '$2y$12$IEmMH2IH3OjhVdZ1VAjhZOLQUgnyBkIGq2axdk1OanK0ctbzsqNLa', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:25', '2024-11-18 19:24:25', NULL),
(5, 'يوسف إبراهيم', 'youssef.ibrahim@example.com', NULL, '$2y$12$nmrnyXiDQ5w24iI9HtKl7O0pZFzR1aIwnjeR7cKFgQDKi5NH8bltG', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:25', '2024-11-18 19:24:25', NULL),
(6, 'هدى أحمد', 'hoda.ahmed@example.com', NULL, '$2y$12$kFQ7tyakmIq9R0QArIXnR.tO7Qy2fE0mGQRFDTpmeXqjIRgZs1ay.', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:26', '2024-11-18 19:24:26', NULL),
(7, 'علي سعيد', 'ali.saeed@example.com', NULL, '$2y$12$LFsFzG34rWekCny2zKuoiOaOyuquelos6j4JHIDGlXrb6aBWMRwO2', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:26', '2024-11-18 19:24:26', NULL),
(8, 'نورا سامي', 'nora.samy@example.com', NULL, '$2y$12$R5rtxrkl4FQ8021a0cYXmu/aSFcsSXdMcg6oCtt53PyJpYkC5EteO', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:26', '2024-11-18 19:24:26', NULL),
(9, 'عمر فؤاد', 'omar.fouad@example.com', NULL, '$2y$12$iA41GzNR.stMTEbIkEUXi.fjMM/lJ3yLdo7g1pHIOdd6xpZIL8I0W', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:26', '2024-11-18 19:24:26', NULL),
(10, 'ليلى سمير', 'laila.samir@example.com', NULL, '$2y$12$I3Qq2U.NMnmseNDC7lZvO.Nh2D4PMnB5XPdB.h4MkgD7Eq7o7yRpu', NULL, NULL, 0, NULL, NULL, '2024-11-18 19:24:26', '2024-11-18 19:24:26', NULL);

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
  ADD KEY `bookings_service_schedule_id_foreign` (`service_schedule_id`),
  ADD KEY `bookings_client_id_foreign` (`client_id`);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
  ADD KEY `notifications_user_id_foreign` (`user_id`);

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
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_service_id_foreign` (`service_id`);

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
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
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
-- Indexes for table `serivce_offers`
--
ALTER TABLE `serivce_offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serivce_offers_service_id_foreign` (`service_id`);

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
  ADD KEY `services_partner_service_provider_id_foreign` (`partner_service_provider_id`);

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
-- Indexes for table `service_provider_partners`
--
ALTER TABLE `service_provider_partners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_provider_partners_service_provider_id_index` (`service_provider_id`),
  ADD KEY `service_provider_partners_partner_service_provider_id_index` (`partner_service_provider_id`);

--
-- Indexes for table `service_schedules`
--
ALTER TABLE `service_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_schedules_partner_service_provider_id_foreign` (`partner_service_provider_id`),
  ADD KEY `service_schedules_service_id_foreign` (`service_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_last_activity_index` (`last_activity`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `permission_groups`
--
ALTER TABLE `permission_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `serivce_offers`
--
ALTER TABLE `serivce_offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `serivce_provider_portfolios`
--
ALTER TABLE `serivce_provider_portfolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service_provider_partners`
--
ALTER TABLE `service_provider_partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service_schedules`
--
ALTER TABLE `service_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_service_schedule_id_foreign` FOREIGN KEY (`service_schedule_id`) REFERENCES `service_schedules` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_permission_group_id_foreign` FOREIGN KEY (`permission_group_id`) REFERENCES `permission_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `serivce_offers`
--
ALTER TABLE `serivce_offers`
  ADD CONSTRAINT `serivce_offers_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `serivce_provider_portfolios`
--
ALTER TABLE `serivce_provider_portfolios`
  ADD CONSTRAINT `serivce_provider_portfolios_service_provider_id_foreign` FOREIGN KEY (`service_provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_partner_service_provider_id_foreign` FOREIGN KEY (`partner_service_provider_id`) REFERENCES `service_provider_partners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `services_service_category_id_foreign` FOREIGN KEY (`service_category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD CONSTRAINT `service_providers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_provider_partners`
--
ALTER TABLE `service_provider_partners`
  ADD CONSTRAINT `service_provider_partners_partner_service_provider_id_foreign` FOREIGN KEY (`partner_service_provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_provider_partners_service_provider_id_foreign` FOREIGN KEY (`service_provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_schedules`
--
ALTER TABLE `service_schedules`
  ADD CONSTRAINT `service_schedules_partner_service_provider_id_foreign` FOREIGN KEY (`partner_service_provider_id`) REFERENCES `service_provider_partners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_schedules_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
