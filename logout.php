<?php
include 'config.php';

session_start();

// Set the default timezone to Indonesia
date_default_timezone_set('Asia/Jakarta');

// Update last logout timestamp
$admin_id = $_SESSION['admin_id'] ?? null;
if ($admin_id) {
    $logout_time = date('Y-m-d H:i:s'); // Format timestamp in Y-m-d H:i:s
    mysqli_query($conn, "UPDATE `users` SET last_logout = '$logout_time' WHERE id = '$admin_id'") or die('query failed');
}

// Destroy session
session_unset();
session_destroy();

header('location:login.php');
?>
