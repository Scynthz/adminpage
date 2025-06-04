<?php 
include 'config.php';

// Only allow access if user is logged in and is an admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Validate and sanitize ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prevent admin from deleting themselves
    if ($id == $_SESSION["user_id"]) {
        echo "<p>You cannot delete your own admin account.</p>";
        echo "<p><a href='admin.php'>Back to Admin Dashboard</a></p>";
        exit();
    }
    
    // Delete user from database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "<p>Error deleting user.</p>";
        echo "<p><a href='admin.php'>Back to Admin Dashboard</a></p>";
    }
    
    $stmt->close();
} else {
    // Invalid or missing ID
    header("Location: admin.php");
    exit();
}
?>