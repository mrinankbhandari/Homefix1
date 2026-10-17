<?php if (!empty($flashSuccess) || !empty($flashError) || !empty($flashInfo)): ?>
<div class="container" style="margin-top: 20px;">
    <?php if (!empty($flashSuccess)): ?>
        <div class="toast toast-success" style="position:static; margin-bottom:12px; pointer-events:auto; display:flex;">
            <span>✅</span>
            <span><?= htmlspecialchars($flashSuccess) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashError)): ?>
        <div class="toast toast-error" style="position:static; margin-bottom:12px; pointer-events:auto; display:flex;">
            <span>⚠️</span>
            <span><?= htmlspecialchars($flashError) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashInfo)): ?>
        <div class="toast toast-info" style="position:static; margin-bottom:12px; pointer-events:auto; display:flex;">
            <span>💡</span>
            <span><?= htmlspecialchars($flashInfo) ?></span>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>
