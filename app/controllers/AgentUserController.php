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
                    alert('성공적으로 저장되었습니다.');
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
            $work_type = "부서 등록";
        }else{
            $temp = $this->model('AgentUser')->updateDeptInfo($code_name,$code_id);
            $work_type = "부서 수정";
        }

        session_start();
        $admin_id = $_SESSION['admin_id'];
        $admin_type = $_SESSION['admin_type'];
        $admin_code_id = $_SESSION['code_id'];

        if ($temp) {
            $work_info = "성공";
            $agentLog = $this->model('AgentUser')->insertAdminLog($admin_code_id,$admin_id,$admin_type,$work_type,$work_info);
            echo "<script>
                    alert('성공적으로 저장되었습니다.');
                    window.location.href='/?url=MainController/index&page=department';
                </script>";
        } else {
            $work_info = "성공";
            $agentLog = $this->model('AgentUser')->insertAdminLog($admin_code_id,$admin_id,$admin_type,$work_type,$work_info);
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

        session_start();
        $admin_id = $_SESSION['admin_id'];
        $admin_type = $_SESSION['admin_type'];
        $admin_code_id = $_SESSION['code_id'];
        $work_type = "부서 삭제";

        if ($temp) {
            $work_info = "성공";
            echo json_encode(["success" => true, "message" => "삭제되었습니다."]);
        } else {
            $work_info = "실패";
            echo json_encode(["success" => false, "message" => "데이터베이스 오류가 발생했습니다."]);
        }

        $agentLog = $this->model('AgentUser')->insertAdminLog($admin_code_id,$admin_id,$admin_type,$work_type,$work_info);
    }

    public function agentUserData() {
        $code_id = $_POST['code_id'] ?? '';
        $hostname = $_POST['hostname'] ?? '';
        $ip = $_POST['ip'] ?? 0;
        $username = $_POST['username'] ?? '';
        $token = $_POST['token'] ?? '';
        $work_info = $_POST['work_info'] ?? '';
        $check_time= $_POST['check_time'] ??'3600';

        $cnt = $this->model('AgentUser')->countAgentUser($hostname,$ip,$username);
        $response = ['cnt' => $cnt];
        if($cnt == 0){
            $temp = $this->model('AgentUser')->insertAgentUserData($hostname,$ip,$username,$token,$code_id);
        }
        else{
            $temp = $this->model('AgentUser')->updateAgentUserData($hostname,$ip,$username,$code_id);
        }

        $agentUserData = $this->model('AgentUser')->selectAgentUserData($hostname,$ip,$username);

        if ($temp) {
            $response['success'] = true;
            $response['result'] = $agentUserData;
        } else {
            $response['success'] = false;
            $response['message'] = "데이터베이스 오류가 발생했습니다.";
        }

        echo json_encode($response);
    }

    public function agentUserLogin() {
        $code_id = $_POST['code_id'] ?? '';
        $hostname = $_POST['hostname'] ?? '';
        $ip = $_POST['ip'] ?? '';
        $username = $_POST['username'] ?? '';
        $token = $_POST['token'] ?? '';
        $work_info = $_POST['work_info'] ?? '';

        $cnt = $this->model('AgentUser')->countAgentUser($hostname,$ip,$username);
        $response = ['cnt' => $cnt];

        if($cnt == 0){
            $work_result = "실패";
            $response['success'] = false;
            $response['message'] = "데이터베이스 오류가 발생했습니다.";
        }
        else{
            $work_result = "성공";
            $response['success'] = true;
            $response['message'] = "로그인 성공";
        }

        $work_type = $username. "님이 로그인 하셨습니다.";

        $log = $this->model('AgentUser')->insertAgentLog($hostname,$ip,$username,$token,$work_type,$work_result,$work_info,$code_id);

        echo json_encode($response);
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

    public function selectAdminLogList(){
        header('Content-Type: application/json');

        $temp = $this->model('AgentUser')->selectAdminLogList();
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }

    public function loginCnt(){
        $user_type = $_GET['user_type'] ?? '';
        $date = $_GET['date'] ?? '';

        header('Content-Type: application/json');

        $result = $this->model('AgentUser')->loginCnt($user_type,$date);

        if(!$result){
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($result);
        }
    }


}