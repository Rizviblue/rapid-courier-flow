<?php
/**
 * Authentication Class
 */

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function login($email, $password) {
        $user = $this->db->fetch(
            "SELECT * FROM users WHERE email = ? AND is_active = 1",
            [$email]
        );
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'phone' => $user['phone'],
                'city' => $user['city']
            ];
            
            // Update last login
            $this->db->query(
                "UPDATE users SET updated_at = NOW() WHERE id = ?",
                [$user['id']]
            );
            
            return true;
        }
        
        return false;
    }
    
    public function register($data) {
        // Check if email already exists
        $existing = $this->db->fetch(
            "SELECT id FROM users WHERE email = ?",
            [$data['email']]
        );
        
        if ($existing) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        // Hash password
        $hashedPassword = password_hash($data['password'], HASH_ALGO);
        
        try {
            $this->db->beginTransaction();
            
            // Insert user
            $this->db->query(
                "INSERT INTO users (name, email, password, role, phone, city) VALUES (?, ?, ?, ?, ?, ?)",
                [
                    $data['name'],
                    $data['email'],
                    $hashedPassword,
                    $data['role'],
                    $data['phone'] ?? null,
                    $data['city'] ?? null
                ]
            );
            
            $userId = $this->db->lastInsertId();
            
            // Create role-specific record
            if ($data['role'] === 'agent') {
                $this->db->query(
                    "INSERT INTO agents (user_id) VALUES (?)",
                    [$userId]
                );
            } elseif ($data['role'] === 'user') {
                $this->db->query(
                    "INSERT INTO customers (user_id) VALUES (?)",
                    [$userId]
                );
            }
            
            $this->db->commit();
            
            return ['success' => true, 'message' => 'Registration successful'];
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
        }
    }
    
    public function logout() {
        session_destroy();
        return true;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }
    
    public function user() {
        return $_SESSION['user'] ?? null;
    }
    
    public function hasRole($role) {
        $user = $this->user();
        return $user && $user['role'] === $role;
    }
    
    public function hasAnyRole($roles) {
        $user = $this->user();
        return $user && in_array($user['role'], $roles);
    }
    
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            redirect(BASE_URL . '/login.php');
        }
    }
    
    public function requireRole($role) {
        $this->requireAuth();
        if (!$this->hasRole($role)) {
            redirect(BASE_URL . '/unauthorized.php');
        }
    }
    
    public function requireAnyRole($roles) {
        $this->requireAuth();
        if (!$this->hasAnyRole($roles)) {
            redirect(BASE_URL . '/unauthorized.php');
        }
    }
}