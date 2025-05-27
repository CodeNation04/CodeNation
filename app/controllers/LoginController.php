<?php

    class LoginController extends Controller {
        public function index() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $session_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
            $loginModel = $this->model('Login'); 
            // $logins = $loginModel->getAdmins($session_id);
            // $this->view('/login', ['admins' => $logins]);
            $this->view('/login', ['session_id' => $session_id]);
        }
    }

?>