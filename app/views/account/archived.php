<?php
$pageTitle = 'Account Archived';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main>
  <div class="container">
    <div class="archived-page">

      <div class="archived-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 8v13H3V8"/><rect x="1" y="3" width="22" height="5" rx="1"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
      </div>

      <h1>Your account has been archived</h1>
      <p class="archived-sub">
        You have <strong><?= $daysLeft ?> day<?= $daysLeft !== 1 ? 's' : '' ?></strong> to restore your account before it is permanently deleted.
      </p>

      <div class="archived-countdown">
        <div class="countdown-bar-wrap">
          <div class="countdown-bar" style="width: <?= round(($daysLeft / 30) * 100) ?>%"></div>
        </div>
        <div class="countdown-labels">
          <span>Archived <?= (new DateTime($user['archived_at']))->format('M j, Y') ?></span>
          <span>Deletes <?= (new DateTime($user['archived_at']))->modify('+30 days')->format('M j, Y') ?></span>
        </div>
      </div>

      <div class="archived-actions">
        <form method="POST" action="/account/restore">
          <button type="submit" class="btn btn-pink btn-lg">Restore My Account</button>
        </form>
        <a href="/logout" class="btn btn-ghost btn-sm" style="margin-top:12px;">Leave without restoring</a>
      </div>

    </div>
  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>