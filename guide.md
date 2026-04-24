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

## db.sql

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

## 

# GROCERY LIST BUILDER

# ADD RECIPES
