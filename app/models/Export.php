<?php
    require_once __DIR__ . '/../../config/database.php';

    class Export {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function selectExportList($code_id,$host_name,$user_name,$externally,$exter_status){

            $sql = "SELECT  a.exter_idx,
                            a.code_code_id,
                            (SELECT code_name FROM code b WHERE b.code_id = a.code_code_id) AS code_name,
                            a.host_name,
                            a.user_name,
                            a.externally,
                            a.reason,
                            a.exter_status
                    FROM externally_info a
                    WHERE 1=1";
                    
            // 디버깅용 바인딩 파라미터 배열
            $params = [];

            if ($code_id !== '') {
                $sql .= " AND code_code_id = :code_id";
                $params[':code_id'] = $code_id;
            }

            if ($host_name !== '') {
                $sql .= " AND host_name LIKE :host_name";
                $params[':host_name'] = '%' . $host_name . '%';
            }

            if ($user_name !== '') {
                $sql .= " AND user_name LIKE :user_name";
                $params[':user_name'] = '%' . $user_name . '%';
            }

            if ($externally !== '') {
                $sql .= " AND externally LIKE :externally";
                $params[':externally'] = '%' . $externally . '%';
            }

            if ($exter_status !== '') {
                $sql .= " AND exter_status = :exter_status";
                $params[':exter_status'] = $exter_status;
            }

            $sql .= " ORDER BY a.exter_idx DESC";

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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function exportStatusReq($id,$status){
            $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $update_ip = $_SERVER['REMOTE_ADDR'];

            $sql = "UPDATE externally_info
                    SET exter_status = :status,
                        update_date = :update_date,
                        update_ip = :update_ip
                    WHERE exter_idx = :id";
            
            $params = [
                ':id' => $id,
                ':status' => $status,
                ':update_date' => $update_date,
                ':update_ip' => $update_ip
            ];

            // 쿼리 실행
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            
            return $stmt->execute();;
        }
    }
?>