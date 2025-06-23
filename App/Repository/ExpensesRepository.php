<?php 
namespace App\Repository;

use PDO;

class ExpensesRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, user_id, valor, descricao, created_at, updated_at FROM saidas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT id, user_id, valor, descricao, created_at, updated_at FROM saidas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM saidas WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function deleteMany(array $ids): void
    {
        foreach ($ids as $id) {
            $this->delete($id);
        }
    }

    public function update(int $id, float $valor, ?string $descricao): void
    {
        $stmt = $this->pdo->prepare("UPDATE saidas SET valor = ?, descricao = ? WHERE id = ?");
        $stmt->execute([$valor, $descricao, $id]);
    }

    public function updateMany(array $data): void
    {
        foreach ($data as $expense) {
            $this->update($expense['id'], $expense['valor'], $expense['descricao']);
        }
    }

    public function create(array $data): void
    {
        $this->pdo->prepare("INSERT INTO saidas (user_id, valor, descricao) VALUES (:user_id, :valor, :descricao)")
            ->execute([
                ':user_id'   => $data['user_id'],
                ':valor'     => $data['valor'],
                ':descricao' => $data['descricao']
            ]);
    }

    public function createMany(array $data): void
    {
        foreach ($data as $expense) {
            $this->create([
                'user_id'   => $expense['user_id'],
                'valor'     => $expense['valor'],
                'descricao' => $expense['descricao']
            ]);
        }
    }
}

