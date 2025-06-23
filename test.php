<?php
// Carrega as variáveis de ambiente
$env = parse_ini_file('.env');

// Define as credenciais de conexão
define('DB_HOST', $env['DB_HOST']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);
define('DB_NAME', $env['DB_NAME']);

try {
    // Estabelece a conexão com o banco de dados
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Prepara e executa a consulta SQL
    $stmt = $pdo->query("SELECT * FROM users");

    // Verifica se há resultados
    if ($stmt->rowCount() > 0) {
        // Exibe os dados em uma tabela HTML
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Email</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nenhum dado encontrado.";
    }
} catch (PDOException $e) {
    // Exibe a mensagem de erro em caso de falha na conexão
    echo "Erro de conexão: " . $e->getMessage();
}
?>

