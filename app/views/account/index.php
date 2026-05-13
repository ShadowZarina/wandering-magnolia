<?php
$pageTitle = 'My Account';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
$initial   = strtoupper($_SESSION['first_name'][0] ?? '?');
$firstName = $_SESSION['first_name'] ?? '';
$search    = htmlspecialchars($_GET['search'] ?? '');

function accountQuery(array $overrides = []): string {
    $params = array_merge([
        'search' => $_GET['search'] ?? '',
        'page'   => $_GET['page']   ?? 1,
    ], $overrides);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return $params ? '?' . http_build_query($params) : '';
}
?>

<main>
  <div class="container">

    <div class="account-header">
      <div class="account-avatar"><?= htmlspecialchars($initial) ?></div>
      <div class="account-info">
        <h1><?= htmlspecialchars($firstName) ?></h1>
        <p><?= $total ?> recipe<?= $total !== 1 ? 's' : '' ?> published</p>
      </div>
      <div style="margin-left:auto; display:flex; gap:10px; align-items:center;">
        <?php if ($trashCount > 0): ?>
        <a href="/account/trash" class="btn btn-ghost btn-sm" style="position:relative;">
          <span class="material-symbols-outlined">delete</span>
          Trash
          <span class="trash-badge"><?= $trashCount ?></span>
        </a>
        <?php else: ?>
        <a href="/account/trash" class="btn btn-ghost btn-sm">
          <span class="material-symbols-outlined">delete</span>
          Trash
        </a>
        <?php endif; ?>
        <a href="/account/settings" class="btn btn-outline btn-sm">
          <span class="material-symbols-outlined">settings</span>
          Settings
        </a>
      </div>
    </div>

    <div class="account-section">
      <div class="account-section-header">
        <h2>My <span class="accent">Recipes</span></h2>
        <a href="/add-recipe" class="btn btn-primary btn-sm">
          <span class="material-symbols-outlined">add</span>
          Add Recipe
        </a>
      </div>

      <!-- Search -->
      <form method="GET" action="/account" class="account-search-form">
        <div class="search-pill">
          <span class="material-symbols-outlined search-icon" style="font-size:18px;">search</span>
          <input type="text" name="search" value="<?= $search ?>" placeholder="Search your recipes...">
          <?php if ($search): ?>
            <a href="/account" class="search-clear" title="Clear">
              <span class="material-symbols-outlined" style="font-size:16px;">close</span>
            </a>
          <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
      </form>

      <?php if ($search): ?>
      <div class="results-meta" style="margin-top:16px;">
        <?= $total ?> result<?= $total !== 1 ? 's' : '' ?> for "<strong><?= $search ?></strong>"
        <a href="/account" class="results-clear">Clear</a>
      </div>
      <?php endif; ?>

      <?php if (empty($recipes)): ?>
        <div class="account-empty">
          <h3>No recipes found</h3>
          <?php if ($search): ?>
            <p>No recipes match your search.</p>
            <a href="/account" class="btn btn-outline btn-sm">Clear search</a>
          <?php else: ?>
            <p>Start sharing your culinary creations with the community.</p>
            <a href="/add-recipe" class="btn btn-pink">Add Your First Recipe</a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="account-recipes-list">
          <?php foreach ($recipes as $r):
            $diff    = htmlspecialchars($r['difficulty']);
            $diffCls = strtolower($diff);
            $id      = (int)$r['recipe_id'];
            $isRemix = !empty($r['remixed_from']);
          ?>
          <div class="account-recipe-row">
            <div class="account-recipe-img">
              <img src="<?= htmlspecialchars($r['image_url']) ?>"
                   alt="<?= htmlspecialchars($r['title']) ?>"
                   onerror="this.src='https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=200'">
            </div>
            <div class="account-recipe-info">
              <h3><?= htmlspecialchars($r['title']) ?></h3>
              <div style="display:flex; gap:8px; align-items:center; margin-top:4px; flex-wrap:wrap;">
                <span class="diff-tag <?= $diffCls ?>"><?= $diff ?></span>
                <?php if ($isRemix): ?>
                  <span class="remix-tag" style="font-size:.72rem;">Remixed from <?= htmlspecialchars($r['original_title']) ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div class="account-recipe-actions">
              <a href="/recipe?id=<?= $id ?>" class="btn btn-ghost btn-sm">
                <span class="material-symbols-outlined">visibility</span>
              </a>
              <a href="/edit-recipe?id=<?= $id ?>" class="btn btn-outline btn-sm">
                <span class="material-symbols-outlined">edit</span>
              </a>
              <form method="POST" action="/delete-recipe"
                    onsubmit="return confirm('Move this recipe to trash?')">
                <input type="hidden" name="recipe_id" value="<?= $id ?>">
                <button type="submit" class="btn btn-delete btn-sm">
                  <span class="material-symbols-outlined">delete</span>
                </button>
              </form>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="pagination" style="padding-block: 32px 48px;">
          <?php if ($currentPage > 1): ?>
            <a href="<?= '/account' . accountQuery(['page' => $currentPage - 1]) ?>" class="page-btn">
              <span class="material-symbols-outlined">chevron_left</span>
            </a>
          <?php else: ?>
            <span class="page-btn disabled"><span class="material-symbols-outlined">chevron_left</span></span>
          <?php endif; ?>

          <?php
          $start = max(1, $currentPage - 2);
          $end   = min($totalPages, $currentPage + 2);
          if ($start > 1): ?>
            <a href="<?= '/account' . accountQuery(['page' => 1]) ?>" class="page-btn">1</a>
            <?php if ($start > 2): ?><span class="page-dots">...</span><?php endif; ?>
          <?php endif; ?>

          <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i === $currentPage): ?>
              <span class="page-btn active"><?= $i ?></span>
            <?php else: ?>
              <a href="<?= '/account' . accountQuery(['page' => $i]) ?>" class="page-btn"><?= $i ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if ($end < $totalPages): ?>
            <?php if ($end < $totalPages - 1): ?><span class="page-dots">...</span><?php endif; ?>
            <a href="<?= '/account' . accountQuery(['page' => $totalPages]) ?>" class="page-btn"><?= $totalPages ?></a>
          <?php endif; ?>

          <?php if ($currentPage < $totalPages): ?>
            <a href="<?= '/account' . accountQuery(['page' => $currentPage + 1]) ?>" class="page-btn">
              <span class="material-symbols-outlined">chevron_right</span>
            </a>
          <?php else: ?>
            <span class="page-btn disabled"><span class="material-symbols-outlined">chevron_right</span></span>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>

  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>