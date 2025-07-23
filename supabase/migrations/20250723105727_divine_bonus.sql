-- Sample Data for Courier Management System
USE courier_management_system;

-- Insert Cities
INSERT INTO cities (name, state, country, postal_code) VALUES
('New York', 'NY', 'USA', '10001'),
('Los Angeles', 'CA', 'USA', '90001'),
('Chicago', 'IL', 'USA', '60601'),
('Houston', 'TX', 'USA', '77001'),
('Phoenix', 'AZ', 'USA', '85001'),
('Philadelphia', 'PA', 'USA', '19101'),
('San Antonio', 'TX', 'USA', '78201'),
('San Diego', 'CA', 'USA', '92101'),
('Dallas', 'TX', 'USA', '75201'),
('San Jose', 'CA', 'USA', '95101'),
('Austin', 'TX', 'USA', '73301'),
('Jacksonville', 'FL', 'USA', '32099'),
('Fort Worth', 'TX', 'USA', '76101'),
('Columbus', 'OH', 'USA', '43085'),
('Charlotte', 'NC', 'USA', '28201'),
('San Francisco', 'CA', 'USA', '94101'),
('Indianapolis', 'IN', 'USA', '46201'),
('Seattle', 'WA', 'USA', '98101'),
('Denver', 'CO', 'USA', '80201'),
('Boston', 'MA', 'USA', '02101'),
('El Paso', 'TX', 'USA', '79901'),
('Detroit', 'MI', 'USA', '48201'),
('Nashville', 'TN', 'USA', '37201'),
('Portland', 'OR', 'USA', '97201'),
('Memphis', 'TN', 'USA', '38101'),
('Oklahoma City', 'OK', 'USA', '73101'),
('Las Vegas', 'NV', 'USA', '89101'),
('Louisville', 'KY', 'USA', '40201'),
('Baltimore', 'MD', 'USA', '21201'),
('Milwaukee', 'WI', 'USA', '53201'),
('Albuquerque', 'NM', 'USA', '87101'),
('Tucson', 'AZ', 'USA', '85701'),
('Fresno', 'CA', 'USA', '93701'),
('Mesa', 'AZ', 'USA', '85201'),
('Sacramento', 'CA', 'USA', '95814'),
('Atlanta', 'GA', 'USA', '30301'),
('Kansas City', 'MO', 'USA', '64101'),
('Colorado Springs', 'CO', 'USA', '80901'),
('Miami', 'FL', 'USA', '33101'),
('Raleigh', 'NC', 'USA', '27601'),
('Omaha', 'NE', 'USA', '68101'),
('Long Beach', 'CA', 'USA', '90801'),
('Virginia Beach', 'VA', 'USA', '23451'),
('Oakland', 'CA', 'USA', '94601'),
('Minneapolis', 'MN', 'USA', '55401'),
('Tulsa', 'OK', 'USA', '74101'),
('Arlington', 'TX', 'USA', '76001'),
('Tampa', 'FL', 'USA', '33601'),
('New Orleans', 'LA', 'USA', '70112'),
('Wichita', 'KS', 'USA', '67201');

-- Insert Users (Admin, Agents, and Regular Users)
INSERT INTO users (name, email, password, phone, role, status, created_at) VALUES
-- Admin Users
('John Smith', 'admin@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 234 567 8900', 'admin', 'active', '2023-01-01 10:00:00'),
('Admin User', 'admin2@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 234 567 8999', 'admin', 'active', '2023-01-01 10:00:00'),

-- Agent Users
('Sarah Johnson', 'agent@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 234 567 8901', 'agent', 'active', '2023-01-15 09:00:00'),
('Michael Chen', 'michael.chen@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 234 567 8902', 'agent', 'active', '2023-02-20 09:00:00'),
('Emily Rodriguez', 'emily.rodriguez@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 234 567 8903', 'agent', 'inactive', '2023-03-10 09:00:00'),
('David Kim', 'david.kim@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 234 567 8904', 'agent', 'active', '2022-11-05 09:00:00'),
('Lisa Wang', 'lisa.wang@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 234 567 8905', 'agent', 'active', '2023-04-12 09:00:00'),
('Robert Martinez', 'robert.martinez@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 234 567 8906', 'agent', 'active', '2023-05-18 09:00:00'),

