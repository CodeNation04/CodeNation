<?php

class AgentUserController extends Controller {
    public function agentUserSubmit() {
        $type = $_POST['type'] ?? '';
        $num = $_POST['num'] ?? 0;
        $code_id = $_POST['department'] ?? '';
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $etc = $_POST['etc'] ?? '';

        if($type !== "moddify"){
            $temp = $this->model('AgentUser')->insertAgentUser($code_id,$name,$phone,$email,$etc);
        }else{
            $temp = $this->model('AgentUser')->updateAgentUser($num,$code_id,$name,$phone,$email,$etc);
        }

        if ($temp) {
            echo "<script>
                    alert('성공적으로 저정되었습니다.');
                    window.location.href='/?url=MainController/index&page=dept';
                </script>";
        } else {
            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=dept';</script>";
            exit;
        }
    }

    public function agentUserList() {
        header('Content-Type: application/json');
        
        $temp = $this->model('AgentUser')->selectAgentUserList();
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function agentUserInfo() {
        $num = $_GET['num'] ?? '';
        header('Content-Type: application/json');
        
        $temp = $this->model('AgentUser')->selectAgentUserInfo($num);
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function agentUserDel(){
        $num = $_POST['num'] ?? '';

        $temp = $this->model('AgentUser')->agentUserDel($num);

        header('Content-Type: application/json');

        if ($temp) {
            echo json_encode(["success" => true, "message" => "삭제되었습니다."]);
        } else {
            echo json_encode(["success" => false, "message" => "데이터베이스 오류가 발생했습니다."]);
        }
    }

}