<?php

class UserModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE user_email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create(string $firstName, string $lastName, string $email, string $password): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'INSERT INTO users (first_name, last_name, user_email, user_password) VALUES (?, ?, ?, ?)'
        );
        return $stmt->execute([$firstName, $lastName, $email, $hash]);
    }
}
