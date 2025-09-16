<?php
// Start session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';
require_once 'functions.php';

// Redirect if not a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

// Sanitize inputs
$username = sanitize_input($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// Basic validation
if (empty($username) || empty($password)) {
    header("Location: login.php?error=" . urlencode("Username/Email and password are required"));
    exit;
}

try {
    // Find user by username or email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    // Verify user exists and password is correct
    if (!$user || !password_verify($password, $user['password_hash'])) {
        // Log failed attempt
        if ($user) {
            log_activity($user['user_id'], 'failed_login', 'Failed login attempt');
        }
        
        // Generic error message to prevent user enumeration
        header("Location: login.php?error=" . urlencode("Invalid username/email or password"));
        exit;
    }

    // Check if account is active
    if (!$user['is_active']) {
        header("Location: login.php?error=" . urlencode("Your account has been deactivated"));
        exit;
    }

    // Check if email is verified (if you want to enforce this)
    // if (!$user['is_verified']) {
    //     // You could resend verification email here if you want
    //     header("Location: login.php?error=" . urlencode("Please verify your email address first"));
    //     exit;
    // }

    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['logged_in'] = true;
    $_SESSION['last_activity'] = time();

    // Set remember me cookie if requested
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + 60 * 60 * 24 * 30; // 30 days
        
        // Store token in database
        $stmt = $pdo->prepare("UPDATE users SET remember_token = ?, token_expiry = ? WHERE user_id = ?");
        $stmt->execute([$token, date('Y-m-d H:i:s', $expiry), $user['user_id']]);
        
        // Set secure cookie
        setcookie('remember_token', $token, [
            'expires' => $expiry,
            'path' => '/',
            'domain' => '',
            'secure' => true,     // or false if not using HTTPS
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    // Update last login
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    $stmt->execute([$user['user_id']]);

    // Log successful login
    log_activity($user['user_id'], 'login', 'User logged in');

    // Redirect to dashboard
    header("Location: dashboard.php");
    exit;

} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    header("Location: login.php?error=" . urlencode("A database error occurred. Please try again later."));
    exit;
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    header("Location: login.php?error=" . urlencode("An unexpected error occurred. Please try again."));
    exit;
}