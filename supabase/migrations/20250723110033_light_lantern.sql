-- Stored Procedures for Courier Management System
USE courier_management_system;

DELIMITER //

-- Procedure: Create New Courier
CREATE PROCEDURE CreateCourier(
    IN p_sender_name VARCHAR(255),
    IN p_sender_phone VARCHAR(20),
    IN p_sender_address TEXT,
    IN p_receiver_name VARCHAR(255),
    IN p_receiver_phone VARCHAR(20),
    IN p_receiver_address TEXT,
    IN p_pickup_city VARCHAR(100),
    IN p_delivery_city VARCHAR(100),
    IN p_courier_type ENUM('standard', 'express', 'overnight', 'same-day'),
    IN p_weight DECIMAL(8,2),
    IN p_package_value DECIMAL(10,2),
    IN p_delivery_date DATE,
    IN p_special_instructions TEXT,
    IN p_created_by INT,
    IN p_customer_id INT,
    OUT p_tracking_number VARCHAR(50),
    OUT p_courier_id INT
)
BEGIN
    DECLARE v_tracking_number VARCHAR(50);
    DECLARE v_delivery_fee DECIMAL(8,2);
    DECLARE v_pickup_city_id INT;
    DECLARE v_delivery_city_id INT;
    
    -- Generate tracking number
    SET v_tracking_number = CONCAT('CMS', LPAD(FLOOR(RAND() * 999999), 6, '0'));
    
    -- Ensure tracking number is unique
    WHILE EXISTS(SELECT 1 FROM couriers WHERE tracking_number = v_tracking_number) DO
        SET v_tracking_number = CONCAT('CMS', LPAD(FLOOR(RAND() * 999999), 6, '0'));
    END WHILE;
    
    -- Calculate delivery fee based on courier type
    CASE p_courier_type
        WHEN 'standard' THEN SET v_delivery_fee = 15.99;
        WHEN 'express' THEN SET v_delivery_fee = 25.99;
        WHEN 'overnight' THEN SET v_delivery_fee = 45.99;
        WHEN 'same-day' THEN SET v_delivery_fee = 65.99;
        ELSE SET v_delivery_fee = 15.99;
    END CASE;
    
    -- Get city IDs
    SELECT id INTO v_pickup_city_id FROM cities WHERE name = p_pickup_city LIMIT 1;
    SELECT id INTO v_delivery_city_id FROM cities WHERE name = p_delivery_city LIMIT 1;
    
    -- Insert courier
    INSERT INTO couriers (
        tracking_number, sender_name, sender_phone, sender_address,
        receiver_name, receiver_phone, receiver_address,
        pickup_city_id, delivery_city_id, pickup_city, delivery_city,
        courier_type, weight, package_value, delivery_fee,
        delivery_date, special_instructions, created_by, customer_id
    ) VALUES (
        v_tracking_number, p_sender_name, p_sender_phone, p_sender_address,
        p_receiver_name, p_receiver_phone, p_receiver_address,
        v_pickup_city_id, v_delivery_city_id, p_pickup_city, p_delivery_city,
        p_courier_type, p_weight, p_package_value, v_delivery_fee,
        p_delivery_date, p_special_instructions, p_created_by, p_customer_id
    );
    
    SET p_courier_id = LAST_INSERT_ID();
    SET p_tracking_number = v_tracking_number;
    
    -- Add initial tracking entry
    INSERT INTO courier_tracking_history (courier_id, status, location, description, updated_by)
    VALUES (p_courier_id, 'pending', p_pickup_city, 'Package received at origin facility', p_created_by);
    
END//

