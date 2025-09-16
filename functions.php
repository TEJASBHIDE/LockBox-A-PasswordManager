<?php
// Input sanitization
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Password encryption
function encrypt_password($password, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($password, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

// Password decryption
function decrypt_password($encrypted_password, $key) {
    $data = base64_decode($encrypted_password);
    $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = substr($data, openssl_cipher_iv_length('aes-256-cbc'));
    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
}

// Activity logging
function log_activity($user_id, $action_type, $description) {
    global $pdo;
    
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action_type, description, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $action_type, $description, $ip_address]);
    } catch (PDOException $e) {
        error_log("Activity log failed: " . $e->getMessage());
    }
}

// Get platform icon
function getPlatformIcon($platform) {
    $platform = strtolower($platform);
    $icons = [
        'facebook' => 'facebook',
        'twitter' => 'twitter',
        'instagram' => 'instagram',
        'linkedin' => 'linkedin',
        'google' => 'google',
        'amazon' => 'amazon',
        'netflix' => 'film',
        'spotify' => 'spotify',
        'apple' => 'apple',
        'microsoft' => 'microsoft',
        'github' => 'github',
        'dropbox' => 'dropbox',
        'slack' => 'slack',
        'paypal' => 'paypal',
        'bank' => 'university',
        'email' => 'envelope'
    ];
    
    return $icons[$platform] ?? 'key';
}

// Send email notification
function send_email_notification($email, $subject, $message) {
    $headers = "From: LockBox <noreply@yourdomain.com>\r\n";
    $headers .= "Reply-To: no-reply@yourdomain.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    try {
        mail($email, $subject, $message, $headers);
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}

// Verify CSRF token
function verify_csrf_token() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }
}

// Generate CSRF token
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Password strength checker
function check_password_strength($password) {
    $strength = 0;
    
    // Length
    if (strlen($password) >= 8) $strength++;
    if (strlen($password) >= 12) $strength++;
    
    // Character diversity
    if (preg_match('/[A-Z]/', $password)) $strength++;
    if (preg_match('/[a-z]/', $password)) $strength++;
    if (preg_match('/[0-9]/', $password)) $strength++;
    if (preg_match('/[^A-Za-z0-9]/', $password)) $strength++;
    
    return $strength;
}