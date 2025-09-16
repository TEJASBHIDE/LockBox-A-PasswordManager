<?php
session_start();
require_once 'database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if account was successfully deleted (in case of redirect back)
if (isset($_GET['deleted'])) {
    echo "<script>
            alert('Your account has been successfully deleted. We hope to see you again!');
            window.location.href = 'index.php';
          </script>";
    exit();
}

// Get user information
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

// Check for errors
$error = isset($_GET['error']) ? "Failed to delete account. Please try again." : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .confirmation-box {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .confirmation-box h2 {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .confirmation-box p {
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .user-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        .btn-cancel {
            background-color: #3498db;
            color: white;
        }
        .btn-cancel:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="confirmation-box">
        <h2><i class="fas fa-exclamation-triangle"></i> Delete Your Account</h2>
        <p>Are you sure you want to permanently delete your account? This action cannot be undone.</p>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="user-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        
        <div class="buttons">
            <form action="process_delete_account.php" method="post" id="deleteForm">
                <button type="submit" class="btn btn-delete">
                    <i class="fas fa-user-times"></i> Yes, Delete My Account
                </button>
            </form>
            <a href="dashboard.php" class="btn btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </div>

    <script>
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you absolutely sure you want to delete your account? This cannot be undone!')) {
                this.submit();
            }
        });
    </script>
</body>
</html>