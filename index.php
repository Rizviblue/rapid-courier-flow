<?php
/**
 * Main Entry Point - Courier Management System
 */

require_once 'config/config.php';

// Initialize authentication
$auth = new Auth();

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    redirect(BASE_URL . '/login.php');
}

$user = $auth->user();

// Redirect based on user role
switch ($user['role']) {
    case 'admin':
        redirect(BASE_URL . '/admin/dashboard.php');
        break;
    case 'agent':
        redirect(BASE_URL . '/agent/dashboard.php');
        break;
    case 'user':
        redirect(BASE_URL . '/user/dashboard.php');
        break;
    default:
        redirect(BASE_URL . '/login.php');
}