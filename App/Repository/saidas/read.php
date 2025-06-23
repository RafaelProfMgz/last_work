<?php
header('Content-Type: application/json');
require_once '../../config/db_connection.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, valor, descricao, created_at FROM saidas WHERE id = :id AND user_id = :user_id");
    $stmt->execute([':id' => $id, ':user_id' => $_SESSION['user_id']]);
    $saida = $stmt->fetch();

    if ($saida) {
        echo json_encode(['success' => true, 'data' => $saida]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Saída não encontrada.']);
    }
} catch (PDOException $e) {
    error_log("Erro ao ler saída: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar saída.']);
}
?>