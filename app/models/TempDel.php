<?php
    require_once __DIR__ . '/../../config/database.php';

    class TempDel {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->pdo;
        }

        public function insertTempDel($code_id,$reser_date,$work_potin,$del_target,$temp_del,$once_date,$reser_date_week,$reser_date_day,$reser_date_time) {
            $create_date = date('Y-m-d H:i:s'); // 현재 날짜와 시간
            $create_ip = $_SERVER['REMOTE_ADDR'];

            $stmt = $this->db->prepare("INSERT INTO del_env (code_code_id, 
                                                            reser_date, 
                                                            reser_date_ymd, 
                                                            reser_date_day, 
                                                            reser_date_time, 
                                                            reser_date_week, 
                                                            work_potin, 
                                                            del_target, 
                                                            del_method, 
                                                            del_yn, 
                                                            create_date, 
                                                            create_ip) 
                                                VALUES (:code_id,
                                                        :reser_date,
                                                        :reser_date_ymd,
                                                        :reser_date_day,
                                                        :reser_date_time,
                                                        :reser_date_week,
                                                        :work_potin,
                                                        :del_target,
                                                        :temp_del,
                                                        'N',
                                                        :create_date,
                                                        :create_ip)");
            $stmt->bindParam(':code_id', $code_id);
            $stmt->bindParam(':reser_date', $reser_date);
            $stmt->bindParam(':reser_date_ymd', $once_date);
            $stmt->bindParam(':reser_date_week', $reser_date_week);
            $stmt->bindParam(':reser_date_day', $reser_date_day);
            $stmt->bindParam(':reser_date_time', $reser_date_time);
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

            $stmt = $this->db->prepare("SELECT  a.del_idx,
                                                a.code_code_id,
                                                (SELECT code_name FROM code b WHERE b.code_id = a.code_code_id) AS code_name,
                                                a.reser_date,
                                                a.reser_date_ymd,
                                                a.reser_date_day,
                                                a.reser_date_time,
                                                a.reser_date_week,
                                                a.work_potin,
                                                a.del_target,
                                                a.set_change_yn,
                                                a.del_method,
                                                a.del_yn,
                                                a.overwrite_cnt,
                                                a.create_date,
                                                a.create_ip
                                        FROM del_env a
                                        WHERE a.del_yn = 'N'
                                        ORDER BY del_idx DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>