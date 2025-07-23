<?php
$user = getCurrentUser();
$stats = getCourierStats();
$userStats = getUserStats();
$recentCouriers = getCouriersByUser($user['id'], $user['role']);
$recentCouriers = array_slice($recentCouriers, 0, 5); // Get last 5 couriers

// Today's stats (mock data for demo)
$todayStats = [
    'new_couriers' => 23,
    'deliveries' => 45,
    'active_agents' => 12
];
?>

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 text-primary">Admin Dashboard</h1>
                    <p class="text-muted">Monitor and manage your courier operations</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo pageUrl('admin-analytics'); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-graph-up"></i> Analytics
                    </a>
                    <a href="<?php echo pageUrl('admin-add-courier'); ?>" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add Courier
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <!-- Total Couriers -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-primary border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Couriers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($stats['total']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Transit -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                In Transit
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($stats['in_transit']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-truck text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivered -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Delivered
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($stats['delivered']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-warning border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Active Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($userStats['active']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Activity -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">New Couriers Today</h6>
                            <h2 class="mb-0"><?php echo $todayStats['new_couriers']; ?></h2>
                        </div>
                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Deliveries Today</h6>
                            <h2 class="mb-0"><?php echo $todayStats['deliveries']; ?></h2>
                        </div>
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Active Agents</h6>
                            <h2 class="mb-0"><?php echo $todayStats['active_agents']; ?></h2>
                        </div>
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Couriers -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history"></i> Recent Couriers
                    </h5>
                    <a href="<?php echo pageUrl('admin-couriers'); ?>" class="btn btn-sm btn-outline-primary">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentCouriers)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-box text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No couriers found</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tracking #</th>
                                        <th>Sender</th>
                                        <th>Receiver</th>
                                        <th>Route</th>
                                        <th>Status</th>
                                        <th>Delivery Date</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentCouriers as $courier): ?>
                                        <tr>
                                            <td>
                                                <strong class="text-primary"><?php echo htmlspecialchars($courier['tracking_number']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($courier['sender_name']); ?></td>
                                            <td><?php echo htmlspecialchars($courier['receiver_name']); ?></td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo htmlspecialchars($courier['pickup_city']); ?> 
                                                    <i class="bi bi-arrow-right"></i>
                                                    <?php echo htmlspecialchars($courier['delivery_city']); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo getStatusBadgeClass($courier['status']); ?>">
                                                    <?php echo getStatusText($courier['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatDate($courier['delivery_date']); ?></td>
                                            <td>
                                                <small class="text-muted"><?php echo formatDateTime($courier['created_at']); ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-xs {
    font-size: .75rem;
}

.font-weight-bold {
    font-weight: 700;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.border-4 {
    border-width: 4px !important;
}
</style>