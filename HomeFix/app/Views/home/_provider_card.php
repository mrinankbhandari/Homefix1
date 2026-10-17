<?php
$avatarUrl = get_provider_avatar($p);
$firstName = strtolower(explode(' ', trim($p['name']))[0]);
?>
<div class="provider-card" id="provider-card-<?= (int)$p['provider_id'] ?>">
    <div class="provider-card-header">
        <!-- Prominent Profile Image Section / Photo Gap -->
        <div class="provider-photo-container">
            <?php if (!empty($avatarUrl)): ?>
                <div class="avatar-slot avatar-slot-filled" title="Profile Photo of <?= htmlspecialchars($p['name']) ?>">
                    <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="provider-photo-img">
                </div>
            <?php else: ?>
                <!-- Explicit Profile Image Gap (Ready for manual addition) -->
                <div class="avatar-slot photo-gap-slot" title="Profile Photo Gap — Add '<?= $firstName ?>.jpg' or 'provider_<?= (int)$p['provider_id'] ?>.jpg' to public/uploads/avatars/">
                    <div class="photo-gap-inner">
                        <span class="photo-gap-camera">📷</span>
                        <span class="avatar-initials"><?= strtoupper(substr($p['name'], 0, 2)) ?></span>
                        <span class="photo-gap-badge">Photo Gap</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="provider-card-info">
            <div class="provider-name-row">
                <h3 class="provider-name"><?= htmlspecialchars($p['name']) ?></h3>
                <?php if (!empty($p['is_verified'])): ?>
                    <span class="verified-icon" title="Verified HomeFix Professional">✓</span>
                <?php endif; ?>
                <span class="badge badge-violet"><?= htmlspecialchars($p['service_type']) ?></span>
            </div>

            <div class="provider-metrics">
                <span class="rating-stars">★ <?= number_format($p['rating'], 1) ?></span>
                <span>(<?= (int)$p['review_count'] ?> reviews)</span>
                <span>📍 <?= htmlspecialchars($p['area']) ?>, <?= htmlspecialchars($p['city']) ?></span>
                <span>⏱ <?= (int)$p['experience'] ?>+ Yrs Exp</span>
            </div>

            <p class="provider-bio"><?= htmlspecialchars($p['bio']) ?></p>
        </div>
    </div>

    <?php if (!empty($p['services'])): ?>
        <div class="provider-services-list">
            <div style="font-size:0.75rem; text-transform:uppercase; font-weight:800; color:#64748B;">Popular Services:</div>
            <?php foreach (array_slice($p['services'], 0, 2) as $s): ?>
                <div class="service-item-row">
                    <span><?= htmlspecialchars($s['service_name']) ?></span>
                    <span class="service-price-tag">₹<?= number_format($s['price'], 0) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="provider-card-footer">
        <div class="hourly-rate-box">
            <span>Starting From</span>
            <strong>₹<?= number_format($p['hourly_rate'], 0) ?></strong>
        </div>

        <div style="display:flex; gap:8px;">
            <button class="btn btn-secondary btn-sm" onclick="window.HomeFixMap.focusProvider(<?= (float)$p['latitude'] ?>, <?= (float)$p['longitude'] ?>)" title="Center on Map">
                📍 Map
            </button>
            <a href="index.php?page=book&provider_id=<?= (int)$p['provider_id'] ?>" class="btn btn-primary btn-sm">
                Book This Pro ⚡
            </a>
        </div>
    </div>
</div>
