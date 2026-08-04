<?php
require_once "models/Auth.php";

class AuthController {
    private $auth;

    public function __construct($db) {
        $this->auth = new Auth($db);
    }

    public function true($data, $code = 200) {
        http_response_code($code);
        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
        exit;
    }

    public function false($message = "Not Found", $code = 404) {
        http_response_code($code);
        echo json_encode([
            "success" => false,
            "message" => $message
        ]);
        exit;
    }

    public function login() {
        $data = $_POST;

        if (empty($data['username']) || empty($data['password'])) {
            $this->false("Validation Failed");
        }

        $result = $this->auth->getToken($data);

        if (!$result) {
            $this->false();
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

    public function create() {
        $data = $_POST;
        $required = [
            "username",
            "password",
            "email",
            "phone",
        ];

        foreach ($required as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === "") {
                http_response_code(400);

                echo json_encode([
                    "success" => false,
                    "message" => "$field is required"
                ]);
                return;
            }
        }

        $result = $this->auth->create($data);

        http_response_code($result["success"] ? 201 : 500);
        echo json_encode($result);
    }
}
