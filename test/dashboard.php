<?php
session_start();

// Rotas para testar (ajuste conforme suas rotas reais)
$rotas = [
    'Entradas - Listar' => ['url' => '/lastwork/backend/api/entradas/list.php', 'method' => 'GET'],
    'Entradas - Criar' => ['url' => '/lastwork/backend/api/entradas/create.php', 'method' => 'POST'],

    'Saídas - Listar' => ['url' => '/lastwork/backend/api/saidas/list.php', 'method' => 'GET'],
    'Saídas - Criar' => ['url' => '/lastwork/backend/api/saidas/create.php', 'method' => 'POST'],

    'Usuários - Criar' => ['url' => '/lastwork/backend/api/users/create.php', 'method' => 'POST'],
    'Usuários - Deletar' => ['url' => '/lastwork/backend/api/users/delete.php', 'method' => 'DELETE'],
    'Usuários - Listar' => ['url' => '/lastwork/backend/api/users/list.php', 'method' => 'GET'],
    'Usuários - Ler' => ['url' => '/lastwork/backend/api/users/read.php', 'method' => 'GET'],
    'Usuários - Atualizar' => ['url' => '/lastwork/backend/api/users/update.php', 'method' => 'PUT'],

    'Register' => ['url' => '/lastwork/backend/api/register.php', 'method' => 'POST'],
    'Login' => ['url' => '/lastwork/backend/api/login.php', 'method' => 'POST'],
    'Logout' => ['url' => '/lastwork/backend/api/logout.php', 'method' => 'POST'],
];

// Função para testar rota (via cURL)
function testarRota($url, $method = 'GET', $data = null) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($method === 'POST' || $method === 'PUT' || $method === 'DELETE') {
        if ($data) {
             curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
             curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
            ]);
        } else if ($method === 'POST' || $method === 'PUT') {
             curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
             ]);
        }
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
                // Testa a rota e pega o status e corpo
                $url = 'http://localhost' . $dados['url'];
                $resultado = testarRota($url, $dados['method']);

                // Define a classe CSS com base no status HTTP
                $statusCode = $resultado['status_code'];
                $classeStatus = 'status-other'; 
                if ($statusCode === 200) {
                    $classeStatus = 'status-200';
                } elseif ($statusCode >= 400 && $statusCode < 500) {
                    if ($statusCode === 401 || $statusCode === 403 || $statusCode === 404) {
                        $classeStatus = 'status-' . $statusCode;
                    } else {
                        $classeStatus = 'status-400';
                    }
                } elseif ($statusCode >= 500) {
                    $classeStatus = 'status-500';
                } elseif ($statusCode === 0) {
                    $classeStatus = 'status-error';
                }

                echo "<tr>";
                echo "<td><a href='$url' target='_blank' class='dashboard-link'>$nome</a></td>";
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
