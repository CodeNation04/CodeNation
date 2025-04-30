<?php
    require_once __DIR__ . '/../../config/database.php';

    class Main {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        // 예시 메서드
        public function getAdmins($id,$pw) {
            $stmt = $this->db->query("SELECT * FROM admin");
            // $stmt = $this->db->query("SELECT * FROM admin where id =". $id . "and pw = " . $pw);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>