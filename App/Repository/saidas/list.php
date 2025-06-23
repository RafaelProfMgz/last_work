<?php
header('Content-Type: application/json');
require_once '../../config/db_connection.php';
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, valor, descricao, created_at FROM saidas WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $saidas = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $saidas]);
} catch (PDOException $e) {
    error_log("Erro ao listar saídas: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao listar saídas.']);
}
?>