<?php
header('Content-Type: application/json');
require_once '../../config/db_connection.php';
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

// Coleta os dados enviados via POST
$input = json_decode(file_get_contents('php://input'), true);
$valor = isset($input['valor']) ? floatval($input['valor']) : null;
$descricao = isset($input['descricao']) ? trim($input['descricao']) : null;

// Validação simples
if ($valor === null || $valor <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Valor inválido.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO saidas (user_id, valor, descricao) VALUES (:user_id, :valor, :descricao)");
    $stmt->execute([
        ':user_id'   => $_SESSION['user_id'],
        ':valor'     => $valor,
        ':descricao' => $descricao
    ]);

    echo json_encode(['success' => true, 'message' => 'Saída registrada com sucesso.']);
} catch (PDOException $e) {
    error_log("Erro ao inserir saída: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao registrar saída.']);
}
?>