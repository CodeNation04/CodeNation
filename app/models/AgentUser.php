<?php
    require_once __DIR__ . '/../../config/database.php';

    class AgentUser {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        // public function insertAgentUser($code_id,$name,$phone,$email,$etc){
        //     $create_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
        //     $create_ip = $_SERVER['REMOTE_ADDR'];
            
        //     $sql = "INSERT INTO user_agent_info(code_code_id,
        //                                         user_name,
        //                                         user_phone,
        //                                         user_email,
        //                                         etc,
        //                                         create_ip,
        //                                         create_date,
        //                                         del_yn,
        //                                         user_type)
        //                     VALUES(:code_id,
        //                            :name,
        //                            :phone,
        //                            :email,
        //                            :etc,
        //                            :create_ip,
        //                            :create_date,
        //                            'N',
        //                            '팀장')";
            
        //     $params = [
        //         ':code_id' => $code_id,
        //         ':name' => $name,
        //         ':phone' => $phone,
        //         ':email' => $email,
        //         ':etc' => $etc,
        //         ':create_ip' => $create_ip,
        //         ':create_date' => $create_date
        //     ];

        //     $debugSql = $sql;
        //     foreach ($params as $key => $val) {
        //         $safeVal = is_numeric($val) ? $val : "'" . addslashes($val) . "'";
        //         $debugSql = str_replace($key, $safeVal, $debugSql);
        //     }

        //     // 콘솔로 출력 (브라우저 개발자 도구에서 확인)
        //     echo "<script>console.log(`실행될 쿼리 (예상): " . $debugSql . "`);</script>";

        //     // 쿼리 실행
        //     $stmt = $this->db->prepare($sql);
        //     foreach ($params as $key => $val) {
        //         $stmt->bindValue($key, $val);
        //     }
            
        //     return $stmt->execute();;
        // }

        // public function updateAgentUser($num,$code_id,$name,$phone,$email,$etc){
        //     $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
        //     $update_ip = $_SERVER['REMOTE_ADDR'];
            
        //     $sql = "UPDATE user_agent_info
        //             SET code_code_id = :code_id,
        //                 user_name = :name,
        //                 user_phone = :phone,
        //                 user_email = :email,
        //                 etc = :etc,
        //                 update_ip = :update_ip,
        //                 update_date = :update_date
        //             WHERE user_idx = :num";
            
        //     $params = [
        //         'num' => $num,
        //         ':code_id' => $code_id,
        //         ':name' => $name,
        //         ':phone' => $phone,
        //         ':email' => $email,
        //         ':etc' => $etc,
        //         ':update_ip' => $update_ip,
        //         ':update_date' => $update_date
        //     ];

        //     $debugSql = $sql;
        //     foreach ($params as $key => $val) {
        //         $safeVal = is_numeric($val) ? $val : "'" . addslashes($val) . "'";
        //         $debugSql = str_replace($key, $safeVal, $debugSql);
        //     }

        //     // 콘솔로 출력 (브라우저 개발자 도구에서 확인)
        //     echo "<script>console.log(`실행될 쿼리 (예상): " . $debugSql . "`);</script>";

        //     // 쿼리 실행
        //     $stmt = $this->db->prepare($sql);
        //     foreach ($params as $key => $val) {
        //         $stmt->bindValue($key, $val);
        //     }
            
        //     return $stmt->execute();;
        // }

        public function selectAgentUserList(){

            $stmt = $this->db->prepare("SELECT  a.user_idx,
                                                a.code_code_id,
                                                (SELECT code_name FROM code b WHERE b.code_id = a.code_code_id) AS code_name,
                                                a.user_id,
                                                a.user_name,
                                                a.user_phone,
                                                a.user_email,
                                                a.user_type,
                                                a.etc,
                                                a.del_yn
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
                                        WHERE user_id = :hostname
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
                                                create_date
                                                )
                            VALUES(:token,
                                   :username,
                                   :ip,
                                   :hostname,
                                   :code_id,
                                   :create_ip,
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

        public function updateAgentUserData($hostname,$ip,$username,$token,$code_id){
            $update_ip = $_SERVER['REMOTE_ADDR'];
            
            $sql = "UPDATE user_agent_info
                    SET user_id = :token,
                        code_code_id = :code_id,
                        update_ip = :update_ip,
                        update_date = now()
                    WHERE user_name = :username
                    AND user_ip = :ip
                    AND host_name = :hostname";
            
            $params = [
                'hostname' => $hostname,
                ':ip' => $ip,
                ':username' => $username,
                ':token' => $token,
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
    }
?>