<?php

    class MainController extends Controller {
        public function index() {
            $this->view('/test', ['message' => 'mvc architecture 테스트중']);
        }
    }

?>