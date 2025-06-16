-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 09:08 PM
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
-- Database: `labtnu`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('manajemen_lab_tnu_cache_ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', 'i:1;', 1749930815),
('manajemen_lab_tnu_cache_ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4:timer', 'i:1749930815;', 1749930815),
('manajemen_lab_tnu_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1750012051),
('manajemen_lab_tnu_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1750012051;', 1750012051),
('manajemen_lab_tnu_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}', 1750094764);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE `labs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`id`, `name`, `location`, `created_at`, `updated_at`) VALUES
(1, 'Dach Ltd Lab', 'Lantai 3', '2025-06-13 10:19:12', '2025-06-13 10:19:12'),
(2, 'Rosenbaum Inc Lab', 'Lantai 1', '2025-06-13 10:19:12', '2025-06-13 10:19:12'),
(3, 'Smith, Spinka and Stracke Lab', 'Lantai 1', '2025-06-13 10:19:12', '2025-06-13 10:19:12'),
(4, 'Bartoletti-Daugherty Lab', 'Lantai 3', '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(5, 'Schultz Ltd Lab', 'Lantai 3', '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(6, 'Schuppe-Torp Lab', 'Lantai 3', '2025-06-13 10:25:04', '2025-06-13 10:25:04');

-- --------------------------------------------------------

--
-- Table structure for table `lab_bookings`
--

CREATE TABLE `lab_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_pengguna` varchar(255) DEFAULT NULL,
  `nit_nip` varchar(255) DEFAULT NULL,
  `lab_id` bigint(20) UNSIGNED NOT NULL,
  `course` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `keperluan` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `bukti_selesai` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lab_bookings_user_id_foreign` (`user_id`),
  KEY `lab_bookings_lab_id_foreign` (`lab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab_bookings`
--

INSERT INTO `lab_bookings` (`id`, `user_id`, `nama_pengguna`, `nit_nip`, `lab_id`, `course`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `keperluan`, `status`, `bukti_selesai`, `created_at`, `updated_at`) VALUES
(1, 5, 'mahasiswa', '12321', 4, '123123', '2025-06-15', '19:32:00', '20:32:00', NULL, 'completed', 'bukti-lab/gCn5mLrXaMBaERbbsJvetsTAtKJTyczJw9g68ewx.png', '2025-06-15 02:32:50', '2025-06-15 02:34:00'),
(2, 5, 'mahasiswa', '213213', 4, 'asdsadsa', '2025-06-17', '20:21:00', '22:21:00', NULL, 'approved', NULL, '2025-06-15 06:21:31', '2025-06-15 06:21:52');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_13_132331_create_permission_tables', 2),
(5, '2025_06_13_133701_create_labs_table', 3),
(6, '2025_06_13_140022_create_tools_table', 4),
(7, '2025_06_13_140906_create_lab_bookings_table', 5),
(8, '2025_06_13_142920_create_tool_bookings_table', 6),
(9, '2025_06_13_143650_add_bukti_selesai_to_lab_bookings_table', 7),
(10, '2025_06_14_143650_delete_bukti_selesai_to_lab_bookings_table', 8),
(11, '2025_06_14_143700_add_nama_pengguna_nitnip_to_tool_bookings_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'web', '2025-06-13 06:30:46', '2025-06-13 06:30:46'),
(2, 'admin', 'web', '2025-06-13 06:30:46', '2025-06-13 06:30:46'),
(3, 'monitor', 'web', '2025-06-13 06:30:46', '2025-06-13 06:30:46'),
(4, 'pengguna', 'web', '2025-06-13 06:30:46', '2025-06-13 06:30:46');

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
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2dbmPXqQxMKTPwrFHBte3fJ1ZyjEo6zAFAZZ52ik', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoic3B0dlFwTGthN2JFODZ3b0lnOU1wdDlJMk14dFFFZHZYRlJwZXpoOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9MYWItVE5VIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJG5oMUZubEx3aFRJN0VxNllORFRHYk9CWFF6WUtlOVllLnZSRkNJdkp6QTRWZ0dZQmpydDVpIjt9', 1750013152),
('ufWFkpF4m0XihjqdKsJZuYnpA7J97nwT4CQQxs0f', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUE80SGlhSldOY0ZZRzYxRVdEcDdUS1czcmVlc2ZPVXhiNTRhM2kzNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9MYWItVE5VIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJHAyQ29UaW9iT2loRVdhREZuaUtJaS56Q2M2TzNraG1NakpNM2RVYnAvYm5YdWFncjBNTzNLIjt9', 1750014234);

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `lab_id` bigint(20) UNSIGNED NOT NULL,
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  `available_quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tools_lab_id_foreign` (`lab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`id`, `name`, `lab_id`, `total_quantity`, `available_quantity`, `created_at`, `updated_at`) VALUES
(1, 'et Tool', 1, 10, 10, '2025-06-13 10:25:04', '2025-06-13 22:51:38'),
(2, 'quam Tool', 1, 1, 1, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(3, 'consequuntur Tool', 1, 10, 10, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(4, 'sed Tool', 1, 8, 5, '2025-06-13 10:25:04', '2025-06-13 22:52:56'),
(5, 'recusandae Tool', 1, 3, 3, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(6, 'perspiciatis Tool', 2, 7, 7, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(7, 'deleniti Tool', 2, 3, 3, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(8, 'quasi Tool', 2, 3, 3, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(9, 'ex Tool', 2, 9, 9, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(10, 'rerum Tool', 2, 1, 1, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(11, 'delectus Tool', 3, 10, 4, '2025-06-13 10:25:04', '2025-06-15 06:06:31'),
(12, 'nihil Tool', 3, 2, 2, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(13, 'reiciendis Tool', 3, 4, 4, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(14, 'velit Tool', 3, 8, 8, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(15, 'magnam Tool', 3, 4, 4, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(16, 'molestias Tool', 4, 7, 7, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(17, 'esse Tool', 4, 9, 9, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(18, 'excepturi Tool', 4, 10, 10, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(19, 'minima Tool', 4, 8, 8, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(20, 'nulla Tool', 4, 9, 9, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(21, 'eligendi Tool', 5, 6, 6, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(22, 'sit Tool', 5, 5, 5, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(23, 'suscipit Tool', 5, 4, 4, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(24, 'nesciunt Tool', 5, 2, 2, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(25, 'earum Tool', 5, 10, 10, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(26, 'atque Tool', 6, 5, 13, '2025-06-13 10:25:04', '2025-06-14 05:04:43'),
(27, 'sed Tool', 6, 2, 2, '2025-06-13 10:25:04', '2025-06-13 10:25:04'),
(28, 'modi Tool', 6, 10, 29, '2025-06-13 10:25:04', '2025-06-14 12:50:11'),
(29, 'cumque Tool', 6, 3, 9, '2025-06-13 10:25:04', '2025-06-15 02:36:22'),
(30, 'est Tool', 6, 9, 9, '2025-06-13 10:25:04', '2025-06-13 10:25:04');

-- --------------------------------------------------------

--
-- Table structure for table `tool_bookings`
--

CREATE TABLE `tool_bookings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_pengguna` varchar(255) DEFAULT NULL,
  `nit_nip` varchar(255) DEFAULT NULL,
  `tool_id` bigint(20) UNSIGNED NOT NULL,
  `course` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `jumlah` int(11) NOT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `bukti_selesai` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tool_bookings_user_id_foreign` (`user_id`),
  KEY `tool_bookings_tool_id_foreign` (`tool_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tool_bookings`
--

INSERT INTO `tool_bookings` (`id`, `user_id`, `nama_pengguna`, `nit_nip`, `tool_id`, `course`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `jumlah`, `status`, `bukti_selesai`, `created_at`, `updated_at`) VALUES
(1, 5, 'mahasiswa', '213123', 29, 'asdasd', '2025-06-16', '18:36:00', '21:36:00', 8, 'completed', 'bukti-alat/VIiaP48ICw8LwIL72rfagM2KJaXYJD2hQZ7IsFoS.png', '2025-06-15 02:36:22', '2025-06-15 02:36:55'),
(2, 5, 'asdsad', '213123', 11, 'asdasdsa', '2025-06-18', '10:06:00', '16:06:00', 6, 'approved', NULL, '2025-06-15 06:06:31', '2025-06-15 06:06:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'superadmin@webmail.com', NULL, '$2y$12$7Nrz2pWQ.nlMWWoW5po6EeKLGXqw0.zCuxLDRJmPegAg.UjJIPO6i', 'B0y5yILmGfTdqNwxjfH5rig7enSWmOyxM90Gx3x7FhUpas2weiJuirS5o5UJ', '2025-06-13 01:13:40', '2025-06-13 01:13:40'),
(2, 'superadmin', 'superadmin@labtnu.test', NULL, '$2y$12$p2CoTiobOihEWaDFniKIi.zCc6O3khmMjJM3dUbp/bnXuagr0MO3K', '3WMfOISoNmgdkNHIUgs8hfZqnc7umSxVfUwz8ZPvH0yNTHPQdjkNQv3ZJNXM', '2025-06-13 06:30:46', '2025-06-13 06:30:46'),
(3, 'admin', 'admin@labtnu.test', NULL, '$2y$12$4zL29ABhPpEm0ESlGZJ1Velb9CLdwywiX6TMNxa0WixORmSntbm3a', 'stA2XZtYazMs4ohEkrSrnOvE5DbvGc7gItCXEPGBezwh21s3fy0uWY40bGy4', '2025-06-13 06:30:47', '2025-06-13 06:30:47'),
(4, 'monitor', 'monitor@labtnu.test', NULL, '$2y$12$nh1FnlLwhTI7Eq6YNDTGbOBXQzYKe9Ye.vRFCIvJzA4VgGYBjrt5i', 'YIe1mOW0xIVjXuB3aI19SCNOEaNYwDqrFwMmcRDQ7i8xqplbHSHM597pF73l', '2025-06-13 06:30:47', '2025-06-13 06:30:47'),
(5, 'mahasiswa', 'pengguna@labtnu.test', NULL, '$2y$12$LyopoJNBLhZjEZ9R42OLsuNxcuBac6subdT2sRbLRV/3tSdi7yIYK', 'SHp7zKVEIpM8OSVNXTzTgazPLLBPfxpmPePcqWkPLJWvNaWAn1fcMJV378H2', '2025-06-13 06:30:47', '2025-06-13 06:30:47'),
(6, 'Test User', 'test@example.com', '2025-06-13 10:13:20', '$2y$12$qBm9n59lAplEFhbxbBtsdO5YxzqmMeiKRQLFNr9zGPvXq0RzqPwlq', '8M6urH1k5z', '2025-06-13 10:13:20', '2025-06-13 10:13:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--

--
-- Indexes for table `cache_locks`
--

--
-- Indexes for table `failed_jobs`
--

--
-- Indexes for table `jobs`
--

--
-- Indexes for table `job_batches`
--

--
-- Indexes for table `labs`
--

--
-- Indexes for table `lab_bookings`
--

--
-- Indexes for table `migrations`
--

--
-- Indexes for table `model_has_roles`
--

--
-- Indexes for table `password_reset_tokens`
--

--
-- Indexes for table `permissions`
--

--
-- Indexes for table `roles`
--

--
-- Indexes for table `sessions`
--

--
-- Indexes for table `tools`
--

--
-- Indexes for table `tool_bookings`
--

--
-- Indexes for table `users`
--

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--

--
-- AUTO_INCREMENT for table `jobs`
--

--
-- AUTO_INCREMENT for table `labs`
--

--
-- AUTO_INCREMENT for table `lab_bookings`
--

--
-- AUTO_INCREMENT for table `migrations`
--

--
-- AUTO_INCREMENT for table `permissions`
--

--
-- AUTO_INCREMENT for table `roles`
--

--
-- AUTO_INCREMENT for table `tools`
--

--
-- AUTO_INCREMENT for table `tool_bookings`
--

--
-- AUTO_INCREMENT for table `users`
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lab_bookings`
--
ALTER TABLE `lab_bookings`
  ADD CONSTRAINT `lab_bookings_lab_id_foreign` FOREIGN KEY (`lab_id`) REFERENCES `labs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tools`
--
ALTER TABLE `tools`
  ADD CONSTRAINT `tools_lab_id_foreign` FOREIGN KEY (`lab_id`) REFERENCES `labs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tool_bookings`
--
ALTER TABLE `tool_bookings`
  ADD CONSTRAINT `tool_bookings_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tool_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
