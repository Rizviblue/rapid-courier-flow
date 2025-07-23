<?php

function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

function formatDateTime($datetime, $format = 'M d, Y H:i') {
    return date($format, strtotime($datetime));
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[+]?[0-9\s\-\(\)]{10,15}$/', $phone);
}

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

function getStatusBadgeClass($status) {
    switch ($status) {
        case 'pending':
            return 'badge-warning';
        case 'in_transit':
            return 'badge-info';
        case 'delivered':
            return 'badge-success';
        case 'cancelled':
            return 'badge-danger';
        case 'active':
            return 'badge-success';
        case 'inactive':
            return 'badge-secondary';
        default:
            return 'badge-secondary';
    }
}

function getStatusText($status) {
    switch ($status) {
        case 'pending':
            return 'Pending';
        case 'in_transit':
            return 'In Transit';
        case 'delivered':
            return 'Delivered';
        case 'cancelled':
            return 'Cancelled';
        case 'active':
            return 'Active';
        case 'inactive':
            return 'Inactive';
        default:
            return ucfirst($status);
    }
}

function showAlert($type, $message, $dismissible = true) {
    $class = 'alert-' . $type;
    $dismissBtn = $dismissible ? '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' : '';
    
    echo "<div class='alert {$class} alert-dismissible fade show' role='alert'>
            {$message}
            {$dismissBtn}
          </div>";
}

function getCourierStats() {
    $db = getDB();
    $stats = [];
    
    $stats['total'] = $db->fetch("SELECT COUNT(*) as count FROM couriers")['count'];
    $stats['pending'] = $db->fetch("SELECT COUNT(*) as count FROM couriers WHERE status = 'pending'")['count'];
    $stats['in_transit'] = $db->fetch("SELECT COUNT(*) as count FROM couriers WHERE status = 'in_transit'")['count'];
    $stats['delivered'] = $db->fetch("SELECT COUNT(*) as count FROM couriers WHERE status = 'delivered'")['count'];
    $stats['cancelled'] = $db->fetch("SELECT COUNT(*) as count FROM couriers WHERE status = 'cancelled'")['count'];
    
    return $stats;
}

function getUserStats() {
    $db = getDB();
    $stats = [];
    
    $stats['total'] = $db->fetch("SELECT COUNT(*) as count FROM users")['count'];
    $stats['admin'] = $db->fetch("SELECT COUNT(*) as count FROM users WHERE role = 'admin'")['count'];
    $stats['agent'] = $db->fetch("SELECT COUNT(*) as count FROM users WHERE role = 'agent'")['count'];
    $stats['user'] = $db->fetch("SELECT COUNT(*) as count FROM users WHERE role = 'user'")['count'];
    $stats['active'] = $db->fetch("SELECT COUNT(*) as count FROM users WHERE status = 'active'")['count'];
    
    return $stats;
}

function getCouriersByUser($userId, $role) {
    $db = getDB();
    
    if ($role === 'admin') {
        return $db->fetchAll("SELECT * FROM couriers ORDER BY created_at DESC");
    } elseif ($role === 'agent') {
        return $db->fetchAll("SELECT * FROM couriers WHERE created_by = ? OR assigned_agent = ? ORDER BY created_at DESC", [$userId, $userId]);
    } else {
        return $db->fetchAll("SELECT * FROM couriers WHERE created_by = ? ORDER BY created_at DESC", [$userId]);
    }
}

function trackCourier($trackingNumber) {
    $db = getDB();
    $courier = $db->fetch("SELECT c.*, u.name as created_by_name FROM couriers c LEFT JOIN users u ON c.created_by = u.id WHERE c.tracking_number = ?", [$trackingNumber]);
    
    if ($courier) {
        $tracking = $db->fetchAll("SELECT ct.*, u.name as updated_by_name FROM courier_tracking ct LEFT JOIN users u ON ct.updated_by = u.id WHERE ct.courier_id = ? ORDER BY ct.created_at ASC", [$courier['id']]);
        $courier['tracking'] = $tracking;
    }
    
    return $courier;
}

function addTrackingUpdate($courierId, $status, $location = '', $notes = '', $updatedBy = null) {
    $db = getDB();
    
    // Insert tracking update
    $db->query("INSERT INTO courier_tracking (courier_id, status, location, notes, updated_by) VALUES (?, ?, ?, ?, ?)", 
               [$courierId, $status, $location, $notes, $updatedBy]);
    
    // Update courier status
    $db->query("UPDATE couriers SET status = ? WHERE id = ?", [$status, $courierId]);
}

function getSetting($key, $default = '') {
    $db = getDB();
    $setting = $db->fetch("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
    return $setting ? $setting['setting_value'] : $default;
}

function updateSetting($key, $value, $updatedBy = null) {
    $db = getDB();
    $existing = $db->fetch("SELECT id FROM settings WHERE setting_key = ?", [$key]);
    
    if ($existing) {
        $db->query("UPDATE settings SET setting_value = ?, updated_by = ? WHERE setting_key = ?", [$value, $updatedBy, $key]);
    } else {
        $db->query("INSERT INTO settings (setting_key, setting_value, updated_by) VALUES (?, ?, ?)", [$key, $value, $updatedBy]);
    }
}

function calculateCourierCost($weight, $type, $distance = null) {
    $baseCost = 10;
    $weightCost = $weight * 2;
    $typeCost = 0;
    
    switch ($type) {
        case 'Express':
            $typeCost = 15;
            break;
        case 'Standard':
            $typeCost = 5;
            break;
        case 'Economy':
            $typeCost = 0;
            break;
    }
    
    return $baseCost + $weightCost + $typeCost;
}
?>