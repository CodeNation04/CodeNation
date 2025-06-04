<?php

class DeleteManageController extends Controller {
    public function deleteManage(){
        $type = $_POST['type'] ?? '';
        $num = $_POST['num'] ?? 0;

        $code_id = $_POST['department'] ?? '';
        $exclude_path = $_POST['exclude_paths'] ?? '';
        $file_ext = $_POST['file_exts'] ?? '';

        if($type !== "moddify"){
            $temp = $this->model('DeleteManage')->insertDeleteManage($code_id,$exclude_path,$file_ext);
            $work_type = "암호화 환경 등록";
        }else{
            $temp = $this->model('DeleteManage')->updateDeleteManage($num,$code_id,$exclude_path,$file_ext);
            $work_type = "암호화 환경 수정";
        }

        session_start();
        $admin_id = $_SESSION['admin_id'];
        $admin_type = $_SESSION['admin_type'];
        $admin_code_id = $_SESSION['code_id'];

        if ($temp) {
            $work_info = "성공";
            $agentLog = $this->model('AgentUser')->insertAdminLog($admin_code_id,$admin_id,$admin_type,$work_type,$work_info);
            echo "<script>
                    alert('성공적으로 저정되었습니다.');
                    window.location.href='/?url=MainController/index&page=deleteData';
                </script>";
        } else {
            $work_info = "실패";
            $agentLog = $this->model('AgentUser')->insertAdminLog($admin_code_id,$admin_id,$admin_type,$work_type,$work_info);
            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=deleteData';</script>";
            exit;
        }

    }

    public function deleteManageList() {
        header('Content-Type: application/json');
        
        $temp = $this->model('DeleteManage')->selectDeleteManageList();
        echo json_encode($temp);
    }

    public function deleteManageInfo() {
        header('Content-Type: application/json');
        $num = $_GET['num'] ?? 0;
        
        $temp = $this->model('DeleteManage')->selectDeleteManageInfo($num);
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function manageListdelete() {
        $num = $_POST['num'] ?? 0;

        $temp = $this->model('DeleteManage')->manageListdelete($num);

        header('Content-Type: application/json');

        session_start();
        $admin_id = $_SESSION['admin_id'];
        $admin_type = $_SESSION['admin_type'];
        $code_id = $_SESSION['code_id'];
        $work_type = "암호화 환경 삭제";

        if ($temp) {
            $work_info = "성공";
            echo json_encode(["success" => true, "message" => "삭제되었습니다."]);
        } else {
            $work_info = "실패";
            echo json_encode(["success" => false, "message" => "데이터베이스 오류가 발생했습니다."]);
        }

        $agentLog = $this->model('AgentUser')->insertAdminLog($code_id,$admin_id,$admin_type,$work_type,$work_info);
    }
}