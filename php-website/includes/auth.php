<?php
/**
 * Enhanced Authentication System
 * Courier Management System - PHP Version
 */

require_once __DIR__ . '/../config/database.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    private static $db;
    
    public static function init() {
        self::$db = Database::getInstance()->getConnection();
    }
    
    /**
     * Authenticate user with email and password
     */
    public static function login($email, $password) {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_city'] = $user['city'];
            $_SESSION['is_authenticated'] = true;
            $_SESSION['login_time'] = time();
            
            // Update last login
            $updateStmt = self::$db->prepare("UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            return true;
        }
        return false;
    }
    
    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated() {
        return isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] === true;
    }
    
    /**
     * Get current user data
     */
    public static function getCurrentUser() {
        if (!self::isAuthenticated()) {
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
    public static function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Check if user has specific role
     */
    public static function hasRole($role) {
        return self::getUserRole() === $role;
    }
    
    /**
     * Check if user has any of the specified roles
     */
    public static function hasAnyRole($roles) {
        $userRole = self::getUserRole();
        return in_array($userRole, $roles);
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        session_unset();
        session_destroy();
        return true;
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Require authentication
     */
    public static function requireAuth() {
        if (!self::isAuthenticated()) {
            header('Location: /php-website/login.php');
            exit();
        }
    }
    
    /**
     * Require specific role
     */
    public static function requireRole($role) {
        self::requireAuth();
        if (!self::hasRole($role)) {
            header('Location: /php-website/unauthorized.php');
            exit();
        }
    }
    
    /**
     * Require any of specified roles
     */
    public static function requireAnyRole($roles) {
        self::requireAuth();
        if (!self::hasAnyRole($roles)) {
            header('Location: /php-website/unauthorized.php');
            exit();
        }
    }
    
    /**
     * Get redirect URL based on user role
     */
    public static function getRoleRedirectUrl($role = null) {
        $role = $role ?? self::getUserRole();
        
        switch ($role) {
            case 'admin':
                return '/php-website/admin/dashboard.php';
            case 'agent':
                return '/php-website/agent/dashboard.php';
            case 'user':
                return '/php-website/user/dashboard.php';
            default:
                return '/php-website/login.php';
        }
    }
    
    /**
     * Set flash message
     */
    public static function setFlashMessage($type, $message) {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Get and clear flash message
     */
    public static function getFlashMessage() {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $message;
        }
        return null;
    }
    
    /**
     * Register new user
     */
    public static function register($name, $email, $password, $role = 'user', $phone = null, $city = null) {
        try {
            // Check if email already exists
            $stmt = self::$db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Email already exists'];
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user
            $stmt = self::$db->prepare("INSERT INTO users (name, email, password, role, phone, city) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword, $role, $phone, $city]);
            
            return ['success' => true, 'message' => 'User registered successfully'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
        }
    }
    
    /**
     * Update user profile
     */
    public static function updateProfile($userId, $data) {
        try {
            $allowedFields = ['name', 'phone', 'city'];
            $updateFields = [];
            $values = [];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updateFields[] = "{$field} = ?";
                    $values[] = $data[$field];
                }
            }
            
            if (empty($updateFields)) {
                return ['success' => false, 'message' => 'No valid fields to update'];
            }
            
            $values[] = $userId;
            $sql = "UPDATE users SET " . implode(', ', $updateFields) . ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            
            $stmt = self::$db->prepare($sql);
            $stmt->execute($values);
            
            // Update session data
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $_SESSION["user_{$field}"] = $data[$field];
                }
            }
            
            return ['success' => true, 'message' => 'Profile updated successfully'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Update failed: ' . $e->getMessage()];
        }
    }
    
    /**
     * Change password
     */
    public static function changePassword($userId, $currentPassword, $newPassword) {
        try {
            // Verify current password
            $stmt = self::$db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if (!$user || !password_verify($currentPassword, $user['password'])) {
                return ['success' => false, 'message' => 'Current password is incorrect'];
            }
            
            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = self::$db->prepare("UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$hashedPassword, $userId]);
            
            return ['success' => true, 'message' => 'Password changed successfully'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Password change failed: ' . $e->getMessage()];
        }
    }
}

// Initialize Auth system
Auth::init();
?>