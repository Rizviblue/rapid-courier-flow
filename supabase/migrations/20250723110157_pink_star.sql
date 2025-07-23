-- Additional Indexes for Performance Optimization
USE courier_management_system;

-- Composite indexes for common queries
CREATE INDEX idx_couriers_status_created ON couriers(status, created_at);
CREATE INDEX idx_couriers_agent_status ON couriers(assigned_agent_id, status);
CREATE INDEX idx_couriers_customer_created ON couriers(customer_id, created_at);
CREATE INDEX idx_couriers_cities_route ON couriers(pickup_city_id, delivery_city_id);
CREATE INDEX idx_couriers_type_status ON couriers(courier_type, status);
CREATE INDEX idx_couriers_priority_created ON couriers(priority, created_at);

-- Tracking history indexes
CREATE INDEX idx_tracking_courier_created ON courier_tracking_history(courier_id, created_at);
CREATE INDEX idx_tracking_status_created ON courier_tracking_history(status, created_at);

-- Assignment indexes
CREATE INDEX idx_assignments_agent_status ON courier_assignments(agent_id, status);
CREATE INDEX idx_assignments_courier_status ON courier_assignments(courier_id, status);

-- Notification indexes
CREATE INDEX idx_notifications_user_read ON notifications(user_id, is_read);
CREATE INDEX idx_notifications_type_created ON notifications(type, created_at);

-- Agent performance indexes
CREATE INDEX idx_agents_city_availability ON agents(city_id, availability);
CREATE INDEX idx_agents_auto_assign ON agents(auto_assign, availability);

-- Customer indexes
CREATE INDEX idx_customers_status_registered ON customers(status, registered_date);
CREATE INDEX idx_customers_total_spent ON customers(total_spent);

-- User indexes
CREATE INDEX idx_users_role_status ON users(role, status);

-- Full-text search indexes for better search performance
ALTER TABLE couriers ADD FULLTEXT(sender_name, receiver_name, pickup_city, delivery_city);
ALTER TABLE customers ADD FULLTEXT(name, email);
ALTER TABLE users ADD FULLTEXT(name, email);

-- Covering indexes for specific queries
CREATE INDEX idx_couriers_dashboard ON couriers(status, created_at, tracking_number, sender_name, receiver_name, pickup_city, delivery_city);
CREATE INDEX idx_agent_performance ON couriers(assigned_agent_id, status, created_at, delivery_fee);