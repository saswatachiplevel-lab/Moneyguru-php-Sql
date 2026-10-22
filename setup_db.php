<?php
/**
 * Moneyguru Financial Services - Database Setup & Seeder Script
 * Run this via CLI or browser to initialize the MySQL database and seed initial data.
 */

require_once __DIR__ . '/config/db.php';

$pdo = getDBConnection();
if (!$pdo) {
    die("Error: Could not connect to MySQL database. Please ensure XAMPP MySQL is running.\n");
}

echo "Connected to MySQL successfully.\n";

// 1. Create Tables
$queries = [
    // Consultations Table
    "CREATE TABLE IF NOT EXISTS `consultations` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(120) NOT NULL,
        `email` VARCHAR(150) NOT NULL,
        `phone` VARCHAR(25) NOT NULL,
        `service` VARCHAR(100) NOT NULL,
        `preferred_contact` ENUM('WhatsApp', 'Phone Call', 'Email') DEFAULT 'WhatsApp',
        `preferred_date` DATE NULL,
        `preferred_time` VARCHAR(50) NULL,
        `message` TEXT NULL,
        `status` ENUM('Pending', 'Contacted', 'Scheduled', 'Completed', 'Cancelled') DEFAULT 'Pending',
        `notes` TEXT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX (`email`),
        INDEX (`phone`),
        INDEX (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Partner Inquiries Table
    "CREATE TABLE IF NOT EXISTS `partner_inquiries` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `full_name` VARCHAR(120) NOT NULL,
        `organization` VARCHAR(150) DEFAULT 'Independent Professional',
        `email` VARCHAR(150) NOT NULL,
        `phone` VARCHAR(25) NOT NULL,
        `partnership_type` VARCHAR(100) NOT NULL,
        `message` TEXT NULL,
        `status` ENUM('New', 'Reviewing', 'Approved', 'Rejected') DEFAULT 'New',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Contact Messages Table
    "CREATE TABLE IF NOT EXISTS `contact_messages` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(120) NOT NULL,
        `email` VARCHAR(150) NOT NULL,
        `phone` VARCHAR(25) NULL,
        `subject` VARCHAR(200) NULL,
        `message` TEXT NOT NULL,
        `status` ENUM('Unread', 'Read', 'Replied') DEFAULT 'Unread',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Calculator Logs Table
    "CREATE TABLE IF NOT EXISTS `calculator_logs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `calc_type` VARCHAR(50) NOT NULL,
        `input_data` JSON NOT NULL,
        `output_data` JSON NOT NULL,
        `user_ip` VARCHAR(50) NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Services Catalog Table
    "CREATE TABLE IF NOT EXISTS `services` (
        `id` VARCHAR(50) PRIMARY KEY,
        `title` VARCHAR(120) NOT NULL,
        `category` VARCHAR(100) NOT NULL,
        `tagline` VARCHAR(255) NOT NULL,
        `short_desc` TEXT NOT NULL,
        `full_desc` TEXT NOT NULL,
        `benefits` JSON NOT NULL,
        `beginner_tips` JSON NOT NULL,
        `key_considerations` JSON NOT NULL,
        `icon_name` VARCHAR(50) NOT NULL,
        `image_url` VARCHAR(255) NOT NULL,
        `badge` VARCHAR(50) NULL,
        `sort_order` INT DEFAULT 0,
        `is_active` TINYINT(1) DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Educational Articles Table
    "CREATE TABLE IF NOT EXISTS `articles` (
        `id` VARCHAR(80) PRIMARY KEY,
        `title` VARCHAR(200) NOT NULL,
        `category` VARCHAR(80) NOT NULL,
        `read_time` VARCHAR(30) NOT NULL,
        `excerpt` TEXT NOT NULL,
        `published_date` VARCHAR(50) NOT NULL,
        `author` VARCHAR(100) NOT NULL,
        `author_role` VARCHAR(100) NOT NULL,
        `image_url` VARCHAR(255) NOT NULL,
        `content` JSON NOT NULL,
        `sort_order` INT DEFAULT 0,
        `is_published` TINYINT(1) DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // FAQs Table
    "CREATE TABLE IF NOT EXISTS `faqs` (
        `id` VARCHAR(50) PRIMARY KEY,
        `category` VARCHAR(50) NOT NULL,
        `category_label` VARCHAR(80) NOT NULL,
        `highlight_tag` VARCHAR(80) NULL,
        `question` TEXT NOT NULL,
        `answer` TEXT NOT NULL,
        `sort_order` INT DEFAULT 0,
        `is_active` TINYINT(1) DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Admins Table
    "CREATE TABLE IF NOT EXISTS `admins` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(60) NOT NULL UNIQUE,
        `password_hash` VARCHAR(255) NOT NULL,
        `email` VARCHAR(120) NOT NULL,
        `full_name` VARCHAR(100) NOT NULL,
        `role` VARCHAR(30) DEFAULT 'admin',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

    // Site Settings Table
    "CREATE TABLE IF NOT EXISTS `site_settings` (
        `setting_key` VARCHAR(60) PRIMARY KEY,
        `setting_value` TEXT NOT NULL,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
];

foreach ($queries as $sql) {
    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        echo "Error creating table: " . $e->getMessage() . "\n";
    }
}
echo "Database tables created successfully.\n";