-- Procedure: Update Courier Status
CREATE PROCEDURE UpdateCourierStatus(
    IN p_courier_id INT,
    IN p_status ENUM('pending', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed_delivery', 'cancelled'),
    IN p_location VARCHAR(255),
    IN p_description TEXT,
    IN p_updated_by INT
)
BEGIN
    DECLARE v_courier_status ENUM('pending', 'in_transit', 'delivered', 'cancelled');
    
    -- Map tracking status to courier status
    CASE p_status
        WHEN 'pending' THEN SET v_courier_status = 'pending';
        WHEN 'picked_up' THEN SET v_courier_status = 'in_transit';
        WHEN 'in_transit' THEN SET v_courier_status = 'in_transit';
        WHEN 'out_for_delivery' THEN SET v_courier_status = 'in_transit';
        WHEN 'delivered' THEN SET v_courier_status = 'delivered';
        WHEN 'failed_delivery' THEN SET v_courier_status = 'in_transit';
        WHEN 'cancelled' THEN SET v_courier_status = 'cancelled';
        ELSE SET v_courier_status = 'pending';
    END CASE;
    
    -- Update courier status
    UPDATE couriers 
    SET status = v_courier_status,
        actual_delivery_date = CASE WHEN p_status = 'delivered' THEN CURDATE() ELSE actual_delivery_date END,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = p_courier_id;
    
    -- Add tracking history entry
    INSERT INTO courier_tracking_history (courier_id, status, location, description, updated_by)
    VALUES (p_courier_id, p_status, p_location, p_description, p_updated_by);
    
END//

-- Procedure: Assign Courier to Agent
CREATE PROCEDURE AssignCourierToAgent(
    IN p_courier_id INT,
    IN p_agent_id INT,
    IN p_assigned_by INT,
    IN p_notes TEXT
)
BEGIN
    DECLARE v_current_agent_id INT;
    
    -- Get current agent assignment
    SELECT assigned_agent_id INTO v_current_agent_id FROM couriers WHERE id = p_courier_id;
    
    -- Update courier assignment
    UPDATE couriers 
    SET assigned_agent_id = p_agent_id, updated_at = CURRENT_TIMESTAMP
    WHERE id = p_courier_id;
    
    -- Close previous assignment if exists
    IF v_current_agent_id IS NOT NULL THEN
        UPDATE courier_assignments 
        SET status = 'cancelled', notes = CONCAT(IFNULL(notes, ''), ' - Reassigned to another agent')
        WHERE courier_id = p_courier_id AND agent_id = v_current_agent_id AND status = 'active';
    END IF;
    
    -- Create new assignment record
    INSERT INTO courier_assignments (courier_id, agent_id, assigned_by, notes)
    VALUES (p_courier_id, p_agent_id, p_assigned_by, p_notes);
    
END//

-- Procedure: Get Agent Performance Report
CREATE PROCEDURE GetAgentPerformanceReport(
    IN p_agent_id INT,
    IN p_date_from DATE,
    IN p_date_to DATE
)
BEGIN
    SELECT 
        a.agent_code,
        u.name as agent_name,
        COUNT(c.id) as total_couriers,
        COUNT(CASE WHEN c.status = 'delivered' THEN 1 END) as delivered_couriers,
        COUNT(CASE WHEN c.status = 'in_transit' THEN 1 END) as in_transit_couriers,
        COUNT(CASE WHEN c.status = 'pending' THEN 1 END) as pending_couriers,
        COUNT(CASE WHEN c.status = 'cancelled' THEN 1 END) as cancelled_couriers,
        ROUND(AVG(c.delivery_fee), 2) as avg_delivery_fee,
        ROUND(SUM(c.delivery_fee), 2) as total_revenue,
        ROUND(
            (COUNT(CASE WHEN c.status = 'delivered' THEN 1 END) * 100.0 / 
             NULLIF(COUNT(CASE WHEN c.status != 'pending' THEN 1 END), 0)), 2
        ) as success_rate,
        COUNT(CASE WHEN c.status = 'delivered' AND c.actual_delivery_date <= c.delivery_date THEN 1 END) as on_time_deliveries,
        ROUND(
            (COUNT(CASE WHEN c.status = 'delivered' AND c.actual_delivery_date <= c.delivery_date THEN 1 END) * 100.0 / 
             NULLIF(COUNT(CASE WHEN c.status = 'delivered' THEN 1 END), 0)), 2
        ) as on_time_percentage
    FROM agents a
    JOIN users u ON a.user_id = u.id
    LEFT JOIN couriers c ON a.id = c.assigned_agent_id 
        AND c.created_at BETWEEN p_date_from AND DATE_ADD(p_date_to, INTERVAL 1 DAY)
    WHERE a.id = p_agent_id
    GROUP BY a.id, a.agent_code, u.name;
END//

-- Procedure: Get Daily Statistics
CREATE PROCEDURE GetDailyStatistics(
    IN p_date DATE
)
BEGIN
    SELECT 
        p_date as report_date,
        COUNT(*) as total_couriers,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_couriers,
        COUNT(CASE WHEN status = 'in_transit' THEN 1 END) as in_transit_couriers,
        COUNT(CASE WHEN status = 'delivered' THEN 1 END) as delivered_couriers,
        COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_couriers,
        ROUND(AVG(weight), 2) as avg_weight,
        ROUND(AVG(delivery_fee), 2) as avg_delivery_fee,
        ROUND(SUM(delivery_fee), 2) as total_revenue,
        COUNT(DISTINCT assigned_agent_id) as active_agents,
        COUNT(DISTINCT customer_id) as active_customers
    FROM couriers
    WHERE DATE(created_at) = p_date;
END//

-- Procedure: Search Couriers
CREATE PROCEDURE SearchCouriers(
    IN p_search_term VARCHAR(255),
    IN p_status VARCHAR(50),
    IN p_courier_type VARCHAR(50),
    IN p_date_from DATE,
    IN p_date_to DATE,
    IN p_limit INT,
    IN p_offset INT
)
BEGIN
    SET @sql = 'SELECT c.*, u.name as created_by_name, au.name as agent_name 
                FROM couriers c 
                LEFT JOIN users u ON c.created_by = u.id 
                LEFT JOIN agents a ON c.assigned_agent_id = a.id 
                LEFT JOIN users au ON a.user_id = au.id 
                WHERE 1=1';
    
    IF p_search_term IS NOT NULL AND p_search_term != '' THEN
        SET @sql = CONCAT(@sql, ' AND (c.tracking_number LIKE "%', p_search_term, '%" 
                                 OR c.sender_name LIKE "%', p_search_term, '%" 
                                 OR c.receiver_name LIKE "%', p_search_term, '%" 
                                 OR c.pickup_city LIKE "%', p_search_term, '%" 
                                 OR c.delivery_city LIKE "%', p_search_term, '%")');
    END IF;
    
    IF p_status IS NOT NULL AND p_status != 'all' THEN
        SET @sql = CONCAT(@sql, ' AND c.status = "', p_status, '"');
    END IF;
    
    IF p_courier_type IS NOT NULL AND p_courier_type != 'all' THEN
        SET @sql = CONCAT(@sql, ' AND c.courier_type = "', p_courier_type, '"');
    END IF;
    
    IF p_date_from IS NOT NULL THEN
        SET @sql = CONCAT(@sql, ' AND DATE(c.created_at) >= "', p_date_from, '"');
    END IF;
    
    IF p_date_to IS NOT NULL THEN
        SET @sql = CONCAT(@sql, ' AND DATE(c.created_at) <= "', p_date_to, '"');
    END IF;
    
    SET @sql = CONCAT(@sql, ' ORDER BY c.created_at DESC');
    
    IF p_limit IS NOT NULL THEN
        SET @sql = CONCAT(@sql, ' LIMIT ', p_limit);
        IF p_offset IS NOT NULL THEN
            SET @sql = CONCAT(@sql, ' OFFSET ', p_offset);
        END IF;
    END IF;
    
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END//

-- Procedure: Auto Assign Couriers to Available Agents
CREATE PROCEDURE AutoAssignCouriers()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_courier_id INT;
    DECLARE v_pickup_city_id INT;
    DECLARE v_agent_id INT;
    
    DECLARE courier_cursor CURSOR FOR
        SELECT id, pickup_city_id 
        FROM couriers 
        WHERE assigned_agent_id IS NULL 
          AND status = 'pending'
        ORDER BY priority DESC, created_at ASC;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN courier_cursor;
    
    courier_loop: LOOP
        FETCH courier_cursor INTO v_courier_id, v_pickup_city_id;
        IF done THEN
            LEAVE courier_loop;
        END IF;
        
        -- Find available agent in the same city with auto_assign enabled
        SELECT a.id INTO v_agent_id
        FROM agents a
        JOIN users u ON a.user_id = u.id
        WHERE a.city_id = v_pickup_city_id
          AND a.availability = TRUE
          AND a.auto_assign = TRUE
          AND u.status = 'active'
          AND (
              SELECT COUNT(*) 
              FROM couriers c 
              WHERE c.assigned_agent_id = a.id 
                AND DATE(c.created_at) = CURDATE()
          ) < a.max_daily_orders
        ORDER BY a.total_couriers ASC
        LIMIT 1;
        
        -- If agent found, assign courier
        IF v_agent_id IS NOT NULL THEN
            CALL AssignCourierToAgent(v_courier_id, v_agent_id, 1, 'Auto-assigned by system');
            SET v_agent_id = NULL;
        END IF;
        
    END LOOP;
    
    CLOSE courier_cursor;
END//

-- Procedure: Generate Tracking Number
CREATE PROCEDURE GenerateTrackingNumber(
    OUT p_tracking_number VARCHAR(50)
)
BEGIN
    DECLARE v_tracking_number VARCHAR(50);
    
    -- Generate tracking number
    SET v_tracking_number = CONCAT('CMS', LPAD(FLOOR(RAND() * 999999), 6, '0'));
    
    -- Ensure tracking number is unique
    WHILE EXISTS(SELECT 1 FROM couriers WHERE tracking_number = v_tracking_number) DO
        SET v_tracking_number = CONCAT('CMS', LPAD(FLOOR(RAND() * 999999), 6, '0'));
    END WHILE;
    
    SET p_tracking_number = v_tracking_number;
END//

-- Procedure: Update Customer Statistics
CREATE PROCEDURE UpdateCustomerStatistics(
    IN p_customer_id INT
)
BEGIN
    UPDATE customers c
    SET 
        total_orders = (
            SELECT COUNT(*) 
            FROM couriers co 
            WHERE co.customer_id = c.id
        ),
        total_spent = (
            SELECT IFNULL(SUM(delivery_fee), 0) 
            FROM couriers co 
            WHERE co.customer_id = c.id 
              AND co.status = 'delivered'
        ),
        last_order_date = (
            SELECT MAX(DATE(created_at)) 
            FROM couriers co 
            WHERE co.customer_id = c.id
        )
    WHERE c.id = p_customer_id;
END//

DELIMITER ;