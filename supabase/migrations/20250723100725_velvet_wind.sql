-- Courier Management System Database Schema
-- MySQL Database Structure

CREATE DATABASE IF NOT EXISTS courier_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE courier_management;

-- Users table (for authentication)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'agent', 'user') NOT NULL DEFAULT 'user',
    phone VARCHAR(20) NULL,
    city VARCHAR(100) NULL,
    avatar VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
);

-- Couriers table
CREATE TABLE couriers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tracking_number VARCHAR(20) UNIQUE NOT NULL,
    sender_name VARCHAR(255) NOT NULL,
    sender_phone VARCHAR(20) NULL,
    sender_address TEXT NULL,
    receiver_name VARCHAR(255) NOT NULL,
    receiver_phone VARCHAR(20) NULL,
    receiver_address TEXT NULL,
    pickup_city VARCHAR(100) NOT NULL,
    delivery_city VARCHAR(100) NOT NULL,
    courier_type VARCHAR(50) NOT NULL DEFAULT 'standard',
    weight DECIMAL(8,2) DEFAULT 0.00,
    delivery_date DATE NULL,
    status ENUM('pending', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending',
    notes TEXT NULL,
    created_by INT NULL,
    assigned_agent INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_agent) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_tracking (tracking_number),
    INDEX idx_status (status),
    INDEX idx_cities (pickup_city, delivery_city),
    INDEX idx_created_by (created_by),
    INDEX idx_assigned_agent (assigned_agent)
);

-- Agents table (extended user info for agents)
CREATE TABLE agents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    total_couriers INT DEFAULT 0,
    completed_deliveries INT DEFAULT 0,
    rating DECIMAL(3,2) DEFAULT 0.00,
    status ENUM('active', 'inactive') DEFAULT 'active',
    working_hours VARCHAR(50) DEFAULT '9to5',
    max_daily_orders INT DEFAULT 20,
    availability BOOLEAN DEFAULT TRUE,
    auto_assign BOOLEAN DEFAULT FALSE,
    joined_date DATE DEFAULT (CURRENT_DATE),
    last_active TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_availability (availability)
);

-- Customers table (extended user info for customers)
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    last_order_date DATE NULL,
    default_address TEXT NULL,
    billing_address TEXT NULL,
    preferred_delivery_time VARCHAR(50) DEFAULT 'anytime',
    special_instructions TEXT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status)
);

-- User settings table
CREATE TABLE user_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_setting (user_id, setting_key),
    INDEX idx_user_setting (user_id, setting_key)
);

-- Notifications table
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_unread (user_id, is_read),
    INDEX idx_created_at (created_at)
);

-- Activity logs table
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_action (user_id, action),
    INDEX idx_created_at (created_at)
);

-- Insert demo data
INSERT INTO users (name, email, password, role, phone, city) VALUES
('John Smith', 'admin@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '+1 234 567 8900', 'New York'),
('Sarah Johnson', 'agent@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', '+1 234 567 8901', 'New York'),
('Michael Chen', 'agent2@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', '+1 234 567 8902', 'Los Angeles'),
('Mike Wilson', 'user@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '+1 234 567 8903', 'Chicago'),
('Emma Davis', 'user2@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '+1 234 567 8904', 'Miami');

-- Insert demo couriers
INSERT INTO couriers (tracking_number, sender_name, sender_phone, receiver_name, receiver_phone, pickup_city, delivery_city, courier_type, weight, delivery_date, status, created_by, assigned_agent) VALUES
('CMS001234', 'John Doe', '+1 555 0101', 'Alice Smith', '+1 555 0201', 'New York', 'Los Angeles', 'express', 2.50, '2024-01-25', 'in_transit', 1, 2),
('CMS001235', 'Bob Johnson', '+1 555 0102', 'Carol Brown', '+1 555 0202', 'Chicago', 'Miami', 'standard', 1.20, '2024-01-24', 'delivered', 2, 2),
('CMS001236', 'David Wilson', '+1 555 0103', 'Eva Davis', '+1 555 0203', 'Houston', 'Seattle', 'express', 3.80, '2024-01-26', 'pending', 1, 3),
('CMS001237', 'Frank Miller', '+1 555 0104', 'Grace Lee', '+1 555 0204', 'Phoenix', 'Boston', 'standard', 0.80, '2024-01-23', 'cancelled', 2, 2),
('CMS001238', 'Henry Clark', '+1 555 0105', 'Ivy Martinez', '+1 555 0205', 'Dallas', 'San Francisco', 'overnight', 1.50, '2024-01-27', 'in_transit', 1, 3);

-- Insert demo agents
INSERT INTO agents (user_id, total_couriers, completed_deliveries, rating, status) VALUES
(2, 156, 142, 4.80, 'active'),
(3, 134, 121, 4.70, 'active');

-- Insert demo customers
INSERT INTO customers (user_id, total_orders, total_spent, last_order_date) VALUES
(4, 23, 1250.00, '2024-01-20'),
(5, 18, 890.00, '2024-01-18');

-- Insert demo notifications
INSERT INTO notifications (user_id, title, message, type) VALUES
(2, 'New courier assigned', 'CMS001234 has been assigned to you', 'info'),
(2, 'Delivery completed', 'Package CMS001235 delivered successfully', 'success'),
(4, 'Package in transit', 'Your package CMS001234 is now in transit', 'info');