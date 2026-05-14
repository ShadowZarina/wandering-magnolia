<?php
// app/views/auth/forgot-password.php
$pageTitle = 'Forgot Password';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">
      <div class="auth-icon">
        <span class="material-symbols-outlined" style="font-size:1.6rem; color:var(--pink);">lock_reset</span>
      </div>
      <h1>Forgot Password</h1>
      <p>Enter your email and we'll send you a 6-digit code</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-error">
      <span class="material-symbols-outlined" style="font-size:16px;">info</span>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="/forgot-password">
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required autofocus>
      </div>
      <button type="submit" class="btn btn-primary btn-lg auth-submit">
        Send OTP Code
        <span class="material-symbols-outlined">send</span>
      </button>
    </form>

    <div class="auth-footer">
      Remember your password? <a href="/login">Sign in</a>
    </div>
  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>