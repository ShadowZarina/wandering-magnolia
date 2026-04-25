# ROLES

ANDREA
Back-End:
- Database (SQL)
- JS for Grocery List Builder
- Add Own Recipes
- Coordinate on ERD/Tables



EJ
- Wireframes, send to Rey (use Figma if possible)
- ERD/Tables for entities and attributes for data (eg. recipes, users, etc.)
- Individual recipes for Recipe Pages


REY
- Plan number of pages (one welcome, one recipes, one page for indiv recipe), cards that will pop up
- Front-end (HTML/CSS/JS) for Welcome and Recipes Page (refer to allrecipes.com)
- Front-end for individual recipe page (including Add Recipes and List Builder)

# FEATURES

FINAL FEATURES
- Welcome Page
- Recipes Page (show list of recipes with images)
	= make 6 predefined recipes and display
	- add own recipes button is here
- Smart Grocery List Builder
	= Button to make list -> generates checklist automatically based on ingredients available in recipe 
	- put this as a button in each individual recipe page
- Add Own Recipes 
	= press Add button -> , text input for title, inputs/textarea for ingredients & directions
- Local Database / Host (on laptop)


# RECIPE PAGE

## SQL

```
CREATE DATABASE IF NOT EXISTS recipe_db;
USE recipe_db;

CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    prep_time VARCHAR(50),
    category VARCHAR(100),
    ingredients TEXT NOT NULL,
    directions TEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT 'default-recipe.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert 1 of 6 Predefined Recipes
INSERT INTO recipes (title, prep_time, category, ingredients, directions) 
VALUES ('Classic Margherita Pizza', '20 mins', 'Dinner', 'Flour, Yeast, Water, Tomato Sauce, Mozzarella, Basil', '1. Prep dough. 2. Add toppings. 3. Bake at 450°F.');
```

## db.sql

```
<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "recipe_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

# GROCERY LIST BUILDER

## html/js + php

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Illustra Recipes | Recipe Gallery</title>
    <style>
        .recipe-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .recipe-card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
        .recipe-card img { width: 100%; height: 150px; object-fit: cover; }
        .modal { display: none; position: fixed; background: rgba(0,0,0,0.5); width: 100%; height: 100%; top:0; left:0; }
        .modal-content { background: white; margin: 10% auto; padding: 20px; width: 50%; }
    </style>
</head>
<body>

    <h1>Recipes Page</h1>
    <button onclick="location.href='add_recipe.php'">+ Add Own Recipe</button>
    <hr>

    <div class="recipe-grid">
        <?php
        include 'db.php';
        $sql = "SELECT * FROM recipes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "
                <div class='recipe-card'>
                    <img src='{$row['image_url']}' alt='Recipe Image'>
                    <h3>{$row['title']}</h3>
                    <p><strong>Time:</strong> {$row['prep_time']} | <strong>Cat:</strong> {$row['category']}</p>
                    <button onclick='generateGroceryList(\"{$row['ingredients']}\")'>Generate Grocery List</button>
                </div>";
            }
        }
        ?>
    </div>

    <div id="groceryModal" class="modal">
        <div class="modal-content">
            <h2>Smart Grocery List</h2>
            <div id="listItems"></div>
            <button onclick="document.getElementById('groceryModal').style.display='none'">Close</button>
        </div>
    </div>

    <script>
        function generateGroceryList(ingredients) {
            const listDiv = document.getElementById('listItems');
            const items = ingredients.split(','); // Assumes comma-separated
            
            let html = '<ul>';
            items.forEach(item => {
                html += `<li><input type="checkbox"> ${item.trim()}</li>`;
            });
            html += '</ul>';
            
            listDiv.innerHTML = html;
            document.getElementById('groceryModal').style.display = 'block';
        }
    </script>
</body>
</html>
```

# ADD RECIPES (add_recipe.php)

```
<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $prep = $_POST['prep_time'];
    $cat = $_POST['category'];
    $ing = $_POST['ingredients'];
    $dir = $_POST['directions'];

    $sql = "INSERT INTO recipes (title, prep_time, category, ingredients, directions) 
            VALUES ('$title', '$prep', '$cat', '$ing', '$dir')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    }
}
?>

<form method="POST">
    <input type="text" name="title" placeholder="Recipe Title" required><br>
    <input type="text" name="prep_time" placeholder="Prep Time (e.g. 30 mins)"><br>
    <input type="text" name="category" placeholder="Category (e.g. Breakfast)"><br>
    <textarea name="ingredients" placeholder="Ingredients (separate with commas)"></textarea><br>
    <textarea name="directions" placeholder="Directions"></textarea><br>
    <button type="submit">Save Recipe</button>
</form>
```

# SAMPLE RECIPES IN DATABASE

