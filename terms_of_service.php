<?php
$page_title = "Terms of Service | LockBox";
include 'header.php';
?>

<div class="policy-container">
    <div class="policy-content">
        <h1>Terms of Service</h1>
        <p class="last-updated">Last Updated: <?php echo date('F j, Y'); ?></p>
        
        <section>
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing or using the LockBox service, you agree to be bound by these Terms of Service.</p>
        </section>
        
        <section>
            <h2>2. Description of Service</h2>
            <p>LockBox provides a password management service that allows users to securely store and manage their passwords.</p>
        </section>
        
        <section>
            <h2>3. User Responsibilities</h2>
            <p>As a user of LockBox, you agree to:</p>
            <ul>
                <li>Keep your master password secure and confidential</li>
                <li>Not use the service for any illegal purpose</li>
                <li>Be responsible for all activities that occur under your account</li>
            </ul>
        </section>
        
        <section>
            <h2>4. Prohibited Conduct</h2>
            <p>You may not:</p>
            <ul>
                <li>Attempt to compromise the security of the service</li>
                <li>Use automated systems to access the service</li>
                <li>Reverse engineer or decompile any part of the service</li>
            </ul>
        </section>
        
        <section>
            <h2>5. Limitation of Liability</h2>
            <p>LockBox shall not be liable for any indirect, incidental, special, consequential or punitive damages resulting from your use of the service.</p>
        </section>
        
        <section>
            <h2>6. Changes to Terms</h2>
            <p>We reserve the right to modify these terms at any time. Your continued use of the service constitutes acceptance of the modified terms.</p>
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