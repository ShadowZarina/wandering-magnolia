<?php

class RecipeHandler {

    public static function handleCreate(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $title      = trim($_POST['title']      ?? '');
        $difficulty = trim($_POST['difficulty'] ?? 'Easy');

        // Ingredients come as parallel arrays
        $ingNames = $_POST['ing_name']  ?? [];
        $ingQtys  = $_POST['ing_qty']   ?? [];
        $ingUnits = $_POST['ing_unit']  ?? [];

        // Directions as array
        $steps = $_POST['direction'] ?? [];

        if (!$title) {
            $_SESSION['form_error'] = 'Recipe title is required.';
            header('Location: /add-recipe'); exit;
        }

        // Handle image upload
        $imageUrl = '/assets/images/default-recipe.jpg';
        if (!empty($_FILES['image']['name'])) {
            $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg','jpeg','png','webp','gif'];
            if (!in_array($ext, $allowed)) {
                $_SESSION['form_error'] = 'Invalid image type.';
                header('Location: /add-recipe'); exit;
            }
            $filename = uniqid('recipe_', true) . '.' . $ext;
            $dest     = ROOT . '/storage/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imageUrl = '/storage/uploads/' . $filename;
            }
        }

        $model    = new RecipeModel();
        $recipeId = $model->create((int)$_SESSION['user_id'], $title, $imageUrl, $difficulty);

        foreach ($ingNames as $i => $name) {
            $name = trim($name);
            if ($name === '') continue;
            $model->addIngredient($recipeId, $name, (float)($ingQtys[$i] ?? 1), trim($ingUnits[$i] ?? ''));
        }

        $stepNum = 1;
        foreach ($steps as $instruction) {
            $instruction = trim($instruction);
            if ($instruction === '') continue;
            $model->addDirection($recipeId, $stepNum++, $instruction);
        }

        header('Location: /recipe?id=' . $recipeId);
        exit;
    }
}
