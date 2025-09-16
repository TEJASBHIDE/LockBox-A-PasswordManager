<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LockBox - Your Secure Password Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <!-- <link rel="stylesheet" href="dashboard.css"> -->
    <link rel="icon" href="images/lockbox-logo.png" type="image/png">
    
</head>
<body>
    <header class="main-header">
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">
                    <img src="images/lockbox-logo.png" alt="LockBox Logo">
                    <span>LockBox</span>
                </a>
                <div class="nav-links">
                    <?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a>
<a href="<?= ($current_page == 'index.php') ? '#features' : 'index.php#features' ?>" class="nav-link">Features</a>
<a href="<?= ($current_page == 'index.php') ? '#how-it-works' : 'index.php#how-it-works' ?>" class="nav-link">Works</a>
<a href="<?= ($current_page == 'index.php') ? '#security' : 'index.php#security' ?>" class="nav-link">Security</a>
<a href="contact.php" class="nav-link <?= ($current_page == 'contact.php') ? 'active' : '' ?>">Contact Us</a>
                    <!-- <a href="index.php" class="nav-link active">Home</a>
                    <a href="index.php#features" class="nav-link">Features</a>
                    <a href="index.php#how-it-works" class="nav-link">Works</a>
                    <a href="index.php#security" class="nav-link">Security</a>
                    <a href="contact.php" class="nav-link">Contact Us</a> -->
                    <!-- <a href="#pricing" class="nav-link">Pricing</a> -->
                    <div class="auth-buttons">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
                            <a href="logout.php" class="btn btn-outline">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline">Login</a>
                            <a href="register.php" class="btn btn-primary">Sign Up</a>
                        <?php endif; ?>
                    </div>
                </div>
                <button class="hamburger" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </nav>
        </div>
    </header>
    <div class="mobile-menu">
        <a href="index.php" class="mobile-nav-link active">Home</a>
        <a href="#features" class="mobile-nav-link">Features</a>
        <a href="#how-it-works" class="mobile-nav-link">Works</a>
        <a href="#security" class="mobile-nav-link">Security</a>
        <a href="contact.php" class="mobile-nav-link">Contact Us</a>
        <!-- <a href="#pricing" class="mobile-nav-link">Pricing</a> -->
        <div class="mobile-auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
                <a href="logout.php" class="btn btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="register.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>