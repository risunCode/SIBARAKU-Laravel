-- ============================================================
-- SIBARAKU - Sistem Inventaris Barang Kabupaten Kubu Raya
-- Full Database Schema
-- Version: 1.2.0-stable
-- Generated: November 30, 2025
-- ============================================================
-- 
-- This SQL file contains the complete database schema for SIBARAKU.
-- Compatible with MySQL 8.0+ / MariaDB 10.6+
--
-- Usage:
--   mysql -u root -p sibaraku < sibaraku-full.sql
--
-- Or import via phpMyAdmin
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;

-- ============================================================
-- Database Creation (Optional - uncomment if needed)
-- ============================================================
-- CREATE DATABASE IF NOT EXISTS `sibaraku` 
--     DEFAULT CHARACTER SET utf8mb4 
--     COLLATE utf8mb4_unicode_ci;
-- USE `sibaraku`;

-- ============================================================
-- Table: users
-- Description: User accounts with security features
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL DEFAULT NULL,
    `avatar` VARCHAR(255) NULL DEFAULT NULL,
    `birth_date` DATE NULL DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `role` VARCHAR(255) NOT NULL DEFAULT 'user' COMMENT 'user, admin, staff, etc.',
    `referral_code` VARCHAR(20) NOT NULL,
    `referred_by` BIGINT UNSIGNED NULL DEFAULT NULL,
    `security_question_1` TINYINT UNSIGNED NULL DEFAULT NULL,
    `security_answer_1` VARCHAR(255) NULL DEFAULT NULL,
    `security_question_2` TINYINT UNSIGNED NULL DEFAULT NULL,
    `security_answer_2` VARCHAR(255) NULL DEFAULT NULL,
    `custom_security_question` VARCHAR(255) NULL DEFAULT NULL,
    `custom_security_answer` VARCHAR(255) NULL DEFAULT NULL,
    `security_setup_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    UNIQUE KEY `users_referral_code_unique` (`referral_code`),
    KEY `users_is_active_index` (`is_active`),
    KEY `users_referral_code_index` (`referral_code`),
    KEY `users_referred_by_foreign` (`referred_by`),
    CONSTRAINT `users_referred_by_foreign` FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: password_reset_tokens
-- Description: Password reset tokens for users
-- ============================================================
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: sessions
-- Description: User session management
-- ============================================================
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `user_agent` TEXT NULL DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: cache
-- Description: Application cache storage
-- ============================================================
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: cache_locks
-- Description: Cache locking mechanism
-- ============================================================
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: jobs
-- Description: Queue jobs storage
-- ============================================================
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: job_batches
-- Description: Job batch management
-- ============================================================
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL DEFAULT NULL,
    `cancelled_at` INT NULL DEFAULT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: failed_jobs
-- Description: Failed job records for debugging
-- ============================================================
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: categories
-- Description: Item categories with hierarchical support
-- ============================================================
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(10) NOT NULL,
    `parent_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `categories_code_unique` (`code`),
    KEY `categories_parent_id_index` (`parent_id`),
    KEY `categories_is_active_index` (`is_active`),
    CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: locations
