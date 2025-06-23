<?php
session_start();
$baseUrl = 'http://localhost/lastwork/backend/app/routes/api.php';

$rotas = [
    'Entradas - Listar' => ['url' => $baseUrl . '/entradas', 'method' => 'GET'],
    'Entradas - Criar' => ['url' => $baseUrl . '/entradas', 'method' => 'POST', 'data' => ['descricao' => 'Entrada Teste', 'valor' => 150.00, 'data' => '2023-01-01']],
    'Entradas - Ler (ID 1)' => ['url' => $baseUrl . '/entradas/1', 'method' => 'GET'],
    'Entradas - Atualizar (ID 1)' => ['url' => $baseUrl . '/entradas/1', 'method' => 'PUT', 'data' => ['descricao' => 'Entrada Teste Atualizada', 'valor' => 160.00]],
    'Entradas - Deletar (ID 999)' => ['url' => $baseUrl . '/entradas/999', 'method' => 'DELETE'],
    'Entradas - Deletar Várias' => ['url' => $baseUrl . '/entradas', 'method' => 'DELETE', 'data' => ['ids' => '1,2']],
    'Entradas - Atualizar Várias' => ['url' => $baseUrl . '/entradas', 'method' => 'PUT', 'data' => ['data' => json_encode([['id' => 1, 'valor' => 170]])]],
    'Entradas - Criar Vários' => ['url' => $baseUrl . '/entradas/many', 'method' => 'POST', 'data' => ['data' => json_encode([['desc' => 'E1'], ['desc' => 'E2']])]],
    'Despesas - Listar' => ['url' => $baseUrl . '/despesas', 'method' => 'GET'],
    'Despesas - Criar' => ['url' => $baseUrl . '/despesas', 'method' => 'POST', 'data' => ['descricao' => 'Despesa Teste', 'valor' => 50.00, 'data' => '2023-01-02']],
    'Despesas - Ler (ID 1)' => ['url' => $baseUrl . '/despesas/1', 'method' => 'GET'],
    'Despesas - Atualizar (ID 1)' => ['url' => $baseUrl . '/despesas/1', 'method' => 'PUT', 'data' => ['descricao' => 'Despesa Teste Atualizada', 'valor' => 60.00]],
    'Despesas - Deletar (ID 999)' => ['url' => $baseUrl . '/despesas/999', 'method' => 'DELETE'],
    'Despesas - Deletar Várias' => ['url' => $baseUrl . '/despesas', 'method' => 'DELETE', 'data' => ['ids' => '3,4']],
    'Despesas - Atualizar Várias' => ['url' => $baseUrl . '/despesas', 'method' => 'PUT', 'data' => ['data' => json_encode([['id' => 3, 'valor' => 70]])]],
    'Despesas - Criar Vários' => ['url' => $baseUrl . '/despesas/many', 'method' => 'POST', 'data' => ['data' => json_encode([['desc' => 'D1'], ['desc' => 'D2']])]],
    'Usuários - Criar' => ['url' => $baseUrl . '/users', 'method' => 'POST', 'data' => ['nome' => 'Teste', 'email' => 'teste@example.com', 'senha' => 'password123']],
    'Usuários - Listar' => ['url' => $baseUrl . '/users', 'method' => 'GET'],
    'Usuários - Ler (ID 1)' => ['url' => $baseUrl . '/users/1', 'method' => 'GET'],
    'Usuários - Atualizar (ID 1)' => ['url' => $baseUrl . '/users/1', 'method' => 'PUT', 'data' => ['nome' => 'Teste Atualizado']],
    'Usuários - Deletar (ID 999)' => ['url' => $baseUrl . '/users/999', 'method' => 'DELETE'],
    'Usuários - Deletar Várias' => ['url' => $baseUrl . '/users', 'method' => 'DELETE', 'data' => ['ids' => '5,6']],
    'Usuários - Atualizar Várias' => ['url' => $baseUrl . '/users', 'method' => 'PUT', 'data' => ['data' => json_encode([['id' => 5, 'email' => 'upd@example.com']])]],
    'Usuários - Criar Vários' => ['url' => $baseUrl . '/users/many', 'method' => 'POST', 'data' => ['data' => json_encode([['email' => 'u1@ex.com'], ['email' => 'u2@ex.com']])]],
    'Auth - Registrar' => ['url' => $baseUrl . '/register', 'method' => 'POST', 'data' => ['email' => 'novo@example.com', 'password' => 'password123', 'nome' => 'Novo User']],
    'Auth - Login' => ['url' => $baseUrl . '/login', 'method' => 'POST', 'data' => ['email' => 'teste@example.com', 'password' => 'password123']],
    'Auth - Logout' => ['url' => $baseUrl . '/logout', 'method' => 'POST'],
];

function testarRota($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    $headers = [];
    if ($method === 'POST' || $method === 'PUT' || $method === 'DELETE') {
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        } else {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }
    }
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $response = curl_exec($ch);
    $err = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($err) {
        return ['status_code' => 0, 'status_text' => "Erro cURL: $err", 'body' => ''];
    }
    return ['status_code' => $httpCode, 'status_text' => "Status HTTP: $httpCode", 'body' => $response];
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Backend - Controle de Rotas</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="dashboard-body">
<div class="dashboard-container">
    <h1 class="dashboard-title">Dashboard Backend - Controle de Rotas</h1>
    <p>Testando rotas na base URL: <strong><?php echo htmlspecialchars($baseUrl); ?></strong></p>
    <p><em>Nota: Rotas com <code>{id}</code> são testadas com ID placeholder (<code>/1</code> ou <code>/999</code>). Rotas que exigem autenticação ou dados específicos podem falhar.</em></p>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>Rota</th>
                <th>Método</th>
                <th>Status</th>
                <th>Resposta</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($rotas as $nome => $dados) {
                $requestData = $dados['data'] ?? null;
                $url = $dados['url'];
                $resultado = testarRota($url, $dados['method'], $requestData);
                $statusCode = $resultado['status_code'];
                $classeStatus = 'status-other';
                if ($statusCode === 200) {
                    $classeStatus = 'status-200';
                } elseif ($statusCode >= 400 && $statusCode < 500) {
                    if ($statusCode === 400) $classeStatus = 'status-400';
                    elseif ($statusCode === 401) $classeStatus = 'status-401';
                    elseif ($statusCode === 403) $classeStatus = 'status-403';
                    elseif ($statusCode === 404) $classeStatus = 'status-404';
                    else $classeStatus = 'status-400';
                } elseif ($statusCode >= 500) {
                    $classeStatus = 'status-500';
                } elseif ($statusCode === 0) {
                    $classeStatus = 'status-error';
                }
                echo "<tr>";
                echo "<td><a href='$url' target='_blank' class='dashboard-link' title='$url'>$nome</a></td>";
                echo "<td>{$dados['method']}</td>";
                echo "<td class='$classeStatus'>{$resultado['status_text']}</td>";
                echo "<td><pre>" . htmlspecialchars($resultado['body']) . "</pre></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
