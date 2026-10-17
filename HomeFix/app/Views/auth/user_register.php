<div class="container">
    <div class="auth-wrapper">
        <div class="auth-banner">
            <div>
                <span class="badge badge-amber" style="margin-bottom:12px;">New Homeowner</span>
                <h2>Join Mumbai's Trusted Home Network.</h2>
                <p style="color:#E0E7FF; font-size:1.05rem;">Get your leaky taps, electrical faults, and carpentry repairs handled by verified neighborhood fixers.</p>

                <ul class="auth-benefits-list">
                    <li><span class="auth-benefit-icon">✓</span> Book certified pros in under 60 seconds</li>
                    <li><span class="auth-benefit-icon">✓</span> See verified customer ratings and real photos</li>
                    <li><span class="auth-benefit-icon">✓</span> No hidden fees or surprise billings</li>
                </ul>
            </div>

            <div style="font-size:0.9rem; color:#E0E7FF;">
                Are you a skilled pro? <a href="index.php?page=provider-register" style="color:#FEF08A; font-weight:800; text-decoration:underline;">Register as Provider →</a>
            </div>
        </div>

        <div class="auth-form-container">
            <h2 style="font-size:1.8rem; margin-bottom:8px;">Create <span class="text-gradient">Account</span></h2>
            <p style="font-size:0.9rem; color:#64748B; margin-bottom:24px;">Sign up as a homeowner in a few clicks.</p>

            <form action="index.php?page=register" method="POST">
                <div class="form-group">
                    <label class="form-label" for="regName">Full Name *</label>
                    <input type="text" id="regName" name="name" class="form-input" required placeholder="e.g. Arun Verma">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="regEmail">Email Address *</label>
                        <input type="email" id="regEmail" name="email" class="form-input" required placeholder="arun@example.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="regPhone">Mobile Number *</label>
                        <input type="tel" id="regPhone" name="phone" class="form-input" required placeholder="9820011223">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="regPass">Password *</label>
                        <input type="password" id="regPass" name="password" class="form-input" required minlength="6" placeholder="Min 6 characters">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="regConfirm">Confirm Password *</label>
                        <input type="password" id="regConfirm" name="confirm_password" class="form-input" required minlength="6" placeholder="Re-enter password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding:14px; font-size:1.05rem; margin-top:8px;">
                    Create Homeowner Account ⚡
                </button>
            </form>

            <div style="text-align:center; margin-top:24px; font-size:0.9rem; color:#64748B;">
                Already have an account? 
                <a href="index.php?page=login" style="color:var(--violet); font-weight:800; text-decoration:underline;">Sign In</a>
            </div>
        </div>
    </div>
</div>
