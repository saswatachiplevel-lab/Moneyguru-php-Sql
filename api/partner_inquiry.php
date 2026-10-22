<?php
/**
 * API: Partner Inquiry
 * Endpoint: /api/partner_inquiry.php
 */

header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed. Use POST.']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

// Honeypot anti-spam trap
if (!empty($data['website_url']) || !empty($data['hp_field'])) {
    echo json_encode(['success' => true, 'message' => 'Partner proposal received successfully!']);
    exit;
}

$full_name = mb_substr(trim($data['full_name'] ?? ($data['fullName'] ?? '')), 0, 120);
$organization = mb_substr(trim($data['organization'] ?? 'Independent Professional'), 0, 150);
$email = mb_substr(trim($data['email'] ?? ''), 0, 150);
$phone = mb_substr(trim($data['phone'] ?? ''), 0, 25);
$partnership_type = mb_substr(trim($data['partnership_type'] ?? ($data['partnershipType'] ?? 'Referral Partner')), 0, 100);
$message = mb_substr(trim($data['message'] ?? ''), 0, 3000);

if (empty($full_name) || strlen($full_name) < 2) {
    echo json_encode(['success' => false, 'error' => 'Please enter your full name.']);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

$cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
if (empty($cleanPhone) || strlen($cleanPhone) < 8) {
    echo json_encode(['success' => false, 'error' => 'Please enter a valid contact number.']);
    exit;
}

$pdo = getDBConnection();
if (!$pdo) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO `partner_inquiries` 
        (`full_name`, `organization`, `email`, `phone`, `partnership_type`, `message`, `status`)
        VALUES (:full_name, :organization, :email, :phone, :partnership_type, :message, 'New')");
    
    $stmt->execute([
        ':full_name' => $full_name,
        ':organization' => $organization ?: 'Independent Professional',
        ':email' => $email,
        ':phone' => $cleanPhone,
        ':partnership_type' => $partnership_type,
        ':message' => $message
    ]);

    $insertId = $pdo->lastInsertId();

    try {
        require_once __DIR__ . '/../config/smtp.php';

        $partnerSubject = "Partnership Proposal Received — Moneyguru Financial Services";
        $partnerHtml = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff; color: #092532;'>
            <div style='text-align: center; border-bottom: 2px solid #C9F24A; padding-bottom: 16px; margin-bottom: 20px;'>
                <h2 style='color: #071D29; margin: 0;'>Moneyguru Financial Services</h2>
                <p style='color: #64748b; font-size: 13px; margin: 4px 0 0;'>Institutional & Affiliate Partnerships</p>
            </div>
            <h3 style='color: #071D29;'>Dear " . htmlspecialchars($full_name) . ",</h3>
            <p style='font-size: 14px; line-height: 1.6;'>
                Thank you for your interest in partnering with Moneyguru. We have received your proposal regarding <strong>" . htmlspecialchars($partnership_type) . "</strong> (" . htmlspecialchars($organization) . ").
            </p>
            <p style='font-size: 13px; line-height: 1.6; color: #475569;'>
                Our advisory team will review your submission and reach out within 24 to 48 business hours.
            </p>
            <div style='margin-top: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; text-align: center;'>
                Moneyguru Financial Services &bull; Kolkata, West Bengal, India
            </div>
        </div>";

        sendAppEmail($email, $partnerSubject, $partnerHtml, 'contact@moneyguru.co.in', $full_name);

        $adminEmailStmt = $pdo->query("SELECT `setting_value` FROM `site_settings` WHERE `setting_key` = 'smtp_admin_notify_email' LIMIT 1");
        $adminNotify = $adminEmailStmt->fetchColumn() ?: 'saswata@moneyguru.co.in';

        $adminSubject = "🤝 New Partner Proposal: {$full_name} ({$organization})";
        $adminHtml = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff; color: #092532;'>
            <h3 style='color: #071D29; margin-top: 0;'>New Partnership Proposal Submitted</h3>
            <table style='width: 100%; border-collapse: collapse; font-size: 13px;'>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Name:</td><td>" . htmlspecialchars($full_name) . "</td></tr>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Organization:</td><td>" . htmlspecialchars($organization) . "</td></tr>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Type:</td><td>" . htmlspecialchars($partnership_type) . "</td></tr>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Phone:</td><td>" . htmlspecialchars($cleanPhone) . "</td></tr>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Email:</td><td>" . htmlspecialchars($email) . "</td></tr>
                " . (!empty($message) ? "<tr><td style='padding: 6px 0; font-weight: bold; vertical-align: top;'>Proposal:</td><td>" . nl2br(htmlspecialchars($message)) . "</td></tr>" : "") . "
            </table>
            <div style='margin-top: 20px;'>
                <a href='http://localhost/Moneyguru/admin/partners.php' style='display: inline-block; background: #071D29; color: #C9F24A; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; font-size: 12px;'>Review Proposal</a>
            </div>
        </div>";

        sendAppEmail($adminNotify, $adminSubject, $adminHtml, $email, 'Saswata Roy');
    } catch (Exception $mailEx) {
        error_log("SMTP partner dispatch notice: " . $mailEx->getMessage());
    }

    echo json_encode([
        'success' => true,
        'message' => 'Partner proposal received successfully! We will connect with you within 24-48 business hours.',
        'id' => $insertId
    ]);
} catch (PDOException $e) {
    error_log("Partner inquiry error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to submit proposal. Please try again.']);
}
