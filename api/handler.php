<?php
header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($action) {
        case 'track':
            if ($method === 'POST') {
                $trackingNumber = sanitizeInput($_POST['tracking_number'] ?? '');
                
                if (empty($trackingNumber)) {
                    echo json_encode(['success' => false, 'message' => 'Tracking number is required']);
                    exit;
                }
                
                $courier = trackCourier($trackingNumber);
                
                if ($courier) {
                    echo json_encode(['success' => true, 'courier' => $courier]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Courier not found']);
                }
            }
            break;
            
        case 'update_status':
            if ($method === 'POST' && hasRole(['admin', 'agent'])) {
                $courierId = intval($_POST['courier_id'] ?? 0);
                $status = sanitizeInput($_POST['status'] ?? '');
                $location = sanitizeInput($_POST['location'] ?? '');
                $notes = sanitizeInput($_POST['notes'] ?? '');
                
                if ($courierId <= 0 || empty($status)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
                    exit;
                }
                
                addTrackingUpdate($courierId, $status, $location, $notes, $_SESSION['user_id']);
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            }
            break;
            
        case 'delete_courier':
            if ($method === 'POST' && hasRole(['admin', 'agent'])) {
                $courierId = intval($_POST['courier_id'] ?? 0);
                
                if ($courierId <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Invalid courier ID']);
                    exit;
                }
                
                $db = getDB();
                $db->query("DELETE FROM couriers WHERE id = ?", [$courierId]);
                
                echo json_encode(['success' => true, 'message' => 'Courier deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            }
            break;
            
        case 'search_couriers':
            if ($method === 'GET') {
                $query = sanitizeInput($_GET['q'] ?? '');
                $status = sanitizeInput($_GET['status'] ?? '');
                $user = getCurrentUser();
                
                $sql = "SELECT * FROM couriers WHERE 1=1";
                $params = [];
                
                // Role-based filtering
                if ($user['role'] !== 'admin') {
                    if ($user['role'] === 'agent') {
                        $sql .= " AND (created_by = ? OR assigned_agent = ?)";
                        $params[] = $user['id'];
                        $params[] = $user['id'];
                    } else {
                        $sql .= " AND created_by = ?";
                        $params[] = $user['id'];
                    }
                }
                
                // Search filter
                if (!empty($query)) {
                    $sql .= " AND (tracking_number LIKE ? OR sender_name LIKE ? OR receiver_name LIKE ? OR pickup_city LIKE ? OR delivery_city LIKE ?)";
                    $searchTerm = "%{$query}%";
                    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
                }
                
                // Status filter
                if (!empty($status)) {
                    $sql .= " AND status = ?";
                    $params[] = $status;
                }
                
                $sql .= " ORDER BY created_at DESC LIMIT 50";
                
                $db = getDB();
                $couriers = $db->fetchAll($sql, $params);
                
                echo json_encode(['success' => true, 'couriers' => $couriers]);
            }
            break;
            
        case 'get_stats':
            if ($method === 'GET') {
                $stats = getCourierStats();
                $userStats = getUserStats();
                
                echo json_encode([
                    'success' => true, 
                    'courier_stats' => $stats,
                    'user_stats' => $userStats
                ]);
            }
            break;
            
        case 'update_user':
            if ($method === 'POST' && hasRole('admin')) {
                $userId = intval($_POST['user_id'] ?? 0);
                $name = sanitizeInput($_POST['name'] ?? '');
                $email = sanitizeInput($_POST['email'] ?? '');
                $role = sanitizeInput($_POST['role'] ?? '');
                $status = sanitizeInput($_POST['status'] ?? '');
                
                if ($userId <= 0 || empty($name) || empty($email)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
                    exit;
                }
                
                $db = getDB();
                $db->query("UPDATE users SET name = ?, email = ?, role = ?, status = ? WHERE id = ?", 
                          [$name, $email, $role, $status, $userId]);
                
                echo json_encode(['success' => true, 'message' => 'User updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            }
            break;
            
        case 'delete_user':
            if ($method === 'POST' && hasRole('admin')) {
                $userId = intval($_POST['user_id'] ?? 0);
                
                if ($userId <= 0 || $userId == $_SESSION['user_id']) {
                    echo json_encode(['success' => false, 'message' => 'Cannot delete this user']);
                    exit;
                }
                
                $db = getDB();
                $db->query("DELETE FROM users WHERE id = ?", [$userId]);
                
                echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            }
            break;
            
        case 'analytics_data':
            if ($method === 'GET' && hasRole(['admin', 'agent'])) {
                $db = getDB();
                
                // Monthly courier stats
                $monthlyStats = $db->fetchAll("
                    SELECT 
                        DATE_FORMAT(created_at, '%Y-%m') as month,
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
                        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
                    FROM couriers 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY month ASC
                ");
                
                // Status distribution
                $statusStats = $db->fetchAll("
                    SELECT status, COUNT(*) as count 
                    FROM couriers 
                    GROUP BY status
                ");
                
                // Top cities
                $topCities = $db->fetchAll("
                    SELECT pickup_city as city, COUNT(*) as count 
                    FROM couriers 
                    GROUP BY pickup_city 
                    ORDER BY count DESC 
                    LIMIT 10
                ");
                
                echo json_encode([
                    'success' => true,
                    'monthly_stats' => $monthlyStats,
                    'status_stats' => $statusStats,
                    'top_cities' => $topCities
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>