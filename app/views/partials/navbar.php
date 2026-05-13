<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$loggedIn    = !empty($_SESSION['user_id']);
$firstName   = $_SESSION['first_name'] ?? '';
$initial     = $firstName ? strtoupper($firstName[0]) : '?';
$currentPath = strtok($_SERVER['REQUEST_URI'], '?');
?>
<nav class="navbar">
  <div class="container nav-inner">
    <a href="/" class="nav-logo">Wandering <span>Magnolias</span></a>

    <?php if ($loggedIn): ?>
    <div class="nav-links" id="nav-links">
      <a href="/recipes"    class="<?= $currentPath === '/recipes'    ? 'active' : '' ?>">Recipes</a>
      <a href="/add-recipe" class="<?= $currentPath === '/add-recipe' ? 'active' : '' ?>">Add Recipe</a>
    </div>
    <div class="nav-actions">
      <a href="/account" class="nav-user" style="text-decoration:none;">
        <div class="avatar"><?= htmlspecialchars($initial) ?></div>
        <span class="nav-user-name"><?= htmlspecialchars($firstName) ?></span>
      </a>
      <a href="/logout" class="btn btn-ghost btn-sm nav-signout">Sign out</a>
      <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
        <span class="material-symbols-outlined" id="hamburger-icon">menu</span>
      </button>
    </div>
    <?php else: ?>
    <div class="nav-links" id="nav-links">
      <a href="/">Home</a>
    </div>
    <div class="nav-actions">
      <a href="/login"    class="btn btn-ghost btn-sm nav-signin">Sign in</a>
      <a href="/register" class="btn btn-primary btn-sm nav-register">Get Started</a>
      <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
        <span class="material-symbols-outlined" id="hamburger-icon">menu</span>
      </button>
    </div>
    <?php endif; ?>
  </div>

  <!-- Mobile Drawer -->
  <div class="nav-drawer" id="nav-drawer">
    <div class="nav-drawer-inner">
      <?php if ($loggedIn): ?>
        <div class="drawer-user">
          <div class="avatar avatar-lg"><?= htmlspecialchars($initial) ?></div>
          <span><?= htmlspecialchars($firstName) ?></span>
        </div>
        <div class="drawer-divider"></div>
        <a href="/recipes"    class="drawer-link <?= $currentPath === '/recipes'    ? 'active' : '' ?>">
          <span class="material-symbols-outlined">restaurant_menu</span> Recipes
        </a>
        <a href="/add-recipe" class="drawer-link <?= $currentPath === '/add-recipe' ? 'active' : '' ?>">
          <span class="material-symbols-outlined">add_circle</span> Add Recipe
        </a>
        <a href="/account"    class="drawer-link <?= $currentPath === '/account'    ? 'active' : '' ?>">
          <span class="material-symbols-outlined">manage_accounts</span> My Account
        </a>
        <div class="drawer-divider"></div>
        <a href="/logout" class="drawer-link drawer-logout">
          <span class="material-symbols-outlined">logout</span> Sign out
        </a>
      <?php else: ?>
        <a href="/"        class="drawer-link <?= $currentPath === '/'        ? 'active' : '' ?>">
          <span class="material-symbols-outlined">home</span> Home
        </a>
        <a href="/login"   class="drawer-link <?= $currentPath === '/login'   ? 'active' : '' ?>">
          <span class="material-symbols-outlined">login</span> Sign in
        </a>
        <a href="/register" class="drawer-link <?= $currentPath === '/register' ? 'active' : '' ?>">
          <span class="material-symbols-outlined">person_add</span> Get Started
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script>
(function() {
  const hamburger = document.getElementById('hamburger');
  const drawer    = document.getElementById('nav-drawer');
  const icon      = document.getElementById('hamburger-icon');
  if (!hamburger || !drawer) return;

  hamburger.addEventListener('click', () => {
    const open = drawer.classList.toggle('open');
    hamburger.setAttribute('aria-expanded', open);
    icon.textContent = open ? 'close' : 'menu';
    document.body.style.overflow = open ? 'hidden' : '';
  });

  document.addEventListener('click', (e) => {
    if (!drawer.contains(e.target) && !hamburger.contains(e.target)) {
      drawer.classList.remove('open');
      hamburger.setAttribute('aria-expanded', false);
      icon.textContent = 'menu';
      document.body.style.overflow = '';
    }
  });
})();
</script>