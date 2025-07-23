<?php

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $db = getDB();
    $user = $db->fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
    return $user;
}

function login($email, $password) {
    $db = getDB();
    $user = $db->fetch("SELECT * FROM users WHERE email = ? AND status = 'active'", [$email]);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }
    
    return false;
}

function logout() {
    session_destroy();
    header('Location: index.php?page=login&logout=1');
    exit;
}

function requireRole($allowedRoles) {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $userRole = $_SESSION['user_role'];
    
    if (is_array($allowedRoles)) {
        if (!in_array($userRole, $allowedRoles)) {
            header('Location: index.php?page=unauthorized');
            exit;
        }
    } else {
        if ($userRole !== $allowedRoles) {
            header('Location: index.php?page=unauthorized');
            exit;
        }
    }
}

function redirectToDashboard($role) {
    switch ($role) {
        case 'admin':
            header('Location: index.php?page=admin-dashboard');
            break;
        case 'agent':
            header('Location: index.php?page=agent-dashboard');
            break;
        case 'user':
            header('Location: index.php?page=user-dashboard');
            break;
        default:
            header('Location: index.php?page=login');
    }
    exit;
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function generateTrackingNumber() {
    $prefix = 'CMS';
    $number = mt_rand(100000, 999999);
    return $prefix . $number;
}

function hasRole($role) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $userRole = $_SESSION['user_role'];
    
    if (is_array($role)) {
        return in_array($userRole, $role);
    }
    
    return $userRole === $role;
}
?>