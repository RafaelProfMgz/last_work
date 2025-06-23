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

// Recebe os IDs via JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input) || empty($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nenhum ID fornecido.']);
    exit;
}

// Filtra os IDs válidos
$ids = array_filter($input, fn($id) => is_numeric($id) && $id > 0);
if (empty($ids)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'IDs inválidos fornecidos.']);
    exit;
}

try {
    // Monta placeholders dinamicamente
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = "DELETE FROM saidas WHERE id IN ($placeholders) AND user_id = ?";
    $stmt = $pdo->prepare($sql);

    // Junta os IDs com o user_id no final
    $params = [...$ids, $_SESSION['user_id']];
    $stmt->execute($params);

    echo json_encode([
        'success' => true,
        'message' => "Deleção concluída. Total removido: " . $stmt->rowCount()
    ]);
} catch (PDOException $e) {
    error_log("Erro ao deletar várias saídas: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao deletar saídas.']);
}
?>