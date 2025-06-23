<?php
namespace App\Repository;

use PDO;

class EntryRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, user_id, valor, descricao, created_at, updated_at FROM entradas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT id, user_id, valor, descricao, created_at, updated_at FROM entradas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM entradas WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function update(int $id, float $valor, ?string $descricao): void
    {
        $stmt = $this->pdo->prepare("UPDATE entradas SET valor = ?, descricao = ? WHERE id = ?");
        $stmt->execute([$valor, $descricao, $id]);
    }

    public function updateMany(array $data): void
    {
        foreach ($data as $entry) {
            $this->update($entry['id'], $entry['valor'], $entry['descricao']);
        }
    }

    public function create(array $data): void
    {
        $this->pdo->prepare("INSERT INTO entradas (user_id, valor, descricao) VALUES (:user_id, :valor, :descricao)")
            ->execute([
                ':user_id'   => $data['user_id'],
                ':valor'     => $data['valor'],
                ':descricao' => $data['descricao']
            ]);
    }

    public function createMany(array $data): void
    {
        foreach ($data as $entry) {
            $this->create([
                'user_id'   => $entry['user_id'],
                'valor'     => $entry['valor'],
                'descricao' => $entry['descricao']
            ]);
        }
    }

}
?>