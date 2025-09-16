<?php
require_once 'auth_check.php';
require_once 'database.php';

// Get user information
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Get user's passwords
$stmt = $pdo->prepare("SELECT * FROM passwords WHERE user_id = ? ORDER BY platform ASC");
$stmt->execute([$_SESSION['user_id']]);
$passwords = $stmt->fetchAll();

// Get categories for filter
$categories = ['Social', 'Finance', 'Work', 'Entertainment', 'Shopping', 'Email', 'Other'];

// Handle password deletion
if (isset($_POST['action']) && $_POST['action'] == 'delete_password') {
    $password_id = $_POST['password_id'];
    
    // Verify the password belongs to the user before deleting
    $stmt = $pdo->prepare("SELECT user_id FROM passwords WHERE password_id = ?");
    $stmt->execute([$password_id]);
    $owner = $stmt->fetch();
    
    if ($owner && $owner['user_id'] == $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM passwords WHERE password_id = ?");
        $stmt->execute([$password_id]);
        $_SESSION['success_message'] = "Password deleted successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error_message'] = "You don't have permission to delete this password.";
        header("Location: dashboard.php");
        exit();
    }
}
?>

<?php include 'header.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="user-profile">
            <div class="avatar">
                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
            </div>
            <div class="user-info">
                <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>
        
        <nav class="dashboard-nav">
            <a href="dashboard.php" class="active"><i class="fas fa-key"></i> Password Vault</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <a href="delete_account.php" class="active"><i class="fas fa-user-times"></i> Delete Account</a>
        </nav>
        
        <div class="quick-actions">
            <button class="btn btn-primary btn-block" id="addPasswordBtn">
                <i class="fas fa-plus"></i> Add Password
            </button>
            <button class="btn btn-outline btn-block" id="generatePasswordBtn">
                <i class="fas fa-magic"></i> Password Generator
            </button>
        </div>
        
        <div class="vault-stats">
            <div class="stat-card">
                <i class="fas fa-key"></i>
                <div>
                    <span class="count"><?php echo count($passwords); ?></span>
                    <span class="label">Passwords</span>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-clock"></i>
                <div>
                    <span class="count"><?php echo date('M j, Y', strtotime($user['last_login'])); ?></span>
                    <span class="label">Last Login</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <div class="dashboard-header">
            <h1><i class="fas fa-key"></i> Password Vault</h1>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>
            
            <div class="search-filter">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search passwords...">
                    <i class="fas fa-search"></i>
                </div>
                <select id="categoryFilter">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <!-- Password Grid -->
        <div class="passwords-grid">
            <?php if (empty($passwords)): ?>
                <div class="empty-state">
                    <i class="fas fa-key"></i>
                    <h3>Your vault is empty</h3>
                    <p>Add your first password to get started</p>
                    <button class="btn btn-primary" id="addFirstPasswordBtn">Add Password</button>
                </div>
            <?php else: ?>
                <?php foreach ($passwords as $password): ?>
                    <div class="password-card" data-id="<?php echo $password['password_id']; ?>" data-category="<?php echo $password['category'] ?? 'Other'; ?>">
                        <!-- <div class="platform-logo">
                            <img src="assets/icons/<?php echo strtolower($password['platform']); ?>.png" 
                                 onerror="this.onerror=null; this.src='assets/icons/default.png'">
                        </div> -->
                        <div class="password-details">
                            <h3><?php echo htmlspecialchars($password['platform']); ?></h3>
                            <p><?php echo htmlspecialchars($password['username']); ?></p>
                            <div class="password-actions">
                                <button class="btn-action view-password" title="View Password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action edit-password" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action delete-password" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add/Edit Password Modal -->
