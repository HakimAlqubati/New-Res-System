-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2023 at 09:03 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newressys_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `manager_id`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Branch1', 'test address', 3, 1, NULL, NULL),
(2, 'Branch2', 'branch 2', 4, 1, NULL, NULL),
(3, 'Branch 3', 'aden - al kharaa', 3, 0, '2023-05-27 18:36:39', '2023-05-27 18:36:39');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `code`, `description`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Vegetablles', 'vegetablles', 'this is vegettable category', 1, '2023-05-20 19:52:36', '2023-05-20 19:52:36'),
(2, 'Frutes', 'frutes', 'this is vegettable frutes', 1, '2023-05-20 19:52:36', '2023-05-20 19:52:36'),
(3, 'Drinks', 'drinks', 'this is drkins', 1, NULL, NULL),
(4, 'sweet', 'sweets', 'this is sweets', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_05_18_235328_create_permission_tables', 2),
(6, '2023_05_19_004447_create_units_table', 3),
(7, '2023_05_19_004554_create_categories_table', 3),
(8, '2023_05_19_004946_create_products_table', 4),
(9, '2023_05_19_005414_create_branches_table', 5),
(10, '2023_05_19_005711_create_unit_prices_table', 6),
(13, '2016_06_01_000001_create_oauth_auth_codes_table', 9),
(14, '2016_06_01_000002_create_oauth_access_tokens_table', 9),
(15, '2016_06_01_000003_create_oauth_refresh_tokens_table', 9),
(16, '2016_06_01_000004_create_oauth_clients_table', 9),
(17, '2016_06_01_000005_create_oauth_personal_access_clients_table', 9),
(20, '2023_05_19_005908_create_orders_table', 10),
(21, '2023_05_23_144917_create_orders_details_table', 10),
(22, '2023_05_25_222751_add_owner_id_to_users_table', 11),
(23, '2023_05_26_000513_add_total_to_orders_table', 12),
(24, '2023_05_29_015810_create_notifications_table', 13),
(25, '2023_05_29_060444_add_active_to_orders_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(6, 'App\\Models\\User', 2),
(7, 'App\\Models\\User', 3),
(7, 'App\\Models\\User', 4),
(8, 'App\\Models\\User', 5),
(8, 'App\\Models\\User', 6),
(8, 'App\\Models\\User', 7),
(8, 'App\\Models\\User', 9);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('23a1e2f9-b3a1-411e-b158-06f83b8d3811', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', 1, '{\"actions\":[],\"body\":null,\"duration\":\"persistent\",\"icon\":null,\"iconColor\":\"secondary\",\"title\":\"Order no 76 Has been created\",\"view\":\"notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', NULL, '2023-05-29 11:01:26', '2023-05-29 11:01:26'),
('407760f1-f29c-439f-b285-f444d7e597f8', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', 1, '{\"actions\":[],\"body\":null,\"duration\":\"persistent\",\"icon\":null,\"iconColor\":\"secondary\",\"title\":\"Order no 75 Has been created\",\"view\":\"notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', NULL, '2023-05-29 01:08:34', '2023-05-29 01:08:34');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('037b19dc9ffee197eda1c6eabb3d04feede927107761b8c40b6f9b5e8682bcd9e156ebb4b9b8d8a4', 3, 1, 'MyApp', '[]', 0, '2023-05-25 21:47:02', '2023-05-25 21:47:02', '2024-05-26 00:47:02'),
('063c1e77705b9c3dddff192dabc0b4ab9516792fdf785cc58426b5738fe1a1e75c98587b3fd0e5fc', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:30:52', '2023-05-25 19:30:52', '2024-05-25 22:30:52'),
('0a4cc1052a791d2e3c8d6235cc5d70040d181d21ff72c6e6ec9831a761281d308d1646d8e668db80', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:21:32', '2023-05-25 20:21:32', '2024-05-25 23:21:32'),
('0fab6b59b82a052e6030674c474679ceef851a54604d13c03fdce50545b489f9666ff5f9b15970e5', 5, 1, 'MyApp', '[]', 0, '2023-05-25 21:45:14', '2023-05-25 21:45:14', '2024-05-26 00:45:14'),
('11d3099e3ad26b9f3ac13b603f0363423203ae1e30d13d6adfcf038b23f39c00116ba48063847ad5', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:47:24', '2023-05-25 19:47:24', '2024-05-25 22:47:24'),
('170425dea89520ba7b3b074cb557a445aef9bc7099c3c999773988c0a004499abf5d685a5be29836', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:49:53', '2023-05-25 19:49:53', '2024-05-25 22:49:53'),
('1a3dd2320bca746f5dd80b49afa41f707946cbaacc49f1e606ca2e2a21faf46bfe817e9a46a018c0', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:25:29', '2023-05-25 20:25:29', '2024-05-25 23:25:29'),
('1d69b6efc4cc9dc4ba2b8b6879bd5e5286b016ed1031fb0cb81fa07663f2cea080235be580682637', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:22:42', '2023-05-25 20:22:42', '2024-05-25 23:22:42'),
('2e61dbdc2e2a690fa4b34a737c4cc0b6bd6073eb69e5093f82bc6eef5becc0c1c65f78dd748d9757', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:27:13', '2023-05-25 20:27:13', '2024-05-25 23:27:13'),
('32232176e8d6ec5f296c37183e8d447071e6eff28cc4bc61c3700d3d9ecace49407e645903832dc0', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:26:45', '2023-05-25 20:26:45', '2024-05-25 23:26:45'),
('39c2b948a0010f4bd30e060407203d9b67ed770953709eb9d8cdce3a4d352047f2fa70b3eb7905c5', 2, 1, 'MyApp', '[]', 0, '2023-05-23 10:23:33', '2023-05-23 10:23:33', '2024-05-23 13:23:33'),
('3cf46810c8b0df4aa9a9df9b25aae914c14270f5360168bbeb61c86443ee977b4a2492696f199ef9', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:46:38', '2023-05-25 19:46:38', '2024-05-25 22:46:38'),
('40359559d173c4b82c1797f159408dfa672e1b3f91cb80f6a5457908f0a80d6b1783b56bfce03009', 5, 1, 'MyApp', '[]', 0, '2023-05-29 11:57:50', '2023-05-29 11:57:50', '2024-05-29 14:57:50'),
('42048ce449becffc01b67748be7424c6d9a19e4359a428910ed8ba95eca42d4845d0d91dc65f6efb', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:23:31', '2023-05-25 20:23:31', '2024-05-25 23:23:31'),
('43a2a69baf7db2b42d6404a150ef764660151b2a6e8068370745bd94c0d221d62d0afe67652c77d3', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:47:09', '2023-05-25 19:47:10', '2024-05-25 22:47:09'),
('608d68db32fe733108da76b7f885cb05ffd9132313a8f67f6aa6df442e75c4eca7b3d77d7701bf79', 5, 1, 'MyApp', '[]', 0, '2023-05-25 22:09:01', '2023-05-25 22:09:01', '2024-05-26 01:09:01'),
('62ac3abdaa4fa14bee8838e8d1446c1cf15c52d2fea2be3a5b46d5416b3b0ad0c6a196c6b5bf3eb9', 3, 1, 'MyApp', '[]', 0, '2023-05-29 12:39:26', '2023-05-29 12:39:26', '2024-05-29 15:39:26'),
('631a28c040c4a92934ce9b84a8c56276941d6f739ac8c8fadb08eb3d8f406c29eb97014f7cf55d0e', 4, 1, 'MyApp', '[]', 0, '2023-05-25 21:45:06', '2023-05-25 21:45:06', '2024-05-26 00:45:06'),
('64da636cde109488490a02f420007975af9792426bcf325dbc623d2041edce915ab08ab21264ced8', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:23:53', '2023-05-25 20:23:53', '2024-05-25 23:23:53'),
('65f16b70c441c2059051ccf4e21b55c0f0c754471168d7962343a11150623f488ed729681a0ba59c', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:38:56', '2023-05-25 19:38:56', '2024-05-25 22:38:56'),
('6c5911377b40659727f52bb412ad0e4c4fe507c33855291ff3f24805da7eae2286ef82e6b804e8f6', 3, 1, 'MyApp', '[]', 0, '2023-05-29 11:59:37', '2023-05-29 11:59:37', '2024-05-29 14:59:37'),
('7395854be1491218bb52d7527cb2339007e23fb227373de61e72d00964b8df52a638541373c3f94e', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:25:12', '2023-05-25 20:25:12', '2024-05-25 23:25:12'),
('7926f5dce0edb55f5970ea2d923d86c44e4c3e397c2ab3f0f3d6baca78b1f3f0c4c6632df099875c', 6, 1, 'MyApp', '[]', 0, '2023-05-29 11:58:49', '2023-05-29 11:58:49', '2024-05-29 14:58:49'),
('7b02c4ad2ba99c396344b3ba6768dfb8b0f7ed105ad18c170107be172b63ab7475e790210ae7e506', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:28:53', '2023-05-25 20:28:53', '2024-05-25 23:28:53'),
('7d2e7ca9fc8419fb9754b392aaf12ae9b3c880f410899a578dfd0e02c9303bf264ca7c299d5b9fa7', 2, 1, 'LaravelAuthApp', '[]', 0, '2023-05-23 10:08:13', '2023-05-23 10:08:13', '2024-05-23 13:08:13'),
('83a7a1fc2263354abfbd6f5c0117816db7878602ccfddf2de6f9eaa013c58456d46761390b82d13e', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:50:14', '2023-05-25 19:50:14', '2024-05-25 22:50:14'),
('91038dd38ff6b6bc17ae6cf80212c605e881445103d29c93151c52ddb8e4528dc08fd78c100f188e', 7, 1, 'MyApp', '[]', 0, '2023-05-25 21:45:18', '2023-05-25 21:45:18', '2024-05-26 00:45:18'),
('914361127dc24d955252804c1b6d0af3569593e0c4feaddf96c1c7f049a0e3658cdb4f20f2474fd7', 9, 1, 'MyApp', '[]', 0, '2023-05-25 21:46:47', '2023-05-25 21:46:47', '2024-05-26 00:46:47'),
('91d138dea5bb9505830d0991e326507dc23283da5f6df597cf2829b3bcf90c0ff5d2cb9419516147', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:55:58', '2023-05-25 19:55:58', '2024-05-25 22:55:58'),
('9a362ee230af99525224f3b0d55845d9a4eb183ebbabea02a77a2fce0c20aaeb4c34a5c10d2fa177', 3, 1, 'MyApp', '[]', 0, '2023-05-25 21:44:46', '2023-05-25 21:44:46', '2024-05-26 00:44:46'),
('9a3db71e056d5468d00eab20a8bc9bdd818cb2ce4dfe242fbe4e01dd7f5391c6fe76b823ec46a711', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:59:48', '2023-05-25 19:59:48', '2024-05-25 22:59:48'),
('9e98cfebfbf22c25a43710bdac5df03aabdeac12bd84529765999faf300246a58d8d24b5ff55f080', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:29:42', '2023-05-25 20:29:42', '2024-05-25 23:29:42'),
('a2040b17bb93ed961f3f87660824a87aa06a857a8d03fbdbe1e29b28b9deb1317391f3022199aa62', 5, 1, 'MyApp', '[]', 0, '2023-05-25 22:09:32', '2023-05-25 22:09:32', '2024-05-26 01:09:32'),
('a2f1d160d455aa7885ddc3e68a22deb62dc7299d4ebc7e200c58d86a905e7e578f9c87ea5048e936', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:59:17', '2023-05-25 19:59:17', '2024-05-25 22:59:17'),
('ac8102379d1de8e1c0d43380468c9e929896e58e8cc7e9d2c83a4afefff1fb746dc47ac89f3ee245', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:22:50', '2023-05-25 20:22:50', '2024-05-25 23:22:50'),
('b059e7921d54677b8efa8bfde717fc09cc16416b3f6d82fa55c30f2216dca27568e2ab0fb5309375', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:56:16', '2023-05-25 19:56:16', '2024-05-25 22:56:16'),
('b67576c66542cfcca070f35051abd9fb9b38a587651528672325863e665ca99f0b0cb17aea1bd43f', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:40:04', '2023-05-25 19:40:04', '2024-05-25 22:40:04'),
('b7f2f67e7be405c2c8bbbec7781bf355c9f417f09c56e35585e992f44851f09bc0f5ec1fa6d840fb', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:40:37', '2023-05-25 19:40:37', '2024-05-25 22:40:37'),
('ca3e5e7eb83f1c60f927efa85186f4b22dd8830a48813be5d1395f1f3b47d07907d730957c4e32a8', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:27:35', '2023-05-25 20:27:35', '2024-05-25 23:27:35'),
('ccaec27017d461bb78ca9375a75ac8daf33e48e55bc9973edc97c0ffa936481df471b58a14493e2b', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:27:45', '2023-05-25 20:27:45', '2024-05-25 23:27:45'),
('d3b253bd89924068c1843f0e486467f4fc14be73446299960842297374f107577731db5fa6783838', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:34:28', '2023-05-25 19:34:28', '2024-05-25 22:34:28'),
('d40a713b91f3d52b7771d0cefcf49d51804cfcb4791f4e52bcc5ebac82137c697d59b29f3e719510', 6, 1, 'MyApp', '[]', 0, '2023-05-29 11:39:08', '2023-05-29 11:39:08', '2024-05-29 14:39:08'),
('d4e0a119af933967084ef17f2685cc2813bd4e571e24d7954f41ef0d24b65e192bbbecf006c558cd', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:39:45', '2023-05-25 19:39:45', '2024-05-25 22:39:45'),
('d5df0db21c003d1118fe7773bfa148530939ab69019b4cc1772e6cf56f6351fa62afdb2a0d0018a7', 5, 1, 'MyApp', '[]', 0, '2023-05-29 12:33:26', '2023-05-29 12:33:26', '2024-05-29 15:33:26'),
('d7be0e9170974cd9f9309c5e9915adc64f02df6c140691e40a3c25f4546d83e74983d0a63c694021', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:58:15', '2023-05-25 19:58:15', '2024-05-25 22:58:15'),
('ded0115eab2e7e65289406019f668779a9a182c1b44c394ea4da8f0d190ddb9544cc275411888309', 3, 1, 'MyApp', '[]', 0, '2023-05-25 21:42:03', '2023-05-25 21:42:03', '2024-05-26 00:42:03'),
('e2089d30a43a3ed610fb820dee01bf8354a8c714cc46909138d87d3a4323cd3f536b01aad01c7943', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:29:56', '2023-05-25 19:29:56', '2024-05-25 22:29:56'),
('e29f94edb88c5ae872b7fd74970c821f55e30a878430ea78d13045fc039754ef5e2cf3df63e03de0', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:33:44', '2023-05-25 19:33:44', '2024-05-25 22:33:44'),
('e5c6bab2b3a8f8b3cb6638cbb98f21c2561459875e2a5a3114c3ff6e32de49118e0414de04c23334', 4, 1, 'MyApp', '[]', 0, '2023-05-25 21:44:55', '2023-05-25 21:44:55', '2024-05-26 00:44:55'),
('e8d757932f8b32e3aa395b3b21c95855dc7c1c809022fdbfcfaa076e8d5b136ab9f9a99dceff5118', 9, 1, 'MyApp', '[]', 0, '2023-05-25 21:45:27', '2023-05-25 21:45:27', '2024-05-26 00:45:27'),
('ecda600de9d2243ef2fd73644f725ec87f52036d0084374b0bdf9ce0fe8d2eaff709d4c58cb7e45f', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:57:25', '2023-05-25 19:57:25', '2024-05-25 22:57:25'),
('f35bee234c70823d71f5f9738f07c9571d3e5b84d06c424be974d3f3038a63ac37d456c1ec4de306', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:01:23', '2023-05-25 20:01:23', '2024-05-25 23:01:23'),
('f3f85aac345f303f3759e51d5954b105a5987ce8e773bcad359cd38192775c456812c450a594ed57', 2, 1, 'MyApp', '[]', 0, '2023-05-23 10:08:23', '2023-05-23 10:08:23', '2024-05-23 13:08:23'),
('f52e7ec6ef03c12aa604b045104f3c730b13f1e356066e090780ef8878d96918bf9a31d51477f9af', 2, 1, 'MyApp', '[]', 0, '2023-05-25 19:56:58', '2023-05-25 19:56:58', '2024-05-25 22:56:58'),
('f90883906fb6f03cd750a26e659ab2955eb786c86fcf4b3c7c8005bb68e88cded0800272de1249e3', 4, 1, 'MyApp', '[]', 0, '2023-05-29 11:39:50', '2023-05-29 11:39:50', '2024-05-29 14:39:50'),
('fc5f25361069405ac3ea75d1e2c1f8cb5fa946cf56fb3d4363ca28aabd9397ad3c827d22bb25fb14', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:26:26', '2023-05-25 20:26:26', '2024-05-25 23:26:26'),
('fd0b0b48f230dc00ac7b1871aa8c33ffebbf23d5941e8951a4ef245aeeb6d08c81c7ce14dfd74b9e', 2, 1, 'MyApp', '[]', 0, '2023-05-25 20:29:30', '2023-05-25 20:29:30', '2024-05-25 23:29:30'),
('fd89362fbbefc6e916b384f71afa1baffa54d79d241627eeb458a367c2e4bc983b89be1769546bfd', 5, 1, 'MyApp', '[]', 0, '2023-05-29 11:10:05', '2023-05-29 11:10:06', '2024-05-29 14:10:05'),
('ff769300231eb0049f714db2e9d8411552166bc5be06a2dd296777a00ae59c5fa45e15fa19aea085', 2, 1, 'MyApp', '[]', 0, '2023-05-28 23:51:46', '2023-05-28 23:51:46', '2024-05-29 02:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'b4ZrufZvEwW9qkfAoU5LE3Iy57XKNDbgMOChagq8', NULL, 'http://localhost', 1, 0, 0, '2023-05-23 09:38:32', '2023-05-23 09:38:32'),
(2, NULL, 'Laravel Password Grant Client', 'zz0Kset6ypJgjmF1Bs5WKOegr2XFaEMXWOGvcN8p', 'users', 'http://localhost', 0, 1, 0, '2023-05-23 09:38:32', '2023-05-23 09:38:32'),
(3, NULL, 'php artisan make:controller AuthController', 'wYeFJ65SlbyPXZGfQPTaZglaE7ndphpXVslIp2qW', NULL, '', 0, 0, 0, '2023-05-23 09:50:59', '2023-05-23 09:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-05-23 09:38:32', '2023-05-23 09:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recorded` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_quantity` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` int(11) NOT NULL,
  `total` double DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `status`, `recorded`, `notes`, `description`, `full_quantity`, `branch_id`, `total`, `active`, `created_at`, `updated_at`) VALUES
(91, 5, 'ordered', 0, '123', '321', 0, 1, 12000, 1, '2023-05-29 12:33:55', '2023-05-29 12:39:39'),
(92, 3, 'ordered', 0, '123', '321', 0, 1, 12000, 1, '2023-05-29 12:39:58', '2023-05-29 12:39:58'),
(93, 3, 'ordered', 0, '123', '321', 0, 1, 12000, 1, '2023-05-29 12:58:57', '2023-05-29 12:58:57'),
(94, 3, 'ordered', 0, '123', '321', 0, 1, 12000, 1, '2023-05-29 12:59:00', '2023-05-29 12:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders_details`
--

CREATE TABLE `orders_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `available_quantity` double NOT NULL,
  `price` double NOT NULL,
  `available_in_store` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders_details`
--

INSERT INTO `orders_details` (`id`, `order_id`, `product_id`, `unit_id`, `quantity`, `available_quantity`, `price`, `available_in_store`, `created_at`, `updated_at`) VALUES
(144, 91, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(145, 91, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(146, 91, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(147, 91, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(148, 91, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(149, 91, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(150, 91, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(151, 91, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(152, 92, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(153, 93, 2, 2, 6, 6, 12000, 0, NULL, NULL),
(154, 94, 2, 2, 6, 6, 12000, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_role', 'web', '2023-05-18 20:53:35', '2023-05-18 20:53:35'),
(2, 'view_any_role', 'web', '2023-05-18 20:53:35', '2023-05-18 20:53:35'),
(3, 'create_role', 'web', '2023-05-18 20:53:35', '2023-05-18 20:53:35'),
(4, 'update_role', 'web', '2023-05-18 20:53:35', '2023-05-18 20:53:35'),
(5, 'delete_role', 'web', '2023-05-18 20:53:35', '2023-05-18 20:53:35'),
(6, 'delete_any_role', 'web', '2023-05-18 20:53:35', '2023-05-18 20:53:35');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, 'MyApp', '87aa1bd4bd1186b8ae22347bfb4b1cc8a63d182e819a41d6ada2677d1ee83426', '[\"*\"]', NULL, NULL, '2023-05-23 09:55:47', '2023-05-23 09:55:47'),
(2, 'App\\Models\\User', 2, 'MyApp', 'ec596f35150fc590c72b42d8f52909ae2ef1d1b93c35e6104a67d75bae86c576', '[\"*\"]', NULL, NULL, '2023-05-23 09:57:00', '2023-05-23 09:57:00'),
(3, 'App\\Models\\User', 2, 'LaravelAuthApp', 'b9bc699f219a1cbae500507399470e9436770716d563018919e8d865de470d13', '[\"*\"]', NULL, NULL, '2023-05-23 10:07:08', '2023-05-23 10:07:08');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `code`, `description`, `category_id`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Tomato', 'tomtaoooooooooo', 'this is tomtao', 1, 1, '2023-05-20 20:06:25', '2023-05-22 20:21:35'),
(2, 'Potato', 'potato', 'this is potato', 1, 1, '2023-05-20 20:07:45', '2023-05-21 21:01:45'),
(3, 'Apple', 'apple', 'this is apple', 2, 1, '2023-05-20 20:07:45', '2023-05-21 21:01:45'),
(4, 'basboosa', 'basboosa', 'this is basboosa', 4, 1, NULL, NULL),
(5, 'lemon', 'lemon', 'this is lemon', 1, 1, NULL, NULL),
(6, 'juece', 'jueice', 'this is juece', 3, 1, NULL, NULL),
(7, 'milk', 'milk', 'this is milk', 3, 1, NULL, NULL),
(8, 'zzzzzzz', 'sss', 'www', 3, 1, '2023-05-28 20:25:53', '2023-05-28 20:25:53'),
(9, 'ssssss', 'sssssssssssss', 'ssssssss', 3, 1, '2023-05-28 20:38:31', '2023-05-28 20:38:31'),
(10, 'Mohammed product', '123', 'mohammed product', 4, 1, '2023-05-28 20:49:01', '2023-05-28 20:49:01'),
(11, 'Hi', 'i', '33', 3, 1, '2023-05-28 23:44:35', '2023-05-28 23:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2023-05-18 20:53:35', '2023-05-18 20:53:35'),
(3, 'Manager', 'web', '2023-05-18 21:05:33', '2023-05-18 21:05:33'),
(4, 'Customer', 'web', '2023-05-18 21:05:43', '2023-05-18 21:05:43'),
(5, 'Store', 'web', '2023-05-18 21:06:01', '2023-05-18 21:06:01'),
(6, 'Driver', 'web', '2023-05-18 21:06:10', '2023-05-18 21:06:10'),
(7, 'Branch', 'web', '2023-05-25 21:20:05', '2023-05-25 21:20:05'),
(8, 'User', 'web', '2023-05-25 21:20:18', '2023-05-25 21:20:18');

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
(6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `code`, `description`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Kilo gram', 'KG', 'this is kilo gram', 1, '2023-05-18 22:27:20', '2023-05-18 22:27:20'),
(2, 'Box', 'box', 'this is box unit', 1, '2023-05-20 19:42:52', '2023-05-20 19:42:52'),
(3, '0.5 KG', '0.5-kg', 'this is have of kilo gram', 1, '2023-05-20 19:48:57', '2023-05-20 19:48:57'),
(4, 'Test unit', 'test-unit', '123', 1, '2023-05-28 19:23:17', '2023-05-28 19:23:17');

-- --------------------------------------------------------

--
-- Table structure for table `unit_prices`
--

CREATE TABLE `unit_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit_prices`
--

INSERT INTO `unit_prices` (`id`, `product_id`, `unit_id`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 500, NULL, NULL),
(2, 1, 2, 600, NULL, NULL),
(3, 1, 3, 300, NULL, NULL),
(4, 2, 1, 1000, NULL, NULL),
(5, 2, 2, 2000, NULL, NULL),
(6, 2, 3, 3500, NULL, NULL),
(7, 1, 4, 1, '2023-05-28 19:30:24', '2023-05-28 19:30:24'),
(8, 8, 2, 2, '2023-05-28 20:25:53', '2023-05-28 20:25:53'),
(9, 9, 3, 3, '2023-05-28 20:38:31', '2023-05-28 20:38:31'),
(10, 10, 1, 700, '2023-05-28 20:49:01', '2023-05-28 20:49:01'),
(11, 10, 2, 800, '2023-05-28 20:49:01', '2023-05-28 20:49:01'),
(12, 10, 3, 900, '2023-05-28 20:49:01', '2023-05-28 20:49:01'),
(13, 11, 1, 1, '2023-05-28 23:45:42', '2023-05-28 23:45:42'),
(14, 11, 3, 150, '2023-05-28 23:45:53', '2023-05-28 23:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `owner_id`) VALUES
(1, 'admin', 'admin@admin.com', NULL, '$2y$10$OJtsV7pCU.JMNcio1Rs1EO.eG3XbJMIp0/cDN5femMmM2XG/tPeQq', NULL, '2023-05-14 20:25:54', '2023-05-29 01:18:48', NULL),
(2, 'hakeem', 'hakeem@admin.com', NULL, '$2y$10$OJtsV7pCU.JMNcio1Rs1EO.eG3XbJMIp0/cDN5femMmM2XG/tPeQq', NULL, '2023-05-14 20:25:54', '2023-05-14 20:25:54', NULL),
(3, 'branch1', 'branch1@admin.com', NULL, '$2y$10$OJtsV7pCU.JMNcio1Rs1EO.eG3XbJMIp0/cDN5femMmM2XG/tPeQq', NULL, NULL, NULL, NULL),
(4, 'branch2', 'branch2@admin.com', NULL, '$2y$10$OJtsV7pCU.JMNcio1Rs1EO.eG3XbJMIp0/cDN5femMmM2XG/tPeQq', NULL, NULL, NULL, NULL),
(5, 'user1 branch1', 'user1@branch1.com', NULL, '$2y$10$OJtsV7pCU.JMNcio1Rs1EO.eG3XbJMIp0/cDN5femMmM2XG/tPeQq', NULL, NULL, NULL, 3),
(6, 'user2 branch1', 'user2@branch1.com', NULL, '$2y$10$OJtsV7pCU.JMNcio1Rs1EO.eG3XbJMIp0/cDN5femMmM2XG/tPeQq', NULL, NULL, NULL, 3),
(7, 'user1 branch2', 'user1@branch2.com', NULL, '$2y$10$OJtsV7pCU.JMNcio1Rs1EO.eG3XbJMIp0/cDN5femMmM2XG/tPeQq', NULL, NULL, NULL, 4),
(9, 'user2 branch2', 'user2@branch2.com', NULL, '$2y$10$OJtsV7pCU.JMNcio1Rs1EO.eG3XbJMIp0/cDN5femMmM2XG/tPeQq', NULL, NULL, NULL, 4),
(10, 'hi', 'hi@dd.com', NULL, '123', NULL, '2023-05-29 01:23:07', '2023-05-29 01:23:07', NULL),
(11, 'hi', 'hi@adm.om', NULL, '123', NULL, '2023-05-29 01:25:56', '2023-05-29 01:25:56', NULL),
(12, 'HO', 'ho@d.c', NULL, '123', NULL, '2023-05-29 01:28:30', '2023-05-29 01:28:30', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_details`
--
ALTER TABLE `orders_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_details_order_id_foreign` (`order_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

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
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit_prices`
--
ALTER TABLE `unit_prices`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `unit_prices`
--
ALTER TABLE `unit_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `orders_details`
--
ALTER TABLE `orders_details`
  ADD CONSTRAINT `orders_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

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
