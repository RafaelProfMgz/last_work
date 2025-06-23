<?php
header('Content-Type: application/json');
require_once '../../config/db_connection.php';
session_start();

// Verifica autenticação
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

// Lê o corpo da requisição (esperado JSON)
$input = json_decode(file_get_contents('php://input'), true);

// Verifica se é um array válido
if (!is_array($input) || count($input) === 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos ou vazios.']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO entradas (user_id, valor, descricao) VALUES (:user_id, :valor, :descricao)");

    foreach ($input as $index => $entrada) {
        // Valida dados obrigatórios
        if (!isset($entrada['valor']) || !is_numeric($entrada['valor']) || $entrada['valor'] <= 0) {
            throw new Exception("Valor inválido no item $index.");
        }

        $valor = floatval($entrada['valor']);
        $descricao = isset($entrada['descricao']) ? trim($entrada['descricao']) : null;

        $stmt->execute([
            ':user_id'   => $_SESSION['user_id'],
            ':valor'     => $valor,
            ':descricao' => $descricao
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Todas as entradas foram registradas com sucesso.']);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Erro ao inserir várias entradas: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao registrar entradas.', 'error' => $e->getMessage()]);
}
