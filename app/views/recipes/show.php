<?php
$pageTitle = htmlspecialchars($recipe['title']);
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
$diff    = htmlspecialchars($recipe['difficulty']);
$diffCls = strtolower($diff);
$id      = (int)$recipe['recipe_id'];
?>

<main>
  <div class="container">
    <div style="padding-top:24px; font-size:.85rem; color:var(--gray-500);">
      <a href="/recipes" style="color:var(--pink);">← Back to Recipes</a>
    </div>

    <div class="recipe-hero">
      <img src="<?= htmlspecialchars($recipe['image_url']) ?>"
           alt="<?= htmlspecialchars($recipe['title']) ?>"
           onerror="this.src='https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=1200'">
      <div class="recipe-hero-overlay">
        <div>
          <div style="font-size:.85rem; color:rgba(255,255,255,.7); margin-bottom:8px; font-family:var(--font-head);">Let's Cook</div>
          <h1><?= htmlspecialchars($recipe['title']) ?></h1>
        </div>
      </div>
    </div>

    <div class="recipe-meta-bar">
      <div class="meta-item">
        <span class="meta-label">Difficulty</span>
        <span class="meta-value" style="color:<?= $diffCls === 'easy' ? '#3D8B3D' : ($diffCls === 'hard' ? 'var(--pink)' : '#C47900') ?>"><?= $diff ?></span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Ingredients</span>
        <span class="meta-value"><?= count($ingredients) ?> Items</span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Steps</span>
        <span class="meta-value"><?= count($directions) ?> Steps</span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Type</span>
        <span class="meta-value"><?= $recipe['is_premade'] ? 'Curated' : 'Community' ?></span>
      </div>
    </div>

    <!-- Body -->
    <div class="recipe-body">
      <!-- Ingredients Sidebar -->
      <aside class="ingredients-card">
        <h2>Ingredients</h2>
        <ul>
          <?php foreach ($ingredients as $ing): ?>
          <li>
            <span class="dot"></span>
            <span class="qty">
              <?= $ing['base_quantity'] != 1 || $ing['unit'] ? htmlspecialchars($ing['base_quantity'] . ' ' . $ing['unit']) : '' ?>
            </span>
            <span><?= htmlspecialchars($ing['name']) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
        <div class="grocery-btn-wrap">
          <a href="/grocery?id=<?= $id ?>" class="btn btn-pink">
            Generate Grocery List
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
          </a>
        </div>
      </aside>

      <!-- Directions -->
      <section class="directions-section">
        <h2>Cooking <span class="accent">Instructions</span></h2>
        <?php foreach ($directions as $dir): ?>
        <div class="direction-step">
          <div class="step-num"><?= str_pad($dir['step_number'], 2, '0', STR_PAD_LEFT) ?></div>
          <div class="step-text"><?= htmlspecialchars($dir['instruction']) ?></div>
        </div>
        <?php endforeach; ?>
      </section>
    </div>
  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>
