document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const strengthMeter = document.querySelector('.strength-meter');
    const strengthValue = document.getElementById('strengthValue');
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    
    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);
        
        // Update strength meter
        strengthMeter.style.width = strength.percentage + '%';
        strengthMeter.style.backgroundColor = strength.color;
        strengthValue.textContent = strength.text;
        strengthValue.style.color = strength.color;
    });
    
    // Toggle password visibility
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        
        // Validate full name
        const fullName = document.getElementById('fullName').value.trim();
        if (fullName.length < 2) {
            document.getElementById('fullNameError').textContent = 'Please enter your full name';
            isValid = false;
        } else {
            document.getElementById('fullNameError').textContent = '';
        }
        
        // Validate username
        const username = document.getElementById('username').value.trim();
        if (username.length < 4) {
            document.getElementById('usernameError').textContent = 'Username must be at least 4 characters';
            isValid = false;
        } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            document.getElementById('usernameError').textContent = 'Username can only contain letters, numbers and underscores';
            isValid = false;
        } else {
            document.getElementById('usernameError').textContent = '';
        }
        
        // Validate email
        const email = document.getElementById('email').value.trim();
        if (!isValidEmail(email)) {
            document.getElementById('emailError').textContent = 'Please enter a valid email address';
            isValid = false;
        } else {
            document.getElementById('emailError').textContent = '';
        }
        
        // Validate password
        const password = passwordInput.value;
        if (password.length < 8) {
            document.getElementById('passwordError').textContent = 'Password must be at least 8 characters';
            isValid = false;
        } else {
            document.getElementById('passwordError').textContent = '';
        }
        
        // Validate confirm password
        const confirmPassword = confirmPasswordInput.value;
        if (password !== confirmPassword) {
            document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
            isValid = false;
        } else {
            document.getElementById('confirmPasswordError').textContent = '';
        }
        
        // Validate terms
        if (!document.getElementById('terms').checked) {
            document.getElementById('termsError').textContent = 'You must agree to the terms';
            isValid = false;
        } else {
            document.getElementById('termsError').textContent = '';
        }
        
        if (isValid) {
            this.submit();
        }
    });
    
    // Helper functions
    function calculatePasswordStrength(password) {
        let strength = 0;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumbers = /\d/.test(password);
        const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        
        if (password.length >= 8) strength += 1;
        if (password.length >= 12) strength += 1;
        if (hasUpperCase) strength += 1;
        if (hasLowerCase) strength += 1;
        if (hasNumbers) strength += 1;
        if (hasSpecialChars) strength += 1;
        
        let result = {};
        
        if (strength <= 2) {
            result = { percentage: 33, color: '#f72585', text: 'Weak' };
        } else if (strength <= 4) {
            result = { percentage: 66, color: '#f8961e', text: 'Moderate' };
        } else {
            result = { percentage: 100, color: '#4cc9f0', text: 'Strong' };
        }
        
        return result;
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});