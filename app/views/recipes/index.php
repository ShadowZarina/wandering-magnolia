<?php
$pageTitle = 'Recipes';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';

$search     = htmlspecialchars($_GET['search']     ?? '');
$difficulty = htmlspecialchars($_GET['difficulty'] ?? '');

function buildQuery(array $overrides = []): string {
    $params = array_merge([
        'search'     => $_GET['search']     ?? '',
        'difficulty' => $_GET['difficulty'] ?? '',
        'page'       => $_GET['page']       ?? 1,
    ], $overrides);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return $params ? '?' . http_build_query($params) : '';
}
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

    <!-- Search + Filter Bar -->
    <div class="search-bar-wrap">
      <form method="GET" action="/recipes" class="search-bar-form">
        <div class="search-pill">
          <span class="material-symbols-outlined">search</span>
          <input type="text" name="search" value="<?= $search ?>" placeholder="Search recipes...">
          <?php if ($search): ?>
            <a href="<?= '/recipes' . buildQuery(['search' => '', 'page' => 1]) ?>" class="search-clear" title="Clear search">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
          <?php endif; ?>
          <button type="submit" class="btn btn-primary btn-sm">Search</button>
        </div>
        <div class="filter-container">
          <div class="filter-pills">
            <?php
            $diffs = ['Easy', 'Intermediate', 'Hard'];
            foreach ($diffs as $d):
              $active = $difficulty === $d;
              $href   = $active
                ? '/recipes' . buildQuery(['difficulty' => '', 'page' => 1])
                : '/recipes' . buildQuery(['difficulty' => $d, 'page' => 1]);
              $cls    = 'filter-pill ' . strtolower($d) . ($active ? ' active' : '');
            ?>
            <a href="<?= $href ?>" class="<?= $cls ?>"><?= $d ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </form>
    </div>

    <!-- Results count -->
    <?php if ($search || $difficulty): ?>
    <div class="results-meta">
      <?= $total ?> result<?= $total !== 1 ? 's' : '' ?>
      <?php if ($search): ?> for "<strong><?= $search ?></strong>"<?php endif; ?>
      <?php if ($difficulty): ?> &middot; <?= $difficulty ?><?php endif; ?>
      <a href="/recipes" class="results-clear">Clear filters</a>
    </div>
    <?php endif; ?>

    <?php if (empty($recipes)): ?>
      <div class="empty-state">
        <p>No recipes found.</p>
        <?php if ($search || $difficulty): ?>
          <a href="/recipes" class="btn btn-outline btn-sm">Clear filters</a>
        <?php else: ?>
          <a href="/add-recipe" class="btn btn-pink btn-sm">Add the first one</a>
        <?php endif; ?>
      </div>
    <?php else: ?>
    <div class="recipes-grid">
      <?php foreach ($recipes as $r):
        $diff    = htmlspecialchars($r['difficulty']);
        $cls     = strtolower($diff);
        $id      = (int)$r['recipe_id'];
        $isRemix = !empty($r['remixed_from']);
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
          <div class="card-head">
          <h3><?= htmlspecialchars($r['title']) ?></h3>
          <?php if ($isRemix): ?>
            <div class="remix-tag"><?= htmlspecialchars($r['original_title']) ?><span class="material-symbols-outlined">shuffle</span></div>
          <?php endif; ?>
          </div>
          <div class="card-footer-row" style="margin-top:<?= $isRemix ? '12px' : '0' ?>">
            <span style="font-size:.8rem; color:var(--gray-500); font-weight:500;">
              <?php if ($r['is_premade']): ?>
                Curated
              <?php elseif (!empty($r['first_name'])): ?>
                <?= htmlspecialchars($r['first_name']) ?>
              <?php else: ?>
                Community
              <?php endif; ?>
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

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination">
      <?php if ($currentPage > 1): ?>
        <a href="<?= '/recipes' . buildQuery(['page' => $currentPage - 1]) ?>" class="page-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
      <?php else: ?>
        <span class="page-btn disabled">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        </span>
      <?php endif; ?>

      <?php
      $start = max(1, $currentPage - 2);
      $end   = min($totalPages, $currentPage + 2);
      if ($start > 1): ?>
        <a href="<?= '/recipes' . buildQuery(['page' => 1]) ?>" class="page-btn">1</a>
        <?php if ($start > 2): ?><span class="page-dots">...</span><?php endif; ?>
      <?php endif; ?>

      <?php for ($i = $start; $i <= $end; $i++): ?>
        <?php if ($i === $currentPage): ?>
          <span class="page-btn active"><?= $i ?></span>
        <?php else: ?>
          <a href="<?= '/recipes' . buildQuery(['page' => $i]) ?>" class="page-btn"><?= $i ?></a>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ($end < $totalPages): ?>
        <?php if ($end < $totalPages - 1): ?><span class="page-dots">...</span><?php endif; ?>
        <a href="<?= '/recipes' . buildQuery(['page' => $totalPages]) ?>" class="page-btn"><?= $totalPages ?></a>
      <?php endif; ?>

      <?php if ($currentPage < $totalPages): ?>
        <a href="<?= '/recipes' . buildQuery(['page' => $currentPage + 1]) ?>" class="page-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
      <?php else: ?>
        <span class="page-btn disabled">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

  </div>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>