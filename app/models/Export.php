<?php
    require_once __DIR__ . '/../../config/database.php';

    class Export {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function selectExportList($host_name,$user_name,$externally,$exter_status,$admin_code_id,$admin_type){

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

            if($admin_type !== "최고관리자"){
                if ($admin_code_id !== '') {
                    $sql .= " AND code_code_id = :admin_code_id";
                    $params[':admin_code_id'] = $admin_code_id;
                }
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

        public function exportCnt($exter_status,$date) {
            if ($date == "year") {
                // 최근 15년간 년도별 카운트
                $stmt = $this->db->prepare("
                    SELECT YEAR(create_date) AS period, COUNT(*) AS count
                    FROM externally_info
                    WHERE exter_status = :exter_status
                    AND create_date >= DATE_SUB(CURDATE(), INTERVAL 15 YEAR)
                    GROUP BY YEAR(create_date)
                    ORDER BY period DESC
                ");
            } else if ($date == "month") {
                // 최근 1년간 월별 카운트
                $stmt = $this->db->prepare("
                    SELECT DATE_FORMAT(create_date, '%Y-%m') AS period, COUNT(*) AS count
                    FROM externally_info
                    WHERE exter_status = :exter_status
                    AND create_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
                    GROUP BY DATE_FORMAT(create_date, '%Y-%m')
                    ORDER BY period DESC
                ");
            } else {
                // 최근 1주일간 일별 카운트
                $stmt = $this->db->prepare("
                    SELECT DATE(create_date) AS period, COUNT(*) AS count
                    FROM externally_info
                    WHERE exter_status = :exter_status
                    AND create_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    GROUP BY DATE(create_date)
                    ORDER BY period DESC
                ");
            }

            // 바인딩 예시
            $stmt->bindParam(':exter_status', $exter_status);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>