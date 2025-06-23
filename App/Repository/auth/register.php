<?php
header('Content-Type: application/json');
require_once '../config/db_connection.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $nome = trim($input['nome'] ?? '');
    $email = trim($input['email'] ?? '');
    $senha = $input['senha'] ?? '';
    $confirm_senha = $input['confirm_senha'] ?? '';

    // Validações
    if (empty($nome)) {
        $errors[] = "O nome completo é obrigatório.";
    }
    if (empty($email)) {
        $errors[] = "O e-mail é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Formato de e-mail inválido.";
    }
    if (empty($senha)) {
        $errors[] = "A senha é obrigatória.";
    } elseif (strlen($senha) < 8) {
        $errors[] = "A senha deve ter no mínimo 8 caracteres.";
    }
    if ($senha !== $confirm_senha) {
        $errors[] = "A confirmação de senha não coincide.";
    }

    if (empty($errors)) {
        try {
            // Verifica se o e-mail já está cadastrado
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $errors[] = "Este e-mail já está cadastrado.";
            } else {
                // Criptografa a senha
                $hashed_password = password_hash($senha, PASSWORD_DEFAULT);

                // Insere o novo usuário no banco de dados
                $stmt = $pdo->prepare("INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)");
                $stmt->execute([$nome, $email, $hashed_password]);

                echo json_encode(['success' => true]);
                return;
            }
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar usuário: " . $e->getMessage());
            $errors[] = "Erro ao cadastrar. Tente novamente mais tarde.";
        }
    }
} else {
    http_response_code(405);
    $errors[] = "Método de requisição não permitido.";
}

echo json_encode(['success' => false, 'errors' => $errors]);
?>

