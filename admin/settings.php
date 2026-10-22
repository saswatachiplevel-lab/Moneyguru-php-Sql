<?php
/**
 * Moneyguru Admin - Settings & Configuration
 * Includes Advisor Coordinates, SMTP Mailer Settings, and Diagnostics.
 */
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/smtp.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = getDBConnection();
$alert = '';
$error = '';
$smtpTestResult = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Security token expired or invalid. Please reload the page.";
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'update_photo') {
            handleAdvisorPhotoUpload($pdo, $alert, $error);
        } elseif ($action === 'reset_photo') {
            handleAdvisorPhotoReset($pdo, $alert, $error);
        } elseif ($action === 'save_settings') {
        $keys = [
            'site_name', 'tagline', 'founder_name', 'founder_designation', 
            'arn_number', 'contact_phone', 'contact_whatsapp', 'contact_email', 'office_location'
        ];
        $stmt = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) 
            VALUES (:k, :v) ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`)");
        
        foreach ($keys as $k) {
            if (isset($_POST[$k])) {
                $stmt->execute([':k' => $k, ':v' => trim($_POST[$k])]);
            }
        }
        $alert = "General business coordinates saved successfully.";
    } elseif ($action === 'save_smtp') {
        $smtpKeys = [
            'smtp_host', 'smtp_port', 'smtp_encryption', 'smtp_user', 
            'smtp_pass', 'smtp_from_email', 'smtp_from_name', 'smtp_admin_notify_email'
        ];
        $stmt = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) 
            VALUES (:k, :v) ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`)");
        
        // Handle checkbox
        $smtpEnabled = isset($_POST['smtp_enabled']) ? '1' : '0';
        $stmt->execute([':k' => 'smtp_enabled', ':v' => $smtpEnabled]);

        foreach ($smtpKeys as $k) {
            if (isset($_POST[$k])) {
                $stmt->execute([':k' => $k, ':v' => trim($_POST[$k])]);
            }
        }
        $alert = "SMTP email configuration saved successfully.";
    } elseif ($action === 'test_smtp') {
        $testEmail = trim($_POST['test_email'] ?? '');
        if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid recipient email address for testing.";
        } else {
            // First save any currently posted SMTP parameters so the test reflects current inputs
            $smtpHost       = trim($_POST['smtp_host'] ?? '');
            $smtpPort       = intval($_POST['smtp_port'] ?? 587);
            $smtpEncryption = trim($_POST['smtp_encryption'] ?? 'tls');
            $smtpUser       = trim($_POST['smtp_user'] ?? '');
            $smtpPass       = trim($_POST['smtp_pass'] ?? '');
            $smtpFromEmail  = trim($_POST['smtp_from_email'] ?? 'contact@moneyguru.co.in');
            $smtpFromName   = trim($_POST['smtp_from_name'] ?? 'Moneyguru Financial Services');

            $mailer = new SimpleSMTPMailer([
                'host'       => $smtpHost,
                'port'       => $smtpPort,
                'encryption' => $smtpEncryption,
                'username'   => $smtpUser,
                'password'   => $smtpPass,
                'from_email' => $smtpFromEmail,
                'from_name'  => $smtpFromName
            ]);

            $nowStr = date('d M Y, h:i A');
            $subject = "✅ Moneyguru SMTP Test Email — " . $nowStr;
            $htmlBody = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff;'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <h2 style='color: #071D29; margin: 0;'>Moneyguru Financial Services</h2>
                    <p style='color: #64748b; font-size: 13px; margin: 4px 0 0;'>Simple Financial Guidance &bull; AMFI ARN- 136048</p>
                </div>
                <div style='background: #ecfdf5; border-left: 4px solid #10b981; padding: 16px; border-radius: 8px; margin-bottom: 20px;'>
                    <h3 style='color: #065f46; margin: 0 0 6px 0; font-size: 16px;'>SMTP Connection Successful!</h3>
                    <p style='color: #047857; margin: 0; font-size: 14px;'>This test email confirms that your SMTP server connection and authentication are properly configured.</p>
                </div>
                <table style='width: 100%; border-collapse: collapse; font-size: 13px; color: #334155; margin-bottom: 20px;'>
                    <tr><td style='padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-weight: bold;'>SMTP Host:</td><td style='padding: 8px 0; border-bottom: 1px solid #f1f5f9;'>{$smtpHost}:{$smtpPort} ({$smtpEncryption})</td></tr>
                    <tr><td style='padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-weight: bold;'>Authenticated User:</td><td style='padding: 8px 0; border-bottom: 1px solid #f1f5f9;'>{$smtpUser}</td></tr>
                    <tr><td style='padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-weight: bold;'>From Header:</td><td style='padding: 8px 0; border-bottom: 1px solid #f1f5f9;'>{$smtpFromName} &lt;{$smtpFromEmail}&gt;</td></tr>
                    <tr><td style='padding: 8px 0; font-weight: bold;'>Dispatched At:</td><td style='padding: 8px 0;'>{$nowStr}</td></tr>
                </table>
                <div style='border-top: 1px solid #e2e8f0; padding-top: 16px; font-size: 12px; color: #94a3b8; text-align: center;'>
                    Moneyguru Advisor Portal &bull; Generated from Settings Diagnostic Tool
                </div>
            </div>";

            $res = $mailer->send($testEmail, $subject, $htmlBody);
            $smtpTestResult = $res;
            if ($res['success']) {
                $alert = "Test email sent successfully to $testEmail! Please check your inbox / spam folder.";
            } else {
                $error = "Failed to send test email: " . ($res['error'] ?? 'Unknown error');
            }
        }
    }
}
}

