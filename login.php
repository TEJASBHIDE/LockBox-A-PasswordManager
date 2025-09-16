<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear success registration flag if set
if (isset($_SESSION['registration_success'])) {
    unset($_SESSION['registration_success']);
}
?>

<?php include 'header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <img src="images/lockbox-logo.png" alt="LockBox Logo" class="auth-logo">
            <h2>Welcome Back</h2>
            <p>Sign in to access your password vault</p>
        </div>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        
        <form id="loginForm" action="process_login.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="loginUsername">Username or Email</label>
                <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="loginUsername" name="username" required 
                           value="<?php echo htmlspecialchars($_GET['username'] ?? ''); ?>">
                </div>
                <span class="error-message" id="usernameError"></span>
            </div>
            
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <div class="input-with-icon password-input">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="loginPassword" name="password" required>
                    <button type="button" class="toggle-password-1" aria-label="Show password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <span class="error-message" id="passwordError"></span>
            </div>
            
            <!-- <div class="form-group remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
            </div> -->
            
            <button type="submit" class="btn btn-primary btn-block btn-auth">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
            
            <div class="auth-divider">
                <span>or</span>
            </div>
            
            <a href="register.php" class="btn btn-outline btn-block btn-auth">
                <i class="fas fa-user-plus"></i> Create New Account
            </a>
        </form>
    </div>
</div>

<script src="assets/js/login-validation.js"></script>

<?php include 'footer.php'; ?>