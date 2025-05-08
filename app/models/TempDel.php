<?php
    require_once __DIR__ . '/../../config/database.php';

    class TempDel {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function insertTempDel($code_id,$reser_date,$work_potin,$del_target,$temp_del) {
            $create_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $create_ip = $_SERVER['REMOTE_ADDR'];

            $debugSql = "INSERT INTO del_env (code_code_id, reser_date, work_potin, del_target, del_method, create_date, create_ip)
                        \n VALUES ('" . addslashes($code_id) . "','" . addslashes($reser_date) . "','" . addslashes($work_potin) . "','" . addslashes($del_target) . "','temp_del','" . addslashes($create_date) . "','" . addslashes($create_ip) . "')";
            echo "<script>console.log(`실행될 쿼리(예상):  {$debugSql}`);</script>";

            $stmt = $this->db->prepare("INSERT INTO del_env (code_code_id, reser_date, work_potin, del_target, del_method, create_date, create_ip) VALUES (:code_id,:reser_date,:work_potin,:del_target,:temp_del,:create_date,:create_ip)");
            $stmt->bindParam(':code_id', $code_id);
            $stmt->bindParam(':reser_date', $reser_date);
            $stmt->bindParam(':work_potin', $work_potin);
            $stmt->bindParam(':del_target', $del_target);
            $stmt->bindParam(':temp_del', $temp_del);
            $stmt->bindParam(':create_date', $create_date);
            $stmt->bindParam(':create_ip', $create_ip);
            
            return $stmt->execute();
        }

        public function selectTempDelList(){
            $debugSql = "SELECT * FROM del_env";
            // echo "<script>console.log(`실행될 쿼리(예상):  {$debugSql}`);</script>";

            $stmt = $this->db->prepare("SELECT * FROM del_env");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>