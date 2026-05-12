<?php
$pageTitle = 'Grocery List — ' . htmlspecialchars($recipe['title']);
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main>
  <div class="container">
    <div class="grocery-page">
      <div class="grocery-header">
        <h1>Grocery <span class="accent">List</span></h1>
        <p><?= htmlspecialchars($recipe['title']) ?></p>
      </div>

      <div class="grocery-card">
        <?php foreach ($ingredients as $ing): ?>
        <div class="grocery-item">
          <div class="grocery-check">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <span class="grocery-name"><?= htmlspecialchars($ing['name']) ?></span>
          <span class="grocery-qty">
            <?php
              $qty  = (float) $ing['base_quantity'];
              $unit = trim($ing['unit']);
              echo htmlspecialchars(($qty != 1 || $unit ? $qty . ' ' . $unit : ''));
            ?>
          </span>
        </div>
        <?php endforeach; ?>

        <div class="grocery-actions">
          <button id="check-all-grocery" class="btn btn-primary btn-sm">Check All</button>
          <button id="clear-grocery"     class="btn btn-ghost btn-sm">Clear All</button>
          <a href="/recipe?id=<?= (int)$recipe['recipe_id'] ?>" class="btn btn-outline btn-sm">
            ← Back to Recipe
          </a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>
