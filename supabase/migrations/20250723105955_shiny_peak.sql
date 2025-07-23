-- Useful Views for Courier Management System
USE courier_management_system;

-- View: Courier Details with Full Information
CREATE OR REPLACE VIEW courier_details AS
SELECT 
    c.id,
    c.tracking_number,
    c.sender_name,
    c.sender_phone,
    c.receiver_name,
    c.receiver_phone,
    c.pickup_city,
    c.delivery_city,
    pc.name as pickup_city_full,
    dc.name as delivery_city_full,
    c.courier_type,
    c.weight,
    c.package_value,
    c.delivery_fee,
    c.status,
    c.priority,
    c.delivery_date,
    c.actual_delivery_date,
    c.pickup_date,
    c.special_instructions,
    c.notes,
    u.name as created_by_name,
    u.email as created_by_email,
    a.agent_code,
    au.name as agent_name,
    au.email as agent_email,
    cust.name as customer_name,
    cust.email as customer_email,
    c.created_at,
    c.updated_at,
    CASE 
        WHEN c.status = 'delivered' AND c.actual_delivery_date <= c.delivery_date THEN 'On Time'
        WHEN c.status = 'delivered' AND c.actual_delivery_date > c.delivery_date THEN 'Late'
        WHEN c.status != 'delivered' AND CURDATE() > c.delivery_date THEN 'Overdue'
        ELSE 'On Schedule'
    END as delivery_status
FROM couriers c
LEFT JOIN cities pc ON c.pickup_city_id = pc.id
LEFT JOIN cities dc ON c.delivery_city_id = dc.id
LEFT JOIN users u ON c.created_by = u.id
LEFT JOIN agents a ON c.assigned_agent_id = a.id
LEFT JOIN users au ON a.user_id = au.id
LEFT JOIN customers cust ON c.customer_id = cust.id;

-- View: Agent Performance Summary
CREATE OR REPLACE VIEW agent_performance AS
SELECT 
    a.id as agent_id,
    a.agent_code,
    u.name as agent_name,
    u.email as agent_email,
    c.name as city_name,
    a.total_couriers,
    a.success_rate,
    a.rating,
    a.availability,
    a.max_daily_orders,
    COUNT(co.id) as total_assigned_couriers,
    COUNT(CASE WHEN co.status = 'delivered' THEN 1 END) as delivered_couriers,
    COUNT(CASE WHEN co.status = 'in_transit' THEN 1 END) as in_transit_couriers,
    COUNT(CASE WHEN co.status = 'pending' THEN 1 END) as pending_couriers,
    COUNT(CASE WHEN co.status = 'cancelled' THEN 1 END) as cancelled_couriers,
    ROUND(
        (COUNT(CASE WHEN co.status = 'delivered' THEN 1 END) * 100.0 / 
         NULLIF(COUNT(CASE WHEN co.status != 'pending' THEN 1 END), 0)), 2
    ) as actual_success_rate,
    AVG(co.delivery_fee) as avg_delivery_fee,
    SUM(co.delivery_fee) as total_revenue,
    COUNT(CASE WHEN co.status = 'delivered' AND co.actual_delivery_date <= co.delivery_date THEN 1 END) as on_time_deliveries,
    ROUND(
        (COUNT(CASE WHEN co.status = 'delivered' AND co.actual_delivery_date <= co.delivery_date THEN 1 END) * 100.0 / 
         NULLIF(COUNT(CASE WHEN co.status = 'delivered' THEN 1 END), 0)), 2
    ) as on_time_percentage
FROM agents a
JOIN users u ON a.user_id = u.id
LEFT JOIN cities c ON a.city_id = c.id
LEFT JOIN couriers co ON a.id = co.assigned_agent_id
GROUP BY a.id, a.agent_code, u.name, u.email, c.name, a.total_couriers, a.success_rate, a.rating, a.availability, a.max_daily_orders;

-- View: Customer Summary
CREATE OR REPLACE VIEW customer_summary AS
SELECT 
    c.id as customer_id,
    c.customer_code,
    c.name as customer_name,
    c.email as customer_email,
    c.phone as customer_phone,
    c.status as customer_status,
    c.total_orders,
    c.total_spent,
    c.registered_date,
    c.last_order_date,
    COUNT(co.id) as actual_total_orders,
    SUM(co.delivery_fee) as actual_total_spent,
    COUNT(CASE WHEN co.status = 'delivered' THEN 1 END) as delivered_orders,
    COUNT(CASE WHEN co.status = 'in_transit' THEN 1 END) as in_transit_orders,
    COUNT(CASE WHEN co.status = 'pending' THEN 1 END) as pending_orders,
    COUNT(CASE WHEN co.status = 'cancelled' THEN 1 END) as cancelled_orders,
    MAX(co.created_at) as last_actual_order_date,
    ROUND(AVG(co.delivery_fee), 2) as avg_order_value,
    DATEDIFF(CURDATE(), c.last_order_date) as days_since_last_order
FROM customers c
LEFT JOIN couriers co ON c.id = co.customer_id
GROUP BY c.id, c.customer_code, c.name, c.email, c.phone, c.status, c.total_orders, c.total_spent, c.registered_date, c.last_order_date;

