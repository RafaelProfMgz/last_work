<?php

namespace App\Service;

use App\Repository\AuthRepository ;
use App\Util\JwtHandler;
use Exception;

class AuthService
{
    private  $authRepository;
    private  $jwtHandler;
    private string $jwtSecret;
    private string $jwtAlgo;
    private int $jwtExpiry;

    public function __construct(AuthRepository $authRepository, JwtHandler $jwtHandler, array $config)
    {
        $this->authRepository = $authRepository;
        $this->jwtHandler = $jwtHandler;
        $this->jwtSecret = $config['jwt_secret'] ?? 'seu_super_secreto_default';
        $this->jwtAlgo = $config['jwt_algo'] ?? 'HS256';
        $this->jwtExpiry = $config['jwt_expiry'] ?? 3600;
    }

    public function authenticateAndGenerateToken(string $email, string $password): string|false
    {
        $user = $this->authRepository->findByEmail($email);

        if (!$user || !password_verify($password, $user['senha'])) {
            return false;
        }

        $payload = [
            'iat' => time(),
            'exp' => time() + $this->jwtExpiry,
            'user_id' => $user['id'],
            'nome' => $user['nome']
        ];

        try {
            $token = $this->jwtHandler->encode($payload, $this->jwtSecret, $this->jwtAlgo);
            return $token;
        } catch (\Exception $e) {
            error_log("Erro ao gerar JWT: " . $e->getMessage());
            throw new Exception("Falha ao gerar token de autenticação.");
        }
    }

    public function register(string $nome, string $email, string $password): array
    {
        $errors = [];

        if (empty($nome)) {
            $errors[] = 'Nome é obrigatório.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        }

        if (empty($password)) {
            $errors[] = 'Senha é obrigatória.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'A senha deve ter no mínimo 8 caracteres.';
        }

        if (!empty($errors)) {
            http_response_code(400);
            return ['success' => false, 'errors' => $errors];
        }
        try {
            $this->authRepository->create($nome, $email, password_hash($password, PASSWORD_DEFAULT));

            http_response_code(201);
            return ['success' => true, 'message' => 'Usuário cadastrado com sucesso.'];
        } catch (Exception $e) {
            error_log("Erro no Service de Autenticação: " . $e->getMessage());
            http_response_code(500);
            return ['success' => false, 'errors' => ['Ocorreu um erro inesperado.']];
        }
    }

}

?>