<?php
    require_once __DIR__ . '/../../config/database.php';

    class AdminInfo {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function adminInfoList(){
            $sql = "SELECT a.id,
                           a.pw,
                           a.admin_type,
                           a.access_ip,
                           (SELECT code_name FROM code b WHERE b.code_id = a.code_code_id) AS code_name
                    FROM admin a
                    WHERE a.admin_type = '중간관리자'
                    ORDER BY a.create_date DESC";
            

            // 콘솔로 출력 (브라우저 개발자 도구에서 확인)
            // echo "<script>console.log(`실행될 쿼리 (예상): " . $sql . "`);</script>";

            // 쿼리 실행
            $stmt = $this->db->prepare($sql);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>