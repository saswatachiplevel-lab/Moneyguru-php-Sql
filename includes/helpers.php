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
        'office_location' => 'Kolkata, West Bengal, India',
        'founder_photo' => 'assets/img/about-portrait.jpg'
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

function getFounderPhotoSrc($prefix = '') {
    $settings = getSiteSettings();
    $photo = !empty($settings['founder_photo']) ? $settings['founder_photo'] : 'assets/img/about-portrait.jpg';
    $baseDir = dirname(__DIR__) . '/';
    $fullPath = $baseDir . $photo;
    
    if (file_exists($fullPath)) {
        return $prefix . $photo . '?v=' . filemtime($fullPath);
    }

    $fallbackJpg = $baseDir . 'assets/img/about-portrait.jpg';
    if (file_exists($fallbackJpg)) {
        return $prefix . 'assets/img/about-portrait.jpg?v=' . filemtime($fallbackJpg);
    }

    return $prefix . 'assets/img/saswata-roy.svg';
}

/**
 * Handle advisor portrait upload from admin panel
 */
function handleAdvisorPhotoUpload($pdo, &$alert, &$error) {
    if (!isset($_FILES['founder_photo']) || $_FILES['founder_photo']['error'] !== UPLOAD_ERR_OK) {
        $errorCode = $_FILES['founder_photo']['error'] ?? UPLOAD_ERR_NO_FILE;
        $error = match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "File size exceeds the server upload limit.",
            UPLOAD_ERR_PARTIAL => "The file was only partially uploaded. Please try again.",
            UPLOAD_ERR_NO_FILE => "Please select an image file to upload.",
            default => "Failed to upload photo (error code $errorCode). Please try again."
        };
        return false;
    }

    $file = $_FILES['founder_photo'];
    $tmpPath = $file['tmp_name'];
    $fileSize = $file['size'];

    // Maximum size: 10MB
    if ($fileSize > 10 * 1024 * 1024) {
        $error = "File size exceeds 10MB limit. Please upload a smaller photo.";
        return false;
    }

    // Verify image MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmpPath);
    finfo_close($finfo);

    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/jpg'  => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($allowedMimes[$mime])) {
        $error = "Invalid file format. Please upload a valid JPG, PNG, or WebP image.";
        return false;
    }

    $ext = $allowedMimes[$mime];
    $targetDir = dirname(__DIR__) . '/assets/img/';
    if (!is_dir($targetDir)) {
        @mkdir($targetDir, 0755, true);
    }

    // Ensure default backup exists
    $defaultBackup = $targetDir . 'about-portrait.default.jpg';
    if (!file_exists($defaultBackup) && file_exists($targetDir . 'about-portrait.jpg')) {
        @copy($targetDir . 'about-portrait.jpg', $defaultBackup);
    }

    // Target filename
    $destFilename = 'about-portrait.' . $ext;
    $destPath = $targetDir . $destFilename;

    if (!move_uploaded_file($tmpPath, $destPath)) {
        $error = "Failed to save uploaded photo to the assets directory. Please check file permissions.";
        return false;
    }

    // If uploaded photo is not jpg, create a copy as about-portrait.jpg if GD is loaded
    if ($ext !== 'jpg') {
        if (extension_loaded('gd')) {
            try {
                if ($ext === 'png') {
                    $img = @imagecreatefrompng($destPath);
                } elseif ($ext === 'webp') {
                    $img = @imagecreatefromwebp($destPath);
                } else {
                    $img = null;
                }
                if ($img) {
                    @imagejpeg($img, $targetDir . 'about-portrait.jpg', 92);
                    @imagedestroy($img);
                }
            } catch (\Throwable $e) {}
        }
    }

    $relativeSettingPath = 'assets/img/' . $destFilename;
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) 
                VALUES ('founder_photo', :p) 
                ON DUPLICATE KEY UPDATE `setting_value` = :p");
            $stmt->execute([':p' => $relativeSettingPath]);
        } catch (\PDOException $e) {}
    }

    $alert = "Advisor portrait photo updated successfully! The new photo is now live on the public website.";
    return true;
}

/**
 * Reset advisor portrait back to the original default photo
 */
function handleAdvisorPhotoReset($pdo, &$alert, &$error) {
    $targetDir = dirname(__DIR__) . '/assets/img/';
    $defaultBackup = $targetDir . 'about-portrait.default.jpg';
    $fallbackSource = file_exists($defaultBackup) ? $defaultBackup : ($targetDir . 'saswata-roy.jpg');

    if (file_exists($fallbackSource)) {
        @copy($fallbackSource, $targetDir . 'about-portrait.jpg');
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) 
                    VALUES ('founder_photo', 'assets/img/about-portrait.jpg') 
                    ON DUPLICATE KEY UPDATE `setting_value` = 'assets/img/about-portrait.jpg'");
                $stmt->execute();
            } catch (\PDOException $e) {}
        }
        $alert = "Advisor portrait photo restored to the original default image successfully!";
        return true;
    } else {
        $error = "Original default photo backup not found.";
        return false;
    }
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
