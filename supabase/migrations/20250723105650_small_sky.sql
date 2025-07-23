-- Courier Management System - Complete MySQL Database Schema
-- Created: 2024

-- Create database
CREATE DATABASE IF NOT EXISTS courier_management_system;
USE courier_management_system;

-- Drop tables if they exist (for clean setup)
DROP TABLE IF EXISTS courier_tracking_history;
DROP TABLE IF EXISTS courier_assignments;
DROP TABLE IF EXISTS couriers;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS agents;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS cities;

-- Cities table
CREATE TABLE cities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    state VARCHAR(50),
    country VARCHAR(50) DEFAULT 'USA',
    postal_code VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users table (for authentication)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'agent', 'user') NOT NULL DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
);

-- Agents table (extends users for agents)
CREATE TABLE agents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    agent_code VARCHAR(20) UNIQUE,
    city_id INT,
    working_hours VARCHAR(50) DEFAULT '9to5',
    max_daily_orders INT DEFAULT 20,
    availability BOOLEAN DEFAULT TRUE,
    auto_assign BOOLEAN DEFAULT FALSE,
    total_couriers INT DEFAULT 0,
    success_rate DECIMAL(5,2) DEFAULT 0.00,
    rating DECIMAL(3,2) DEFAULT 0.00,
    joined_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL,
    INDEX idx_agent_code (agent_code),
    INDEX idx_city (city_id),
    INDEX idx_availability (availability)
);

-- Customers table
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    customer_code VARCHAR(20) UNIQUE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    default_address TEXT,
    billing_address TEXT,
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('active', 'inactive') DEFAULT 'active',
    preferred_delivery_time VARCHAR(50) DEFAULT 'anytime',
    package_instructions TEXT,
    registered_date DATE,
    last_order_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_customer_code (customer_code),
    INDEX idx_email (email),
    INDEX idx_status (status)
);

-- Couriers table (main shipments table)
CREATE TABLE couriers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tracking_number VARCHAR(50) NOT NULL UNIQUE,
    sender_name VARCHAR(255) NOT NULL,
    sender_phone VARCHAR(20),
    sender_address TEXT,
    receiver_name VARCHAR(255) NOT NULL,
    receiver_phone VARCHAR(20),
    receiver_address TEXT,
    pickup_city_id INT,
    delivery_city_id INT,
    pickup_city VARCHAR(100) NOT NULL,
    delivery_city VARCHAR(100) NOT NULL,
    courier_type ENUM('standard', 'express', 'overnight', 'same-day') DEFAULT 'standard',
    weight DECIMAL(8,2) DEFAULT 0.00,
    dimensions VARCHAR(100),
    package_value DECIMAL(10,2) DEFAULT 0.00,
    delivery_fee DECIMAL(8,2) DEFAULT 0.00,
    status ENUM('pending', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    delivery_date DATE,
    actual_delivery_date DATE,
    pickup_date DATE,
    estimated_delivery_time TIMESTAMP,
    special_instructions TEXT,
    notes TEXT,
    created_by INT,
    assigned_agent_id INT,
    customer_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (pickup_city_id) REFERENCES cities(id) ON DELETE SET NULL,
    FOREIGN KEY (delivery_city_id) REFERENCES cities(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_agent_id) REFERENCES agents(id) ON DELETE SET NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    
    INDEX idx_tracking_number (tracking_number),
    INDEX idx_status (status),
    INDEX idx_courier_type (courier_type),
    INDEX idx_pickup_city (pickup_city_id),
    INDEX idx_delivery_city (delivery_city_id),
    INDEX idx_created_by (created_by),
    INDEX idx_assigned_agent (assigned_agent_id),
    INDEX idx_delivery_date (delivery_date),
    INDEX idx_created_at (created_at)
);

-- Courier assignments table (for tracking agent assignments)
CREATE TABLE courier_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    courier_id INT NOT NULL,
    agent_id INT NOT NULL,
    assigned_by INT,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    notes TEXT,
    
    FOREIGN KEY (courier_id) REFERENCES couriers(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_courier (courier_id),
    INDEX idx_agent (agent_id),
    INDEX idx_status (status)
);

