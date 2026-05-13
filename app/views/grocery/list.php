<?php
$pageTitle = 'Grocery List — ' . htmlspecialchars($recipe['title']);
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main>
  <div class="container">
    <div class="grocery-page">

      <div class="grocery-header">
        <!-- <div class="grocery-icon">
          <span class="material-symbols-outlined">shopping_cart</span>
        </div> -->
        <h1>Grocery <span class="accent">List</span></h1>
        <p><?= htmlspecialchars($recipe['title']) ?></p>
      </div>

      <!-- Serving Scaler -->
      <div class="scaler-card">
        <div class="scaler-label">
          <span class="material-symbols-outlined">group</span>
          Servings
        </div>
        <div class="scaler-controls">
          <button type="button" class="scaler-btn" id="scaler-down" aria-label="Decrease servings">
            <span class="material-symbols-outlined">remove</span>
          </button>
          <div class="scaler-display">
            <span id="scaler-value">1</span>
            <!-- <span class="scaler-unit">x base</span> -->
          </div>
          <button type="button" class="scaler-btn" id="scaler-up" aria-label="Increase servings">
            <span class="material-symbols-outlined">add</span>
          </button>
        </div>
        <div class="scaler-presets">
          <button class="scaler-preset active" data-value="1">1x</button>
          <button class="scaler-preset" data-value="2">2x</button>
          <button class="scaler-preset" data-value="3">3x</button>
          <button class="scaler-preset" data-value="4">4x</button>
        </div>
      </div>

      <div class="grocery-card">
        <?php foreach ($ingredients as $ing): ?>
        <div class="grocery-item" data-base="<?= (float)$ing['base_quantity'] ?>" data-unit="<?= htmlspecialchars($ing['unit']) ?>">
          <div class="grocery-check">
            <span class="material-symbols-outlined" style="font-size:14px; opacity:0;">check</span>
          </div>
          <span class="grocery-name"><?= htmlspecialchars($ing['name']) ?></span>
          <span class="grocery-qty">
            <span class="qty-value"><?= $ing['base_quantity'] != 0 ? htmlspecialchars($ing['base_quantity']) : '' ?></span>
            <?php if (trim($ing['unit'])): ?>
              <span class="qty-unit"><?= htmlspecialchars($ing['unit']) ?></span>
            <?php endif; ?>
          </span>
        </div>
        <?php endforeach; ?>

        <div class="grocery-actions">
          <button id="check-all-grocery" class="btn btn-primary btn-sm">
            <span class="material-symbols-outlined">checklist</span>
            Check All
          </button>
          <button id="clear-grocery" class="btn btn-ghost btn-sm">
            <span class="material-symbols-outlined">refresh</span>
            Clear All
          </button>
          <a href="/recipe?id=<?= (int)$recipe['recipe_id'] ?>" class="btn btn-outline btn-sm">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Recipe
          </a>
        </div>
      </div>

    </div>
  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>