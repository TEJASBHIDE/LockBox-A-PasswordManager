<?php
// Start session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'database.php';
require_once 'functions.php';

// Initialize variables
$errors = [];
$success = false;

// Process form when submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate inputs
    if (empty($full_name)) {
        $errors['full_name'] = 'Full name is required';
    }
    
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = 'Username can only contain letters, numbers and underscores';
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'Username already taken';
        }
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors['email'] = 'Email already registered';
        }
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    } elseif (check_password_strength($password) < 3) {
        $errors['password'] = 'Password is too weak';
    }
    
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    
    if (!isset($_POST['terms'])) {
        $errors['terms'] = 'You must agree to the terms';
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        try {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Generate verification token
            $verification_token = bin2hex(random_bytes(32));
            
            // Start transaction
            $pdo->beginTransaction();
            
            // Insert user into database
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, full_name, verification_token) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash, $full_name, $verification_token]);
            
            // Get user ID
            $user_id = $pdo->lastInsertId();
            
            // Log activity
            log_activity($user_id, 'registration', 'User registered');
            
            // Commit transaction
            $pdo->commit();
            
            // Set success session variables
            $_SESSION['registration_success'] = true;
            $_SESSION['email_sent'] = $email;
            
            // Try to send verification email (but don't fail if it doesn't work)
            try {
                $verification_link = "https://yourdomain.com/verify.php?token=$verification_token";
                
                $email_subject = "Verify Your LockBox Account";
                $email_message = "
                    <html>
                    <head>
                        <title>Verify Your Account</title>
                    </head>
                    <body>
                        <h2>Welcome to LockBox, $full_name!</h2>
                        <p>Please click the button below to verify your email address:</p>
                        <a href='$verification_link' style='background-color: #4361ee; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 20px 0;'>
                            Verify Email
                        </a>
                        <p>If you didn't create an account with LockBox, please ignore this email.</p>
                    </body>
                    </html>
                ";
                
                send_email_notification($email, $email_subject, $email_message);
            } catch (Exception $e) {
                error_log("Email sending failed: " . $e->getMessage());
                // Continue even if email fails
            }
            
            // Always redirect to success page
            header("Location: register_success.php");
            exit;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Registration failed: " . $e->getMessage());
            $errors['general'] = "Registration failed. Please try again later.";
        }
    }
}

// If we got here, there were errors
$_SESSION['register_errors'] = $errors;
$_SESSION['register_form_data'] = $_POST;
header("Location: register.php");
exit;