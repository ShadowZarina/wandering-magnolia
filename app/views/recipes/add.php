<?php
$pageTitle = 'Add Recipe';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main>
  <div class="container">
    <div class="page-header">
      <h1>Add a <span class="accent">Recipe</span></h1>
      <p>Share your culinary creation with the community</p>
    </div>

    <?php if (!empty($error)): ?>
    <div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form class="add-recipe-page" method="POST" action="/add-recipe" enctype="multipart/form-data">

      <div class="form-row-2col">

        <div class="form-card">
          <h2>Basic Info</h2>
          <div class="form-group">
            <label for="title">Recipe Title</label>
            <input type="text" id="title" name="title" placeholder="e.g. Spicy Garlic Butter Shrimp" required>
          </div>
          <div class="form-group diff-level">
            <label>Difficulty Level</label>
           <div class="difficulty-picker">
              <input type="radio" name="difficulty" id="diff-easy" value="Easy" checked class="diff-input">
              <label for="diff-easy" class="diff-pill diff-easy">Easy</label>

              <input type="radio" name="difficulty" id="diff-intermediate" value="Intermediate" class="diff-input">
              <label for="diff-intermediate" class="diff-pill diff-intermediate">Intermediate</label>

              <input type="radio" name="difficulty" id="diff-hard" value="Hard"               class="diff-input">
              <label for="diff-hard" class="diff-pill diff-hard">Hard</label>
            </div>
          </div>
          <div class="form-group">
            <label for="image">Recipe Image</label>
            <input type="file" id="image" name="image" accept="image/*">
          </div>
        </div>

        <div class="form-card">
          <h2>Ingredients</h2>
          <div style="display:grid; grid-template-columns:1fr 90px 100px 36px; gap:10px; margin-bottom:8px;">
            <span style="font-size:.75rem; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:.5px;">Name</span>
            <span style="font-size:.75rem; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:.5px;">Qty</span>
            <span style="font-size:.75rem; font-weight:600; color:var(--gray-500); text-transform:uppercase; letter-spacing:.5px;">Unit</span>
            <span></span>
          </div>
          <div id="ingredient-list">
            <div class="ingredient-row">
              <input type="text"   name="ing_name[]"  placeholder="Ingredient name" required>
              <input type="number" name="ing_qty[]"   placeholder="Qty" step="0.01" min="0" value="1">
              <input type="text"   name="ing_unit[]"  placeholder="g, cup, pcs…">
              <button type="button" class="remove-btn" title="Remove">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>
          </div>
          <button type="button" id="add-ingredient" class="add-more-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add another ingredient
          </button>
        </div>

      </div>

      <!-- Row 2: Directions full width -->
      <div class="form-card">
        <h2>Cooking Instructions</h2>
        <div id="direction-list">
          <div class="direction-row">
            <div class="step-badge">01</div>
            <textarea name="direction[]" rows="3" placeholder="Describe this cooking step…" required></textarea>
            <button type="button" class="remove-btn" title="Remove">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
        </div>
        <button type="button" id="add-direction" class="add-more-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Add another step
        </button>
      </div>

      <!-- Submit -->
      <div style="display:flex; gap:14px; padding-bottom:80px;">
        <button type="submit" class="btn btn-pink btn-lg">Publish Recipe →</button>
        <a href="/recipes" class="btn btn-outline btn-lg">Cancel</a>
      </div>

    </form>
  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>