<div class="modal" id="passwordModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add New Password</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="passwordForm" action="process_password.php" method="POST">
                <input type="hidden" name="action" id="formAction" value="add_password">
                <input type="hidden" name="password_id" id="passwordIdField" value="">
                
                <?php if (isset($_SESSION['password_error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($_SESSION['password_error']); ?>
                        <?php unset($_SESSION['password_error']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="platform">Platform/Service</label>
                    <input type="text" id="platform" name="platform" required 
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['platform'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="username">Username/Email</label>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['username'] ?? ''); ?>">
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
                </div>
                
                <div class="form-group">
                    <label for="url">Website URL</label>
                    <input type="url" id="url" name="url" 
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['url'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category; ?>" 
                                <?php if (isset($_SESSION['form_data']['category'])) echo $_SESSION['form_data']['category'] == $category ? 'selected' : ''; ?>>
                                <?php echo $category; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3"><?php echo htmlspecialchars($_SESSION['form_data']['notes'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-outline close-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Password Modal -->
<div class="modal" id="viewPasswordModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>View Password</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="verifyPasswordForm">
                <input type="hidden" name="action" value="verify_password">
                <input type="hidden" id="passwordId" name="password_id">
                
                <div class="form-group">
                    <p>For security reasons, please verify your master password:</p>
                    <label for="masterPassword">Master Password</label>
                    <div class="password-input">
                        <input type="password" id="masterPassword" name="master_password" required>
                        <button type="button" class="toggle-password" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline close-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Verify</button>
                </div>
            </form>
            
            <div id="passwordDetails" style="display: none;">
                <div class="password-info">
                    <div class="info-row">
                        <span class="label">Platform:</span>
                        <span class="value" id="viewPlatform"></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Username:</span>
                        <span class="value" id="viewUsername"></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Password:</span>
                        <div class="value password-value">
                            <span id="viewPassword"></span>
                            <button class="btn-copy" title="Copy to clipboard">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="info-row">
                        <span class="label">URL:</span>
                        <span class="value" id="viewUrl"></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Category:</span>
                        <span class="value" id="viewCategory"></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Notes:</span>
                        <span class="value" id="viewNotes"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Generator Modal -->
<div class="modal" id="generatePasswordModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Generate Strong Password</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="generatedPassword">Generated Password</label>
                <div class="password-input">
                    <input type="text" id="generatedPassword" readonly>
                    <button class="btn-copy" title="Copy to clipboard">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label>Password Length: <span id="lengthValue">12</span></label>
                <input type="range" id="passwordLength" min="8" max="32" value="12">
            </div>
            
            <div class="form-group">
                <label>Include:</label>
                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" id="includeUppercase" checked> Uppercase Letters (A-Z)
                    </label>
                    <label>
                        <input type="checkbox" id="includeLowercase" checked> Lowercase Letters (a-z)
                    </label>
                    <label>
                        <input type="checkbox" id="includeNumbers" checked> Numbers (0-9)
                    </label>
                    <label>
                        <input type="checkbox" id="includeSymbols" checked> Symbols (!@#$%^&*)
                    </label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-outline close-modal">Cancel</button>
                <button type="button" class="btn btn-secondary" id="regeneratePassword">Regenerate</button>
                <button type="button" class="btn btn-primary" id="useGeneratedPassword">Use Password</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteConfirmationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirm Deletion</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this password? This action cannot be undone.</p>
            <form id="deletePasswordForm" method="POST" action="dashboard.php">
                <input type="hidden" name="action" value="delete_password">
                <input type="hidden" name="password_id" id="deletePasswordId">
                <div class="form-actions">
                    <button type="button" class="btn btn-outline close-modal">Cancel</button>
                    <button 
  type="submit" 
  class="btn btn-danger" 
  style="
    background-color: #dc3545; 
    color: white; 
    border-color: #dc3545;
    transition: background-color 0.3s ease;
  "
  onmouseover="this.style.backgroundColor='#c82333'"
  onmouseout="this.style.backgroundColor='#dc3545'"
>
  Delete
</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Dashboard Layout */
.dashboard-container {
    display: flex;
    min-height: 100vh;
    background-color: #f8f9fa;
}

.sidebar {
    width: 280px;
    background: #2c3e50;
    color: white;
    padding: 1.5rem;
    position: fixed;
    height: 70vh;
    overflow-y: auto;
    transition: all 0.3s ease;
    top: 15%;
}

.main-content {
    flex: 1;
    margin-left: 280px;
    padding: 2rem;
    min-height: 70vh;
}

/* User Profile */
.user-profile {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.avatar {
    width: 50px;
    height: 50px;
    background-color: #4361ee;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin-right: 1rem;
}

.user-info h3 {
    margin: 0;
    font-size: 1.1rem;
    color: white;
}

.user-info p {
    margin: 0.25rem 0 0;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

/* Dashboard Navigation */
.dashboard-nav {
    margin-bottom: 2rem;
}

.dashboard-nav a {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.dashboard-nav a i {
    margin-right: 0.75rem;
    width: 20px;
    text-align: center;
}

.dashboard-nav a:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.dashboard-nav a.active {
    background: rgba(67, 97, 238, 0.2);
    color: white;
}

/* Quick Actions */
.quick-actions {
    margin-bottom: 2rem;
}

.quick-actions .btn {
    margin-bottom: 0.75rem;
}

/* Vault Stats */
.vault-stats {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
}

.stat-card {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
}

.stat-card:first-child {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.stat-card i {
    font-size: 1.25rem;
    margin-right: 1rem;
    color: #4361ee;
}

.stat-card .count {
    display: block;
    font-weight: 600;
    color: white;
}

.stat-card .label {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
}

/* Dashboard Header */
.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-header h1 {
    margin-bottom: 1.5rem;
    color: #2c3e50;
    display: flex;
    align-items: center;
}

.dashboard-header h1 i {
    margin-right: 0.75rem;
    color: #4361ee;
}

.search-filter {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.search-box {
    flex: 1;
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 40px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    font-size: 0.95rem;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

#categoryFilter {
    padding: 0.75rem 1rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background: white;
    font-size: 0.95rem;
    min-width: 180px;
}

/* Passwords Grid */
.passwords-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.password-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 1.25rem;
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
}

.password-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-color: #4361ee;
}

.platform-logo {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    overflow: hidden;
}

.platform-logo img {
    max-width: 30px;
    max-height: 30px;
}

.password-details h3 {
    margin: 0 0 0.25rem;
    font-size: 1.1rem;
    color: #2c3e50;
}

.password-details p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.password-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
    gap: 0.5rem;
}

.btn-action {
    background: none;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-action:hover {
    background: #f8f9fa;
    color: #4361ee;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 0;
}

.empty-state i {
    font-size: 3rem;
    color: #e9ecef;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal.active {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: white;
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.modal.active .modal-content {
    transform: translateY(0);
}

.modal-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.25rem;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6c757d;
    padding: 0 0.5rem;
}

.modal-body {
    padding: 1.5rem;
}

/* Form Styles */
.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #2c3e50;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    font-family: inherit;
    font-size: 0.95rem;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.password-input {
    position: relative;
}

.password-input input {
    padding-right: 80px;
}

.password-input .toggle-password,
.password-input .generate-password {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 0.5rem;
}

.password-input .toggle-password {
    right: 40px;
}

.password-input .generate-password {
    right: 10px;
}

.password-strength {
    margin-top: 0.5rem;
}

.strength-meter {
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    margin-bottom: 0.25rem;
    overflow: hidden;
}

.strength-meter::after {
    content: '';
    display: block;
    height: 100%;
    width: 0;
    background: #f72585;
    transition: width 0.3s ease;
}

.strength-text {
    font-size: 0.8rem;
    color: #6c757d;
}

/* Password Info */
.password-info {
    margin-top: 1.5rem;
}

.info-row {
    display: flex;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f8f9fa;
}

.info-row:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.label {
    font-weight: 500;
    color: #2c3e50;
    min-width: 100px;
}

.value {
    flex: 1;
    color: #6c757d;
}

.password-value {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.btn-copy {
    background: none;
    border: none;
    color: #4361ee;
    cursor: pointer;
    padding: 0.25rem;
}

/* Checkbox Group */
.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    font-weight: normal;
    cursor: pointer;
}

.checkbox-group input {
    margin-right: 0.5rem;
}

/* Range Input */
input[type="range"] {
    width: 100%;
    height: 6px;
    -webkit-appearance: none;
    background: #e9ecef;
    border-radius: 3px;
    margin-top: 0.5rem;
}

input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    background: #4361ee;
    border-radius: 50%;
    cursor: pointer;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1.5rem;
}

/* Responsive Styles */
@media (max-width: 992px) {
    .sidebar {
        width: 240px;
    }
    
    .main-content {
        margin-left: 240px;
    }
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: 280px;
        z-index: 1000;
    }
    
    .sidebar.active {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .search-filter {
        flex-direction: column;
    }
    
    #categoryFilter {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .main-content {
        padding: 1.5rem;
    }
    
    .passwords-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!--------------------------------------------------------------------------------------------->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal handling
    const modals = document.querySelectorAll('.modal');
    const closeModalButtons = document.querySelectorAll('.close-modal');
    
    function openModal(modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Add Password Button
    document.getElementById('addPasswordBtn')?.addEventListener('click', function() {
        resetPasswordForm('add_password');
        openModal(document.getElementById('passwordModal'));
    });
    
    // Add First Password Button
    document.getElementById('addFirstPasswordBtn')?.addEventListener('click', function() {
        resetPasswordForm('add_password');
        openModal(document.getElementById('passwordModal'));
    });
    
    function resetPasswordForm(action) {
        document.getElementById('modalTitle').textContent = action === 'add_password' 
            ? 'Add New Password' 
            : 'Edit Password';
        document.getElementById('formAction').value = action;
        document.getElementById('passwordIdField').value = '';
        document.getElementById('passwordForm').reset();
        updatePasswordStrength('');
    }
    
    // Generate Password Button
    document.getElementById('generatePasswordBtn')?.addEventListener('click', function() {
        generatePassword();
        openModal(document.getElementById('generatePasswordModal'));
    });
    
    // Password Generator Functionality
    function generatePassword() {
        const length = parseInt(document.getElementById('passwordLength').value);
        const includeUppercase = document.getElementById('includeUppercase').checked;
        const includeLowercase = document.getElementById('includeLowercase').checked;
        const includeNumbers = document.getElementById('includeNumbers').checked;
        const includeSymbols = document.getElementById('includeSymbols').checked;
        
        let charset = '';
        if (includeUppercase) charset += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (includeLowercase) charset += 'abcdefghijklmnopqrstuvwxyz';
        if (includeNumbers) charset += '0123456789';
        if (includeSymbols) charset += '!@#$%^&*';
        
        if (charset === '') {
            document.getElementById('generatedPassword').value = 'Select at least one character type';
            return;
        }
        
        let password = '';
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * charset.length);
            password += charset[randomIndex];
        }
        
        document.getElementById('generatedPassword').value = password;
    }
    
    // Regenerate Password Button
    document.getElementById('regeneratePassword')?.addEventListener('click', generatePassword);
    
    // Use Generated Password Button
    document.getElementById('useGeneratedPassword')?.addEventListener('click', function() {
        const generatedPassword = document.getElementById('generatedPassword').value;
        document.getElementById('password').value = generatedPassword;
        updatePasswordStrength(generatedPassword);
        closeModal(document.getElementById('generatePasswordModal'));
    });
    
    // Password Length Slider
    document.getElementById('passwordLength')?.addEventListener('input', function() {
        document.getElementById('lengthValue').textContent = this.value;
    });
    
    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Copy to Clipboard
    document.querySelectorAll('.btn-copy').forEach(function(button) {
        button.addEventListener('click', function() {
            const textToCopy = this.parentElement.querySelector('span')?.textContent || 
                              this.parentElement.parentElement.querySelector('input')?.value;
            
            if (textToCopy) {
                navigator.clipboard.writeText(textToCopy).then(function() {
                    const originalIcon = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(function() {
                        button.innerHTML = originalIcon;
                    }, 2000);
                });
            }
        });
    });
    
    // Password Strength Meter
    document.getElementById('password')?.addEventListener('input', function() {
        updatePasswordStrength(this.value);
    });
    
    function updatePasswordStrength(password) {
        const strengthMeter = document.querySelector('.strength-meter');
        const strengthText = document.getElementById('strengthValue');
        
        // Reset
        strengthMeter.style.width = '0%';
        strengthMeter.style.backgroundColor = '#f72585';
        strengthText.textContent = 'Weak';
        
        if (!password) return;
        
        // Calculate strength
        let strength = 0;
        
        // Length
        if (password.length >= 8) strength += 1;
        if (password.length >= 12) strength += 1;
        if (password.length >= 16) strength += 1;
        
        // Character types
        if (/[A-Z]/.test(password)) strength += 1;
        if (/[a-z]/.test(password)) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;
        
        // Update UI
        let width = 0;
        let color = '#f72585'; // Red
        let text = 'Weak';
        
        if (strength >= 6) {
            width = 100;
            color = '#4cc9f0'; // Strong blue
            text = 'Strong';
        } else if (strength >= 4) {
            width = 66;
            color = '#4895ef'; // Medium blue
            text = 'Medium';
        } else if (strength >= 2) {
            width = 33;
            text = 'Weak';
        }
        
        strengthMeter.style.width = width + '%';
        strengthMeter.style.backgroundColor = color;
        strengthText.textContent = text;
    }
    
    // Search Functionality
    document.getElementById('searchInput')?.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const passwordCards = document.querySelectorAll('.password-card');
        
        passwordCards.forEach(function(card) {
            const platform = card.querySelector('h3').textContent.toLowerCase();
            const username = card.querySelector('p').textContent.toLowerCase();
            
            if (platform.includes(searchTerm) || username.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
    
    // Category Filter
    document.getElementById('categoryFilter')?.addEventListener('change', function() {
        const selectedCategory = this.value;
        const passwordCards = document.querySelectorAll('.password-card');
        
        passwordCards.forEach(function(card) {
            const cardCategory = card.getAttribute('data-category');
            
            if (!selectedCategory || cardCategory === selectedCategory) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
    
    // View Password
    document.querySelectorAll('.view-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const passwordCard = this.closest('.password-card');
            const passwordId = passwordCard.getAttribute('data-id');
            
            document.getElementById('passwordId').value = passwordId;
            document.getElementById('passwordDetails').style.display = 'none';
            document.getElementById('verifyPasswordForm').reset();
            document.getElementById('verifyPasswordForm').style.display = 'block';
            
            openModal(document.getElementById('viewPasswordModal'));
        });
    });
    
    // Verify Password Form
    document.getElementById('verifyPasswordForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
        
        fetch('process_password.php', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.style.display = 'none';
                document.getElementById('viewPlatform').textContent = data.platform;
                document.getElementById('viewUsername').textContent = data.username;
                document.getElementById('viewPassword').textContent = data.password;
                document.getElementById('viewUrl').textContent = data.url || 'N/A';
                document.getElementById('viewCategory').textContent = data.category || 'Other';
                document.getElementById('viewNotes').textContent = data.notes || 'N/A';
                document.getElementById('passwordDetails').style.display = 'block';
            } else {
                alert('Error: ' + data.message);
                form.reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    });
    
    // Edit Password
    document.querySelectorAll('.edit-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const passwordCard = this.closest('.password-card');
            const passwordId = passwordCard.getAttribute('data-id');
            
            fetch('get_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'password_id=' + encodeURIComponent(passwordId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resetPasswordForm('edit_password');
                    document.getElementById('passwordIdField').value = passwordId;
                    document.getElementById('platform').value = data.platform;
                    document.getElementById('username').value = data.username;
                    document.getElementById('password').value = data.password;
                    document.getElementById('url').value = data.url || '';
                    document.getElementById('category').value = data.category || '';
                    document.getElementById('notes').value = data.notes || '';
                    
                    updatePasswordStrength(data.password);
                    openModal(document.getElementById('passwordModal'));
                } else {
                    alert('Error: ' + (data.message || 'Failed to load password details.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
    
    // Delete Password
    document.querySelectorAll('.delete-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const passwordCard = this.closest('.password-card');
            const passwordId = passwordCard.getAttribute('data-id');
            
            document.getElementById('deletePasswordId').value = passwordId;
            openModal(document.getElementById('deleteConfirmationModal'));
        });
    });
    
    // Delete Password Form
    document.getElementById('deletePasswordForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        
        fetch('process_password.php', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = 'dashboard.php';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    });
    
    // Password Form Submission
    document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        
        fetch('process_password.php', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = 'dashboard.php';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    });
    
    // Close modals when clicking outside
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal(modal);
            }
        });
    });
    
    // Close modals with close button
    closeModalButtons.forEach(button => {
        button.addEventListener('click', function() {
            closeModal(this.closest('.modal'));
        });
    });
    
    // Display any session messages
    <?php if (isset($_SESSION['success_message'])): ?>
        alert('<?php echo addslashes($_SESSION['success_message']); ?>');
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        alert('Error: <?php echo addslashes($_SESSION['error_message']); ?>');
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
});
</script>