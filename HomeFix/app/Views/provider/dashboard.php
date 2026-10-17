<div class="container" style="padding: 40px 20px;">
    <div class="dashboard-header">
        <div>
            <div class="badge badge-amber" style="margin-bottom:6px;">Service Pro Partner Portal</div>
            <h2><?= htmlspecialchars($provider['name']) ?>'s <span class="text-gradient">Workspace</span></h2>
            <p><?= htmlspecialchars($provider['service_type']) ?> · <?= htmlspecialchars($provider['area']) ?>, Mumbai · Rating: <strong>★ <?= number_format($provider['rating'], 1) ?></strong> (<?= (int)$provider['review_count'] ?> reviews)</p>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="index.php?page=provider-profile" class="btn btn-secondary">
                📷 Profile &amp; Photo Gap
            </a>
            <a href="index.php?page=home" class="btn btn-primary">
                View Live Marketplace
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-amber">
            <span class="stat-num"><?= $stats['pending'] ?></span>
            <span class="stat-label">⏳ Pending Requests</span>
        </div>
        <div class="stat-card stat-violet">
            <span class="stat-num"><?= $stats['accepted'] ?></span>
            <span class="stat-label">📅 Confirmed Upcoming</span>
        </div>
        <div class="stat-card stat-emerald">
            <span class="stat-num"><?= $stats['completed'] ?></span>
            <span class="stat-label">🎉 Completed Jobs</span>
        </div>
        <div class="stat-card">
            <span class="stat-num">₹<?= number_format($stats['earnings'], 0) ?></span>
            <span class="stat-label">💰 Realized Earnings</span>
        </div>
    </div>

    <!-- Bookings Queue -->
    <div style="margin-bottom: 40px;">
        <h3 style="font-size:1.6rem; margin-bottom:16px;">Appointments &amp; Service Requests</h3>
        
        <?php if (empty($bookings)): ?>
            <div class="empty-state">
                <div class="empty-icon">📬</div>
                <h3>No Customer Requests Yet</h3>
                <p>New bookings from homeowners in <?= htmlspecialchars($provider['area']) ?> will appear here in real time.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Homeowner</th>
                            <th>Service &amp; Fee</th>
                            <th>Date &amp; Time</th>
                            <th>Location Address</th>
                            <th>Status</th>
                            <th>Actions (AJAX)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $b): ?>
                            <tr>
                                <td><strong>#<?= (int)$b['booking_id'] ?></strong></td>
                                <td>
                                    <div style="font-weight:700;"><?= htmlspecialchars($b['customer_name']) ?></div>
                                    <div style="font-size:0.82rem; color:#64748B;">📞 <?= htmlspecialchars($b['customer_phone']) ?></div>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($b['service_name']) ?></div>
                                    <strong style="color:#059669;">₹<?= number_format($b['price'], 2) ?></strong>
                                </td>
                                <td>
                                    <div style="font-weight:700;"><?= date('D, d M', strtotime($b['date'])) ?></div>
                                    <div style="font-size:0.82rem; color:#64748B;"><?= date('h:i A', strtotime($b['time'])) ?></div>
                                </td>
                                <td>
                                    <div style="font-size:0.85rem; max-width:220px;"><?= htmlspecialchars($b['address']) ?></div>
                                    <?php if (!empty($b['notes'])): ?>
                                        <div style="font-size:0.75rem; color:#64748B; font-style:italic;">"<?= htmlspecialchars($b['notes']) ?>"</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'Pending' => 'badge-amber',
                                        'Accepted' => 'badge-violet',
                                        'Completed' => 'badge-emerald',
                                        'Rejected' => 'badge-coral',
                                        'Cancelled' => 'badge-dark'
                                    ][$b['status']] ?? 'badge-dark';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($b['status']) ?></span>
                                </td>
                                <td>
                                    <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                        <?php if ($b['status'] === 'Pending'): ?>
                                            <button class="btn btn-lime btn-sm btn-update-booking-status"
                                                    data-booking-id="<?= (int)$b['booking_id'] ?>"
                                                    data-status="Accepted"
                                                    data-confirm="Accept this booking from <?= htmlspecialchars(addslashes($b['customer_name'])) ?>?">
                                                ✓ Accept
                                            </button>
                                            <button class="btn btn-coral btn-sm btn-update-booking-status"
                                                    data-booking-id="<?= (int)$b['booking_id'] ?>"
                                                    data-status="Rejected"
                                                    data-confirm="Decline this service request?">
                                                ✕ Reject
                                            </button>
                                        <?php elseif ($b['status'] === 'Accepted'): ?>
                                            <button class="btn btn-primary btn-sm btn-update-booking-status"
                                                    data-booking-id="<?= (int)$b['booking_id'] ?>"
                                                    data-status="Completed"
                                                    data-confirm="Mark this service as successfully completed?">
                                                🎉 Complete Job
                                            </button>
                                        <?php else: ?>
                                            <span style="font-size:0.82rem; color:#64748B; font-weight:700;">No Action Needed</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Split Grid: Manage Services & Customer Reviews -->
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:32px; align-items:start;">
        <!-- Services Catalog -->
        <div class="pro-summary-card">
            <h3 style="font-size:1.3rem; margin-bottom:12px;">Your Listed Services</h3>
            <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px;">
                <?php foreach ($services as $s): ?>
                    <div style="display:flex; justify-content:space-between; align-items:center; background:#F8FAFC; border:1px solid #E2E8F0; padding:10px 14px; border-radius:8px;">
                        <div>
                            <strong><?= htmlspecialchars($s['service_name']) ?></strong>
                            <div style="font-size:0.8rem; color:#64748B;"><?= htmlspecialchars($s['description'] ?: 'Standard offering') ?></div>
                        </div>
                        <span class="service-price-tag">₹<?= number_format($s['price'], 0) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Add Service Mini-Form -->
            <form action="index.php?page=provider-add-service" method="POST" style="background:#FEF3C7; border:var(--border-mid); border-radius:var(--radius-md); padding:16px;">
                <h4 style="font-size:0.95rem; margin-bottom:10px;">＋ Add New Service Offering</h4>
                <div class="form-group" style="margin-bottom:10px;">
                    <input type="text" name="service_name" class="form-input" style="padding:8px 12px; font-size:0.9rem;" placeholder="e.g. Inverter AC Gas Refill" required>
                </div>
                <div style="display:flex; gap:10px; margin-bottom:10px;">
                    <input type="number" step="any" name="price" class="form-input" style="padding:8px 12px; font-size:0.9rem;" placeholder="Price ₹" required>
                    <input type="text" name="category" class="form-input" style="padding:8px 12px; font-size:0.9rem;" value="<?= htmlspecialchars($provider['service_type']) ?>">
                </div>
                <button type="submit" class="btn btn-dark btn-sm btn-block">Add to Catalog</button>
            </form>
        </div>

        <!-- Verified Customer Reviews -->
        <div class="pro-summary-card">
            <h3 style="font-size:1.3rem; margin-bottom:12px;">Verified Customer Reviews</h3>
            <?php if (empty($reviews)): ?>
                <p style="font-size:0.9rem; color:#64748B;">No customer reviews submitted yet. Completed bookings will prompt customers to leave ratings.</p>
            <?php else: ?>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <?php foreach ($reviews as $rev): ?>
                        <div style="background:#F8FAFC; border:var(--border-thin); border-radius:8px; padding:12px 16px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                <strong style="font-size:0.92rem;"><?= htmlspecialchars($rev['reviewer_name']) ?></strong>
                                <span class="badge badge-amber" style="font-size:0.75rem;">★ <?= (int)$rev['rating'] ?>/5</span>
                            </div>
                            <p style="font-size:0.88rem; color:#334155; margin:0;"><?= htmlspecialchars($rev['comment']) ?></p>
                            <span style="font-size:0.75rem; color:#94A3B8;"><?= date('d M Y', strtotime($rev['created_at'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
