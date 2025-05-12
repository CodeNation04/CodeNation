<?php
    require_once __DIR__ . '/../../config/database.php';

    class DeleteManage {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }
e
        public function insertTempDel($code_id,$overwrite_cnt,$set_change_yn,$del_method) {
            $create_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $create_ip = $_SERVER['REMOTE_ADDR'];

            $stmt = $this->db->prepare("INSERT INTO del_env (code_code_id, 
                                                            overwrite_cnt, 
                                                            set_change_yn, 
                                                            del_method, 
                                                            del_yn, 
                                                            create_date, 
                                                            create_ip) 
                                                VALUES (:code_id,
                                                        :overwrite_cnt,
                                                        :set_change_yn,
                                                        :del_method,
                                                        'N',
                                                        :create_date,
                                                        :create_ip)");
            $stmt->bindParam(':code_id', $code_id);
            $stmt->bindParam(':overwrite_cnt', $overwrite_cnt);
            $stmt->bindParam(':set_change_yn', $set_change_yn);
            $stmt->bindParam(':del_method', $del_method);
            $stmt->bindParam(':create_date', $create_date);
            $stmt->bindParam(':create_ip', $create_ip);
            
            return $stmt->execute();
        }

        public function updateTempDel($num,$code_id,$overwrite_cnt,$set_change_yn,$del_method) {
            $update_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $update_ip = $_SERVER['REMOTE_ADDR'];

            $stmt = $this->db->prepare("UPDATE del_env 
                                        SET code_code_id = :code_id,
                                            overwrite_cnt = :overwrite_cnt,
                                            set_change_yn = :set_change_yn,
                                            del_method = :del_method,
                                            update_date :update_date,
                                            update_ip:update_ip
                                        WHERE del_idx = :num");

            $stmt->bindParam(':num', $num);
            $stmt->bindParam(':code_id', $code_id);
            $stmt->bindParam(':overwrite_cnt', $overwrite_cnt);
            $stmt->bindParam(':set_change_yn', $set_change_yn);
            $stmt->bindParam(':del_method', $del_method);
            $stmt->bindParam(':update_date', $update_date);
            $stmt->bindParam(':update_ip', $update_ip);
            
            return $stmt->execute();
        }

    }
?>
