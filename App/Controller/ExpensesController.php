<?php

namespace App\Controller;

use App\Service\ExpensesService;

use Exception;

class ExpensesController
{

    private ExpensesService $expensesService;
    public function __construct(ExpensesService $expensesService)
    {
        $this->expensesService = $expensesService;
    }

    public function findAll(): void
    {
        try {
            $expenses = $this->expensesService->findAll();
            echo json_encode($expenses);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function findOne(int $id): void
    {
        try {
            $expense = $this->expensesService->findOne($id);
            echo json_encode($expense);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->expensesService->delete($id);
            echo json_encode(['message' => 'Despesa excluida com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deleteMany(array $data): void
    {
        try {
            $this->expensesService->deleteMany($data);
            echo json_encode(['message' => 'Despesas excluidas com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update(int $id, array $data): void
    {
        try {
            $this->expensesService->update($id, $data);
            echo json_encode(['message' => 'Despesa atualizada com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    public function updateMany(array $data): void
    {
        try {
            $this->expensesService->updateMany($data);
            echo json_encode(['message' => 'Despesas atualizadas com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function create(array $data): void
    {
        try {
            $this->expensesService->create($data);
            echo json_encode(['message' => 'Despesa criada com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function createMany(array $data): void
    {
        try {
            $this->expensesService->createMany($data);
            echo json_encode(['message' => 'Despesas criadas com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

?>