-- Regular Users
('Mike Wilson', 'user@courierpro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 234 567 8910', 'user', 'active', '2023-06-01 10:00:00'),
('Alice Cooper', 'alice.cooper@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0101', 'user', 'active', '2023-01-15 10:00:00'),
('Bob Johnson', 'bob.johnson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0102', 'user', 'active', '2023-02-20 10:00:00'),
('Carol Brown', 'carol.brown@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0103', 'user', 'active', '2023-03-10 10:00:00'),
('David Wilson', 'david.wilson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0104', 'user', 'active', '2023-04-05 10:00:00'),
('Eva Davis', 'eva.davis@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0105', 'user', 'active', '2023-05-12 10:00:00'),
('Frank Miller', 'frank.miller@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0106', 'user', 'active', '2023-06-18 10:00:00'),
('Grace Lee', 'grace.lee@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0107', 'user', 'active', '2023-07-22 10:00:00'),
('Henry Taylor', 'henry.taylor@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0108', 'user', 'active', '2023-08-15 10:00:00'),
('Ivy Chen', 'ivy.chen@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0109', 'user', 'active', '2023-09-10 10:00:00'),
('Jack Robinson', 'jack.robinson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0110', 'user', 'active', '2023-10-05 10:00:00'),
('Kate Anderson', 'kate.anderson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0111', 'user', 'active', '2023-11-12 10:00:00'),
('Liam Murphy', 'liam.murphy@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 555 0112', 'user', 'active', '2023-12-01 10:00:00');

-- Insert Agents (linked to agent users)
INSERT INTO agents (user_id, agent_code, city_id, working_hours, max_daily_orders, availability, auto_assign, total_couriers, success_rate, rating, joined_date) VALUES
(3, 'AGT001', 1, '9to5', 25, TRUE, FALSE, 156, 94.50, 4.80, '2023-01-15'),
(4, 'AGT002', 2, '8to6', 30, TRUE, TRUE, 134, 92.30, 4.70, '2023-02-20'),
(5, 'AGT003', 3, '9to5', 20, FALSE, FALSE, 89, 88.20, 4.60, '2023-03-10'),
(6, 'AGT004', 4, '7to7', 35, TRUE, TRUE, 201, 96.10, 4.90, '2022-11-05'),
(7, 'AGT005', 5, '9to5', 22, TRUE, FALSE, 78, 91.40, 4.65, '2023-04-12'),
(8, 'AGT006', 6, '8to6', 28, TRUE, TRUE, 112, 93.80, 4.75, '2023-05-18');