-- Courier tracking history table (for status updates and tracking)
CREATE TABLE courier_tracking_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    courier_id INT NOT NULL,
    status ENUM('pending', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed_delivery', 'cancelled') NOT NULL,
    location VARCHAR(255),
    description TEXT,
    updated_by INT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (courier_id) REFERENCES couriers(id) ON DELETE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_courier (courier_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Notifications table
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    courier_id INT,
    type ENUM('courier_update', 'delivery_alert', 'system_notification', 'promotional') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (courier_id) REFERENCES couriers(id) ON DELETE CASCADE,
    
    INDEX idx_user (user_id),
    INDEX idx_courier (courier_id),
    INDEX idx_is_read (is_read),
    INDEX idx_type (type)
);

-- Settings table for system configuration
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    key_name VARCHAR(100) NOT NULL UNIQUE,
    value TEXT,
    description TEXT,
    type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_key_name (key_name)
);

-- Reports table for storing generated reports
CREATE TABLE reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    type ENUM('daily', 'weekly', 'monthly', 'custom') NOT NULL,
    generated_by INT NOT NULL,
    date_from DATE,
    date_to DATE,
    parameters JSON,
    file_path VARCHAR(500),
    status ENUM('generating', 'completed', 'failed') DEFAULT 'generating',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_type (type),
    INDEX idx_generated_by (generated_by),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- User preferences table
CREATE TABLE user_preferences (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    email_notifications BOOLEAN DEFAULT TRUE,
    sms_notifications BOOLEAN DEFAULT FALSE,
    push_notifications BOOLEAN DEFAULT TRUE,
    order_updates BOOLEAN DEFAULT TRUE,
    system_alerts BOOLEAN DEFAULT TRUE,
    marketing_emails BOOLEAN DEFAULT FALSE,
    delivery_alerts BOOLEAN DEFAULT TRUE,
    theme VARCHAR(20) DEFAULT 'light',
    language VARCHAR(10) DEFAULT 'en',
    timezone VARCHAR(50) DEFAULT 'America/New_York',
    currency VARCHAR(10) DEFAULT 'USD',
    date_format VARCHAR(20) DEFAULT 'MM/DD/YYYY',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_preferences (user_id)
);

-- Create triggers for updating counters
DELIMITER //

-- Trigger to update agent's total_couriers when a courier is assigned
CREATE TRIGGER update_agent_courier_count_insert
AFTER INSERT ON couriers
FOR EACH ROW
BEGIN
    IF NEW.assigned_agent_id IS NOT NULL THEN
        UPDATE agents 
        SET total_couriers = total_couriers + 1 
        WHERE id = NEW.assigned_agent_id;
    END IF;
END//

-- Trigger to update agent's total_couriers when assignment changes
CREATE TRIGGER update_agent_courier_count_update
AFTER UPDATE ON couriers
FOR EACH ROW
BEGIN
    -- Decrease count for old agent
    IF OLD.assigned_agent_id IS NOT NULL AND OLD.assigned_agent_id != NEW.assigned_agent_id THEN
        UPDATE agents 
        SET total_couriers = total_couriers - 1 
        WHERE id = OLD.assigned_agent_id;
    END IF;
    
    -- Increase count for new agent
    IF NEW.assigned_agent_id IS NOT NULL AND OLD.assigned_agent_id != NEW.assigned_agent_id THEN
        UPDATE agents 
        SET total_couriers = total_couriers + 1 
        WHERE id = NEW.assigned_agent_id;
    END IF;
END//

-- Trigger to update customer's total_orders and total_spent
CREATE TRIGGER update_customer_stats_insert
AFTER INSERT ON couriers
FOR EACH ROW
BEGIN
    IF NEW.customer_id IS NOT NULL THEN
        UPDATE customers 
        SET total_orders = total_orders + 1,
            last_order_date = CURDATE()
        WHERE id = NEW.customer_id;
    END IF;
END//

DELIMITER ;