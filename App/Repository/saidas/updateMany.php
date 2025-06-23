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

// Validação básica
if (!is_array($input) || empty($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nenhuma entrada fornecida.']);
    exit;
}

$sucesso = 0;
$falhas = [];

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        UPDATE entradas
        SET valor = :valor, descricao = :descricao, updated_at = CURRENT_TIMESTAMP
        WHERE id = :id AND user_id = :user_id
    ");

    foreach ($input as $entrada) {
        $id = isset($entrada['id']) ? intval($entrada['id']) : 0;
        $valor = isset($entrada['valor']) ? floatval($entrada['valor']) : null;
        $descricao = isset($entrada['descricao']) ? trim($entrada['descricao']) : null;

        if ($id > 0 && $valor !== null && $valor > 0) {
            $stmt->execute([
                ':valor'     => $valor,
                ':descricao' => $descricao,
                ':id'        => $id,
                ':user_id'   => $_SESSION['user_id']
            ]);

            if ($stmt->rowCount() > 0) {
                $sucesso++;
            } else {
                $falhas[] = $id;
            }
        } else {
            $falhas[] = $id;
        }
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => "Atualizações concluídas.",
        'atualizados' => $sucesso,
        'falhas' => $falhas
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Erro ao atualizar múltiplas entradas: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar entradas.']);
}
