-- Custom Functions for Courier Management System
USE courier_management_system;

DELIMITER //

-- Function: Calculate Delivery Fee
CREATE FUNCTION CalculateDeliveryFee(
    p_courier_type ENUM('standard', 'express', 'overnight', 'same-day'),
    p_weight DECIMAL(8,2),
    p_distance INT
) RETURNS DECIMAL(8,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_base_fee DECIMAL(8,2);
    DECLARE v_weight_fee DECIMAL(8,2) DEFAULT 0;
    DECLARE v_distance_fee DECIMAL(8,2) DEFAULT 0;
    DECLARE v_total_fee DECIMAL(8,2);
    
    -- Base fee by courier type
    CASE p_courier_type
        WHEN 'standard' THEN SET v_base_fee = 15.99;
        WHEN 'express' THEN SET v_base_fee = 25.99;
        WHEN 'overnight' THEN SET v_base_fee = 45.99;
        WHEN 'same-day' THEN SET v_base_fee = 65.99;
        ELSE SET v_base_fee = 15.99;
    END CASE;
    
    -- Additional weight fee (for packages over 5kg)
    IF p_weight > 5.0 THEN
        SET v_weight_fee = (p_weight - 5.0) * 2.50;
    END IF;
    
    -- Additional distance fee (for distances over 500 miles)
    IF p_distance > 500 THEN
        SET v_distance_fee = (p_distance - 500) * 0.10;
    END IF;
    
    SET v_total_fee = v_base_fee + v_weight_fee + v_distance_fee;
    
    RETURN ROUND(v_total_fee, 2);
END//

-- Function: Get Courier Status Display
CREATE FUNCTION GetCourierStatusDisplay(
    p_status ENUM('pending', 'in_transit', 'delivered', 'cancelled')
) RETURNS VARCHAR(50)
READS SQL DATA
DETERMINISTIC
BEGIN
    CASE p_status
        WHEN 'pending' THEN RETURN 'Pending Pickup';
        WHEN 'in_transit' THEN RETURN 'In Transit';
        WHEN 'delivered' THEN RETURN 'Delivered';
        WHEN 'cancelled' THEN RETURN 'Cancelled';
        ELSE RETURN 'Unknown Status';
    END CASE;
END//

-- Function: Calculate Delivery Days
CREATE FUNCTION CalculateDeliveryDays(
    p_courier_type ENUM('standard', 'express', 'overnight', 'same-day'),
    p_pickup_city VARCHAR(100),
    p_delivery_city VARCHAR(100)
) RETURNS INT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_base_days INT;
    DECLARE v_distance_factor INT DEFAULT 0;
    
    -- Base delivery days by courier type
    CASE p_courier_type
        WHEN 'same-day' THEN SET v_base_days = 0;
        WHEN 'overnight' THEN SET v_base_days = 1;
        WHEN 'express' THEN SET v_base_days = 2;
        WHEN 'standard' THEN SET v_base_days = 5;
        ELSE SET v_base_days = 5;
    END CASE;
    
    -- Add extra days for cross-country shipments (simplified logic)
    IF (p_pickup_city IN ('New York', 'Boston', 'Philadelphia') AND p_delivery_city IN ('Los Angeles', 'San Francisco', 'Seattle')) OR
       (p_pickup_city IN ('Los Angeles', 'San Francisco', 'Seattle') AND p_delivery_city IN ('New York', 'Boston', 'Philadelphia')) THEN
        SET v_distance_factor = 1;
    END IF;
    
    -- Same-day delivery only available within same city
    IF p_courier_type = 'same-day' AND p_pickup_city != p_delivery_city THEN
        SET v_base_days = 1; -- Convert to overnight
    END IF;
    
    RETURN v_base_days + v_distance_factor;
END//

-- Function: Get Agent Workload
CREATE FUNCTION GetAgentWorkload(
    p_agent_id INT,
    p_date DATE
) RETURNS INT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_workload INT DEFAULT 0;
    
    SELECT COUNT(*) INTO v_workload
    FROM couriers
    WHERE assigned_agent_id = p_agent_id
      AND DATE(created_at) = p_date
      AND status IN ('pending', 'in_transit');
    
    RETURN v_workload;
END//

-- Function: Is Agent Available
CREATE FUNCTION IsAgentAvailable(
    p_agent_id INT,
    p_date DATE
) RETURNS BOOLEAN
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_is_available BOOLEAN DEFAULT FALSE;
    DECLARE v_current_workload INT;
    DECLARE v_max_orders INT;
    DECLARE v_availability BOOLEAN;
    DECLARE v_user_status VARCHAR(20);
    
    -- Get agent details
    SELECT a.availability, a.max_daily_orders, u.status
    INTO v_availability, v_max_orders, v_user_status
    FROM agents a
    JOIN users u ON a.user_id = u.id
    WHERE a.id = p_agent_id;
    
    -- Check if agent is active and available
    IF v_availability = TRUE AND v_user_status = 'active' THEN
        -- Get current workload
        SET v_current_workload = GetAgentWorkload(p_agent_id, p_date);
        
        -- Check if under max daily orders
        IF v_current_workload < v_max_orders THEN
            SET v_is_available = TRUE;
        END IF;
    END IF;
    
    RETURN v_is_available;
END//

-- Function: Get Estimated Delivery Date
CREATE FUNCTION GetEstimatedDeliveryDate(
    p_courier_type ENUM('standard', 'express', 'overnight', 'same-day'),
    p_pickup_city VARCHAR(100),
    p_delivery_city VARCHAR(100),
    p_pickup_date DATE
) RETURNS DATE
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_delivery_days INT;
    DECLARE v_estimated_date DATE;
    
    SET v_delivery_days = CalculateDeliveryDays(p_courier_type, p_pickup_city, p_delivery_city);
    
    -- Add business days (skip weekends for standard delivery)
    IF p_courier_type = 'standard' THEN
        SET v_estimated_date = p_pickup_date;
        WHILE v_delivery_days > 0 DO
            SET v_estimated_date = DATE_ADD(v_estimated_date, INTERVAL 1 DAY);
            -- Skip weekends
            IF DAYOFWEEK(v_estimated_date) NOT IN (1, 7) THEN
                SET v_delivery_days = v_delivery_days - 1;
            END IF;
        END WHILE;
    ELSE
        SET v_estimated_date = DATE_ADD(p_pickup_date, INTERVAL v_delivery_days DAY);
    END IF;
    
    RETURN v_estimated_date;
END//

-- Function: Get Customer Tier
CREATE FUNCTION GetCustomerTier(
    p_customer_id INT
) RETURNS VARCHAR(20)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_total_spent DECIMAL(10,2);
    DECLARE v_tier VARCHAR(20);
    
    SELECT total_spent INTO v_total_spent
    FROM customers
    WHERE id = p_customer_id;
    
    CASE
        WHEN v_total_spent >= 10000 THEN SET v_tier = 'Platinum';
        WHEN v_total_spent >= 5000 THEN SET v_tier = 'Gold';
        WHEN v_total_spent >= 1000 THEN SET v_tier = 'Silver';
        ELSE SET v_tier = 'Bronze';
    END CASE;
    
    RETURN v_tier;
END//

-- Function: Calculate Distance Between Cities (simplified)
CREATE FUNCTION CalculateDistance(
    p_city1 VARCHAR(100),
    p_city2 VARCHAR(100)
) RETURNS INT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_distance INT DEFAULT 500; -- Default distance
    
    -- Simplified distance calculation (in real implementation, use coordinates)
    -- This is just sample data for demonstration
    CASE
        WHEN (p_city1 = 'New York' AND p_city2 = 'Los Angeles') OR (p_city1 = 'Los Angeles' AND p_city2 = 'New York') THEN SET v_distance = 2445;
        WHEN (p_city1 = 'New York' AND p_city2 = 'Chicago') OR (p_city1 = 'Chicago' AND p_city2 = 'New York') THEN SET v_distance = 790;
        WHEN (p_city1 = 'Los Angeles' AND p_city2 = 'San Francisco') OR (p_city1 = 'San Francisco' AND p_city2 = 'Los Angeles') THEN SET v_distance = 380;
        WHEN (p_city1 = 'Houston' AND p_city2 = 'Dallas') OR (p_city1 = 'Dallas' AND p_city2 = 'Houston') THEN SET v_distance = 240;
        WHEN p_city1 = p_city2 THEN SET v_distance = 0;
        ELSE SET v_distance = 500; -- Default for unknown routes
    END CASE;
    
    RETURN v_distance;
END//

-- Function: Get Business Days Between Dates
CREATE FUNCTION GetBusinessDays(
    p_start_date DATE,
    p_end_date DATE
) RETURNS INT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_days INT DEFAULT 0;
    DECLARE v_current_date DATE;
    
    SET v_current_date = p_start_date;
    
    WHILE v_current_date <= p_end_date DO
        -- Count only weekdays (Monday = 2, Friday = 6)
        IF DAYOFWEEK(v_current_date) BETWEEN 2 AND 6 THEN
            SET v_days = v_days + 1;
        END IF;
        SET v_current_date = DATE_ADD(v_current_date, INTERVAL 1 DAY);
    END WHILE;
    
    RETURN v_days;
END//

-- Function: Format Tracking Status for Display
CREATE FUNCTION FormatTrackingStatus(
    p_status ENUM('pending', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed_delivery', 'cancelled')
) RETURNS VARCHAR(50)
READS SQL DATA
DETERMINISTIC
BEGIN
    CASE p_status
        WHEN 'pending' THEN RETURN 'Package Received';
        WHEN 'picked_up' THEN RETURN 'Picked Up';
        WHEN 'in_transit' THEN RETURN 'In Transit';
        WHEN 'out_for_delivery' THEN RETURN 'Out for Delivery';
        WHEN 'delivered' THEN RETURN 'Delivered';
        WHEN 'failed_delivery' THEN RETURN 'Delivery Failed';
        WHEN 'cancelled' THEN RETURN 'Cancelled';
        ELSE RETURN 'Unknown Status';
    END CASE;
END//

DELIMITER ;