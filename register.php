<?php include 'header.php'; ?>
<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve errors and form data from session
$errors = $_SESSION['register_errors'] ?? [];
$form_data = $_SESSION['register_form_data'] ?? [];

// Clear the session data
unset($_SESSION['register_errors']);
unset($_SESSION['register_form_data']);

// Display general error if exists
if (isset($errors['general'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($errors['general']) . '</div>';
}
?>
<div class="auth-container">
    <div class="auth-card">
        <h2>Create Your LockBox Account</h2>
        <form id="registerForm" action="process_register.php" method="POST">
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="full_name" required>
                <span class="error-message" id="fullNameError"></span>
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <span class="error-message" id="usernameError"></span>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <span class="error-message" id="emailError"></span>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input">
                    <input type="password" id="password" name="password" required>
                    <button type="button" class="toggle-password" aria-label="Show password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength">
                    <div class="strength-meter"></div>
                    <span class="strength-text">Password Strength: <span id="strengthValue">Weak</span></span>
                </div>
                <span class="error-message" id="passwordError"></span>
            </div>
            
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <div class="password-input">
                    <input type="password" id="confirmPassword" name="confirm_password" required>
                    <button type="button" class="toggle-password" aria-label="Show password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <span class="error-message" id="confirmPasswordError"></span>
            </div>
            
            <div class="form-group terms">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="terms_of_service.php">Terms of Service</a> and <a href="privacy_policy.php">Privacy Policy</a></label>
                <span class="error-message" id="termsError"></span>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </form>
        
        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    </div>
</div>

<script src="assets/js/register-validation.js"></script>

<?php include 'footer.php'; ?>