// Fetch all current settings
$stmt = $pdo->query("SELECT `setting_key`, `setting_value` FROM `site_settings`");
$settings = [];
foreach ($stmt->fetchAll() as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings | Moneyguru Admin</title>
    <link rel="icon" type="image/svg+xml" href="../assets/img/logo.svg">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F7F8F5; }
    </style>
</head>
<body class="min-h-screen text-[#092532] flex flex-col">
    
    <header class="bg-[#071D29] text-white px-6 py-3.5 border-b border-white/10 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="../assets/img/logo.svg" alt="Moneyguru" class="w-9 h-9 rounded-full object-contain">
                <div>
                    <h1 class="font-extrabold text-base tracking-tight text-white flex items-center gap-1.5">
                        <span>Moneyguru Admin</span>
                        <span class="text-[10px] font-bold bg-[#C9F24A] text-[#071D29] px-2 py-0.5 rounded-full">Settings</span>
                    </h1>
                    <p class="text-[10px] text-white/60">Saswata Roy &bull; ARN- 136048</p>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-1 text-xs font-semibold">
                <a href="index.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Dashboard</a>
                <a href="consultations.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">All Consultations</a>
                <a href="partners.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Partnerships</a>
                <a href="profile.php" class="text-white/70 hover:text-white px-3.5 py-1.5 rounded-lg hover:bg-white/5 transition-colors">Profile</a>
                <a href="settings.php" class="bg-white/10 text-[#C9F24A] px-3.5 py-1.5 rounded-lg">Settings</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="../index.php" target="_blank" class="hidden sm:flex items-center gap-1.5 text-xs text-white/80 hover:text-white bg-white/5 px-3 py-1.5 rounded-lg border border-white/10">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span>View Website</span>
                </a>
                <a href="logout.php" class="flex items-center gap-1.5 text-xs text-rose-300 hover:text-rose-200 bg-rose-500/10 px-3 py-1.5 rounded-lg border border-rose-500/20">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-5xl mx-auto w-full p-6 sm:p-8 space-y-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#092532]">Settings &amp; Configuration</h2>
                <p class="text-xs text-[#607078] mt-0.5">Configure business contact coordinates, SMTP email gateway, and security.</p>
            </div>
            <div>
                <a href="profile.php" class="inline-flex items-center gap-2 bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>Manage Admin Profile &amp; Password &rarr;</span>
                </a>
            </div>
        </div>

        <?php if (!empty($alert)): ?>
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
            <div>
                <strong class="font-bold">Success:</strong> <?= htmlspecialchars($alert) ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-xs text-red-800 flex items-center gap-3 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
            <div>
                <strong class="font-bold">Notice:</strong> <?= htmlspecialchars($error) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- SMTP Email Settings Section -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/90 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-xl bg-[#071D29]/5 text-[#071D29]">
                            <i data-lucide="mail-check" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#092532]">SMTP Email Configuration</h3>
                    </div>
                    <p class="text-xs text-[#607078] mt-1">
                        Configure outgoing SMTP for instant consultation booking confirmations and advisor lead alerts.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold <?= ($settings['smtp_enabled'] ?? '0') === '1' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' ?>">
                        <span class="w-2 h-2 rounded-full <?= ($settings['smtp_enabled'] ?? '0') === '1' ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
                        <span><?= ($settings['smtp_enabled'] ?? '0') === '1' ? 'SMTP Active' : 'SMTP Disabled' ?></span>
                    </span>
                </div>
            </div>

            <!-- SMTP Form -->
            <form method="POST" action="settings.php" id="smtpForm" class="space-y-6 pt-6">
                <?= csrfInput() ?>
                <input type="hidden" name="action" value="save_smtp">

                <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 border border-gray-200">
                    <input type="checkbox" id="smtp_enabled" name="smtp_enabled" value="1" <?= ($settings['smtp_enabled'] ?? '0') === '1' ? 'checked' : '' ?> class="w-4 h-4 rounded text-[#071D29] focus:ring-[#071D29]">
                    <label for="smtp_enabled" class="text-xs font-bold text-[#092532] cursor-pointer">
                        Enable SMTP for all outgoing system notifications and lead alerts
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">SMTP Host / Server</label>
                        <input type="text" name="smtp_host" id="smtp_host" value="<?= htmlspecialchars($settings['smtp_host'] ?? 'smtp.gmail.com') ?>" placeholder="e.g. smtp.gmail.com or smtp.office365.com" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Port</label>
                        <input type="number" name="smtp_port" id="smtp_port" value="<?= htmlspecialchars($settings['smtp_port'] ?? '587') ?>" placeholder="587" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Encryption Protocol</label>
                        <select name="smtp_encryption" id="smtp_encryption" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm font-semibold">
                            <option value="tls" <?= ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS / STARTTLS (Port 587 - Recommended)</option>
                            <option value="ssl" <?= ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL / SMTPS (Port 465)</option>
                            <option value="none" <?= ($settings['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None / Plain (Port 25)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">SMTP Username / Email</label>
                        <input type="text" name="smtp_user" id="smtp_user" value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>" placeholder="your-email@gmail.com" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">SMTP Password / App Password</label>
                        <div class="relative">
                            <input type="password" id="smtp_pass" name="smtp_pass" value="<?= htmlspecialchars($settings['smtp_pass'] ?? '') ?>" placeholder="Enter password or 16-char app pass" class="w-full px-3.5 pr-10 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm font-mono">
                            <button type="button" onclick="togglePasswordVisibility('smtp_pass', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Toggle password">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">From Email Address</label>
                        <input type="email" name="smtp_from_email" id="smtp_from_email" value="<?= htmlspecialchars($settings['smtp_from_email'] ?? 'contact@moneyguru.co.in') ?>" placeholder="noreply@moneyguru.co.in" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">From Sender Name</label>
                        <input type="text" name="smtp_from_name" id="smtp_from_name" value="<?= htmlspecialchars($settings['smtp_from_name'] ?? 'Moneyguru Financial Services') ?>" placeholder="Moneyguru Financial Services" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Admin Notification Recipient</label>
                        <input type="email" name="smtp_admin_notify_email" id="smtp_admin_notify_email" value="<?= htmlspecialchars($settings['smtp_admin_notify_email'] ?? 'saswata@moneyguru.co.in') ?>" placeholder="saswata@moneyguru.co.in" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm">
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200 text-xs text-amber-900 space-y-1">
                    <div class="font-bold flex items-center gap-1.5">
                        <i data-lucide="lightbulb" class="w-4 h-4 text-amber-600 shrink-0"></i>
                        <span>Quick Setup Tip for Gmail &amp; Google Workspace:</span>
                    </div>
                    <p class="text-[11px] text-amber-800 pl-5 leading-relaxed">
                        If using Gmail, enable 2-Step Verification in your Google Account and create a 16-character <strong>App Password</strong> (under Security &rarr; 2-Step Verification &rarr; App Passwords). Use that App Password here as your SMTP password with Port <strong>587</strong> and <strong>TLS</strong> encryption.
                    </p>
                </div>

                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <button type="submit" class="bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-bold text-xs px-6 py-3 rounded-xl shadow-md transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Save SMTP Settings</span>
                    </button>
                </div>
            </form>

            <!-- Test SMTP Email Tool -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="send" class="w-4 h-4 text-[#071D29]"></i>
                    <h4 class="text-sm font-bold text-[#092532]">Live SMTP Connection Test</h4>
                </div>
                <p class="text-xs text-[#607078] mb-4">
                    Send an instantaneous test email using the currently entered parameters above to verify socket connection and authentication.
                </p>

                <form method="POST" action="settings.php" class="flex flex-col sm:flex-row items-center gap-3">
                    <?= csrfInput() ?>
                    <input type="hidden" name="action" value="test_smtp">
                    
                    <!-- Hidden mirrors of currently typed values so user can test before or after saving -->
                    <input type="hidden" name="smtp_host" id="mirror_smtp_host">
                    <input type="hidden" name="smtp_port" id="mirror_smtp_port">
                    <input type="hidden" name="smtp_encryption" id="mirror_smtp_encryption">
                    <input type="hidden" name="smtp_user" id="mirror_smtp_user">
                    <input type="hidden" name="smtp_pass" id="mirror_smtp_pass">
                    <input type="hidden" name="smtp_from_email" id="mirror_smtp_from_email">
                    <input type="hidden" name="smtp_from_name" id="mirror_smtp_from_name">

                    <div class="w-full sm:flex-1 relative">
                        <i data-lucide="mail" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                        <input type="email" name="test_email" required value="<?= htmlspecialchars($_SESSION['admin_email'] ?? ($settings['smtp_admin_notify_email'] ?? 'saswata@moneyguru.co.in')) ?>" placeholder="Recipient Email (e.g. saswata@moneyguru.co.in)" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                    </div>

                    <button type="submit" onclick="syncMirrors()" class="w-full sm:w-auto bg-[#123B43] hover:bg-[#071D29] text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 shrink-0">
                        <i data-lucide="play" class="w-4 h-4 text-[#C9F24A]"></i>
                        <span>Send Test Email</span>
                    </button>
                </form>

                <?php if ($smtpTestResult !== null): ?>
                <div class="mt-4 p-4 rounded-2xl <?= $smtpTestResult['success'] ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200' ?>">
                    <div class="flex items-center gap-2 mb-2 font-bold text-xs <?= $smtpTestResult['success'] ? 'text-emerald-800' : 'text-red-800' ?>">
                        <i data-lucide="<?= $smtpTestResult['success'] ? 'check-circle' : 'alert-circle' ?>" class="w-4 h-4"></i>
                        <span><?= $smtpTestResult['success'] ? 'SMTP Test Passed!' : 'SMTP Connection Diagnostic Trace' ?></span>
                    </div>
                    <?php if (!empty($smtpTestResult['log'])): ?>
                    <pre class="bg-black/90 text-emerald-400 text-[11px] p-3 rounded-xl overflow-x-auto font-mono max-h-48 whitespace-pre-wrap"><?= htmlspecialchars($smtpTestResult['log']) ?></pre>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Business Coordinates -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200/90 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <div class="p-2 rounded-xl bg-[#071D29]/5 text-[#071D29]">
                    <i data-lucide="building" class="w-5 h-5"></i>
                </div>
                <h3 class="text-lg font-bold text-[#092532]">Advisor &amp; Business Coordinates</h3>
            </div>
            <p class="text-xs text-[#607078] mb-6">These values automatically update the public website headers, footer, and booking cards.</p>

            <!-- Advisor Portrait Photo Preview Strip -->
            <div class="mb-6 p-4 rounded-2xl bg-[#F7F8F5] border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="<?= htmlspecialchars(getFounderPhotoSrc('../')) ?>" 
                         onerror="this.onerror=null; this.src='../assets/img/saswata-roy.svg';" 
                         alt="Advisor Photo" 
                         class="w-16 h-20 rounded-xl object-cover object-top border-2 border-white shadow-md bg-gray-900 shrink-0">
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-xs font-bold text-[#092532] uppercase">Advisor Portrait Photo</h4>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">Active on Frontend</span>
                        </div>
                        <p class="text-[11px] text-[#607078] mt-1">Featured in homepage "About Saswata Roy" section and consultation booking cards.</p>
                    </div>
                </div>
                <a href="profile.php#photo-section" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] px-4 py-2.5 rounded-xl transition-all shadow-sm shrink-0">
                    <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                    <span>Update Photo in Profile &rarr;</span>
                </a>
            </div>

            <form method="POST" action="settings.php" class="space-y-4">
                <?= csrfInput() ?>
                <input type="hidden" name="action" value="save_settings">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Founder / Advisor Name</label>
                        <input type="text" name="founder_name" value="<?= htmlspecialchars($settings['founder_name'] ?? 'Saswata Roy') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">AMFI ARN Number</label>
                        <input type="text" name="arn_number" value="<?= htmlspecialchars($settings['arn_number'] ?? 'ARN- 136048') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Direct Calling Number</label>
                        <input type="text" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone'] ?? '6291390883') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">WhatsApp Number (e.g. 916291390883)</label>
                        <input type="text" name="contact_whatsapp" value="<?= htmlspecialchars($settings['contact_whatsapp'] ?? '916291390883') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Contact Email</label>
                        <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? 'contact@moneyguru.co.in') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#092532] uppercase mb-1">Office Location</label>
                        <input type="text" name="office_location" value="<?= htmlspecialchars($settings['office_location'] ?? 'Kolkata, West Bengal, India') ?>" class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs sm:text-sm">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-bold text-xs px-6 py-2.5 rounded-xl shadow-md transition-all">
                        Save Business Coordinates
                    </button>
                </div>
            </form>
        </div>

    </main>

    <script>
        lucide.createIcons();

        function syncMirrors() {
            document.getElementById('mirror_smtp_host').value = document.getElementById('smtp_host').value;
            document.getElementById('mirror_smtp_port').value = document.getElementById('smtp_port').value;
            document.getElementById('mirror_smtp_encryption').value = document.getElementById('smtp_encryption').value;
            document.getElementById('mirror_smtp_user').value = document.getElementById('smtp_user').value;
            document.getElementById('mirror_smtp_pass').value = document.getElementById('smtp_pass').value;
            document.getElementById('mirror_smtp_from_email').value = document.getElementById('smtp_from_email').value;
            document.getElementById('mirror_smtp_from_name').value = document.getElementById('smtp_from_name').value;
        }

        function togglePasswordVisibility(fieldId, btn) {
            const field = document.getElementById(fieldId);
            if (!field) return;
            const isPassword = field.type === 'password';
            field.type = isPassword ? 'text' : 'password';
            btn.innerHTML = isPassword ? '<i data-lucide="eye-off" class="w-4 h-4"></i>' : '<i data-lucide="eye" class="w-4 h-4"></i>';
            lucide.createIcons();
        }
    </script>
</body>
</html>
