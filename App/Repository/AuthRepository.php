<?php

namespace App\Repository;

use PDO;

class AuthRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, senha FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, email FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function update(int $id, string $nome, string $email): void
    {
        $stmt = $this->pdo->prepare("UPDATE users SET nome = ?, email = ? WHERE id = ?");
        $stmt->execute([$nome, $email, $id]);
    }

    public function create(string $nome, string $email, string $senha): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha]);
    }

    public function updatePassword(int $id, string $senha): void
    {
        $stmt = $this->pdo->prepare("UPDATE users SET senha = ? WHERE id = ?");
        $stmt->execute([$senha, $id]);
    }

}

?>