-- View: Daily Statistics
CREATE OR REPLACE VIEW daily_stats AS
SELECT 
    DATE(created_at) as date,
    COUNT(*) as total_couriers,
    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_couriers,
    COUNT(CASE WHEN status = 'in_transit' THEN 1 END) as in_transit_couriers,
    COUNT(CASE WHEN status = 'delivered' THEN 1 END) as delivered_couriers,
    COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_couriers,
    COUNT(CASE WHEN courier_type = 'standard' THEN 1 END) as standard_couriers,
    COUNT(CASE WHEN courier_type = 'express' THEN 1 END) as express_couriers,
    COUNT(CASE WHEN courier_type = 'overnight' THEN 1 END) as overnight_couriers,
    COUNT(CASE WHEN courier_type = 'same-day' THEN 1 END) as same_day_couriers,
    ROUND(AVG(weight), 2) as avg_weight,
    ROUND(AVG(delivery_fee), 2) as avg_delivery_fee,
    ROUND(SUM(delivery_fee), 2) as total_revenue,
    ROUND(AVG(package_value), 2) as avg_package_value
FROM couriers
GROUP BY DATE(created_at)
ORDER BY date DESC;

-- View: Route Analysis
CREATE OR REPLACE VIEW route_analysis AS
SELECT 
    CONCAT(pickup_city, ' → ', delivery_city) as route,
    pickup_city,
    delivery_city,
    COUNT(*) as total_shipments,
    COUNT(CASE WHEN status = 'delivered' THEN 1 END) as delivered_shipments,
    COUNT(CASE WHEN status = 'in_transit' THEN 1 END) as in_transit_shipments,
    COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_shipments,
    ROUND(AVG(delivery_fee), 2) as avg_delivery_fee,
    ROUND(SUM(delivery_fee), 2) as total_revenue,
    ROUND(AVG(weight), 2) as avg_weight,
    ROUND(
        (COUNT(CASE WHEN status = 'delivered' THEN 1 END) * 100.0 / COUNT(*)), 2
    ) as success_rate,
    MIN(created_at) as first_shipment,
    MAX(created_at) as last_shipment,
    ROUND(AVG(DATEDIFF(actual_delivery_date, pickup_date)), 1) as avg_delivery_days
FROM couriers
WHERE pickup_city IS NOT NULL AND delivery_city IS NOT NULL
GROUP BY pickup_city, delivery_city
HAVING COUNT(*) >= 1
ORDER BY total_shipments DESC;

-- View: Recent Activity (Last 30 days)
CREATE OR REPLACE VIEW recent_activity AS
SELECT 
    'courier' as activity_type,
    c.id as reference_id,
    c.tracking_number as reference_code,
    CONCAT('Courier ', c.tracking_number, ' created') as description,
    u.name as user_name,
    c.created_at as activity_time
FROM couriers c
LEFT JOIN users u ON c.created_by = u.id
WHERE c.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)

UNION ALL

SELECT 
    'tracking' as activity_type,
    cth.courier_id as reference_id,
    c.tracking_number as reference_code,
    CONCAT('Status updated to ', cth.status, ' for ', c.tracking_number) as description,
    u.name as user_name,
    cth.created_at as activity_time
FROM courier_tracking_history cth
JOIN couriers c ON cth.courier_id = c.id
LEFT JOIN users u ON cth.updated_by = u.id
WHERE cth.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)

UNION ALL

SELECT 
    'assignment' as activity_type,
    ca.courier_id as reference_id,
    c.tracking_number as reference_code,
    CONCAT('Courier ', c.tracking_number, ' assigned to agent ', au.name) as description,
    u.name as user_name,
    ca.assigned_at as activity_time
FROM courier_assignments ca
JOIN couriers c ON ca.courier_id = c.id
JOIN agents a ON ca.agent_id = a.id
JOIN users au ON a.user_id = au.id
LEFT JOIN users u ON ca.assigned_by = u.id
WHERE ca.assigned_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)

ORDER BY activity_time DESC;

-- View: Overdue Shipments
CREATE OR REPLACE VIEW overdue_shipments AS
SELECT 
    c.id,
    c.tracking_number,
    c.sender_name,
    c.receiver_name,
    c.pickup_city,
    c.delivery_city,
    c.courier_type,
    c.status,
    c.priority,
    c.delivery_date,
    DATEDIFF(CURDATE(), c.delivery_date) as days_overdue,
    a.agent_code,
    u.name as agent_name,
    u.email as agent_email,
    c.created_at
FROM couriers c
LEFT JOIN agents a ON c.assigned_agent_id = a.id
LEFT JOIN users u ON a.user_id = u.id
WHERE c.status IN ('pending', 'in_transit') 
  AND c.delivery_date < CURDATE()
ORDER BY days_overdue DESC, c.priority DESC;

-- View: Monthly Revenue Report
CREATE OR REPLACE VIEW monthly_revenue AS
SELECT 
    YEAR(created_at) as year,
    MONTH(created_at) as month,
    MONTHNAME(created_at) as month_name,
    COUNT(*) as total_shipments,
    COUNT(CASE WHEN status = 'delivered' THEN 1 END) as delivered_shipments,
    ROUND(SUM(delivery_fee), 2) as total_revenue,
    ROUND(SUM(CASE WHEN status = 'delivered' THEN delivery_fee ELSE 0 END), 2) as delivered_revenue,
    ROUND(AVG(delivery_fee), 2) as avg_shipment_value,
    COUNT(DISTINCT assigned_agent_id) as active_agents,
    COUNT(DISTINCT customer_id) as active_customers
FROM couriers
GROUP BY YEAR(created_at), MONTH(created_at)
ORDER BY year DESC, month DESC;