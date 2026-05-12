-- Wandering Magnolias — Database Schema
-- Run this file to initialize the database

USE wandering_magnolias;

-- ─────────────────────────────────────────────
-- USERS
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    user_id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name  VARCHAR(80)  NOT NULL,
    last_name   VARCHAR(80)  NOT NULL,
    user_email  VARCHAR(180) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ─────────────────────────────────────────────
-- RECIPES
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS recipes (
    recipe_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NULL,
    title       VARCHAR(200) NOT NULL,
    image_url   VARCHAR(400) NOT NULL,
    difficulty  ENUM('Easy','Intermediate','Hard') NOT NULL DEFAULT 'Easy',
    is_premade  TINYINT(1) NOT NULL DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- ─────────────────────────────────────────────
-- INGREDIENTS
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS ingredients (
    ingredient_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id      INT UNSIGNED NOT NULL,
    name           VARCHAR(200) NOT NULL,
    base_quantity  DECIMAL(8,2) NOT NULL DEFAULT 1,
    unit           VARCHAR(60)  NOT NULL DEFAULT '',
    FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE
);

-- ─────────────────────────────────────────────
-- DIRECTIONS
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS directions (
    direction_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recipe_id    INT UNSIGNED NOT NULL,
    step_number  INT UNSIGNED NOT NULL,
    instruction  TEXT NOT NULL,
    FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE
);

-- ─────────────────────────────────────────────
-- SEED — 6 Premade Recipes
-- ─────────────────────────────────────────────
INSERT INTO recipes (user_id, title, image_url, difficulty, is_premade) VALUES
(NULL, 'Spicy Ground Beef Tacos',        'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800', 'Easy',         1),
(NULL, 'Classic Spaghetti Carbonara',    'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=800', 'Intermediate', 1),
(NULL, 'Fresh Greek Salad',              'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800', 'Easy',         1),
(NULL, 'Chicken Tikka Masala',           'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=800', 'Hard',         1),
(NULL, 'Avocado Toast with Poached Egg', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=800', 'Easy',         1),
(NULL, 'Mushroom Risotto',               'https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=800', 'Intermediate', 1);

-- ── Tacos (recipe_id = 1) ──
INSERT INTO ingredients (recipe_id, name, base_quantity, unit) VALUES
(1,'Ground beef',1,'lb'),(1,'Olive oil',2,'tbsp'),(1,'Smoked paprika',1,'tsp'),
(1,'Cumin',1,'tsp'),(1,'Cayenne pepper',0.5,'tsp'),(1,'Flour tortillas',8,'pcs'),
(1,'Shredded lettuce',1,'cup'),(1,'Diced tomatoes',1,'cup'),(1,'Chopped red onion',0.5,'cup'),
(1,'Fresh cilantro',0.25,'cup'),(1,'Avocado',1,'pcs'),(1,'Salt & pepper',1,'to taste');

INSERT INTO directions (recipe_id, step_number, instruction) VALUES
(1,1,'In a bowl, toss the ground beef with smoked paprika, cumin, cayenne, salt, and pepper.'),
(1,2,'Heat olive oil in a skillet over medium-high heat. Cook seasoned beef for 2–3 minutes per side until opaque.'),
(1,3,'Warm the tortillas in a dry pan for 30 seconds each side.'),
(1,4,'Place beef on each tortilla, top with lettuce, tomatoes, red onion, cilantro, and avocado slices.'),
(1,5,'Squeeze lime over each taco. Serve immediately.');

-- ── Carbonara (recipe_id = 2) ──
INSERT INTO ingredients (recipe_id, name, base_quantity, unit) VALUES
(2,'Spaghetti',400,'g'),(2,'Pancetta or guanciale',150,'g'),(2,'Egg yolks',4,'pcs'),
(2,'Whole egg',1,'pcs'),(2,'Pecorino Romano',80,'g'),(2,'Black pepper',1,'tsp'),
(2,'Salt',1,'to taste');

INSERT INTO directions (recipe_id, step_number, instruction) VALUES
(2,1,'Boil a large pot of salted water and cook spaghetti until al dente.'),
(2,2,'Fry pancetta in a large pan over medium heat until crispy. Remove from heat.'),
(2,3,'Whisk egg yolks, whole egg, and most of the cheese together in a bowl.'),
(2,4,'Add hot pasta to the pancetta pan. Off heat, pour in the egg mixture, tossing rapidly.'),
(2,5,'Add pasta water as needed to create a creamy sauce. Season with pepper and serve.');

-- ── Greek Salad (recipe_id = 3) ──
INSERT INTO ingredients (recipe_id, name, base_quantity, unit) VALUES
(3,'Romaine lettuce',1,'head'),(3,'Cherry tomatoes',200,'g'),(3,'Cucumber',1,'pcs'),
(3,'Kalamata olives',100,'g'),(3,'Feta cheese',150,'g'),(3,'Red onion',0.5,'pcs'),
(3,'Olive oil',3,'tbsp'),(3,'Red wine vinegar',1,'tbsp'),(3,'Dried oregano',1,'tsp'),
(3,'Salt & pepper',1,'to taste');

INSERT INTO directions (recipe_id, step_number, instruction) VALUES
(3,1,'Chop lettuce, cucumber, and red onion into bite-size pieces.'),
(3,2,'Halve the cherry tomatoes.'),
(3,3,'Combine all vegetables and olives in a large bowl.'),
(3,4,'Whisk olive oil, vinegar, oregano, salt, and pepper for the dressing.'),
(3,5,'Pour dressing over the salad, top with crumbled feta, and serve.');

-- ── Chicken Tikka Masala (recipe_id = 4) ──
INSERT INTO ingredients (recipe_id, name, base_quantity, unit) VALUES
(4,'Chicken breast',600,'g'),(4,'Plain yogurt',1,'cup'),(4,'Garlic',4,'cloves'),
(4,'Ginger',1,'tbsp'),(4,'Garam masala',2,'tsp'),(4,'Turmeric',1,'tsp'),
(4,'Canned tomatoes',400,'g'),(4,'Heavy cream',0.5,'cup'),(4,'Butter',2,'tbsp'),
(4,'Onion',1,'pcs'),(4,'Cilantro',0.25,'cup');

INSERT INTO directions (recipe_id, step_number, instruction) VALUES
(4,1,'Marinate chicken in yogurt, garlic, ginger, garam masala, and turmeric for at least 1 hour.'),
(4,2,'Grill or broil chicken until charred. Set aside.'),
(4,3,'Melt butter in a pan. Sauté onion until golden. Add garlic and ginger.'),
(4,4,'Add canned tomatoes and simmer 10 minutes. Stir in cream.'),
(4,5,'Add grilled chicken to the sauce. Simmer 5 minutes. Garnish with cilantro and serve with rice.');

-- ── Avocado Toast (recipe_id = 5) ──
INSERT INTO ingredients (recipe_id, name, base_quantity, unit) VALUES
(5,'Sourdough bread',2,'slices'),(5,'Ripe avocado',1,'pcs'),(5,'Eggs',2,'pcs'),
(5,'Lemon juice',1,'tsp'),(5,'Red pepper flakes',0.25,'tsp'),(5,'Salt & pepper',1,'to taste'),
(5,'Everything bagel seasoning',1,'tsp');

INSERT INTO directions (recipe_id, step_number, instruction) VALUES
(5,1,'Toast the sourdough slices until golden and crispy.'),
(5,2,'Mash avocado with lemon juice, salt, and pepper.'),
(5,3,'Poach eggs in simmering water with a splash of vinegar for 3 minutes.'),
(5,4,'Spread avocado on toast. Top with poached egg.'),
(5,5,'Sprinkle red pepper flakes and everything bagel seasoning. Serve immediately.');

-- ── Mushroom Risotto (recipe_id = 6) ──
INSERT INTO ingredients (recipe_id, name, base_quantity, unit) VALUES
(6,'Arborio rice',300,'g'),(6,'Mixed mushrooms',400,'g'),(6,'Shallots',2,'pcs'),
(6,'Garlic',3,'cloves'),(6,'Dry white wine',0.5,'cup'),(6,'Vegetable stock',1,'l'),
(6,'Parmesan',80,'g'),(6,'Butter',3,'tbsp'),(6,'Olive oil',2,'tbsp'),
(6,'Fresh thyme',2,'sprigs'),(6,'Salt & pepper',1,'to taste');

INSERT INTO directions (recipe_id, step_number, instruction) VALUES
(6,1,'Heat stock in a separate saucepan and keep warm.'),
(6,2,'Sauté shallots and garlic in olive oil until soft. Add mushrooms and thyme; cook until golden.'),
(6,3,'Add rice and toast for 1 minute. Pour in wine and stir until absorbed.'),
(6,4,'Add warm stock one ladle at a time, stirring continuously until each ladle is absorbed (~18 min).'),
(6,5,'Remove from heat. Stir in butter and parmesan. Season and serve immediately.');
