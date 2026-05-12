<?php
$pageTitle = 'Sign In';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main class="auth-page">

<div class="photo"></div>
  <div class="auth-card">
    <div class="auth-logo">
      <h1>Welcome back</h1>
      <p>Sign in to your Wandering Magnolias account</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/login">
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="andreatochip@gmail.com" required autofocus>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-primary btn-lg auth-submit">Sign In →</button>
    </form>

    <div class="auth-footer">
      Don't have an account? <a href="/register">Create one</a>
    </div>
  </div>
</main>

<!-- <?php require ROOT . '/app/views/partials/footer.php'; ?> -->
