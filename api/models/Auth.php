<?php
class Auth {
    private $conn;
    private $table = "users";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function execute($sql, $type, ...$param) {
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($type, ...$param);
        $stmt->execute();

        if($stmt->field_count > 0) {
            return $stmt->get_result();
        }
        
        return $stmt;
    }

    public function getToken($user, $pass) {
        $sql = "SELECT * FROM {$this->table} WHERE username = ?";
        $result = $this->execute($sql, "s", $user)->fetch_assoc();

        if (!$result) {
            return false;
        }

        if (!password_verify($pass, $result['password'])) {
            return false;
        }

        $token = bin2hex(random_bytes(16));
        $update = "UPDATE {$this->table} SET token = '$token' WHERE username = ?";
        $this->execute($update, "s", $user);

        return [
            'token' => $token,
            "user" => [
                "id" => $result['user_id'],
                "username" => $result['username'],
                "email" => $result['email'],
                "role" => $result['role'],
                "created_at" => $result['created_at'],
                "updated_at" => $result['updated_at']
            ]
        ];
    }

    public function delToken($token) {
        $update = "UPDATE {$this->table} SET token = NULL WHERE token = ?";
        $stmt = $this->execute($update, "s", $token);

        return $stmt;
    }

    public static function findByToken($conn, $token) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");

        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}