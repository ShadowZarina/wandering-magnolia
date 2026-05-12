<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$loggedIn  = !empty($_SESSION['user_id']);
$firstName = $_SESSION['first_name'] ?? '';
$initial   = $firstName ? strtoupper($firstName[0]) : '?';
$currentPath = strtok($_SERVER['REQUEST_URI'], '?');
?>
<nav class="navbar">
  <div class="container nav-inner">
    <a href="/" class="nav-logo">Wandering <span>Magnolias</span></a>

    <?php if ($loggedIn): ?>
    <div class="nav-links">
      <a href="/recipes"    class="<?= $currentPath === '/recipes'    ? 'active' : '' ?>">Recipes</a>
      <a href="/add-recipe" class="<?= $currentPath === '/add-recipe' ? 'active' : '' ?>">Add Recipe</a>
    </div>
    <div class="nav-actions">
      <div class="nav-user">
        <div class="avatar"><?= htmlspecialchars($initial) ?></div>
        <span><?= htmlspecialchars($firstName) ?></span>
      </div>
      <a href="/logout" class="btn btn-ghost btn-sm">Sign out</a>
    </div>
    <?php else: ?>
    <div class="nav-links">
      <a href="/">Home</a>
    </div>
    <div class="nav-actions">
      <a href="/login"    class="btn btn-ghost btn-sm">Sign in</a>
      <a href="/register" class="btn btn-primary btn-sm">Get Started</a>
    </div>
    <?php endif; ?>
  </div>
</nav>
