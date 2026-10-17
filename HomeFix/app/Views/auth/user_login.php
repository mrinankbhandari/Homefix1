<div class="container">
    <div class="auth-wrapper">
        <!-- Homeowner Benefits Column -->
        <div class="auth-banner">
            <div>
                <span class="badge badge-amber" style="margin-bottom:12px;">Homeowner Portal</span>
                <h2>Welcome Back, Homeowner.</h2>
                <p style="color:#E0E7FF; font-size:1.05rem;">Access your dashboard to track ongoing service appointments, view fixer contacts, and leave reviews.</p>

                <ul class="auth-benefits-list">
                    <li>
                        <span class="auth-benefit-icon">✓</span>
                        <span>Track live booking status in real-time</span>
                    </li>
                    <li>
                        <span class="auth-benefit-icon">✓</span>
                        <span>Zero upfront payment until job is inspected</span>
                    </li>
                    <li>
                        <span class="auth-benefit-icon">✓</span>
                        <span>Direct contact with certified Mumbai fixers</span>
                    </li>
                </ul>
            </div>

            <div style="background:rgba(255,255,255,0.1); border-radius:12px; padding:16px; border:1px solid rgba(255,255,255,0.2);">
                <div style="font-size:0.85rem; font-weight:800; color:#FEF08A; text-transform:uppercase;">Are you a Service Professional?</div>
                <div style="font-size:0.9rem; margin-top:4px;">
                    Please use the <a href="index.php?page=provider-login" style="color:#FFFFFF; text-decoration:underline; font-weight:bold;">Pro Partner Portal →</a>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="auth-form-container">
            <h2 style="font-size:1.8rem; margin-bottom:8px;">Homeowner <span class="text-gradient">Sign In</span></h2>
            <p style="font-size:0.9rem; color:#64748B; margin-bottom:24px;">Enter your email and password to access your account.</p>

            <!-- Pre-filled Demo Hint for testing convenience -->
            <div style="background:#FEF3C7; border:var(--border-thin); border-radius:8px; padding:10px 14px; margin-bottom:18px; font-size:0.82rem; color:#78350F;">
                <strong>Demo Customer:</strong> <code>arun@example.com</code> / <code>password123</code>
            </div>

            <form action="index.php?page=login" method="POST">
                <div class="form-group">
                    <label class="form-label" for="userEmail">Email Address</label>
                    <input type="email" id="userEmail" name="email" class="form-input" required placeholder="you@example.com" value="arun@example.com">
                </div>

                <div class="form-group">
                    <label class="form-label" for="userPassword">Password</label>
                    <input type="password" id="userPassword" name="password" class="form-input" required placeholder="••••••••" value="password123">
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding:14px; font-size:1.05rem; margin-top:8px;">
                    Sign In to HomeFix ⚡
                </button>
            </form>

            <div style="text-align:center; margin-top:24px; font-size:0.9rem; color:#64748B;">
                Don't have a homeowner account? 
                <a href="index.php?page=register" style="color:var(--violet); font-weight:800; text-decoration:underline;">Create Account</a>
            </div>
        </div>
    </div>
</div>
