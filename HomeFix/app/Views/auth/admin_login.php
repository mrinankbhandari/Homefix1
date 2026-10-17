<div class="container container-card">
    <div class="booking-form-card" style="border-color:#BE123C; box-shadow:6px 6px 0px #BE123C;">
        <div style="text-align:center; margin-bottom:24px;">
            <div class="badge badge-coral" style="margin-bottom:8px;">Administrative Control</div>
            <h2 style="font-size:1.8rem;">Admin <span style="color:#BE123C;">Console</span></h2>
            <p style="font-size:0.9rem; color:#64748B;">Restricted access for HomeFix platform moderation and management.</p>
        </div>

        <div style="background:#FFE4E6; border:var(--border-thin); border-radius:8px; padding:10px 14px; margin-bottom:18px; font-size:0.82rem; color:#9F1239;">
            <strong>Admin Credentials:</strong> <code>admin@homefix.com</code> / <code>admin123</code>
        </div>

        <form action="index.php?page=admin-login" method="POST">
            <div class="form-group">
                <label class="form-label" for="adminEmail">Admin Email</label>
                <input type="email" id="adminEmail" name="email" class="form-input" required value="admin@homefix.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="adminPass">Security Password</label>
                <input type="password" id="adminPass" name="password" class="form-input" required value="admin123">
            </div>

            <button type="submit" class="btn btn-coral btn-block" style="padding:14px; font-size:1rem; margin-top:8px;">
                Enter Admin Console 🛡️
            </button>
        </form>

        <div style="text-align:center; margin-top:20px; font-size:0.85rem;">
            <a href="index.php?page=home" style="color:#64748B; text-decoration:underline;">← Back to Public Marketplace</a>
        </div>
    </div>
</div>
