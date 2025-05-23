<?php

class AgentUserController extends Controller {
    // public function agentUserSubmit() {
    //     $type = $_POST['type'] ?? '';
    //     $num = $_POST['num'] ?? 0;
    //     $code_id = $_POST['department'] ?? '';
    //     $name = $_POST['name'] ?? '';
    //     $phone = $_POST['phone'] ?? '';
    //     $email = $_POST['email'] ?? '';
    //     $etc = $_POST['etc'] ?? '';

    //     if($type !== "moddify"){
    //         $temp = $this->model('AgentUser')->insertAgentUser($code_id,$name,$phone,$email,$etc);
    //     }else{
    //         $temp = $this->model('AgentUser')->updateAgentUser($num,$code_id,$name,$phone,$email,$etc);
    //     }

    //     if ($temp) {
    //         echo "<script>
    //                 alert('성공적으로 저장되었습니다.');
    //                 window.location.href='/?url=MainController/index&page=dept';
    //             </script>";
    //     } else {
    //         echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=dept';</script>";
    //         exit;
    //     }
    // }

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

    public function selectDeptList(){
        header('Content-Type: application/json');
        
        $temp = $this->model('AgentUser')->selectDeptList();
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function deptInfoAdd(){
        $type = $_POST['type'] ?? '';
        $code_name = $_POST['dept_name'] ?? '';
        $code_id = $_POST['dept_code'] ?? '';

        if($type !== "moddify"){
            $temp = $this->model('AgentUser')->insertDeptInfo($code_name,$code_id);
        }else{
            $temp = $this->model('AgentUser')->updateDeptInfo($code_name,$code_id);
        }

        if ($temp) {
            echo "<script>
                    alert('성공적으로 저정되었습니다.');
                    window.location.href='/?url=MainController/index&page=department';
                </script>";
        } else {
            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=department';</script>";
            exit;
        }
    }

    public function deptInfo() {
        $num = $_GET['num'] ?? '';
        header('Content-Type: application/json');
        
        $temp = $this->model('AgentUser')->selectdeptInfo($num);
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function deptInfoDel(){
        $code_id = $_POST['num'] ?? '';

        $temp = $this->model('AgentUser')->deptInfoDel($code_id);

        header('Content-Type: application/json');

        if ($temp) {
            echo json_encode(["success" => true, "message" => "삭제되었습니다."]);
        } else {
            echo json_encode(["success" => false, "message" => "데이터베이스 오류가 발생했습니다."]);
        }
    }

    public function agentUserData() {
        $code_id = $_POST['code_id'] ?? '';
        $hostname = $_POST['hostname'] ?? '';
        $ip = $_POST['ip'] ?? 0;
        $username = $_POST['username'] ?? '';
        $token = $_POST['token'] ?? '';
        $work_info = $_POST['work_info'] ?? '';

        $cnt = $this->model('AgentUser')->countAgentUser($hostname,$ip,$username);

        if($cnt == 0){
            $temp = $this->model('AgentUser')->insertAgentUserData($hostname,$ip,$username,$token,$code_id);

            $work_type = $username. "님이 로그인 하셨습니다.";
            $work_result = "성공";
            $log = $this->model('AgentUser')->insertAgentLog($hostname,$ip,$username,$token,$work_type,$work_result,$work_info,$code_id);
        }else{
            $temp = $this->model('AgentUser')->updateAgentUserData($hostname,$ip,$username,$token,$code_id);

            $work_type = $username. "님이 로그인 하셨습니다.";
            $work_result = "성공";
            $log = $this->model('AgentUser')->insertAgentLog($hostname,$ip,$username,$token,$work_type,$work_result,$work_info,$code_id);
        }

        if ($temp) {
            echo json_encode(["success" => true, "message" => "성공"]);
        } else {
            echo json_encode(["success" => false, "message" => "데이터베이스 오류가 발생했습니다."]);
        }
    }

    public function selectLogList(){
        header('Content-Type: application/json');
        
        $temp = $this->model('AgentUser')->selectLogList();
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function logAdd(){
        $code_id = $_POST['code_id'] ?? '';
        $hostname = $_POST['hostname'] ?? '';
        $ip = $_POST['ip'] ?? 0;
        $username = $_POST['username'] ?? '';
        $token = $_POST['token'] ?? '';
        $work_info = $_POST['work_info'] ?? '';

        $work_type = $username. "님이 " . $work_info . " 하셨습니다.";
        $work_result = "성공";

        $temp = $this->model('AgentUser')->insertAgentLog($hostname,$ip,$username,$token,$work_type,$work_result,$work_info,$code_id);

        if ($temp) {
            echo json_encode(["success" => true, "message" => "성공"]);
        } else {
            echo json_encode(["success" => false, "message" => "데이터베이스 오류가 발생했습니다."]);
        }
    }
}