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

        public function adminInfo($id) {
            $sql = "SELECT *
                    FROM admin
                    WHERE id = :id";
            
            // 디버깅용 바인딩 파라미터 배열
            $params = [
                ':id' => $id
            ];

            // 예상 SQL 생성 (디버깅용)
            $debugSql = $sql;
            foreach ($params as $key => $val) {
                $safeVal = is_numeric($val) ? $val : "'" . addslashes($val) . "'";
                $debugSql = str_replace($key, $safeVal, $debugSql);
            }

            // 콘솔로 출력 (브라우저 개발자 도구에서 확인)
            // echo "<script>console.log(`실행될 쿼리 (예상): " . $debugSql . "`);</script>";

            // 쿼리 실행
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
?>