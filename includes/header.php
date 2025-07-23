<?php
/**
 * Header Component
 */

if (!isset($auth)) {
    $auth = new Auth();
}

$user = $auth->user();
$notifications = []; // This would come from a notification system

// Get unread notification count
$notificationCount = count($notifications);
?>

<header class="header flex items-center justify-between">
    <!-- Search -->
    <div class="flex-1 max-w-md">
        <div class="relative">
            <input
                type="text"
                placeholder="Search couriers, tracking numbers..."
                class="input pl-10 w-full"
                id="global-search"
            >
        </div>
    </div>

    <!-- Right side -->
    <div class="flex items-center gap-4">
        <!-- Notifications -->
        <div class="relative">
            <button 
                class="btn btn-outline p-2"
                onclick="toggleNotifications()"
                data-tooltip="Notifications"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19c-5 0-8-3-8-6s4-6 9-6 9 3 9 6c0 3-3 6-8 6z"></path>
                </svg>
                <?php if ($notificationCount > 0): ?>
                    <span class="badge badge-default absolute -top-1 -right-1 h-5 w-5 text-xs p-0 flex items-center justify-center bg-red-500">
                        <?php echo $notificationCount; ?>
                    </span>
                <?php endif; ?>
            </button>
            
            <!-- Notifications Dropdown -->
            <div id="notifications-dropdown" class="absolute right-0 top-full mt-2 w-80 bg-card border rounded-lg shadow-lg z-50" style="display: none;">
                <div class="p-4 border-b">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold">Notifications</h3>
                        <button class="text-sm text-primary" onclick="markAllAsRead()">
                            Mark all as read
                        </button>
                    </div>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    <?php if (empty($notifications)): ?>
                        <div class="p-4 text-center text-muted-foreground">
                            No new notifications
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notification): ?>
                            <div class="p-4 border-b hover:bg-muted/50 cursor-pointer">
                                <div class="font-medium text-sm"><?php echo htmlspecialchars($notification['title']); ?></div>
                                <div class="text-sm text-muted-foreground mt-1"><?php echo htmlspecialchars($notification['message']); ?></div>
                                <div class="text-xs text-muted-foreground mt-2"><?php echo format_date($notification['created_at']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative">
            <button 
                class="btn btn-outline flex items-center gap-2"
                onclick="toggleProfileMenu()"
            >
                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                    <span class="text-primary-foreground font-medium">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </span>
                </div>
                <div class="text-left">
                    <div class="text-sm font-medium"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="text-xs text-muted-foreground capitalize"><?php echo htmlspecialchars($user['role']); ?></div>
                </div>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <!-- Profile Dropdown Menu -->
            <div id="profile-dropdown" class="absolute right-0 top-full mt-2 w-56 bg-card border rounded-lg shadow-lg z-50" style="display: none;">
                <div class="p-2">
                    <a href="profile.php" class="flex items-center gap-2 p-2 rounded hover:bg-muted text-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile Settings
                    </a>
                    <div class="border-t my-1"></div>
                    <a href="logout.php" class="flex items-center gap-2 p-2 rounded hover:bg-muted text-sm text-red-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function toggleNotifications() {
    const dropdown = document.getElementById('notifications-dropdown');
    const profileDropdown = document.getElementById('profile-dropdown');
    
    // Close profile dropdown if open
    profileDropdown.style.display = 'none';
    
    // Toggle notifications dropdown
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function toggleProfileMenu() {
    const dropdown = document.getElementById('profile-dropdown');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    
    // Close notifications dropdown if open
    notificationsDropdown.style.display = 'none';
    
    // Toggle profile dropdown
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function markAllAsRead() {
    // This would make an AJAX call to mark notifications as read
    CourierApp.showNotification('All notifications marked as read', 'success');
    document.getElementById('notifications-dropdown').style.display = 'none';
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const profileDropdown = document.getElementById('profile-dropdown');
    
    if (!event.target.closest('.relative')) {
        notificationsDropdown.style.display = 'none';
        profileDropdown.style.display = 'none';
    }
});
</script>