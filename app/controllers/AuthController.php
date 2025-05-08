<?php

class AuthController extends Controller {
    public function login() {
        // echo '<pre>';
        // print_r($_POST['id']);
        // print_r($_POST['pw']);
        // echo '</pre>';

        $username = $_POST['id'] ?? '';
        $password = $_POST['pw'] ?? '';
        
        // 모델 사용
        $admin = $this->model('Login')->getAdminByUsername($username);
        
        if ($admin && base64_decode($admin['pw']) === $password) {
            session_start();
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_type'] = $admin['admin_type'];
            echo json_encode(['status' => 'success']);
            header('Location: /?url=MainController/index');
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid login']);
            echo "<script>alert('아이디 또는 비밀번호가 올바르지 않습니다.'); window.location.href='/?url=LoginController/login';</script>";
            exit;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();

        header('Location: /?url=LoginController/login');
        exit;
    }
}