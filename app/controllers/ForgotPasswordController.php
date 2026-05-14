<?php
// app/controllers/ForgotPasswordController.php

require_once ROOT . '/app/models/UserModel.php';
require_once ROOT . '/app/models/PasswordResetModel.php';

class ForgotPasswordController {

    public function showRequest(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $error = $_SESSION['fp_error'] ?? null;
        unset($_SESSION['fp_error']);
        require ROOT . '/app/views/auth/forgot-password.php';
    }

    public function sendOtp(): void {
        require_once ROOT . '/app/handlers/ForgotPasswordHandler.php';
        ForgotPasswordHandler::handleRequest();
    }

    public function showVerify(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['fp_email'])) {
            header('Location: /forgot-password'); exit;
        }
        $email = $_SESSION['fp_email'];
        $error = $_SESSION['fp_error'] ?? null;
        unset($_SESSION['fp_error']);
        require ROOT . '/app/views/auth/verify-otp.php';
    }

    public function verifyOtp(): void {
        require_once ROOT . '/app/handlers/ForgotPasswordHandler.php';
        ForgotPasswordHandler::handleVerify();
    }

    public function showReset(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['fp_email']) || empty($_SESSION['fp_verified'])) {
            header('Location: /forgot-password'); exit;
        }
        $error = $_SESSION['fp_error'] ?? null;
        unset($_SESSION['fp_error']);
        require ROOT . '/app/views/auth/reset-password.php';
    }

    public function resetPassword(): void {
        require_once ROOT . '/app/handlers/ForgotPasswordHandler.php';
        ForgotPasswordHandler::handleReset();
    }
}