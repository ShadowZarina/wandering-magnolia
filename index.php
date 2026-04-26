<div class="admin-controls">
    <a href="add-recipe.php" class="btn-add-recipe">
        <span class="icon">+</span> Add Own Recipe
    </a>
</div>

<style>
.btn-add-recipe {
    display: inline-flex;
    align-items: center;
    background-color: #f27013; /* Allrecipes orange */
    color: white;
    padding: 12px 24px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.2s;
}

.btn-add-recipe:hover {
    background-color: #d96311;
}

.icon {
    margin-right: 8px;
    font-size: 1.2rem;
}
</style>

// ADD RECIPE FORM PAGE

<?php
include 'db.php'; // Ensure your database connection file is included

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize inputs
    $title = $conn->real_escape_string($_POST['title']);
    $prep_time = $conn->real_escape_string($_POST['prep_time']);
    $category = $conn->real_escape_string($_POST['category']);
    $ingredients = $conn->real_escape_string($_POST['ingredients']);
    $directions = $conn->real_escape_string($_POST['directions']);

    $sql = "INSERT INTO recipes (title, prep_time, category, ingredients, directions) 
            VALUES ('$title', '$prep_time', '$category', '$ingredients', '$directions')";

    if ($conn->query($sql) === TRUE) {
        $message = "<p style='color: green;'>Recipe added successfully! <a href='index.php'>View Gallery</a></p>";
    } else {
        $message = "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Recipe</title>
    <style>
        .form-container { max-width: 600px; margin: 40px auto; font-family: sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 120px; resize: vertical; }
        .submit-btn { background: #f27013; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 1rem; }
        .submit-btn:hover { background: #d96311; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Your Own Recipe</h2>
    <?php echo $message; ?>
    
    <form action="add-recipe.php" method="POST">
        <div class="form-group">
            <label for="title">Recipe Title</label>
            <input type="text" id="title" name="title" placeholder="e.g. Grandma's Apple Pie" required>
        </div>

        <div class="form-group">
            <label for="prep_time">Prep Time</label>
            <input type="text" id="prep_time" name="prep_time" placeholder="e.g. 30 mins">
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" placeholder="e.g. Dessert, Breakfast">
        </div>

        <div class="form-group">
            <label for="ingredients">Ingredients (Separate with commas for the Grocery List)</label>
            <textarea id="ingredients" name="ingredients" placeholder="Flour, Sugar, Eggs, Milk..." required></textarea>
        </div>

        <div class="form-group">
            <label for="directions">Directions</label>
            <textarea id="directions" name="directions" placeholder="1. Preheat oven... 2. Mix ingredients..." required></textarea>
        </div>

        <button type="submit" class="submit-btn">Save Recipe to Database</button>
    </form>
</div>

</body>
</html>
