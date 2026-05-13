<?php
$pageTitle = 'Recently Deleted';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main>
  <div class="container">

    <div class="page-header">
      <div>
        <a href="/account" style="font-size:.85rem; color:var(--pink);">← Back to Account</a>
        <h1 style="margin-top:8px;">Recently <span class="accent">Deleted</span></h1>
        <p>Recipes here are permanently deleted after 30 days.</p>
      </div>
    </div>

    <?php if (empty($recipes)): ?>
      <div class="account-empty" style="margin-bottom:80px;">
        <span class="material-symbols-outlined" style="font-size:3rem; color:var(--gray-300); display:block; margin-bottom:16px;">delete_sweep</span>
        <h3>Trash is empty</h3>
        <p>Deleted recipes will appear here for 30 days before being permanently removed.</p>
        <a href="/account" class="btn btn-outline btn-sm" style="margin-top:8px;">Back to Account</a>
      </div>
    <?php else: ?>
      <div class="account-recipes-list" style="padding-bottom:80px;">
        <?php foreach ($recipes as $r):
          $diff      = htmlspecialchars($r['difficulty']);
          $diffCls   = strtolower($diff);
          $id        = (int)$r['recipe_id'];
          $deletedAt = new DateTime($r['deleted_at']);
          $expiresAt = (clone $deletedAt)->modify('+30 days');
          $now       = new DateTime();
          $daysLeft  = max(0, (int)$now->diff($expiresAt)->days);
          $isUrgent  = $daysLeft <= 3;
        ?>
        <div class="account-recipe-row trash-row">
          <div class="account-recipe-img" style="opacity:.6;">
            <img src="<?= htmlspecialchars($r['image_url']) ?>"
                 alt="<?= htmlspecialchars($r['title']) ?>"
                 onerror="this.src='https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=200'">
          </div>
          <div class="account-recipe-info">
            <h3 style="opacity:.7;"><?= htmlspecialchars($r['title']) ?></h3>
            <div style="display:flex; gap:8px; align-items:center; margin-top:4px; flex-wrap:wrap;">
              <span class="diff-tag <?= $diffCls ?>"><?= $diff ?></span>
              <span class="trash-expiry <?= $isUrgent ? 'urgent' : '' ?>">
                <span class="material-symbols-outlined" style="font-size:13px;">schedule</span>
                <?php if ($daysLeft === 0): ?>
                  Deletes today
                <?php elseif ($daysLeft === 1): ?>
                  Deletes tomorrow
                <?php else: ?>
                  <?= $daysLeft ?> days left
                <?php endif; ?>
              </span>
            </div>
          </div>
          <div class="account-recipe-actions">
            <!-- Restore -->
            <form method="POST" action="/restore-recipe">
              <input type="hidden" name="recipe_id" value="<?= $id ?>">
              <button type="submit" class="btn btn-outline btn-sm" title="Restore recipe">
                <span class="material-symbols-outlined">restore_from_trash</span>
                Restore
              </button>
            </form>
            <!-- Permanent delete -->
            <form method="POST" action="/delete-recipe-permanent"
                  onsubmit="return confirm('Permanently delete this recipe? This cannot be undone.')">
              <input type="hidden" name="recipe_id" value="<?= $id ?>">
              <button type="submit" class="btn btn-delete btn-sm" title="Delete permanently">
                <span class="material-symbols-outlined">delete_forever</span>
              </button>
            </form>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>