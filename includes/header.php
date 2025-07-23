<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Courier Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php if (isLoggedIn() && !in_array($page, ['login', 'register'])): ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo pageUrl(($_SESSION['user_role'] ?? 'user') . '-dashboard'); ?>">
                <i class="bi bi-truck"></i> <?php echo APP_NAME; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php $role = $_SESSION['user_role']; ?>
                    
                    <?php if ($role === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo pageUrl('admin-dashboard'); ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-box"></i> Couriers
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo pageUrl('admin-add-courier'); ?>">Add Courier</a></li>
                                <li><a class="dropdown-item" href="<?php echo pageUrl('admin-couriers'); ?>">View All</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo pageUrl('admin-agents'); ?>">
                                <i class="bi bi-people"></i> Agents
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo pageUrl('admin-customers'); ?>">
                                <i class="bi bi-person-circle"></i> Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo pageUrl('admin-reports'); ?>">
                                <i class="bi bi-file-earmark-text"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo pageUrl('admin-analytics'); ?>">
                                <i class="bi bi-graph-up"></i> Analytics
                            </a>
                        </li>
                    
                    <?php elseif ($role === 'agent'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo pageUrl('agent-dashboard'); ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-box"></i> Couriers
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo pageUrl('admin-add-courier'); ?>">Add Courier</a></li>
                                <li><a class="dropdown-item" href="<?php echo pageUrl('admin-couriers'); ?>">View All</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo pageUrl('agent-reports'); ?>">
                                <i class="bi bi-file-earmark-text"></i> Reports
                            </a>
                        </li>
                    
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo pageUrl('user-dashboard'); ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo pageUrl('user-packages'); ?>">
                                <i class="bi bi-box"></i> My Packages
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo pageUrl('support'); ?>">
                            <i class="bi bi-question-circle"></i> Support
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo pageUrl($role . '-settings'); ?>">
                                <i class="bi bi-gear"></i> Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo pageUrl('logout'); ?>">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>

<main class="<?php echo isLoggedIn() && !in_array($page, ['login', 'register']) ? 'container-fluid mt-4' : ''; ?>"><?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>