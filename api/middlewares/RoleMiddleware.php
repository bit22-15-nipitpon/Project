<?php
require_once "middlewares/AuthMiddleware.php";

class RoleMiddleware {
    public static function handle($conn, $role) {
        $user = AuthMiddleware::handle($conn);

        if ($user['role'] !== $role) {
            http_response_code(403);
            echo json_encode([
                "success" => false,
                "message" => "Access denied"
            ]);
            exit;
        }

        $_REQUEST['user'] = $user;
        return $user;
    }
}