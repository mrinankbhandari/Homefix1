<div class="container">
    <div class="auth-wrapper">
        <!-- Service Provider Dark Bold Banner -->
        <div class="auth-banner provider-banner">
            <div>
                <span class="badge badge-amber" style="margin-bottom:12px;">Service Pro Partner Portal</span>
                <h2 style="color:#FEF08A;">Grow Your Local Business in Mumbai.</h2>
                <p style="color:#CBD5E1; font-size:1.05rem;">Connect with nearby homeowners who are actively looking for trusted electricians, plumbers, and carpenters.</p>

                <ul class="auth-benefits-list">
                    <li>
                        <span class="auth-benefit-icon" style="background:#FEF08A;">⚡</span>
                        <span>Receive instant booking notifications &amp; leads</span>
                    </li>
                    <li>
                        <span class="auth-benefit-icon" style="background:#FEF08A;">₹</span>
                        <span>Keep 100% of your earnings on initial bookings</span>
                    </li>
                    <li>
                        <span class="auth-benefit-icon" style="background:#FEF08A;">★</span>
                        <span>Build your online reputation with verified local reviews</span>
                    </li>
                </ul>
            </div>

            <div style="background:rgba(255,255,255,0.08); border-radius:12px; padding:16px; border:1px solid rgba(255,255,255,0.15);">
                <div style="font-size:0.85rem; font-weight:800; color:#A7F3D0; text-transform:uppercase;">Looking for Home Repairs?</div>
                <div style="font-size:0.9rem; margin-top:4px;">
                    Please use the <a href="index.php?page=login" style="color:#FFFFFF; text-decoration:underline; font-weight:bold;">Homeowner Login →</a>
                </div>
            </div>
        </div>

        <!-- Provider Login Form -->
        <div class="auth-form-container">
            <h2 style="font-size:1.8rem; margin-bottom:8px;">Pro Partner <span class="text-gradient">Sign In</span></h2>
            <p style="font-size:0.9rem; color:#64748B; margin-bottom:24px;">Sign in to your service provider workspace.</p>

            <!-- Pre-filled Demo Hint for testing convenience -->
            <div style="background:#FEF3C7; border:var(--border-thin); border-radius:8px; padding:10px 14px; margin-bottom:18px; font-size:0.82rem; color:#78350F;">
                <strong>Demo Pro (Electrician):</strong> <code>ramesh.electrician@example.com</code> / <code>password123</code><br>
                <strong>Demo Pro (Plumber):</strong> <code>sunita.plumbing@example.com</code> / <code>password123</code>
            </div>

            <form action="index.php?page=provider-login" method="POST">
                <div class="form-group">
                    <label class="form-label" for="proEmail">Registered Business Email</label>
                    <input type="email" id="proEmail" name="email" class="form-input" required placeholder="pro@example.com" value="ramesh.electrician@example.com">
                </div>

                <div class="form-group">
                    <label class="form-label" for="proPassword">Password</label>
                    <input type="password" id="proPassword" name="password" class="form-input" required placeholder="••••••••" value="password123">
                </div>

                <button type="submit" class="btn btn-accent btn-block" style="padding:14px; font-size:1.05rem; margin-top:8px;">
                    Access Partner Dashboard ⚡
                </button>
            </form>

            <div style="text-align:center; margin-top:24px; font-size:0.9rem; color:#64748B;">
                Want to list your services on HomeFix? 
                <a href="index.php?page=provider-register" style="color:var(--violet); font-weight:800; text-decoration:underline;">Register as Provider</a>
            </div>
        </div>
    </div>
</div>
