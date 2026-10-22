<?php
/**
 * Moneyguru Financial Services - Helper Functions
 */

require_once __DIR__ . '/../config/db.php';

function getSiteSettings() {
    static $settings = null;
    if ($settings !== null) return $settings;

    $defaults = [
        'site_name' => 'Moneyguru Financial Services',
        'tagline' => 'Simple Financial Guidance',
        'founder_name' => 'Saswata Roy',
        'founder_designation' => 'AMFI-Registered Mutual Fund Distributor',
        'arn_number' => 'ARN- 136048',
        'contact_phone' => '6291390883',
        'contact_whatsapp' => '916291390883',
        'contact_email' => 'contact@moneyguru.co.in',
        'office_location' => 'Kolkata, West Bengal, India'
    ];

    $pdo = getDBConnection();
    if (!$pdo) return $defaults;

    try {
        $stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `site_settings`");
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $defaults[$row['setting_key']] = $row['setting_value'];
        }
    } catch (PDOException $e) {
        // use defaults
    }

    $settings = $defaults;
    return $settings;
}

function getAllServices() {
    $pdo = getDBConnection();
    if (!$pdo) {
        $file = __DIR__ . '/../services.json';
        return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    }

    try {
        $stmt = $pdo->query("SELECT * FROM `services` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $services = $stmt->fetchAll();
        foreach ($services as &$s) {
            $s['benefits'] = json_decode($s['benefits'], true) ?: [];
            $s['beginnerTips'] = json_decode($s['beginner_tips'], true) ?: [];
            $s['keyConsiderations'] = json_decode($s['key_considerations'], true) ?: [];
            $s['shortDesc'] = $s['short_desc'];
            $s['fullDesc'] = $s['full_desc'];
            $s['iconName'] = $s['icon_name'];
            $s['imageUrl'] = $s['image_url'];
        }
        return $services;
    } catch (PDOException $e) {
        $file = __DIR__ . '/../services.json';
        return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    }
}

function getAllArticles() {
    $pdo = getDBConnection();
    if (!$pdo) {
        $file = __DIR__ . '/../articles.json';
        return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    }

    try {
        $stmt = $pdo->query("SELECT * FROM `articles` WHERE `is_published` = 1 ORDER BY `sort_order` ASC");
        $articles = $stmt->fetchAll();
        foreach ($articles as &$a) {
            $a['content'] = json_decode($a['content'], true) ?: [];
            $a['readTime'] = $a['read_time'];
            $a['publishedDate'] = $a['published_date'];
            $a['authorRole'] = $a['author_role'];
            $a['imageUrl'] = $a['image_url'];
        }
        return $articles;
    } catch (PDOException $e) {
        $file = __DIR__ . '/../articles.json';
        return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    }
}

function getAllFaqs() {
    $pdo = getDBConnection();
    if (!$pdo) {
        $file = __DIR__ . '/../faqs.json';
        return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    }

    try {
        $stmt = $pdo->query("SELECT * FROM `faqs` WHERE `is_active` = 1 ORDER BY `sort_order` ASC");
        $faqs = $stmt->fetchAll();
        foreach ($faqs as &$f) {
            $f['categoryLabel'] = $f['category_label'];
            $f['highlightTag'] = $f['highlight_tag'];
        }
        return $faqs;
    } catch (PDOException $e) {
        $file = __DIR__ . '/../faqs.json';
        return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    }
}
