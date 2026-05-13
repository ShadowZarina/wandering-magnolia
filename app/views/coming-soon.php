<?php
$pageTitle = 'Coming Soon';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main>
  <div class="container">
    <div class="coming-soon-page">

      <div class="coming-soon-illustration">
        <div class="cs-pot">
          <div class="cs-steam cs-steam-1"></div>
          <div class="cs-steam cs-steam-2"></div>
          <div class="cs-steam cs-steam-3"></div>
          <span class="material-symbols-outlined">cooking</span>
        </div>
      </div>

      <div class="cs-eyebrow">
        <span class="material-symbols-outlined">construction</span>
        Page on the works
      </div>

      <h1>Something <span class="accent">Delicious</span><br>is Cooking</h1>

      <p>We are still putting the finishing touches on this page. Check back soon — good things take time, just like a proper risotto.</p>

      <div class="cs-actions">
        <a href="/recipes" class="btn btn-primary btn-lg">
          <span class="material-symbols-outlined">restaurant_menu</span>
          Browse Recipes
        </a>
        <a href="javascript:history.back()" class="btn btn-outline btn-lg">
          <span class="material-symbols-outlined">arrow_back</span>
          Go Back
        </a>
      </div>

    </div>
  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>