<?php

require_once ROOT . '/app/middleware/AuthMiddleware.php';
require_once ROOT . '/app/models/RecipeModel.php';

class RecipeController {

    public function index(): void {
        AuthMiddleware::require();
        $model      = new RecipeModel();
        $page       = max(1, (int) ($_GET['page']       ?? 1));
        $search     = trim($_GET['search']     ?? '');
        $difficulty = trim($_GET['difficulty'] ?? '');

        $allowed = ['', 'Easy', 'Intermediate', 'Hard'];
        if (!in_array($difficulty, $allowed)) $difficulty = '';

        $data = $model->getPaginated($page, $search, $difficulty);

        $recipes     = $data['recipes'];
        $totalPages  = $data['total_pages'];
        $total       = $data['total'];
        $currentPage = $data['current_page'];

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

    public function remix(): void {
        AuthMiddleware::require();
        $id = (int) ($_GET['id'] ?? 0);
        $model    = new RecipeModel();
        $original = $model->getById($id);

        if (!$original) { http_response_code(404); echo '<h1>Recipe not found</h1>'; return; }

        if ((int)$original['user_id'] === (int)$_SESSION['user_id']) {
            header('Location: /recipe?id=' . $id);
            exit;
        }

        $ingredients = $model->getIngredients($id);
        $directions  = $model->getDirections($id);
        $error       = $_SESSION['form_error'] ?? null;
        unset($_SESSION['form_error']);

        require ROOT . '/app/views/recipes/remix.php';
    }

    public function storeRemix(): void {
        AuthMiddleware::require();
        require_once ROOT . '/app/handlers/RecipeHandler.php';
        RecipeHandler::handleRemix();
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