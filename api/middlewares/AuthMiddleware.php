<?php
require_once __DIR__ . "/../models/Auth.php";

class AuthMiddleware {
    public static function handle($conn) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization']
            ?? $headers['authorization']
            ?? $_SERVER['HTTP_AUTHORIZATION']
            ?? '';

        if (empty($authHeader)) {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Access Token is required"
            ]);
            exit;
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $user = Auth::findByToken($conn, $token);

        if (!$user) {
            http_response_code(401);
            echo json_encode([
                "success" => false,
                "message" => "Invalid Access Token"
            ]);
            exit;
        }

        $_REQUEST['token'] = $user['token'];
        return $user;
    }
}