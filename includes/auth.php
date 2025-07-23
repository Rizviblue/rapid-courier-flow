<?php
// Authentication helper functions

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is authenticated
 */
function isAuthenticated() {
    return isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] === true;
}

/**
 * Get current user data
 */
function getCurrentUser() {
    if (!isAuthenticated()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['user_name'] ?? null,
        'email' => $_SESSION['user_email'] ?? null,
        'role' => $_SESSION['user_role'] ?? null,
        'phone' => $_SESSION['user_phone'] ?? null,
        'city' => $_SESSION['user_city'] ?? null
    ];
}

/**
 * Get user role
 */
function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

/**
 * Check if user has specific role
 */
function hasRole($role) {
    return getUserRole() === $role;
}

/**
 * Check if user has any of the specified roles
 */
function hasAnyRole($roles) {
    $userRole = getUserRole();
    return in_array($userRole, $roles);
}

/**
 * Logout user
 */
function logout() {
    session_unset();
    session_destroy();
    session_start();
}

/**
 * Redirect if not authenticated
 */
function requireAuth($redirectTo = 'login.php') {
    if (!isAuthenticated()) {
        header('Location: ' . $redirectTo);
        exit();
    }
}

/**
 * Redirect if not authorized (wrong role)
 */
function requireRole($allowedRoles, $redirectTo = 'unauthorized.php') {
    requireAuth();
    
    if (!is_array($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }
    
    if (!hasAnyRole($allowedRoles)) {
        header('Location: ' . $redirectTo);
        exit();
    }
}

/**
 * Demo users data
 */
function getDemoUsers() {
    return [
        'admin@courierpro.com' => [
            'id' => 1,
            'name' => 'John Smith',
            'email' => 'admin@courierpro.com',
            'role' => 'admin',
            'password' => 'password'
        ],
        'agent@courierpro.com' => [
            'id' => 2,
            'name' => 'Sarah Johnson',
            'email' => 'agent@courierpro.com',
            'role' => 'agent',
            'city' => 'New York',
            'password' => 'password'
        ],
        'user@courierpro.com' => [
            'id' => 3,
            'name' => 'Mike Wilson',
            'email' => 'user@courierpro.com',
            'role' => 'user',
            'phone' => '+1 234 567 8900',
            'password' => 'password'
        ]
    ];
}

/**
 * Authenticate user with email and password
 */
function authenticateUser($email, $password) {
    $users = getDemoUsers();
    
    if (isset($users[$email]) && $users[$email]['password'] === $password) {
        $user = $users[$email];
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['is_authenticated'] = true;
        
        // Set optional fields
        if (isset($user['phone'])) {
            $_SESSION['user_phone'] = $user['phone'];
        }
        if (isset($user['city'])) {
            $_SESSION['user_city'] = $user['city'];
        }
        
        return true;
    }
    
    return false;
}

/**
 * Get redirect URL based on user role
 */
function getRoleRedirectUrl($role) {
    return match($role) {
        'admin' => 'admin/dashboard.php',
        'agent' => 'agent/dashboard.php',
        'user' => 'user/dashboard.php',
        default => 'dashboard.php'
    };
}

/**
 * Demo login by role
 */
function demoLogin($role) {
    $demoUsers = [
        'admin' => [
            'id' => 1,
            'name' => 'John Smith',
            'email' => 'admin@courierpro.com',
            'role' => 'admin'
        ],
        'agent' => [
            'id' => 2,
            'name' => 'Sarah Johnson',
            'email' => 'agent@courierpro.com',
            'role' => 'agent',
            'city' => 'New York'
        ],
        'user' => [
            'id' => 3,
            'name' => 'Mike Wilson',
            'email' => 'user@courierpro.com',
            'role' => 'user',
            'phone' => '+1 234 567 8900'
        ]
    ];
    
    if (isset($demoUsers[$role])) {
        $user = $demoUsers[$role];
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['is_authenticated'] = true;
        
        // Set optional fields
        if (isset($user['phone'])) {
            $_SESSION['user_phone'] = $user['phone'];
        }
        if (isset($user['city'])) {
            $_SESSION['user_city'] = $user['city'];
        }
        
        return true;
    }
    
    return false;
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get flash message and clear it
 */
function getFlashMessage($type = null) {
    if ($type) {
        $message = $_SESSION['flash_' . $type] ?? null;
        unset($_SESSION['flash_' . $type]);
        return $message;
    }
    
    $messages = [];
    foreach (['success', 'error', 'warning', 'info'] as $type) {
        if (isset($_SESSION['flash_' . $type])) {
            $messages[$type] = $_SESSION['flash_' . $type];
            unset($_SESSION['flash_' . $type]);
        }
    }
    
    return $messages;
}

/**
 * Set flash message
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_' . $type] = $message;
}
?>