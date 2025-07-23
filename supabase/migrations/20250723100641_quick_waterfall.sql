-- Courier Management System Database Schema
-- Run this SQL to create the database structure

CREATE DATABASE IF NOT EXISTS courier_management;
USE courier_management;

-- Users table (for authentication)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'agent', 'user') NOT NULL,
    phone VARCHAR(20),
    city VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Couriers table
CREATE TABLE couriers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tracking_number VARCHAR(20) UNIQUE NOT NULL,
    sender_name VARCHAR(255) NOT NULL,
    receiver_name VARCHAR(255) NOT NULL,
    pickup_city VARCHAR(100) NOT NULL,
    delivery_city VARCHAR(100) NOT NULL,
    courier_type VARCHAR(50) NOT NULL,
    weight DECIMAL(8,2) DEFAULT 0,
    delivery_date DATE,
    status ENUM('pending', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Agents table (extended user info for agents)
CREATE TABLE agents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    total_couriers INT DEFAULT 0,
    rating DECIMAL(3,2) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    joined_date DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Customers table (extended user info for customers)
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0,
    last_order_date DATE,
    default_address TEXT,
    billing_address TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert demo data
INSERT INTO users (name, email, password, role, phone, city) VALUES
('John Smith', 'admin@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '+1 234 567 8900', 'New York'),
('Sarah Johnson', 'agent@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', '+1 234 567 8901', 'New York'),
('Mike Wilson', 'user@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '+1 234 567 8902', 'Los Angeles');

-- Insert demo couriers
INSERT INTO couriers (tracking_number, sender_name, receiver_name, pickup_city, delivery_city, courier_type, weight, delivery_date, status, created_by) VALUES
('CMS001234', 'John Doe', 'Alice Smith', 'New York', 'Los Angeles', 'Express', 2.50, '2024-01-25', 'in_transit', 1),
('CMS001235', 'Bob Johnson', 'Carol Brown', 'Chicago', 'Miami', 'Standard', 1.20, '2024-01-24', 'delivered', 2),
('CMS001236', 'David Wilson', 'Eva Davis', 'Houston', 'Seattle', 'Express', 3.80, '2024-01-26', 'pending', 1),
('CMS001237', 'Frank Miller', 'Grace Lee', 'Phoenix', 'Boston', 'Standard', 0.80, '2024-01-23', 'cancelled', 2);

-- Insert demo agents
INSERT INTO agents (user_id, total_couriers, rating, status) VALUES
(2, 156, 4.8, 'active');

-- Insert demo customers
INSERT INTO customers (user_id, total_orders, total_spent, last_order_date) VALUES
(3, 23, 1250.00, '2024-01-20');