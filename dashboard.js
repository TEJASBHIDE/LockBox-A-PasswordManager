document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const addPasswordBtn = document.getElementById('addPasswordBtn');
    const addFirstPasswordBtn = document.getElementById('addFirstPasswordBtn');
    const generatePasswordBtn = document.getElementById('generatePasswordBtn');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const passwordCards = document.querySelectorAll('.password-card');
    
    // Modal Elements
    const modals = document.querySelectorAll('.modal');
    const addPasswordModal = document.getElementById('addPasswordModal');
    const viewPasswordModal = document.getElementById('viewPasswordModal');
    const generatePasswordModal = document.getElementById('generatePasswordModal');
    const closeModalBtns = document.querySelectorAll('.close-modal');
    
    // Form Elements
    const addPasswordForm = document.getElementById('addPasswordForm');
    const verifyPasswordForm = document.getElementById('verifyPasswordForm');
    const useGeneratedPasswordBtn = document.getElementById('useGeneratedPassword');
    const regeneratePasswordBtn = document.getElementById('regeneratePassword');
    
    // Password Generator Elements
    const generatedPasswordInput = document.getElementById('generatedPassword');
    const passwordLengthInput = document.getElementById('passwordLength');
    const lengthValue = document.getElementById('lengthValue');
    
    // Event Listeners
    if (addPasswordBtn) addPasswordBtn.addEventListener('click', () => toggleModal(addPasswordModal));
    if (addFirstPasswordBtn) addFirstPasswordBtn.addEventListener('click', () => toggleModal(addPasswordModal));
    if (generatePasswordBtn) generatePasswordBtn.addEventListener('click', () => toggleModal(generatePasswordModal));
    
    closeModalBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modals.forEach(modal => modal.classList.remove('active'));
        });
    });
    
    // Close modal when clicking outside
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
    
    // Search and Filter functionality
    if (searchInput && categoryFilter) {
        searchInput.addEventListener('input', filterPasswords);
        categoryFilter.addEventListener('change', filterPasswords);
    }
    
    // Password card actions
    passwordCards.forEach(card => {
        const viewBtn = card.querySelector('.view-password');
        const editBtn = card.querySelector('.edit-password');
        const deleteBtn = card.querySelector('.delete-password');
        
        if (viewBtn) viewBtn.addEventListener('click', () => {
            const passwordId = card.getAttribute('data-id');
            document.getElementById('passwordId').value = passwordId;
            toggleModal(viewPasswordModal);
        });
        
        if (editBtn) editBtn.addEventListener('click', () => {
            // Implement edit functionality
            alert('Edit functionality will be implemented');
        });
        
        if (deleteBtn) deleteBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to delete this password?')) {
                const passwordId = card.getAttribute('data-id');
                deletePassword(passwordId);
            }
        });
    });
    
    // Password generator
    if (passwordLengthInput) {
        passwordLengthInput.addEventListener('input', function() {
            lengthValue.textContent = this.value;
            generatePassword();
        });
        
        // Generate initial password
        generatePassword();
    }
    
    if (regeneratePasswordBtn) {
        regeneratePasswordBtn.addEventListener('click', generatePassword);
    }
    
    if (useGeneratedPasswordBtn) {
        useGeneratedPasswordBtn.addEventListener('click', function() {
            const password = generatedPasswordInput.value;
            document.getElementById('password').value = password;
            document.getElementById('strengthValue').textContent = 'Very Strong';
            document.querySelector('.strength-meter').style.width = '100%';
            document.querySelector('.strength-meter').style.backgroundColor = '#4cc9f0';
            toggleModal(generatePasswordModal);
        });
    }
    
    // Form submissions
    if (addPasswordForm) {
        addPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addPassword(this);
        });
    }
    
    if (verifyPasswordForm) {
        verifyPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            verifyPassword(this);
        });
    }
    
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
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
    
    // Copy to clipboard
    document.querySelectorAll('.btn-copy').forEach(btn => {
        btn.addEventListener('click', function() {
            const textToCopy = this.parentElement.querySelector('span')?.textContent || 
                              this.parentElement.querySelector('input')?.value;
            
            if (textToCopy) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    const originalIcon = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(() => {
                        this.innerHTML = originalIcon;
                    }, 2000);
                });
            }
        });
    });
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            updatePasswordStrength(this.value);
        });
    }
    
    // Functions
    function toggleModal(modal) {
        modal.classList.toggle('active');
    }
    
    function filterPasswords() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        
        passwordCards.forEach(card => {
            const platform = card.querySelector('h3').textContent.toLowerCase();
            const username = card.querySelector('p').textContent.toLowerCase();
            const category = card.getAttribute('data-category');
            
            const matchesSearch = platform.includes(searchTerm) || username.includes(searchTerm);
            const matchesCategory = selectedCategory === '' || category === selectedCategory;
            
            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    function generatePassword() {
        const length = passwordLengthInput.value;
        const includeUppercase = document.getElementById('includeUppercase').checked;
        const includeLowercase = document.getElementById('includeLowercase').checked;
        const includeNumbers = document.getElementById('includeNumbers').checked;
        const includeSymbols = document.getElementById('includeSymbols').checked;
        
        let charset = '';
        if (includeUppercase) charset += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (includeLowercase) charset += 'abcdefghijklmnopqrstuvwxyz';
        if (includeNumbers) charset += '0123456789';
        if (includeSymbols) charset += '!@#$%^&*()_+~`|}{[]\:;?><,./-=';
        
        let password = '';
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * charset.length);
            password += charset[randomIndex];
        }
        
        generatedPasswordInput.value = password;
    }
    
    function updatePasswordStrength(password) {
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
        
        const strengthMeter = document.querySelector('.strength-meter');
        const strengthText = document.getElementById('strengthValue');
        
        let result = {};
        if (strength <= 2) {
            result = { percentage: 33, color: '#f72585', text: 'Weak' };
        } else if (strength <= 4) {
            result = { percentage: 66, color: '#f8961e', text: 'Moderate' };
        } else {
            result = { percentage: 100, color: '#4cc9f0', text: 'Strong' };
        }
        
        strengthMeter.style.width = result.percentage + '%';
        strengthMeter.style.backgroundColor = result.color;
        strengthText.textContent = result.text;
        strengthText.style.color = result.color;
    }
    
    function addPassword(form) {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Add loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        
        // Simulate API call (replace with actual AJAX call)
        setTimeout(() => {
            console.log('Password would be added:', data);
            alert('Password added successfully! (This is a demo)');
            form.reset();
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Password';
            toggleModal(addPasswordModal);
            // In a real app, you would refresh the password list or add the new password to the DOM
        }, 1500);
    }
    
    function verifyPassword(form) {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Add loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
        
        // Simulate verification (replace with actual verification)
        setTimeout(() => {
            // In a real app, you would verify the master password with your backend
            console.log('Verification data:', data);
            
            // For demo purposes, we'll assume verification is successful
            document.getElementById('passwordDetails').style.display = 'block';
            form.style.display = 'none';
            
            // Simulate fetching password details
            document.getElementById('viewPlatform').textContent = 'Example Platform';
            document.getElementById('viewUsername').textContent = 'user@example.com';
            document.getElementById('viewPassword').textContent = '••••••••';
            document.getElementById('viewUrl').textContent = 'https://example.com';
            document.getElementById('viewCategory').textContent = 'Social';
            document.getElementById('viewNotes').textContent = 'Personal account';
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Verify';
        }, 1000);
    }
    
    function deletePassword(passwordId) {
        // Simulate API call (replace with actual AJAX call)
        console.log('Password would be deleted:', passwordId);
        alert('Password deleted successfully! (This is a demo)');
        // In a real app, you would remove the password card from the DOM or refresh the list
    }
    
    // Initialize password strength if on add password form
    if (passwordInput) {
        updatePasswordStrength(passwordInput.value);
    }

    
});
