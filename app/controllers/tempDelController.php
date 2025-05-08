<?php

class tempDelController extends Controller {
    public function tempDel() {
        $code_id = $_POST['department'] ?? '';
        $reser_date = $_POST['period'] ?? '';
        $work_potin = $_POST['schedule'] ?? '';
        $del_target = $_POST['targets'] ?? '';
        $temp_del = $_POST['temp_del'] ?? '';

        $temp = $this->model('TempDel')->insertTempDel($code_id,$reser_date,$work_potin,$del_target,$temp_del);

        if ($temp) {
            echo "<script>
                    alert('성공적으로 저정되었습니다.');
                    window.location.href='/?url=MainController/login&page=task&tab=temp_delete';
                </script>";
        } else {
            http_response_code(401);
            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/login&page=task&tab=temp_delete';</script>";
            exit;
        }
    }
}