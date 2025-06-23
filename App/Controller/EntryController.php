<?php

namespace App\Controller;

use App\Service\EntryService;

use Exception;

class EntryController
{
    private  EntryService $entryService;
    public function __construct(EntryService $entryService)
    {
        $this->entryService = $entryService;
    }

    public function findAll(): void
    {
        try {
            $entries = $this->entryService->findAll();
            echo json_encode($entries);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function findOne(int $id): void
    {
        try {
            $entry = $this->entryService->findOne($id);
            echo json_encode($entry);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->entryService->delete($id);
            echo json_encode(['message' => 'Entrada excluida com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deleteMany(array $ids): void
    {
        try {
            $this->entryService->deleteMany($ids);
            echo json_encode(['message' => 'Entradas excluidas com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update(int $id, array $data): void
    {
        try {
            $this->entryService->update($id, $data);
            echo json_encode(['message' => 'Entrada atualizada com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updateMany(array $data): void
    {
        try {
            $this->entryService->updateMany($data);
            echo json_encode(['message' => 'Entradas atualizadas com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function create(array $data): void
    {
        try {
            $this->entryService->create($data);
            echo json_encode(['message' => 'Entrada criada com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function createMany(array $data): void
    {
        try {
            $this->entryService->createMany($data);
            echo json_encode(['message' => 'Entradas criadas com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}
?>