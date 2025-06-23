<?php

namespace App\Service;

use App\Repository\EntryRepository;

class EntryService
{
    private $entryRepository;

    public function __construct(EntryRepository $entryRepository)
    {
        $this->entryRepository = $entryRepository;
    }

    public function findAll(): array
    {
        return $this->entryRepository->findAll();
    }

    public function findOne(int $id): array
    {
        return $this->entryRepository->findById($id);
    }

    public function delete(int $id): void
    {
        $this->entryRepository->delete($id);
    }

    public function deleteMany(array $ids): void
    {
        foreach ($ids as $id) {
            $this->entryRepository->delete($id);
        }
    }

    public function update(int $id, array $data): void
    {
        $this->entryRepository->update($id, $data['valor'], $data['descricao']);
    }

    public function updateMany(array $data): void
    {
        foreach ($data as $entry) {
            $this->entryRepository->update($entry['id'], $entry['valor'], $entry['descricao']);
        }
    }

    public function create(array $data): void
    {
        $this->entryRepository->create($data);
    }

    public function createMany(array $data): void
    {
        $this->entryRepository->createMany($data);
    }
}
