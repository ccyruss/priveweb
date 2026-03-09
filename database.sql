-- Database Schema for Prive Web Project

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

-- Table for general site settings
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description_tr` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_user` varchar(100) DEFAULT NULL,
  `smtp_pass` varchar(100) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  `about_img` varchar(255) DEFAULT NULL,
  `about_content_tr` text DEFAULT NULL,
  `about_content_en` text DEFAULT NULL,
  `corporate_content_tr` text DEFAULT NULL,
  `corporate_content_en` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO `settings` (`id`, `title`) VALUES (1, 'Prive Luxury Tourism');

-- Table for sliders
CREATE TABLE `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL, -- YouTube URL
  `title_tr` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `subtitle_tr` varchar(255) DEFAULT NULL,
  `subtitle_en` varchar(255) DEFAULT NULL,
  `button_text_tr` varchar(100) DEFAULT NULL,
  `button_text_en` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for categories
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_tr` varchar(255) DEFAULT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `slug_tr` varchar(255) DEFAULT NULL,
  `slug_en` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for tours
CREATE TABLE `tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `title_tr` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `slug_tr` varchar(255) DEFAULT NULL,
  `slug_en` varchar(255) DEFAULT NULL,
  `short_desc_tr` text DEFAULT NULL,
  `short_desc_en` text DEFAULT NULL,
  `content_tr` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `hover_image` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `duration_tr` varchar(100) DEFAULT NULL,
  `duration_en` varchar(100) DEFAULT NULL,
  `location_tr` varchar(100) DEFAULT NULL,
  `location_en` varchar(100) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for tour gallery
CREATE TABLE `tour_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for blog posts
CREATE TABLE `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_tr` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `slug_tr` varchar(255) DEFAULT NULL,
  `slug_en` varchar(255) DEFAULT NULL,
  `content_tr` longtext DEFAULT NULL,
  `content_en` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for photo/video gallery
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('photo','video') DEFAULT 'photo',
  `file` varchar(255) DEFAULT NULL, -- Image path or Video URL
  `title_tr` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table for contact messages
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin table
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin: admin / admin123 (hashed)
INSERT INTO `admins` (`username`, `password`) VALUES ('admin', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgdt9767nsu6e.N86B/247YfN8ru');

COMMIT;


/* NEW TABLES FOR DYNAMIC TOUR DETAILS */

<?php
require 'inc/db.php';

try {
    // 1. Alter tours table
    $alter_sql = "
    ALTER TABLE `tours` 
    ADD COLUMN `advance_facilities_tr` TEXT DEFAULT NULL,
    ADD COLUMN `advance_facilities_en` TEXT DEFAULT NULL,
    ADD COLUMN `expect_desc_tr` TEXT DEFAULT NULL,
    ADD COLUMN `expect_desc_en` TEXT DEFAULT NULL,
    ADD COLUMN `departure_location_tr` VARCHAR(255) DEFAULT NULL,
    ADD COLUMN `departure_location_en` VARCHAR(255) DEFAULT NULL,
    ADD COLUMN `departure_time` VARCHAR(100) DEFAULT NULL,
    ADD COLUMN `return_time` VARCHAR(100) DEFAULT NULL,
    ADD COLUMN `price` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `original_price` DECIMAL(10,2) DEFAULT NULL,
    ADD COLUMN `max_guests` INT(11) DEFAULT NULL,
    ADD COLUMN `map_iframe` TEXT DEFAULT NULL;
    ";

    // Ignore errors if columns exist
    try {
        $pdo->exec($alter_sql);
    } catch (PDOException $e) {
    }

    // 2. Create tour_expect_list table
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS `tour_expect_list` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tour_id` int(11) NOT NULL,
      `text_tr` varchar(255) DEFAULT NULL,
      `text_en` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `tour_id` (`tour_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 3. Create tour_includes table (for Included/Exclude)
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS `tour_includes` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tour_id` int(11) NOT NULL,
      `text_tr` varchar(255) DEFAULT NULL,
      `text_en` varchar(255) DEFAULT NULL,
      `is_included` tinyint(1) DEFAULT 1,
      PRIMARY KEY (`id`),
      KEY `tour_id` (`tour_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 4. Create tour_amenities table
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS `tour_amenities` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tour_id` int(11) NOT NULL,
      `text_tr` varchar(255) DEFAULT NULL,
      `text_en` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `tour_id` (`tour_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 5. Create tour_itineraries table (for Tour Plan)
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS `tour_itineraries` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tour_id` int(11) NOT NULL,
      `day_number` int(11) DEFAULT 1,
      `title_tr` varchar(255) DEFAULT NULL,
      `title_en` varchar(255) DEFAULT NULL,
      `content_tr` text DEFAULT NULL,
      `content_en` text DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `tour_id` (`tour_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 6. Create tour_itinerary_items tables (for bullet points under each day)
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS `tour_itinerary_items` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `itinerary_id` int(11) NOT NULL,
      `text_tr` varchar(255) DEFAULT NULL,
      `text_en` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `itinerary_id` (`itinerary_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // 7. Create tour_faqs table
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS `tour_faqs` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tour_id` int(11) NOT NULL,
      `question_tr` varchar(255) DEFAULT NULL,
      `question_en` varchar(255) DEFAULT NULL,
      `answer_tr` text DEFAULT NULL,
      `answer_en` text DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `tour_id` (`tour_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Note: Tour slider images can just reuse the existing `tour_gallery` table!

    // Dump updated DB schema definition to database.sql for reference
    $schema = file_get_contents('database.sql');
    if (strpos($schema, 'tour_itineraries') === false) {
        $schema .= "\n\n/* NEW TABLES FOR DYNAMIC TOUR DETAILS */\n\n";
        $schema .= file_get_contents(__FILE__);
        file_put_contents('database.sql', $schema);
    }

    echo "Database migrations executed successfully.";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
