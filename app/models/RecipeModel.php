<?php

class RecipeModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll(): array {
        $stmt = $this->db->query('SELECT * FROM recipes ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare('SELECT * FROM recipes WHERE recipe_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getIngredients(int $recipeId): array {
        $stmt = $this->db->prepare(
            'SELECT * FROM ingredients WHERE recipe_id = ? ORDER BY ingredient_id'
        );
        $stmt->execute([$recipeId]);
        return $stmt->fetchAll();
    }

    public function getDirections(int $recipeId): array {
        $stmt = $this->db->prepare(
            'SELECT * FROM directions WHERE recipe_id = ? ORDER BY step_number'
        );
        $stmt->execute([$recipeId]);
        return $stmt->fetchAll();
    }

    public function create(int $userId, string $title, string $imageUrl, string $difficulty): int {
        $stmt = $this->db->prepare(
            'INSERT INTO recipes (user_id, title, image_url, difficulty, is_premade) VALUES (?, ?, ?, ?, 0)'
        );
        $stmt->execute([$userId, $title, $imageUrl, $difficulty]);
        return (int) $this->db->lastInsertId();
    }

    public function addIngredient(int $recipeId, string $name, float $qty, string $unit): void {
        $stmt = $this->db->prepare(
            'INSERT INTO ingredients (recipe_id, name, base_quantity, unit) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$recipeId, $name, $qty, $unit]);
    }

    public function addDirection(int $recipeId, int $step, string $instruction): void {
        $stmt = $this->db->prepare(
            'INSERT INTO directions (recipe_id, step_number, instruction) VALUES (?, ?, ?)'
        );
        $stmt->execute([$recipeId, $step, $instruction]);
    }
}
