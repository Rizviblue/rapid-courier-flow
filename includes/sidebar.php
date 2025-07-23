<?php
/**
 * Sidebar Component
 */

if (!isset($auth)) {
    $auth = new Auth();
}

$user = $auth->user();
$currentPage = basename($_SERVER['PHP_SELF']);

// Define sidebar items based on role
$sidebarItems = [
    'admin' => [
        ['icon' => 'dashboard', 'label' => 'Dashboard', 'path' => 'dashboard.php'],
        ['icon' => 'plus', 'label' => 'Add Courier', 'path' => 'add-courier.php'],
        ['icon' => 'list', 'label' => 'Courier List', 'path' => 'couriers.php'],
        ['icon' => 'users', 'label' => 'Agent Management', 'path' => 'agents.php'],
        ['icon' => 'user-check', 'label' => 'Customer Management', 'path' => 'customers.php'],
        ['icon' => 'bar-chart', 'label' => 'Reports', 'path' => 'reports.php'],
        ['icon' => 'settings', 'label' => 'Settings', 'path' => 'settings.php']
    ],
    'agent' => [
        ['icon' => 'dashboard', 'label' => 'Dashboard', 'path' => 'dashboard.php'],
        ['icon' => 'plus', 'label' => 'Add Courier', 'path' => 'add-courier.php'],
        ['icon' => 'list', 'label' => 'Courier List', 'path' => 'couriers.php'],
        ['icon' => 'bar-chart', 'label' => 'Reports', 'path' => 'reports.php'],
        ['icon' => 'settings', 'label' => 'Settings', 'path' => 'settings.php']
    ],
    'user' => [
        ['icon' => 'dashboard', 'label' => 'Dashboard', 'path' => 'dashboard.php'],
        ['icon' => 'search', 'label' => 'Track Package', 'path' => 'track.php'],
        ['icon' => 'list', 'label' => 'My Packages', 'path' => 'packages.php'],
        ['icon' => 'user-check', 'label' => 'Support', 'path' => 'support.php'],
        ['icon' => 'settings', 'label' => 'Settings', 'path' => 'settings.php']
    ]
];

$items = $sidebarItems[$user['role']] ?? [];

// SVG Icons
$icons = [
    'dashboard' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>',
    'plus' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>',
    'list' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>',
    'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a4 4 0 11-8 0 4 4 0 018 0z"></path>',
    'user-check' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
    'bar-chart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>',
    'settings' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>',
    'search' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>'
];
?>

<div class="sidebar">
    <!-- Logo/Brand -->
    <div class="sidebar-header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-foreground rounded-lg flex items-center justify-center">
                <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold">CourierPro</h1>
                <p class="text-xs opacity-80 capitalize"><?php echo $user['role']; ?> Panel</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <?php foreach ($items as $item): ?>
            <?php 
            $isActive = $currentPage === $item['path'];
            $activeClass = $isActive ? 'active' : '';
            ?>
            <a href="<?php echo $item['path']; ?>" class="<?php echo $activeClass; ?>">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <?php echo $icons[$item['icon']] ?? ''; ?>
                </svg>
                <?php echo $item['label']; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- User Info & Logout -->
    <div class="sidebar-footer">
        <div class="mb-4 px-4 py-2">
            <p class="text-sm font-medium"><?php echo htmlspecialchars($user['name']); ?></p>
            <p class="text-xs opacity-80"><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        <a href="<?php echo BASE_URL; ?>/logout.php" class="btn btn-outline w-full text-primary-foreground border-primary-foreground hover:bg-primary-foreground hover:text-primary">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Logout
        </a>
    </div>
</div>