<?php
$page_title = "Cookie Policy | LockBox";
include 'header.php';
?>

<div class="policy-container">
    <div class="policy-content">
        <h1>Cookie Policy</h1>
        <p class="last-updated">Last Updated: <?php echo date('F j, Y'); ?></p>
        
        <section>
            <h2>1. What Are Cookies</h2>
            <p>Cookies are small text files stored on your device when you visit websites. They help the website remember information about your visit.</p>
        </section>
        
        <section>
            <h2>2. How We Use Cookies</h2>
            <p>LockBox uses cookies for the following purposes:</p>
            <ul>
                <li><strong>Essential Cookies:</strong> Necessary for the website to function properly (e.g., authentication)</li>
                <li><strong>Preference Cookies:</strong> Remember your preferences (e.g., theme selection)</li>
                <li><strong>Analytics Cookies:</strong> Help us understand how users interact with our service</li>
            </ul>
        </section>
        
        <section>
            <h2>3. Your Cookie Choices</h2>
            <p>You can control and/or delete cookies as you wish. Most web browsers allow some control of cookies through browser settings.</p>
            <p>However, if you disable essential cookies, parts of LockBox may not work properly.</p>
        </section>
        
        <section>
            <h2>4. Third-Party Cookies</h2>
            <p>We may use third-party services that set their own cookies to provide features like analytics. These are subject to the respective privacy policies of those services.</p>
        </section>
        
        <section>
            <h2>5. Changes to This Policy</h2>
            <p>We may update this Cookie Policy from time to time. We will notify you of any changes by posting the new policy on this page.</p>
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