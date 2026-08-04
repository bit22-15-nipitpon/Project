<?php
require_once "models/Report.php";

class ReportController {
    private $table;

    public function __construct($db) {
        $this->table = new Report($db);
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

    public function index() {
        $result = $this->table->getAll();

        if (!$result) {
            $this->false();
        }

        $this->true($result);
    }
}