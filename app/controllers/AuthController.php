<?php

class AuthController extends Controller {
    public function login() {
        // 접속 IP 출력
        $ip = $_SERVER['REMOTE_ADDR'];
        // echo "<script>alert('접속 IP: {$ip}');</script>";

        $username = $_POST['id'] ?? '';
        $password = $_POST['pw'] ?? '';
        
        // 모델 사용
        $admin = $this->model('Login')->getAdminByUsername($username,$ip);

        $admin_id = $admin['id'];
        $admin_type = $admin['admin_type'];
        $code_id = $admin['code_code_id'];
        $work_type = "로그인";

        if ($admin && base64_decode($admin['pw']) === $password) {
            session_start();
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_type'] = $admin_type;
            $_SESSION['code_id'] = $code_id;
            $work_info = "성공";
            $agentLog = $this->model('AgentUser')->insertAdminLog($code_id,$admin_id,$admin_type,$work_type,$work_info);
            echo json_encode(['status' => 'success']);
            header('Location: /?url=MainController/index');
        }else if($admin['pw'] !== $password){
            echo json_encode(['status' => 'error', 'message' => 'Invalid login']);
            $work_info = "비밀번호 오류";
            $agentLog = $this->model('AgentUser')->insertAdminLog($code_id,$admin_id,$admin_type,$work_type,$work_info);
            echo "<script>alert('아이디 또는 비밀번호가 올바르지 않습니다.'); window.location.href='/?url=LoginController/login';</script>";
            exit;
        }else if ($admin['ip'] !== $ip) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid login']);
            $work_info = "허용되지않은 IP";
            $agentLog = $this->model('AgentUser')->insertAdminLog($code_id,$admin_id,$admin_type,$work_type,$work_info);
            echo "<script>alert('ip가 허용되어있지않습니다.'); window.location.href='/?url=LoginController/login';</script>";
            exit;
        }
    }

    public function logout() {
        session_start();
        $admin_id = $_SESSION['admin_id'];
        $admin_type = $_SESSION['admin_type'];
        $code_id = $_SESSION['code_id'];
        $work_type = "로그아웃";
        $work_info = "성공";
        $agentLog = $this->model('AgentUser')->insertAdminLog($code_id,$admin_id,$admin_type,$work_type,$work_info);
        
        session_unset();
        session_destroy();

        header('Location: /?url=LoginController/login');
        exit;
    }
}