<?php

require_once ROOT . '/app/middleware/AuthMiddleware.php';
require_once ROOT . '/app/models/UserModel.php';

class AuthController {

    public function showLogin(): void {
        AuthMiddleware::guest();
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);
        require ROOT . '/app/views/auth/login.php';
    }

    public function showRegister(): void {
        AuthMiddleware::guest();
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);
        require ROOT . '/app/views/auth/register.php';
    }

    public function login(): void {
        AuthMiddleware::guest();
        require_once ROOT . '/app/handlers/AuthHandler.php';
        AuthHandler::handleLogin();
    }

    public function register(): void {
        AuthMiddleware::guest();
        require_once ROOT . '/app/handlers/AuthHandler.php';
        AuthHandler::handleRegister();
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: /login');
        exit;
    }
}
