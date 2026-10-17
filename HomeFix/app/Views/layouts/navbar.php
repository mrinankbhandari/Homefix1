<header class="site-header">
    <div class="container">
        <nav class="navbar">
            <a href="index.php?page=home" class="brand-logo">
                <span class="logo-badge">🛠️</span>
                <span>Home<span class="text-gradient">Fix</span></span>
            </a>

            <ul class="nav-links">
                <li><a href="index.php?page=home#explore-services" class="nav-link">Find Pros</a></li>
                <li><a href="index.php?page=home#how-it-works" class="nav-link">How It Works</a></li>
                <li><a href="index.php?page=xml-catalog" class="nav-link">XML Catalog ✦</a></li>
            </ul>

            <div class="nav-actions">
                <?php if (!empty($user)): ?>
                    <?php if ($user['role'] === 'customer'): ?>
                        <a href="index.php?page=my-bookings" class="btn btn-secondary btn-sm">
                            📋 My Bookings
                        </a>
                        <div class="user-pill">
                            <div class="avatar-slot" style="width:32px; height:32px; min-width:32px; border-radius:50%;">
                                <?php if (!empty($user['profile_image']) && file_exists(UPLOAD_DIR . $user['profile_image'])): ?>
                                    <img src="<?= UPLOAD_URL . htmlspecialchars($user['profile_image']) ?>" alt="<?= htmlspecialchars($user['name']) ?>">
                                <?php else: ?>
                                    <span class="avatar-initials" style="font-size:0.85rem;"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                            <span><?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></span>
                        </div>
                    <?php elseif ($user['role'] === 'provider'): ?>
                        <a href="index.php?page=provider-dashboard" class="btn btn-accent btn-sm">
                            ⚡ Pro Dashboard
                        </a>
                        <a href="index.php?page=provider-profile" class="btn btn-secondary btn-sm">
                            📷 Photo Slot & Bio
                        </a>
                    <?php elseif ($user['role'] === 'admin'): ?>
                        <a href="index.php?page=admin-dashboard" class="btn btn-coral btn-sm">
                            🛡️ Admin Console
                        </a>
                    <?php endif; ?>

                    <a href="index.php?page=logout" class="btn btn-dark btn-sm" title="Sign out">
                        Sign Out
                    </a>

                <?php else: ?>
                    <!-- Separate Login Pages per Project Requirement -->
                    <a href="index.php?page=login" class="btn btn-secondary btn-sm">
                        👤 Homeowner Login
                    </a>
                    <a href="index.php?page=provider-login" class="btn btn-accent btn-sm">
                        ⚡ Pro Partner Portal
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