-- Insert Customers
INSERT INTO customers (user_id, customer_code, name, email, phone, default_address, billing_address, total_orders, total_spent, status, preferred_delivery_time, package_instructions, registered_date, last_order_date) VALUES
(10, 'CUST001', 'Alice Cooper', 'alice.cooper@email.com', '+1 555 0101', '123 Main St, New York, NY 10001', '123 Main St, New York, NY 10001', 24, 2450.00, 'active', 'morning', 'Leave at front door', '2023-01-15', '2024-01-20'),
(11, 'CUST002', 'Bob Johnson', 'bob.johnson@email.com', '+1 555 0102', '456 Oak Ave, Los Angeles, CA 90001', '456 Oak Ave, Los Angeles, CA 90001', 18, 1890.00, 'active', 'afternoon', 'Ring doorbell', '2023-02-20', '2024-01-18'),
(12, 'CUST003', 'Carol Brown', 'carol.brown@email.com', '+1 555 0103', '789 Pine St, Chicago, IL 60601', '789 Pine St, Chicago, IL 60601', 31, 3120.00, 'active', 'evening', 'Call before delivery', '2022-11-10', '2024-01-22'),
(13, 'CUST004', 'David Wilson', 'david.wilson@email.com', '+1 555 0104', '321 Elm Dr, Houston, TX 77001', '321 Elm Dr, Houston, TX 77001', 7, 670.00, 'inactive', 'anytime', 'Leave with neighbor', '2023-08-15', '2023-12-05'),
(14, 'CUST005', 'Eva Davis', 'eva.davis@email.com', '+1 555 0105', '654 Maple Ln, Phoenix, AZ 85001', '654 Maple Ln, Phoenix, AZ 85001', 42, 4200.00, 'active', 'morning', 'Signature required', '2022-06-30', '2024-01-21'),
(15, 'CUST006', 'Frank Miller', 'frank.miller@email.com', '+1 555 0106', '987 Cedar Rd, Philadelphia, PA 19101', '987 Cedar Rd, Philadelphia, PA 19101', 15, 1450.00, 'active', 'afternoon', 'Leave at back door', '2023-03-22', '2024-01-19'),
(16, 'CUST007', 'Grace Lee', 'grace.lee@email.com', '+1 555 0107', '147 Birch St, San Antonio, TX 78201', '147 Birch St, San Antonio, TX 78201', 28, 2890.00, 'active', 'evening', 'Call on arrival', '2023-01-08', '2024-01-23'),
(17, 'CUST008', 'Henry Taylor', 'henry.taylor@email.com', '+1 555 0108', '258 Spruce Ave, San Diego, CA 92101', '258 Spruce Ave, San Diego, CA 92101', 12, 1200.00, 'active', 'anytime', 'Leave in mailbox', '2023-07-14', '2024-01-17'),
(18, 'CUST009', 'Ivy Chen', 'ivy.chen@email.com', '+1 555 0109', '369 Willow Way, Dallas, TX 75201', '369 Willow Way, Dallas, TX 75201', 35, 3650.00, 'active', 'morning', 'Ring bell twice', '2022-09-03', '2024-01-24'),
(19, 'CUST010', 'Jack Robinson', 'jack.robinson@email.com', '+1 555 0110', '741 Ash Blvd, San Jose, CA 95101', '741 Ash Blvd, San Jose, CA 95101', 19, 1950.00, 'active', 'afternoon', 'Leave with concierge', '2023-04-17', '2024-01-16');

