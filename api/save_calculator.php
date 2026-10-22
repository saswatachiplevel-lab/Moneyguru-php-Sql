<?php
/**
 * API: Save Calculator Log
 * Endpoint: /api/save_calculator.php
 */

header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: $_POST;

$calc_type = substr(trim($data['calc_type'] ?? 'SIP'), 0, 50);
$input_data = json_encode($data['input_data'] ?? []);
$output_data = json_encode($data['output_data'] ?? []);
$user_ip = substr($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1', 0, 50);

$pdo = getDBConnection();
if ($pdo) {
    try {
        $stmt = $pdo->prepare("INSERT INTO `calculator_logs` (`calc_type`, `input_data`, `output_data`, `user_ip`) 
            VALUES (:calc_type, :input_data, :output_data, :user_ip)");
        $stmt->execute([
            ':calc_type' => $calc_type,
            ':input_data' => $input_data,
            ':output_data' => $output_data,
            ':user_ip' => $user_ip
        ]);
        echo json_encode(['success' => true]);
        exit;
    } catch (PDOException $e) {
        // silent fallback
    }
}
echo json_encode(['success' => false]);
