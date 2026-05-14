<?php
// app/views/auth/reset-password.php
$pageTitle = 'Reset Password';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="auth-icon">
        <span class="material-symbols-outlined" style="font-size:1.6rem; color:var(--pink);">key</span>
      </div>
      <h1>New Password</h1>
      <p>Choose a strong password of at least 6 characters</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-error">
      <span class="material-symbols-outlined" style="font-size:16px;">info</span>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="/reset-password">
      <div class="form-group">
        <label for="password">New Password</label>
        <div class="password-field">
          <input type="password" id="password" name="password"
                 placeholder="Min. 6 characters" required autofocus>
          <button type="button" class="password-toggle" id="toggle-pass" title="Show password">
            <span class="material-symbols-outlined" id="toggle-icon">visibility</span>
          </button>
        </div>
      </div>
      <div class="form-group">
        <label for="confirm">Confirm Password</label>
        <div class="password-field">
          <input type="password" id="confirm" name="confirm"
                 placeholder="Repeat new password" required>
          <button type="button" class="password-toggle" id="toggle-confirm" title="Show password">
            <span class="material-symbols-outlined" id="toggle-icon-2">visibility</span>
          </button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-lg auth-submit">
        Reset Password
        <span class="material-symbols-outlined">check</span>
      </button>
    </form>
  </div>
</main>

<script>
(function() {
  document.getElementById('toggle-pass').addEventListener('click', function() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('toggle-icon');
    if (input.type === 'password') {
      input.type = 'text'; icon.textContent = 'visibility_off';
    } else {
      input.type = 'password'; icon.textContent = 'visibility';
    }
  });
  document.getElementById('toggle-confirm').addEventListener('click', function() {
    const input = document.getElementById('confirm');
    const icon  = document.getElementById('toggle-icon-2');
    if (input.type === 'password') {
      input.type = 'text'; icon.textContent = 'visibility_off';
    } else {
      input.type = 'password'; icon.textContent = 'visibility';
    }
  });
})();
</script>

<?php require ROOT . '/app/views/partials/footer.php'; ?>