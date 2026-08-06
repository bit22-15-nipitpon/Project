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

    public function inProgress($id) {
        $sql = "
            SELECT *
            FROM {$this->table} r
            INNER JOIN report_type t ON t.type_id = r.type_id
            INNER JOIN users u ON u.user_id = r.user_id
            WHERE r.report_id = ? and r.status = 'pending'
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            return ["inProgress" => true];
        }

        $up = "
            UPDATE `reports` 
            SET status = 'in_progress'
            WHERE report_id = ?
        ";

        $update = $this->conn->prepare($up);
        $update->bind_param("i", $id);
        $update->execute();

        return [
            "id" => $result['report_id'],
            "image" => $result['image'],
            "title" => $result['title'],
            "detail" => $result['detail'],
            "type" => $result['name'],
            "location" => $result['location'],
            "reporter_name" => $result['username'],
            "status" => 'in_progress'
        ];
    }

    public function resolved($id) {
        $sql = "
            SELECT *
            FROM {$this->table} r
            INNER JOIN report_type t ON t.type_id = r.type_id
            INNER JOIN users u ON u.user_id = r.user_id
            WHERE r.report_id = ? and r.status = 'in_progress'
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            return;
        }

        $up = "
            UPDATE `reports` 
            SET status = 'resolved'
            WHERE report_id = ?
        ";

        $update = $this->conn->prepare($up);
        $update->bind_param("i", $id);
        $update->execute();

        return [
            "id" => $result['report_id'],
            "image" => $result['image'],
            "title" => $result['title'],
            "detail" => $result['detail'],
            "type" => $result['name'],
            "location" => $result['location'],
            "reporter_name" => $result['username'],
            "status" => 'resolved',
        ];
    }

    pub
}