-- Insert Couriers (sample shipments)
INSERT INTO couriers (tracking_number, sender_name, sender_phone, sender_address, receiver_name, receiver_phone, receiver_address, pickup_city_id, delivery_city_id, pickup_city, delivery_city, courier_type, weight, package_value, delivery_fee, status, priority, delivery_date, pickup_date, special_instructions, notes, created_by, assigned_agent_id, customer_id, created_at) VALUES
-- Active shipments
('CMS001234', 'John Doe', '+1 555 1001', '100 Business Plaza, New York, NY', 'Alice Cooper', '+1 555 0101', '123 Main St, New York, NY 10001', 1, 2, 'New York', 'Los Angeles', 'express', 2.50, 150.00, 25.99, 'in_transit', 'high', '2024-01-25', '2024-01-20', 'Handle with care', 'Fragile electronics', 3, 1, 1, '2024-01-20 10:00:00'),
('CMS001235', 'Bob Johnson', '+1 555 1002', '200 Commerce St, Chicago, IL', 'Carol Brown', '+1 555 0103', '789 Pine St, Chicago, IL 60601', 3, 39, 'Chicago', 'Miami', 'standard', 1.20, 75.00, 15.99, 'delivered', 'medium', '2024-01-24', '2024-01-19', 'None', 'Delivered successfully', 4, 2, 3, '2024-01-19 14:30:00'),
('CMS001236', 'David Wilson', '+1 555 1003', '300 Trade Center, Houston, TX', 'Eva Davis', '+1 555 0105', '654 Maple Ln, Phoenix, AZ 85001', 4, 5, 'Houston', 'Seattle', 'express', 3.80, 200.00, 35.99, 'pending', 'medium', '2024-01-26', NULL, 'Signature required', 'Awaiting pickup', 3, 1, 5, '2024-01-21 09:15:00'),
('CMS001237', 'Frank Miller', '+1 555 1004', '400 Industrial Way, Phoenix, AZ', 'Grace Lee', '+1 555 0107', '147 Birch St, San Antonio, TX 78201', 5, 7, 'Phoenix', 'Boston', 'standard', 0.80, 45.00, 12.99, 'cancelled', 'low', '2024-01-23', NULL, 'None', 'Cancelled by sender', 4, 2, 7, '2024-01-18 16:45:00'),
('CMS001238', 'Henry Taylor', '+1 555 1005', '500 Market Square, San Diego, CA', 'Ivy Chen', '+1 555 0109', '369 Willow Way, Dallas, TX 75201', 8, 9, 'San Diego', 'Dallas', 'overnight', 1.50, 300.00, 45.99, 'in_transit', 'urgent', '2024-01-22', '2024-01-21', 'Next day delivery', 'Priority shipment', 6, 4, 9, '2024-01-21 08:00:00'),
('CMS001239', 'Jack Robinson', '+1 555 1006', '600 Tech Park, San Jose, CA', 'Kate Anderson', '+1 555 0111', '852 Valley View, Austin, TX 73301', 10, 11, 'San Jose', 'Austin', 'express', 2.20, 180.00, 28.99, 'delivered', 'high', '2024-01-20', '2024-01-18', 'Handle with care', 'Computer equipment', 7, 5, NULL, '2024-01-18 11:20:00'),
('CMS001240', 'Liam Murphy', '+1 555 1007', '700 Innovation Dr, Seattle, WA', 'Alice Cooper', '+1 555 0101', '123 Main St, New York, NY 10001', 18, 1, 'Seattle', 'New York', 'standard', 4.50, 120.00, 22.99, 'in_transit', 'medium', '2024-01-27', '2024-01-22', 'None', 'Cross-country shipment', 3, 1, 1, '2024-01-22 13:45:00'),
('CMS001241', 'Sarah Johnson', '+1 555 1008', '800 Corporate Blvd, Denver, CO', 'Bob Johnson', '+1 555 0102', '456 Oak Ave, Los Angeles, CA 90001', 19, 2, 'Denver', 'Los Angeles', 'same-day', 0.90, 85.00, 55.99, 'delivered', 'urgent', '2024-01-21', '2024-01-21', 'Same day delivery', 'Rush order completed', 4, 2, 2, '2024-01-21 07:30:00'),
('CMS001242', 'Michael Chen', '+1 555 1009', '900 Enterprise Ave, Boston, MA', 'David Wilson', '+1 555 0104', '321 Elm Dr, Houston, TX 77001', 20, 4, 'Boston', 'Houston', 'express', 3.20, 250.00, 32.99, 'pending', 'high', '2024-01-28', NULL, 'Fragile contents', 'Awaiting agent assignment', 6, NULL, 4, '2024-01-23 10:15:00'),
('CMS001243', 'Emily Rodriguez', '+1 555 1010', '1000 Gateway Plaza, Detroit, MI', 'Frank Miller', '+1 555 0106', '987 Cedar Rd, Philadelphia, PA 19101', 22, 6, 'Detroit', 'Philadelphia', 'standard', 2.80, 95.00, 18.99, 'in_transit', 'medium', '2024-01-26', '2024-01-23', 'None', 'Standard delivery', 7, 5, 6, '2024-01-23 14:20:00'),

