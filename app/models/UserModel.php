<?php

class UserModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE user_email = ? LIMIT 1'
        );
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE user_id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(string $firstName, string $lastName, string $email, string $password): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'INSERT INTO users (first_name, last_name, user_email, user_password) VALUES (?, ?, ?, ?)'
        );
        return $stmt->execute([$firstName, $lastName, $email, $hash]);
    }

    public function updateProfile(int $userId, string $firstName, string $lastName, string $email): bool {
        $stmt = $this->db->prepare(
            'UPDATE users SET first_name = ?, last_name = ?, user_email = ? WHERE user_id = ?'
        );
        return $stmt->execute([$firstName, $lastName, $email, $userId]);
    }

    public function updatePassword(int $userId, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'UPDATE users SET user_password = ? WHERE user_id = ?'
        );
        return $stmt->execute([$hash, $userId]);
    }

    public function archive(int $userId): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET status = 'archived', archived_at = NOW() WHERE user_id = ?"
        );
        return $stmt->execute([$userId]);
    }

    public function restore(int $userId): bool {
        $stmt = $this->db->prepare(
            "UPDATE users SET status = 'active', archived_at = NULL WHERE user_id = ?"
        );
        return $stmt->execute([$userId]);
    }

    public function purgeExpiredArchives(int $days = 30): void {
        $stmt = $this->db->prepare(
            "DELETE FROM users WHERE status = 'archived' AND archived_at < NOW() - INTERVAL ? DAY"
        );
        $stmt->execute([$days]);
    }
}