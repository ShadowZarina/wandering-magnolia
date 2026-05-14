<?php

class ForgotPasswordHandler {

    public static function handleRequest(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = trim($_POST['email'] ?? '');

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['fp_error'] = 'Please enter a valid email address.';
            header('Location: /forgot-password'); exit;
        }

        $userModel = new UserModel();
        $user      = $userModel->findByEmail($email);

        if ($user) {
            $resetModel = new PasswordResetModel();
            $otp        = $resetModel->createToken($email);

            require_once ROOT . '/app/core/Mailer.php';
            Mailer::sendOtp($email, $user['first_name'], $otp);
        }

        $_SESSION['fp_email'] = $email;
        header('Location: /verify-otp'); exit;
    }

    public static function handleVerify(): void {

        error_log('OTP received: [' . ($_POST['otp_full'] ?? 'MISSING') . ']');
        error_log('Email in session: [' . ($_SESSION['fp_email'] ?? 'MISSING') . ']');
        error_log('DB check: ' . json_encode((new PasswordResetModel())->verify($_SESSION['fp_email'] ?? '', $_POST['otp_full'] ?? '')));

        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = $_SESSION['fp_email'] ?? '';
        $otp   = trim($_POST['otp_full'] ?? '');

        if (!$email) {
            header('Location: /forgot-password'); exit;
        }

        if (strlen($otp) !== 6 || !ctype_digit($otp)) {
            $_SESSION['fp_error'] = 'Please enter the complete 6-digit code.';
            header('Location: /verify-otp'); exit;
        }

        $resetModel = new PasswordResetModel();

        if (!$resetModel->verify($email, $otp)) {
            $_SESSION['fp_error'] = 'Invalid or expired code. Please try again.';
            header('Location: /verify-otp'); exit;
        }

        $resetModel->markUsed($email);
        $_SESSION['fp_verified'] = true;
        header('Location: /reset-password'); exit;
    }

    public static function handleReset(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email    = $_SESSION['fp_email']    ?? '';
        $verified = $_SESSION['fp_verified'] ?? false;

        if (!$email || !$verified) {
            header('Location: /forgot-password'); exit;
        }

        $password = trim($_POST['password'] ?? '');
        $confirm  = trim($_POST['confirm']  ?? '');

        if (strlen($password) < 6) {
            $_SESSION['fp_error'] = 'Password must be at least 6 characters.';
            header('Location: /reset-password'); exit;
        }

        if ($password !== $confirm) {
            $_SESSION['fp_error'] = 'Passwords do not match.';
            header('Location: /reset-password'); exit;
        }

        $userModel = new UserModel();
        $user      = $userModel->findByEmail($email);

        if (!$user) {
            header('Location: /forgot-password'); exit;
        }

        $userModel->updatePassword($user['user_id'], $password);

        $resetModel = new PasswordResetModel();
        $resetModel->deleteByEmail($email);
        unset($_SESSION['fp_email'], $_SESSION['fp_verified'], $_SESSION['fp_error']);

        $_SESSION['auth_success'] = 'Password reset successfully. You can now sign in.';
        header('Location: /login'); exit;
    }
}