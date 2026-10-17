<div class="container" style="padding: 40px 20px;">
    <div class="dashboard-header">
        <div>
            <div class="badge badge-emerald" style="margin-bottom:6px;">Homeowner Portal</div>
            <h2>My Fix <span class="text-gradient">Bookings</span></h2>
            <p>Track your scheduled services, contact your assigned fixer, and leave verified reviews.</p>
        </div>
        <div>
            <a href="index.php?page=home#explore-services" class="btn btn-primary">
                ＋ Book Another Pro
            </a>
        </div>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="empty-state">
            <div class="empty-icon">🧰</div>
            <h3>No Bookings Yet</h3>
            <p>You haven't requested any home service repairs yet. Explore certified pros in Mumbai!</p>
            <a href="index.php?page=home#explore-services" class="btn btn-primary">
                Find Local Fixers ⚡
            </a>
        </div>
    <?php else: ?>
        <div style="display:flex; flex-direction:column; gap:20px;">
            <?php foreach ($bookings as $b): ?>
                <div class="provider-card" style="gap:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
                        <div style="display:flex; gap:16px; align-items:center;">
                            <!-- Provider Avatar Slot / Photo Gap -->
                            <div class="avatar-slot" style="width:56px; height:56px; min-width:56px;">
                                <?php if (!empty($b['provider_image']) && file_exists(UPLOAD_DIR . $b['provider_image'])): ?>
                                    <img src="<?= UPLOAD_URL . htmlspecialchars($b['provider_image']) ?>" alt="<?= htmlspecialchars($b['provider_name']) ?>">
                                <?php else: ?>
                                    <span class="avatar-initials" style="font-size:1.2rem;"><?= strtoupper(substr($b['provider_name'], 0, 2)) ?></span>
                                    <span class="avatar-gap-hint">Photo Gap</span>
                                <?php endif; ?>
                            </div>

                            <div>
                                <span class="badge badge-violet" style="font-size:0.75rem;"><?= htmlspecialchars($b['service_type']) ?></span>
                                <h3 style="font-size:1.3rem; margin:2px 0;"><?= htmlspecialchars($b['provider_name']) ?></h3>
                                <div style="font-size:0.85rem; color:#64748B; font-weight:600;">
                                    📞 <?= htmlspecialchars($b['provider_phone']) ?> · 📍 <?= htmlspecialchars($b['area']) ?>, Mumbai
                                </div>
                            </div>
                        </div>

                        <div>
                            <!-- Booking Status Badge -->
                            <?php
                            $statusColors = [
                                'Pending'   => 'badge-amber',
                                'Accepted'  => 'badge-violet',
                                'Completed' => 'badge-emerald',
                                'Rejected'  => 'badge-coral',
                                'Cancelled' => 'badge-dark'
                            ];
                            $badgeClass = $statusColors[$b['status']] ?? 'badge-dark';
                            ?>
                            <span class="badge <?= $badgeClass ?>" style="font-size:0.9rem; padding:6px 14px;">
                                Status: <?= htmlspecialchars($b['status']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Details Row -->
                    <div style="background:#F8FAFC; border:1.5px dashed #CBD5E1; border-radius:var(--radius-md); padding:14px 18px; margin:8px 0; display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px;">
                        <div>
                            <span style="font-size:0.75rem; text-transform:uppercase; font-weight:800; color:#64748B;">Service</span>
                            <div style="font-weight:700;"><?= htmlspecialchars($b['service_name']) ?></div>
                        </div>
                        <div>
                            <span style="font-size:0.75rem; text-transform:uppercase; font-weight:800; color:#64748B;">Scheduled Time</span>
                            <div style="font-weight:700;">📅 <?= date('D, d M Y', strtotime($b['date'])) ?> at <?= date('h:i A', strtotime($b['time'])) ?></div>
                        </div>
                        <div>
                            <span style="font-size:0.75rem; text-transform:uppercase; font-weight:800; color:#64748B;">Rate</span>
                            <div style="font-weight:800; color:#059669;">₹<?= number_format($b['price'], 2) ?></div>
                        </div>
                        <div>
                            <span style="font-size:0.75rem; text-transform:uppercase; font-weight:800; color:#64748B;">Service Location</span>
                            <div style="font-size:0.85rem;"><?= htmlspecialchars($b['address']) ?></div>
                        </div>
                    </div>

                    <?php if (!empty($b['notes'])): ?>
                        <div style="font-size:0.85rem; color:#475569;">
                            <strong>Your Notes:</strong> <?= htmlspecialchars($b['notes']) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Action Bar -->
                    <div style="display:flex; justify-content:space-between; align-items:center; border-top:var(--border-thin); padding-top:12px; margin-top:4px;">
                        <span style="font-size:0.82rem; color:#64748B;">Booking ID: #<?= (int)$b['booking_id'] ?> · Placed on <?= date('d M Y', strtotime($b['created_at'])) ?></span>

                        <div style="display:flex; gap:10px;">
                            <?php if ($b['status'] === 'Pending'): ?>
                                <button class="btn btn-coral btn-sm btn-update-booking-status"
                                        data-booking-id="<?= (int)$b['booking_id'] ?>"
                                        data-status="Cancelled"
                                        data-confirm="Are you sure you want to cancel this booking request?">
                                    Cancel Request ✕
                                </button>
                            <?php elseif ($b['status'] === 'Completed'): ?>
                                <?php if (!empty($b['review_rating'])): ?>
                                    <div class="badge badge-amber">
                                        ★ Your Review: <?= (int)$b['review_rating'] ?>/5 ("<?= htmlspecialchars($b['review_comment']) ?>")
                                    </div>
                                <?php else: ?>
                                    <button class="btn btn-accent btn-sm" onclick="openReviewModal(<?= (int)$b['booking_id'] ?>, '<?= htmlspecialchars(addslashes($b['provider_name'])) ?>', '<?= htmlspecialchars(addslashes($b['service_name'])) ?>')">
                                        ★ Rate &amp; Leave Review
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
