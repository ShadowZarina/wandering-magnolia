<?php

require_once ROOT . '/app/middleware/AuthMiddleware.php';
require_once ROOT . '/app/models/RecipeModel.php';
require_once ROOT . '/app/models/UserModel.php';

class AccountController {

    public function index(): void {
        AuthMiddleware::require();

        $model       = new RecipeModel();
        $page        = max(1, (int) ($_GET['page']   ?? 1));
        $search      = trim($_GET['search'] ?? '');
        $userId      = (int) $_SESSION['user_id'];

        // Purge expired trash on account load
        $model->purgeExpiredTrash();

        $data        = $model->getByUserPaginated($userId, $page, $search);
        $recipes     = $data['recipes'];
        $totalPages  = $data['total_pages'];
        $total       = $data['total'];
        $currentPage = $data['current_page'];

        // Trash count for badge
        $trashed     = $model->getTrashedByUser($userId);
        $trashCount  = count($trashed);

        require ROOT . '/app/views/account/index.php';
    }

    public function trash(): void {
        AuthMiddleware::require();

        $model   = new RecipeModel();
        $userId  = (int) $_SESSION['user_id'];
        $recipes = $model->getTrashedByUser($userId);

        require ROOT . '/app/views/account/trash.php';
    }

    public function settings(): void {
        AuthMiddleware::require();

        $userModel = new UserModel();
        $user      = $userModel->findById((int) $_SESSION['user_id']);

        $error   = $_SESSION['settings_error']   ?? null;
        $success = $_SESSION['settings_success'] ?? null;
        unset($_SESSION['settings_error'], $_SESSION['settings_success']);

        require ROOT . '/app/views/account/settings.php';
    }

    public function updateProfile(): void {
        AuthMiddleware::require();
        require_once ROOT . '/app/handlers/AccountHandler.php';
        AccountHandler::handleUpdateProfile();
    }

    public function changePassword(): void {
        AuthMiddleware::require();
        require_once ROOT . '/app/handlers/AccountHandler.php';
        AccountHandler::handleChangePassword();
    }

    public function archive(): void {
        AuthMiddleware::require();
        require_once ROOT . '/app/handlers/AccountHandler.php';
        AccountHandler::handleArchive();
    }

    public function archived(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['archived_user_id'])) {
            header('Location: /login'); exit;
        }

        $userModel = new UserModel();
        $user      = $userModel->findById((int) $_SESSION['archived_user_id']);

        if (!$user || $user['status'] !== 'archived') {
            header('Location: /login'); exit;
        }

        $archivedAt = new DateTime($user['archived_at']);
        $expiresAt  = (clone $archivedAt)->modify('+30 days');
        $now        = new DateTime();
        $daysLeft   = max(0, (int) $now->diff($expiresAt)->days);

        require ROOT . '/app/views/account/archived.php';
    }

    public function restoreAccount(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        require_once ROOT . '/app/handlers/AccountHandler.php';
        AccountHandler::handleRestore();
    }

    public function edit(): void {
        AuthMiddleware::require();
        $id     = (int) ($_GET['id'] ?? 0);
        $model  = new RecipeModel();
        $recipe = $model->getById($id);

        if (!$recipe || (int)$recipe['user_id'] !== (int)$_SESSION['user_id']) {
            http_response_code(403); echo '<h1>Unauthorized</h1>'; return;
        }

        $ingredients = $model->getIngredients($id);
        $directions  = $model->getDirections($id);
        $error       = $_SESSION['form_error'] ?? null;
        unset($_SESSION['form_error']);

        require ROOT . '/app/views/account/edit.php';
    }

    public function update(): void {
        AuthMiddleware::require();
        require_once ROOT . '/app/handlers/RecipeHandler.php';
        RecipeHandler::handleUpdate();
    }

    // Soft delete — sends to trash
    public function delete(): void {
        AuthMiddleware::require();
        $id     = (int) ($_POST['recipe_id'] ?? 0);
        $model  = new RecipeModel();
        $recipe = $model->getById($id);

        if (!$recipe || (int)$recipe['user_id'] !== (int)$_SESSION['user_id']) {
            http_response_code(403); echo '<h1>Unauthorized</h1>'; return;
        }

        $model->softDelete($id);
        header('Location: /account');
        exit;
    }

    // Restore from trash
    public function restoreRecipe(): void {
        AuthMiddleware::require();
        $id     = (int) ($_POST['recipe_id'] ?? 0);
        $model  = new RecipeModel();
        $recipe = $model->getById($id);

        if (!$recipe || (int)$recipe['user_id'] !== (int)$_SESSION['user_id']) {
            http_response_code(403); echo '<h1>Unauthorized</h1>'; return;
        }

        $model->restore($id);
        header('Location: /account/trash');
        exit;
    }

    // Permanently delete from trash
    public function permanentDelete(): void {
        AuthMiddleware::require();
        $id     = (int) ($_POST['recipe_id'] ?? 0);
        $model  = new RecipeModel();
        $recipe = $model->getById($id);

        if (!$recipe || (int)$recipe['user_id'] !== (int)$_SESSION['user_id']) {
            http_response_code(403); echo '<h1>Unauthorized</h1>'; return;
        }

        $model->delete($id);
        header('Location: /account/trash');
        exit;
    }
}