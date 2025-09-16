<?php include 'header.php'; ?>

<?php
require_once 'database.php'; // Include your database connection file

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    $errors = [];
    
    // Validate inputs
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    if (empty($subject)) {
        $errors['subject'] = 'Subject is required';
    }
    
    if (empty($message)) {
        $errors['message'] = 'Message is required';
    }
    
    // If no errors, save to database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            
            $success = "Thank you for your message! We'll get back to you soon.";
            
            // Clear form fields
            $name = $email = $phone = $subject = $message = '';
        } catch (PDOException $e) {
            $errors['database'] = 'Error submitting your message. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Your Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Basic styling - customize as needed */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            /* padding: 20px; */
        }
        
        .contact-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .contact-header h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .contact-header p {
            font-size: 1.1rem;
            color: #7f8c8d;
        }
        
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .contact-info {
            flex: 1;
            min-width: 300px;
        }
        
        .contact-info h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .info-item i {
            font-size: 1.2rem;
            color: #3498db;
            margin-right: 15px;
            margin-top: 3px;
        }
        
        .info-content h3 {
            margin: 0 0 5px 0;
            color: #2c3e50;
            font-size: 1.1rem;
        }
        
        .info-content p {
            margin: 0;
            color: #7f8c8d;
        }
        
        .contact-form {
            flex: 1;
            min-width: 300px;
        }
        
        .contact-form h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #3498db;
            outline: none;
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .send-btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    border: 2px solid transparent;
}

.send-btn {
    background-color: var(--primary-color);
    color: var(--white);
    border-color: var(--primary-color);
}

.send-btn:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-2px);
}
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="contact-header">
            <h1>Contact Us</h1>
            <p>Have questions? We're here to help!</p>
        </div>
        
        <!-- Success Message -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- Error Message -->
        <?php if (!empty($errors['database'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($errors['database']); ?>
            </div>
        <?php endif; ?>
        
        <div class="contact-container">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="info-content">
                        <h3>Address</h3>
                        <p>123 Business Street, City, Country</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div class="info-content">
                        <h3>Phone</h3>
                        <p>+1 (123) 456-7890</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div class="info-content">
                        <h3>Email</h3>
                        <p>info@yourcompany.com</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div class="info-content">
                        <h3>Working Hours</h3>
                        <p>Monday - Friday: 9:00 AM - 5:00 PM</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                
                <form action="contact.php" method="POST">
                    <div class="form-group">
                        <label for="name">Your Name *</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                        <?php if (!empty($errors['name'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['name']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Your Email *</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        <?php if (!empty($errors['email'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['email']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" name="subject" class="form-control" 
                               value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                        <?php if (!empty($errors['subject'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['subject']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Your Message *</label>
                        <textarea id="message" name="message" class="form-control" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                        <?php if (!empty($errors['message'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['message']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="send-btn">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>