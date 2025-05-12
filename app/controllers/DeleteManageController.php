<?php

class DeleteManageController extends Controller {
    public function deleteManage(){
        $type = $_POST['type'] ?? '';
        $num = $_POST['num'] ?? 0;
        
        $code_id = $_POST['department'] ?? '';
        $set_change_yn = $_POST['allow_change'] ?? '';
        $del_method = $_POST['delete_method'] ?? '';
        $overwrite_cnt = $_POST['overwrite_count'] ?? '';

        if($type !== "moddify"){
            $temp = $this->model('DeleteManage')->insertDeleteManage($code_id,$overwrite_cnt,$set_change_yn,$del_method);
        }else{
            $temp = $this->model('DeleteManage')->updateDeleteManage($num,$code_id,$overwrite_cnt,$set_change_yn,$del_method);
        }

        if ($temp) {
            echo "<script>
                    alert('성공적으로 저정되었습니다.');
                    window.location.href='/?url=MainController/index&page=delete';
                </script>";
        } else {
            http_response_code(401);
            echo "<script>alert('데이터베이스에서 오류가 발생하였습니다.'); window.location.href='/?url=MainController/index&page=delete';</script>";
            exit;
        }

    }
}