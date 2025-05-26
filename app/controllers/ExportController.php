<?php

class ExportController extends Controller {
    public function exportList() {
        $code_id = $_GET["code_id"] ?? '';
        $host_name = $_GET["host_name"] ?? '';
        $user_name = $_GET["user_name"] ?? '';
        $externally = $_GET["externally"] ?? '';
        $exter_status = $_GET["exter_status"] ?? '';

        header('Content-Type: application/json');
        
        $temp = $this->model('Export')->selectExportList($code_id,$host_name,$user_name,$externally,$exter_status);
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function exportStatusReq() {
        $id = $_POST["id"] ?? 0;
        $status = $_POST["status"] ?? '';
        
        $temp = $this->model('Export')->exportStatusReq($id,$status);

        header('Content-Type: application/json');

        if ($temp) {
            echo json_encode(["success" => true, "message" => "${status}처리되었습니다."]);
        } else {
            echo json_encode(["success" => false, "message" => "데이터베이스 오류가 발생했습니다."]);
        }
    }

    public function exportCnt() {
        $exter_status = $_GET["exter_status"] ?? '';
        $date = $_GET["exter_date"] ?? '';

        header('Content-Type: application/json');
        
        $temp = $this->model('Export')->exportCnt($exter_status,$date);
        
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

}