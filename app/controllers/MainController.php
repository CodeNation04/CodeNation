<?php

    class MainController extends Controller {
        public function index() {
            $this->view('/main', ['message' => 'mvc architecture 테스트중']);
        }
    }

?>