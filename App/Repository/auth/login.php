<?php
header('Content-Type: application/json');
require_once '../config/db_connection.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;

const JWT_SECRET = 'seu_super_secreto';
const JWT_ALGO = 'HS256';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['success' => false, 'errors' => ['M  todo n o permitido.']]));
}

$input = json_decode(file_get_contents('php://input'), true);
$errors = [];

$email = trim($input['email'] ?? '');
$senha = $input['senha'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email inv lido.';
}

if (empty($senha)) {
    $errors[] = 'Senha   obrigat ria.';
}

if ($errors) {
    exit(json_encode(['success' => false, 'errors' => $errors]));
}

try {
    $stmt = $pdo->prepare("SELECT id, nome, senha FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($senha, $user['senha'])) {
        http_response_code(401);
        exit(json_encode(['success' => false, 'errors' => ['Credenciais inv lidas.']]));
    }

    $now = time();
    $payload = [
        'iat' => $now,
        'exp' => $now + 3600, // expira em 1 hora
        'user_id' => $user['id'],
        'nome' => $user['nome']
    ];

    $token = JWT::encode($payload, JWT_SECRET, JWT_ALGO);

    echo json_encode(['success' => true, 'token' => $token]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Erro interno.']]);
}

?>