<?php

class SuperAdminController extends Controller {
    public function superAdmin() {
        $ip = $_POST['admin_ip'] ?? '';
        $pw = $_POST['admin_pw'] ?? '';
        $id = $_POST['admin_id'] ?? '';

        $admin = $this->model('SuperAdmin')->updateAdminInfo($id,$ip,$pw);

        if ($admin) {
            echo "<script>
                    alert('성공적으로 수정되었습니다.');
                    window.location.href='/?url=MainController/index&page=super';
                </script>";
        } else {
            http_response_code(401);
            // echo json_encode(['status' => 'error', 'message' => 'error']);
            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=super';</script>";
            exit;
        }
    }

    public function adminInfo() {
        $id = $_GET['session_id'] ?? '';
        
        $admin = $this->model('SuperAdmin')->adminInfo($id);
        if (!$admin) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($admin);
        }
    }
}