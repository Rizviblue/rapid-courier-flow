<?php
/**
 * Logout Handler
 */

require_once 'config/config.php';

$auth = new Auth();
$auth->logout();

flash('success', 'You have been logged out successfully.');
redirect(BASE_URL . '/login.php');