<?php 

namespace App\Service;

use App\Repository\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }
    public function findOne(int $id): array
    {
        return $this->userRepository->findById($id);
    }

    public function delete(int $id): void
    {
        $this->userRepository->delete($id);
    }

    public function deleteMany(array $ids): void
    {
        foreach ($ids as $id) {
            $this->userRepository->delete($id);
        }
    }

    public function update(int $id, array $data): void
    {
        $this->userRepository->update($id, $data['nome'], $data['email']);
    }

    public function updateMany(array $data): void
    {
        foreach ($data as $user) {
            $this->userRepository->update($user['id'], $user['nome'], $user['email']);
        }
    }

    public function create(array $data): void
    {
        $this->userRepository->create($data['nome'], $data['email'], $data['senha']);
    }

    public function createMany(array $data): void
    {
        foreach ($data as $user) {
            $this->userRepository->create($user['nome'], $user['email'], $user['senha']);
        }
    }
}

?>