<?php

    class LoginController extends Controller {
        public function index() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
    
            // 세션 값 가져오기
            $session_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
            $session_pw = isset($_SESSION['pw']) ? $_SESSION['pw'] : null;
            $loginModel = $this->model('Login'); 
            $logins = $loginModel->getAdmins($session_id,$session_pw);
            $this->view('/login', ['admins' => $logins]);
        }
    }

?>