-- Courier Management System Database Schema

CREATE DATABASE IF NOT EXISTS courier_management;
USE courier_management;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    city VARCHAR(50),
    role ENUM('admin', 'agent', 'user') NOT NULL DEFAULT 'user',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Couriers table
CREATE TABLE couriers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tracking_number VARCHAR(20) UNIQUE NOT NULL,
    sender_name VARCHAR(100) NOT NULL,
    sender_phone VARCHAR(20),
    sender_address TEXT,
    receiver_name VARCHAR(100) NOT NULL,
    receiver_phone VARCHAR(20),
    receiver_address TEXT,
    pickup_city VARCHAR(50) NOT NULL,
    delivery_city VARCHAR(50) NOT NULL,
    courier_type ENUM('Express', 'Standard', 'Economy') NOT NULL,
    weight DECIMAL(5,2) NOT NULL,
    dimensions VARCHAR(50),
    delivery_date DATE NOT NULL,
    status ENUM('pending', 'in_transit', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    notes TEXT,
    cost DECIMAL(10,2),
    created_by INT NOT NULL,
    assigned_agent INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (assigned_agent) REFERENCES users(id)
);

-- Courier tracking history
CREATE TABLE courier_tracking (
    id INT PRIMARY KEY AUTO_INCREMENT,
    courier_id INT NOT NULL,
    status ENUM('pending', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'cancelled') NOT NULL,
    location VARCHAR(100),
    notes TEXT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (courier_id) REFERENCES couriers(id) ON DELETE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Support tickets
CREATE TABLE support_tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'open',
    assigned_to INT,
    response TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

-- System settings
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

-- Insert default admin user (password: 'password')
INSERT INTO users (name, email, password, role) VALUES 
('John Smith', 'admin@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Sarah Johnson', 'agent@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent'),
('Mike Wilson', 'user@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Insert sample couriers
INSERT INTO couriers (tracking_number, sender_name, receiver_name, pickup_city, delivery_city, courier_type, weight, delivery_date, status, created_by) VALUES 
('CMS001234', 'John Doe', 'Alice Smith', 'New York', 'Los Angeles', 'Express', 2.50, '2024-01-25', 'in_transit', 1),
('CMS001235', 'Bob Johnson', 'Carol Brown', 'Chicago', 'Miami', 'Standard', 1.20, '2024-01-24', 'delivered', 2),
('CMS001236', 'David Wilson', 'Eva Davis', 'Houston', 'Seattle', 'Express', 3.80, '2024-01-26', 'pending', 1),
('CMS001237', 'Frank Miller', 'Grace Lee', 'Phoenix', 'Boston', 'Standard', 0.80, '2024-01-23', 'cancelled', 2);

-- Insert tracking history for sample couriers
INSERT INTO courier_tracking (courier_id, status, location, notes, updated_by) VALUES 
(1, 'picked_up', 'New York Warehouse', 'Package picked up from sender', 1),
(1, 'in_transit', 'Chicago Hub', 'Package in transit', 1),
(2, 'picked_up', 'Chicago Warehouse', 'Package picked up from sender', 2),
(2, 'in_transit', 'Atlanta Hub', 'Package in transit', 2),
(2, 'delivered', 'Miami', 'Package delivered successfully', 2),
(3, 'pending', 'Houston Warehouse', 'Awaiting pickup', 1);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, description, updated_by) VALUES 
('company_name', 'CourierPro', 'Company name displayed on the site', 1),
('company_email', 'info@courierpro.com', 'Main company contact email', 1),
('company_phone', '+1 (555) 123-4567', 'Main company contact phone', 1),
('default_courier_type', 'Standard', 'Default courier type for new shipments', 1),
('max_weight_limit', '50', 'Maximum weight limit in kg', 1);