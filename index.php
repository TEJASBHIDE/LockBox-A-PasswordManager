<?php include 'header.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Secure Your Digital Life with LockBox</h1>
                    <p class="hero-text">The most trusted password manager that keeps all your passwords safe in one secure vault.</p>
                    <div class="hero-buttons">
                        <a href="register.php" class="btn btn-primary btn-large">Get Started for Free</a>
                        <a href="#how-it-works" class="btn btn-outline btn-large">Learn More</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="images/lockbox-logo.png" alt="LockBox Password Manager" class="hero-illustration">
                </div>
            </div>
        </section>

        <!-- Trust Badges -->
        <!-- <section class="trust-badges">
            <div class="container">
                <p>Trusted by over 1 million users worldwide</p>
                <div class="badges">
                    <img src="images/badge-ssl.png" alt="SSL Secure">
                    <img src="images/badge-aes.png" alt="AES 256-bit Encryption">
                    <img src="images/badge-gdpr.png" alt="GDPR Compliant">
                </div>
            </div>
        </section> -->

        <!-- Features Section -->
        <section class="features" id="features">
            <div class="container">
                <h2 class="section-title">Why Choose LockBox?</h2>
                <p class="section-subtitle">Everything you need to manage passwords securely</p>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3>Military-Grade Encryption</h3>
                        <p>All your passwords are encrypted with AES-256 bit encryption, the same standard used by banks and governments.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h3>Cross-Platform Sync</h3>
                        <p>Access your passwords from any device, anywhere. Automatically syncs across all your devices.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <h3>Password Generator</h3>
                        <p>Create strong, unique passwords for all your accounts with our built-in password generator.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3>Security Alerts</h3>
                        <p>Get notified if any of your passwords are compromised in a data breach.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                        <h3>Biometric Login</h3>
                        <p>Use your fingerprint or face ID for quick and secure access to your vault.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h3>Secure Sharing</h3>
                        <p>Safely share passwords with family or team members without revealing the actual password.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="how-it-works" id="how-it-works">
            <div class="container">
                <h2 class="section-title">How LockBox Works</h2>
                <p class="section-subtitle">Secure password management in three simple steps</p>
                
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h3>Create Your Account</h3>
                        <p>Sign up with your email and create a strong master password that only you know.</p>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <h3>Add Your Passwords</h3>
                        <p>Store all your passwords in your encrypted vault. You can organize them by categories.</p>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <h3>Access Anywhere</h3>
                        <p>Log in to your vault from any device to retrieve your passwords when you need them.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Security Section -->
        <section class="security" id="security">
            <!-- <div class="container"> -->
                <div class="security-content">
                    <h2 class="section-title">Your Security Is Our Priority</h2>
                    <p class="section-subtitle">We use the most advanced security measures to protect your data</p>
                    
                    <div class="security-features">
                        <div class="security-feature">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <h3>Zero-Knowledge Architecture</h3>
                                <p>We never store or have access to your master password or any of your encrypted data.</p>
                            </div>
                        </div>
                        
                        <div class="security-feature">
                            <i class="fas fa-database"></i>
                            <div>
                                <h3>End-to-End Encryption</h3>
                                <p>All data is encrypted on your device before it ever reaches our servers.</p>
                            </div>
                        </div>
                        
                        <div class="security-feature">
                            <i class="fas fa-user-secret"></i>
                            <div>
                                <h3>Two-Factor Authentication</h3>
                                <p>Add an extra layer of security to your account with 2FA.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </section>

        <!-- Pricing Section
        <section class="pricing" id="pricing">
            <div class="container">
                <h2 class="section-title">Simple, Transparent Pricing</h2>
                <p class="section-subtitle">Choose the plan that fits your needs</p>
                
                <div class="pricing-cards">
                    <div class="pricing-card">
                        <h3>Free</h3>
                        <div class="price">$0<span>/month</span></div>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Store up to 50 passwords</li>
                            <li><i class="fas fa-check"></i> Access on 1 device</li>
                            <li><i class="fas fa-check"></i> Basic password generator</li>
                            <li><i class="fas fa-check"></i> Security alerts</li>
                            <li><i class="fas fa-times"></i> Secure sharing</li>
                            <li><i class="fas fa-times"></i> Priority support</li>
                        </ul>
                        <a href="register.php" class="btn btn-outline">Get Started</a>
                    </div>
                    
                    <div class="pricing-card popular">
                        <div class="popular-badge">Most Popular</div>
                        <h3>Premium</h3>
                        <div class="price">$3.99<span>/month</span></div>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Unlimited password storage</li>
                            <li><i class="fas fa-check"></i> Sync across unlimited devices</li>
                            <li><i class="fas fa-check"></i> Advanced password generator</li>
                            <li><i class="fas fa-check"></i> Security alerts</li>
                            <li><i class="fas fa-check"></i> Secure sharing (5 people)</li>
                            <li><i class="fas fa-check"></i> Priority support</li>
                        </ul>
                        <a href="register.php" class="btn btn-primary">Get Premium</a>
                    </div>
                    
                    <div class="pricing-card">
                        <h3>Family</h3>
                        <div class="price">$6.99<span>/month</span></div>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Everything in Premium</li>
                            <li><i class="fas fa-check"></i> For up to 5 family members</li>
                            <li><i class="fas fa-check"></i> Family password sharing</li>
                            <li><i class="fas fa-check"></i> Family management dashboard</li>
                            <li><i class="fas fa-check"></i> Secure sharing (unlimited)</li>
                            <li><i class="fas fa-check"></i> 24/7 premium support</li>
                        </ul>
                        <a href="register.php" class="btn btn-outline">Get Family Plan</a>
                    </div>
                </div>
            </div>
        </section> -->

        <!-- CTA Section -->
        <!-- <section class="cta">
            <div class="container">
                <h2>Ready to Take Control of Your Passwords?</h2>
                <p>Join millions of users who trust LockBox to keep their digital lives secure.</p>
                <a href="register.php" class="btn btn-primary btn-large">Start Your Free Trial</a>
            </div>
        </section> -->
    </main>

<?php include 'footer.php'; ?>