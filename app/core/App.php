<?php

class App {
    protected $controller;
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        // 도메인 기준 기본 컨트롤러 결정
        $this->controller = $this->getControllerByDomain();
        
        $url = $this->parseUrl();

        // URL로부터 컨트롤러 이름 재지정 (도메인 기반보다 우선시)
        if (!empty($url) && file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 메서드 지정
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // 파라미터 지정
        $this->params = $url ? array_values($url) : [];

        // 최종 실행
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    private function getControllerByDomain() {
        $host = $_SERVER['HTTP_HOST'];
        
        // 필요에 따라 서브도메인 파싱
        if (strpos($host, 'login.') === 0) {
            return 'LoginController';
        } elseif (strpos($host, 'auth.') === 0) {
            return 'AuthController';
        } elseif (strpos($host, 'main.') === 0) {
            return 'MainController';
        }

        // 기본값
        return 'LoginController';
    }
}