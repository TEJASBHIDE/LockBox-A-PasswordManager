<?php
session_start();
require_once 'database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Begin transaction
    $pdo->beginTransaction();
    
    // Delete user from database
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    // Commit transaction
    $pdo->commit();
    
    // Destroy session
    session_unset();
    session_destroy();
    
    // Redirect to delete page with success parameter
    header("Location: delete_account.php?deleted=1");
    exit();
    
} catch (PDOException $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    
    // Log error
    error_log("Account deletion failed: " . $e->getMessage());
    
    // Redirect back with error message
    header("Location: delete_account.php?error=1");
    exit();
}