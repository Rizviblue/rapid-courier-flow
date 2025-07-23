<?php
require_once '../includes/auth.php';

// Require user role
Auth::requireRole('user');

$user = Auth::getCurrentUser();
$db = Database::getInstance()->getConnection();

// Get user-specific statistics
try {
    // Total packages sent
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM couriers WHERE sender_email = ?");
    $stmt->execute([$user['email']]);
    $totalSent = $stmt->fetch()['total'];
    
    // Packages received
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM couriers WHERE recipient_email = ?");
    $stmt->execute([$user['email']]);
    $totalReceived = $stmt->fetch()['total'];
    
    // Active packages
    $stmt = $db->prepare("SELECT COUNT(*) as active FROM couriers WHERE (sender_email = ? OR recipient_email = ?) AND status IN ('picked_up', 'in_transit', 'out_for_delivery')");
    $stmt->execute([$user['email'], $user['email']]);
    $activePackages = $stmt->fetch()['active'];
    
    // Total spent
    $stmt = $db->prepare("SELECT SUM(cost) as spent FROM couriers WHERE sender_email = ?");
    $stmt->execute([$user['email']]);
    $totalSpent = $stmt->fetch()['spent'] ?? 0;
    
    // Recent packages (both sent and received)
    $stmt = $db->prepare("SELECT * FROM couriers WHERE sender_email = ? OR recipient_email = ? ORDER BY created_at DESC LIMIT 6");
    $stmt->execute([$user['email'], $user['email']]);
    $recentPackages = $stmt->fetchAll();
    
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
    <title>My Dashboard - Rapid Courier Flow</title>
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
                        <a href="send-package.php" class="nav-link">
                            <i class="fas fa-plus"></i>
                            Send Package
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="track-package.php" class="nav-link">
                            <i class="fas fa-search"></i>
                            Track Package
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="my-packages.php" class="nav-link">
                            <i class="fas fa-box"></i>
                            My Packages
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
                    <h2 class="text-2xl font-bold">My Dashboard</h2>
                    <p style="color: var(--muted-foreground);">Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="font-medium"><?php echo htmlspecialchars($user['name']); ?></p>
                        <p class="text-sm" style="color: var(--muted-foreground);">Customer</p>
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
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Packages Sent</p>
                                <p class="text-2xl font-bold"><?php echo number_format($totalSent ?? 0); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--primary); color: white;">
                                <i class="fas fa-paper-plane text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Packages Received</p>
                                <p class="text-2xl font-bold"><?php echo number_format($totalReceived ?? 0); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--success); color: white;">
                                <i class="fas fa-inbox text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Active Deliveries</p>
                                <p class="text-2xl font-bold"><?php echo number_format($activePackages ?? 0); ?></p>
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
                                <p class="text-sm font-medium" style="color: var(--muted-foreground);">Total Spent</p>
                                <p class="text-2xl font-bold">$<?php echo number_format($totalSpent ?? 0, 2); ?></p>
                            </div>
                            <div class="p-3 rounded-lg" style="background-color: var(--info); color: white;">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="card">
                    <div class="card-content">
                        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="send-package.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Send Package
                            </a>
                            <a href="track-package.php" class="btn btn-secondary">
                                <i class="fas fa-search"></i>
                                Track Package
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <h3 class="text-lg font-semibold mb-4">Package Tracking</h3>
                        <form id="quickTrackForm" class="flex gap-2">
                            <input 
                                type="text" 
                                placeholder="Enter tracking number" 
                                class="input flex-1"
                                id="trackingNumber"
                                required
                            >
                            <button type="submit" class="btn btn-outline">
                                <i class="fas fa-search"></i>
                                Track
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recent Packages -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Packages</h3>
                    <p class="card-description">Your latest package activities</p>
                </div>
                <div class="card-content">
                    <?php if (!empty($recentPackages)): ?>
                        <div class="space-y-4">
                            <?php foreach ($recentPackages as $package): ?>
                                <?php 
                                $isSender = $package['sender_email'] === $user['email'];
                                $direction = $isSender ? 'Sent to' : 'Received from';
                                $otherParty = $isSender ? $package['recipient_name'] : $package['sender_name'];
                                ?>
                                <div class="flex items-center justify-between p-4 rounded-lg" style="background-color: var(--accent);">
                                    <div class="flex items-center gap-4">
                                        <div class="p-2 rounded-lg" style="background-color: var(--<?php echo $isSender ? 'primary' : 'success'; ?>); color: white;">
                                            <i class="fas fa-<?php echo $isSender ? 'paper-plane' : 'inbox'; ?>"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium"><?php echo htmlspecialchars($package['tracking_number']); ?></p>
                                            <p class="text-sm" style="color: var(--muted-foreground);">
                                                <?php echo $direction; ?> <?php echo htmlspecialchars($otherParty); ?>
                                            </p>
                                            <p class="text-sm" style="color: var(--muted-foreground);">
                                                <?php echo date('M j, Y g:i A', strtotime($package['created_at'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="badge badge-<?php echo getStatusColor($package['status']); ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $package['status'])); ?>
                                        </span>
                                        <p class="text-sm mt-1 font-medium">$<?php echo number_format($package['cost'], 2); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <a href="my-packages.php" class="btn btn-outline">
                                <i class="fas fa-list"></i>
                                View All Packages
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <i class="fas fa-box text-4xl mb-4" style="color: var(--muted-foreground);"></i>
                            <h3 class="text-lg font-semibold mb-2">No packages yet</h3>
                            <p style="color: var(--muted-foreground);" class="mb-4">Start by sending your first package!</p>
                            <a href="send-package.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Send Your First Package
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Quick track form
        document.getElementById('quickTrackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const trackingNumber = document.getElementById('trackingNumber').value.trim();
            
            if (trackingNumber) {
                // Redirect to track page with tracking number
                window.location.href = 'track-package.php?tracking=' + encodeURIComponent(trackingNumber);
            } else {
                alert('Please enter a tracking number');
            }
        });

        // Auto-refresh packages every 60 seconds
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                location.reload();
            }
        }, 60000);

        // Add some animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.classList.add('fade-in');
            });
        });
    </script>

    <style>
        .space-y-4 > * + * {
            margin-top: 1rem;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
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