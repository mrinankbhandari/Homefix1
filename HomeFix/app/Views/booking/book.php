<div class="container">
    <div style="margin-top: 30px;">
        <a href="index.php?page=home#explore-services" class="btn btn-secondary btn-sm">
            ← Back to Fixers
        </a>
    </div>

    <div class="booking-page-grid">
        <!-- Left: Provider Profile Summary matching Slide 6 sketch -->
        <div class="pro-summary-card">
            <?php 
            $proAvatarUrl = get_provider_avatar($provider); 
            $proFirstName = strtolower(explode(' ', trim($provider['name']))[0]);
            ?>
            <div style="display:flex; align-items:center; gap:18px; margin-bottom:20px;">
                <!-- Profile Image Slot / Manual Photo Gap -->
                <?php if (!empty($proAvatarUrl)): ?>
                    <div class="avatar-slot avatar-slot-filled" style="width:84px; height:84px; min-width:84px;" title="Profile Photo of <?= htmlspecialchars($provider['name']) ?>">
                        <img src="<?= htmlspecialchars($proAvatarUrl) ?>" alt="<?= htmlspecialchars($provider['name']) ?>" class="provider-photo-img">
                    </div>
                <?php else: ?>
                    <div class="avatar-slot photo-gap-slot" style="width:84px; height:84px; min-width:84px;" title="Profile Photo Gap — Add '<?= $proFirstName ?>.jpg' to public/uploads/avatars/">
                        <div class="photo-gap-inner">
                            <span class="photo-gap-camera">📷</span>
                            <span class="avatar-initials" style="font-size:1.5rem;"><?= strtoupper(substr($provider['name'], 0, 2)) ?></span>
                            <span class="photo-gap-badge">Photo Gap</span>
                        </div>
                    </div>
                <?php endif; ?>

                <div>
                    <span class="badge badge-violet" style="margin-bottom:4px;"><?= htmlspecialchars($provider['service_type']) ?></span>
                    <h2 style="font-size:1.6rem; margin-bottom:4px;"><?= htmlspecialchars($provider['name']) ?></h2>
                    <div style="font-size:0.9rem; font-weight:700; color:#475569;">
                        <span style="color:#B45309; background:#FEF3C7; padding:2px 6px; border-radius:4px; border:1px solid #0F172A;">★ <?= number_format($provider['rating'], 1) ?></span>
                        <span>(<?= (int)$provider['review_count'] ?> reviews)</span> · 
                        <span>📍 <?= htmlspecialchars($provider['area']) ?>, Mumbai</span>
                    </div>
                </div>
            </div>

            <p style="font-size:0.92rem; color:#334155; margin-bottom:20px; line-height:1.5;">
                <?= htmlspecialchars($provider['bio']) ?>
            </p>

            <div style="background:#F8FAFC; border:var(--border-mid); border-radius:var(--radius-md); padding:16px; margin-bottom:20px;">
                <div style="font-size:0.8rem; text-transform:uppercase; font-weight:800; color:#64748B; margin-bottom:10px;">Pro Credentials &amp; Details</div>
                <div style="display:flex; flex-direction:column; gap:8px; font-size:0.9rem; font-weight:600;">
                    <div>⏱ Experience: <strong><?= (int)$provider['experience'] ?>+ Years</strong></div>
                    <div>📞 Phone: <strong><?= htmlspecialchars($provider['phone']) ?></strong></div>
                    <div>📍 Workshop: <strong><?= htmlspecialchars($provider['address']) ?></strong></div>
                    <div>🛡️ Background: <strong>Identity Verified</strong></div>
                </div>
            </div>

            <div style="border-top:var(--border-thin); padding-top:16px; display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.85rem; font-weight:700; color:#64748B;">Base Hourly Rate</span>
                <strong style="font-size:1.4rem; font-family:var(--font-heading);">₹<?= number_format($provider['hourly_rate'], 0) ?></strong>
            </div>
        </div>

        <!-- Right: Booking Form (Matching Rough Sketch from PPT Slide 6) -->
        <div class="booking-form-card">
            <div class="booking-form-header">
                <span class="badge badge-amber" style="margin-bottom:8px;">Slide 6 Specification</span>
                <h2>Confirm <span class="text-gradient">Booking</span></h2>
                <p style="font-size:0.92rem; color:#64748B;">Choose your specific service, pick date &amp; time, and enter your address.</p>
            </div>

            <?php if (!$isLoggedIn): ?>
                <div style="background:#EDE9FE; border:var(--border-mid); border-radius:var(--radius-md); padding:16px 20px; margin-bottom:24px;">
                    <h4 style="color:#5B21B6; margin-bottom:6px;">Have a Homeowner Account?</h4>
                    <p style="font-size:0.9rem; color:#4C1D95; margin-bottom:12px;">Please log in so your booking is linked to your dashboard and you can track status &amp; submit reviews.</p>
                    <a href="index.php?page=login" class="btn btn-primary btn-sm">
                        Log In Now ↗
                    </a>
                </div>
            <?php endif; ?>

            <form action="index.php?page=book" method="POST">
                <input type="hidden" name="provider_id" value="<?= (int)$provider['provider_id'] ?>">

                <!-- Service Selection Dropdown -->
                <div class="form-group">
                    <label class="form-label" for="bookingServiceSelect">Service Required *</label>
                    <select name="service_id" id="bookingServiceSelect" class="form-select" required>
                        <?php foreach ($services as $srv): ?>
                            <option value="<?= (int)$srv['service_id'] ?>" 
                                    data-price="<?= (float)$srv['price'] ?>"
                                    <?= ($selectedServiceId === (int)$srv['service_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($srv['service_name']) ?> — ₹<?= number_format($srv['price'], 0) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date & Time Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="bookingDateInput">Appointment Date *</label>
                        <input type="date" name="date" id="bookingDateInput" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="bookingTimeInput">Preferred Time Slot *</label>
                        <input type="time" name="time" id="bookingTimeInput" class="form-input" required value="10:00">
                    </div>
                </div>

                <!-- Address Input -->
                <div class="form-group">
                    <label class="form-label" for="bookingAddressInput">Service Address (Street, Flat/Building, Mumbai) *</label>
                    <input type="text" name="address" id="bookingAddressInput" class="form-input" required placeholder="e.g. Flat 302, Palm View, Link Road, Andheri West, Mumbai">
                </div>

                <!-- Issue Notes / Symptoms -->
                <div class="form-group">
                    <label class="form-label" for="bookingNotesInput">What needs fixing? (Optional details)</label>
                    <textarea name="notes" id="bookingNotesInput" class="form-textarea" rows="3" placeholder="Briefly describe the leak, electrical issue, or repairs needed..."></textarea>
                </div>

                <!-- Live Price Calculation Box -->
                <div class="price-summary-box">
                    <div>
                        <div style="font-size:0.8rem; text-transform:uppercase; font-weight:800; color:#854D0E;">Estimated Total</div>
                        <div style="font-size:0.85rem; color:#713F12;">Includes standard inspection &amp; base labor</div>
                    </div>
                    <div>
                        <strong id="bookingDisplayPrice" style="font-family:var(--font-heading); font-size:1.8rem; color:#0F172A;">
                            ₹<?= !empty($services) ? number_format($services[0]['price'], 2) : number_format($provider['hourly_rate'], 2) ?>
                        </strong>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="font-size:1.1rem; padding:14px;">
                    Confirm Booking ⚡
                </button>
            </form>
        </div>
    </div>
</div>
