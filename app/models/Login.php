<?php
    require_once __DIR__ . '/../../config/database.php';

    class Login {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function getAdmins($id,$pw) {
            $stmt = $this->db->query("SELECT * FROM admin");
            // $stmt = $this->db->query("SELECT * FROM admin where id =". $id . "and pw = " . $pw);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>