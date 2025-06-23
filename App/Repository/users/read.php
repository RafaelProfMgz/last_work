<?php
require_once '../../config/db_connection.php';

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();

echo json_encode($users);
?>
