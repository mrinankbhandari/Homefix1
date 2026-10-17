<div class="container container-narrow" style="padding: 40px 20px;">
    <div style="margin-bottom: 24px;">
        <a href="index.php?page=provider-dashboard" class="btn btn-secondary btn-sm">
            ← Back to Dashboard
        </a>
    </div>

    <div class="booking-form-card">
        <div class="booking-form-header">
            <div class="badge badge-amber" style="margin-bottom:8px;">Pro Account Settings</div>
            <h2>Edit Profile &amp; <span class="text-gradient">Photo Slot Gap</span></h2>
            <p>Customize your service profile and manage your listing avatar.</p>
        </div>

        <!-- Explanatory note about manual profile image gap per request -->
        <div style="background:#FEF3C7; border:var(--border-mid); border-radius:var(--radius-md); padding:16px 20px; margin-bottom:24px;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                <span style="font-size:1.3rem;">📷</span>
                <strong style="font-size:0.95rem; color:#78350F;">Profile Photo Gap &amp; Manual Image Slot:</strong>
            </div>
            <p style="font-size:0.88rem; color:#92400E; line-height:1.45;">
                Per project requirements, an explicit gap and slot has been kept for profile pictures. You can either select an image below to upload it automatically, or manually drop image files directly into <code>public/uploads/avatars/</code> at any time. If no custom image is present, your stylized monogram initials will automatically display!
            </p>
        </div>

        <form action="index.php?page=provider-profile" method="POST" enctype="multipart/form-data">
            <!-- Profile Avatar Preview & Slot -->
            <div style="display:flex; align-items:center; gap:20px; margin-bottom:24px; background:#F8FAFC; border:1.5px dashed #CBD5E1; border-radius:var(--radius-md); padding:18px;">
                <div class="avatar-slot" style="width:84px; height:84px; min-width:84px;">
                    <?php if (!empty($provider['profile_image']) && file_exists(UPLOAD_DIR . $provider['profile_image'])): ?>
                        <img src="<?= UPLOAD_URL . htmlspecialchars($provider['profile_image']) ?>" alt="<?= htmlspecialchars($provider['name']) ?>">
                    <?php else: ?>
                        <span class="avatar-initials" style="font-size:1.8rem;"><?= strtoupper(substr($provider['name'], 0, 2)) ?></span>
                        <span class="avatar-gap-hint">Photo Gap</span>
                    <?php endif; ?>
                </div>

                <div style="flex:1;">
                    <label class="form-label" for="profile_image_input">Upload Custom Photo (Optional)</label>
                    <input type="file" id="profile_image_input" name="profile_image" accept="image/*" class="form-input" style="padding:8px 12px; font-size:0.85rem;">
                    <div style="font-size:0.75rem; color:#64748B; margin-top:4px;">Supported: JPG, PNG, WEBP (Square 1:1 recommended)</div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="proName">Your Professional Name *</label>
                    <input type="text" id="proName" name="name" class="form-input" value="<?= htmlspecialchars($provider['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="proPhone">Phone Contact Number *</label>
                    <input type="text" id="proPhone" name="phone" class="form-input" value="<?= htmlspecialchars($provider['phone']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="proArea">Primary Mumbai Area *</label>
                    <input type="text" id="proArea" name="area" class="form-input" value="<?= htmlspecialchars($provider['area']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="proExp">Experience (Years) *</label>
                    <input type="number" id="proExp" name="experience" class="form-input" value="<?= (int)$provider['experience'] ?>" required min="0" max="60">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="proRate">Base Starting Price / Hourly Rate (₹) *</label>
                <input type="number" step="any" id="proRate" name="hourly_rate" class="form-input" value="<?= (float)$provider['hourly_rate'] ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="proAddress">Workshop / Base Address *</label>
                <input type="text" id="proAddress" name="address" class="form-input" value="<?= htmlspecialchars($provider['address']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="proBio">About You &amp; Your Skills (Bio) *</label>
                <textarea id="proBio" name="bio" class="form-textarea" rows="4" required><?= htmlspecialchars($provider['bio']) ?></textarea>
            </div>

            <!-- Map Location & GPS Coordinates (Where to add map location) -->
            <div class="form-group" style="background:#F1F5F9; border:var(--border-mid); border-radius:var(--radius-md); padding:16px; margin-bottom:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap; gap:8px;">
                    <div>
                        <label class="form-label" style="margin-bottom:2px;">🗺️ Map Location &amp; GPS Coordinates</label>
                        <div style="font-size:0.8rem; color:#64748B;">Controls where your service pin appears on the MapTiler neighborhood map.</div>
                    </div>
                    <button type="button" class="btn btn-accent btn-sm" onclick="detectProLocation()">
                        📍 Use My Current GPS Location
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="proLat" style="font-size:0.82rem;">Latitude (e.g. 19.1197)</label>
                        <input type="number" step="any" id="proLat" name="latitude" class="form-input" value="<?= (float)($provider['latitude'] ?? 19.0760) ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="proLng" style="font-size:0.82rem;">Longitude (e.g. 72.8464)</label>
                        <input type="number" step="any" id="proLng" name="longitude" class="form-input" value="<?= (float)($provider['longitude'] ?? 72.8777) ?>" required>
                    </div>
                </div>
                <div id="gps-status" style="font-size:0.82rem; margin-top:8px; color:#6C5CE7; font-weight:700;"></div>
            </div>

            <script>
            function detectProLocation() {
                const status = document.getElementById('gps-status');
                if (!navigator.geolocation) {
                    status.textContent = 'Geolocation is not supported by your browser.';
                    return;
                }
                status.textContent = 'Detecting current GPS coordinates...';
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        document.getElementById('proLat').value = pos.coords.latitude.toFixed(6);
                        document.getElementById('proLng').value = pos.coords.longitude.toFixed(6);
                        status.textContent = '✓ GPS location detected successfully!';
                        status.style.color = '#059669';
                    },
                    err => {
                        status.textContent = 'Could not retrieve GPS coordinates (' + err.message + '). Please enter coordinates manually.';
                        status.style.color = '#BE123C';
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            }
            </script>

            <button type="submit" class="btn btn-primary btn-block" style="padding:14px; font-size:1.05rem;">
                Save Profile Changes 💾
            </button>
        </form>
    </div>
</div>
