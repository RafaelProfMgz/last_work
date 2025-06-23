<?php
header('Content-Type: application/json');
require_once '../../config/db_connection.php';

session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$nome = trim($input['nome'] ?? '');
$email = trim($input['email'] ?? '');
$senha = $input['senha'] ?? '';
$confirm_senha = $input['confirm_senha'] ?? '';

$errors = [];

if (empty($nome)) {
    $errors[] = "O nome é obrigatório.";
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
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Este e-mail já está cadastrado.";
        } else {
            $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $hashed_password]);
            echo json_encode(['success' => true, 'message' => 'Usuário cadastrado com sucesso.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar usuário.']);
    }
} else {
    echo json_encode(['success' => false, 'errors' => $errors]);
}
?>
