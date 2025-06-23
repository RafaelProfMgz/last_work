<?php

namespace App\Service;

use App\Repository\ExpensesRepository;

class ExpensesService
{

    private $expensesRepository;

    public function __construct(ExpensesRepository $expensesRepository)
    {
        $this->expensesRepository = $expensesRepository;
    }

    public function findAll(): array
    {
        return $this->expensesRepository->findAll();
    }

    public function findOne(int $id): array
    {
        return $this->expensesRepository->findById($id);
    }

    public function delete(int $id): void
    {
        $this->expensesRepository->delete($id);
    }

    public function deleteMany(array $ids): void
    {
        $this->expensesRepository->deleteMany($ids);
    }

    public function update(int $id, array $data): void
    {
        $this->expensesRepository->update($id, $data['valor'], $data['descricao']);
    }

    public function updateMany(array $data): void
    {
        $this->expensesRepository->updateMany($data);
    }

    public function create(array $data): void
    {
        $this->expensesRepository->create($data);
    }

    public function createMany(array $data): void
    {
        $this->expensesRepository->createMany($data);
    }
}

?>