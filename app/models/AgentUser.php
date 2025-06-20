<?php
    require_once __DIR__ . '/../../config/database.php';

    class AgentUser {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function insertAgentUser($code_id,$name,$phone,$email,$etc){
            $create_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $create_ip = $_SERVER['REMOTE_ADDR'];
            
            $sql = "INSERT INTO user_agent_info(code_code_id,
                                                user_name,
                                                user_phone,
                                                user_email,
                                                etc,
                                                create_ip,
                                                create_date,
                                                del_yn,
                                                user_type)
                            VALUES(:code_id,
                                   :name,
                                   :phone,
                                   :email,
                                   :etc,
                                   :create_ip,
                                   :create_date,
                                   'N',
                                   '팀장')";
            
            $params = [
                ':code_id' => $code_id,
                ':name' => $name,
                ':phone' => $phone,
                ':email' => $email,
                ':etc' => $etc,
                ':create_ip' => $create_ip,
                ':create_date' => $create_date
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

        public function updateAgentUser($num,$code_id,$name,$phone,$email,$etc){
            $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $update_ip = $_SERVER['REMOTE_ADDR'];
            
            $sql = "UPDATE user_agent_info
                    SET code_code_id = :code_id,
                        user_name = :name,
                        user_phone = :phone,
                        user_email = :email,
                        etc = :etc,
                        update_ip = :update_ip,
                        update_date = :update_date
                    WHERE user_idx = :num";
            
            $params = [
                'num' => $num,
                ':code_id' => $code_id,
                ':name' => $name,
                ':phone' => $phone,
                ':email' => $email,
                ':etc' => $etc,
                ':update_ip' => $update_ip,
                ':update_date' => $update_date
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

        public function selectAgentUserList(){

            $stmt = $this->db->prepare("SELECT  a.user_idx,
                                                a.code_code_id,
                                                (SELECT code_name FROM code b WHERE b.code_id = a.code_code_id) AS code_name,
                                                a.user_id,
                                                a.user_ip,
                                                a.host_name,
                                                a.user_name,
                                                a.user_phone,
                                                a.user_email,
                                                a.user_type,
                                                a.etc,
                                                a.del_yn,
                                                a.update_date
                                        FROM user_agent_info a
                                        WHERE a.del_yn = 'N'
                                        ORDER BY a.user_idx DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function selectAgentUserInfo($num){

            $stmt = $this->db->prepare("SELECT  *
                                        FROM user_agent_info
                                        WHERE user_idx = :num");

            $stmt->bindParam(':num', $num);                            
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function agentUserDel($num){
            $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $update_ip = $_SERVER['REMOTE_ADDR'];

            $sql = "UPDATE user_agent_info
                    SET del_yn = 'Y',
                        update_date = :update_date,
                        update_ip = :update_ip
                    WHERE user_idx = :num";
            
            $params = [
                ':num' => $num,
                ':update_date' => $update_date,
                ':update_ip' => $update_ip
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
            
            return $stmt->execute();;
        }

        public function selectDeptList(){

            $stmt = $this->db->prepare("SELECT  *
                                        FROM code
                                        WHERE sub_id = 'department'
                                        AND del_yn = 'N'
                                        ORDER BY create_date DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insertDeptInfo($code_name,$code_id){
            $create_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $create_ip = $_SERVER['REMOTE_ADDR'];
            
            $sql = "INSERT INTO code(code_id,
                                    code_name,
                                    sub_id,
                                    create_ip,
                                    create_date)
                            VALUES(:code_id,
                                   :code_name,
                                   'department',
                                   :create_ip,
                                   :create_date)";
            
            $params = [
                ':code_id' => $code_id,
                ':code_name' => $code_name,
                ':create_ip' => $create_ip,
                ':create_date' => $create_date
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

        public function updateDeptInfo($code_name,$code_id){
            $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $update_ip = $_SERVER['REMOTE_ADDR'];
            
            $sql = "UPDATE code
                    SET code_name = :code_name,
                        update_ip = :update_ip,
                        update_date = :update_date
                    WHERE code_id = :code_id";
            
            $params = [
                ':code_id' => $code_id,
                ':code_name' => $code_name,
                ':update_ip' => $update_ip,
                ':update_date' => $update_date
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

        public function selectdeptInfo($num){

            $stmt = $this->db->prepare("SELECT *
                                        FROM code
                                        WHERE code_id = :num");

            $stmt->bindParam(':num', $num);                            
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function deptInfoDel($code_id){
            $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $update_ip = $_SERVER['REMOTE_ADDR'];

            $sql = "UPDATE code
                    SET del_yn = 'Y'
                    WHERE code_id = :code_id";
            
            $params = [
                ':code_id' => $code_id
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
            
            return $stmt->execute();;
        }

        public function countAgentUser($hostname,$ip,$username){

            $stmt = $this->db->prepare("SELECT count(*)
                                        FROM user_agent_info
                                        WHERE host_name = :hostname
                                        AND user_name = :username
                                        AND user_ip = :ip");
            $stmt->bindParam(':hostname', $hostname);   
            $stmt->bindParam(':username', $username);     
            $stmt->bindParam(':ip', $ip);                      
            $stmt->execute();
            return $stmt->fetchColumn();
        }

        public function insertAgentUserData($hostname,$ip,$username,$token,$code_id){
            $create_ip = $_SERVER['REMOTE_ADDR'];
            
            $sql = "INSERT INTO user_agent_info(user_id,
                                                user_name,
                                                user_ip,
                                                host_name,
                                                code_code_id,
                                                create_ip,
                                                del_yn,
                                                create_date,
                                                update_date
                                                )
                            VALUES(:token,
                                   :username,
                                   :ip,
                                   :hostname,
                                   :code_id,
                                   :create_ip,
                                   'N',
                                   now(),
                                   now())";
            
            $params = [
                ':hostname' => $hostname,
                ':ip' => $ip,
                ':username' => $username,
                ':token' => $token,
                ':code_id' => $code_id,
                ':create_ip' => $create_ip
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
            
            return $stmt->execute();;
        }
        
        public function updateAgentUserData($hostname,$ip,$username,$code_id){
            $update_ip = $_SERVER['REMOTE_ADDR'];
            
            $sql = "UPDATE user_agent_info
                    SET code_code_id = :code_id,
                        update_ip = :update_ip,
                        update_date = now()
                    WHERE user_name = :username
                    AND user_ip = :ip
                    AND host_name = :hostname";
            
            $params = [
                ':hostname' => $hostname,
                ':code_id' => $code_id,
                ':ip' => $ip,
                ':username' => $username,
                ':update_ip' => $update_ip
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
            
            return $stmt->execute();;
        }

        public function selectAgentUserData($hostname,$ip,$username){

            $stmt = $this->db->prepare("SELECT  a.code_code_id,
                                                (SELECT code_name FROM code b WHERE b.code_id = a.code_code_id) AS code_name,
                                                a.user_id,
                                                a.user_ip,
                                                a.host_name,
                                                a.user_name
                                        FROM user_agent_info a
                                        WHERE a.host_name = :hostname
                                        AND a.user_ip = :ip
                                        AND a.user_name = :username");
            $stmt->bindParam(':hostname', $hostname);
            $stmt->bindParam(':ip', $ip);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function insertAgentLog($hostname,$ip,$username,$token,$work_type,$work_result,$work_info,$code_id){  
            $sql = "INSERT INTO user_agent_log(user_id,
                                                user_name,
                                                host_name,
                                                work_type,
                                                work_result,
                                                work_info,
                                                code_code_id,
                                                create_date
                                                )
                            VALUES(:token,
                                   :username,
                                   :hostname,
                                   :work_type,
                                   :work_result,
                                   :work_info,
                                   :code_id,
                                   now())";
            
            $params = [
                ':hostname' => $hostname,
                ':code_id' => $code_id,
                ':username' => $username,
                ':work_type' => $work_type,
                ':work_result' => $work_result,
                ':work_info' => $work_info,
                ':token' => $token
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
            
            return $stmt->execute();;
        }

        public function selectLogList(){

            $stmt = $this->db->prepare("SELECT  a.code_code_id,
                                                (SELECT code_name FROM code b WHERE b.code_id = a.code_code_id) AS code_name,
                                                a.user_id,
                                                a.host_name,
                                                a.user_name,
                                                a.work_type,
                                                a.work_result,
                                                a.work_info,
                                                a.create_date
                                        FROM user_agent_log a
                                        ORDER BY create_date DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insertAdminLog($code_id,$admin_id,$admin_type,$work_type,$work_info){  
            $create_ip = $_SERVER['REMOTE_ADDR'];

            $sql = "INSERT INTO appreciation_log(code_code_id,
                                                admin_id,
                                                admin_type,
                                                work_type,
                                                work_info,
                                                create_date,
                                                create_ip
                                                )
                            VALUES(:code_id,
                                   :admin_id,
                                   :admin_type,
                                   :work_type,
                                   :work_info,
                                   NOW(),
                                   :create_ip)";
            
            $params = [
                ':code_id' => $code_id,
                ':admin_id' => $admin_id,
                ':admin_type' => $admin_type,
                ':work_type' => $work_type,
                ':work_info' => $work_info,
                ':create_ip' => $create_ip
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
            
            return $stmt->execute();;
        }

        public function selectAdminLogList(){

            $stmt = $this->db->prepare("SELECT  a.code_code_id,
                                                (SELECT code_name FROM code b WHERE b.code_id = a.code_code_id) AS code_name,
                                                a.admin_id,
                                                a.admin_type,
                                                a.work_type,
                                                a.work_info,
                                                a.create_date
                                        FROM appreciation_log a
                                        ORDER BY create_date DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

       
        public function loginCnt($user_type,$date){
            if($user_type !== '전체'){
                if($user_type === '중간관리자'){
                    $table = 'appreciation_log';
                    $condition = "admin_type = '중간관리자' AND work_type = '로그인'";
                }else{
                    $table = 'user_agent_log';
                    $condition = "work_info = '로그인'";
                }

                if($date === 'year'){
                    $sql = "SELECT YEAR(create_date) AS period, COUNT(*) AS count FROM {$table} WHERE {$condition} AND create_date >= DATE_SUB(CURDATE(), INTERVAL 15 YEAR) GROUP BY YEAR(create_date) ORDER BY period DESC";
                }else if($date === 'month'){
                    $sql = "SELECT DATE_FORMAT(create_date, '%Y-%m') AS period, COUNT(*) AS count FROM {$table} WHERE {$condition} AND create_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) GROUP BY DATE_FORMAT(create_date, '%Y-%m') ORDER BY period DESC";
                }else{
                    $sql = "SELECT DATE(create_date) AS period, COUNT(*) AS count FROM {$table} WHERE {$condition} AND create_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(create_date) ORDER BY period DESC";
                }

                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                if($date === 'year'){
                    $sql = "SELECT period, SUM(cnt) AS count FROM (
                                SELECT YEAR(create_date) AS period, COUNT(*) AS cnt FROM appreciation_log WHERE work_type = '로그인' AND create_date >= DATE_SUB(CURDATE(), INTERVAL 15 YEAR) GROUP BY YEAR(create_date)
                                UNION ALL
                                SELECT YEAR(create_date) AS period, COUNT(*) AS cnt FROM user_agent_log WHERE work_info = '로그인' AND create_date >= DATE_SUB(CURDATE(), INTERVAL 15 YEAR) GROUP BY YEAR(create_date)
                            ) AS t GROUP BY period ORDER BY period DESC";
                }else if($date === 'month'){
                    $sql = "SELECT period, SUM(cnt) AS count FROM (
                                SELECT DATE_FORMAT(create_date, '%Y-%m') AS period, COUNT(*) AS cnt FROM appreciation_log WHERE work_type = '로그인' AND create_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) GROUP BY DATE_FORMAT(create_date, '%Y-%m')
                                UNION ALL
                                SELECT DATE_FORMAT(create_date, '%Y-%m') AS period, COUNT(*) AS cnt FROM user_agent_log WHERE work_info = '로그인' AND create_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) GROUP BY DATE_FORMAT(create_date, '%Y-%m')
                            ) AS t GROUP BY period ORDER BY period DESC";
                }else{
                    $sql = "SELECT period, SUM(cnt) AS count FROM (
                                SELECT DATE(create_date) AS period, COUNT(*) AS cnt FROM appreciation_log WHERE work_type = '로그인' AND create_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(create_date)
                                UNION ALL
                                SELECT DATE(create_date) AS period, COUNT(*) AS cnt FROM user_agent_log WHERE work_info = '로그인' AND create_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(create_date)
                            ) AS t GROUP BY period ORDER BY period DESC";
                }

                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
?>