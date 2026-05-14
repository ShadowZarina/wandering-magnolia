<?php
// app/models/PasswordResetModel.php

class PasswordResetModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function createToken(string $email): string {
        // Invalidate any existing tokens for this email
        $this->db->prepare('DELETE FROM password_resets WHERE user_email = ?')
                 ->execute([$email]);

        // Generate 6-digit OTP
        $token     = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $stmt2 = $this->db->query("SELECT DATE_ADD(NOW(), INTERVAL 15 MINUTE) as expiry");
        $expiresAt = $stmt2->fetchColumn();
        $stmt = $this->db->prepare(
            'INSERT INTO password_resets (user_email, token, expires_at) VALUES (?, ?, ?)'
        );
        $stmt->execute([$email, $token, $expiresAt]);

        return $token;
    }

    public function verify(string $email, string $token): bool {
        $email = trim($email);
        $token = trim($token);
        
        $stmt = $this->db->prepare(
            'SELECT id FROM password_resets
            WHERE user_email = ? AND token = ? AND used = 0 AND expires_at > NOW()
            LIMIT 1'
        );
        $stmt->execute([$email, $token]);
        $result = $stmt->fetch();
        error_log('Verify query - email: ' . $email . ' token: ' . $token . ' result: ' . json_encode($result));
        return (bool) $result;
    }

    public function markUsed(string $email): void {
        $this->db->prepare('UPDATE password_resets SET used = 1 WHERE user_email = ?')
                 ->execute([$email]);
    }

    public function deleteByEmail(string $email): void {
        $this->db->prepare('DELETE FROM password_resets WHERE user_email = ?')
                 ->execute([$email]);
    }
}