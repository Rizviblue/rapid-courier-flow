<?php
/**
 * Courier Model Class
 */

class CourierModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll($filters = []) {
        $sql = "SELECT c.*, u1.name as created_by_name, u2.name as assigned_agent_name 
                FROM couriers c 
                LEFT JOIN users u1 ON c.created_by = u1.id 
                LEFT JOIN users u2 ON c.assigned_agent = u2.id 
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND c.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (c.tracking_number LIKE ? OR c.sender_name LIKE ? OR c.receiver_name LIKE ? OR c.pickup_city LIKE ? OR c.delivery_city LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($filters['created_by'])) {
            $sql .= " AND c.created_by = ?";
            $params[] = $filters['created_by'];
        }
        
        if (!empty($filters['assigned_agent'])) {
            $sql .= " AND c.assigned_agent = ?";
            $params[] = $filters['assigned_agent'];
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT ?";
            $params[] = (int)$filters['limit'];
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getById($id) {
        return $this->db->fetch(
            "SELECT c.*, u1.name as created_by_name, u2.name as assigned_agent_name 
             FROM couriers c 
             LEFT JOIN users u1 ON c.created_by = u1.id 
             LEFT JOIN users u2 ON c.assigned_agent = u2.id 
             WHERE c.id = ?",
            [$id]
        );
    }
    
    public function getByTrackingNumber($trackingNumber) {
        return $this->db->fetch(
            "SELECT c.*, u1.name as created_by_name, u2.name as assigned_agent_name 
             FROM couriers c 
             LEFT JOIN users u1 ON c.created_by = u1.id 
             LEFT JOIN users u2 ON c.assigned_agent = u2.id 
             WHERE c.tracking_number = ?",
            [$trackingNumber]
        );
    }
    
    public function create($data) {
        $trackingNumber = generate_tracking_number();
        
        // Ensure unique tracking number
        while ($this->getByTrackingNumber($trackingNumber)) {
            $trackingNumber = generate_tracking_number();
        }
        
        $sql = "INSERT INTO couriers (
                    tracking_number, sender_name, sender_phone, sender_address,
                    receiver_name, receiver_phone, receiver_address,
                    pickup_city, delivery_city, courier_type, weight,
                    delivery_date, status, notes, created_by, assigned_agent
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $trackingNumber,
            $data['sender_name'],
            $data['sender_phone'] ?? null,
            $data['sender_address'] ?? null,
            $data['receiver_name'],
            $data['receiver_phone'] ?? null,
            $data['receiver_address'] ?? null,
            $data['pickup_city'],
            $data['delivery_city'],
            $data['courier_type'],
            $data['weight'] ?? 0,
            $data['delivery_date'] ?? null,
            $data['status'] ?? 'pending',
            $data['notes'] ?? null,
            $data['created_by'] ?? null,
            $data['assigned_agent'] ?? null
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $sql = "UPDATE couriers SET 
                sender_name = ?, sender_phone = ?, sender_address = ?,
                receiver_name = ?, receiver_phone = ?, receiver_address = ?,
                pickup_city = ?, delivery_city = ?, courier_type = ?, weight = ?,
                delivery_date = ?, status = ?, notes = ?, assigned_agent = ?,
                updated_at = NOW()
                WHERE id = ?";
        
        $params = [
            $data['sender_name'],
            $data['sender_phone'] ?? null,
            $data['sender_address'] ?? null,
            $data['receiver_name'],
            $data['receiver_phone'] ?? null,
            $data['receiver_address'] ?? null,
            $data['pickup_city'],
            $data['delivery_city'],
            $data['courier_type'],
            $data['weight'] ?? 0,
            $data['delivery_date'] ?? null,
            $data['status'],
            $data['notes'] ?? null,
            $data['assigned_agent'] ?? null,
            $id
        ];
        
        return $this->db->query($sql, $params);
    }
    
    public function delete($id) {
        return $this->db->query("DELETE FROM couriers WHERE id = ?", [$id]);
    }
    
    public function getStats($userId = null, $role = null) {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'in_transit' THEN 1 ELSE 0 END) as in_transit,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
                FROM couriers WHERE 1=1";
        
        $params = [];
        
        if ($role === 'agent' && $userId) {
            $sql .= " AND assigned_agent = ?";
            $params[] = $userId;
        } elseif ($role === 'user' && $userId) {
            $sql .= " AND created_by = ?";
            $params[] = $userId;
        }
        
        return $this->db->fetch($sql, $params);
    }
    
    public function getRecentActivity($limit = 10, $userId = null, $role = null) {
        $sql = "SELECT c.*, u1.name as created_by_name 
                FROM couriers c 
                LEFT JOIN users u1 ON c.created_by = u1.id 
                WHERE 1=1";
        
        $params = [];
        
        if ($role === 'agent' && $userId) {
            $sql .= " AND c.assigned_agent = ?";
            $params[] = $userId;
        } elseif ($role === 'user' && $userId) {
            $sql .= " AND c.created_by = ?";
            $params[] = $userId;
        }
        
        $sql .= " ORDER BY c.updated_at DESC LIMIT ?";
        $params[] = $limit;
        
        return $this->db->fetchAll($sql, $params);
    }
}