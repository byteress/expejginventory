# ************************************************************
# Sequel Ace SQL dump
# Version 20066
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 8.3.0)
# Database: salesandinventory
# Generation Time: 2024-06-08 00:50:41 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table branches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `branches`;

CREATE TABLE `branches` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;

INSERT INTO `branches` (`id`, `name`, `address`, `phone`, `description`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	('50137629-5a82-430e-9c11-a4a92a0a0d20','Candon','Mark Foster','Buffy Allen','Eos ut quis ex quam ','2024-06-06 22:45:31','2024-06-06 22:45:31',NULL),
	('7fc739c5-01d5-495e-96ea-50edfca66a60','Sta Maria','Paula Lowery','Patience Clayton','Laborum culpa dolor','2024-06-06 22:45:39','2024-06-06 22:45:39',NULL);

/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `branch_id`, `created_at`, `updated_at`)
VALUES
	('493115a6-0fa2-469f-ad27-8bfe0ca8d6a3','Martena','Cash','bujuhy@mailinator.com','12324','Voluptatem eaque sed','50137629-5a82-430e-9c11-a4a92a0a0d20','2024-06-06 23:00:07','2024-06-06 23:00:07');

/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table line_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `line_items`;

CREATE TABLE `line_items` (
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `reservation_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `line_items` WRITE;
/*!40000 ALTER TABLE `line_items` DISABLE KEYS */;

INSERT INTO `line_items` (`product_id`, `order_id`, `title`, `price`, `quantity`, `reservation_id`, `created_at`, `updated_at`)
VALUES
	('5080f149-05d0-4193-b53a-d2906ee65530','bb2afad9-6e9d-46c4-b1fe-669718167da2','Hamilton Woodward Table',65600,1,'a0298653-e746-4f5d-9e5b-0585d05fbde5',NULL,NULL),
	('4cc33218-fd47-415f-a1e0-d98ccff3bc43','bb2afad9-6e9d-46c4-b1fe-669718167da2','Ulysses Winters Chair',300000,1,'e1469f26-fa58-460c-b61b-1508b53192e1',NULL,NULL),
	('9ff27e48-f440-484d-a02d-72deb49f0746','bb2afad9-6e9d-46c4-b1fe-669718167da2','Clare Vance Chair',45000,1,'6fba69e0-c915-42e4-a133-c9785c1411d2',NULL,NULL),
	('04583c9b-0cf3-485c-8590-31e4505949c0','bb2afad9-6e9d-46c4-b1fe-669718167da2','Talon Mitchell Chair',50000,1,'72679e6f-db64-406c-9253-48d5154523c3',NULL,NULL);

/*!40000 ALTER TABLE `line_items` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table media
# ------------------------------------------------------------

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`)
VALUES
	(1,'ProductManagement\\Models\\Product','b3377313-a6dd-42c1-99e2-a9af0a00ae52','5ec35d49-2ca5-49a2-843b-bec26cd2c6c1','featured','BSjXp4riU7b29FLyzl96Xja0N94VU4-metacHJvZDEuanBlZw==-','BSjXp4riU7b29FLyzl96Xja0N94VU4-metacHJvZDEuanBlZw==-.jpg','image/jpeg','public','public',120908,'[]','[]','[]','{\"media_library_original\": {\"urls\": [\"BSjXp4riU7b29FLyzl96Xja0N94VU4-metacHJvZDEuanBlZw==-___media_library_original_1024_1024.jpg\", \"BSjXp4riU7b29FLyzl96Xja0N94VU4-metacHJvZDEuanBlZw==-___media_library_original_856_856.jpg\", \"BSjXp4riU7b29FLyzl96Xja0N94VU4-metacHJvZDEuanBlZw==-___media_library_original_716_716.jpg\", \"BSjXp4riU7b29FLyzl96Xja0N94VU4-metacHJvZDEuanBlZw==-___media_library_original_599_599.jpg\", \"BSjXp4riU7b29FLyzl96Xja0N94VU4-metacHJvZDEuanBlZw==-___media_library_original_501_501.jpg\", \"BSjXp4riU7b29FLyzl96Xja0N94VU4-metacHJvZDEuanBlZw==-___media_library_original_419_419.jpg\", \"BSjXp4riU7b29FLyzl96Xja0N94VU4-metacHJvZDEuanBlZw==-___media_library_original_351_351.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTAyNCAxMDI0Ij4KCTxpbWFnZSB3aWR0aD0iMTAyNCIgaGVpZ2h0PSIxMDI0IiB4bGluazpocmVmPSJkYXRhOmltYWdlL2pwZWc7YmFzZTY0LC85ai80QUFRU2taSlJnQUJBUUVBWUFCZ0FBRC8vZ0E3UTFKRlFWUlBVam9nWjJRdGFuQmxaeUIyTVM0d0lDaDFjMmx1WnlCSlNrY2dTbEJGUnlCMk9EQXBMQ0J4ZFdGc2FYUjVJRDBnT1RBSy85c0FRd0FEQWdJREFnSURBd01EQkFNREJBVUlCUVVFQkFVS0J3Y0dDQXdLREF3TENnc0xEUTRTRUEwT0VRNExDeEFXRUJFVEZCVVZGUXdQRnhnV0ZCZ1NGQlVVLzlzQVF3RURCQVFGQkFVSkJRVUpGQTBMRFJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVUvOEFBRVFnQUlBQWdBd0VSQUFJUkFRTVJBZi9FQUI4QUFBRUZBUUVCQVFFQkFBQUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVRQUFJQkF3TUNCQU1GQlFRRUFBQUJmUUVDQXdBRUVRVVNJVEZCQmhOUllRY2ljUlF5Z1pHaENDTkNzY0VWVXRId0pETmljb0lKQ2hZWEdCa2FKU1luS0NrcU5EVTJOemc1T2tORVJVWkhTRWxLVTFSVlZsZFlXVnBqWkdWbVoyaHBhbk4wZFhaM2VIbDZnNFNGaG9lSWlZcVNrNVNWbHBlWW1acWlvNlNscHFlb3FhcXlzN1MxdHJlNHVickN3OFRGeHNmSXljclMwOVRWMXRmWTJkcmg0dVBrNWVibjZPbnE4Zkx6OVBYMjkvajUrdi9FQUI4QkFBTUJBUUVCQVFFQkFRRUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVSQUFJQkFnUUVBd1FIQlFRRUFBRUNkd0FCQWdNUkJBVWhNUVlTUVZFSFlYRVRJaktCQ0JSQ2thR3h3UWtqTTFMd0ZXSnkwUW9XSkRUaEpmRVhHQmthSmljb0tTbzFOamM0T1RwRFJFVkdSMGhKU2xOVVZWWlhXRmxhWTJSbFptZG9hV3B6ZEhWMmQzaDVlb0tEaElXR2g0aUppcEtUbEpXV2w1aVptcUtqcEtXbXA2aXBxckt6dExXMnQ3aTV1c0xEeE1YR3g4akp5dExUMU5YVzE5aloydUxqNU9YbTUranA2dkx6OVBYMjkvajUrdi9hQUF3REFRQUNFUU1SQUQ4QTdDeDBZcFp5WEJUY3FESnhYQlpIYTNZaWo4VzZkYTI1azhvNVhqcFdjbWthSk5senczOFVGMURWSTdHMGlJZHVsRUpPUk00cUpKOFhOVDFiUWRHTjZlUjZWdTQzTWxJNS93Q0hQeEN0OVQwZTV0bWRaWFpEV0taVW82bVd1b3dMQk5DYlF5TnVQT0s1S2taTjZIWkJwSXNlQVRCYitMQmN2Q0lWVVpHYTBvcHhXcGxYMVdoMW5qKy8vd0NFcjBhNnQwK2NDdDRPVjlUZ2ltbWZLL3cvOFVSZUd2RWNjSG5mdWkyRHpWcEhXMmZRR3RmRVBSN0swUjdhM1dWMlhrZ1VPeUJYTVN5MWViV29KSnJTRmhLZnVqRlEwTzUwWGdPdzF1YVNhSzlnS0kvOTRWckZXTTVIak9qL0FBenNCZUxMS1NYejFwTFFwNm5xZHY0UnNMTzFpSlVNTWQ2enZkajJPdzhHdHBscmZSSUlsQXlNakZVdHhNOUM4VFhscmF4TEphcUUrWHNLNnJJNTdzLy8yUT09Ij4KCTwvaW1hZ2U+Cjwvc3ZnPg==\"}}',1,'2024-06-06 22:47:38','2024-06-06 22:49:03'),
	(2,'ProductManagement\\Models\\Product','04583c9b-0cf3-485c-8590-31e4505949c0','24368e86-243b-4396-98b4-a11c2730269a','featured','uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-','uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-.jpg','image/jpeg','public','public',133234,'[]','[]','[]','{\"media_library_original\": {\"urls\": [\"uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-___media_library_original_1024_1024.jpg\", \"uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-___media_library_original_856_856.jpg\", \"uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-___media_library_original_716_716.jpg\", \"uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-___media_library_original_599_599.jpg\", \"uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-___media_library_original_501_501.jpg\", \"uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-___media_library_original_419_419.jpg\", \"uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-___media_library_original_351_351.jpg\", \"uYmbdbkEBzFngKOZiAk8GfN9OKTYaf-metacHJvZDIuanBlZw==-___media_library_original_293_293.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTAyNCAxMDI0Ij4KCTxpbWFnZSB3aWR0aD0iMTAyNCIgaGVpZ2h0PSIxMDI0IiB4bGluazpocmVmPSJkYXRhOmltYWdlL2pwZWc7YmFzZTY0LC85ai80QUFRU2taSlJnQUJBUUVBWUFCZ0FBRC8vZ0E3UTFKRlFWUlBVam9nWjJRdGFuQmxaeUIyTVM0d0lDaDFjMmx1WnlCSlNrY2dTbEJGUnlCMk9EQXBMQ0J4ZFdGc2FYUjVJRDBnT1RBSy85c0FRd0FEQWdJREFnSURBd01EQkFNREJBVUlCUVVFQkFVS0J3Y0dDQXdLREF3TENnc0xEUTRTRUEwT0VRNExDeEFXRUJFVEZCVVZGUXdQRnhnV0ZCZ1NGQlVVLzlzQVF3RURCQVFGQkFVSkJRVUpGQTBMRFJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVUvOEFBRVFnQUlBQWdBd0VSQUFJUkFRTVJBZi9FQUI4QUFBRUZBUUVCQVFFQkFBQUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVRQUFJQkF3TUNCQU1GQlFRRUFBQUJmUUVDQXdBRUVRVVNJVEZCQmhOUllRY2ljUlF5Z1pHaENDTkNzY0VWVXRId0pETmljb0lKQ2hZWEdCa2FKU1luS0NrcU5EVTJOemc1T2tORVJVWkhTRWxLVTFSVlZsZFlXVnBqWkdWbVoyaHBhbk4wZFhaM2VIbDZnNFNGaG9lSWlZcVNrNVNWbHBlWW1acWlvNlNscHFlb3FhcXlzN1MxdHJlNHVickN3OFRGeHNmSXljclMwOVRWMXRmWTJkcmg0dVBrNWVibjZPbnE4Zkx6OVBYMjkvajUrdi9FQUI4QkFBTUJBUUVCQVFFQkFRRUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVSQUFJQkFnUUVBd1FIQlFRRUFBRUNkd0FCQWdNUkJBVWhNUVlTUVZFSFlYRVRJaktCQ0JSQ2thR3h3UWtqTTFMd0ZXSnkwUW9XSkRUaEpmRVhHQmthSmljb0tTbzFOamM0T1RwRFJFVkdSMGhKU2xOVVZWWlhXRmxhWTJSbFptZG9hV3B6ZEhWMmQzaDVlb0tEaElXR2g0aUppcEtUbEpXV2w1aVptcUtqcEtXbXA2aXBxckt6dExXMnQ3aTV1c0xEeE1YR3g4akp5dExUMU5YVzE5aloydUxqNU9YbTUranA2dkx6OVBYMjkvajUrdi9hQUF3REFRQUNFUU1SQUQ4QThZaTByeFY0WTFKYmFlQm81RHp0SXJ6Mmp0dWFtbi9GdldiSzdlemtnWjNUZ2dDbFlvMGJMeDVQZmFsRUxtSmtFaHhnaW81Uzc2V09tMXVaRWlVaGVvelRJUFMvaW5KWjZ0NDJ0N3VGRldGaDF4V3JsZlV6U3NqaHZoMTRZMDZmeDVxVWw3RWpRc0R0TERpaERrbVIrTlBEVnBGck51YldKUWdsNDJqM3FXeW9qdkd1aFBKYXhxa2dpSlVZSnJHVHNhTFU0dSs4ZWFuZVNyOXFpY2hPNEZha3BDVCtPWmJTRVBDUExjL3haNXJQbVJweU02L3diNHgwaThTTmRWdVZFK2M4bXF1UzR0SFAvdEMrT1ZzdE1nT2xreUpqN3lWU1Z6TnV4N1hyWGgzUXRPUm9EYklTd3huRlo4MXREUkxxZURmRmY0YjNRdHBMblNYTzNxRkZMbHV6UlZHbFk4Rk9uYTdaM082YVJsWUgxcm9TU1JnM0pzOUcwWHhmYk5wU1dlcVc0dWR2ZHVhaHV3MHJuLy9aIj4KCTwvaW1hZ2U+Cjwvc3ZnPg==\"}}',1,'2024-06-06 22:47:59','2024-06-06 22:49:04'),
	(3,'ProductManagement\\Models\\Product','5080f149-05d0-4193-b53a-d2906ee65530','e783fba8-6828-4bdc-9767-36e13eb787f1','featured','M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-','M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-.jpg','image/jpeg','public','public',132737,'[]','[]','[]','{\"media_library_original\": {\"urls\": [\"M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-___media_library_original_1024_1024.jpg\", \"M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-___media_library_original_856_856.jpg\", \"M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-___media_library_original_716_716.jpg\", \"M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-___media_library_original_599_599.jpg\", \"M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-___media_library_original_501_501.jpg\", \"M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-___media_library_original_419_419.jpg\", \"M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-___media_library_original_351_351.jpg\", \"M1OtV9odUgYIH95vnSyNqCojBGZOF9-metacHJvZDMuanBlZw==-___media_library_original_293_293.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTAyNCAxMDI0Ij4KCTxpbWFnZSB3aWR0aD0iMTAyNCIgaGVpZ2h0PSIxMDI0IiB4bGluazpocmVmPSJkYXRhOmltYWdlL2pwZWc7YmFzZTY0LC85ai80QUFRU2taSlJnQUJBUUVBWUFCZ0FBRC8vZ0E3UTFKRlFWUlBVam9nWjJRdGFuQmxaeUIyTVM0d0lDaDFjMmx1WnlCSlNrY2dTbEJGUnlCMk9EQXBMQ0J4ZFdGc2FYUjVJRDBnT1RBSy85c0FRd0FEQWdJREFnSURBd01EQkFNREJBVUlCUVVFQkFVS0J3Y0dDQXdLREF3TENnc0xEUTRTRUEwT0VRNExDeEFXRUJFVEZCVVZGUXdQRnhnV0ZCZ1NGQlVVLzlzQVF3RURCQVFGQkFVSkJRVUpGQTBMRFJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVUvOEFBRVFnQUlBQWdBd0VSQUFJUkFRTVJBZi9FQUI4QUFBRUZBUUVCQVFFQkFBQUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVRQUFJQkF3TUNCQU1GQlFRRUFBQUJmUUVDQXdBRUVRVVNJVEZCQmhOUllRY2ljUlF5Z1pHaENDTkNzY0VWVXRId0pETmljb0lKQ2hZWEdCa2FKU1luS0NrcU5EVTJOemc1T2tORVJVWkhTRWxLVTFSVlZsZFlXVnBqWkdWbVoyaHBhbk4wZFhaM2VIbDZnNFNGaG9lSWlZcVNrNVNWbHBlWW1acWlvNlNscHFlb3FhcXlzN1MxdHJlNHVickN3OFRGeHNmSXljclMwOVRWMXRmWTJkcmg0dVBrNWVibjZPbnE4Zkx6OVBYMjkvajUrdi9FQUI4QkFBTUJBUUVCQVFFQkFRRUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVSQUFJQkFnUUVBd1FIQlFRRUFBRUNkd0FCQWdNUkJBVWhNUVlTUVZFSFlYRVRJaktCQ0JSQ2thR3h3UWtqTTFMd0ZXSnkwUW9XSkRUaEpmRVhHQmthSmljb0tTbzFOamM0T1RwRFJFVkdSMGhKU2xOVVZWWlhXRmxhWTJSbFptZG9hV3B6ZEhWMmQzaDVlb0tEaElXR2g0aUppcEtUbEpXV2w1aVptcUtqcEtXbXA2aXBxckt6dExXMnQ3aTV1c0xEeE1YR3g4akp5dExUMU5YVzE5aloydUxqNU9YbTUranA2dkx6OVBYMjkvajUrdi9hQUF3REFRQUNFUU1SQUQ4QTdQU1l2dFZsSkw1Z0pYdFhqODl6MWVWbzV6V2ZGcmVIN29EeXcyZTlPY2JvVUpPNHh2aVM0bGdqWlJpWDlLNXFjTGFuUk9WOWpsZmlaOFJyN1RJVVcySXdldUs3S2FUT1NvMmliU2RRMW1LU1NJU0FvZlExZzBqb1RJdGEwYS91SW1tdUpWWURuclRjcmdrWW45anRmaU4wdWdqcHdCbW92WXEycHozaTdRTHFXM2FNM1N2SWVtVFdsT1JFMWRHZjRZOFFhemJhNllyZ3VJaWZsMzhWY282RXFSM3ZpUTZ6RnBadVZaV2l4bmFweldLU0x1ZVRTK05wamVlVVBNaGx6aXRPUm9PYUxPMzBud0ZxM2llS0c5V2QySDkzTldySXpiZlEyZmpIb0UxNUlqNlRHSUhYdW94V3NJOXpHVXJiRGZoQjRaOFE2N2VSV3VxU005dG5CQlBhcG5GTFZGUmxjOTUxejlsL1I1bzRycU9GQStBU2NWbHpzcmxOL3dBUGVDTEx3dGFMR3FBbFJTM0hzZi9aIj4KCTwvaW1hZ2U+Cjwvc3ZnPg==\"}}',1,'2024-06-06 22:48:10','2024-06-06 22:49:05'),
	(4,'ProductManagement\\Models\\Product','cbcd1c39-474a-4eab-9a4b-8820c22a7f9f','7b75107a-5c6d-4528-b5f4-a30ae7fca725','featured','yrEabq7iKEorf0KtEkQD0jh7WTjL58-metacHJvZDQuanBlZw==-','yrEabq7iKEorf0KtEkQD0jh7WTjL58-metacHJvZDQuanBlZw==-.jpg','image/jpeg','public','public',100879,'[]','[]','[]','{\"media_library_original\": {\"urls\": [\"yrEabq7iKEorf0KtEkQD0jh7WTjL58-metacHJvZDQuanBlZw==-___media_library_original_1024_1024.jpg\", \"yrEabq7iKEorf0KtEkQD0jh7WTjL58-metacHJvZDQuanBlZw==-___media_library_original_856_856.jpg\", \"yrEabq7iKEorf0KtEkQD0jh7WTjL58-metacHJvZDQuanBlZw==-___media_library_original_716_716.jpg\", \"yrEabq7iKEorf0KtEkQD0jh7WTjL58-metacHJvZDQuanBlZw==-___media_library_original_599_599.jpg\", \"yrEabq7iKEorf0KtEkQD0jh7WTjL58-metacHJvZDQuanBlZw==-___media_library_original_501_501.jpg\", \"yrEabq7iKEorf0KtEkQD0jh7WTjL58-metacHJvZDQuanBlZw==-___media_library_original_419_419.jpg\", \"yrEabq7iKEorf0KtEkQD0jh7WTjL58-metacHJvZDQuanBlZw==-___media_library_original_351_351.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTAyNCAxMDI0Ij4KCTxpbWFnZSB3aWR0aD0iMTAyNCIgaGVpZ2h0PSIxMDI0IiB4bGluazpocmVmPSJkYXRhOmltYWdlL2pwZWc7YmFzZTY0LC85ai80QUFRU2taSlJnQUJBUUVBWUFCZ0FBRC8vZ0E3UTFKRlFWUlBVam9nWjJRdGFuQmxaeUIyTVM0d0lDaDFjMmx1WnlCSlNrY2dTbEJGUnlCMk9EQXBMQ0J4ZFdGc2FYUjVJRDBnT1RBSy85c0FRd0FEQWdJREFnSURBd01EQkFNREJBVUlCUVVFQkFVS0J3Y0dDQXdLREF3TENnc0xEUTRTRUEwT0VRNExDeEFXRUJFVEZCVVZGUXdQRnhnV0ZCZ1NGQlVVLzlzQVF3RURCQVFGQkFVSkJRVUpGQTBMRFJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVUvOEFBRVFnQUlBQWdBd0VSQUFJUkFRTVJBZi9FQUI4QUFBRUZBUUVCQVFFQkFBQUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVRQUFJQkF3TUNCQU1GQlFRRUFBQUJmUUVDQXdBRUVRVVNJVEZCQmhOUllRY2ljUlF5Z1pHaENDTkNzY0VWVXRId0pETmljb0lKQ2hZWEdCa2FKU1luS0NrcU5EVTJOemc1T2tORVJVWkhTRWxLVTFSVlZsZFlXVnBqWkdWbVoyaHBhbk4wZFhaM2VIbDZnNFNGaG9lSWlZcVNrNVNWbHBlWW1acWlvNlNscHFlb3FhcXlzN1MxdHJlNHVickN3OFRGeHNmSXljclMwOVRWMXRmWTJkcmg0dVBrNWVibjZPbnE4Zkx6OVBYMjkvajUrdi9FQUI4QkFBTUJBUUVCQVFFQkFRRUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVSQUFJQkFnUUVBd1FIQlFRRUFBRUNkd0FCQWdNUkJBVWhNUVlTUVZFSFlYRVRJaktCQ0JSQ2thR3h3UWtqTTFMd0ZXSnkwUW9XSkRUaEpmRVhHQmthSmljb0tTbzFOamM0T1RwRFJFVkdSMGhKU2xOVVZWWlhXRmxhWTJSbFptZG9hV3B6ZEhWMmQzaDVlb0tEaElXR2g0aUppcEtUbEpXV2w1aVptcUtqcEtXbXA2aXBxckt6dExXMnQ3aTV1c0xEeE1YR3g4akp5dExUMU5YVzE5aloydUxqNU9YbTUranA2dkx6OVBYMjkvajUrdi9hQUF3REFRQUNFUU1SQUQ4QTh1c1BMZ0M3emd0MHJsTnJtanIzaDJTNDBWcm9mZHhVdERUTjM0R2FlQ3pvM3lobXhSRWNqMzNVZkROcDRhdkxhNVE4eUxrbXRyR1I4MjJVTmk5c0RjZks2OUNheTVrYThqWll2dGZoR21tM0JEUkRyOUttVWxZT1ZvNjc0WlMyViswVWVucHRjTUMxS0FTUGR2RkVWdHFaMCswYVlMTUVBeG5tdWk2TTdIeEg0azhTMmo2TEFZNUNrbmZGZUtuTnpaOUJCUVVEbk5EOFFtL3ZCWXhPenZLZG9MVjBwTzVqVTVHdEQzTHdicGwzOExJVjFUVVpWRURqSTVycFdoNXpWMmIzaHo0a3hlTC9BQjVGTEhjRHlVSHJWUklrckkyTlErRGZnOHBzTUlJcm5VT3AwT2IyTXlINE5lRjlQbFdhMGlDeXFjZytsYVdGekVQeEYwZGRWMFNLeVptYU5PMVNsWWZOYzhXdTRtOExYSWJUMWFKaHdXQnE3MklhdWYvWiI+Cgk8L2ltYWdlPgo8L3N2Zz4=\"}}',1,'2024-06-06 22:48:23','2024-06-06 22:49:06'),
	(5,'ProductManagement\\Models\\Product','9ff27e48-f440-484d-a02d-72deb49f0746','1dc63501-e996-47f9-abae-3c5ad48dc185','featured','xRFMMOruMtU2CRwvDd66GzmNFHqDqf-metacHJvZDUuanBlZw==-','xRFMMOruMtU2CRwvDd66GzmNFHqDqf-metacHJvZDUuanBlZw==-.jpg','image/jpeg','public','public',107170,'[]','[]','[]','{\"media_library_original\": {\"urls\": [\"xRFMMOruMtU2CRwvDd66GzmNFHqDqf-metacHJvZDUuanBlZw==-___media_library_original_1024_1024.jpg\", \"xRFMMOruMtU2CRwvDd66GzmNFHqDqf-metacHJvZDUuanBlZw==-___media_library_original_856_856.jpg\", \"xRFMMOruMtU2CRwvDd66GzmNFHqDqf-metacHJvZDUuanBlZw==-___media_library_original_716_716.jpg\", \"xRFMMOruMtU2CRwvDd66GzmNFHqDqf-metacHJvZDUuanBlZw==-___media_library_original_599_599.jpg\", \"xRFMMOruMtU2CRwvDd66GzmNFHqDqf-metacHJvZDUuanBlZw==-___media_library_original_501_501.jpg\", \"xRFMMOruMtU2CRwvDd66GzmNFHqDqf-metacHJvZDUuanBlZw==-___media_library_original_419_419.jpg\", \"xRFMMOruMtU2CRwvDd66GzmNFHqDqf-metacHJvZDUuanBlZw==-___media_library_original_351_351.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTAyNCAxMDI0Ij4KCTxpbWFnZSB3aWR0aD0iMTAyNCIgaGVpZ2h0PSIxMDI0IiB4bGluazpocmVmPSJkYXRhOmltYWdlL2pwZWc7YmFzZTY0LC85ai80QUFRU2taSlJnQUJBUUVBWUFCZ0FBRC8vZ0E3UTFKRlFWUlBVam9nWjJRdGFuQmxaeUIyTVM0d0lDaDFjMmx1WnlCSlNrY2dTbEJGUnlCMk9EQXBMQ0J4ZFdGc2FYUjVJRDBnT1RBSy85c0FRd0FEQWdJREFnSURBd01EQkFNREJBVUlCUVVFQkFVS0J3Y0dDQXdLREF3TENnc0xEUTRTRUEwT0VRNExDeEFXRUJFVEZCVVZGUXdQRnhnV0ZCZ1NGQlVVLzlzQVF3RURCQVFGQkFVSkJRVUpGQTBMRFJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVUvOEFBRVFnQUlBQWdBd0VSQUFJUkFRTVJBZi9FQUI4QUFBRUZBUUVCQVFFQkFBQUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVRQUFJQkF3TUNCQU1GQlFRRUFBQUJmUUVDQXdBRUVRVVNJVEZCQmhOUllRY2ljUlF5Z1pHaENDTkNzY0VWVXRId0pETmljb0lKQ2hZWEdCa2FKU1luS0NrcU5EVTJOemc1T2tORVJVWkhTRWxLVTFSVlZsZFlXVnBqWkdWbVoyaHBhbk4wZFhaM2VIbDZnNFNGaG9lSWlZcVNrNVNWbHBlWW1acWlvNlNscHFlb3FhcXlzN1MxdHJlNHVickN3OFRGeHNmSXljclMwOVRWMXRmWTJkcmg0dVBrNWVibjZPbnE4Zkx6OVBYMjkvajUrdi9FQUI4QkFBTUJBUUVCQVFFQkFRRUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVSQUFJQkFnUUVBd1FIQlFRRUFBRUNkd0FCQWdNUkJBVWhNUVlTUVZFSFlYRVRJaktCQ0JSQ2thR3h3UWtqTTFMd0ZXSnkwUW9XSkRUaEpmRVhHQmthSmljb0tTbzFOamM0T1RwRFJFVkdSMGhKU2xOVVZWWlhXRmxhWTJSbFptZG9hV3B6ZEhWMmQzaDVlb0tEaElXR2g0aUppcEtUbEpXV2w1aVptcUtqcEtXbXA2aXBxckt6dExXMnQ3aTV1c0xEeE1YR3g4akp5dExUMU5YVzE5aloydUxqNU9YbTUranA2dkx6OVBYMjkvajUrdi9hQUF3REFRQUNFUU1SQUQ4QTg5MGF3c2hISGV4U2huSDhJcjVaNHJsbFpuMnNjczlyRG1UUFNmaDM0c2t0YnlYekJoQU9NMTZEcXFVT2FKNHNzUEtuVjVKRlQ0cWVKRjE2ekt3T055bXZKcFl1WFBhU1BYcTRHS3A4MFdhSHcrK01seDRlMFdDd2xqSlZmNHEraXB0T056NWVwRnhsWThNc05KdnREaURDVWdaNk5YblR3MFp1NTc5TE1LbEpXUjFtazMxNUZFOG9PL0s4aFJWeG9xbXJJNWF1SmxXbGRtVExmbTRuSWRaQVNlZUt6NUlwM3NWN1diVnJuZVd0bHBsdm9rTWtuK3RJNXpXem00cjNUT0ZGVkplOHphOFYzWGgrT0c4aE5vQkxHRHR4VzhIZVZqeXB6YVEvNEdYZWc2OHQxQmUyNFJsenREZDYzbkJKRVVhcmxLekc2N2Q2RlorSW5nU3dVeHEyQzJLK1pxNGljSjJTUHM4UGhJVktkMnhmRTl6bzkyOXJiMmNRR1J5QjJyM01QYXBDNTg5aVU2TTNGTXpmRWxnbXBUR1pJanZmN3dxMXZjNW5GTTFmQm5ocExLUkpJWXZMYzljVmJrMlNxY1ZxWHZFZWwydHZPenlSWmNqazF4U2hHVDFSNkVLczRxeVp5bGxaTXQ4MHlSWngwelc4WktLc2pubW5OM2tmLzlrPSI+Cgk8L2ltYWdlPgo8L3N2Zz4=\"}}',1,'2024-06-06 22:48:37','2024-06-06 22:49:06'),
	(6,'ProductManagement\\Models\\Product','4cc33218-fd47-415f-a1e0-d98ccff3bc43','8ffee60b-260d-4aa2-bf50-16544deebfca','featured','vBtfVtffA9w37Y9Z3VUDo8QrmlqTbZ-metacHJvZDYuanBlZw==-','vBtfVtffA9w37Y9Z3VUDo8QrmlqTbZ-metacHJvZDYuanBlZw==-.jpg','image/jpeg','public','public',84720,'[]','[]','[]','{\"media_library_original\": {\"urls\": [\"vBtfVtffA9w37Y9Z3VUDo8QrmlqTbZ-metacHJvZDYuanBlZw==-___media_library_original_1024_1024.jpg\", \"vBtfVtffA9w37Y9Z3VUDo8QrmlqTbZ-metacHJvZDYuanBlZw==-___media_library_original_856_856.jpg\", \"vBtfVtffA9w37Y9Z3VUDo8QrmlqTbZ-metacHJvZDYuanBlZw==-___media_library_original_716_716.jpg\", \"vBtfVtffA9w37Y9Z3VUDo8QrmlqTbZ-metacHJvZDYuanBlZw==-___media_library_original_599_599.jpg\", \"vBtfVtffA9w37Y9Z3VUDo8QrmlqTbZ-metacHJvZDYuanBlZw==-___media_library_original_501_501.jpg\", \"vBtfVtffA9w37Y9Z3VUDo8QrmlqTbZ-metacHJvZDYuanBlZw==-___media_library_original_419_419.jpg\"], \"base64svg\": \"data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgMTAyNCAxMDI0Ij4KCTxpbWFnZSB3aWR0aD0iMTAyNCIgaGVpZ2h0PSIxMDI0IiB4bGluazpocmVmPSJkYXRhOmltYWdlL2pwZWc7YmFzZTY0LC85ai80QUFRU2taSlJnQUJBUUVBWUFCZ0FBRC8vZ0E3UTFKRlFWUlBVam9nWjJRdGFuQmxaeUIyTVM0d0lDaDFjMmx1WnlCSlNrY2dTbEJGUnlCMk9EQXBMQ0J4ZFdGc2FYUjVJRDBnT1RBSy85c0FRd0FEQWdJREFnSURBd01EQkFNREJBVUlCUVVFQkFVS0J3Y0dDQXdLREF3TENnc0xEUTRTRUEwT0VRNExDeEFXRUJFVEZCVVZGUXdQRnhnV0ZCZ1NGQlVVLzlzQVF3RURCQVFGQkFVSkJRVUpGQTBMRFJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVUvOEFBRVFnQUlBQWdBd0VSQUFJUkFRTVJBZi9FQUI4QUFBRUZBUUVCQVFFQkFBQUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVRQUFJQkF3TUNCQU1GQlFRRUFBQUJmUUVDQXdBRUVRVVNJVEZCQmhOUllRY2ljUlF5Z1pHaENDTkNzY0VWVXRId0pETmljb0lKQ2hZWEdCa2FKU1luS0NrcU5EVTJOemc1T2tORVJVWkhTRWxLVTFSVlZsZFlXVnBqWkdWbVoyaHBhbk4wZFhaM2VIbDZnNFNGaG9lSWlZcVNrNVNWbHBlWW1acWlvNlNscHFlb3FhcXlzN1MxdHJlNHVickN3OFRGeHNmSXljclMwOVRWMXRmWTJkcmg0dVBrNWVibjZPbnE4Zkx6OVBYMjkvajUrdi9FQUI4QkFBTUJBUUVCQVFFQkFRRUFBQUFBQUFBQkFnTUVCUVlIQ0FrS0MvL0VBTFVSQUFJQkFnUUVBd1FIQlFRRUFBRUNkd0FCQWdNUkJBVWhNUVlTUVZFSFlYRVRJaktCQ0JSQ2thR3h3UWtqTTFMd0ZXSnkwUW9XSkRUaEpmRVhHQmthSmljb0tTbzFOamM0T1RwRFJFVkdSMGhKU2xOVVZWWlhXRmxhWTJSbFptZG9hV3B6ZEhWMmQzaDVlb0tEaElXR2g0aUppcEtUbEpXV2w1aVptcUtqcEtXbXA2aXBxckt6dExXMnQ3aTV1c0xEeE1YR3g4akp5dExUMU5YVzE5aloydUxqNU9YbTUranA2dkx6OVBYMjkvajUrdi9hQUF3REFRQUNFUU1SQUQ4QThRdFBDRjFZV01kMjY1aWJ1S3pVMDNZdHdhMU9kMTIxU2U0S2dqY2VNVkVxeWpMbFowUXc4cHg1a1pmL0FBcmpVZE12N1RVNUlTTGNzQ0d4VnFhYnNZT0RSN1hIUEpjYWRFSXhuQ2l1ZXJpYWRGKyt6YW5RblUxaWpuUEFQeE50TlEwUWFkcWNSSUhRNHJubHpSbGVKdkZSY2JNNEx4Wk9VOFF0TFpndENHeUJSSk9iVE5vVkZUaTRuWVh2eFdmVWREdE5LbXMvTDJZRy9GS1VaVTF6bUtsQ28rVzUwK21lSWJDdzBiS3lCNVNPbGZDWXg0akdZaXpWa2ZXWWVOSERVYjMxT1IrR2ttamFycFppa2pWTGtEN3hyOUtTaXo0ZDh5S1Y3b0VOdnJvODJVZVVXcmFuR045VGt4RTV4aG9YdkV0allTV3lSVzdKNWg0REN1K3JHbktQS2p4S0ZTdENmTXlGYlMzMExUUTByaVdVajFybCtxVUlxOWpzV094RTUyV3c3VFBoYnFWbGRCcmVUWXVlMWZQdXZZK3VWRzUyemZDMmJVYk1DU1g5OWo3MVFxN3VPVkdOck1wTDhHN3VKR1pwaTVIU3Irc3k3bVN3c0gwS2RsOE05UmU4SXVqdmhIVE5XOFZKcTF6TllTRVhleC8vMlE9PSI+Cgk8L2ltYWdlPgo8L3N2Zz4=\"}}',1,'2024-06-06 22:48:50','2024-06-06 22:49:07');

