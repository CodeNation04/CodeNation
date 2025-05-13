<?php
    require_once __DIR__ . '/../../config/database.php';

    class DeleteManage {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }
        
        public function insertDeleteManage($code_id,$exclude_path,$file_ext) {
            $create_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $create_ip = $_SERVER['REMOTE_ADDR'];

            $stmt = $this->db->prepare("INSERT INTO del_env (code_code_id, 
                                                            exclude_path, 
                                                            file_ext, 
                                                            del_yn, 
                                                            create_date, 
                                                            create_ip) 
                                                VALUES (:code_id,
                                                        :exclude_path,
                                                        :file_ext,
                                                        'N',
                                                        :create_date,
                                                        :create_ip)");
            $stmt->bindParam(':code_id', $code_id);
            $stmt->bindParam(':exclude_path', $exclude_path);
            $stmt->bindParam(':file_ext', $file_ext);
            $stmt->bindParam(':create_date', $create_date);
            $stmt->bindParam(':create_ip', $create_ip);
            
            return $stmt->execute();
        }

        public function updateDeleteManage($num,$code_id,$exclude_path,$file_ext) {
            $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $update_ip = $_SERVER['REMOTE_ADDR'];

            $stmt = $this->db->prepare("UPDATE del_env 
                                        SET code_code_id = :code_id,
                                            exclude_path = :exclude_path,
                                            file_ext = :file_ext,
                                            update_date = :update_date,
                                            update_ip =:update_ip
                                        WHERE del_idx = :num");

            $stmt->bindParam(':num', $num);
            $stmt->bindParam(':code_id', $code_id);
            $stmt->bindParam(':exclude_path', $exclude_path);
            $stmt->bindParam(':file_ext', $file_ext);
            $stmt->bindParam(':update_date', $update_date);
            $stmt->bindParam(':update_ip', $update_ip);
            
            return $stmt->execute();
        }
        
        public function selectDeleteManageList(){

            $stmt = $this->db->prepare("SELECT  a.del_idx,
                                                a.code_code_id,
                                                (SELECT code_name FROM code b WHERE b.code_id = a.code_code_id) AS code_name,
                                                a.file_ext,
                                                a.exclude_path,
                                                a.del_yn,
                                                a.create_date,
                                                a.create_ip
                                        FROM del_env a
                                        WHERE a.del_yn = 'N'
                                        ORDER BY del_idx DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function selectDeleteManageInfo($num){

            $stmt = $this->db->prepare("SELECT  del_idx,
                                                code_code_id,
                                                file_ext,
                                                exclude_path
                                        FROM del_env 
                                        WHERE del_yn = 'N'
                                        AND del_idx = :num");

            $stmt->bindParam(':num', $num);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function manageListdelete($num) {
            $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $update_ip = $_SERVER['REMOTE_ADDR'];

            $stmt = $this->db->prepare("UPDATE del_env 
                                        SET del_yn = 'Y',
                                            update_date = :update_date,
                                            update_ip =:update_ip
                                        WHERE del_idx = :num");

            $stmt->bindParam(':num', $num);
            $stmt->bindParam(':update_date', $update_date);
            $stmt->bindParam(':update_ip', $update_ip);
            
            return $stmt->execute();
        }
    }
?>
