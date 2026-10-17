<div class="container" style="max-width:960px; margin:40px auto; padding:0 20px;">
    <div class="booking-form-card">
        <div class="booking-form-header">
            <span class="badge badge-amber" style="margin-bottom:8px;">Become a Partner Pro</span>
            <h2>List Your Service on <span class="text-gradient">HomeFix</span></h2>
            <p>Join Mumbai's premier local marketplace. Connect with homeowners looking for quality workers.</p>
        </div>

        <form action="index.php?page=provider-register" method="POST">
            <h3 style="font-size:1.15rem; margin-bottom:14px; border-bottom:1px solid #E2E8F0; padding-bottom:6px;">1. Basic Contact Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="regProName">Your Name or Business Title *</label>
                    <input type="text" id="regProName" name="name" class="form-input" required placeholder="e.g. Ramesh Kumar / Rohan PipeCare">
                </div>

                <div class="form-group">
                    <label class="form-label" for="regProPhone">Mobile Phone Number *</label>
                    <input type="tel" id="regProPhone" name="phone" class="form-input" required placeholder="9821012345">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="regProEmail">Business Email *</label>
                    <input type="email" id="regProEmail" name="email" class="form-input" required placeholder="pro@example.com">
                </div>

                <div class="form-group">
                    <label class="form-label" for="regProCity">City</label>
                    <input type="text" id="regProCity" name="city" class="form-input" value="Mumbai" readonly style="background:#F1F5F9;">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="regProPass">Account Password *</label>
                    <input type="password" id="regProPass" name="password" class="form-input" required minlength="6" placeholder="Min 6 characters">
                </div>

                <div class="form-group">
                    <label class="form-label" for="regProConfirm">Confirm Password *</label>
                    <input type="password" id="regProConfirm" name="confirm_password" class="form-input" required minlength="6" placeholder="Re-enter password">
                </div>
            </div>

            <h3 style="font-size:1.15rem; margin:24px 0 14px; border-bottom:1px solid #E2E8F0; padding-bottom:6px;">2. Professional Details &amp; Trade</h3>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="regServiceType">Primary Service Category *</label>
                    <select name="service_type" id="regServiceType" class="form-select" required>
                        <option value="">Select Your Trade</option>
                        <option value="Electrician">⚡ Electrician</option>
                        <option value="Plumber">🔧 Plumber</option>
                        <option value="Carpenter">🪚 Carpenter</option>
                        <option value="Cleaning">🧹 Deep Cleaning</option>
                        <option value="Appliance Repair">❄️ Appliance Repair (AC/Fridge/WM)</option>
                        <option value="Painting">🎨 Painting &amp; Waterproofing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="regProArea">Primary Neighborhood / Area *</label>
                    <input type="text" id="regProArea" name="area" class="form-input" required placeholder="e.g. Andheri, Bandra, Dadar, Powai">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="regProExp">Years of Trade Experience *</label>
                    <input type="number" id="regProExp" name="experience" class="form-input" required min="1" max="50" value="5">
                </div>

                <div class="form-group">
                    <label class="form-label" for="regProRate">Starting Service Fee / Hourly Rate (₹) *</label>
                    <input type="number" step="any" id="regProRate" name="hourly_rate" class="form-input" required value="499.00">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="regProAddress">Physical Address / Workshop *</label>
                <input type="text" id="regProAddress" name="address" class="form-input" required placeholder="Street address or workshop locality in Mumbai">
            </div>

            <div class="form-group">
                <label class="form-label" for="regProBio">Short Professional Bio *</label>
                <textarea id="regProBio" name="bio" class="form-textarea" rows="3" required placeholder="Describe your background, specialty tools, and reliability to potential customers..."></textarea>
            </div>

            <!-- Map Location Coordinates (Where to add map location) -->
            <div class="form-group" style="background:#F1F5F9; border:var(--border-mid); border-radius:var(--radius-md); padding:16px; margin-bottom:20px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; flex-wrap:wrap; gap:8px;">
                    <div>
                        <label class="form-label" style="margin-bottom:2px;">🗺️ Workshop Map Location (GPS)</label>
                        <div style="font-size:0.8rem; color:#64748B;">Places your service pin on the customer map.</div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="autoDetectRegisterLocation()">
                        📍 Auto-Detect My Location
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="regLat" style="font-size:0.82rem;">Latitude</label>
                        <input type="number" step="any" id="regLat" name="latitude" class="form-input" value="19.0760" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="regLng" style="font-size:0.82rem;">Longitude</label>
                        <input type="number" step="any" id="regLng" name="longitude" class="form-input" value="72.8777" required>
                    </div>
                </div>
                <div id="reg-gps-msg" style="font-size:0.82rem; margin-top:8px; color:#6C5CE7; font-weight:700;"></div>
            </div>

            <script>
            function autoDetectRegisterLocation() {
                const msg = document.getElementById('reg-gps-msg');
                if (!navigator.geolocation) {
                    msg.textContent = 'Geolocation not supported by browser.';
                    return;
                }
                msg.textContent = 'Detecting current coordinates...';
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        document.getElementById('regLat').value = pos.coords.latitude.toFixed(6);
                        document.getElementById('regLng').value = pos.coords.longitude.toFixed(6);
                        msg.textContent = '✓ GPS coordinates detected successfully!';
                        msg.style.color = '#059669';
                    },
                    err => {
                        msg.textContent = 'Could not detect GPS location automatically. You can enter coordinates manually.';
                        msg.style.color = '#BE123C';
                    }
                );
            }
            </script>

            <button type="submit" class="btn btn-accent btn-block" style="padding:14px; font-size:1.1rem; margin-top:12px;">
                Complete Registration &amp; Publish Listing ⚡
            </button>
        </form>

        <div style="text-align:center; margin-top:20px; font-size:0.9rem; color:#64748B;">
            Already registered as a provider? 
            <a href="index.php?page=provider-login" style="color:var(--violet); font-weight:800; text-decoration:underline;">Pro Sign In</a>
        </div>
    </div>
</div>