/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2014_10_12_000000_create_users_table',1),
	(2,'2014_10_12_100000_create_password_reset_tokens_table',1),
	(3,'2019_08_19_000000_create_failed_jobs_table',1),
	(4,'2019_12_14_000001_create_personal_access_tokens_table',1),
	(5,'2024_05_04_175405_create_permission_tables',1),
	(6,'2024_05_06_021800_create_branches_table',1),
	(7,'2024_05_06_021806_create_suppliers_table',1),
	(8,'2024_05_18_184340_create_products_table',1),
	(9,'2024_05_19_033414_create_jobs_table',1),
	(10,'2024_05_19_091314_create_media_table',1),
	(11,'2024_05_25_065025_create_stored_events_table',1),
	(12,'2024_05_25_065026_create_snapshots_table',1),
	(13,'2024_05_25_065633_create_stock_management_stored_events_table',1),
	(14,'2024_05_26_071213_create_transfer_stored_events_table',1),
	(15,'2024_05_26_091214_create_stocks_table',1),
	(16,'2024_05_26_093114_create_receive_history_table',1),
	(17,'2024_05_27_004720_create_transfer_requests_table',1),
	(18,'2024_05_27_015438_create_request_details_table',1),
	(19,'2024_05_28_131219_create_product_requests_table',1),
	(20,'2024_06_05_010825_create_customers_table',1),
	(21,'2024_06_05_104310_create_order_stored_events_table',1),
	(22,'2024_06_06_094549_create_orders_table',1),
	(23,'2024_06_06_094739_create_line_items_table',1),
	(24,'2024_06_08_070954_create_payment_methods_table',2);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table model_has_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table model_has_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