-- Additional shipments for better data variety
('CMS001244', 'Lisa Wang', '+1 555 1011', '1100 Silicon Valley, San Jose, CA', 'Grace Lee', '+1 555 0107', '147 Birch St, San Antonio, TX 78201', 10, 7, 'San Jose', 'San Antonio', 'express', 1.75, 160.00, 26.99, 'delivered', 'medium', '2024-01-19', '2024-01-17', 'Tech equipment', 'Delivered on time', 3, 1, 7, '2024-01-17 09:30:00'),
('CMS001245', 'Robert Martinez', '+1 555 1012', '1200 Financial District, New York, NY', 'Henry Taylor', '+1 555 0108', '258 Spruce Ave, San Diego, CA 92101', 1, 8, 'New York', 'San Diego', 'overnight', 0.65, 400.00, 65.99, 'delivered', 'urgent', '2024-01-18', '2024-01-17', 'Overnight delivery', 'High-value item', 4, 2, 8, '2024-01-17 15:45:00'),
('CMS001246', 'Amanda Foster', '+1 555 1013', '1300 Research Park, Austin, TX', 'Ivy Chen', '+1 555 0109', '369 Willow Way, Dallas, TX 75201', 11, 9, 'Austin', 'Dallas', 'standard', 5.20, 80.00, 16.99, 'in_transit', 'low', '2024-01-25', '2024-01-22', 'Books and documents', 'Educational materials', 6, 4, 9, '2024-01-22 11:00:00'),
('CMS001247', 'Christopher Lee', '+1 555 1014', '1400 Medical Center, Houston, TX', 'Jack Robinson', '+1 555 0110', '741 Ash Blvd, San Jose, CA 95101', 4, 10, 'Houston', 'San Jose', 'express', 1.10, 500.00, 42.99, 'pending', 'urgent', '2024-01-29', NULL, 'Medical supplies', 'Temperature sensitive', 7, NULL, 10, '2024-01-24 08:15:00'),
('CMS001248', 'Jennifer Adams', '+1 555 1015', '1500 Art District, Miami, FL', 'Kate Anderson', '+1 555 0111', '852 Valley View, Austin, TX 73301', 39, 11, 'Miami', 'Austin', 'standard', 3.60, 220.00, 24.99, 'delivered', 'medium', '2024-01-21', '2024-01-19', 'Artwork - fragile', 'Special handling required', 3, 1, NULL, '2024-01-19 12:30:00'),
('CMS001249', 'Daniel Brown', '+1 555 1016', '1600 Sports Complex, Phoenix, AZ', 'Liam Murphy', '+1 555 0112', '963 Mountain View, Denver, CO 80201', 5, 19, 'Phoenix', 'Denver', 'express', 2.90, 135.00, 29.99, 'in_transit', 'medium', '2024-01-27', '2024-01-24', 'Sports equipment', 'Handle with care', 8, 6, NULL, '2024-01-24 10:45:00'),
('CMS001250', 'Michelle Garcia', '+1 555 1017', '1700 Fashion Ave, Los Angeles, CA', 'Alice Cooper', '+1 555 0101', '123 Main St, New York, NY 10001', 2, 1, 'Los Angeles', 'New York', 'overnight', 1.30, 350.00, 58.99, 'delivered', 'high', '2024-01-20', '2024-01-19', 'Designer clothing', 'Fashion week delivery', 4, 2, 1, '2024-01-19 16:20:00'),
('CMS001251', 'Kevin Wilson', '+1 555 1018', '1800 Tech Hub, Seattle, WA', 'Carol Brown', '+1 555 0103', '789 Pine St, Chicago, IL 60601', 18, 3, 'Seattle', 'Chicago', 'standard', 4.10, 190.00, 21.99, 'cancelled', 'medium', '2024-01-23', NULL, 'Software package', 'Cancelled - duplicate order', 6, NULL, 3, '2024-01-20 13:10:00'),
('CMS001252', 'Rachel Thompson', '+1 555 1019', '1900 Green Energy, Portland, OR', 'Eva Davis', '+1 555 0105', '654 Maple Ln, Phoenix, AZ 85001', 24, 5, 'Portland', 'Phoenix', 'express', 2.40, 275.00, 33.99, 'in_transit', 'high', '2024-01-28', '2024-01-25', 'Solar equipment', 'Renewable energy parts', 7, 5, 5, '2024-01-25 09:00:00'),
('CMS001253', 'Steven Clark', '+1 555 1020', '2000 Aerospace Dr, San Diego, CA', 'Frank Miller', '+1 555 0106', '987 Cedar Rd, Philadelphia, PA 19101', 8, 6, 'San Diego', 'Philadelphia', 'overnight', 0.95, 600.00, 72.99, 'pending', 'urgent', '2024-01-30', NULL, 'Aerospace components', 'Critical parts shipment', 3, NULL, 6, '2024-01-25 14:30:00');

