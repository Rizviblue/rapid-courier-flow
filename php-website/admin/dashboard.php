<?php
require_once '../includes/auth.php';

// Require admin role
Auth::requireRole('admin');

$user = Auth::getCurrentUser();
$db = Database::getInstance()->getConnection();

// Get dashboard statistics
try {
    // Total couriers
    $stmt = $db->query("SELECT COUNT(*) as total FROM couriers");
    $totalCouriers = $stmt->fetch()['total'];
    
    // Active couriers
    $stmt = $db->query("SELECT COUNT(*) as active FROM couriers WHERE status IN ('picked_up', 'in_transit', 'out_for_delivery')");
    $activeCouriers = $stmt->fetch()['active'];
    
    // Total users
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
    $totalUsers = $stmt->fetch()['total'];
    
    // Total agents
    $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'agent'");
    $totalAgents = $stmt->fetch()['total'];
    
    // Recent couriers
    $stmt = $db->query("SELECT * FROM couriers ORDER BY created_at DESC LIMIT 5");
    $recentCouriers = $stmt->fetchAll();
    
    // Status distribution
    $stmt = $db->query("SELECT status, COUNT(*) as count FROM couriers GROUP BY status");
    $statusDistribution = $stmt->fetchAll();
    
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
    <title>Admin Dashboard - Rapid Courier Flow</title>
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
                        <a href="courier-list.php" class="nav-link">
                            <i class="fas fa-box"></i>
                            Couriers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="add-courier.php" class="nav-link">
                            <i class="fas fa-plus"></i>
                            Add Courier
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="agent-management.php" class="nav-link">
                            <i class="fas fa-users"></i>
                            Agents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="customer-management.php" class="nav-link">
                            <i class="fas fa-user-friends"></i>
                            Customers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="reports.php" class="nav-link">
                            <i class="fas fa-chart-bar"></i>
                            Reports
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
                    <h2 class="text-2xl font-bold">Admin Dashboard</h2>
                    <p style="color: var(--muted-foreground);">Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="font-medium"><?php echo htmlspecialchars($user['name']); ?></p>
                        <p class="text-sm" style="color: var(--muted-foreground);">Administrator</p>
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
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Total Couriers</p>
                                <p class="text-2xl font-bold"><?php echo number_format($totalCouriers ?? 0); ?></p>
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
                                <p class="text-2xl font-bold"><?php echo number_format($activeCouriers ?? 0); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--success); color: white;">
                                <i class="fas fa-truck text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Total Customers</p>
                                <p class="text-2xl font-bold"><?php echo number_format($totalUsers ?? 0); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--warning); color: white;">
                                <i class="fas fa-user-friends text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Total Agents</p>
                                <p class="text-2xl font-bold"><?php echo number_format($totalAgents ?? 0); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--info); color: white;">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-2 gap-6">
                <!-- Recent Couriers -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Couriers</h3>
                        <p class="card-description">Latest courier shipments</p>
                    </div>
                    <div class="card-content">
                        <?php if (!empty($recentCouriers)): ?>
                            <div class="space-y-4">
                                <?php foreach ($recentCouriers as $courier): ?>
                                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--accent);">
                                        <div>
                                            <p class="font-medium"><?php echo htmlspecialchars($courier['tracking_number']); ?></p>
                                            <p class="text-sm" style="color: var(--muted-foreground);">
                                                <?php echo htmlspecialchars($courier['sender_name']); ?> → 
                                                <?php echo htmlspecialchars($courier['recipient_name']); ?>
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-<?php echo getStatusColor($courier['status']); ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $courier['status'])); ?>
                                            </span>
                                            <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                                                $<?php echo number_format($courier['cost'], 2); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-box text-4xl mb-4" style="color: var(--muted-foreground);"></i>
                                <p style="color: var(--muted-foreground);">No courier data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status Distribution -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Delivery Status</h3>
                        <p class="card-description">Current delivery status distribution</p>
                    </div>
                    <div class="card-content">
                        <?php if (!empty($statusDistribution)): ?>
                            <div class="space-y-4">
                                <?php foreach ($statusDistribution as $status): ?>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-3 h-3 rounded-full" style="background-color: var(--<?php echo getStatusColor($status['status']); ?>);"></div>
                                            <span class="font-medium"><?php echo ucfirst(str_replace('_', ' ', $status['status'])); ?></span>
                                        </div>
                                        <span class="font-bold"><?php echo $status['count']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-chart-pie text-4xl mb-4" style="color: var(--muted-foreground);"></i>
                                <p style="color: var(--muted-foreground);">No status data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-4 gap-4">
                    <a href="add-courier.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Add New Courier
                    </a>
                    <a href="agent-management.php" class="btn btn-secondary">
                        <i class="fas fa-user-plus"></i>
                        Add Agent
                    </a>
                    <a href="reports.php" class="btn btn-outline">
                        <i class="fas fa-download"></i>
                        Export Reports
                    </a>
                    <a href="settings.php" class="btn btn-ghost">
                        <i class="fas fa-cog"></i>
                        System Settings
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Auto-refresh dashboard data every 30 seconds
        setInterval(function() {
            // Only refresh if user is still on the page
            if (document.visibilityState === 'visible') {
                location.reload();
            }
        }, 30000);

        // Add some interactivity to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 1px 3px 0 rgba(0, 0, 0, 0.1)';
                });
            });
        });
    </script>
</body>
</html>

<?php
// Helper function to get status color
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
?>