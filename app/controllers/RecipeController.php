<?php

require_once ROOT . '/app/middleware/AuthMiddleware.php';
require_once ROOT . '/app/models/RecipeModel.php';

class RecipeController {

    public function index(): void {
        AuthMiddleware::require();
        $model   = new RecipeModel();
        $recipes = $model->getAll();
        require ROOT . '/app/views/recipes/index.php';
    }

    public function show(): void {
        AuthMiddleware::require();
        $id = (int) ($_GET['id'] ?? 0);
        $model  = new RecipeModel();
        $recipe = $model->getById($id);
        if (!$recipe) { http_response_code(404); echo '<h1>Recipe not found</h1>'; return; }
        $ingredients = $model->getIngredients($id);
        $directions  = $model->getDirections($id);
        require ROOT . '/app/views/recipes/show.php';
    }

    public function add(): void {
        AuthMiddleware::require();
        $error = $_SESSION['form_error'] ?? null;
        unset($_SESSION['form_error']);
        require ROOT . '/app/views/recipes/add.php';
    }

    public function store(): void {
        AuthMiddleware::require();
        require_once ROOT . '/app/handlers/RecipeHandler.php';
        RecipeHandler::handleCreate();
    }

    public function grocery(): void {
        AuthMiddleware::require();
        $id = (int) ($_GET['id'] ?? 0);
        $model  = new RecipeModel();
        $recipe = $model->getById($id);
        if (!$recipe) { http_response_code(404); echo '<h1>Recipe not found</h1>'; return; }
        $ingredients = $model->getIngredients($id);
        require ROOT . '/app/views/grocery/list.php';
    }
}
