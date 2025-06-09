<?php

class ExportController extends Controller {
    public function exportList() {
        $code_id = $_GET["code_id"] ?? '';
        $host_name = $_GET["host_name"] ?? '';
        $user_name = $_GET["user_name"] ?? '';
        $externally = $_GET["externally"] ?? '';
        $exter_status = $_GET["exter_status"] ?? '';
        $admin_code_id = $_GET["admin_code_id"] ?? '';
        $admin_type = $_GET["admin_type"] ?? '';

        header('Content-Type: application/json');
        
        $temp = $this->model('Export')->selectExportList($host_name,$user_name,$externally,$exter_status,$admin_code_id,$admin_type);
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

        session_start();
        $admin_id = $_SESSION['admin_id'];
        $admin_type = $_SESSION['admin_type'];
        $code_id = $_SESSION['code_id'];
        $work_type = "외부 반출 요청 $status";

        if ($temp) {
            $work_info = "성공";
            echo json_encode(["success" => true, "message" => "${status}처리되었습니다."]);
        } else {
            $work_info = "실패";
            echo json_encode(["success" => false, "message" => "데이터베이스 오류가 발생했습니다."]);
        }

        $agentLog = $this->model('AgentUser')->insertAdminLog($code_id,$admin_id,$admin_type,$work_type,$work_info);
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