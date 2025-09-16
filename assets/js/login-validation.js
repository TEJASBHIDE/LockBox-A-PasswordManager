document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const togglePasswordButtons = document.querySelectorAll('.toggle-password-1');
    
    // Toggle password visibility
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.password-input').querySelector('input');
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
        
        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
        });
        
        // Validate username/email
        const username = document.getElementById('loginUsername').value.trim();
        if (username.length === 0) {
            document.getElementById('usernameError').textContent = 'Username or email is required';
            isValid = false;
        }
        
        // Validate password
        const password = document.getElementById('loginPassword').value;
        if (password.length === 0) {
            document.getElementById('passwordError').textContent = 'Password is required';
            isValid = false;
        }
        
        if (isValid) {
            // Add loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
            
            // Submit form
            this.submit();
        }
    });
    
    // Display any username passed in URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('username')) {
        document.getElementById('loginUsername').value = urlParams.get('username');
    }
});