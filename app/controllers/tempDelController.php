<?php

class TempDelController extends Controller {
    public function tempDel() {
        $code_id = $_POST['department'] ?? '';
        $reser_date = $_POST['period'] ?? '';
        $work_potin = $_POST['schedules'] ?? '';
        $del_target = $_POST['targets'] ?? '';
        $temp_del = $_POST['temp_del'] ?? '';
        $weekly_day = $_POST['weekly_day'] ?? '';
        $weekly_time = $_POST['weekly_time'] ?? '';
        $monthly_day = $_POST['monthly_day'] ?? '';
        $monthly_day = $_POST['monthly_time'] ?? '';

        $temp = $this->model('TempDel')->insertTempDel($code_id,$reser_date,$work_potin,$del_target,$temp_del);

        if ($temp) {
            echo "<script>
                    alert('성공적으로 저정되었습니다.');
                    window.location.href='/?url=MainController/index&page=task&tab=temp_delete';
                </script>";
        } else {
            http_response_code(401);
            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=task&tab=temp_delete';</script>";
            exit;
        }
    }

    public function tempDelList() {
        header('Content-Type: application/json');
        $temp = $this->model('TempDel')->selectTempDelList();
        echo json_encode($temp);
    }
}