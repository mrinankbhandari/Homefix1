<div class="container" style="padding: 40px 20px;">
    <div class="dashboard-header" style="border-color:#BE123C; box-shadow:6px 6px 0px #BE123C;">
        <div>
            <div class="badge badge-coral" style="margin-bottom:6px;">Admin Platform Oversight</div>
            <h2>HomeFix <span style="color:#BE123C;">Administration Console</span></h2>
            <p>Manage users, verify service professionals, moderate marketplace listings, and track platform metrics.</p>
        </div>
        <div>
            <a href="index.php?page=home" class="btn btn-secondary">
                View Public Marketplace ↗
            </a>
        </div>
    </div>

    <!-- Platform Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-violet">
            <span class="stat-num"><?= $customerCount ?></span>
            <span class="stat-label">👥 Homeowner Customers</span>
        </div>
        <div class="stat-card stat-amber">
            <span class="stat-num"><?= $providerCount ?></span>
            <span class="stat-label">🛠️ Active Service Pros</span>
        </div>
        <div class="stat-card stat-emerald">
            <span class="stat-num"><?= $stats['total'] ?></span>
            <span class="stat-label">📋 Platform Bookings</span>
        </div>
        <div class="stat-card">
            <span class="stat-num">₹<?= number_format($stats['revenue'], 0) ?></span>
            <span class="stat-label">💰 Completed Job Volume</span>
        </div>
    </div>

    <!-- 1. Manage Providers & Remove Fake Listings (PPT Slide 5 requirement) -->
    <div style="margin-bottom: 40px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 style="font-size:1.6rem;">Manage Providers &amp; Listings (<?= count($providers) ?>)</h3>
            <span class="badge badge-coral">Listing Moderation Active</span>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Pro Details</th>
                        <th>Trade Category</th>
                        <th>Location Area</th>
                        <th>Rating / Reviews</th>
                        <th>Base Rate</th>
                        <th>Verification</th>
                        <th>Moderate Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($providers as $p): ?>
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div class="avatar-slot" style="width:42px; height:42px; min-width:42px;">
                                        <?php if (!empty($p['profile_image']) && file_exists(UPLOAD_DIR . $p['profile_image'])): ?>
                                            <img src="<?= UPLOAD_URL . htmlspecialchars($p['profile_image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                                        <?php else: ?>
                                            <span class="avatar-initials" style="font-size:1rem;"><?= strtoupper(substr($p['name'], 0, 2)) ?></span>
                                            <span class="avatar-gap-hint">Gap</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($p['name']) ?></strong>
                                        <div style="font-size:0.8rem; color:#64748B;">📞 <?= htmlspecialchars($p['phone']) ?> · <?= htmlspecialchars($p['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-violet"><?= htmlspecialchars($p['service_type']) ?></span></td>
                            <td><?= htmlspecialchars($p['area']) ?>, Mumbai</td>
                            <td>
                                <span style="font-weight:700; color:#B45309;">★ <?= number_format($p['rating'], 1) ?></span>
                                <span style="font-size:0.82rem; color:#64748B;">(<?= (int)$p['review_count'] ?>)</span>
                            </td>
                            <td><strong>₹<?= number_format($p['hourly_rate'], 0) ?></strong></td>
                            <td><span class="badge badge-emerald">Verified ✓</span></td>
                            <td>
                                <!-- Remove Fake Listing Form -->
                                <form action="index.php?page=admin-delete-provider" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to remove <?= htmlspecialchars(addslashes($p['name'])) ?> from the marketplace? This will delete the listing and associated services.');">
                                    <input type="hidden" name="provider_id" value="<?= (int)$p['provider_id'] ?>">
                                    <button type="submit" class="btn btn-coral btn-sm">
                                        Remove Fake Listing ✕
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Registered Platform Accounts -->
    <div style="margin-bottom: 40px;">
        <h3 style="font-size:1.6rem; margin-bottom:16px;">Platform User Directory (<?= count($users) ?> Accounts)</h3>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Account Role</th>
                        <th>Phone</th>
                        <th>Registered Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#<?= (int)$u['user_id'] ?></td>
                            <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <?php
                                $roleBadge = ($u['role'] === 'admin') ? 'badge-coral' : (($u['role'] === 'provider') ? 'badge-amber' : 'badge-violet');
                                ?>
                                <span class="badge <?= $roleBadge ?>"><?= ucfirst(htmlspecialchars($u['role'])) ?></span>
                            </td>
                            <td><?= htmlspecialchars($u['phone'] ?: 'N/A') ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($u['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. All Platform Bookings -->
    <div>
        <h3 style="font-size:1.6rem; margin-bottom:16px;">All Marketplace Bookings Log</h3>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Assigned Pro</th>
                        <th>Service</th>
                        <th>Date &amp; Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td><strong>#<?= (int)$b['booking_id'] ?></strong></td>
                            <td>
                                <div><?= htmlspecialchars($b['customer_name']) ?></div>
                                <div style="font-size:0.8rem; color:#64748B;">📞 <?= htmlspecialchars($b['customer_phone']) ?></div>
                            </td>
                            <td>
                                <div><strong><?= htmlspecialchars($b['provider_name']) ?></strong></div>
                                <div style="font-size:0.8rem; color:#64748B;"><?= htmlspecialchars($b['service_type']) ?></div>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($b['service_name']) ?></div>
                                <div style="font-size:0.85rem; color:#059669; font-weight:bold;">₹<?= number_format($b['price'], 2) ?></div>
                            </td>
                            <td><?= date('d M Y', strtotime($b['date'])) ?> at <?= date('h:i A', strtotime($b['time'])) ?></td>
                            <td>
                                <?php
                                $bStatus = [
                                    'Pending' => 'badge-amber',
                                    'Accepted' => 'badge-violet',
                                    'Completed' => 'badge-emerald',
                                    'Rejected' => 'badge-coral',
                                    'Cancelled' => 'badge-dark'
                                ][$b['status']] ?? 'badge-dark';
                                ?>
                                <span class="badge <?= $bStatus ?>"><?= htmlspecialchars($b['status']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