-- Insert Courier Assignments
INSERT INTO courier_assignments (courier_id, agent_id, assigned_by, assigned_at, status, notes) VALUES
(1, 1, 1, '2024-01-20 10:30:00', 'active', 'High priority express delivery'),
(2, 2, 1, '2024-01-19 15:00:00', 'completed', 'Standard delivery completed successfully'),
(3, 1, 1, '2024-01-21 09:45:00', 'active', 'Awaiting pickup confirmation'),
(4, 2, 1, '2024-01-18 17:00:00', 'cancelled', 'Assignment cancelled due to shipment cancellation'),
(5, 4, 1, '2024-01-21 08:30:00', 'active', 'Overnight priority shipment'),
(6, 5, 1, '2024-01-18 11:45:00', 'completed', 'Express delivery completed on time'),
(7, 1, 1, '2024-01-22 14:00:00', 'active', 'Cross-country standard delivery'),
(8, 2, 1, '2024-01-21 07:45:00', 'completed', 'Same-day delivery completed'),
(10, 5, 1, '2024-01-23 14:45:00', 'active', 'Standard delivery in progress'),
(11, 1, 1, '2024-01-17 10:00:00', 'completed', 'Tech equipment delivered safely'),
(12, 2, 1, '2024-01-17 16:00:00', 'completed', 'Overnight high-value delivery'),
(13, 4, 1, '2024-01-22 11:30:00', 'active', 'Educational materials shipment'),
(15, 1, 1, '2024-01-19 12:45:00', 'completed', 'Artwork delivered with special handling'),
(16, 6, 1, '2024-01-24 11:00:00', 'active', 'Sports equipment in transit'),
(17, 2, 1, '2024-01-19 16:45:00', 'completed', 'Fashion week priority delivery'),
(19, 5, 1, '2024-01-25 09:30:00', 'active', 'Renewable energy equipment');

-- Insert Courier Tracking History
INSERT INTO courier_tracking_history (courier_id, status, location, description, updated_by, created_at) VALUES
-- Tracking for CMS001234 (in_transit)
(1, 'pending', 'New York, NY', 'Package received at origin facility', 3, '2024-01-20 10:00:00'),
(1, 'picked_up', 'New York, NY', 'Package picked up by courier', 3, '2024-01-20 11:30:00'),
(1, 'in_transit', 'Philadelphia, PA', 'Package in transit - arrived at Philadelphia hub', 3, '2024-01-20 18:45:00'),
(1, 'in_transit', 'Chicago, IL', 'Package in transit - arrived at Chicago hub', 3, '2024-01-21 08:20:00'),
(1, 'in_transit', 'Denver, CO', 'Package in transit - arrived at Denver hub', 3, '2024-01-21 20:15:00'),

-- Tracking for CMS001235 (delivered)
(2, 'pending', 'Chicago, IL', 'Package received at origin facility', 4, '2024-01-19 14:30:00'),
(2, 'picked_up', 'Chicago, IL', 'Package picked up by courier', 4, '2024-01-19 16:00:00'),
(2, 'in_transit', 'Atlanta, GA', 'Package in transit - arrived at Atlanta hub', 4, '2024-01-20 10:30:00'),
(2, 'out_for_delivery', 'Miami, FL', 'Package out for delivery', 4, '2024-01-21 09:00:00'),
(2, 'delivered', 'Miami, FL', 'Package delivered successfully', 4, '2024-01-21 14:20:00'),

-- Tracking for CMS001236 (pending)
(3, 'pending', 'Houston, TX', 'Package received at origin facility', 3, '2024-01-21 09:15:00'),

-- Tracking for CMS001237 (cancelled)
(4, 'pending', 'Phoenix, AZ', 'Package received at origin facility', 4, '2024-01-18 16:45:00'),
(4, 'cancelled', 'Phoenix, AZ', 'Package cancelled by sender request', 4, '2024-01-18 18:00:00'),

-- Tracking for CMS001238 (in_transit)
(5, 'pending', 'San Diego, CA', 'Package received at origin facility', 6, '2024-01-21 08:00:00'),
(5, 'picked_up', 'San Diego, CA', 'Package picked up by courier', 6, '2024-01-21 09:30:00'),
(5, 'in_transit', 'Phoenix, AZ', 'Package in transit - arrived at Phoenix hub', 6, '2024-01-21 16:45:00'),
(5, 'in_transit', 'Albuquerque, NM', 'Package in transit - arrived at Albuquerque hub', 6, '2024-01-22 02:20:00'),

