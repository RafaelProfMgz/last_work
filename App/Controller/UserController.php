<?php

namespace App\Controller;

use App\Service\UserService;

use Exception;

class UserController
{
    private UserService  $userService ;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function findAll(): void
    {
        try {
            $users = $this->userService->findAll();
            echo json_encode($users);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    public function findOne(int $id): void
    {
        try {
            $user = $this->userService->findOne($id);
            echo json_encode($user);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    public function delete(int $id): void
    {
        try {
            $this->userService->delete($id);
            echo json_encode(['message' => 'Usuário excluído com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deleteMany(array $ids): void
    {
        try {
            $this->userService->deleteMany($ids);
            echo json_encode(['message' => 'Usuários excluídos com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function update(int $id, array $data): void
    {
        try {
            $this->userService->update($id, $data);
            echo json_encode(['message' => 'Usuário atualizado com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updateMany(array $data): void
    {
        try {
            $this->userService->updateMany($data);
            echo json_encode(['message' => 'Usuários atualizados com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function create(array $data): void
    {
        try {
            $this->userService->create($data);
            echo json_encode(['message' => 'Usuário criado com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function createMany(array $data): void
    {
        try {
            $this->userService->createMany($data);
            echo json_encode(['message' => 'Usuários criados com sucesso.']);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

?>