<?php
/**
 * Database Configuration
 * Courier Management System
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Database credentials
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'courier_management';
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            // For demo purposes, we'll use SQLite if MySQL is not available
            try {
                $this->connection = new PDO(
                    "sqlite:" . __DIR__ . "/../data/courier.db",
                    null,
                    null,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                $this->createTables();
            } catch (PDOException $e2) {
                die("Database connection failed: " . $e2->getMessage());
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    private function createTables() {
        $queries = [
            "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(20) NOT NULL DEFAULT 'user',
                phone VARCHAR(20),
                city VARCHAR(100),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            
            "CREATE TABLE IF NOT EXISTS couriers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                tracking_number VARCHAR(50) UNIQUE NOT NULL,
                sender_name VARCHAR(255) NOT NULL,
                sender_email VARCHAR(255) NOT NULL,
                sender_phone VARCHAR(20) NOT NULL,
                recipient_name VARCHAR(255) NOT NULL,
                recipient_email VARCHAR(255) NOT NULL,
                recipient_phone VARCHAR(20) NOT NULL,
                pickup_address TEXT NOT NULL,
                delivery_address TEXT NOT NULL,
                weight DECIMAL(8,2) NOT NULL,
                package_type VARCHAR(100) NOT NULL,
                priority VARCHAR(20) DEFAULT 'standard',
                status VARCHAR(20) DEFAULT 'pending',
                agent_id INTEGER,
                estimated_delivery DATE,
                actual_delivery DATE,
                cost DECIMAL(10,2) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (agent_id) REFERENCES users(id)
            )",
            
            "CREATE TABLE IF NOT EXISTS courier_tracking (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                courier_id INTEGER NOT NULL,
                status VARCHAR(50) NOT NULL,
                location VARCHAR(255),
                notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (courier_id) REFERENCES couriers(id)
            )"
        ];
        
        foreach ($queries as $query) {
            $this->connection->exec($query);
        }
        
        $this->insertDemoData();
    }
    
    private function insertDemoData() {
        // Insert demo users if they don't exist
        $users = [
            ['John Smith', 'admin@courierpro.com', password_hash('password', PASSWORD_DEFAULT), 'admin', '+1 555 0101', 'New York'],
            ['Sarah Johnson', 'agent@courierpro.com', password_hash('password', PASSWORD_DEFAULT), 'agent', '+1 555 0102', 'Los Angeles'],
            ['Mike Wilson', 'user@courierpro.com', password_hash('password', PASSWORD_DEFAULT), 'user', '+1 234 567 8900', 'Chicago']
        ];
        
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $insertStmt = $this->connection->prepare("INSERT INTO users (name, email, password, role, phone, city) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($users as $user) {
            $stmt->execute([$user[1]]);
            if ($stmt->fetchColumn() == 0) {
                $insertStmt->execute($user);
            }
        }
    }
}

// Initialize database connection
$db = Database::getInstance();
?>