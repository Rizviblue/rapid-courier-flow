<?php
$user = getCurrentUser();
$userCouriers = getCouriersByUser($user['id'], $user['role']);
$recentCouriers = array_slice($userCouriers, 0, 5);

// User specific stats
$userStats = [
    'total' => count($userCouriers),
    'pending' => count(array_filter($userCouriers, fn($c) => $c['status'] === 'pending')),
    'in_transit' => count(array_filter($userCouriers, fn($c) => $c['status'] === 'in_transit')),
    'delivered' => count(array_filter($userCouriers, fn($c) => $c['status'] === 'delivered'))
];
?>

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 text-primary">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
                    <p class="text-muted">Track your packages and manage your shipments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Track Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-search"></i> Quick Track Package
                    </h5>
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="trackingNumber" 
                                       placeholder="Enter tracking number (e.g., CMS001234)">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-light w-100" onclick="trackCourier()">
                                <i class="bi bi-search"></i> Track Package
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Results -->
    <div id="trackingResult" class="mb-4"></div>

    <!-- User Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-start border-primary border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Packages
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userStats['total']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-box text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-warning border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userStats['pending']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                In Transit
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userStats['in_transit']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-truck text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Delivered
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $userStats['delivered']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Packages -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history"></i> Recent Packages
                    </h5>
                    <a href="<?php echo pageUrl('user-packages'); ?>" class="btn btn-sm btn-outline-primary">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentCouriers)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-box text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No packages yet</h5>
                            <p class="text-muted">Contact an agent to create your first shipment</p>
                            <a href="<?php echo pageUrl('support'); ?>" class="btn btn-primary">
                                <i class="bi bi-question-circle"></i> Get Help
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tracking #</th>
                                        <th>Receiver</th>
                                        <th>Route</th>
                                        <th>Status</th>
                                        <th>Delivery Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentCouriers as $courier): ?>
                                        <tr>
                                            <td>
                                                <strong class="text-primary"><?php echo htmlspecialchars($courier['tracking_number']); ?></strong>
                                            </td>
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
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="trackSpecificCourier('<?php echo $courier['tracking_number']; ?>')">
                                                    <i class="bi bi-search"></i> Track
                                                </button>
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

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #007bff;
}
</style>

<script>
function trackSpecificCourier(trackingNumber) {
    document.getElementById('trackingNumber').value = trackingNumber;
    trackCourier();
}

// Auto-focus tracking input
document.getElementById('trackingNumber').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        trackCourier();
    }
});
</script>