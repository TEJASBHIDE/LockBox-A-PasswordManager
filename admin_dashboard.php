<?php
session_start();
require_once 'database.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get stats for dashboard
$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$active_users = $pdo->query("SELECT COUNT(*) FROM users WHERE is_active = 1")->fetchColumn();
$new_messages = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'")->fetchColumn();
$admins_count = $pdo->query("SELECT COUNT(*) FROM admins WHERE is_active = 1")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: #f5f7fa;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        .admin-profile {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .admin-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        .admin-profile h3 {
            margin: 10px 0 5px;
            font-weight: 500;
        }
        .admin-profile p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
        .nav-menu {
            margin-top: 20px;
        }
        .nav-menu a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 15px;
        }
        .nav-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .nav-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid white;
        }
        .nav-menu a.active {
            background: rgba(255, 255, 255, 0.2);
            border-left: 3px solid white;
        }
        .main-content {
            flex: 1;
            padding: 30px;
            background: #f5f7fa;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h2 {
            color: #333;
            font-weight: 600;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            display: flex;
            align-items: center;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
        }
        .stat-icon.users {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }
        .stat-icon.active-users {
            background: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }
        .stat-icon.messages {
            background: rgba(155, 89, 182, 0.1);
            color: #9b59b6;
        }
        .stat-icon.admins {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }
        .stat-info h3 {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .stat-info p {
            font-size: 24px;
            color: #333;
            font-weight: 600;
        }
        .recent-activity {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }
        .recent-activity h3 {
            margin-bottom: 20px;
            color: #444;
            font-weight: 500;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .activity-list {
            list-style: none;
        }
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f5f7fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #764ba2;
        }
        .activity-content h4 {
            font-size: 15px;
            color: #333;
            margin-bottom: 5px;
        }
        .activity-content p {
            font-size: 13px;
            color: #777;
        }
        .activity-time {
            margin-left: auto;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="admin-profile">
                <i class="fas fa-user-circle" style="font-size: 80px; color: white;"></i>
                <h3><?php echo htmlspecialchars($_SESSION['admin_fullname']); ?></h3>
                <p><?php echo htmlspecialchars($_SESSION['admin_email']); ?></p>
            </div>
            <div class="nav-menu">
                <a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
                <a href="contact_messages.php"><i class="fas fa-envelope"></i> Contact Messages</a>
                <a href="add_admin.php"><i class="fas fa-user-plus"></i> Add Admin</a>
                <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Dashboard Overview</h2>
                <button class="logout-btn" onclick="window.location.href='admin_logout.php'">Logout</button>
            </div>

            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Users</h3>
                        <p><?php echo $users_count; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon active-users">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Active Users</h3>
                        <p><?php echo $active_users; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon messages">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3>New Messages</h3>
                        <p><?php echo $new_messages; ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon admins">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Admins</h3>
                        <p><?php echo $admins_count; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>