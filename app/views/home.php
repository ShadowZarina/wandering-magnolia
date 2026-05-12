<?php
$pageTitle = 'Welcome';
require ROOT . '/app/views/partials/head.php';
require ROOT . '/app/views/partials/navbar.php';
?>

<main>
  <section class="hero">
    <div class="container">
      <div class="hero-inner">
        <div class="hero-text">
          <div class="hero-eyebrow">Recipe Collection</div>
          <h1>Explore <span class="accent">Culinary</span><br>Creations</h1>
          <p>Discover handcrafted recipes, build smart grocery lists, and bring your own culinary vision to life — all in one place.</p>
          <div class="hero-actions">
            <a href="/register" class="btn btn-primary btn-lg">Start Cooking</a>
            <a href="/login"    class="btn btn-outline btn-lg">Sign In</a>
          </div>
        </div>
        <div class="hero-img-grid">
          <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=900" alt="Tacos">
          <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600" alt="Salad">
          <img src="https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=600" alt="Risotto">
        </div>
      </div>
    </div>
  </section>
</main>

<?php require ROOT . '/app/views/partials/footer.php'; ?>