// 2. Seed Services
$servicesFile = __DIR__ . '/services.json';
if (file_exists($servicesFile)) {
    $servicesData = json_decode(file_get_contents($servicesFile), true);
    if (!empty($servicesData)) {
        $stmt = $pdo->prepare("INSERT INTO `services` (`id`, `title`, `category`, `tagline`, `short_desc`, `full_desc`, `benefits`, `beginner_tips`, `key_considerations`, `icon_name`, `image_url`, `badge`, `sort_order`) 
            VALUES (:id, :title, :category, :tagline, :short_desc, :full_desc, :benefits, :beginner_tips, :key_considerations, :icon_name, :image_url, :badge, :sort_order)
            ON DUPLICATE KEY UPDATE 
            `title` = VALUES(`title`),
            `category` = VALUES(`category`),
            `tagline` = VALUES(`tagline`),
            `short_desc` = VALUES(`short_desc`),
            `full_desc` = VALUES(`full_desc`),
            `benefits` = VALUES(`benefits`),
            `beginner_tips` = VALUES(`beginner_tips`),
            `key_considerations` = VALUES(`key_considerations`),
            `icon_name` = VALUES(`icon_name`),
            `image_url` = VALUES(`image_url`),
            `badge` = VALUES(`badge`),
            `sort_order` = VALUES(`sort_order`)");

        $order = 1;
        foreach ($servicesData as $s) {
            $stmt->execute([
                ':id' => $s['id'],
                ':title' => $s['title'],
                ':category' => $s['category'],
                ':tagline' => $s['tagline'],
                ':short_desc' => $s['shortDesc'] ?? ($s['short_desc'] ?? ''),
                ':full_desc' => $s['fullDesc'] ?? ($s['full_desc'] ?? ''),
                ':benefits' => json_encode($s['benefits'] ?? []),
                ':beginner_tips' => json_encode($s['beginnerTips'] ?? ($s['beginner_tips'] ?? [])),
                ':key_considerations' => json_encode($s['keyConsiderations'] ?? ($s['key_considerations'] ?? [])),
                ':icon_name' => $s['iconName'] ?? ($s['icon_name'] ?? 'TrendingUp'),
                ':image_url' => $s['imageUrl'] ?? ($s['image_url'] ?? ''),
                ':badge' => $s['badge'] ?? null,
                ':sort_order' => $order++
            ]);
        }
        echo "Seeded " . count($servicesData) . " services.\n";
    }
}

// 3. Seed Articles
$articlesFile = __DIR__ . '/articles.json';
if (file_exists($articlesFile)) {
    $articlesData = json_decode(file_get_contents($articlesFile), true);
    if (!empty($articlesData)) {
        $stmt = $pdo->prepare("INSERT INTO `articles` (`id`, `title`, `category`, `read_time`, `excerpt`, `published_date`, `author`, `author_role`, `image_url`, `content`, `sort_order`)
            VALUES (:id, :title, :category, :read_time, :excerpt, :published_date, :author, :author_role, :image_url, :content, :sort_order)
            ON DUPLICATE KEY UPDATE
            `title` = VALUES(`title`),
            `category` = VALUES(`category`),
            `read_time` = VALUES(`read_time`),
            `excerpt` = VALUES(`excerpt`),
            `published_date` = VALUES(`published_date`),
            `author` = VALUES(`author`),
            `author_role` = VALUES(`author_role`),
            `image_url` = VALUES(`image_url`),
            `content` = VALUES(`content`),
            `sort_order` = VALUES(`sort_order`)");

        $order = 1;
        foreach ($articlesData as $a) {
            $stmt->execute([
                ':id' => $a['id'],
                ':title' => $a['title'],
                ':category' => $a['category'],
                ':read_time' => $a['readTime'],
                ':excerpt' => $a['excerpt'],
                ':published_date' => $a['publishedDate'],
                ':author' => $a['author'],
                ':author_role' => $a['authorRole'],
                ':image_url' => $a['imageUrl'],
                ':content' => json_encode($a['content']),
                ':sort_order' => $order++
            ]);
        }
        echo "Seeded " . count($articlesData) . " articles.\n";
    }
}

// 4. Seed FAQs
$faqsFile = __DIR__ . '/faqs.json';
if (file_exists($faqsFile)) {
    $faqsData = json_decode(file_get_contents($faqsFile), true);
    if (!empty($faqsData)) {
        $stmt = $pdo->prepare("INSERT INTO `faqs` (`id`, `category`, `category_label`, `highlight_tag`, `question`, `answer`, `sort_order`)
            VALUES (:id, :category, :category_label, :highlight_tag, :question, :answer, :sort_order)
            ON DUPLICATE KEY UPDATE
            `category` = VALUES(`category`),
            `category_label` = VALUES(`category_label`),
            `highlight_tag` = VALUES(`highlight_tag`),
            `question` = VALUES(`question`),
            `answer` = VALUES(`answer`),
            `sort_order` = VALUES(`sort_order`)");

        $order = 1;
        foreach ($faqsData as $f) {
            $stmt->execute([
                ':id' => $f['id'],
                ':category' => $f['category'],
                ':category_label' => $f['categoryLabel'],
                ':highlight_tag' => $f['highlightTag'] ?? null,
                ':question' => $f['question'],
                ':answer' => $f['answer'],
                ':sort_order' => $order++
            ]);
        }
        echo "Seeded " . count($faqsData) . " FAQs.\n";
    }
}

// 5. Seed Admin User (Production Safe: Only if no admin exists)
$adminCount = $pdo->query("SELECT COUNT(*) FROM `admins`")->fetchColumn();
if ($adminCount == 0) {
    $adminUsername = 'admin';
    $adminPassword = 'admin123';
    $adminHash = password_hash($adminPassword, PASSWORD_BCRYPT);
    $adminEmail = 'saswata@moneyguru.co.in';
    $adminFullName = 'Saswata Roy';

    $stmt = $pdo->prepare("INSERT INTO `admins` (`username`, `password_hash`, `email`, `full_name`, `role`)
        VALUES (:username, :password_hash, :email, :full_name, 'superadmin')");
    $stmt->execute([
        ':username' => $adminUsername,
        ':password_hash' => $adminHash,
        ':email' => $adminEmail,
        ':full_name' => $adminFullName
    ]);
    echo "Initial Admin account seeded (Email: $adminEmail).\n";
} else {
    echo "Admin account already exists. Existing credentials preserved.\n";
}

// 6. Seed Site Settings
$settings = [
    'site_name' => 'Moneyguru Financial Services',
    'tagline' => 'Simple Financial Guidance',
    'founder_name' => 'Saswata Roy',
    'founder_designation' => 'AMFI-Registered Mutual Fund Distributor',
    'arn_number' => 'ARN- 136048',
    'contact_phone' => '+91 98300 00000',
    'contact_whatsapp' => '+91 98300 00000',
    'contact_email' => 'contact@moneyguru.co.in',
    'office_location' => 'Kolkata, West Bengal, India',
    'smtp_enabled' => '0',
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => '587',
    'smtp_encryption' => 'tls',
    'smtp_user' => '',
    'smtp_pass' => '',
    'smtp_from_email' => 'contact@moneyguru.co.in',
    'smtp_from_name' => 'Moneyguru Financial Services',
    'smtp_admin_notify_email' => 'saswata@moneyguru.co.in'
];

$stmt = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) 
    VALUES (:k, :v) ON DUPLICATE KEY UPDATE `setting_value` = `setting_value`");
foreach ($settings as $k => $v) {
    $stmt->execute([':k' => $k, ':v' => $v]);
}
echo "Site settings and SMTP configuration initialized.\n";

echo "=== DATABASE INITIALIZATION COMPLETED SUCCESSFULLY! ===\n";
