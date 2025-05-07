<?php
    require_once __DIR__ . '/../../config/database.php';

    class Main {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function getAdmins($id) {
            $debugSql = "SELECT * FROM admin WHERE id = '" . addslashes($id) . "'";
            echo "<script>console.log(`실행될 쿼리(예상):  {$debugSql}`);</script>";

            $stmt = $this->db->prepare("SELECT * FROM admin WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>