<?php
class Report {
    private $conn;
    private $table = "reports";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        $result = [];
        $data = $this->conn->query("
            SELECT * FROM {$this->table} r
            INNER JOIN report_type t ON t.type_id = r.type_id
            INNER JOIN users u ON u.user_id = r.user_id
            ORDER BY status ASC
        ");

        while ($row = $data->fetch_assoc()) {
            $result[] = [
                "image" => $row['image'],
                "title" => $row['title'],
                "detail" => $row['detail'],
                "type" => $row['name'],
                "location" => $row['location'],
                "reporter_name" => $row['username'],
                "status" => $row['status'],
            ];
        }

        return $result;
    }
}