```
USE recipe_db;

-- 1. Spaghetti and Meatballs
INSERT INTO recipes (title, prep_time, category, ingredients, directions) 
VALUES (
    'Classic Spaghetti and Meatballs', 
    '1 hour', 
    'Pasta', 
    'Spaghetti noodles, Ground beef, Breadcrumbs, Parmesan cheese, Parsley, Egg, Garlic, Marinara sauce, Olive oil', 
    '1. Mix beef, breadcrumbs, cheese, and spices; form into balls. 2. Brown meatballs in a pan. 3. Simmer meatballs in marinara sauce. 4. Serve over boiled spaghetti.'
);

-- 2. Chicken Quesadillas
INSERT INTO recipes (title, prep_time, category, ingredients, directions) 
VALUES (
    'Best Chicken Quesadilla', 
    '25 mins', 
    'Mexican', 
    'Flour tortillas, Cooked shredded chicken, Shredded cheese (Monterey Jack or Cheddar), Bell peppers, Onions, Taco seasoning, Butter', 
    '1. Sauté peppers and onions with seasoning. 2. Butter one side of tortilla. 3. Layer cheese, chicken, and veggies on half. 4. Fold and grill until golden brown.'
);

-- 3. Classic Potato Salad
INSERT INTO recipes (title, prep_time, category, ingredients, directions) 
VALUES (
    'Classic Potato Salad', 
    '45 mins', 
    'Salad / Side', 
    'Russet potatoes, Hard-boiled eggs, Mayonnaise, Yellow mustard, Red onion, Celery, Relish, Paprika, Salt and Pepper', 
    '1. Boil potatoes until tender; cool and peel. 2. Chop potatoes and eggs. 3. Whisk mayo, mustard, and seasonings. 4. Fold everything together and refrigerate.'
);
```

# HTML/CSS FOR RECIPE PAGE

## HTML

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Detail - Allrecipes Style</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="recipe-container">
    <header class="recipe-header">
        <nav class="breadcrumb">Home > Recipes > Pasta</nav>
        <h1 class="recipe-title">Classic Spaghetti and Meatballs</h1>
        <div class="recipe-meta-top">
            <span class="rating">★★★★★ 4.8 (1,240 Ratings)</span>
        </div>
    </header>

    <section class="recipe-hero">
        <div class="image-wrapper">
            <img src="https://imagesvc.meredithcorp.io/v3/mm/image?url=https%3A%2F%2Fstatic.onecms.io%2Fwp-content%2Fuploads%2Fsites%2F43%2F2023%2F01%2F12%2F21353-classic-spaghetti-and-meatballs-ddmfs-4x3-1504.jpg" alt="Spaghetti">
        </div>
        <div class="action-bar">
            <button class="btn-action">Save</button>
            <button class="btn-action">Print</button>
            <button class="btn-action" onclick="showGroceryList()">Smart Grocery List</button>
        </div>
    </section>

    <section class="recipe-stats">
        <div class="stat-item"><strong>Prep Time:</strong> 20 mins</div>
        <div class="stat-item"><strong>Cook Time:</strong> 40 mins</div>
        <div class="stat-item"><strong>Total Time:</strong> 1 hr</div>
        <div class="stat-item"><strong>Servings:</strong> 4</div>
    </section>

    <hr class="divider">

    <div class="recipe-body">
        <aside class="ingredients-section">
            <h2>Ingredients</h2>
            <ul class="ingredient-list">
                <li><label><input type="checkbox"> 1 lb Spaghetti noodles</label></li>
                <li><label><input type="checkbox"> 1/2 lb Ground beef</label></li>
                <li><label><input type="checkbox"> 1/4 cup Breadcrumbs</label></li>
                <li><label><input type="checkbox"> 1/4 cup Parmesan cheese</label></li>
                <li><label><input type="checkbox"> 2 cups Marinara sauce</label></li>
                <li><label><input type="checkbox"> 1 clove Garlic, minced</label></li>
            </ul>
        </aside>

        <article class="directions-section">
            <h2>Directions</h2>
            <ol class="direction-list">
                <li>
                    <h3>Step 1</h3>
                    <p>In a large bowl, combine ground beef, breadcrumbs, parmesan, and garlic. Form into 12 meatballs.</p>
                </li>
                <li>
                    <h3>Step 2</h3>
                    <p>Heat olive oil in a skillet over medium heat. Brown meatballs on all sides, about 10 minutes.</p>
                </li>
                <li>
                    <h3>Step 3</h3>
                    <p>Add marinara sauce to the skillet and simmer for 15 minutes while you boil the spaghetti in a separate pot.</p>
                </li>
            </ol>
        </article>
    </div>
</main>

</body>
</html>
```
## CSS