-- Description: Physical locations for inventory storage
-- ============================================================
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `address` TEXT NULL DEFAULT NULL,
    `pic` VARCHAR(255) NULL DEFAULT NULL,
    `building` VARCHAR(255) NULL DEFAULT NULL,
    `floor` VARCHAR(255) NULL DEFAULT NULL,
    `room` VARCHAR(255) NULL DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `locations_code_unique` (`code`),
    KEY `locations_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: commodities
-- Description: Main inventory items table
-- ============================================================
DROP TABLE IF EXISTS `commodities`;
CREATE TABLE `commodities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `item_code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `location_id` BIGINT UNSIGNED NOT NULL,
    `brand` VARCHAR(255) NULL DEFAULT NULL,
    `model` VARCHAR(255) NULL DEFAULT NULL,
    `serial_number` VARCHAR(255) NULL DEFAULT NULL,
    `acquisition_type` ENUM('pembelian', 'hibah', 'bantuan', 'produksi', 'lainnya') NOT NULL DEFAULT 'pembelian',
    `acquisition_source` VARCHAR(255) NULL DEFAULT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `condition` ENUM('baik', 'rusak_ringan', 'rusak_berat') NOT NULL DEFAULT 'baik',
    `purchase_year` YEAR NULL DEFAULT NULL,
    `purchase_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `specifications` TEXT NULL DEFAULT NULL,
    `notes` TEXT NULL DEFAULT NULL,
    `responsible_person` VARCHAR(255) NULL DEFAULT NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `updated_by` BIGINT UNSIGNED NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `commodities_item_code_unique` (`item_code`),
    KEY `commodities_item_code_index` (`item_code`),
    KEY `commodities_category_id_index` (`category_id`),
    KEY `commodities_location_id_index` (`location_id`),
    KEY `commodities_condition_index` (`condition`),
    KEY `commodities_created_by_index` (`created_by`),
    KEY `commodities_deleted_at_index` (`deleted_at`),
    KEY `commodities_updated_by_foreign` (`updated_by`),
    FULLTEXT KEY `commodities_name_brand_notes_fulltext` (`name`, `brand`, `notes`),
    CONSTRAINT `commodities_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `commodities_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `commodities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `commodities_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: commodity_images
-- Description: Images attached to inventory items
-- ============================================================
DROP TABLE IF EXISTS `commodity_images`;
CREATE TABLE `commodity_images` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `commodity_id` BIGINT UNSIGNED NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NULL DEFAULT NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `commodity_images_commodity_id_index` (`commodity_id`),
    KEY `commodity_images_is_primary_index` (`is_primary`),
    CONSTRAINT `commodity_images_commodity_id_foreign` FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: transfers
