<?php

class AdminInfoController extends Controller {
    public function adminInfoList() {
        header('Content-Type: application/json');
        $temp = $this->model('AdminInfo')->adminInfoList();
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function duplicateCheck(){
        $id = $_POST['id'] ?? 0;

        $temp = $this->model('AdminInfo')->duplicateCheck($id);

        header('Content-Type: application/json');
        
        if ($temp == 0) {
            echo json_encode(["success" => true, "message" => "사용가능한 ID입니다."]);
        } else {
            echo json_encode(["success" => false, "message" => "중복된 ID입니다."]);
        }
    }

    public function adminInfo() {
        $type = $_POST['type'] ?? '';
        $id = $_POST['admin_id'] ?? '';
        $pw = $_POST['pw'] ?? '';
        $access_ip = $_POST['ip'] ?? '';
        $code_id = $_POST['department'] ?? '';

        if($type !== "moddify"){
            $temp = $this->model('AdminInfo')->insertAdminInfo($id,$pw,$access_ip,$code_id);
        }else{
            $temp = $this->model('AdminInfo')->updateAdminInfo($id,$pw,$access_ip,$code_id);
        }

        session_start();
        $admin_id = $_SESSION['admin_id'];
        $admin_type = $_SESSION['admin_type'];
        $admin_code_id = $_SESSION['code_id'];

        if ($temp) {

            $work_info = "성공";
            if($type !== "moddify"){
                $work_type = "중간관리자 등록";
            }else{
                $work_type = "중간관리자 수정";
            }

            $agentLog = $this->model('AgentUser')->insertAdminLog($admin_code_id,$admin_id,$admin_type,$work_type,$work_info);

            echo "<script>
                    alert('성공적으로 저장되었습니다.');
                    window.location.href='/?url=MainController/index&page=admin';
                </script>";
        } else {
            $work_info = "실패";
            $agentLog = $this->model('AgentUser')->insertAdminLog($admin_code_id,$admin_id,$admin_type,$work_type,$work_info);
            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=admin';</script>";
            exit;
        }
    }

    public function adminInfoObj(){
        $id = $_GET['id'] ?? '';

        $temp = $this->model('AdminInfo')->adminInfoObj($id);

        header('Content-Type: application/json');
        
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function adminInfoDelete(){
        $id = $_POST['id'] ?? '';

        $temp = $this->model('AdminInfo')->adminInfoDelete($id);

        header('Content-Type: application/json');

        session_start();
        $admin_id = $_SESSION['admin_id'];
        $admin_type = $_SESSION['admin_type'];
        $code_id = $_SESSION['code_id'];
        $work_type = "중간관리자 삭제";

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