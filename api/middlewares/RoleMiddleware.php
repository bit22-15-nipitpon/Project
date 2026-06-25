<?php
require_once "middlewares/AuthMiddleware.php";

class RoleMiddleware {
    public static function handle($conn, $role) {
        $user = AuthMiddleware::handle($conn);

        if ($user['role'] !== $role) {
            http_response_code(403);
            echo json_encode([
                "status" => false,
                "message" => "Access denied"
            ]);
            exit;
        }

        return $user;
    }
}