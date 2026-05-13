-- Wandering Magnolias — Full Schema + Seed
-- Compatible with MySQL 8+ and MariaDB
-- Run this file to initialize the database from scratch

CREATE DATABASE IF NOT EXISTS wandering_magnolias CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE wandering_magnolias;

SET FOREIGN_KEY_CHECKS = 0;

-- ─── users ────────────────────────────────────
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    user_id       INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    first_name    VARCHAR(80)   NOT NULL,
    last_name     VARCHAR(80)   NOT NULL,
    user_email    VARCHAR(180)  NOT NULL,
    user_password VARCHAR(255)  NOT NULL,
    status        ENUM('active','archived') NOT NULL DEFAULT 'active',
    archived_at   TIMESTAMP     NULL DEFAULT NULL,
    updated_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    UNIQUE KEY (user_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ─── recipes ──────────────────────────────────
DROP TABLE IF EXISTS recipes;
CREATE TABLE recipes (
    recipe_id    INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    user_id      INT UNSIGNED  NULL,
    remixed_from INT UNSIGNED  NULL DEFAULT NULL,
    title        VARCHAR(200)  NOT NULL,
    image_url    VARCHAR(400)  NOT NULL,
    difficulty   ENUM('Easy','Intermediate','Hard') NOT NULL DEFAULT 'Easy',
    is_premade   TINYINT(1)    NOT NULL DEFAULT 0,
    is_deleted   TINYINT(1)    NOT NULL DEFAULT 0,
    deleted_at   TIMESTAMP     NULL DEFAULT NULL,
    created_at   TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (recipe_id),
    KEY (user_id),
    KEY (remixed_from),
    CONSTRAINT recipes_ibfk_1 FOREIGN KEY (user_id)      REFERENCES users   (user_id)   ON DELETE SET NULL,
    CONSTRAINT recipes_ibfk_2 FOREIGN KEY (remixed_from) REFERENCES recipes (recipe_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ─── ingredients ──────────────────────────────
DROP TABLE IF EXISTS ingredients;
CREATE TABLE ingredients (
    ingredient_id INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    recipe_id     INT UNSIGNED  NOT NULL,
    name          VARCHAR(200)  NOT NULL,
    base_quantity DECIMAL(8,2)  NOT NULL DEFAULT 1.00,
    unit          VARCHAR(60)   NOT NULL DEFAULT '',
    PRIMARY KEY (ingredient_id),
    KEY (recipe_id),
    CONSTRAINT ingredients_ibfk_1 FOREIGN KEY (recipe_id) REFERENCES recipes (recipe_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ─── directions ───────────────────────────────
DROP TABLE IF EXISTS directions;
CREATE TABLE directions (
    direction_id INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    recipe_id    INT UNSIGNED  NOT NULL,
    step_number  INT UNSIGNED  NOT NULL,
    instruction  TEXT          NOT NULL,
    PRIMARY KEY (direction_id),
    KEY (recipe_id),
    CONSTRAINT directions_ibfk_1 FOREIGN KEY (recipe_id) REFERENCES recipes (recipe_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ─────────────────────────────────────────────
-- SEED — 12 Premade Recipes
-- ─────────────────────────────────────────────

INSERT INTO recipes (user_id, title, image_url, difficulty, is_premade) VALUES
(NULL, 'Spicy Ground Beef Tacos',        'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=800', 'Easy',         1),
(NULL, 'Classic Spaghetti Carbonara',    'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=800', 'Intermediate', 1),
(NULL, 'Fresh Greek Salad',              'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800', 'Easy',         1),
(NULL, 'Chicken Tikka Masala',           'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=800', 'Hard',         1),
(NULL, 'Avocado Toast with Poached Egg', 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=800', 'Easy',         1),
(NULL, 'Mushroom Risotto',               'https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=800', 'Intermediate', 1),
(NULL, 'Garlic Butter Shrimp Pasta',     'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=800', 'Easy',         1),
(NULL, 'Korean Beef Bulgogi',            'https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=800', 'Intermediate', 1),
(NULL, 'Caprese Salad',                  'https://images.unsplash.com/photo-1592417817098-8fd3d9eb14a5?w=800', 'Easy',         1),
(NULL, 'Butter Chicken',                 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=800', 'Intermediate', 1),
(NULL, 'Classic French Omelette',        'https://images.unsplash.com/photo-1510693206972-df098062cb71?w=800', 'Hard',         1),
(NULL, 'Creamy Tomato Basil Soup',       'https://images.unsplash.com/photo-1547592180-85f173990554?w=800', 'Easy',          1);

-- ─── Ingredients ──────────────────────────────

INSERT INTO ingredients (recipe_id, name, base_quantity, unit) VALUES
-- Tacos (1)
(1,'Ground beef',1.00,'lb'),(1,'Olive oil',2.00,'tbsp'),(1,'Smoked paprika',1.00,'tsp'),
(1,'Cumin',1.00,'tsp'),(1,'Cayenne pepper',0.50,'tsp'),(1,'Flour tortillas',8.00,'pcs'),
(1,'Shredded lettuce',1.00,'cup'),(1,'Diced tomatoes',1.00,'cup'),(1,'Chopped red onion',0.50,'cup'),
(1,'Fresh cilantro',0.25,'cup'),(1,'Avocado',1.00,'pcs'),(1,'Salt & pepper',1.00,'to taste'),
-- Carbonara (2)
(2,'Spaghetti',400.00,'g'),(2,'Pancetta or guanciale',150.00,'g'),(2,'Egg yolks',4.00,'pcs'),
(2,'Whole egg',1.00,'pcs'),(2,'Pecorino Romano',80.00,'g'),(2,'Black pepper',1.00,'tsp'),
(2,'Salt',1.00,'to taste'),
-- Greek Salad (3)
(3,'Romaine lettuce',1.00,'head'),(3,'Cherry tomatoes',200.00,'g'),(3,'Cucumber',1.00,'pcs'),
(3,'Kalamata olives',100.00,'g'),(3,'Feta cheese',150.00,'g'),(3,'Red onion',0.50,'pcs'),
(3,'Olive oil',3.00,'tbsp'),(3,'Red wine vinegar',1.00,'tbsp'),(3,'Dried oregano',1.00,'tsp'),
(3,'Salt & pepper',1.00,'to taste'),
-- Chicken Tikka Masala (4)
(4,'Chicken breast',600.00,'g'),(4,'Plain yogurt',1.00,'cup'),(4,'Garlic',4.00,'cloves'),
(4,'Ginger',1.00,'tbsp'),(4,'Garam masala',2.00,'tsp'),(4,'Turmeric',1.00,'tsp'),
(4,'Canned tomatoes',400.00,'g'),(4,'Heavy cream',0.50,'cup'),(4,'Butter',2.00,'tbsp'),
(4,'Onion',1.00,'pcs'),(4,'Cilantro',0.25,'cup'),
-- Avocado Toast (5)
(5,'Sourdough bread',2.00,'slices'),(5,'Ripe avocado',1.00,'pcs'),(5,'Eggs',2.00,'pcs'),
(5,'Lemon juice',1.00,'tsp'),(5,'Red pepper flakes',0.25,'tsp'),(5,'Salt & pepper',1.00,'to taste'),
(5,'Everything bagel seasoning',1.00,'tsp'),
-- Mushroom Risotto (6)
(6,'Arborio rice',300.00,'g'),(6,'Mixed mushrooms',400.00,'g'),(6,'Shallots',2.00,'pcs'),
(6,'Garlic',3.00,'cloves'),(6,'Dry white wine',0.50,'cup'),(6,'Vegetable stock',1.00,'l'),
(6,'Parmesan',80.00,'g'),(6,'Butter',3.00,'tbsp'),(6,'Olive oil',2.00,'tbsp'),
(6,'Fresh thyme',2.00,'sprigs'),(6,'Salt & pepper',1.00,'to taste'),
-- Garlic Butter Shrimp Pasta (7)
(7,'Spaghetti',300.00,'g'),(7,'Large shrimp',400.00,'g'),(7,'Butter',4.00,'tbsp'),
(7,'Garlic',6.00,'cloves'),(7,'White wine',0.25,'cup'),(7,'Parsley',0.25,'cup'),
(7,'Chili flakes',0.50,'tsp'),(7,'Lemon',1.00,'pcs'),(7,'Salt & pepper',1.00,'to taste'),
-- Korean Beef Bulgogi (8)
(8,'Beef ribeye',500.00,'g'),(8,'Soy sauce',4.00,'tbsp'),(8,'Sesame oil',2.00,'tbsp'),
(8,'Brown sugar',2.00,'tbsp'),(8,'Garlic',4.00,'cloves'),(8,'Ginger',1.00,'tbsp'),
(8,'Asian pear',0.50,'pcs'),(8,'Green onions',3.00,'pcs'),(8,'Sesame seeds',1.00,'tbsp'),
-- Caprese Salad (9)
(9,'Fresh mozzarella',250.00,'g'),(9,'Ripe tomatoes',4.00,'pcs'),(9,'Fresh basil',1.00,'bunch'),
(9,'Extra virgin olive oil',3.00,'tbsp'),(9,'Balsamic glaze',2.00,'tbsp'),
(9,'Flaky sea salt',1.00,'to taste'),(9,'Black pepper',1.00,'to taste'),
-- Butter Chicken (10)
(10,'Chicken thighs',700.00,'g'),(10,'Butter',3.00,'tbsp'),(10,'Onion',1.00,'pcs'),
(10,'Garlic',4.00,'cloves'),(10,'Ginger',1.00,'tbsp'),(10,'Tomato puree',400.00,'g'),
(10,'Heavy cream',0.50,'cup'),(10,'Garam masala',2.00,'tsp'),(10,'Cumin',1.00,'tsp'),
(10,'Kashmiri chili powder',1.00,'tsp'),(10,'Sugar',1.00,'tsp'),(10,'Salt',1.00,'to taste'),
-- Classic French Omelette (11)
(11,'Eggs',3.00,'pcs'),(11,'Butter',1.00,'tbsp'),(11,'Salt',1.00,'pinch'),
(11,'Chives',1.00,'tbsp'),(11,'Water',1.00,'tsp'),
-- Creamy Tomato Basil Soup (12)
(12,'Canned whole tomatoes',800.00,'g'),(12,'Onion',1.00,'pcs'),(12,'Garlic',4.00,'cloves'),
(12,'Vegetable broth',2.00,'cups'),(12,'Heavy cream',0.50,'cup'),(12,'Fresh basil',0.50,'cup'),
(12,'Butter',2.00,'tbsp'),(12,'Sugar',1.00,'tsp'),(12,'Salt & pepper',1.00,'to taste');

-- ─── Directions ───────────────────────────────

INSERT INTO directions (recipe_id, step_number, instruction) VALUES
-- Tacos (1)
(1,1,'Season the ground beef with paprika, cumin, cayenne, salt, and pepper. Mix well until evenly coated.'),
(1,2,'Heat olive oil in a skillet over medium-high heat. Cook the seasoned beef for about 3 minutes per side. Do not burn!'),
(1,3,'While the beef is cooking, warm the tortillas in a dry pan for 30 seconds each side.'),
(1,4,'Assemble the tacos — beef on tortilla, then top with lettuce, tomatoes, red onion, cilantro, and avocado slices.'),
(1,5,'Squeeze lime over each taco and serve immediately while fresh.'),
-- Carbonara (2)
(2,1,'Boil a large pot of heavily salted water. Cook the spaghetti until al dente — check the package for exact timing.'),
(2,2,'Fry the pancetta in a large pan until crispy and golden. Remove from heat.'),
(2,3,'Whisk together the egg yolks, whole egg, and most of the cheese in a bowl. Season generously with black pepper.'),
(2,4,'Add the hot pasta to the pancetta pan. Off heat — do not cook further or the eggs will scramble! Toss rapidly while pouring in the egg mixture.'),
(2,5,'Add a splash of pasta water to create a creamy sauce. Taste, season if needed, then serve immediately.'),
-- Greek Salad (3)
(3,1,'Chop the lettuce, cucumber, and red onion into medium bite-size pieces — not too small, not too big.'),
(3,2,'Halve the cherry tomatoes.'),
(3,3,'Combine everything in a large bowl — lettuce, cucumber, onion, tomatoes, and olives. Toss gently.'),
(3,4,'For the dressing, whisk together olive oil, red wine vinegar, oregano, salt, and pepper. Taste before pouring and adjust as needed.'),
(3,5,'Pour the dressing over the salad and top with crumbled feta. Do not mix after adding the feta — it looks beautiful as is.'),
-- Chicken Tikka Masala (4)
(4,1,'Marinate the chicken in yogurt, garlic, ginger, garam masala, and turmeric for at least 1 hour. Overnight in the fridge is even better.'),
(4,2,'Grill or broil the chicken until charred on the outside and fully cooked inside. Set aside and let it rest for a few minutes.'),
(4,3,'In a large pan, melt the butter and saute the onion until golden brown. This is the flavor base — do not rush it.'),
(4,4,'Add garlic and ginger, cook for 1 minute. Pour in the canned tomatoes and simmer for 10 minutes. Stir in the cream.'),
(4,5,'Slice the grilled chicken and add it to the sauce. Simmer for another 5 minutes so the flavors absorb. Garnish with cilantro and serve with rice.'),
-- Avocado Toast (5)
(5,1,'Toast the sourdough until golden and crispy. Thickness is personal preference — go with what you like.'),
(5,2,'Mash the avocado with lemon juice, salt, and pepper. Do not over-mash — a few small chunks add texture.'),
(5,3,'To poach the egg, simmer water with a splash of vinegar. Swirl the water and gently drop in the egg. About 3 minutes for a runny yolk. Takes practice!'),
(5,4,'Spread the avocado on the toast and carefully place the poached egg on top without bursting the yolk.'),
(5,5,'Sprinkle with red pepper flakes and everything bagel seasoning. Serve immediately while still hot.'),
-- Mushroom Risotto (6)
(6,1,'Heat the stock in a separate saucepan and keep it warm throughout. Cold stock will stop the cooking process.'),
(6,2,'Saute the shallots and garlic in olive oil until soft and translucent. Add the mushrooms and thyme, cook until golden. Do not rush this step — takes about 8 to 10 minutes.'),
(6,3,'Add the arborio rice and toast for 1 minute — you will hear a slight crackling sound. Pour in the wine and stir until fully absorbed.'),
(6,4,'Add warm stock one ladle at a time, stirring continuously. Wait until each ladle is absorbed before adding the next. Takes about 18 minutes total — patience pays off.'),
(6,5,'Remove from heat and stir in butter and parmesan until creamy. Season well and serve immediately — risotto does not like to wait.'),
-- Garlic Butter Shrimp Pasta (7)
(7,1,'Boil the pasta in heavily salted water until al dente. Save 1 cup of pasta water before draining.'),
(7,2,'Melt 2 tablespoons of butter in a large pan. Cook the shrimp for 1 to 2 minutes each side until pink. Set aside.'),
(7,3,'In the same pan, add the remaining butter and saute the garlic until fragrant — about 1 minute. Do not let it brown.'),
(7,4,'Deglaze with white wine and let it reduce by half. Add the pasta water, then toss in the drained pasta.'),
(7,5,'Add the shrimp back in, squeeze over some lemon, and add chili flakes and parsley. Toss everything together and serve immediately.'),
-- Korean Beef Bulgogi (8)
(8,1,'Slice the beef as thin as possible — easier if it is partially frozen. This is important for tenderness.'),
(8,2,'Blend together the soy sauce, sesame oil, sugar, garlic, ginger, and Asian pear for the marinade. The pear is the secret to tenderness.'),
(8,3,'Marinate the beef for at least 30 minutes. Overnight in the fridge is ideal if you have the time.'),
(8,4,'Cook in a very hot pan or grill in small batches. Do not overcrowd the pan or the meat will steam instead of sear.'),
(8,5,'Serve over steamed rice and top with green onions and sesame seeds. Goes great with kimchi on the side.'),
-- Caprese Salad (9)
(9,1,'Slice the mozzarella and tomatoes into even rounds, about 1cm thick. Keep the sizes consistent for a clean presentation.'),
(9,2,'Arrange alternating slices of tomato and mozzarella on a plate, overlapping slightly like a fan.'),
(9,3,'Tuck fresh basil leaves in between each slice.'),
(9,4,'Drizzle generously with olive oil and then balsamic glaze.'),
(9,5,'Season with flaky sea salt and cracked pepper. Serve immediately — simple but incredible when the ingredients are good.'),
-- Butter Chicken (10)
(10,1,'Season the chicken with salt, cumin, and half the garam masala. Cook in butter until browned on all sides. Set aside.'),
(10,2,'In the same pan, saute the onion until very soft and slightly caramelized. This builds the flavor base.'),
(10,3,'Add garlic and ginger, cook for 1 minute. Add the chili powder and remaining garam masala and toast the spices briefly.'),
(10,4,'Pour in the tomato puree and simmer for 15 minutes until thick and deep red. The kitchen will smell amazing at this point.'),
(10,5,'Stir in the cream and sugar. Return the chicken to the pan and simmer for 10 more minutes. Serve with naan or rice.'),
-- Classic French Omelette (11)
(11,1,'Whisk the eggs with salt and water until fully combined. About 20 strokes — do not over-whisk.'),
(11,2,'Heat the butter in a non-stick pan over medium heat. Wait until the foaming subsides but before it starts to brown.'),
(11,3,'Pour in the eggs. Immediately shake the pan while stirring with a fork in small circular motions. Work quickly.'),
(11,4,'When barely set but still slightly wet on top, stop stirring. Tilt the pan and roll the omelette forward.'),
(11,5,'Slide onto a plate seam-side down. It should be pale yellow with no browning. Difficult to master but worth the effort.'),
-- Creamy Tomato Basil Soup (12)
(12,1,'Saute the onion in butter until soft and translucent. Add the garlic and cook for another minute.'),
(12,2,'Add the canned tomatoes and broth. Crush the tomatoes with a spoon and simmer for 20 minutes.'),
(12,3,'Add the fresh basil and sugar. Blend until smooth using an immersion blender. Be careful with the hot liquid.'),
(12,4,'Return to heat and stir in the cream. Season with salt and pepper. Simmer for 5 more minutes.'),
(12,5,'Serve hot with crusty bread. Perfect for a rainy day. Can also be topped with croutons.');