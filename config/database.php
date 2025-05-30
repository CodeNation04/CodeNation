<?php
class Database {
    private $host = '192.168.0.63';       // MySQL 호스트
    private $username = 'we12345';        // 사용자명
    private $password = 'we12345';        // 비밀번호
    private $database = 'project';        // 데이터베이스명
    private $port = 3307;                 // 포트 번호

    public $pdo;

    public function __construct() {
        $_GET    = $this->sanitizeArray($_GET);
        $_POST   = $this->sanitizeArray($_POST);
        $_COOKIE = $this->sanitizeArray($_COOKIE);

        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset=utf8",
                $this->username,
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("DB 연결 실패: " . $e->getMessage());
        }
    }

    private function sanitizeInput($input) {
        $input = trim($input);
        $input = str_replace(['../', '..\\', './', '.\\', '/', '\\'], '', $input);

        $pattern = '/\b(select|insert|update|delete|drop|union|--|#|\*|;)\b/i';
        $input = preg_replace($pattern, '', $input);

        $input = strip_tags($input);

        return $input;
    }
    
    private function sanitizeArray($array) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->sanitizeArray($value);
            } else {
                $array[$key] = $this->sanitizeInput($value);
            }
        }
        return $array;
    }
}