-- More tracking entries for other packages
(6, 'pending', 'San Jose, CA', 'Package received at origin facility', 7, '2024-01-18 11:20:00'),
(6, 'picked_up', 'San Jose, CA', 'Package picked up by courier', 7, '2024-01-18 13:00:00'),
(6, 'in_transit', 'Sacramento, CA', 'Package in transit', 7, '2024-01-18 18:30:00'),
(6, 'in_transit', 'Denver, CO', 'Package in transit - arrived at Denver hub', 7, '2024-01-19 08:15:00'),
(6, 'out_for_delivery', 'Austin, TX', 'Package out for delivery', 7, '2024-01-19 10:00:00'),
(6, 'delivered', 'Austin, TX', 'Package delivered successfully', 7, '2024-01-19 15:30:00'),

(7, 'pending', 'Seattle, WA', 'Package received at origin facility', 3, '2024-01-22 13:45:00'),
(7, 'picked_up', 'Seattle, WA', 'Package picked up by courier', 3, '2024-01-22 15:20:00'),
(7, 'in_transit', 'Portland, OR', 'Package in transit', 3, '2024-01-22 19:00:00'),
(7, 'in_transit', 'Denver, CO', 'Package in transit - arrived at Denver hub', 3, '2024-01-23 12:30:00'),

(8, 'pending', 'Denver, CO', 'Package received at origin facility', 4, '2024-01-21 07:30:00'),
(8, 'picked_up', 'Denver, CO', 'Package picked up by courier', 4, '2024-01-21 08:00:00'),
(8, 'out_for_delivery', 'Los Angeles, CA', 'Package out for delivery', 4, '2024-01-21 11:00:00'),
(8, 'delivered', 'Los Angeles, CA', 'Same-day delivery completed', 4, '2024-01-21 14:45:00');

-- Insert Notifications
INSERT INTO notifications (user_id, courier_id, type, title, message, is_read, created_at) VALUES
-- Notifications for users
(9, 1, 'courier_update', 'Package Update', 'Your package CMS001234 is now in transit', FALSE, '2024-01-21 08:20:00'),
(9, 7, 'courier_update', 'Package Picked Up', 'Your package CMS001240 has been picked up', FALSE, '2024-01-22 15:20:00'),
(10, 1, 'delivery_alert', 'Delivery Scheduled', 'Your package CMS001234 is scheduled for delivery tomorrow', TRUE, '2024-01-24 10:00:00'),
(11, 2, 'courier_update', 'Package Delivered', 'Your package CMS001235 has been delivered successfully', TRUE, '2024-01-21 14:20:00'),
(12, 2, 'delivery_alert', 'Delivery Confirmation', 'Package CMS001235 delivered to Carol Brown', TRUE, '2024-01-21 14:25:00'),
(14, 3, 'courier_update', 'Package Pending', 'Your package CMS001236 is awaiting pickup', FALSE, '2024-01-21 09:15:00'),
(15, 19, 'courier_update', 'Package In Transit', 'Your package CMS001252 is now in transit', FALSE, '2024-01-25 09:30:00'),

-- System notifications
(1, NULL, 'system_notification', 'System Maintenance', 'Scheduled maintenance tonight from 2-4 AM EST', FALSE, '2024-01-24 16:00:00'),
(3, NULL, 'system_notification', 'New Feature Available', 'Real-time tracking is now available for all shipments', TRUE, '2024-01-20 09:00:00'),
(4, NULL, 'system_notification', 'Performance Report', 'Your monthly performance report is ready', FALSE, '2024-01-25 08:00:00'),

-- Agent notifications
(3, 1, 'courier_update', 'New Assignment', 'You have been assigned courier CMS001234', TRUE, '2024-01-20 10:30:00'),
(3, 3, 'courier_update', 'Pickup Required', 'Package CMS001236 requires pickup', FALSE, '2024-01-21 09:45:00'),
(4, 5, 'courier_update', 'Priority Delivery', 'Urgent: Package CMS001238 requires immediate attention', TRUE, '2024-01-21 08:30:00'),
(6, 13, 'courier_update', 'New Assignment', 'You have been assigned courier CMS001246', FALSE, '2024-01-22 11:30:00');

