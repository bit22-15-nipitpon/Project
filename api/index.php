<?php
require_once "config/Database.php";
require_once "controllers/AuthController.php";
require_once "controllers/ReportController.php";

require_once "middlewares/AuthMiddleware.php";
require_once "middlewares/RoleMiddleware.php";

date_default_timezone_set('Asia/Bangkok');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

$request = str_replace('/project/api', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$method = $_SERVER['REQUEST_METHOD'];
$db = (new Database())->connect();
$routes = [
    'GET' => [
        '/reports' => ['handle' => [ReportController::class, 'index']],
    ],
    'POST' => [
        '/login' => ['handle' => [AuthController::class, 'login']],
        '/register' => ['handle' => [AuthController::class, 'create']],
        '/logout' => [
            'handle' => [AuthController::class, 'logout'],
            'middlewares' => ['Auth']
        ],
    ],
];

foreach($routes[$method] as $route => $config) {
    if(!empty($config['middlewares'])) {
        foreach($config['middlewares'] as $mw) {
            if($mw === "Auth") {
                AuthMiddleware::handle($db);
            }
            if ($mw === 'admin') {
                RoleMiddleware::handle($db, "admin");
            }
        }
    }

    $pattern = "#^" . preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $route) . "$#";
    if (preg_match($pattern, $request, $matches)) {
        [$class, $action] = $config['handle'];
        call_user_func_array([new $class($db), $action], array_slice($matches, 1));
        exit;
    }
}

http_response_code(404);
echo json_encode([
    "success" => false,
    "message" => "Not Found"
]);