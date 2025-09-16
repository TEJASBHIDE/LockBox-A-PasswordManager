<?php
require_once 'auth_check.php';
require_once 'database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_password':
        case 'edit_password':
            $platform = trim($_POST['platform'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $url = trim($_POST['url'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $notes = trim($_POST['notes'] ?? '');
            
            if (empty($platform) || empty($password)) {
                throw new Exception('Platform and password are required');
            }
            
            if ($action === 'add_password') {
                $stmt = $pdo->prepare("INSERT INTO passwords (user_id, platform, username, password, url, category, notes) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_SESSION['user_id'],
                    $platform,
                    $username,
                    $password,
                    $url,
                    $category,
                    $notes
                ]);
                
                $response['success'] = true;
                $response['message'] = 'Password added successfully!';
            } else {
                $password_id = $_POST['password_id'] ?? 0;
                
                // Verify ownership
                $stmt = $pdo->prepare("SELECT user_id FROM passwords WHERE password_id = ?");
                $stmt->execute([$password_id]);
                $owner = $stmt->fetch();
                
                if (!$owner || $owner['user_id'] != $_SESSION['user_id']) {
                    throw new Exception('You do not have permission to edit this password');
                }
                
                $stmt = $pdo->prepare("UPDATE passwords SET 
                    platform = ?, 
                    username = ?, 
                    password = ?, 
                    url = ?, 
                    category = ?, 
                    notes = ?,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE password_id = ?");
                $stmt->execute([
                    $platform,
                    $username,
                    $password,
                    $url,
                    $category,
                    $notes,
                    $password_id
                ]);
                
                $response['success'] = true;
                $response['message'] = 'Password updated successfully!';
            }
            break;
            
        case 'verify_password':
            $password_id = $_POST['password_id'] ?? 0;
            $master_password = trim($_POST['master_password'] ?? '');
            
            if (empty($master_password)) {
                throw new Exception('Master password is required');
            }
            
            // Verify master password
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            
            if (!$user || !password_verify($master_password, $user['password_hash'])) {
                throw new Exception('Incorrect master password');
            }
            
            // Get the requested password
            $stmt = $pdo->prepare("SELECT * FROM passwords WHERE password_id = ? AND user_id = ?");
            $stmt->execute([$password_id, $_SESSION['user_id']]);
            $password = $stmt->fetch();
            
            if (!$password) {
                throw new Exception('Password not found or you do not have permission to view it');
            }
            
            $response['success'] = true;
            $response['platform'] = htmlspecialchars($password['platform']);
            $response['username'] = htmlspecialchars($password['username']);
            $response['password'] = htmlspecialchars($password['password']);
            $response['url'] = htmlspecialchars($password['url']);
            $response['category'] = htmlspecialchars($password['category']);
            $response['notes'] = htmlspecialchars($password['notes']);
            break;
            
        case 'delete_password':
            $password_id = $_POST['password_id'] ?? 0;
            
            // Verify ownership
            $stmt = $pdo->prepare("SELECT user_id FROM passwords WHERE password_id = ?");
            $stmt->execute([$password_id]);
            $owner = $stmt->fetch();
            
            if (!$owner || $owner['user_id'] != $_SESSION['user_id']) {
                throw new Exception('You do not have permission to delete this password');
            }
            
            $stmt = $pdo->prepare("DELETE FROM passwords WHERE password_id = ?");
            $stmt->execute([$password_id]);
            
            $response['success'] = true;
            $response['message'] = 'Password deleted successfully!';
            break;
            
        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);