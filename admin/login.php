<?php
/**
 * Moneyguru Admin - Secure Production Login
 * Authentication: Email and Password
 * Security: CSRF protection, brute-force rate-limiting, and session fixation prevention.
 */
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../config/db.php';

initSecureSession();
sendSecurityHeaders();

// If already authenticated, redirect to dashboard
if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';
$notice = '';
$inputEmail = '';

// Check URL status notifications
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'session_expired') {
        $notice = "Your session expired due to inactivity. Please sign in again.";
    } elseif ($_GET['msg'] === 'logged_out') {
        $notice = "You have been logged out safely.";
    } elseif ($_GET['msg'] === 'security_warning') {
        $notice = "Session refreshed for security purposes. Please sign in again.";
    }
}

$clientIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Rate Limiting Check
    $rate = checkLoginRateLimit($clientIp);
    if (!$rate['allowed']) {
        $error = "Too many failed attempts. Please wait {$rate['wait_minutes']} minute(s) before trying again.";
    } else {
        // 2. CSRF Token Verification
        $token = $_POST['csrf_token'] ?? '';
        if (!verifyCsrfToken($token)) {
            $error = "Security validation token expired. Please refresh the page and try again.";
        } else {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $inputEmail = $email;

            if (empty($email) || empty($password)) {
                $error = "Please enter both your email address and password.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Please enter a valid email address.";
            } else {
                $pdo = getDBConnection();
                if ($pdo) {
                    $stmt = $pdo->prepare("SELECT * FROM `admins` WHERE LOWER(`email`) = LOWER(:email) LIMIT 1");
                    $stmt->execute([':email' => $email]);
                    $admin = $stmt->fetch();

                    if ($admin && password_verify($password, $admin['password_hash'])) {
                        // Successful Login: Reset rate limit & regenerate session to prevent session fixation
                        clearLoginAttempts($clientIp);
                        session_regenerate_id(true);

                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $admin['id'];
                        $_SESSION['admin_email'] = $admin['email'];
                        $_SESSION['admin_username'] = $admin['username'] ?? '';
                        $_SESSION['admin_name'] = $admin['full_name'] ?? 'Administrator';
                        $_SESSION['admin_role'] = $admin['role'] ?? 'admin';
                        $_SESSION['last_activity'] = time();
                        $_SESSION['agent_fingerprint'] = md5($_SERVER['HTTP_USER_AGENT'] ?? 'unknown_agent');

                        header("Location: index.php");
                        exit;
                    } else {
                        // Failed Login Attempt
                        recordFailedLogin($clientIp);
                        $remaining = $rate['remaining'] - 1;
                        if ($remaining <= 2 && $remaining > 0) {
                            $error = "Invalid credentials. {$remaining} attempts remaining before temporary lockout.";
                        } else {
                            $error = "Invalid email address or password.";
                        }
                    }
                } else {
                    $error = "Unable to connect to database service. Please check server logs.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Moneyguru Financial Services</title>
    <link rel="icon" type="image/svg+xml" href="../assets/img/logo.svg">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #071D29; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden text-[#092532]">
    
    <!-- Ambient Background Glows -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#123B43]/50 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#C9F24A]/10 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white rounded-3xl p-8 sm:p-10 shadow-2xl relative z-10 border border-white/20">
        
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#071D29] text-[#C9F24A] mb-3 shadow-lg">
                <img src="../assets/img/logo.svg" alt="Moneyguru" class="w-9 h-9 rounded-full object-contain">
            </div>
            <h1 class="text-2xl font-black text-[#071D29] tracking-tight">Moneyguru Admin</h1>
            <p class="text-xs text-gray-500 mt-1">Advisor Portal &amp; Lead Management System</p>
        </div>

        <?php if (!empty($notice)): ?>
        <div class="mb-5 p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-800 flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 shrink-0 text-blue-600"></i>
            <span><?= htmlspecialchars($notice) ?></span>
        </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
        <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="login.php" class="space-y-4" autocomplete="off">
            <?= csrfInput() ?>

            <div>
                <label class="block text-xs font-bold text-[#092532] uppercase mb-1.5">Admin Email</label>
                <div class="relative">
                    <i data-lucide="mail" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="email" name="email" required value="<?= htmlspecialchars($inputEmail) ?>" placeholder="name@moneyguru.co.in" autofocus class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-[#092532] uppercase mb-1.5">Password</label>
                <div class="relative">
                    <i data-lucide="lock" class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="password" id="loginPassword" name="password" required value="" placeholder="••••••••" class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#071D29]">
                    <button type="button" onclick="togglePasswordVisibility('loginPassword', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Toggle password visibility">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#071D29] hover:bg-[#0B2733] text-[#C9F24A] font-extrabold text-sm py-3.5 rounded-xl shadow-lg transition-all active:scale-98">
                    <span>Sign In to Dashboard</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </form>

        <div class="mt-6 pt-5 border-t border-gray-100 text-center">
            <a href="../index.php" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#071D29] hover:underline">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Return to Public Website</span>
            </a>
        </div>

    </div>

    <script>
        lucide.createIcons();
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
