-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 10, 2025 at 02:45 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baraka-hijab`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `provinsi` varchar(255) NOT NULL,
  `kabupaten` varchar(255) NOT NULL,
  `kecamatan` varchar(255) NOT NULL,
  `kelurahan` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `detail` text,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `provinsi`, `kabupaten`, `kecamatan`, `kelurahan`, `postal_code`, `detail`, `is_primary`, `created_at`, `updated_at`) VALUES
(2, 4, 'Jawa Tengah', 'Rembang', 'Sale', 'Sale', '', 'Desa sale RT/RW : 02/04 (depan Alfamart Sale), Kecamatan Sale, Kabupaten  Rembang, Jawa Tengah', 0, '2025-06-05 13:33:19', '2025-06-08 03:45:46'),
(4, 4, 'DKI Jakarta', 'Kota Adm. Jakarta Barat', 'Kebon Jeruk', 'Sukabumi Selatan', '11560', 'asdasdas', 1, '2025-06-07 08:11:27', '2025-06-08 03:45:46'),
(5, 4, 'Bali', 'Kab. Karangasem', 'Kubu', 'Ban', '80853', 'lkhlkh', 0, '2025-06-07 08:12:21', '2025-06-08 03:45:46'),
(7, 4, 'Daerah Istimewa Yogyakarta', 'Kota Yogyakarta', 'Kraton', 'Patehan', '55133', 'asek', 0, '2025-06-09 00:12:45', '2025-06-09 00:12:45');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('77de68daecd823babbb58edb1c8e14d7106e83bb', 'i:3;', 1746372390),
('77de68daecd823babbb58edb1c8e14d7106e83bb:timer', 'i:1746372390;', 1746372390),
('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:2;', 1749464123),
('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1749464123;', 1749464123);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `quantity` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_variant_id`, `quantity`, `created_at`, `updated_at`) VALUES
(21, 4, 16, 2, '2025-05-20 02:13:04', '2025-06-04 03:27:42'),
(32, 4, 15, 1, '2025-06-04 03:34:03', '2025-06-04 03:34:03');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Woman', 'woman', NULL, '2025-04-28 08:20:21', '2025-05-02 05:10:39'),
(2, 'Hijabs', 'hijabs', NULL, '2025-04-28 08:25:51', '2025-05-02 06:09:18'),
(3, 'contoh serviceCategory', 'contoh-servicecategory', NULL, '2025-06-02 01:29:38', '2025-06-02 01:29:38'),
(5, 'anianni', 'anianni', NULL, '2025-06-02 03:28:54', '2025-06-02 03:28:54');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_04_28_103311_create_categories_table', 1),
(5, '2025_04_28_130756_create_products_table', 1),
(6, '2025_04_28_130825_create_photos_table', 1),
(7, '2025_04_28_130919_create_product_variants_table', 1),
(8, '2025_04_28_131950_create_carts_table', 1),
(12, '2025_04_28_162227_add_unique_constraint_to_slug_columns', 2),
(13, '2025_04_30_130327_create_personal_access_tokens_table', 2),
(14, '2025_05_02_112913_create_sub_categories_table', 3),
(16, '2025_05_02_122515_add_sub_category_id_to_products_table', 4),
(18, '2025_05_31_154730_add_column_weight_in_product_variants_table', 5),
(24, '2025_06_02_092451_create_addresses_table', 6),
(25, '2025_06_05_202845_add_phone_number_column_to_users_table', 6),
(27, '2025_06_05_205344_add_postal_code_column_to_addresses_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 3, 'auth_token', '46bbe6f8cdd3b50ca9b1ab2b3c6f32daa27921dfffa0ad49511f959df35f2887', '[\"*\"]', NULL, NULL, '2025-05-13 08:07:24', '2025-05-13 08:07:24'),
(2, 'App\\Models\\User', 4, 'auth_token', '60c0f111c1e59343cb64686fe871a8e79e949292ff22a0b4289eb0964aa15150', '[\"*\"]', NULL, NULL, '2025-05-13 08:16:25', '2025-05-13 08:16:25'),
(3, 'App\\Models\\User', 4, 'auth_token', '1eb0a4c81227245c15d2a7ad8bf909201c2cb4aa39a351b8977a2f21945ab4b2', '[\"*\"]', NULL, NULL, '2025-05-13 08:16:46', '2025-05-13 08:16:46'),
(4, 'App\\Models\\User', 4, 'auth_token', 'e8935d514e9cbff7fead4472d6317ca1a8519f37b1ae953f6ca823590450345a', '[\"*\"]', NULL, NULL, '2025-05-13 08:38:16', '2025-05-13 08:38:16'),
(5, 'App\\Models\\User', 3, 'auth_token', 'b409153b8432ded441290a229bf0ddcfb459dc5a262bf70595db1d00db130735', '[\"*\"]', '2025-05-14 10:23:06', NULL, '2025-05-14 08:58:51', '2025-05-14 10:23:06'),
(6, 'App\\Models\\User', 4, 'auth_token', 'cb8eb48ad6d3f2918333cfe6644471ce12b0f5d8fde15888bb86a3a3f004bfbe', '[\"*\"]', NULL, NULL, '2025-05-14 09:56:39', '2025-05-14 09:56:39'),
(7, 'App\\Models\\User', 4, 'auth_token', 'd05d6dd7ae0f8652b05bcf1f5d75a7fc6b76cd2205cb6b6e5fe500806097d6a7', '[\"*\"]', NULL, NULL, '2025-05-14 10:05:10', '2025-05-14 10:05:10'),
(8, 'App\\Models\\User', 4, 'auth_token', '73d50b1e98c4a3298f7d53aad11604c09d44e25ad0ba1e7f3997f3212fdad462', '[\"*\"]', NULL, NULL, '2025-05-14 10:17:14', '2025-05-14 10:17:14'),
(9, 'App\\Models\\User', 4, 'auth_token', '770427fbc2594a90f1118210ad99c2dc44be59a16596d4b3d8d86df78916d7e9', '[\"*\"]', '2025-05-14 10:18:19', NULL, '2025-05-14 10:18:17', '2025-05-14 10:18:19'),
(10, 'App\\Models\\User', 4, 'auth_token', '1cf4de98fe2724de60a5d676bd9e13949da50aa9d71323479327cf499f67e46d', '[\"*\"]', '2025-05-14 10:19:16', NULL, '2025-05-14 10:19:13', '2025-05-14 10:19:16'),
(11, 'App\\Models\\User', 4, 'auth_token', '547d658940531300c7efd44f59556ec2f15544c042e9b3aadce2687eb0e61b6f', '[\"*\"]', '2025-05-15 03:55:58', NULL, '2025-05-15 03:55:46', '2025-05-15 03:55:58'),
(13, 'App\\Models\\User', 4, 'auth_token', '6592910667f96221cc76e184fc3245aac89233346340751829d75ca771902f10', '[\"*\"]', '2025-05-30 22:56:11', NULL, '2025-05-16 05:08:18', '2025-05-30 22:56:11'),
(14, 'App\\Models\\User', 4, 'auth_token', '7ad77ab189bf8dde4576aaee2b344f479ae432775824d017e62f38fbedd8b66f', '[\"*\"]', '2025-05-18 02:23:03', NULL, '2025-05-18 02:23:02', '2025-05-18 02:23:03'),
(15, 'App\\Models\\User', 4, 'auth_token', 'b49517bce9fe19a0081ed6b6c0aee04ca688e2146eaf1e31f3e31a018cbd42fe', '[\"*\"]', '2025-05-18 02:38:47', NULL, '2025-05-18 02:38:46', '2025-05-18 02:38:47'),
(16, 'App\\Models\\User', 4, 'auth_token', '3d98f576c60ac29cb43fec66643cdd26f25c6e7a26f91e7b3c328b7b018eb12e', '[\"*\"]', '2025-05-18 02:40:45', NULL, '2025-05-18 02:40:44', '2025-05-18 02:40:45'),
(17, 'App\\Models\\User', 4, 'auth_token', '4f1e5d1efa12919018718b4c836df3a4ebd438edc85f8ebadccf4bea08f1c81b', '[\"*\"]', '2025-05-18 02:41:29', NULL, '2025-05-18 02:41:28', '2025-05-18 02:41:29'),
(18, 'App\\Models\\User', 4, 'auth_token', '51af0beb460009f24e21cc136e3b1d733af73835726628c7e90b4cfc32e11196', '[\"*\"]', '2025-05-18 02:46:45', NULL, '2025-05-18 02:46:44', '2025-05-18 02:46:45'),
(19, 'App\\Models\\User', 4, 'auth_token', '63215a649d85b4bdd561ca3e7c42c709b480dff87b2833e406fdc8ca705a4012', '[\"*\"]', '2025-05-18 02:48:14', NULL, '2025-05-18 02:48:13', '2025-05-18 02:48:14'),
(20, 'App\\Models\\User', 4, 'auth_token', 'fa924d1baafb211b7dd8d83e8356f26c1d073a4bad0d7a7a776845e8283cd187', '[\"*\"]', '2025-05-18 02:49:58', NULL, '2025-05-18 02:49:56', '2025-05-18 02:49:58'),
(21, 'App\\Models\\User', 4, 'auth_token', '99b57c4ce1f3b23dcfe4ce18374ca8bb1f8aa8ad2c67fed8e093667979b71896', '[\"*\"]', '2025-05-18 02:52:18', NULL, '2025-05-18 02:52:17', '2025-05-18 02:52:18'),
(22, 'App\\Models\\User', 4, 'auth_token', '71ee1929a23e4b7ac94c787054deb43974ba686390fd33635b310b034d9dac88', '[\"*\"]', '2025-05-18 02:52:48', NULL, '2025-05-18 02:52:47', '2025-05-18 02:52:48'),
(23, 'App\\Models\\User', 4, 'auth_token', '99b99e7899f46c2747307f634839789938c1334f2eb345953faebda36f5b8ec5', '[\"*\"]', '2025-05-18 02:57:10', NULL, '2025-05-18 02:57:09', '2025-05-18 02:57:10'),
(24, 'App\\Models\\User', 4, 'auth_token', '9228e91b629335cf037fe68c5634630ee0ef31faf20aa3b838e96c99022a2f8a', '[\"*\"]', '2025-05-18 03:44:15', NULL, '2025-05-18 02:57:45', '2025-05-18 03:44:15'),
(25, 'App\\Models\\User', 4, 'auth_token', '3566e4a1c05dcc386eadb927e7490ead245b7be1b45d619107f54bd1ab6901b1', '[\"*\"]', '2025-05-18 09:00:58', NULL, '2025-05-18 08:51:13', '2025-05-18 09:00:58'),
(26, 'App\\Models\\User', 4, 'auth_token', '0f0b4fdee51faf9c94f780f8a9eddcac13d8b51f6954183f79a70efc1bc5a4a2', '[\"*\"]', '2025-05-18 09:01:28', NULL, '2025-05-18 09:01:19', '2025-05-18 09:01:28'),
(27, 'App\\Models\\User', 4, 'auth_token', 'f1c9e41d9cf74e77a62db59c7ffc4145f1c55c14d4fc20fe01e8e949539bc70a', '[\"*\"]', '2025-05-18 09:02:16', NULL, '2025-05-18 09:02:08', '2025-05-18 09:02:16'),
(28, 'App\\Models\\User', 4, 'auth_token', '947ac4ed7c2df3fe0643387ab9cd11480ddfadc56d4c53049c43c9458886c334', '[\"*\"]', '2025-05-18 09:06:11', NULL, '2025-05-18 09:06:02', '2025-05-18 09:06:11'),
(29, 'App\\Models\\User', 4, 'auth_token', 'f5938e83f22577f6de93a981389a7ea51cae33e620d05959c0b2945e441f45d0', '[\"*\"]', '2025-05-18 09:07:24', NULL, '2025-05-18 09:07:16', '2025-05-18 09:07:24'),
(30, 'App\\Models\\User', 4, 'auth_token', '4606632515891734ce563c1727afb49d9b6dd034bc5635a30937e933e35f94de', '[\"*\"]', '2025-05-18 09:08:32', NULL, '2025-05-18 09:08:25', '2025-05-18 09:08:32'),
(31, 'App\\Models\\User', 4, 'auth_token', '0d12c833887c7aaa4e804274112d030b81a157761e31703ff153568122769d4e', '[\"*\"]', '2025-05-18 09:17:07', NULL, '2025-05-18 09:15:28', '2025-05-18 09:17:07'),
(32, 'App\\Models\\User', 4, 'auth_token', '20e0e5995a99716fc50c2198207f7924a7922472179fde23aad701c02e75151f', '[\"*\"]', '2025-05-18 09:19:44', NULL, '2025-05-18 09:17:58', '2025-05-18 09:19:44'),
(33, 'App\\Models\\User', 4, 'auth_token', '3f2968141d090c0f6855d64ef36d13aeff63b7371ce2ffe08e2957e1c6fede00', '[\"*\"]', '2025-05-18 09:26:22', NULL, '2025-05-18 09:21:54', '2025-05-18 09:26:22'),
(34, 'App\\Models\\User', 4, 'auth_token', 'e66d4e09fb19efba663340cd8d761526c440ac563ac6b46f80f6e51fd68e2f7a', '[\"*\"]', '2025-05-18 09:27:58', NULL, '2025-05-18 09:27:38', '2025-05-18 09:27:58'),
(35, 'App\\Models\\User', 4, 'auth_token', 'e7f4b1465650f8a325caddda04e77de295b0d969a64f47c4a151aa98d2fedfbd', '[\"*\"]', '2025-05-19 02:27:34', NULL, '2025-05-19 01:15:21', '2025-05-19 02:27:34'),
(36, 'App\\Models\\User', 4, 'auth_token', '37f86336dbf680b1dafafd7e761ecdcb58e21be9a32d1c5d0ec680a79e99e02a', '[\"*\"]', '2025-05-20 05:27:45', NULL, '2025-05-20 02:12:56', '2025-05-20 05:27:45'),
(37, 'App\\Models\\User', 4, 'auth_token', '5cd6a4aa9474d30230a2f119851fe5aaa68c3fefbe3d664e54c971ec8a5bc146', '[\"*\"]', '2025-05-22 08:35:54', NULL, '2025-05-20 10:23:01', '2025-05-22 08:35:54'),
(38, 'App\\Models\\User', 3, 'auth_token', '4bf5e88cc9c7485da6b5c4a7f30f23eb99f11541955963fcf4e2431e70a79c4b', '[\"*\"]', '2025-05-21 21:38:31', NULL, '2025-05-21 21:38:27', '2025-05-21 21:38:31'),
(39, 'App\\Models\\User', 3, 'auth_token', 'f26d129d8117eecf127b1d273e21dd53b461fc3ab761925908fc872f609ab40b', '[\"*\"]', '2025-05-29 07:29:31', NULL, '2025-05-29 07:20:51', '2025-05-29 07:29:31'),
(40, 'App\\Models\\User', 3, 'auth_token', 'adcd93d9d0410e086f6d63c46fca1e24e4d7a9c96d1a87f7003a4b2598f49d37', '[\"*\"]', '2025-05-29 07:36:29', NULL, '2025-05-29 07:36:16', '2025-05-29 07:36:29'),
(41, 'App\\Models\\User', 3, 'auth_token', '5aedb8c01fe39f583def1ea4abc013b507fee90e24808751134704f671dd60c4', '[\"*\"]', NULL, NULL, '2025-05-29 07:37:59', '2025-05-29 07:37:59'),
(42, 'App\\Models\\User', 3, 'auth_token', 'fa6c6c105fbb5b209c0c51a36feb613ab04362a5a8c84313e54b4d77c9ff04bb', '[\"*\"]', '2025-05-29 07:43:42', NULL, '2025-05-29 07:38:45', '2025-05-29 07:43:42'),
(43, 'App\\Models\\User', 3, 'auth_token', '4480e8f80aac0601145bb4b9851dbe63133eacd72e9c7129b51a1b2d2232877f', '[\"*\"]', '2025-05-29 07:43:56', NULL, '2025-05-29 07:43:52', '2025-05-29 07:43:56'),
(44, 'App\\Models\\User', 3, 'auth_token', '79eac1f3f1c3130f632d08d9d6824af5d29bdba6b5674aad6d824b049de7ca64', '[\"*\"]', '2025-05-29 07:57:40', NULL, '2025-05-29 07:57:36', '2025-05-29 07:57:40'),
(45, 'App\\Models\\User', 3, 'auth_token', 'e5c0a4a3bb8c3756154cc18f11dae3cfe65c90e774a96f5e51aea43981249642', '[\"*\"]', '2025-05-29 08:09:17', NULL, '2025-05-29 08:09:13', '2025-05-29 08:09:17'),
(46, 'App\\Models\\User', 3, 'auth_token', 'd5df0c35d8b955c65064971a5fdb0dd8568ad4976807dfb38c096317dbc2bb00', '[\"*\"]', '2025-05-29 08:09:49', NULL, '2025-05-29 08:09:44', '2025-05-29 08:09:49'),
(47, 'App\\Models\\User', 3, 'auth_token', '51e838d10443ba8bfabfae4894a4d4f0e1f1ed112a1508115b8a3b88ef25bba4', '[\"*\"]', '2025-05-29 08:10:01', NULL, '2025-05-29 08:10:00', '2025-05-29 08:10:01'),
(48, 'App\\Models\\User', 3, 'auth_token', 'defa776085a210d223731eb1ef5a2ef5c6cf9db6f935b44d642fe9f59e44b3c9', '[\"*\"]', '2025-05-29 08:10:19', NULL, '2025-05-29 08:10:18', '2025-05-29 08:10:19'),
(49, 'App\\Models\\User', 3, 'auth_token', '8963508a215825239925dbc66ef45c5a142eb68bc71729ac4078590bb2d24d3e', '[\"*\"]', '2025-05-29 08:11:41', NULL, '2025-05-29 08:11:40', '2025-05-29 08:11:41'),
(50, 'App\\Models\\User', 3, 'auth_token', 'd9e09b55cc5830ce657d0088690d8a530e77203ddb112b506f2c04e90658318a', '[\"*\"]', '2025-05-29 08:31:36', NULL, '2025-05-29 08:31:35', '2025-05-29 08:31:36'),
(51, 'App\\Models\\User', 3, 'auth_token', '17a137c16a114bdae0d672b40867d1e327ee21c8c96b23e7e558d7c101a0e843', '[\"*\"]', '2025-05-29 08:33:08', NULL, '2025-05-29 08:33:07', '2025-05-29 08:33:08'),
(52, 'App\\Models\\User', 3, 'auth_token', 'ca331555f736d14e2d4896b5553abcf43046bfe01307bc05accbc1d92a59afb8', '[\"*\"]', '2025-05-29 08:35:15', NULL, '2025-05-29 08:35:14', '2025-05-29 08:35:15'),
(53, 'App\\Models\\User', 3, 'auth_token', '5be6a8f209d536d4194ff45aa51b859571e38498cf0f4691b98b5472e3477c3c', '[\"*\"]', '2025-05-29 08:35:44', NULL, '2025-05-29 08:35:43', '2025-05-29 08:35:44'),
(54, 'App\\Models\\User', 3, 'auth_token', '9ee9e6237922add3a478cf0c0c4c4529ccf02d262644211d07d8e48603f1babe', '[\"*\"]', '2025-05-29 08:35:56', NULL, '2025-05-29 08:35:55', '2025-05-29 08:35:56'),
(55, 'App\\Models\\User', 3, 'auth_token', '0629c3fbaf54c4be64dfd8b387e9860b65b9c0b77fc286a16e916c198d3eecaf', '[\"*\"]', '2025-05-29 08:36:56', NULL, '2025-05-29 08:36:55', '2025-05-29 08:36:56'),
(56, 'App\\Models\\User', 3, 'auth_token', '2c083b855d43b066f60b63e0b40bc7ebe9b8e92cd0fae81bf0c07f2d49d9ca7c', '[\"*\"]', '2025-05-29 08:39:11', NULL, '2025-05-29 08:39:10', '2025-05-29 08:39:11'),
(57, 'App\\Models\\User', 3, 'auth_token', '300e271c707b8b7a5fc696d0fe9d1a81cb55d049afe71e0a765237181c8a855f', '[\"*\"]', '2025-05-29 08:39:47', NULL, '2025-05-29 08:39:47', '2025-05-29 08:39:47'),
(58, 'App\\Models\\User', 3, 'auth_token', '6381496e00b70f8bc4b78af258db7cbf17c747fd876bbb430274766a2fd440b9', '[\"*\"]', '2025-05-29 08:40:40', NULL, '2025-05-29 08:40:39', '2025-05-29 08:40:40'),
(59, 'App\\Models\\User', 3, 'auth_token', '3470c6e50e928a4a56d6018df34a178aaa8cbfd8f687020f3f8eb84f2b878d59', '[\"*\"]', '2025-05-29 08:41:30', NULL, '2025-05-29 08:41:29', '2025-05-29 08:41:30'),
(60, 'App\\Models\\User', 3, 'auth_token', 'be616ab44f3064ebc635838241a1edc3cfa14c4776d1bcc0b8e6e06b3afffeb3', '[\"*\"]', '2025-05-29 08:49:23', NULL, '2025-05-29 08:49:22', '2025-05-29 08:49:23'),
(61, 'App\\Models\\User', 3, 'auth_token', 'd162c7a02f1cdadca0349c507589d281426ae1bc1124c2e6ebb2df73e48a82e5', '[\"*\"]', '2025-05-29 08:49:51', NULL, '2025-05-29 08:49:49', '2025-05-29 08:49:51'),
(62, 'App\\Models\\User', 3, 'auth_token', 'a8a7bdf35ed25a204e30c48f23978c2912647f6117b9a117ec513e19f06524d0', '[\"*\"]', '2025-05-29 08:50:33', NULL, '2025-05-29 08:50:31', '2025-05-29 08:50:33'),
(63, 'App\\Models\\User', 4, 'auth_token', '667d4cd7ce27970fa00b9f66c6d72e6622fdcafda72f7e5bfde310286b492622', '[\"*\"]', '2025-05-29 08:52:29', NULL, '2025-05-29 08:52:24', '2025-05-29 08:52:29'),
(64, 'App\\Models\\User', 3, 'auth_token', 'a2fc4b943b62e4552de1a44c519e9dafbcec338ce762f49ff49959149cf60ea1', '[\"*\"]', '2025-05-29 08:55:09', NULL, '2025-05-29 08:55:08', '2025-05-29 08:55:09'),
(65, 'App\\Models\\User', 3, 'auth_token', 'c0d9754cb1864c5fd9f242fa708913fe83563bd6516ea7670b293d4621617b51', '[\"*\"]', '2025-05-29 08:55:43', NULL, '2025-05-29 08:55:42', '2025-05-29 08:55:43'),
(66, 'App\\Models\\User', 3, 'auth_token', 'fdb4550fb0c2daed5aa980774c6c79d6594d0025c56b26a564a89ef4bc6a2863', '[\"*\"]', '2025-05-29 08:56:03', NULL, '2025-05-29 08:56:02', '2025-05-29 08:56:03'),
(67, 'App\\Models\\User', 3, 'auth_token', '52ddfdec15d8cfca8725a0ed6922be2b323128ec0f9bcec73855b16f21c6d72d', '[\"*\"]', '2025-05-29 08:56:34', NULL, '2025-05-29 08:56:33', '2025-05-29 08:56:34'),
(68, 'App\\Models\\User', 3, 'auth_token', '6aa773cfab232101c16c5aa1e025dbc281f84f43d619dc60b2b190afb0744b96', '[\"*\"]', '2025-05-29 09:00:58', NULL, '2025-05-29 09:00:57', '2025-05-29 09:00:58'),
(69, 'App\\Models\\User', 3, 'auth_token', '57619420eda310d4921d36520111cbee5402ad7f187f0029baa74e6a9ffcd098', '[\"*\"]', '2025-05-29 09:06:45', NULL, '2025-05-29 09:06:44', '2025-05-29 09:06:45'),
(70, 'App\\Models\\User', 3, 'auth_token', '63a988b988acf3e39c3c18c13a17dda1c916fe9525da641abeefa682e7e86c4a', '[\"*\"]', '2025-05-29 09:07:45', NULL, '2025-05-29 09:07:44', '2025-05-29 09:07:45'),
(71, 'App\\Models\\User', 4, 'auth_token', '0f47950ae07a015357f3d90f650ffb6d4ed4d49f47586d743bde7ef44d0f7ce2', '[\"*\"]', '2025-05-29 09:08:59', NULL, '2025-05-29 09:08:07', '2025-05-29 09:08:59'),
(72, 'App\\Models\\User', 3, 'auth_token', '1716c0a8c6df5d4d934fbb5ba42955de13ddebdfe4ca1df72cd2bb4b3068329b', '[\"*\"]', '2025-05-29 09:08:55', NULL, '2025-05-29 09:08:54', '2025-05-29 09:08:55'),
(73, 'App\\Models\\User', 3, 'auth_token', '041a666bf6aa6a6d44e5e7cc90b4c1fbc50fb4eb79b17b1bfbdfbbff3562d47f', '[\"*\"]', '2025-05-29 09:09:16', NULL, '2025-05-29 09:09:15', '2025-05-29 09:09:16'),
(74, 'App\\Models\\User', 3, 'auth_token', '915e7dd4207ba72ebbdbd22abf6863a820b0131e43cef455e9674b56ac28a411', '[\"*\"]', '2025-05-29 09:11:25', NULL, '2025-05-29 09:11:24', '2025-05-29 09:11:25'),
(75, 'App\\Models\\User', 3, 'auth_token', 'd92c4939dfe9dfe6c1cdc99d31950312d38373be061606a12208c0a9ad5bc7ce', '[\"*\"]', '2025-05-29 09:12:23', NULL, '2025-05-29 09:12:22', '2025-05-29 09:12:23'),
(76, 'App\\Models\\User', 4, 'auth_token', '126b12d3a45e795ebfde3dfe03426ce397e2bb481fdcf6430ad46bda02498a25', '[\"*\"]', '2025-05-29 09:12:57', NULL, '2025-05-29 09:12:44', '2025-05-29 09:12:57'),
(77, 'App\\Models\\User', 4, 'auth_token', '9d0af68a872e7de1375cd234707b8fc32a1bdd01f33fb3384ea8162c5dc98ee7', '[\"*\"]', '2025-05-30 11:07:42', NULL, '2025-05-29 09:13:03', '2025-05-30 11:07:42'),
(78, 'App\\Models\\User', 4, 'auth_token', '59962bf3dbe595ce5a040ab3a2efc1d9bc6ce445057a94e401933e70b9310a8c', '[\"*\"]', '2025-05-30 22:17:07', NULL, '2025-05-30 21:56:36', '2025-05-30 22:17:07'),
(79, 'App\\Models\\User', 4, 'auth_token', '15e5f34536a1239d7533325f044da249a1cdbb1b00466b87bdc9786e68291f48', '[\"*\"]', '2025-06-04 11:16:11', NULL, '2025-05-30 22:20:27', '2025-06-04 11:16:11'),
(81, 'App\\Models\\User', 4, 'auth_token', '07ff8c442f058e419d498212264c0f87bc764af332ae305a9b6f9db1c7bcedd5', '[\"*\"]', '2025-06-05 13:37:48', NULL, '2025-06-02 03:32:30', '2025-06-05 13:37:48'),
(82, 'App\\Models\\User', 4, 'auth_token', '05fd4529ec50c5d041516d6d07a39b299531a498a56750ca3a98e67d2ad4735e', '[\"*\"]', '2025-06-05 10:24:23', NULL, '2025-06-04 11:16:38', '2025-06-05 10:24:23'),
(83, 'App\\Models\\User', 4, 'auth_token', '7652260735cb2f6acf58fcacffc86dc73606d9d80992a26b6a9331008062b1dd', '[\"*\"]', '2025-06-05 13:37:13', NULL, '2025-06-05 10:26:31', '2025-06-05 13:37:13'),
(84, 'App\\Models\\User', 4, 'auth_token', 'd4068c1bfb5b1db7cdc66b32f9059a3b8100dbc96977186c8957285b26fdbe8c', '[\"*\"]', '2025-06-09 08:58:15', NULL, '2025-06-05 13:39:48', '2025-06-09 08:58:15'),
(85, 'App\\Models\\User', 4, 'auth_token', 'db263e8688f5831f5eda3baeb51afa5e32c37157f8fbb1d0b5d8fba709abb569', '[\"*\"]', '2025-06-08 07:09:50', NULL, '2025-06-08 05:37:42', '2025-06-08 07:09:50');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` bigint UNSIGNED NOT NULL,
  `photo` varchar(255) NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `photo`, `product_id`, `created_at`, `updated_at`) VALUES
(13, 'products/01JT8HY5W7HFC38FNH51NK192W.jpg', 6, '2025-05-02 06:10:56', '2025-05-02 06:10:56'),
(14, 'products/01JT8HY5WB5BSNQ17DG5R4220W.jpg', 6, '2025-05-02 06:10:56', '2025-05-02 06:10:56'),
(15, 'products/01JT8HY5WHNJRQJQPSXDY2AK7Q.jpg', 6, '2025-05-02 06:10:56', '2025-05-02 06:10:56'),
(16, 'products/01JT8SFH4KXSFJFYXCDV9EWCV5.jpg', 7, '2025-05-02 08:22:44', '2025-05-02 08:22:44'),
(17, 'products/01JT8SFH4R0DGC2X9VDDW11S5H.jpg', 7, '2025-05-02 08:22:44', '2025-05-02 08:22:44'),
(18, 'products/01JT8SFH4XPWWM3F8VXJPV5N6W.jpg', 7, '2025-05-02 08:22:44', '2025-05-02 08:22:44'),
(19, 'products/01JT8SFH52E5R0G13B2021X6KY.jpg', 7, '2025-05-02 08:22:44', '2025-05-02 08:22:44'),
(20, 'products/01JTDJTA67X04JFCSR6FW5AK38.jpg', 8, '2025-05-04 05:02:30', '2025-05-04 05:02:30'),
(21, 'products/01JTDJTA6CD9X8J70DSV9F7AH7.jpg', 8, '2025-05-04 05:02:30', '2025-05-04 05:02:30'),
(22, 'products/01JTDJX97W4CF14NFATJ22DPKY.jpg', 9, '2025-05-04 05:04:07', '2025-05-04 05:04:07'),
(23, 'products/01JTDJX984GNWVDT4MBD790C5Z.jpg', 9, '2025-05-04 05:04:07', '2025-05-04 05:04:07'),
(24, 'products/01JTDJX98DYZK0B7Y9ECN2NBX2.jpg', 9, '2025-05-04 05:04:07', '2025-05-04 05:04:07'),
(25, 'products/01JTDXA2C7MBNG3GSF6K8Y0MYV.jpg', 10, '2025-05-04 08:05:52', '2025-05-04 08:05:52'),
(26, 'products/01JTDXA2CJTFTTZF5GTVENMYJ0.jpg', 10, '2025-05-04 08:05:52', '2025-05-04 08:05:52'),
(27, 'products/01JTDXA2CYET878XT696TWNEDP.jpg', 10, '2025-05-04 08:05:52', '2025-05-04 08:05:52'),
(28, 'products/01JTDXD223HA9D5ZDZ89YY5247.jpg', 11, '2025-05-04 08:07:30', '2025-05-04 08:07:30'),
(29, 'products/01JTDXD227FX1D15B2HBC5T0AW.jpg', 11, '2025-05-04 08:07:30', '2025-05-04 08:07:30'),
(30, 'products/01JTDXD22H6VVW0Z72BCEB847Y.jpg', 11, '2025-05-04 08:07:30', '2025-05-04 08:07:30'),
(31, 'products/01JTDYFC2NB9FC0BX92BT7R1Y1.jpg', 12, '2025-05-04 08:26:14', '2025-05-04 08:26:14'),
(32, 'products/01JTDYFC38TSMVGY7NXEC4AFYD.jpg', 12, '2025-05-04 08:26:14', '2025-05-04 08:26:14');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `price` bigint UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `is_ready` tinyint(1) NOT NULL,
  `sub_category_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `price`, `description`, `thumbnail`, `is_ready`, `sub_category_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(6, 'Abaya', 'abaya', 150000, 'sample', 'thumbnails/01JT8HY5VEG8D0MZQYCAG76YS0.jpg', 1, 3, NULL, '2025-05-02 06:10:56', '2025-05-02 06:10:56'),
(7, 'Arabian', 'arabian', 150000, 'Contoh deskripsi', 'thumbnails/01JT8SFH3SG5MZAKYY43D8EDV9.jpg', 1, 5, NULL, '2025-05-02 08:22:44', '2025-05-04 07:53:18'),
(8, 'Nura Scarf', 'nura-scarf', 150000, 'yy', 'thumbnails/01JTDJTA58PA2JM9MYR9ZVGMMM.jpg', 1, 7, NULL, '2025-05-04 05:02:30', '2025-05-04 07:53:39'),
(9, 'Nora Abaya', 'nora-abaya', 150000, 'asdasddasdasd', 'thumbnails/01JTDJX97KT7GC0G1H9FTHMQMP.jpg', 1, 6, NULL, '2025-05-04 05:04:07', '2025-05-04 07:53:56'),
(10, 'Lunar', 'lunar', 150000, 'fasdf asdsdf asdfadsfd s dsaf asdf asdf SADf asdfasdfaa', 'thumbnails/01JTDXA2BZ2E23TBYVG4N5Y2C9.jpg', 1, 10, NULL, '2025-05-04 08:05:52', '2025-05-04 08:05:52'),
(11, 'Lunar Sport', 'lunar-sport', 150000, 'asdas ASF ASF DS fdgsfdfhsghgfjg gjsgf jhsfghsfgh sgfh sgh ', 'thumbnails/01JTDXD21SZWR7A0QDE5B2WE5Y.jpg', 1, 9, NULL, '2025-05-04 08:07:30', '2025-05-04 08:07:30'),
(12, 'Yuna Abaya', 'yuna-abaya', 150000, 'asdasdasdasdasdasd asdas dasdasdasdasddas das dasasd', 'thumbnails/01JTDYFC1CMQNG7ETKCDH7T0GG.jpg', 1, 8, NULL, '2025-05-04 08:26:14', '2025-05-31 09:21:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `stock` bigint UNSIGNED NOT NULL,
  `weight` int UNSIGNED NOT NULL,
  `size` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `stock`, `weight`, `size`, `color`, `product_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(12, 12, 250, 'One Size', '#e6c482', 6, NULL, '2025-05-02 06:10:56', '2025-05-31 09:28:29'),
(13, 12, 250, 'One Size', '#ff349c', 7, NULL, '2025-05-02 08:22:44', '2025-05-31 09:31:04'),
(14, 12, 250, 'One Size', '#ffc588', 7, NULL, '2025-05-02 08:22:44', '2025-05-31 09:31:04'),
(15, 12, 250, 'One Size', 'Navy', 8, NULL, '2025-05-04 05:02:30', '2025-05-31 09:20:02'),
(16, 12, 250, 'One Size', '#ffd398', 9, NULL, '2025-05-04 05:04:07', '2025-05-31 09:32:19'),
(17, 12, 250, 'One Size', 'Pink', 10, NULL, '2025-05-04 08:05:52', '2025-05-31 09:20:27'),
(18, 12, 250, 'One Size', '#d96e2a', 11, NULL, '2025-05-04 08:07:30', '2025-05-31 09:32:40'),
(19, 12, 250, 'One Size', 'White', 12, NULL, '2025-05-04 08:26:14', '2025-05-31 09:21:24');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('D9KsI3XelvaperpqX862gMGXyYs188hxs6Oj7N6P', NULL, '127.0.0.1', 'PostmanRuntime/7.44.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWTBmdm1Wdno2NnRSdEZyd3RDOTRsQWdkQ1RVYVVIaTJHYWR0UjdISCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1748878717),
('HUnRI6enWr3uDGu7yapCKDYyMWKPe6MqSjnmdy0g', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSmdNRFVUYWxPdHZ5d3VGMFdiaXhVUDBLeXNDUWRUZFV2cW1DRVZmOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9kdWN0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRXcWJrNi4zNXdCTE04TDlhNy5FcXZ1cHFhMUgubHVwZ2YzcGpoQWkuSHNieWlIUWRuS2NqeSI7fQ==', 1749464137),
('IdredSQsz2xMCsZeknacZfAEP6hQ8ef9KLP4TB2c', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 OPR/119.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidkFmUTFoeHJzNnFyYktIZ0xHczlwRGNMVzJrcURZWjJ5TFlCTGh1aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1749464012),
('LtIVKM7fycEBWMReVqNXiCmHnor9AN0B01V34P0D', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:138.0) Gecko/20100101 Firefox/138.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYXZCMW9kUFBRd1pNZ1AzMG9YeGNhaDNyeVZvWmtJNHpZUGluZjB5QyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9kdWN0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRXcWJrNi4zNXdCTE04TDlhNy5FcXZ1cHFhMUgubHVwZ2YzcGpoQWkuSHNieWlIUWRuS2NqeSI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1748709169);

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `name`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Gamis', 1, '2025-05-02 05:10:39', '2025-05-02 05:10:39'),
(2, 'Celana', 1, '2025-05-02 05:10:39', '2025-05-02 05:10:39'),
(3, 'Abaya', 2, '2025-05-02 06:07:26', '2025-05-04 07:51:17'),
(5, 'Scraf', 2, '2025-05-04 07:52:42', '2025-05-04 07:52:42'),
(6, 'Segitiga', 2, '2025-05-04 07:52:42', '2025-05-04 07:52:42'),
(7, 'Pashmina', 2, '2025-05-04 07:52:42', '2025-05-04 07:52:42'),
(8, 'Arabian', 2, '2025-05-04 07:52:42', '2025-05-04 07:52:42'),
(9, 'Sport', 2, '2025-05-04 07:54:52', '2025-05-04 07:54:52'),
(10, 'Instant', 2, '2025-05-04 07:54:53', '2025-05-04 07:54:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` bigint NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `email_verified_at`, `role`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Yusuf Hikam', 'hikamh2r@gmail.com', 0, NULL, 'admin', '$2y$12$Wqbk6.35wBLM8L9a7.Eqvupqa1H.lupgf3pjhAi.HsbyiHQdnKcjy', 'hQaeIkHMKhV4hDhPVnggnzgXssb0hEJXzxLTdcwSGkpVnx7mJCAsH4cA639f', NULL, NULL),
(4, 'Yuka', 'yoeshika.project@gmail.com', 81328677753, '2025-06-05 20:30:42', 'customer', '$2y$12$Wqbk6.35wBLM8L9a7.Eqvupqa1H.lupgf3pjhAi.HsbyiHQdnKcjy', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

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
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photos_product_id_foreign` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_sub_category_id_foreign` (`sub_category_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