-- Description: Item transfer/movement records
-- ============================================================
DROP TABLE IF EXISTS `transfers`;
CREATE TABLE `transfers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `transfer_number` VARCHAR(50) NOT NULL,
    `commodity_id` BIGINT UNSIGNED NOT NULL,
    `from_location_id` BIGINT UNSIGNED NOT NULL,
    `to_location_id` BIGINT UNSIGNED NOT NULL,
    `requested_by` BIGINT UNSIGNED NOT NULL,
    `approved_by` BIGINT UNSIGNED NULL DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'rejected', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `reason` TEXT NOT NULL,
    `rejection_reason` TEXT NULL DEFAULT NULL,
    `transfer_date` DATE NULL DEFAULT NULL,
    `notes` TEXT NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `transfers_transfer_number_unique` (`transfer_number`),
    KEY `transfers_commodity_id_index` (`commodity_id`),
    KEY `transfers_status_index` (`status`),
    KEY `transfers_requested_by_index` (`requested_by`),
    KEY `transfers_approved_by_index` (`approved_by`),
    KEY `transfers_created_at_index` (`created_at`),
    KEY `transfers_from_location_id_foreign` (`from_location_id`),
    KEY `transfers_to_location_id_foreign` (`to_location_id`),
    CONSTRAINT `transfers_commodity_id_foreign` FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `transfers_from_location_id_foreign` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `transfers_to_location_id_foreign` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `transfers_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `transfers_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: maintenances (formerly maintenance_logs)
-- Description: Maintenance and repair records
-- ============================================================
DROP TABLE IF EXISTS `maintenances`;
CREATE TABLE `maintenances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `commodity_id` BIGINT UNSIGNED NOT NULL,
    `maintenance_date` DATE NOT NULL,
    `maintenance_type` VARCHAR(255) NULL DEFAULT NULL,
    `description` TEXT NOT NULL,
    `cost` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `performed_by` VARCHAR(255) NULL DEFAULT NULL,
    `vendor` VARCHAR(255) NULL DEFAULT NULL,
    `next_maintenance_date` DATE NULL DEFAULT NULL,
    `condition_after` ENUM('baik', 'rusak_ringan', 'rusak_berat') NULL DEFAULT NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `maintenances_commodity_id_index` (`commodity_id`),
    KEY `maintenances_maintenance_date_index` (`maintenance_date`),
    KEY `maintenances_next_maintenance_date_index` (`next_maintenance_date`),
    KEY `maintenances_created_by_foreign` (`created_by`),
    CONSTRAINT `maintenances_commodity_id_foreign` FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`id`) ON DELETE CASCADE,
    CONSTRAINT `maintenances_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: disposals
-- Description: Item disposal/write-off records
-- ============================================================
DROP TABLE IF EXISTS `disposals`;
CREATE TABLE `disposals` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `disposal_number` VARCHAR(50) NOT NULL,
    `commodity_id` BIGINT UNSIGNED NOT NULL,
    `disposal_date` DATE NULL DEFAULT NULL,
    `reason` ENUM('rusak_berat', 'hilang', 'usang', 'dicuri', 'dijual', 'dihibahkan', 'lainnya') NOT NULL DEFAULT 'rusak_berat',
    `disposal_method` VARCHAR(255) NULL DEFAULT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `estimated_value` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `disposal_value` DECIMAL(15,2) NULL DEFAULT NULL,
    `notes` TEXT NULL DEFAULT NULL,
    `requested_by` BIGINT UNSIGNED NOT NULL,
    `approved_by` BIGINT UNSIGNED NULL DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `rejection_reason` TEXT NULL DEFAULT NULL,
    `attachments` JSON NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `disposals_disposal_number_unique` (`disposal_number`),
    KEY `disposals_commodity_id_index` (`commodity_id`),
    KEY `disposals_status_index` (`status`),
    KEY `disposals_requested_by_index` (`requested_by`),
    KEY `disposals_created_at_index` (`created_at`),
    KEY `disposals_approved_by_foreign` (`approved_by`),
    CONSTRAINT `disposals_commodity_id_foreign` FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `disposals_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `disposals_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: activity_logs
-- Description: Audit trail for all system activities
-- ============================================================
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `action` VARCHAR(50) NOT NULL,
    `model_type` VARCHAR(100) NULL DEFAULT NULL,
    `model_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `description` VARCHAR(255) NOT NULL,
    `old_values` JSON NULL DEFAULT NULL,
    `new_values` JSON NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `user_agent` VARCHAR(255) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `activity_logs_user_id_index` (`user_id`),
    KEY `activity_logs_model_type_model_id_index` (`model_type`, `model_id`),
    KEY `activity_logs_action_index` (`action`),
    KEY `activity_logs_created_at_index` (`created_at`),
    CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: notifications
