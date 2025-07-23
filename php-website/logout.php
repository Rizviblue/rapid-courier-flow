<?php
require_once 'includes/auth.php';

// Verify user is logged in
if (!Auth::isAuthenticated()) {
    header('Location: login.php');
    exit();
}

// Get user info before logout for flash message
$user = Auth::getCurrentUser();
$userName = $user['name'] ?? 'User';

// Perform logout
Auth::logout();

// Set flash message for next page load
Auth::setFlashMessage('success', 'You have been successfully logged out. Thank you, ' . $userName . '!');

// Redirect to login page
header('Location: login.php');
exit();
?>