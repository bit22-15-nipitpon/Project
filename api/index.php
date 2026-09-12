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
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

$request = str_replace('/project/api', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$method = $_SERVER['REQUEST_METHOD'];
$db = (new Database())->connect();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$routes = [
    'GET' => [
        '/reports' => ['handle' => [ReportController::class, 'index'],],
        '/reports/user' => [
            'handle' => [ReportController::class, 'show'],
            'middlewares' => ['Auth']
        ],
        '/reports_type' => ['handle' => [ReportController::class, 'type']],
    ],
    'POST' => [
        '/login' => ['handle' => [AuthController::class, 'login']],
        '/register' => ['handle' => [AuthController::class, 'create']],
        '/logout' => [
            'handle' => [AuthController::class, 'logout'],
            'middlewares' => ['Auth']
        ],
        '/create' => [
            'handle' => [ReportController::class, 'create'],
            'middlewares' => ['user']
        ],
    ],
    'PUT' => [
        '/progress/{report_id}' => [
            'handle' => [ReportController::class, 'progress'],
            'middlewares' => ['staff']
        ],
        '/resolved/{report_id}' => [
            'handle' => [ReportController::class, 'resolved'],
            'middlewares' => ['staff']
        ],
    ]
];

foreach ($routes[$method] as $route => $config) {
    $pattern = "#^" . preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $route) . "$#";
    if (preg_match($pattern, $request, $matches)) {
        if (isset($config['middlewares'])) {
            foreach ($config['middlewares'] as $mw) {
                if ($mw === "Auth") {
                    AuthMiddleware::handle($db);
                }
                if ($mw === 'admin') {
                    RoleMiddleware::handle($db, "admin");
                }
                if ($mw === 'staff') {
                    RoleMiddleware::handle($db, "staff");
                }
                if ($mw === 'user') {
                    RoleMiddleware::handle($db, "user");
                }
            }
        }

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
