<?php
session_start();

// Database configuration
require_once 'config/database.php';
require_once 'config/app.php';

// Auto-load classes
spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Include utility functions
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Get the requested page
$page = $_GET['page'] ?? 'login';
$role = $_SESSION['user_role'] ?? null;

// Route to appropriate page based on authentication and role
if (!isLoggedIn() && !in_array($page, ['login', 'register', 'support', 'forgot-password'])) {
    header('Location: index.php?page=login');
    exit;
}

// Include header
include 'includes/header.php';

// Route to appropriate content
switch ($page) {
    case 'login':
        if (isLoggedIn()) {
            redirectToDashboard($role);
        }
        include 'pages/login.php';
        break;
        
    case 'register':
        if (isLoggedIn()) {
            redirectToDashboard($role);
        }
        include 'pages/register.php';
        break;
        
    case 'logout':
        logout();
        break;
        
    case 'support':
        include 'pages/support.php';
        break;
        
    case 'forgot-password':
        include 'pages/forgot-password.php';
        break;
        
    // Admin routes
    case 'admin-dashboard':
        requireRole('admin');
        include 'pages/admin/dashboard.php';
        break;
        
    case 'admin-add-courier':
        requireRole(['admin', 'agent']);
        include 'pages/admin/add-courier.php';
        break;
        
    case 'admin-couriers':
        requireRole(['admin', 'agent']);
        include 'pages/admin/courier-list.php';
        break;
        
    case 'admin-agents':
        requireRole('admin');
        include 'pages/admin/agent-management.php';
        break;
        
    case 'admin-customers':
        requireRole('admin');
        include 'pages/admin/customer-management.php';
        break;
        
    case 'admin-reports':
        requireRole(['admin', 'agent']);
        include 'pages/admin/reports.php';
        break;
        
    case 'admin-analytics':
        requireRole('admin');
        include 'pages/admin/analytics.php';
        break;
        
    case 'admin-settings':
        requireRole(['admin', 'agent']);
        include 'pages/admin/settings.php';
        break;
        
    // Agent routes
    case 'agent-dashboard':
        requireRole('agent');
        include 'pages/agent/dashboard.php';
        break;
        
    case 'agent-reports':
        requireRole('agent');
        include 'pages/agent/reports.php';
        break;
        
    case 'agent-settings':
        requireRole('agent');
        include 'pages/agent/settings.php';
        break;
        
    // User routes
    case 'user-dashboard':
        requireRole('user');
        include 'pages/user/dashboard.php';
        break;
        
    case 'user-packages':
        requireRole('user');
        include 'pages/user/packages.php';
        break;
        
    case 'user-settings':
        requireRole('user');
        include 'pages/user/settings.php';
        break;
        
    // API endpoints
    case 'api':
        $action = $_GET['action'] ?? '';
        include 'api/handler.php';
        break;
        
    default:
        if (isLoggedIn()) {
            redirectToDashboard($role);
        } else {
            header('Location: index.php?page=login');
        }
        break;
}

// Include footer
include 'includes/footer.php';
?>