<?php

class AccountHandler {

    public static function handleUpdateProfile(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId    = (int) $_SESSION['user_id'];
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name']  ?? '');
        $email     = trim($_POST['email']      ?? '');

        if (!$firstName || !$lastName || !$email) {
            $_SESSION['settings_error'] = 'All fields are required.';
            header('Location: /account/settings'); exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['settings_error'] = 'Enter a valid email address.';
            header('Location: /account/settings'); exit;
        }

        $model = new UserModel();

        // Check email not taken by another user
        $existing = $model->findByEmail($email);
        if ($existing && (int)$existing['user_id'] !== $userId) {
            $_SESSION['settings_error'] = 'That email is already in use.';
            header('Location: /account/settings'); exit;
        }

        $model->updateProfile($userId, $firstName, $lastName, $email);

        // Update session
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name']  = $lastName;
        $_SESSION['email']      = $email;

        $_SESSION['settings_success'] = 'Profile updated successfully.';
        header('Location: /account/settings'); exit;
    }

    public static function handleChangePassword(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId      = (int) $_SESSION['user_id'];
        $current     = trim($_POST['current_password'] ?? '');
        $newPass     = trim($_POST['new_password']     ?? '');
        $confirm     = trim($_POST['confirm_password'] ?? '');

        if (!$current || !$newPass || !$confirm) {
            $_SESSION['settings_error'] = 'All password fields are required.';
            header('Location: /account/settings'); exit;
        }
        if (strlen($newPass) < 6) {
            $_SESSION['settings_error'] = 'New password must be at least 6 characters.';
            header('Location: /account/settings'); exit;
        }
        if ($newPass !== $confirm) {
            $_SESSION['settings_error'] = 'New passwords do not match.';
            header('Location: /account/settings'); exit;
        }

        $model = new UserModel();
        $user  = $model->findById($userId);

        if (!$user || !password_verify($current, $user['user_password'])) {
            $_SESSION['settings_error'] = 'Current password is incorrect.';
            header('Location: /account/settings'); exit;
        }

        $model->updatePassword($userId, $newPass);
        $_SESSION['settings_success'] = 'Password changed successfully.';
        header('Location: /account/settings'); exit;
    }

    public static function handleArchive(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId   = (int) $_SESSION['user_id'];
        $confirm  = trim($_POST['confirm_archive'] ?? '');

        if ($confirm !== 'DELETE') {
            $_SESSION['settings_error'] = 'Please type DELETE to confirm.';
            header('Location: /account/settings'); exit;
        }

        $model = new UserModel();
        $model->archive($userId);

        // Log out but store archive token in session for restore window
        $_SESSION['archived_user_id'] = $userId;
        session_destroy();

        session_start();
        $_SESSION['archived_user_id'] = $userId;

        header('Location: /account/archived'); exit;
    }

    public static function handleRestore(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId = (int) ($_SESSION['archived_user_id'] ?? 0);
        if (!$userId) {
            header('Location: /login'); exit;
        }

        $model = new UserModel();
        $user  = $model->findById($userId);

        if (!$user || $user['status'] !== 'archived') {
            header('Location: /login'); exit;
        }

        $model->restore($userId);

        // Log back in
        $_SESSION['user_id']    = $user['user_id'];
        $_SESSION['first_name'] = $user['first_name'];
        unset($_SESSION['archived_user_id']);

        header('Location: /account'); exit;
    }
}