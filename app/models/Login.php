<?php
    require_once __DIR__ . '/../../config/database.php';

    class Login {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function getAdminByUsername($username) {
            $debugSql = "SELECT * FROM admin WHERE id = '" . addslashes($username) . "'";
            // echo "<script>console.log(`실행될 쿼리(예상):  {$debugSql}`);</script>";

            $stmt = $this->db->prepare("SELECT * FROM admin WHERE id = :username" );
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>