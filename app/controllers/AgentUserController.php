<?php

class AgentUserController extends Controller {
    public function agentUserSubmit() {
        $type = $_POST['type'] ?? '';
        $code_id = $_POST['department'] ?? '';
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $etc = $_POST['etc'] ?? '';

        if($type !== "moddify"){
            $temp = $this->model('AgentUser')->insertAgentUser($code_id,$name,$phone,$email,$etc);
        }else{
            $temp = $this->model('AgentUser')->updateAgentUser($code_id,$name,$phone,$email,$etc);
        }

        if ($temp) {
            echo "<script>
                    alert('성공적으로 저정되었습니다.');
                    window.location.href='/?url=MainController/index&page=dept';
                </script>";
        } else {
            http_response_code(401);
            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=dept';</script>";
            exit;
        }
    }

    public function agentUserList() {
        header('Content-Type: application/json');
        
        $temp = $this->model('AgentUser')->selectagentUserList();
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

}