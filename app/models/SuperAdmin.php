<?php
    require_once __DIR__ . '/../../config/database.php';

    class SuperAdmin {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function updateAdminInfo($id,$ip,$pw) {
            $encodedPw = base64_encode($pw);
            $debugSql = "UPDATE admin SET access_ip = '" . addslashes($ip) . "', pw = '" . addslashes($encodedPw) . "' WHERE id = '" . addslashes($id) . "'";
            echo "<script>console.log(`실행될 쿼리(예상):  {$debugSql}`);</script>";

            $stmt = $this->db->prepare("UPDATE admin SET access_ip = :ip, pw = :encodedPw WHERE id = :id" );
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':ip', $ip);
            $stmt->bindParam(':encodedPw', $encodedPw);
            
            return $stmt->execute();
        }
    }
?>