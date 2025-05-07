<?php

    class MainController extends Controller {
        public function index() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
    
            $session_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
            $mainModel = $this->model('Main'); 
            $mains = $mainModel->getAdmins($session_id);
            $this->view('/main', ['admins' => $mains]);
        }
    }

?>