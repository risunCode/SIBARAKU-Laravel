-- ============================================================
-- SIBARAKU - Database Schema for DrawDB
-- Version: 1.2.0-stable
-- ============================================================

-- Table: users
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NULL,
    `avatar` VARCHAR(255) NULL,
    `birth_date` DATE NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `role` VARCHAR(255) NOT NULL DEFAULT 'user',
    `referral_code` VARCHAR(20) NOT NULL,
    `referred_by` BIGINT UNSIGNED NULL,
    `security_question_1` TINYINT UNSIGNED NULL,
    `security_answer_1` VARCHAR(255) NULL,
    `security_question_2` TINYINT UNSIGNED NULL,
    `security_answer_2` VARCHAR(255) NULL,
    `custom_security_question` VARCHAR(255) NULL,
    `custom_security_answer` VARCHAR(255) NULL,
    `security_setup_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`email`),
    UNIQUE (`referral_code`)
);

-- Table: password_reset_tokens
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    PRIMARY KEY (`email`)
);

-- Table: sessions
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`)
);

-- Table: cache
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
);

-- Table: cache_locks
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
);

-- Table: jobs
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`)
);

-- Table: job_batches
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL,
    PRIMARY KEY (`id`)
);

-- Table: failed_jobs
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`uuid`)
);

-- Table: categories
CREATE TABLE `categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(10) NOT NULL,
    `parent_id` BIGINT UNSIGNED NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`code`)
);

-- Table: locations
CREATE TABLE `locations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(20) NOT NULL,
    `description` TEXT NULL,
    `address` TEXT NULL,
    `pic` VARCHAR(255) NULL,
    `building` VARCHAR(255) NULL,
    `floor` VARCHAR(255) NULL,
    `room` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`code`)
);

-- Table: commodities
CREATE TABLE `commodities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `item_code` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `location_id` BIGINT UNSIGNED NOT NULL,
    `brand` VARCHAR(255) NULL,
    `model` VARCHAR(255) NULL,
    `serial_number` VARCHAR(255) NULL,
    `acquisition_type` ENUM('pembelian', 'hibah', 'bantuan', 'produksi', 'lainnya') NOT NULL DEFAULT 'pembelian',
    `acquisition_source` VARCHAR(255) NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `condition` ENUM('baik', 'rusak_ringan', 'rusak_berat') NOT NULL DEFAULT 'baik',
    `purchase_year` YEAR NULL,
    `purchase_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `specifications` TEXT NULL,
    `notes` TEXT NULL,
    `responsible_person` VARCHAR(255) NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`item_code`)
);

-- Table: commodity_images
CREATE TABLE `commodity_images` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `commodity_id` BIGINT UNSIGNED NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
);

-- Table: transfers
CREATE TABLE `transfers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `transfer_number` VARCHAR(50) NOT NULL,
    `commodity_id` BIGINT UNSIGNED NOT NULL,
    `from_location_id` BIGINT UNSIGNED NOT NULL,
    `to_location_id` BIGINT UNSIGNED NOT NULL,
    `requested_by` BIGINT UNSIGNED NOT NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `status` ENUM('pending', 'approved', 'rejected', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `reason` TEXT NOT NULL,
    `rejection_reason` TEXT NULL,
    `transfer_date` DATE NULL,
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`transfer_number`)
);

