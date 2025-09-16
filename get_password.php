<?php
require_once 'auth_check.php';
require_once 'database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $password_id = $_POST['password_id'] ?? 0;
    
    if (empty($password_id)) {
        throw new Exception('Password ID is required');
    }
    
    // Verify ownership
    $stmt = $pdo->prepare("SELECT * FROM passwords WHERE password_id = ? AND user_id = ?");
    $stmt->execute([$password_id, $_SESSION['user_id']]);
    $password = $stmt->fetch();
    
    if (!$password) {
        throw new Exception('Password not found or you do not have permission to view it');
    }
    
    $response['success'] = true;
    $response['platform'] = $password['platform'];
    $response['username'] = $password['username'];
    $response['password'] = $password['password'];
    $response['url'] = $password['url'];
    $response['category'] = $password['category'];
    $response['notes'] = $password['notes'];
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);