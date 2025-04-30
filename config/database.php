<?php
class Database {
    private $host = '192.168.0.63';       // MySQL 호스트
    private $username = 'we12345';        // 사용자명
    private $password = 'we12345';        // 비밀번호
    private $database = 'project';        // 데이터베이스명
    private $port = 3307;                 // 포트 번호

    public $pdo;

    public function __construct() {
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
}