<?php
$pageTitle = 'Recipes';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main>
  <div class="container">
    <div class="page-header">
      <div class="page-header-row">
        <div>
          <h1>Explore <span class="accent">Recipes</span></h1>
          <p>Discover curated dishes and community creations</p>
        </div>
        <a href="/add-recipe" class="btn btn-primary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Add Recipe
        </a>
      </div>
    </div>

    <?php if (empty($recipes)): ?>
      <p style="color:var(--gray-500); padding-block:40px;">No recipes yet. <a href="/add-recipe" style="color:var(--pink)">Add the first one!</a></p>
    <?php else: ?>
    <div class="recipes-grid">
      <?php foreach ($recipes as $r):
        $diff  = htmlspecialchars($r['difficulty']);
        $cls   = strtolower($diff);
        $id    = (int)$r['recipe_id'];
      ?>
      <article class="recipe-card">
        <div class="card-img">
          <img src="<?= htmlspecialchars($r['image_url']) ?>"
               alt="<?= htmlspecialchars($r['title']) ?>"
               loading="lazy"
               onerror="this.src='https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=600'">
          <span class="card-badge <?= $cls ?>"><?= $diff ?></span>
        </div>
        <div class="card-body">
          <h3><?= htmlspecialchars($r['title']) ?></h3>
          <div class="card-footer-row">
            <span style="font-size:.8rem; color:var(--gray-500); font-weight:500;">
              <?= $r['is_premade'] ? 'Curated' : 'Community' ?>
            </span>
            <a href="/recipe?id=<?= $id ?>" class="btn btn-primary btn-sm">
              See Recipe
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>
