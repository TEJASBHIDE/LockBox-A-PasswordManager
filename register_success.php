<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user came here accidentally
if (!isset($_SESSION['registration_success'])) {
    header("Location: register.php");
    exit;
}

// Clear the success flag
unset($_SESSION['registration_success']);
?>

<?php include 'header.php'; ?>

<div class="auth-container">
    <div class="auth-card text-center">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#4cc9f0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        
        <h2>Welcome to the LockBox Family!</h2>
        
        <div class="success-message">
            <p>Thank you for signing up with LockBox. Your account has been successfully created.</p>
            
            <?php if (!empty($_SESSION['email_sent'])): ?>
                <div class="email-notice">
                    <i class="fas fa-envelope"></i>
                    <p>We've sent a verification email to <strong><?php echo htmlspecialchars($_SESSION['email_sent']); ?></strong>.</p>
                    <p>Please check your inbox and verify your email to complete registration.</p>
                </div>
            <?php endif; ?>
            
            <div class="next-steps">
                <h3>What's Next?</h3>
                <ul>
                    <li><i class="fas fa-check-circle"></i> Check your email for verification</li>
                    <li><i class="fas fa-key"></i> Login to access your password vault</li>
                    <li><i class="fas fa-shield-alt"></i> Start securing your digital life</li>
                </ul>
            </div>
        </div>
        
        <div class="success-actions">
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Return Home
            </a>
            <a href="login.php" class="btn btn-outline">
                <i class="fas fa-sign-in-alt"></i> Login Now
            </a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>