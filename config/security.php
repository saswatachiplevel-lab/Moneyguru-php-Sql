<?php
/**
 * Moneyguru Financial Services - Production Security Layer
 * Handles CSRF tokens, secure session management, rate-limiting, and security headers.
 */

// 1. Production Error Reporting & Logging
if (getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

/**
 * Send standard HTTP security headers
 */
function sendSecurityHeaders() {
    if (headers_sent()) return;

    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
}

/**
 * Initialize a hardened, secure PHP session
 */
function initSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_strict_mode', '1');
        
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        session_start();
    }
}

/**
 * Generate or retrieve the CSRF token for the current session
 */
function getCsrfToken(): string {
    initSecureSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output a hidden HTML CSRF input field
 */
function csrfInput(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(getCsrfToken(), ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verify submitted CSRF token
 */
function verifyCsrfToken(?string $token): bool {
    initSecureSession();
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Rate Limiter for Login Attempts
 * Limits failed attempts to 5 per 15 minutes per IP address/email.
 */
function checkLoginRateLimit(string $key): array {
    initSecureSession();
    $maxAttempts = 5;
    $lockoutTime = 900; // 15 minutes

    $attemptsKey = 'login_attempts_' . md5($key);
    $data = $_SESSION[$attemptsKey] ?? ['count' => 0, 'first_attempt' => time()];

    // Reset if window has elapsed
    if (time() - $data['first_attempt'] > $lockoutTime) {
        $_SESSION[$attemptsKey] = ['count' => 0, 'first_attempt' => time()];
        return ['allowed' => true, 'remaining' => $maxAttempts, 'wait_seconds' => 0];
    }

    if ($data['count'] >= $maxAttempts) {
        $remainingWait = $lockoutTime - (time() - $data['first_attempt']);
        return [
            'allowed'      => false,
            'remaining'    => 0,
            'wait_seconds' => max(1, $remainingWait),
            'wait_minutes' => ceil($remainingWait / 60)
        ];
    }

    return [
        'allowed'      => true,
        'remaining'    => $maxAttempts - $data['count'],
        'wait_seconds' => 0
    ];
}

/**
 * Record a failed login attempt
 */
function recordFailedLogin(string $key) {
    initSecureSession();
    $attemptsKey = 'login_attempts_' . md5($key);
    $data = $_SESSION[$attemptsKey] ?? ['count' => 0, 'first_attempt' => time()];

    // If previous window elapsed, start fresh
    if (time() - $data['first_attempt'] > 900) {
        $data = ['count' => 1, 'first_attempt' => time()];
    } else {
        $data['count'] = ($data['count'] ?? 0) + 1;
    }

    $_SESSION[$attemptsKey] = $data;
}

/**
 * Reset failed login attempts on successful authentication
 */
function clearLoginAttempts(string $key) {
    initSecureSession();
    $attemptsKey = 'login_attempts_' . md5($key);
    unset($_SESSION[$attemptsKey]);
}

/**
 * Sanitize plain string output
 */
function e($val): string {
    return htmlspecialchars((string)($val ?? ''), ENT_QUOTES, 'UTF-8');
}
