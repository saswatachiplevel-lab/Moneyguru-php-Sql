<?php
/**
 * API: Book Consultation
 * Endpoint: /api/book_consultation.php
 */

header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed. Use POST.']);
    exit;
}

// Support both JSON body and standard POST FormData
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

// Honeypot anti-spam trap
if (!empty($data['website_url']) || !empty($data['hp_field'])) {
    echo json_encode(['success' => true, 'message' => 'Consultation booked successfully!']);
    exit;
}

$name = mb_substr(trim($data['name'] ?? ''), 0, 120);
$email = mb_substr(trim($data['email'] ?? ''), 0, 150);
$phone = mb_substr(trim($data['phone'] ?? ''), 0, 25);
$service = mb_substr(trim($data['service'] ?? 'Mutual Funds & SIP'), 0, 100);
$preferred_contact = trim($data['preferred_contact'] ?? ($data['preferredContact'] ?? 'WhatsApp'));
$preferred_date = !empty($data['preferred_date']) ? substr(trim($data['preferred_date']), 0, 10) : null;
$preferred_time = !empty($data['preferred_time']) ? substr(trim($data['preferred_time']), 0, 50) : null;
$message = mb_substr(trim($data['message'] ?? ''), 0, 3000);

// Validation
if (empty($name) || strlen($name) < 2) {
    echo json_encode(['success' => false, 'error' => 'Please enter your full name.']);
    exit;
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

$cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
if (empty($cleanPhone) || strlen($cleanPhone) < 8) {
    echo json_encode(['success' => false, 'error' => 'Please enter a valid phone or WhatsApp number.']);
    exit;
}

$validContacts = ['WhatsApp', 'Phone Call', 'Email'];
if (!in_array($preferred_contact, $validContacts)) {
    $preferred_contact = 'WhatsApp';
}

$pdo = getDBConnection();
if (!$pdo) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO `consultations` 
        (`name`, `email`, `phone`, `service`, `preferred_contact`, `preferred_date`, `preferred_time`, `message`, `status`) 
        VALUES (:name, :email, :phone, :service, :preferred_contact, :preferred_date, :preferred_time, :message, 'Pending')");
    
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $cleanPhone,
        ':service' => $service,
        ':preferred_contact' => $preferred_contact,
        ':preferred_date' => $preferred_date,
        ':preferred_time' => $preferred_time,
        ':message' => $message
    ]);

    $insertId = $pdo->lastInsertId();
    $bookingRef = 'MG-' . str_pad($insertId, 5, '0', STR_PAD_LEFT);

    // Send automated notification via SMTP (non-blocking if mail fails)
    try {
        require_once __DIR__ . '/../config/smtp.php';

        // 1. Client Confirmation Email
        $clientSubject = "Booking Confirmation: {$bookingRef} — Moneyguru Financial Services";
        $clientHtml = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff; color: #092532;'>
            <div style='text-align: center; border-bottom: 2px solid #C9F24A; padding-bottom: 16px; margin-bottom: 20px;'>
                <h2 style='color: #071D29; margin: 0;'>Moneyguru Financial Services</h2>
                <p style='color: #64748b; font-size: 13px; margin: 4px 0 0;'>AMFI-Registered Mutual Fund Distributor &bull; ARN- 136048</p>
            </div>
            <h3 style='color: #071D29;'>Dear " . htmlspecialchars($name) . ",</h3>
            <p style='font-size: 14px; line-height: 1.6;'>
                Thank you for booking a financial guidance session with Moneyguru. Your consultation request has been confirmed and assigned the reference number <strong>{$bookingRef}</strong>.
            </p>
            <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin: 20px 0;'>
                <h4 style='margin: 0 0 10px 0; color: #071D29; font-size: 14px;'>Session Summary:</h4>
                <ul style='list-style: none; padding: 0; margin: 0; font-size: 13px; line-height: 1.8;'>
                    <li><strong>Booking Reference:</strong> {$bookingRef}</li>
                    <li><strong>Financial Service:</strong> " . htmlspecialchars($service) . "</li>
                    <li><strong>Preferred Channel:</strong> " . htmlspecialchars($preferred_contact) . "</li>
                    " . (!empty($preferred_date) ? "<li><strong>Preferred Date:</strong> " . htmlspecialchars($preferred_date) . "</li>" : "") . "
                    " . (!empty($preferred_time) ? "<li><strong>Preferred Slot:</strong> " . htmlspecialchars($preferred_time) . "</li>" : "") . "
                </ul>
            </div>
            <p style='font-size: 13px; line-height: 1.6; color: #475569;'>
                Advisor <strong>Saswata Roy</strong> will contact you via your preferred channel (" . htmlspecialchars($preferred_contact) . ") prior to the slot.
            </p>
            <div style='margin-top: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; text-align: center;'>
                Need urgent assistance? Call or WhatsApp: <strong>+91 6291390883</strong><br>
                Moneyguru Financial Services &bull; Kolkata, West Bengal, India
            </div>
        </div>";

        sendAppEmail($email, $clientSubject, $clientHtml, 'contact@moneyguru.co.in', $name);

        // 2. Admin Alert Email
        $adminEmailStmt = $pdo->query("SELECT `setting_value` FROM `site_settings` WHERE `setting_key` = 'smtp_admin_notify_email' LIMIT 1");
        $adminNotify = $adminEmailStmt->fetchColumn() ?: 'saswata@moneyguru.co.in';

        $adminSubject = "🔔 New Lead [{$bookingRef}]: {$name} ({$service})";
        $adminHtml = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff; color: #092532;'>
            <h3 style='color: #071D29; margin-top: 0;'>New Client Consultation Booked</h3>
            <p style='font-size: 14px;'>A new client has scheduled a consultation on the Moneyguru portal:</p>
            <table style='width: 100%; border-collapse: collapse; font-size: 13px;'>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Reference:</td><td>{$bookingRef}</td></tr>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Client Name:</td><td>" . htmlspecialchars($name) . "</td></tr>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Phone / WhatsApp:</td><td>" . htmlspecialchars($cleanPhone) . "</td></tr>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Email:</td><td>" . htmlspecialchars($email) . "</td></tr>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Service:</td><td>" . htmlspecialchars($service) . "</td></tr>
                <tr><td style='padding: 6px 0; font-weight: bold;'>Preferred Contact:</td><td>" . htmlspecialchars($preferred_contact) . "</td></tr>
                " . (!empty($message) ? "<tr><td style='padding: 6px 0; font-weight: bold; vertical-align: top;'>Client Note:</td><td>" . nl2br(htmlspecialchars($message)) . "</td></tr>" : "") . "
            </table>
            <div style='margin-top: 20px;'>
                <a href='http://localhost/Moneyguru/admin/consultations.php' style='display: inline-block; background: #071D29; color: #C9F24A; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; font-size: 12px;'>View in Admin Panel</a>
            </div>
        </div>";

        sendAppEmail($adminNotify, $adminSubject, $adminHtml, $email, 'Saswata Roy');
    } catch (Exception $mailEx) {
        error_log("SMTP dispatch notice: " . $mailEx->getMessage());
    }

    echo json_encode([
        'success' => true,
        'message' => 'Consultation booked successfully! Saswata Roy will reach out to you via ' . htmlspecialchars($preferred_contact) . '.',
        'id' => $insertId,
        'booking_reference' => $bookingRef
    ]);
} catch (PDOException $e) {
    error_log("Consultation error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to save booking. Please try again.']);
}
