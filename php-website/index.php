<?php
require_once 'includes/auth.php';

// Check if user is authenticated
if (Auth::isAuthenticated()) {
    // Redirect to appropriate dashboard based on role
    $redirectUrl = Auth::getRoleRedirectUrl();
    header('Location: ' . $redirectUrl);
    exit();
} else {
    // Redirect to login page
    header('Location: login.php');
    exit();
}
?>