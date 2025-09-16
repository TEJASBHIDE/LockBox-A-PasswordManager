<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';

// Clear remember token if set
// if (isset($_SESSION['user_id'])) {
//     $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL, token_expiry = NULL WHERE user_id = ?");
//     $stmt->execute([$_SESSION['user_id']]);
// }

// Unset all session variables
$_SESSION = array();

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Delete remember me cookie
setcookie('remember_token', '', time() - 3600, '/');

// Redirect to home page
header("Location:index.php");
exit;
?>