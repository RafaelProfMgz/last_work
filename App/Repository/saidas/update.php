<?php
header('Content-Type: application/json');
require_once '../../config/db_connection.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$id = isset($input['id']) ? intval($input['id']) : 0;
$valor = isset($input['valor']) ? floatval($input['valor']) : null;
$descricao = isset($input['descricao']) ? trim($input['descricao']) : null;

if ($id <= 0 || $valor === null || $valor <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE saidas SET valor = :valor, descricao = :descricao, updated_at = CURRENT_TIMESTAMP WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        ':valor'     => $valor,
        ':descricao' => $descricao,
        ':id'        => $id,
        ':user_id'   => $_SESSION['user_id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Saída atualizada com sucesso.']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Saída não encontrada ou nenhum dado alterado.']);
    }
} catch (PDOException $e) {
    error_log("Erro ao atualizar saída: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar saída.']);
}
?>