-- Table: maintenances
CREATE TABLE `maintenances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `commodity_id` BIGINT UNSIGNED NOT NULL,
    `maintenance_date` DATE NOT NULL,
    `maintenance_type` VARCHAR(255) NULL,
    `description` TEXT NOT NULL,
    `cost` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `performed_by` VARCHAR(255) NULL,
    `vendor` VARCHAR(255) NULL,
    `next_maintenance_date` DATE NULL,
    `condition_after` ENUM('baik', 'rusak_ringan', 'rusak_berat') NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
);

-- Table: disposals
CREATE TABLE `disposals` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `disposal_number` VARCHAR(50) NOT NULL,
    `commodity_id` BIGINT UNSIGNED NOT NULL,
    `disposal_date` DATE NULL,
    `reason` ENUM('rusak_berat', 'hilang', 'usang', 'dicuri', 'dijual', 'dihibahkan', 'lainnya') NOT NULL DEFAULT 'rusak_berat',
    `disposal_method` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `estimated_value` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `disposal_value` DECIMAL(15,2) NULL,
    `notes` TEXT NULL,
    `requested_by` BIGINT UNSIGNED NOT NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `rejection_reason` TEXT NULL,
    `attachments` JSON NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`disposal_number`)
);

-- Table: activity_logs
CREATE TABLE `activity_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(50) NOT NULL,
    `model_type` VARCHAR(100) NULL,
    `model_id` BIGINT UNSIGNED NULL,
    `description` VARCHAR(255) NOT NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL,
    PRIMARY KEY (`id`)
);

-- Table: notifications
CREATE TABLE `notifications` (
    `id` CHAR(36) NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `notifiable_type` VARCHAR(255) NOT NULL,
    `notifiable_id` BIGINT UNSIGNED NOT NULL,
    `data` TEXT NOT NULL,
    `read_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
);

-- Table: referral_codes
CREATE TABLE `referral_codes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(20) NOT NULL,
    `description` VARCHAR(255) NULL,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `max_uses` INT NULL,
    `used_count` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `expires_at` TIMESTAMP NULL,
    `role` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`code`)
);

-- Table: referral_code_usage
CREATE TABLE `referral_code_usage` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `referral_code_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `used_at` TIMESTAMP NOT NULL,
    PRIMARY KEY (`id`)
);

-- Table: report_signatures
CREATE TABLE `report_signatures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `signable_type` VARCHAR(255) NOT NULL,
    `signable_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `signature_hash` VARCHAR(255) NOT NULL,
    `content_hash` VARCHAR(255) NOT NULL,
    `signed_at` TIMESTAMP NOT NULL,
    `ip_address` VARCHAR(255) NULL,
    `user_agent` TEXT NULL,
    `metadata` JSON NULL,
    `is_valid` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`signature_hash`)
);

-- ============================================================
-- Foreign Key Relationships
-- ============================================================

-- users.referred_by -> users.id
ALTER TABLE `users` ADD FOREIGN KEY (`referred_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- categories.parent_id -> categories.id
ALTER TABLE `categories` ADD FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

-- commodities relationships
ALTER TABLE `commodities` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT;
ALTER TABLE `commodities` ADD FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT;
ALTER TABLE `commodities` ADD FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT;
ALTER TABLE `commodities` ADD FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- commodity_images.commodity_id -> commodities.id
ALTER TABLE `commodity_images` ADD FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`id`) ON DELETE CASCADE;

-- transfers relationships
ALTER TABLE `transfers` ADD FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`id`) ON DELETE RESTRICT;
ALTER TABLE `transfers` ADD FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT;
ALTER TABLE `transfers` ADD FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE RESTRICT;
ALTER TABLE `transfers` ADD FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT;
ALTER TABLE `transfers` ADD FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- maintenances relationships
ALTER TABLE `maintenances` ADD FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`id`) ON DELETE CASCADE;
ALTER TABLE `maintenances` ADD FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

-- disposals relationships
ALTER TABLE `disposals` ADD FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`id`) ON DELETE RESTRICT;
ALTER TABLE `disposals` ADD FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT;
ALTER TABLE `disposals` ADD FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- activity_logs.user_id -> users.id
ALTER TABLE `activity_logs` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- referral_codes.created_by -> users.id
ALTER TABLE `referral_codes` ADD FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- referral_code_usage relationships
ALTER TABLE `referral_code_usage` ADD FOREIGN KEY (`referral_code_id`) REFERENCES `referral_codes` (`id`) ON DELETE CASCADE;
ALTER TABLE `referral_code_usage` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- report_signatures.user_id -> users.id
ALTER TABLE `report_signatures` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

-- sessions.user_id -> users.id (optional, no strict FK)
-- ALTER TABLE `sessions` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
