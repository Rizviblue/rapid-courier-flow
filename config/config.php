<?php
/**
 * Application Configuration
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define constants
define('APP_NAME', 'CourierPro');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/courier-management-php');
define('ASSETS_URL', BASE_URL . '/assets');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'courier_management');
define('DB_USER', 'root');
define('DB_PASS', '');

// Security settings
define('HASH_ALGO', PASSWORD_DEFAULT);
define('SESSION_LIFETIME', 3600); // 1 hour

// File upload settings
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// Pagination settings
define('ITEMS_PER_PAGE', 10);

// Email settings (for future use)
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');

// Timezone
date_default_timezone_set('America/New_York');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include autoloader
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Helper functions
function redirect($url) {
    header("Location: $url");
    exit();
}

function flash($key, $message = null) {
    if ($message === null) {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    $_SESSION['flash'][$key] = $message;
}

function old($key, $default = '') {
    return $_SESSION['old'][$key] ?? $default;
}

function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function format_date($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

function format_currency($amount) {
    return '$' . number_format($amount, 2);
}

function generate_tracking_number() {
    return 'CMS' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
}