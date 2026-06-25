<?php
class Database {
    private $conn;
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "issue_reporting_db";

    public function connect() {
        $this->conn = null;

        $this->conn = mysqli_connect(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        return $this->conn;
    }
}