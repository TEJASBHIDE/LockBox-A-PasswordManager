<?php
$page_title = "Privacy Policy | LockBox";
include 'header.php';
?>

<div class="policy-container">
    <div class="policy-content">
        <h1>Privacy Policy</h1>
        <p class="last-updated">Last Updated: <?php echo date('F j, Y'); ?></p>
        
        <section>
            <h2>1. Information We Collect</h2>
            <p>We collect information to provide better services to all our users. The types of information we collect include:</p>
            <ul>
                <li><strong>Account Information:</strong> When you create a LockBox account, we collect your name, email address, and password.</li>
                <li><strong>Password Data:</strong> The passwords you store in LockBox are encrypted and only accessible with your master password.</li>
                <li><strong>Usage Data:</strong> We collect information about how you interact with our services.</li>
            </ul>
        </section>
        
        <section>
            <h2>2. How We Use Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Provide, maintain, and improve our services</li>
                <li>Develop new features and functionality</li>
                <li>Protect LockBox and our users</li>
                <li>Communicate with you about your account</li>
            </ul>
        </section>
        
        <section>
            <h2>3. Information Security</h2>
            <p>We work hard to protect your information:</p>
            <ul>
                <li>All passwords are encrypted using AES-256 encryption</li>
                <li>We use HTTPS for all communications</li>
                <li>Regular security audits are performed</li>
            </ul>
        </section>
        
        <section>
            <h2>4. Changes to This Policy</h2>
            <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page.</p>
        </section>
        
        <section>
            <h2>5. Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us at privacy@lockbox.example.com.</p>
        </section>
    </div>
</div>
<style>
    /* Policy Pages Styling */
.policy-container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.policy-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 2.5rem;
    margin-top: 15%
}

.policy-content h1 {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.last-updated {
    color: #6c757d;
    margin-bottom: 2rem;
    font-size: 0.9rem;
}

.policy-content h2 {
    color: #2c3e50;
    margin: 2rem 0 1rem;
    font-size: 1.3rem;
}

.policy-content p {
    line-height: 1.6;
    margin-bottom: 1rem;
    color: #495057;
}

.policy-content ul {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.policy-content li {
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .policy-content {
        padding: 1.5rem;
    }
    
    .policy-content h1 {
        font-size: 1.5rem;
    }
}
</style>
<?php include 'footer.php'; ?>