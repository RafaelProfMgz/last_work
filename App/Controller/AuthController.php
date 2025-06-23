<?php
namespace App\Controller;

use App\Service\AuthService;
use Exception;

class AuthController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(array $requestData): array
    {
        $errors = [];
        $email = trim($requestData['email'] ?? '');
        $password = $requestData['senha'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        }

        if (empty($password)) {
            $errors[] = 'Senha é obrigatória.';
        }

        if (!empty($errors)) {
            http_response_code(400);
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $token = $this->authService->authenticateAndGenerateToken($email, $password);

            if ($token) {
                http_response_code(200);
                return ['success' => true, 'token' => $token];
            } else {
                http_response_code(401);
                return ['success' => false, 'errors' => ['Credenciais inválidas.']];
            }
        } catch (Exception $e) {
            error_log("Erro no Controller de Autenticação: " . $e->getMessage());
            http_response_code(500);
            return ['success' => false, 'errors' => ['Ocorreu um erro inesperado.']];
        }
    }

    public function register(array $requestData): array
    {
        $errors = [];
        $nome = trim($requestData['nome'] ?? '');
        $email = trim($requestData['email'] ?? '');
        $password = $requestData['senha'] ?? '';
        $confirmPassword = $requestData['confirm_senha'] ?? '';

        if (empty($nome)) {
            $errors[] = 'Nome é obrigatório.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        }

        if (empty($password)) {
            $errors[] = 'Senha é obrigatória.';
        }

        if (empty($confirmPassword)) {
            $errors[] = 'Confirmação de senha é obrigatória.';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Senha e confirmação de senha não conferem.';
        }

        if (!empty($errors)) {
            http_response_code(400);
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $user = $this->authService->register($nome, $email, $password);

            if ($user) {
                http_response_code(201);
                return ['success' => true, 'message' => 'Usuário cadastrado com sucesso.'];
            } else {
                http_response_code(400);
                return ['success' => false, 'errors' => ['Ocorreu um erro ao cadastrar o usuário.']];
            }
        } catch (Exception $e) {
            error_log("Erro no Controller de Autenticação: " . $e->getMessage());
            http_response_code(500);
            return ['success' => false, 'errors' => ['Ocorreu um erro inesperado.']];
        }
    }
}
?>
