<?php
require_once "models/Auth.php";

class AuthController {
    private $auth;

    public function __construct($db) {
        $this->auth = new Auth($db);
    }

    public function true($data) {
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
    }

    public function false($code) {
        http_response_code($code);
        echo json_encode([
            "success" => false,
            "message" => "Login failed"
        ]);
    }

    public function login() {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $result = $this->auth->getToken($user, $pass);
        if (!$result) {
            $this->false(404);
            return;
        }
        $this->true($result);
    }

    public function logout() {
        $token = $_REQUEST['token'];
        $result = $this->auth->delToken($token);

        http_response_code(200);
        echo json_encode([
            "success" => true
        ]);
    }
}
