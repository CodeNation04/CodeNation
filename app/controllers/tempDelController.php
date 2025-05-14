<?php

class TempDelController extends Controller {
    public function tempDel() {
        $type = $_POST['type'] ?? '';
        $num = $_POST['num'] ?? 0;
        $code_id = $_POST['department'] ?? '';
        $reser_date = $_POST['period'] ?? '';
        // $work_potin = $_POST['schedules'] ?? '';
        // $del_target = $_POST['targets'] ?? '';
        $job_type = $_POST['job_type'] ?? '';
        $folder_path = $_POST['target_path'] ?? '';

        $once_datetime = $_POST['once_datetime'] ?? '';
        $once_datetime = str_replace('T', ' ', $once_datetime);

        $daily_time = $_POST['daily_time'] ?? '';

        $reser_date_week = $_POST['weekly_day'] ?? '';
        $weekly_time = $_POST['weekly_time'] ?? '';

        $reser_date_day = $_POST['monthly_day'] ?? '';
        $monthly_time = $_POST['monthly_time'] ?? '';

        $reser_date_time = $once_datetime ?: ($daily_time ?: ($weekly_time ?: ($monthly_time ?: '')));

        if($type !== "moddify"){
            $temp = $this->model('TempDel')->insertTempDel($code_id,$reser_date,$reser_date_week,$reser_date_day,$reser_date_time,$job_type,$folder_path);
        }else{
            $temp = $this->model('TempDel')->updateTempDel($num,$code_id,$reser_date,$reser_date_week,$reser_date_day,$reser_date_time,$job_type,$folder_path);
        }

        if ($temp) {
            echo "<script>
                    alert('성공적으로 저정되었습니다.');
                    window.location.href='/?url=MainController/index&page=task&tab=temp_delete';
                </script>";
        } else {

            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=task&tab=temp_delete';</script>";
            exit;
        }
    }

    public function tempListDelete() {
        $num = $_POST['num'] ?? 0;

        $temp = $this->model('TempDel')->deleteTemp($num);

        header('Content-Type: application/json');

        if ($temp) {
            echo json_encode(["success" => true, "message" => "삭제되었습니다."]);
        } else {
            echo json_encode(["success" => false, "message" => "데이터베이스 오류가 발생했습니다."]);
        }
    }

    public function tempDelList() {
        header('Content-Type: application/json');
        
        $temp = $this->model('TempDel')->selectTempDelList();
        echo json_encode($temp);
    }

    public function tempDelInfo() {
        $del_idx = $_GET['num'] ?? '';
        header('Content-Type: application/json');
        $temp = $this->model('TempDel')->selectTempDelInfo($del_idx);
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }
    
}