-- Insert System Settings
INSERT INTO settings (key_name, value, description, type) VALUES
('site_name', 'CourierPro Management System', 'Name of the courier management system', 'string'),
('site_email', 'admin@courierpro.com', 'Main contact email for the system', 'string'),
('site_phone', '+1 234 567 8900', 'Main contact phone number', 'string'),
('default_currency', 'USD', 'Default currency for pricing', 'string'),
('default_timezone', 'America/New_York', 'Default timezone for the system', 'string'),
('max_package_weight', '50', 'Maximum package weight in kg', 'number'),
('delivery_fee_standard', '15.99', 'Standard delivery fee', 'number'),
('delivery_fee_express', '25.99', 'Express delivery fee', 'number'),
('delivery_fee_overnight', '45.99', 'Overnight delivery fee', 'number'),
('delivery_fee_same_day', '65.99', 'Same day delivery fee', 'number'),
('email_notifications_enabled', 'true', 'Enable email notifications', 'boolean'),
('sms_notifications_enabled', 'true', 'Enable SMS notifications', 'boolean'),
('auto_assign_agents', 'false', 'Automatically assign agents to new couriers', 'boolean'),
('tracking_update_interval', '30', 'Tracking update interval in minutes', 'number'),
('max_daily_orders_per_agent', '25', 'Maximum daily orders per agent', 'number'),
('business_hours_start', '09:00', 'Business hours start time', 'string'),
('business_hours_end', '17:00', 'Business hours end time', 'string'),
('supported_cities', '["New York", "Los Angeles", "Chicago", "Houston", "Phoenix", "Philadelphia", "San Antonio", "San Diego", "Dallas", "San Jose"]', 'List of supported cities', 'json');

-- Insert User Preferences for some users
INSERT INTO user_preferences (user_id, email_notifications, sms_notifications, push_notifications, order_updates, system_alerts, marketing_emails, delivery_alerts, theme, language, timezone, currency, date_format) VALUES
(1, TRUE, FALSE, TRUE, TRUE, TRUE, FALSE, TRUE, 'light', 'en', 'America/New_York', 'USD', 'MM/DD/YYYY'),
(3, TRUE, TRUE, TRUE, TRUE, TRUE, FALSE, TRUE, 'light', 'en', 'America/New_York', 'USD', 'MM/DD/YYYY'),
(4, TRUE, FALSE, TRUE, TRUE, TRUE, FALSE, TRUE, 'dark', 'en', 'America/Los_Angeles', 'USD', 'MM/DD/YYYY'),
(9, TRUE, TRUE, FALSE, TRUE, FALSE, TRUE, TRUE, 'light', 'en', 'America/New_York', 'USD', 'MM/DD/YYYY'),
(10, FALSE, TRUE, TRUE, TRUE, FALSE, FALSE, TRUE, 'light', 'en', 'America/New_York', 'USD', 'MM/DD/YYYY'),
(11, TRUE, FALSE, TRUE, TRUE, TRUE, TRUE, TRUE, 'dark', 'en', 'America/Chicago', 'USD', 'DD/MM/YYYY'),
(12, TRUE, TRUE, TRUE, TRUE, TRUE, FALSE, TRUE, 'light', 'en', 'America/Chicago', 'USD', 'MM/DD/YYYY');

-- Insert some sample reports
INSERT INTO reports (name, type, generated_by, date_from, date_to, parameters, status, created_at, completed_at) VALUES
('Daily Courier Report - Jan 24', 'daily', 1, '2024-01-24', '2024-01-24', '{"include_cancelled": false, "group_by_agent": true}', 'completed', '2024-01-24 18:00:00', '2024-01-24 18:05:00'),
('Weekly Performance Report', 'weekly', 1, '2024-01-15', '2024-01-21', '{"include_metrics": true, "agent_performance": true}', 'completed', '2024-01-22 09:00:00', '2024-01-22 09:12:00'),
('Monthly Summary - December', 'monthly', 1, '2023-12-01', '2023-12-31', '{"financial_summary": true, "top_routes": true}', 'completed', '2024-01-01 10:00:00', '2024-01-01 10:25:00'),
('Agent Performance Report', 'custom', 3, '2024-01-01', '2024-01-25', '{"agent_id": 1, "detailed_tracking": true}', 'generating', '2024-01-25 14:30:00', NULL);