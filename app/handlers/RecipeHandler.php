<?php

class RecipeHandler {

    public static function handleCreate(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $title      = trim($_POST['title']      ?? '');
        $difficulty = trim($_POST['difficulty'] ?? 'Easy');
        $ingNames   = $_POST['ing_name']  ?? [];
        $ingQtys    = $_POST['ing_qty']   ?? [];
        $ingUnits   = $_POST['ing_unit']  ?? [];
        $steps      = $_POST['direction'] ?? [];

        if (!$title) {
            $_SESSION['form_error'] = 'Recipe title is required.';
            header('Location: /add-recipe'); exit;
        }

        $imageUrl = '/assets/images/default-recipe.jpg';
        if (!empty($_FILES['image']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];
            if (!in_array($ext, $allowed)) {
                $_SESSION['form_error'] = 'Invalid image type.';
                header('Location: /add-recipe'); exit;
            }
            $filename = uniqid('recipe_', true) . '.' . $ext;
            $dest     = ROOT . '/public/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imageUrl = '/uploads/' . $filename;
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

    public static function handleUpdate(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $recipeId   = (int) ($_POST['recipe_id'] ?? 0);
        $title      = trim($_POST['title']       ?? '');
        $difficulty = trim($_POST['difficulty']  ?? 'Easy');
        $ingNames   = $_POST['ing_name']  ?? [];
        $ingQtys    = $_POST['ing_qty']   ?? [];
        $ingUnits   = $_POST['ing_unit']  ?? [];
        $steps      = $_POST['direction'] ?? [];

        if (!$title || !$recipeId) {
            $_SESSION['form_error'] = 'Recipe title is required.';
            header('Location: /edit-recipe?id=' . $recipeId); exit;
        }

        $model  = new RecipeModel();
        $recipe = $model->getById($recipeId);

        if (!$recipe || (int)$recipe['user_id'] !== (int)$_SESSION['user_id']) {
            http_response_code(403); echo '<h1>Unauthorized</h1>'; exit;
        }

        $imageUrl = null;
        if (!empty($_FILES['image']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];
            if (!in_array($ext, $allowed)) {
                $_SESSION['form_error'] = 'Invalid image type.';
                header('Location: /edit-recipe?id=' . $recipeId); exit;
            }
            $filename = uniqid('recipe_', true) . '.' . $ext;
            $dest     = ROOT . '/public/uploads/' . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imageUrl = '/uploads/' . $filename;
            }
        }

        $model->update($recipeId, $title, $difficulty, $imageUrl);

        $model->clearIngredients($recipeId);
        foreach ($ingNames as $i => $name) {
            $name = trim($name);
            if ($name === '') continue;
            $model->addIngredient($recipeId, $name, (float)($ingQtys[$i] ?? 1), trim($ingUnits[$i] ?? ''));
        }

        $model->clearDirections($recipeId);
        $stepNum = 1;
        foreach ($steps as $instruction) {
            $instruction = trim($instruction);
            if ($instruction === '') continue;
            $model->addDirection($recipeId, $stepNum++, $instruction);
        }

        header('Location: /account');
        exit;
    }

    public static function handleRemix(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $originalId = (int) ($_POST['original_id'] ?? 0);
        $title      = trim($_POST['title']          ?? '');
        $difficulty = trim($_POST['difficulty']     ?? 'Easy');
        $ingNames   = $_POST['ing_name']  ?? [];
        $ingQtys    = $_POST['ing_qty']   ?? [];
        $ingUnits   = $_POST['ing_unit']  ?? [];
        $steps      = $_POST['direction'] ?? [];

        if (!$title || !$originalId) {
            $_SESSION['form_error'] = 'Recipe title is required.';
            header('Location: /remix-recipe?id=' . $originalId); exit;
        }

        $model    = new RecipeModel();
        $original = $model->getById($originalId);

        if (!$original) {
            http_response_code(404); echo '<h1>Original recipe not found</h1>'; exit;
        }

        // Use original image unless a new one is uploaded
        $imageUrl = $original['image_url'];
        if (!empty($_FILES['image']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp','gif'];
            if (in_array($ext, $allowed)) {
                $filename = uniqid('recipe_', true) . '.' . $ext;
                $dest     = ROOT . '/public/uploads/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $imageUrl = '/uploads/' . $filename;
                }
            }
        }

        $recipeId = $model->create(
            (int)$_SESSION['user_id'],
            $title,
            $imageUrl,
            $difficulty,
            $originalId   // remixed_from
        );

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