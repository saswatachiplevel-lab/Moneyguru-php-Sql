<?php
/**
 * Admin Authentication & Session Integrity Check
 * Enforces secure session parameters, inactivity timeouts, and fingerprint verification.
 */
require_once __DIR__ . '/../config/security.php';
initSecureSession();
sendSecurityHeaders();

// Check logged in state
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Check session inactivity timeout (2 Hours = 7200s)
$maxInactivity = 7200;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $maxInactivity)) {
    // Session expired
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: login.php?msg=session_expired");
    exit;
}
$_SESSION['last_activity'] = time();

// Verify user agent fingerprint to prevent session hijacking
$currentUserAgent = md5($_SERVER['HTTP_USER_AGENT'] ?? 'unknown_agent');
if (!isset($_SESSION['agent_fingerprint'])) {
    $_SESSION['agent_fingerprint'] = $currentUserAgent;
} elseif ($_SESSION['agent_fingerprint'] !== $currentUserAgent) {
    // Potential session hijacking attempt
    $_SESSION = [];
    session_destroy();
    header("Location: login.php?msg=security_warning");
    exit;
}
