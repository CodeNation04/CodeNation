<?php

class AdminInfoController extends Controller {
    public function adminInfoList() {
        header('Content-Type: application/json');
        $temp = $this->model('AdminInfo')->adminInfoList();
        if (!$temp) {
            echo json_encode(["error" => "No data found."]);
        } else {
            echo json_encode($temp);
        }
    }
}