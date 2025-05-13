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

        public function duplicateCheck($id){
            $sql = "SELECT count(*)
                    FROM admin 
                    WHERE id = :id";
            
            $params = [
                ':id' => $id
            ];

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

            // count 값 가져오기
            $count = $stmt->fetchColumn();
            return $count;
        }

        public function insertAdminInfo($id,$pw,$access_ip,$code_id){
            $create_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $create_ip = $_SERVER['REMOTE_ADDR'];
            $encodedPw = base64_encode($pw);
            
            $sql = "INSERT INTO admin(id,
                                      pw,
                                      admin_type,
                                      create_date,
                                      create_ip,
                                      access_ip,
                                      del_yn,
                                      code_code_id)
                            VALUES(:id,
                                   :encodedPw,
                                   '중간관리자',
                                   :create_date,
                                   :create_ip,
                                   :access_ip,
                                   'N',
                                   :code_id)";
            
            $params = [
                ':id' => $id,
                ':encodedPw' => $encodedPw,
                ':create_date' => $create_date,
                ':create_ip' => $create_ip,
                ':access_ip' => $access_ip,
                ':code_id' => $code_id
            ];

            $debugSql = $sql;
            foreach ($params as $key => $val) {
                $safeVal = is_numeric($val) ? $val : "'" . addslashes($val) . "'";
                $debugSql = str_replace($key, $safeVal, $debugSql);
            }

            // 콘솔로 출력 (브라우저 개발자 도구에서 확인)
            echo "<script>console.log(`실행될 쿼리 (예상): " . $debugSql . "`);</script>";

            // 쿼리 실행
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            
            return $stmt->execute();;
        }

        public function updateAdminInfo($id,$pw,$access_ip,$code_id){
            $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $update_ip = $_SERVER['REMOTE_ADDR'];
            $encodedPw = base64_encode($pw);
            
            $sql = "UPDATE admin
                    SET pw = :encodedPw,
                        access_ip = :access_ip,
                        code_code_id = :code_id,
                        update_date = :update_date,
                        update_ip = :update_ip
                    WHERE id = :id";
            
            $params = [
                ':id' => $id,
                ':encodedPw' => $encodedPw,
                ':update_date' => $update_date,
                ':update_ip' => $update_ip,
                ':access_ip' => $access_ip,
                ':code_id' => $code_id
            ];

            $debugSql = $sql;
            foreach ($params as $key => $val) {
                $safeVal = is_numeric($val) ? $val : "'" . addslashes($val) . "'";
                $debugSql = str_replace($key, $safeVal, $debugSql);
            }

            // 콘솔로 출력 (브라우저 개발자 도구에서 확인)
            echo "<script>console.log(`실행될 쿼리 (예상): " . $debugSql . "`);</script>";

            // 쿼리 실행
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            
            return $stmt->execute();;
        }

        public function adminInfoObj($id){
            $sql = "SELECT *
                    FROM admin 
                    WHERE id = :id";

            $params = [
                ':id' => $id
            ];

            // 콘솔로 출력 (브라우저 개발자 도구에서 확인)
            // echo "<script>console.log(`실행될 쿼리 (예상): " . $sql . "`);</script>";

            // 쿼리 실행
            $stmt = $this->db->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }

            $stmt->execute();
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // pw 필드가 존재하면 base64 디코딩
            if ($admin && isset($admin['pw'])) {
                $admin['pw_decoded'] = base64_decode($admin['pw']);
            }

            return $admin;
        }
    }
?>