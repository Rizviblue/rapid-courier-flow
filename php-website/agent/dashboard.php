<?php
require_once '../includes/auth.php';

// Require agent role
Auth::requireRole('agent');

$user = Auth::getCurrentUser();
$db = Database::getInstance()->getConnection();

// Get agent-specific statistics
try {
    // Assigned couriers
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM couriers WHERE agent_id = ?");
    $stmt->execute([$user['id']]);
    $assignedCouriers = $stmt->fetch()['total'];
    
    // Active deliveries
    $stmt = $db->prepare("SELECT COUNT(*) as active FROM couriers WHERE agent_id = ? AND status IN ('picked_up', 'in_transit', 'out_for_delivery')");
    $stmt->execute([$user['id']]);
    $activeDeliveries = $stmt->fetch()['active'];
    
    // Completed deliveries today
    $stmt = $db->prepare("SELECT COUNT(*) as completed FROM couriers WHERE agent_id = ? AND status = 'delivered' AND DATE(actual_delivery) = CURDATE()");
    $stmt->execute([$user['id']]);
    $completedToday = $stmt->fetch()['completed'];
    
    // Total earnings (approximate)
    $stmt = $db->prepare("SELECT SUM(cost * 0.1) as earnings FROM couriers WHERE agent_id = ? AND status = 'delivered'");
    $stmt->execute([$user['id']]);
    $totalEarnings = $stmt->fetch()['earnings'] ?? 0;
    
    // My assigned couriers
    $stmt = $db->prepare("SELECT * FROM couriers WHERE agent_id = ? ORDER BY created_at DESC LIMIT 8");
    $stmt->execute([$user['id']]);
    $myCouriers = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}

// Get flash message
$flash_message = Auth::getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - Rapid Courier Flow</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="main-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="flex items-center gap-2 mb-6">
                <i class="fas fa-shipping-fast text-2xl" style="color: var(--primary);"></i>
                <h1 class="text-lg font-bold">Courier Pro</h1>
            </div>
            
            <nav>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="my-deliveries.php" class="nav-link">
                            <i class="fas fa-truck"></i>
                            My Deliveries
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="reports.php" class="nav-link">
                            <i class="fas fa-chart-line"></i>
                            My Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="settings.php" class="nav-link">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div>
                    <h2 class="text-2xl font-bold">Agent Dashboard</h2>
                    <p style="color: var(--muted-foreground);">Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="font-medium"><?php echo htmlspecialchars($user['name']); ?></p>
                        <p class="text-sm" style="color: var(--muted-foreground);">Delivery Agent</p>
                    </div>
                    <div>
                        <a href="../logout.php" class="btn btn-outline btn-sm">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <?php if ($flash_message): ?>
                <div class="alert alert-<?php echo $flash_message['type']; ?>">
                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                    <?php echo htmlspecialchars($flash_message['message']); ?>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-4 gap-6 mb-8">
                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Assigned Couriers</p>
                                <p class="text-2xl font-bold"><?php echo number_format($assignedCouriers ?? 0); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--primary); color: white;">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Active Deliveries</p>
                                <p class="text-2xl font-bold"><?php echo number_format($activeDeliveries ?? 0); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--warning); color: white;">
                                <i class="fas fa-truck text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Completed Today</p>
                                <p class="text-2xl font-bold"><?php echo number_format($completedToday ?? 0); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--success); color: white;">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Total Earnings</p>
                                <p class="text-2xl font-bold">$<?php echo number_format($totalEarnings ?? 0, 2); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--info); color: white;">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Deliveries -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Assigned Deliveries</h3>
                    <p class="card-description">Couriers assigned to you</p>
                </div>
                <div class="card-content">
                    <?php if (!empty($myCouriers)): ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tracking Number</th>
                                        <th>From → To</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Value</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($myCouriers as $courier): ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <p class="font-medium"><?php echo htmlspecialchars($courier['tracking_number']); ?></p>
                                                    <p class="text-sm" style="color: var(--muted-foreground);">
                                                        <?php echo date('M j, Y', strtotime($courier['created_at'])); ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <p class="font-medium"><?php echo htmlspecialchars($courier['sender_name']); ?></p>
                                                    <p class="text-sm" style="color: var(--muted-foreground);">
                                                        → <?php echo htmlspecialchars($courier['recipient_name']); ?>
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo getStatusColor($courier['status']); ?>">
                                                    <?php echo ucfirst(str_replace('_', ' ', $courier['status'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo getPriorityColor($courier['priority']); ?>">
                                                    <?php echo ucfirst($courier['priority']); ?>
                                                </span>
                                            </td>
                                            <td class="font-medium">$<?php echo number_format($courier['cost'], 2); ?></td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <button class="btn btn-sm btn-outline" onclick="updateStatus('<?php echo $courier['id']; ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-ghost" onclick="viewDetails('<?php echo $courier['id']; ?>')">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-truck text-4xl mb-4" style="color: var(--muted-foreground);"></i>
                            <p style="color: var(--muted-foreground);">No deliveries assigned yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-3 gap-4">
                    <button class="btn btn-primary" onclick="updateLocation()">
                        <i class="fas fa-map-marker-alt"></i>
                        Update Location
                    </button>
                    <a href="my-deliveries.php" class="btn btn-secondary">
                        <i class="fas fa-list"></i>
                        View All Deliveries
                    </a>
                    <a href="reports.php" class="btn btn-outline">
                        <i class="fas fa-chart-line"></i>
                        View Reports
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        function updateStatus(courierId) {
            // Status update functionality
            alert('Status update feature would be implemented here for courier ID: ' + courierId);
        }

        function viewDetails(courierId) {
            // View details functionality
            alert('View details feature would be implemented here for courier ID: ' + courierId);
        }

        function updateLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    alert('Location updated: ' + position.coords.latitude + ', ' + position.coords.longitude);
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        // Auto-refresh every 30 seconds
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>

<?php
// Helper functions
function getStatusColor($status) {
    switch ($status) {
        case 'delivered':
            return 'success';
        case 'pending':
            return 'warning';
        case 'cancelled':
            return 'destructive';
        case 'picked_up':
        case 'in_transit':
        case 'out_for_delivery':
            return 'primary';
        default:
            return 'secondary';
    }
}

function getPriorityColor($priority) {
    switch ($priority) {
        case 'high':
            return 'destructive';
        case 'medium':
            return 'warning';
        case 'low':
            return 'secondary';
        default:
            return 'primary';
    }
}
?>