<?php
if (session_status() === PHP_SESSION_NONE) session_start();

/**
 * Simple CSRF helper
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

function csrf_input() {
    $token = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return "<input type=\"hidden\" name=\"_csrf\" value=\"$token\">";
}

function verify_csrf($token) {
    if (empty($_SESSION['csrf_token'])) return false;
    $valid = hash_equals($_SESSION['csrf_token'], (string)$token);
    // Optionally expire token after 24 hours
    if ($valid && isset($_SESSION['csrf_token_time']) && (time() - $_SESSION['csrf_token_time']) > 86400) {
        $valid = false;
    }
    return $valid;
}
?>