VALUES
	(3,'App\\Models\\User','2c45c8a3-7395-477b-b877-2abb92faaf73'),
	(1,'App\\Models\\User','49c4c872-d328-4015-9615-c13c22c6159a'),
	(2,'App\\Models\\User','8860d0ce-1830-4aa0-828c-4b2c2c5c4f46'),
	(3,'App\\Models\\User','e322d4f3-e142-4d7f-817c-e5bf6cadc93b'),
	(4,'App\\Models\\User','ee9a5835-124f-4119-8aa5-1ef6015b2c7a');

/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order_stored_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_stored_events`;

CREATE TABLE `order_stored_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `aggregate_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aggregate_version` bigint unsigned DEFAULT NULL,
  `event_version` tinyint unsigned NOT NULL DEFAULT '1',
  `event_class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_properties` json NOT NULL,
  `meta_data` json NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `o_aggregate_uuid_aggregate_version_unique` (`aggregate_uuid`,`aggregate_version`),
  KEY `order_stored_events_event_class_index` (`event_class`),
  KEY `order_stored_events_aggregate_uuid_index` (`aggregate_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `order_stored_events` WRITE;
/*!40000 ALTER TABLE `order_stored_events` DISABLE KEYS */;

INSERT INTO `order_stored_events` (`id`, `aggregate_uuid`, `aggregate_version`, `event_version`, `event_class`, `event_properties`, `meta_data`, `created_at`)
VALUES
	(1,'b3377313-a6dd-42c1-99e2-a9af0a00ae52',1,1,'OrderContracts\\Events\\PriceSet','{\"productId\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"salePrice\": 51600, \"regularPrice\": 90000}','{\"created-at\": \"2024-06-06T14:47:39.005749Z\", \"stored-event-id\": 1, \"aggregate-root-uuid\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"aggregate-root-version\": 1}','2024-06-06 22:47:39'),
	(2,'04583c9b-0cf3-485c-8590-31e4505949c0',1,1,'OrderContracts\\Events\\PriceSet','{\"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"salePrice\": 62000, \"regularPrice\": 85000}','{\"created-at\": \"2024-06-06T14:47:59.038738Z\", \"stored-event-id\": 2, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 1}','2024-06-06 22:47:59'),
	(3,'5080f149-05d0-4193-b53a-d2906ee65530',1,1,'OrderContracts\\Events\\PriceSet','{\"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"salePrice\": 26800, \"regularPrice\": 65600}','{\"created-at\": \"2024-06-06T14:48:10.113293Z\", \"stored-event-id\": 3, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 1}','2024-06-06 22:48:10'),
	(4,'cbcd1c39-474a-4eab-9a4b-8820c22a7f9f',1,1,'OrderContracts\\Events\\PriceSet','{\"productId\": \"cbcd1c39-474a-4eab-9a4b-8820c22a7f9f\", \"salePrice\": 32500, \"regularPrice\": 83300}','{\"created-at\": \"2024-06-06T14:48:23.470487Z\", \"stored-event-id\": 4, \"aggregate-root-uuid\": \"cbcd1c39-474a-4eab-9a4b-8820c22a7f9f\", \"aggregate-root-version\": 1}','2024-06-06 22:48:23'),
	(5,'9ff27e48-f440-484d-a02d-72deb49f0746',1,1,'OrderContracts\\Events\\PriceSet','{\"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"salePrice\": 66900, \"regularPrice\": 45400}','{\"created-at\": \"2024-06-06T14:48:37.209694Z\", \"stored-event-id\": 5, \"aggregate-root-uuid\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"aggregate-root-version\": 1}','2024-06-06 22:48:37'),
	(6,'4cc33218-fd47-415f-a1e0-d98ccff3bc43',1,1,'OrderContracts\\Events\\PriceSet','{\"productId\": \"4cc33218-fd47-415f-a1e0-d98ccff3bc43\", \"salePrice\": 96500, \"regularPrice\": 120000}','{\"created-at\": \"2024-06-06T14:48:50.679976Z\", \"stored-event-id\": 6, \"aggregate-root-uuid\": \"4cc33218-fd47-415f-a1e0-d98ccff3bc43\", \"aggregate-root-version\": 1}','2024-06-06 22:48:50'),
	(7,'bb2afad9-6e9d-46c4-b1fe-669718167da2',1,1,'OrderContracts\\Events\\OrderPlaced','{\"items\": [{\"price\": 90000.0, \"title\": \"Kerry Frazier Sala Set\", \"quantity\": 1, \"productId\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"reservationId\": \"963ba47f-5ad9-40db-ae88-34d08af93e24\"}, {\"price\": 65600.0, \"title\": \"Hamilton Woodward Table\", \"quantity\": 1, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"9aec5531-68bf-4ee3-aafd-98bc8deecf03\"}, {\"price\": 300000.0, \"title\": \"Talon Mitchell Chair\", \"quantity\": 1, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"f18d45c7-cb0c-44c4-9fd3-5248135d8093\"}], \"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"customerId\": \"493115a6-0fa2-469f-ad27-8bfe0ca8d6a3\", \"assistantId\": \"2c45c8a3-7395-477b-b877-2abb92faaf73\"}','{\"created-at\": \"2024-06-06T15:00:07.637938Z\", \"stored-event-id\": 7, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 1}','2024-06-06 23:00:07'),
	(8,'bb2afad9-6e9d-46c4-b1fe-669718167da2',2,1,'OrderContracts\\Events\\ItemAdded','{\"price\": 120000, \"title\": \"Ulysses Winters Chair\", \"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"quantity\": 1, \"productId\": \"4cc33218-fd47-415f-a1e0-d98ccff3bc43\", \"reservationId\": \"e1469f26-fa58-460c-b61b-1508b53192e1\"}','{\"created-at\": \"2024-06-06T15:00:53.687831Z\", \"stored-event-id\": 8, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 2}','2024-06-06 23:00:53'),
	(9,'bb2afad9-6e9d-46c4-b1fe-669718167da2',3,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 300000, \"productId\": \"4cc33218-fd47-415f-a1e0-d98ccff3bc43\", \"itemAuthRequred\": true, \"orderAuthRequired\": true}','{\"created-at\": \"2024-06-06T15:01:12.641046Z\", \"stored-event-id\": 9, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 3}','2024-06-06 23:01:12'),
	(10,'bb2afad9-6e9d-46c4-b1fe-669718167da2',4,1,'OrderContracts\\Events\\OrderAuthorized','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\"}','{\"created-at\": \"2024-06-06T15:01:44.238832Z\", \"stored-event-id\": 10, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 4}','2024-06-06 23:01:44'),
	(11,'bb2afad9-6e9d-46c4-b1fe-669718167da2',5,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"newQuantity\": 2, \"reservationId\": \"990114df-8ee7-41b5-8120-f1ed64e160b5\"}','{\"created-at\": \"2024-06-06T15:57:21.792628Z\", \"stored-event-id\": 11, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 5}','2024-06-06 23:57:21'),
	(12,'bb2afad9-6e9d-46c4-b1fe-669718167da2',6,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"newQuantity\": 2, \"reservationId\": \"dfdbd476-9a93-4602-b97d-03b543a68e7a\"}','{\"created-at\": \"2024-06-06T15:57:53.976311Z\", \"stored-event-id\": 12, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 6}','2024-06-06 23:57:53'),
	(13,'bb2afad9-6e9d-46c4-b1fe-669718167da2',7,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 2, \"reservationId\": \"224f0313-2cec-4c64-b21b-4c39a63bec54\"}','{\"created-at\": \"2024-06-06T15:58:07.188085Z\", \"stored-event-id\": 13, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 7}','2024-06-06 23:58:07'),
	(14,'bb2afad9-6e9d-46c4-b1fe-669718167da2',8,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 3, \"reservationId\": \"301f05ef-4476-4ce4-ae7c-8dc20f0a57a2\"}','{\"created-at\": \"2024-06-06T15:58:09.990475Z\", \"stored-event-id\": 14, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 8}','2024-06-06 23:58:09'),
	(15,'bb2afad9-6e9d-46c4-b1fe-669718167da2',9,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 4, \"reservationId\": \"1e741c60-0f57-4856-9ca5-2c5637153d78\"}','{\"created-at\": \"2024-06-06T15:58:10.668352Z\", \"stored-event-id\": 15, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 9}','2024-06-06 23:58:10'),
	(16,'bb2afad9-6e9d-46c4-b1fe-669718167da2',10,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 5, \"reservationId\": \"db102cad-3fc1-4148-abe2-b11c2874f3d8\"}','{\"created-at\": \"2024-06-06T15:58:11.317000Z\", \"stored-event-id\": 16, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 10}','2024-06-06 23:58:11'),
	(17,'bb2afad9-6e9d-46c4-b1fe-669718167da2',11,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 6, \"reservationId\": \"928a5d17-9fee-4e11-b74e-433a2a6ea6c4\"}','{\"created-at\": \"2024-06-06T15:58:11.971136Z\", \"stored-event-id\": 17, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 11}','2024-06-06 23:58:11'),
	(18,'bb2afad9-6e9d-46c4-b1fe-669718167da2',12,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 7, \"reservationId\": \"a5637df8-22a6-422b-a93b-9997e2e0f282\"}','{\"created-at\": \"2024-06-06T15:58:12.550968Z\", \"stored-event-id\": 18, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 12}','2024-06-06 23:58:12'),
	(19,'bb2afad9-6e9d-46c4-b1fe-669718167da2',13,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 8, \"reservationId\": \"402f0c8e-f6bb-4334-b464-b51867f4a11f\"}','{\"created-at\": \"2024-06-06T15:58:13.157167Z\", \"stored-event-id\": 19, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 13}','2024-06-06 23:58:13'),
	(20,'bb2afad9-6e9d-46c4-b1fe-669718167da2',14,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 9, \"reservationId\": \"50dd5a07-cb84-4e3c-96f4-6df301551fd3\"}','{\"created-at\": \"2024-06-06T15:58:13.784299Z\", \"stored-event-id\": 20, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 14}','2024-06-06 23:58:13'),
	(21,'bb2afad9-6e9d-46c4-b1fe-669718167da2',15,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 10, \"reservationId\": \"c08ece73-8641-4122-a737-f4cdfa57341e\"}','{\"created-at\": \"2024-06-06T15:58:19.537039Z\", \"stored-event-id\": 21, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 15}','2024-06-06 23:58:19'),
	(22,'bb2afad9-6e9d-46c4-b1fe-669718167da2',16,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 9, \"reservationId\": \"3ecb5cca-6c0e-494c-94dc-992b324c8e67\"}','{\"created-at\": \"2024-06-06T15:59:08.462772Z\", \"stored-event-id\": 22, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 16}','2024-06-06 23:59:08'),
	(23,'bb2afad9-6e9d-46c4-b1fe-669718167da2',17,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 8, \"reservationId\": \"59af74d8-4168-48e0-aea2-aca093e1befb\"}','{\"created-at\": \"2024-06-06T15:59:09.999845Z\", \"stored-event-id\": 23, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 17}','2024-06-06 23:59:10'),
	(24,'bb2afad9-6e9d-46c4-b1fe-669718167da2',18,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 7, \"reservationId\": \"6e64d142-c82b-4f41-8eb9-96597fb1fb9a\"}','{\"created-at\": \"2024-06-06T15:59:15.365140Z\", \"stored-event-id\": 24, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 18}','2024-06-06 23:59:15'),
	(25,'bb2afad9-6e9d-46c4-b1fe-669718167da2',19,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 6, \"reservationId\": \"45e802df-84fc-4a9e-9d2c-408f57d62743\"}','{\"created-at\": \"2024-06-06T15:59:15.909553Z\", \"stored-event-id\": 25, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 19}','2024-06-06 23:59:15'),
	(26,'bb2afad9-6e9d-46c4-b1fe-669718167da2',20,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 5, \"reservationId\": \"30ddb086-6b42-48c1-aca4-3d1fb6e0e652\"}','{\"created-at\": \"2024-06-06T15:59:16.260239Z\", \"stored-event-id\": 26, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 20}','2024-06-06 23:59:16'),
	(27,'bb2afad9-6e9d-46c4-b1fe-669718167da2',21,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 4, \"reservationId\": \"71511b52-3a84-4e24-b724-c1ca3222dd0b\"}','{\"created-at\": \"2024-06-06T15:59:16.740005Z\", \"stored-event-id\": 27, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 21}','2024-06-06 23:59:16'),
	(28,'bb2afad9-6e9d-46c4-b1fe-669718167da2',22,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 3, \"reservationId\": \"36740a10-2aa7-4b99-b1af-9d9876d2e02f\"}','{\"created-at\": \"2024-06-06T15:59:17.169031Z\", \"stored-event-id\": 28, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 22}','2024-06-06 23:59:17'),
	(29,'bb2afad9-6e9d-46c4-b1fe-669718167da2',23,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"newQuantity\": 2, \"reservationId\": \"0625ce05-c09c-4328-8f22-57ab2db869cd\"}','{\"created-at\": \"2024-06-06T15:59:17.576549Z\", \"stored-event-id\": 29, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 23}','2024-06-06 23:59:17'),
	(30,'bb2afad9-6e9d-46c4-b1fe-669718167da2',24,1,'OrderContracts\\Events\\ItemRemoved','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"authorizationRequired\": false}','{\"created-at\": \"2024-06-06T15:59:20.676851Z\", \"stored-event-id\": 30, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 24}','2024-06-06 23:59:20'),
	(31,'bb2afad9-6e9d-46c4-b1fe-669718167da2',25,1,'OrderContracts\\Events\\ItemRemoved','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"authorizationRequired\": false}','{\"created-at\": \"2024-06-06T16:00:08.738774Z\", \"stored-event-id\": 31, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 25}','2024-06-07 00:00:08'),
	(32,'bb2afad9-6e9d-46c4-b1fe-669718167da2',26,1,'OrderContracts\\Events\\ItemAdded','{\"price\": 45400, \"title\": \"Clare Vance Chair\", \"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"quantity\": 1, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"reservationId\": \"6fba69e0-c915-42e4-a133-c9785c1411d2\"}','{\"created-at\": \"2024-06-06T16:08:32.679547Z\", \"stored-event-id\": 32, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 26}','2024-06-07 00:08:32'),
	(33,'bb2afad9-6e9d-46c4-b1fe-669718167da2',27,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"newQuantity\": 3, \"reservationId\": \"0a7bf01a-2808-42c0-8d7c-d86b24167eef\"}','{\"created-at\": \"2024-06-07T07:28:39.739987Z\", \"stored-event-id\": 33, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 27}','2024-06-07 15:28:39'),
	(34,'bb2afad9-6e9d-46c4-b1fe-669718167da2',28,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"newQuantity\": 2, \"reservationId\": \"3783fd90-3d64-41db-8add-1256ce8b1a5d\"}','{\"created-at\": \"2024-06-07T07:28:41.651433Z\", \"stored-event-id\": 34, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 28}','2024-06-07 15:28:41'),
	(35,'bb2afad9-6e9d-46c4-b1fe-669718167da2',29,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 100000, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"itemAuthRequred\": false, \"orderAuthRequired\": false}','{\"created-at\": \"2024-06-07T07:29:04.108759Z\", \"stored-event-id\": 35, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 29}','2024-06-07 15:29:04'),
	(36,'bb2afad9-6e9d-46c4-b1fe-669718167da2',30,1,'OrderContracts\\Events\\ItemQuantityUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"newQuantity\": 1, \"reservationId\": \"a0298653-e746-4f5d-9e5b-0585d05fbde5\"}','{\"created-at\": \"2024-06-07T07:29:27.744486Z\", \"stored-event-id\": 36, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 30}','2024-06-07 15:29:27'),
	(37,'bb2afad9-6e9d-46c4-b1fe-669718167da2',31,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 10000, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"itemAuthRequred\": false, \"orderAuthRequired\": false}','{\"created-at\": \"2024-06-07T07:29:38.418517Z\", \"stored-event-id\": 37, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 31}','2024-06-07 15:29:38'),
	(38,'bb2afad9-6e9d-46c4-b1fe-669718167da2',32,1,'OrderContracts\\Events\\ItemAdded','{\"price\": 85000, \"title\": \"Talon Mitchell Chair\", \"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"quantity\": 1, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"72679e6f-db64-406c-9253-48d5154523c3\"}','{\"created-at\": \"2024-06-07T07:30:05.852703Z\", \"stored-event-id\": 38, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 32}','2024-06-07 15:30:05'),
	(39,'bb2afad9-6e9d-46c4-b1fe-669718167da2',33,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 50000, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"itemAuthRequred\": false, \"orderAuthRequired\": false}','{\"created-at\": \"2024-06-07T07:30:10.711058Z\", \"stored-event-id\": 39, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 33}','2024-06-07 15:30:10'),
	(40,'bb2afad9-6e9d-46c4-b1fe-669718167da2',34,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 50000, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"itemAuthRequred\": true, \"orderAuthRequired\": true}','{\"created-at\": \"2024-06-07T07:32:23.929980Z\", \"stored-event-id\": 40, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 34}','2024-06-07 15:32:23'),
	(41,'bb2afad9-6e9d-46c4-b1fe-669718167da2',35,1,'OrderContracts\\Events\\OrderAuthorized','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\"}','{\"created-at\": \"2024-06-07T23:07:35.303560Z\", \"stored-event-id\": 41, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 35}','2024-06-08 07:07:35'),
	(42,'bb2afad9-6e9d-46c4-b1fe-669718167da2',36,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 10000, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"itemAuthRequred\": true, \"orderAuthRequired\": true}','{\"created-at\": \"2024-06-08T00:46:13.823424Z\", \"stored-event-id\": 42, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 36}','2024-06-08 08:46:13'),
	(43,'bb2afad9-6e9d-46c4-b1fe-669718167da2',37,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 10500, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"itemAuthRequred\": true, \"orderAuthRequired\": true}','{\"created-at\": \"2024-06-08T00:46:21.192828Z\", \"stored-event-id\": 43, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 37}','2024-06-08 08:46:21'),
	(44,'bb2afad9-6e9d-46c4-b1fe-669718167da2',38,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 45000, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"itemAuthRequred\": false, \"orderAuthRequired\": false}','{\"created-at\": \"2024-06-08T00:46:52.092286Z\", \"stored-event-id\": 44, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 38}','2024-06-08 08:46:52'),
	(45,'bb2afad9-6e9d-46c4-b1fe-669718167da2',39,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 10000, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"itemAuthRequred\": true, \"orderAuthRequired\": true}','{\"created-at\": \"2024-06-08T00:46:56.157321Z\", \"stored-event-id\": 45, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 39}','2024-06-08 08:46:56'),
	(46,'bb2afad9-6e9d-46c4-b1fe-669718167da2',40,1,'OrderContracts\\Events\\ItemPriceUpdated','{\"orderId\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"newPrice\": 45000, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"itemAuthRequred\": false, \"orderAuthRequired\": false}','{\"created-at\": \"2024-06-08T00:47:00.328827Z\", \"stored-event-id\": 46, \"aggregate-root-uuid\": \"bb2afad9-6e9d-46c4-b1fe-669718167da2\", \"aggregate-root-version\": 40}','2024-06-08 08:47:00');

/*!40000 ALTER TABLE `order_stored_events` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assistant_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL,
  `requires_authorization` tinyint(1) NOT NULL DEFAULT '0',
  `placed_at` timestamp NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cashier` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;

INSERT INTO `orders` (`id`, `order_id`, `branch_id`, `customer_id`, `assistant_id`, `total`, `requires_authorization`, `placed_at`, `completed_at`, `receipt_number`, `cashier`)
VALUES
	(1,'bb2afad9-6e9d-46c4-b1fe-669718167da2','50137629-5a82-430e-9c11-a4a92a0a0d20','493115a6-0fa2-469f-ad27-8bfe0ca8d6a3','2c45c8a3-7395-477b-b877-2abb92faaf73',460600,0,'2024-06-06 15:00:07','2024-06-08 08:47:29','12345','49c4c872-d328-4015-9615-c13c22c6159a');

/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table password_reset_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table payment_methods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payment_methods`;

CREATE TABLE `payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `order_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;

INSERT INTO `payment_methods` (`id`, `method`, `reference`, `amount`, `order_id`, `user_id`, `created_at`, `updated_at`)
VALUES
	(1,'Cash','1211',4606,'bb2afad9-6e9d-46c4-b1fe-669718167da2','49c4c872-d328-4015-9615-c13c22c6159a',NULL,NULL);

/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table personal_access_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table product_requests
# ------------------------------------------------------------

DROP TABLE IF EXISTS `product_requests`;

CREATE TABLE `product_requests` (
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `incoming` int NOT NULL DEFAULT '0',
  `date_requested` timestamp NOT NULL,
  KEY `product_requests_product_id_receiver_index` (`product_id`,`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regular_price` int NOT NULL,
  `sale_price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` (`id`, `sku_code`, `sku_number`, `model`, `description`, `supplier_id`, `regular_price`, `sale_price`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	('04583c9b-0cf3-485c-8590-31e4505949c0','AJ','000000000002','Talon Mitchell','Chair','10ff5102-e820-4e8d-acd4-c5608252cb21',85000,62000,'2024-06-06 22:47:59','2024-06-06 22:49:03',NULL),
	('4cc33218-fd47-415f-a1e0-d98ccff3bc43','AJ','000000000006','Ulysses Winters','Chair','10ff5102-e820-4e8d-acd4-c5608252cb21',120000,96500,'2024-06-06 22:48:50','2024-06-06 22:49:06',NULL),
	('5080f149-05d0-4193-b53a-d2906ee65530','AJ','000000000003','Hamilton Woodward','Table','4267452d-2f1b-41f3-afc1-77ae3815ad7a',65600,26800,'2024-06-06 22:48:10','2024-06-06 22:49:04',NULL),
	('9ff27e48-f440-484d-a02d-72deb49f0746','AJ','000000000005','Clare Vance','Chair','10ff5102-e820-4e8d-acd4-c5608252cb21',45400,66900,'2024-06-06 22:48:37','2024-06-06 22:49:06',NULL),
	('b3377313-a6dd-42c1-99e2-a9af0a00ae52','AJ','000000000001','Kerry Frazier','Sala Set','5f04eee3-9218-4f23-8f10-c288a31035b7',90000,51600,'2024-06-06 22:47:38','2024-06-06 22:49:02',NULL),
	('cbcd1c39-474a-4eab-9a4b-8820c22a7f9f','AJ','000000000004','Illiana Ferguson','Foam','10ff5102-e820-4e8d-acd4-c5608252cb21',83300,32500,'2024-06-06 22:48:23','2024-06-06 22:49:05',NULL);

/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table receive_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `receive_history`;

CREATE TABLE `receive_history` (
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` timestamp NOT NULL,
  KEY `receive_history_product_id_branch_id_index` (`product_id`,`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `receive_history` WRITE;
/*!40000 ALTER TABLE `receive_history` DISABLE KEYS */;

INSERT INTO `receive_history` (`product_id`, `branch_id`, `quantity`, `user_id`, `date`)
VALUES
	('04583c9b-0cf3-485c-8590-31e4505949c0','50137629-5a82-430e-9c11-a4a92a0a0d20',10,'49c4c872-d328-4015-9615-c13c22c6159a','2024-06-06 22:51:50'),
	('4cc33218-fd47-415f-a1e0-d98ccff3bc43','50137629-5a82-430e-9c11-a4a92a0a0d20',15,'49c4c872-d328-4015-9615-c13c22c6159a','2024-06-06 22:51:50'),
	('5080f149-05d0-4193-b53a-d2906ee65530','50137629-5a82-430e-9c11-a4a92a0a0d20',20,'49c4c872-d328-4015-9615-c13c22c6159a','2024-06-06 22:51:50'),
	('9ff27e48-f440-484d-a02d-72deb49f0746','50137629-5a82-430e-9c11-a4a92a0a0d20',25,'49c4c872-d328-4015-9615-c13c22c6159a','2024-06-06 22:51:50'),
	('b3377313-a6dd-42c1-99e2-a9af0a00ae52','50137629-5a82-430e-9c11-a4a92a0a0d20',30,'49c4c872-d328-4015-9615-c13c22c6159a','2024-06-06 22:51:50'),
	('cbcd1c39-474a-4eab-9a4b-8820c22a7f9f','50137629-5a82-430e-9c11-a4a92a0a0d20',35,'49c4c872-d328-4015-9615-c13c22c6159a','2024-06-06 22:51:50');

/*!40000 ALTER TABLE `receive_history` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table request_details
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_details`;

CREATE TABLE `request_details` (
  `transfer_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json NOT NULL,
  PRIMARY KEY (`transfer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table role_has_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`)
VALUES
	(1,'admin','web','2024-06-06 22:45:11','2024-06-06 22:45:11'),
	(2,'inventory_head','web','2024-06-06 22:45:11','2024-06-06 22:45:11'),
	(3,'sales_rep','web','2024-06-06 22:45:11','2024-06-06 22:45:11'),
	(4,'cashier','web','2024-06-06 22:45:11','2024-06-06 22:45:11'),
	(5,'delivery','web','2024-06-06 22:45:11','2024-06-06 22:45:11');

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table snapshots
# ------------------------------------------------------------

DROP TABLE IF EXISTS `snapshots`;

CREATE TABLE `snapshots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `aggregate_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aggregate_version` bigint unsigned NOT NULL,
  `state` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `snapshots_aggregate_uuid_index` (`aggregate_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table stock_management_stored_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stock_management_stored_events`;

CREATE TABLE `stock_management_stored_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `aggregate_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aggregate_version` bigint unsigned DEFAULT NULL,
  `event_version` tinyint unsigned NOT NULL DEFAULT '1',
  `event_class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_properties` json NOT NULL,
  `meta_data` json NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sm_aggregate_uuid_aggregate_version_unique` (`aggregate_uuid`,`aggregate_version`),
  KEY `stock_management_stored_events_event_class_index` (`event_class`),
  KEY `stock_management_stored_events_aggregate_uuid_index` (`aggregate_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `stock_management_stored_events` WRITE;
/*!40000 ALTER TABLE `stock_management_stored_events` DISABLE KEYS */;

INSERT INTO `stock_management_stored_events` (`id`, `aggregate_uuid`, `aggregate_version`, `event_version`, `event_class`, `event_properties`, `meta_data`, `created_at`)
VALUES
	(1,'04583c9b-0cf3-485c-8590-31e4505949c0',1,1,'StockManagementContracts\\Events\\ProductReceived','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 10, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\"}','{\"created-at\": \"2024-06-06T14:51:50.212772Z\", \"stored-event-id\": 1, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 1}','2024-06-06 22:51:50'),
	(2,'4cc33218-fd47-415f-a1e0-d98ccff3bc43',1,1,'StockManagementContracts\\Events\\ProductReceived','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 15, \"productId\": \"4cc33218-fd47-415f-a1e0-d98ccff3bc43\"}','{\"created-at\": \"2024-06-06T14:51:50.222290Z\", \"stored-event-id\": 2, \"aggregate-root-uuid\": \"4cc33218-fd47-415f-a1e0-d98ccff3bc43\", \"aggregate-root-version\": 1}','2024-06-06 22:51:50'),
	(3,'5080f149-05d0-4193-b53a-d2906ee65530',1,1,'StockManagementContracts\\Events\\ProductReceived','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 20, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\"}','{\"created-at\": \"2024-06-06T14:51:50.241517Z\", \"stored-event-id\": 3, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 1}','2024-06-06 22:51:50'),
	(4,'9ff27e48-f440-484d-a02d-72deb49f0746',1,1,'StockManagementContracts\\Events\\ProductReceived','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 25, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\"}','{\"created-at\": \"2024-06-06T14:51:50.245608Z\", \"stored-event-id\": 4, \"aggregate-root-uuid\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"aggregate-root-version\": 1}','2024-06-06 22:51:50'),
	(5,'b3377313-a6dd-42c1-99e2-a9af0a00ae52',1,1,'StockManagementContracts\\Events\\ProductReceived','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 30, \"productId\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\"}','{\"created-at\": \"2024-06-06T14:51:50.250394Z\", \"stored-event-id\": 5, \"aggregate-root-uuid\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"aggregate-root-version\": 1}','2024-06-06 22:51:50'),
	(6,'cbcd1c39-474a-4eab-9a4b-8820c22a7f9f',1,1,'StockManagementContracts\\Events\\ProductReceived','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 35, \"productId\": \"cbcd1c39-474a-4eab-9a4b-8820c22a7f9f\"}','{\"created-at\": \"2024-06-06T14:51:50.254792Z\", \"stored-event-id\": 6, \"aggregate-root-uuid\": \"cbcd1c39-474a-4eab-9a4b-8820c22a7f9f\", \"aggregate-root-version\": 1}','2024-06-06 22:51:50'),
	(7,'04583c9b-0cf3-485c-8590-31e4505949c0',2,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"2c45c8a3-7395-477b-b877-2abb92faaf73\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"f18d45c7-cb0c-44c4-9fd3-5248135d8093\"}','{\"created-at\": \"2024-06-06T14:53:48.052314Z\", \"stored-event-id\": 7, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 2}','2024-06-06 22:53:48'),
	(8,'b3377313-a6dd-42c1-99e2-a9af0a00ae52',2,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"2c45c8a3-7395-477b-b877-2abb92faaf73\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"reservationId\": \"963ba47f-5ad9-40db-ae88-34d08af93e24\"}','{\"created-at\": \"2024-06-06T14:53:50.687586Z\", \"stored-event-id\": 8, \"aggregate-root-uuid\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"aggregate-root-version\": 2}','2024-06-06 22:53:50'),
	(9,'5080f149-05d0-4193-b53a-d2906ee65530',2,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"2c45c8a3-7395-477b-b877-2abb92faaf73\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"9aec5531-68bf-4ee3-aafd-98bc8deecf03\"}','{\"created-at\": \"2024-06-06T14:53:54.979936Z\", \"stored-event-id\": 9, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 2}','2024-06-06 22:53:54'),
	(10,'4cc33218-fd47-415f-a1e0-d98ccff3bc43',2,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"4cc33218-fd47-415f-a1e0-d98ccff3bc43\", \"reservationId\": \"e1469f26-fa58-460c-b61b-1508b53192e1\"}','{\"created-at\": \"2024-06-06T15:00:53.678325Z\", \"stored-event-id\": 10, \"aggregate-root-uuid\": \"4cc33218-fd47-415f-a1e0-d98ccff3bc43\", \"aggregate-root-version\": 2}','2024-06-06 23:00:53'),
	(11,'b3377313-a6dd-42c1-99e2-a9af0a00ae52',3,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"reservationId\": \"963ba47f-5ad9-40db-ae88-34d08af93e24\"}','{\"created-at\": \"2024-06-06T15:57:21.767956Z\", \"stored-event-id\": 11, \"aggregate-root-uuid\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"aggregate-root-version\": 3}','2024-06-06 23:57:21'),
	(12,'b3377313-a6dd-42c1-99e2-a9af0a00ae52',4,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"reservationId\": \"990114df-8ee7-41b5-8120-f1ed64e160b5\"}','{\"created-at\": \"2024-06-06T15:57:21.783705Z\", \"stored-event-id\": 12, \"aggregate-root-uuid\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"aggregate-root-version\": 4}','2024-06-06 23:57:21'),
	(13,'5080f149-05d0-4193-b53a-d2906ee65530',3,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"9aec5531-68bf-4ee3-aafd-98bc8deecf03\"}','{\"created-at\": \"2024-06-06T15:57:53.954229Z\", \"stored-event-id\": 13, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 3}','2024-06-06 23:57:53'),
	(14,'5080f149-05d0-4193-b53a-d2906ee65530',4,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"dfdbd476-9a93-4602-b97d-03b543a68e7a\"}','{\"created-at\": \"2024-06-06T15:57:53.965565Z\", \"stored-event-id\": 14, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 4}','2024-06-06 23:57:53'),
	(15,'04583c9b-0cf3-485c-8590-31e4505949c0',3,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"f18d45c7-cb0c-44c4-9fd3-5248135d8093\"}','{\"created-at\": \"2024-06-06T15:58:07.168143Z\", \"stored-event-id\": 15, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 3}','2024-06-06 23:58:07'),
	(16,'04583c9b-0cf3-485c-8590-31e4505949c0',4,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"224f0313-2cec-4c64-b21b-4c39a63bec54\"}','{\"created-at\": \"2024-06-06T15:58:07.177506Z\", \"stored-event-id\": 16, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 4}','2024-06-06 23:58:07'),
	(17,'04583c9b-0cf3-485c-8590-31e4505949c0',5,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"224f0313-2cec-4c64-b21b-4c39a63bec54\"}','{\"created-at\": \"2024-06-06T15:58:09.967272Z\", \"stored-event-id\": 17, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 5}','2024-06-06 23:58:09'),
	(18,'04583c9b-0cf3-485c-8590-31e4505949c0',6,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 3, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"301f05ef-4476-4ce4-ae7c-8dc20f0a57a2\"}','{\"created-at\": \"2024-06-06T15:58:09.979491Z\", \"stored-event-id\": 18, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 6}','2024-06-06 23:58:09'),
	(19,'04583c9b-0cf3-485c-8590-31e4505949c0',7,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 3, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"301f05ef-4476-4ce4-ae7c-8dc20f0a57a2\"}','{\"created-at\": \"2024-06-06T15:58:10.646401Z\", \"stored-event-id\": 19, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 7}','2024-06-06 23:58:10'),
	(20,'04583c9b-0cf3-485c-8590-31e4505949c0',8,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 4, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"1e741c60-0f57-4856-9ca5-2c5637153d78\"}','{\"created-at\": \"2024-06-06T15:58:10.657845Z\", \"stored-event-id\": 20, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 8}','2024-06-06 23:58:10'),
	(21,'04583c9b-0cf3-485c-8590-31e4505949c0',9,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 4, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"1e741c60-0f57-4856-9ca5-2c5637153d78\"}','{\"created-at\": \"2024-06-06T15:58:11.291679Z\", \"stored-event-id\": 21, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 9}','2024-06-06 23:58:11'),
	(22,'04583c9b-0cf3-485c-8590-31e4505949c0',10,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 5, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"db102cad-3fc1-4148-abe2-b11c2874f3d8\"}','{\"created-at\": \"2024-06-06T15:58:11.304189Z\", \"stored-event-id\": 22, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 10}','2024-06-06 23:58:11'),
	(23,'04583c9b-0cf3-485c-8590-31e4505949c0',11,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 5, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"db102cad-3fc1-4148-abe2-b11c2874f3d8\"}','{\"created-at\": \"2024-06-06T15:58:11.946119Z\", \"stored-event-id\": 23, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 11}','2024-06-06 23:58:11'),
	(24,'04583c9b-0cf3-485c-8590-31e4505949c0',12,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 6, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"928a5d17-9fee-4e11-b74e-433a2a6ea6c4\"}','{\"created-at\": \"2024-06-06T15:58:11.959948Z\", \"stored-event-id\": 24, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 12}','2024-06-06 23:58:11'),
	(25,'04583c9b-0cf3-485c-8590-31e4505949c0',13,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 6, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"928a5d17-9fee-4e11-b74e-433a2a6ea6c4\"}','{\"created-at\": \"2024-06-06T15:58:12.529891Z\", \"stored-event-id\": 25, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 13}','2024-06-06 23:58:12'),
	(26,'04583c9b-0cf3-485c-8590-31e4505949c0',14,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 7, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"a5637df8-22a6-422b-a93b-9997e2e0f282\"}','{\"created-at\": \"2024-06-06T15:58:12.541909Z\", \"stored-event-id\": 26, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 14}','2024-06-06 23:58:12'),
	(27,'04583c9b-0cf3-485c-8590-31e4505949c0',15,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 7, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"a5637df8-22a6-422b-a93b-9997e2e0f282\"}','{\"created-at\": \"2024-06-06T15:58:13.134059Z\", \"stored-event-id\": 27, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 15}','2024-06-06 23:58:13'),
	(28,'04583c9b-0cf3-485c-8590-31e4505949c0',16,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 8, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"402f0c8e-f6bb-4334-b464-b51867f4a11f\"}','{\"created-at\": \"2024-06-06T15:58:13.146624Z\", \"stored-event-id\": 28, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 16}','2024-06-06 23:58:13'),
	(29,'04583c9b-0cf3-485c-8590-31e4505949c0',17,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 8, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"402f0c8e-f6bb-4334-b464-b51867f4a11f\"}','{\"created-at\": \"2024-06-06T15:58:13.747426Z\", \"stored-event-id\": 29, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 17}','2024-06-06 23:58:13'),
	(30,'04583c9b-0cf3-485c-8590-31e4505949c0',18,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 9, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"50dd5a07-cb84-4e3c-96f4-6df301551fd3\"}','{\"created-at\": \"2024-06-06T15:58:13.767989Z\", \"stored-event-id\": 30, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 18}','2024-06-06 23:58:13'),
	(31,'04583c9b-0cf3-485c-8590-31e4505949c0',19,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 9, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"50dd5a07-cb84-4e3c-96f4-6df301551fd3\"}','{\"created-at\": \"2024-06-06T15:58:19.504804Z\", \"stored-event-id\": 31, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 19}','2024-06-06 23:58:19'),
	(32,'04583c9b-0cf3-485c-8590-31e4505949c0',20,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 10, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"c08ece73-8641-4122-a737-f4cdfa57341e\"}','{\"created-at\": \"2024-06-06T15:58:19.523868Z\", \"stored-event-id\": 32, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 20}','2024-06-06 23:58:19'),
	(34,'04583c9b-0cf3-485c-8590-31e4505949c0',21,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 10, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"c08ece73-8641-4122-a737-f4cdfa57341e\"}','{\"created-at\": \"2024-06-06T15:59:08.429322Z\", \"stored-event-id\": 34, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 21}','2024-06-06 23:59:08'),
	(35,'04583c9b-0cf3-485c-8590-31e4505949c0',22,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 9, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"3ecb5cca-6c0e-494c-94dc-992b324c8e67\"}','{\"created-at\": \"2024-06-06T15:59:08.449418Z\", \"stored-event-id\": 35, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 22}','2024-06-06 23:59:08'),
	(36,'04583c9b-0cf3-485c-8590-31e4505949c0',23,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 9, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"3ecb5cca-6c0e-494c-94dc-992b324c8e67\"}','{\"created-at\": \"2024-06-06T15:59:09.971584Z\", \"stored-event-id\": 36, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 23}','2024-06-06 23:59:09'),
	(37,'04583c9b-0cf3-485c-8590-31e4505949c0',24,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 8, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"59af74d8-4168-48e0-aea2-aca093e1befb\"}','{\"created-at\": \"2024-06-06T15:59:09.988580Z\", \"stored-event-id\": 37, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 24}','2024-06-06 23:59:09'),
	(38,'04583c9b-0cf3-485c-8590-31e4505949c0',25,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 8, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"59af74d8-4168-48e0-aea2-aca093e1befb\"}','{\"created-at\": \"2024-06-06T15:59:15.327896Z\", \"stored-event-id\": 38, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 25}','2024-06-06 23:59:15'),
	(39,'04583c9b-0cf3-485c-8590-31e4505949c0',26,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 7, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"6e64d142-c82b-4f41-8eb9-96597fb1fb9a\"}','{\"created-at\": \"2024-06-06T15:59:15.351233Z\", \"stored-event-id\": 39, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 26}','2024-06-06 23:59:15'),
	(40,'04583c9b-0cf3-485c-8590-31e4505949c0',27,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 7, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"6e64d142-c82b-4f41-8eb9-96597fb1fb9a\"}','{\"created-at\": \"2024-06-06T15:59:15.869180Z\", \"stored-event-id\": 40, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 27}','2024-06-06 23:59:15'),
	(41,'04583c9b-0cf3-485c-8590-31e4505949c0',28,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 6, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"45e802df-84fc-4a9e-9d2c-408f57d62743\"}','{\"created-at\": \"2024-06-06T15:59:15.895130Z\", \"stored-event-id\": 41, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 28}','2024-06-06 23:59:15'),
	(42,'04583c9b-0cf3-485c-8590-31e4505949c0',29,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 6, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"45e802df-84fc-4a9e-9d2c-408f57d62743\"}','{\"created-at\": \"2024-06-06T15:59:16.220455Z\", \"stored-event-id\": 42, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 29}','2024-06-06 23:59:16'),
	(43,'04583c9b-0cf3-485c-8590-31e4505949c0',30,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 5, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"30ddb086-6b42-48c1-aca4-3d1fb6e0e652\"}','{\"created-at\": \"2024-06-06T15:59:16.245891Z\", \"stored-event-id\": 43, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 30}','2024-06-06 23:59:16'),
	(44,'04583c9b-0cf3-485c-8590-31e4505949c0',31,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 5, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"30ddb086-6b42-48c1-aca4-3d1fb6e0e652\"}','{\"created-at\": \"2024-06-06T15:59:16.708013Z\", \"stored-event-id\": 44, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 31}','2024-06-06 23:59:16'),
	(45,'04583c9b-0cf3-485c-8590-31e4505949c0',32,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 4, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"71511b52-3a84-4e24-b724-c1ca3222dd0b\"}','{\"created-at\": \"2024-06-06T15:59:16.727242Z\", \"stored-event-id\": 45, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 32}','2024-06-06 23:59:16'),
	(46,'04583c9b-0cf3-485c-8590-31e4505949c0',33,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 4, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"71511b52-3a84-4e24-b724-c1ca3222dd0b\"}','{\"created-at\": \"2024-06-06T15:59:17.130924Z\", \"stored-event-id\": 46, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 33}','2024-06-06 23:59:17'),
	(47,'04583c9b-0cf3-485c-8590-31e4505949c0',34,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 3, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"36740a10-2aa7-4b99-b1af-9d9876d2e02f\"}','{\"created-at\": \"2024-06-06T15:59:17.155343Z\", \"stored-event-id\": 47, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 34}','2024-06-06 23:59:17'),
	(48,'04583c9b-0cf3-485c-8590-31e4505949c0',35,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 3, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"36740a10-2aa7-4b99-b1af-9d9876d2e02f\"}','{\"created-at\": \"2024-06-06T15:59:17.537756Z\", \"stored-event-id\": 48, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 35}','2024-06-06 23:59:17'),
	(49,'04583c9b-0cf3-485c-8590-31e4505949c0',36,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"0625ce05-c09c-4328-8f22-57ab2db869cd\"}','{\"created-at\": \"2024-06-06T15:59:17.562824Z\", \"stored-event-id\": 49, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 36}','2024-06-06 23:59:17'),
	(50,'04583c9b-0cf3-485c-8590-31e4505949c0',37,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"0625ce05-c09c-4328-8f22-57ab2db869cd\"}','{\"created-at\": \"2024-06-06T15:59:20.656865Z\", \"stored-event-id\": 50, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 37}','2024-06-06 23:59:20'),
	(51,'b3377313-a6dd-42c1-99e2-a9af0a00ae52',5,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"reservationId\": \"990114df-8ee7-41b5-8120-f1ed64e160b5\"}','{\"created-at\": \"2024-06-06T16:00:08.712869Z\", \"stored-event-id\": 51, \"aggregate-root-uuid\": \"b3377313-a6dd-42c1-99e2-a9af0a00ae52\", \"aggregate-root-version\": 5}','2024-06-07 00:00:08'),
	(52,'9ff27e48-f440-484d-a02d-72deb49f0746',2,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"ee9a5835-124f-4119-8aa5-1ef6015b2c7a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"reservationId\": \"6fba69e0-c915-42e4-a133-c9785c1411d2\"}','{\"created-at\": \"2024-06-06T16:08:32.629101Z\", \"stored-event-id\": 52, \"aggregate-root-uuid\": \"9ff27e48-f440-484d-a02d-72deb49f0746\", \"aggregate-root-version\": 2}','2024-06-07 00:08:32'),
	(53,'5080f149-05d0-4193-b53a-d2906ee65530',5,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"dfdbd476-9a93-4602-b97d-03b543a68e7a\"}','{\"created-at\": \"2024-06-07T07:28:39.678415Z\", \"stored-event-id\": 53, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 5}','2024-06-07 15:28:39'),
	(54,'5080f149-05d0-4193-b53a-d2906ee65530',6,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 3, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"0a7bf01a-2808-42c0-8d7c-d86b24167eef\"}','{\"created-at\": \"2024-06-07T07:28:39.718951Z\", \"stored-event-id\": 54, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 6}','2024-06-07 15:28:39'),
	(55,'5080f149-05d0-4193-b53a-d2906ee65530',7,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 3, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"0a7bf01a-2808-42c0-8d7c-d86b24167eef\"}','{\"created-at\": \"2024-06-07T07:28:41.538349Z\", \"stored-event-id\": 55, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 7}','2024-06-07 15:28:41'),
	(56,'5080f149-05d0-4193-b53a-d2906ee65530',8,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"3783fd90-3d64-41db-8add-1256ce8b1a5d\"}','{\"created-at\": \"2024-06-07T07:28:41.633163Z\", \"stored-event-id\": 56, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 8}','2024-06-07 15:28:41'),
	(57,'5080f149-05d0-4193-b53a-d2906ee65530',9,1,'StockManagementContracts\\Events\\ReservationCancelled','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 2, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"3783fd90-3d64-41db-8add-1256ce8b1a5d\"}','{\"created-at\": \"2024-06-07T07:29:27.699197Z\", \"stored-event-id\": 57, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 9}','2024-06-07 15:29:27'),
	(58,'5080f149-05d0-4193-b53a-d2906ee65530',10,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"reservationId\": \"a0298653-e746-4f5d-9e5b-0585d05fbde5\"}','{\"created-at\": \"2024-06-07T07:29:27.718119Z\", \"stored-event-id\": 58, \"aggregate-root-uuid\": \"5080f149-05d0-4193-b53a-d2906ee65530\", \"aggregate-root-version\": 10}','2024-06-07 15:29:27'),
	(59,'04583c9b-0cf3-485c-8590-31e4505949c0',38,1,'StockManagementContracts\\Events\\ProductReserved','{\"actor\": \"49c4c872-d328-4015-9615-c13c22c6159a\", \"branchId\": \"50137629-5a82-430e-9c11-a4a92a0a0d20\", \"quantity\": 1, \"productId\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"reservationId\": \"72679e6f-db64-406c-9253-48d5154523c3\"}','{\"created-at\": \"2024-06-07T07:30:05.825106Z\", \"stored-event-id\": 59, \"aggregate-root-uuid\": \"04583c9b-0cf3-485c-8590-31e4505949c0\", \"aggregate-root-version\": 38}','2024-06-07 15:30:05');

/*!40000 ALTER TABLE `stock_management_stored_events` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table stocks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stocks`;

CREATE TABLE `stocks` (
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `available` int NOT NULL,
  `reserved` int NOT NULL,
  KEY `stocks_product_id_branch_id_index` (`product_id`,`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;

INSERT INTO `stocks` (`product_id`, `branch_id`, `available`, `reserved`)
VALUES
	('04583c9b-0cf3-485c-8590-31e4505949c0','50137629-5a82-430e-9c11-a4a92a0a0d20',9,1),
	('4cc33218-fd47-415f-a1e0-d98ccff3bc43','50137629-5a82-430e-9c11-a4a92a0a0d20',14,1),
	('5080f149-05d0-4193-b53a-d2906ee65530','50137629-5a82-430e-9c11-a4a92a0a0d20',19,1),
	('9ff27e48-f440-484d-a02d-72deb49f0746','50137629-5a82-430e-9c11-a4a92a0a0d20',24,1),
	('b3377313-a6dd-42c1-99e2-a9af0a00ae52','50137629-5a82-430e-9c11-a4a92a0a0d20',30,0),
	('cbcd1c39-474a-4eab-9a4b-8820c22a7f9f','50137629-5a82-430e-9c11-a4a92a0a0d20',35,0);

/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table stored_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stored_events`;

CREATE TABLE `stored_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `aggregate_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aggregate_version` bigint unsigned DEFAULT NULL,
  `event_version` tinyint unsigned NOT NULL DEFAULT '1',
  `event_class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_properties` json NOT NULL,
  `meta_data` json NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stored_events_aggregate_uuid_aggregate_version_unique` (`aggregate_uuid`,`aggregate_version`),
  KEY `stored_events_event_class_index` (`event_class`),
  KEY `stored_events_aggregate_uuid_index` (`aggregate_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table suppliers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `suppliers`;

CREATE TABLE `suppliers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;

INSERT INTO `suppliers` (`id`, `code`, `name`, `phone`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	('10ff5102-e820-4e8d-acd4-c5608252cb21','RD','Riley Daniels','214','2024-06-06 22:47:20','2024-06-06 22:47:20',NULL),
	('2947c37b-c7e6-4972-a0d8-17e4ad36262f','NL','Nissim Lamb','254','2024-06-06 22:47:07','2024-06-06 22:47:07',NULL),
	('4267452d-2f1b-41f3-afc1-77ae3815ad7a','BF','Brielle Francis','565','2024-06-06 22:47:11','2024-06-06 22:47:11',NULL),
	('5f04eee3-9218-4f23-8f10-c288a31035b7','SG','Shafira Gillespie','250','2024-06-06 22:47:17','2024-06-06 22:47:17',NULL);

/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table transfer_requests
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transfer_requests`;

CREATE TABLE `transfer_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transfer_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table transfer_stored_events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transfer_stored_events`;

CREATE TABLE `transfer_stored_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `aggregate_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aggregate_version` bigint unsigned DEFAULT NULL,
  `event_version` tinyint unsigned NOT NULL DEFAULT '1',
  `event_class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_properties` json NOT NULL,
  `meta_data` json NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_aggregate_uuid_aggregate_version_unique` (`aggregate_uuid`,`aggregate_version`),
  KEY `transfer_stored_events_event_class_index` (`event_class`),
  KEY `transfer_stored_events_aggregate_uuid_index` (`aggregate_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `branch_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	('2c45c8a3-7395-477b-b877-2abb92faaf73','Louis','Giles','sales@mailinator.com','+1 (885) 602-9587','Est perferendis dolo','50137629-5a82-430e-9c11-a4a92a0a0d20',NULL,'$2y$12$X.toKEi/8R9GRrv9t3QPpO368VHL0eGpo2HtDMOqrnTcSYA5uGP4q',NULL,'2024-06-06 22:46:41','2024-06-06 22:46:41',NULL),
	('49c4c872-d328-4015-9615-c13c22c6159a','Admin','Admin','admin@mailinator.com',NULL,NULL,NULL,NULL,'$2y$12$NK5UVCq01RBOkB9LdkuZyO2gUwMEnLPalEQNg0/A247nvdcPiQOU.',NULL,'2024-06-06 22:45:11','2024-06-06 22:45:11',NULL),
	('8860d0ce-1830-4aa0-828c-4b2c2c5c4f46','Illana','Guthrie','candon@mailinator.com','+1 (444) 434-3981','Eaque dolorem deseru','50137629-5a82-430e-9c11-a4a92a0a0d20',NULL,'$2y$12$eRPlMfvhYDWrqjHhMFX44OAU6sE5jpb9Sl4yK0kEud36Ak55Skb9W',NULL,'2024-06-06 22:45:56','2024-06-06 22:45:56',NULL),
	('e322d4f3-e142-4d7f-817c-e5bf6cadc93b','Gil','Richard','stamaria@mailinator.com','+1 (542) 862-7045','Magnam ex pariatur ','7fc739c5-01d5-495e-96ea-50edfca66a60',NULL,'$2y$12$WLDiBSVqEWcGRBj/P0V8tul800N55kzM4/7mxoLfQnQX2UsZKanYm',NULL,'2024-06-06 22:46:10','2024-06-06 22:46:10',NULL),
	('ee9a5835-124f-4119-8aa5-1ef6015b2c7a','Alika','Grant','cashier@mailinator.com','+1 (469) 653-3368','Commodo consequatur','50137629-5a82-430e-9c11-a4a92a0a0d20',NULL,'$2y$12$5Xpvbqpn8Q0YJkLLkaXV5OB2DXEwZ21IV8iI9GicS8DMyNvaEOmHS',NULL,'2024-06-06 22:46:56','2024-06-06 22:46:56',NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
