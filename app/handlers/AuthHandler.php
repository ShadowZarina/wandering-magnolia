<?php

class AuthHandler {

    public static function handleLogin(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$email || !$password) {
            $_SESSION['auth_error'] = 'Please fill in all fields.';
            header('Location: /login'); exit;
        }

        $model = new UserModel();
        $user  = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user['user_password'])) {
            $_SESSION['auth_error'] = 'Invalid email or password.';
            header('Location: /login'); exit;
        }

        // Archived account — redirect to restore page
        if (($user['status'] ?? 'active') === 'archived') {
            $_SESSION['archived_user_id'] = $user['user_id'];
            header('Location: /account/archived'); exit;
        }

        $_SESSION['user_id']    = $user['user_id'];
        $_SESSION['first_name'] = $user['first_name'];
        header('Location: /recipes');
        exit;
    }

    public static function handleRegister(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name']  ?? '');
        $email     = trim($_POST['email']      ?? '');
        $password  = trim($_POST['password']   ?? '');
        $confirm   = trim($_POST['confirm']    ?? '');

        if (!$firstName || !$lastName || !$email || !$password) {
            $_SESSION['auth_error'] = 'All fields are required.';
            header('Location: /register'); exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = 'Enter a valid email address.';
            header('Location: /register'); exit;
        }
        if (strlen($password) < 6) {
            $_SESSION['auth_error'] = 'Password must be at least 6 characters.';
            header('Location: /register'); exit;
        }
        if ($password !== $confirm) {
            $_SESSION['auth_error'] = 'Passwords do not match.';
            header('Location: /register'); exit;
        }

        $model = new UserModel();
        if ($model->findByEmail($email)) {
            $_SESSION['auth_error'] = 'Email is already registered.';
            header('Location: /register'); exit;
        }

        $model->create($firstName, $lastName, $email, $password);
        $user = $model->findByEmail($email);
        $_SESSION['user_id']    = $user['user_id'];
        $_SESSION['first_name'] = $user['first_name'];
        header('Location: /recipes');
        exit;
    }
}