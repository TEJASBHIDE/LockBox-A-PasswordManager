<?php
session_start();
require_once 'database.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total users count
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_pages = ceil($total_users / $limit);

// Get users with pagination
$stmt = $pdo->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// Handle user actions (activate/deactivate/delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    try {
        if ($action == 'activate') {
            $pdo->prepare("UPDATE users SET is_active = 1 WHERE user_id = ?")->execute([$user_id]);
        } elseif ($action == 'deactivate') {
            $pdo->prepare("UPDATE users SET is_active = 0 WHERE user_id = ?")->execute([$user_id]);
        } elseif ($action == 'delete') {
            $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$user_id]);
        }
        
        header("Location: manage_users.php");
        exit();
    } catch (PDOException $e) {
        $error = "Failed to perform action: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        .card h3 {
            margin-bottom: 20px;
            color: #444;
            font-weight: 500;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 500;
            color: #555;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .status-active {
            color: #2ecc71;
            font-weight: 500;
        }
        .status-inactive {
            color: #e74c3c;
            font-weight: 500;
        }
        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            margin-right: 5px;
            border: none;
            transition: all 0.3s;
        }
        .activate-btn {
            background-color: #2ecc71;
            color: white;
        }
        .deactivate-btn {
            background-color: #f39c12;
            color: white;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }
        .action-btn:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .pagination a.active {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            border: 1px solid #667eea;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
        .search-filter {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-box {
            position: relative;
            width: 300px;
        }
        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        .filter-dropdown {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background: white;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
                <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="manage_users.php" class="active"><i class="fas fa-users"></i> Manage Users</a>
                <a href="contact_messages.php"><i class="fas fa-envelope"></i> Contact Messages</a>
                <a href="add_admin.php"><i class="fas fa-user-plus"></i> Add Admin</a>
                <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Manage Users</h2>
                <button class="logout-btn" onclick="window.location.href='admin_logout.php'">Logout</button>
            </div>

            <div class="card">
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <h3>User List</h3>
                
                <div class="search-filter">
                    <!-- <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search users...">
                    </div> -->
                    <!-- <select class="filter-dropdown">
                        <option>All Users</option>
                        <option>Active Only</option>
                        <option>Inactive Only</option>
                    </select> -->
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Joined</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <span class="status-active">Active</span>
                                <?php else: ?>
                                    <span class="status-inactive">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$user['is_active']): ?>
                                    <button class="action-btn activate-btn" onclick="window.location.href='manage_users.php?action=activate&id=<?php echo $user['user_id']; ?>'">
                                        <i class="fas fa-check"></i> Activate
                                    </button>
                                <?php else: ?>
                                    <button class="action-btn deactivate-btn" onclick="window.location.href='manage_users.php?action=deactivate&id=<?php echo $user['user_id']; ?>'">
                                        <i class="fas fa-times"></i> Deactivate
                                    </button>
                                <?php endif; ?>
                                <button class="action-btn delete-btn" onclick="if(confirm('Are you sure you want to delete this user?')) { window.location.href='manage_users.php?action=delete&id=<?php echo $user['user_id']; ?>' }">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="manage_users.php?page=<?php echo $page - 1; ?>">&laquo;</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="manage_users.php?page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="manage_users.php?page=<?php echo $page + 1; ?>">&raquo;</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>