-- Description: Laravel notification storage
-- ============================================================
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
    `id` CHAR(36) NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `notifiable_type` VARCHAR(255) NOT NULL,
    `notifiable_id` BIGINT UNSIGNED NOT NULL,
    `data` TEXT NOT NULL,
    `read_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`, `notifiable_id`),
    KEY `notifications_read_at_index` (`read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: referral_codes
-- Description: Registration referral codes
-- ============================================================
DROP TABLE IF EXISTS `referral_codes`;
CREATE TABLE `referral_codes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(20) NOT NULL,
    `description` VARCHAR(255) NULL DEFAULT NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `max_uses` INT NULL DEFAULT NULL COMMENT 'null = unlimited',
    `used_count` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `expires_at` TIMESTAMP NULL DEFAULT NULL,
    `role` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Role yang akan diberikan ke user (admin, staff, etc.)',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `referral_codes_code_unique` (`code`),
    KEY `referral_codes_code_index` (`code`),
    KEY `referral_codes_is_active_index` (`is_active`),
    KEY `referral_codes_created_by_foreign` (`created_by`),
    CONSTRAINT `referral_codes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: referral_code_usage
-- Description: Track referral code usage per user
-- ============================================================
DROP TABLE IF EXISTS `referral_code_usage`;
CREATE TABLE `referral_code_usage` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `referral_code_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `used_at` TIMESTAMP NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_referral_code_usage` (`referral_code_id`, `user_id`),
    KEY `referral_code_usage_user_id_foreign` (`user_id`),
    CONSTRAINT `referral_code_usage_referral_code_id_foreign` FOREIGN KEY (`referral_code_id`) REFERENCES `referral_codes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `referral_code_usage_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: migrations (Laravel internal)
-- Description: Track applied migrations
-- ============================================================
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Insert migration records
-- ============================================================
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2024_01_01_000001_create_categories_table', 1),
('2024_01_01_000002_create_locations_table', 1),
('2024_01_01_000003_create_commodities_table', 1),
('2024_01_01_000004_create_commodity_images_table', 1),
('2024_01_01_000005_create_transfers_table', 1),
('2024_01_01_000006_create_maintenance_logs_table', 1),
('2024_01_01_000007_create_disposals_table', 1),
('2024_01_01_000008_create_activity_logs_table', 1),
('2024_01_01_000009_create_notifications_table', 1),
('2025_11_26_184243_create_referral_codes_table', 1),
('2025_11_27_100727_add_address_pic_to_locations_table', 1),
('2025_11_27_143244_add_soft_deletes_to_users_table', 1),
('2025_11_27_164500_add_role_to_referral_codes_table', 1),
('2025_11_27_164600_create_referral_code_usage_table', 1),
('2025_11_27_164700_add_role_to_users_table', 1),
('2025_11_28_094213_rename_maintenance_logs_to_maintenances', 1),
('2025_11_29_213753_add_attachment_to_disposals_table', 1),
('2025_11_30_121811_fix_disposal_status_enum', 1),
('2025_11_30_123917_create_report_signatures_table', 1);

-- ============================================================
-- Table: report_signatures
-- Description: Digital signatures for reports (PDF verification)
-- ============================================================
DROP TABLE IF EXISTS `report_signatures`;
CREATE TABLE `report_signatures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `signable_type` VARCHAR(255) NOT NULL COMMENT 'disposal, maintenance, transfer',
    `signable_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `signature_hash` VARCHAR(255) NOT NULL,
    `content_hash` VARCHAR(255) NOT NULL,
    `signed_at` TIMESTAMP NOT NULL,
    `ip_address` VARCHAR(255) NULL DEFAULT NULL,
    `user_agent` TEXT NULL DEFAULT NULL,
    `metadata` JSON NULL DEFAULT NULL,
    `is_valid` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `report_signatures_signature_hash_unique` (`signature_hash`),
    KEY `report_signatures_signable_type_signable_id_index` (`signable_type`, `signable_id`),
    KEY `report_signatures_user_id_index` (`user_id`),
    KEY `report_signatures_is_valid_index` (`is_valid`),
    CONSTRAINT `report_signatures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

-- ============================================================
-- END OF SIBARAKU DATABASE SCHEMA
-- ============================================================
-- 
-- Summary of Tables:
-- ------------------
-- 1.  users                  - User accounts with security features
-- 2.  password_reset_tokens  - Password reset functionality
-- 3.  sessions               - User session management
-- 4.  cache                  - Application cache storage
-- 5.  cache_locks            - Cache locking mechanism
-- 6.  jobs                   - Queue jobs storage
-- 7.  job_batches            - Job batch management
-- 8.  failed_jobs            - Failed job records
-- 9.  categories             - Item categories (hierarchical)
-- 10. locations              - Physical storage locations
-- 11. commodities            - Main inventory items
-- 12. commodity_images       - Item images
-- 13. transfers              - Item transfer records
-- 14. maintenances           - Maintenance/repair records
-- 15. disposals              - Item disposal records
-- 16. activity_logs          - Audit trail
-- 17. notifications          - System notifications
-- 18. referral_codes         - Registration referral codes
-- 19. referral_code_usage    - Referral code usage tracking
-- 20. report_signatures      - Digital signatures for PDF reports
-- 21. migrations             - Laravel migration tracking
--
-- Total: 21 tables
-- ============================================================
