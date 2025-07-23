<?php
// Application Configuration

define('APP_NAME', 'CourierPro');
define('APP_URL', 'http://localhost/courier-management');
define('APP_VERSION', '1.0.0');

// Timezone
date_default_timezone_set('UTC');

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_PATH', 'uploads/');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Function to get base URL
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script);
    return $protocol . $host . $path;
}

// Function to create URL
function url($path = '') {
    $baseUrl = getBaseUrl();
    if ($path) {
        return $baseUrl . '/' . ltrim($path, '/');
    }
    return $baseUrl;
}

// Function to create page URL
function pageUrl($page, $params = []) {
    $url = 'index.php?page=' . $page;
    if (!empty($params)) {
        $url .= '&' . http_build_query($params);
    }
    return $url;
}
?>