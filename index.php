<?php

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

require __DIR__ . '/config/db_connection.php';

if (!isset($pdo) || !($pdo instanceof PDO)) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno: Falha ao obter a conexão com o banco de dados.']);
    exit;
}

$config = [
    'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'fallback_secret',
    'jwt_algo' => $_ENV['JWT_ALGO'] ?? 'HS256',
    'jwt_expiration_minutes' => $_ENV['JWT_EXPIRATION_MINUTES'] ?? 60
];

use App\Util\JwtHandler;
$jwtHandler = new JwtHandler();

use App\Router;
$router = new Router();

require __DIR__ . '/App/routes/api.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$basePath = '/lastwork/backend';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}
$requestUri = strtok($requestUri, '?');

if (empty($requestUri) || $requestUri === '/') {
     $requestUri = '/';
}

try {
    $match = $router->match($requestMethod, $requestUri);

    if ($match === false) {
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint não encontrado.']);
    } else {
        $handler = $match['handler'];
        $vars = $match['vars'];

        $requestData = null;
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'application/json') !== false) {
             $json_data = file_get_contents('php://input');
             $requestData = json_decode($json_data, true);

             if ($requestData === null && $json_data !== '') {
                 http_response_code(400);
                 echo json_encode(['error' => 'Corpo da requisição JSON inválido.']);
                 exit;
             }
        } else if (strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
            if ($requestMethod === 'POST') {
                $requestData = $_POST;
            } else {
                parse_str(file_get_contents('php://input'), $putDeleteData);
                $requestData = $putDeleteData;
            }
        } else if ($requestMethod === 'POST' && empty($_POST)) {
             $requestData = $_POST;
        }

        $controllerClass = $handler[0];
        $methodName = $handler[1];

        $controllerInstance = null;

        switch ($controllerClass) {
            case App\Controller\AuthController::class:
                $authRepository = new App\Repository\AuthRepository($pdo);
                $authService = new App\Service\AuthService($authRepository, $jwtHandler, $config);
                $controllerInstance = new $controllerClass($authService);
                break;
            case App\Controller\UserController::class:
                 $userRepository = new App\Repository\UserRepository($pdo);
                 $userService = new App\Service\UserService($userRepository);
                 $controllerInstance = new $controllerClass($userService);
                 break;
            case App\Controller\EntryController::class:
                 $entryRepository = new App\Repository\EntryRepository($pdo);
                 $entryService = new App\Service\EntryService($entryRepository);
                 $controllerInstance = new $controllerClass($entryService);
                 break;
            case App\Controller\ExpensesController::class:
                 $expensesRepository = new App\Repository\ExpensesRepository($pdo);
                 $expensesService = new App\Service\ExpensesService($expensesRepository);
                 $controllerInstance = new $controllerClass($expensesService);
                 break;
            default:
                http_response_code(500);
                echo json_encode(['error' => 'Controller não configurado para injeção de dependência: ' . $controllerClass]);
                exit;
        }

        $args = [];

        if (!empty($vars)) {
             $args = array_values($vars);
        }

        if ($requestData !== null && ($requestMethod === 'POST' || $requestMethod === 'PUT' || ($requestMethod === 'DELETE' && !empty($requestData)))) {
             $args[] = $requestData;
        }

        $response = call_user_func_array([$controllerInstance, $methodName], $args);

        echo json_encode($response);
    }

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ocorreu um erro interno do servidor.']);
    error_log("Erro Fatal do Servidor: " . $e->getMessage() . "\n" . $e->getTraceAsString());
}

?>
