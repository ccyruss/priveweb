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
