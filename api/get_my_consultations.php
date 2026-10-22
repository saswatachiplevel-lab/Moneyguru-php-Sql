<?php
/**
 * API: Get Consultations for Client
 * Endpoint: /api/get_my_consultations.php
 */

header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config/db.php';

$identifier = trim($_REQUEST['identifier'] ?? '');
if (empty($identifier)) {
    echo json_encode(['success' => false, 'error' => 'Please provide an email or phone number.']);
    exit;
}

$pdo = getDBConnection();
if (!$pdo) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
    exit;
}

try {
    $cleanPhone = preg_replace('/[^0-9]/', '', $identifier);
    if (!empty($cleanPhone) && strlen($cleanPhone) >= 6) {
        $stmt = $pdo->prepare("SELECT `id`, `name`, `service`, `preferred_contact`, `preferred_date`, `preferred_time`, `status`, `created_at` 
            FROM `consultations` 
            WHERE `email` = :email OR `phone` LIKE :phone 
            ORDER BY `id` DESC LIMIT 10");
        $stmt->execute([
            ':email' => $identifier,
            ':phone' => '%' . $cleanPhone . '%'
        ]);
    } else {
        $stmt = $pdo->prepare("SELECT `id`, `name`, `service`, `preferred_contact`, `preferred_date`, `preferred_time`, `status`, `created_at` 
            FROM `consultations` 
            WHERE LOWER(`email`) = LOWER(:email) 
            ORDER BY `id` DESC LIMIT 10");
        $stmt->execute([':email' => $identifier]);
    }
    $results = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'consultations' => $results
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Query error.']);
}
