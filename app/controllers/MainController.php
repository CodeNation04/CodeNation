<?php

class MainController extends Controller {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 세션값이 없으면 로그인 페이지로 리디렉션
        if (!isset($_SESSION['admin_id'])) {
            header("Location: http://localhost/?url=LoginController/login");
            exit; // 이후 코드 실행 방지
        }

        $session_id = $_SESSION['admin_id'];
        $mainModel = $this->model('Main'); 
        $mains = $mainModel->getAdmins($session_id);
        $this->view('/main', ['admins' => $mains]);
    }
}

?>