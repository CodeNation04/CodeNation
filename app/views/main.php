<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>i-Mon 관리</title>
    <link rel="stylesheet" href="../css/main.css" /> 
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>i-Mon Admin</h2>

            <?php if ($_SESSION['user_role'] === 'super'): ?>
            <div class="menu-group">
                <div class="menu-group-title">조직 및 관리자</div>
                <a href="?page=super" class="menu-item">최고관리자</a>
                <a href="?page=admin" class="menu-item">관리자 목록</a>
                <a href="?page=dept" class="menu-item">부서 정보 관리</a>
        
                <a href="?page=export" class="menu-item">외부 반출 승인 관리</a>
            </div>

            <div class="menu-group">
                <div class="menu-group-title">정책 설정</div>
                <a href="?page=task" class="menu-item">예약 작업 관리</a>
                <a href="?page=delete" class="menu-item">삭제 환경 관리</a>
               
            </div>
            <?php endif; ?>

            <div class="menu-group">
                <div class="menu-group-title">정보 조회</div>
                <a href="?page=agent" class="menu-item">Agent 정보 조회</a>
                <a href="?page=agentlog" class="menu-item">Agent 로그 조회</a>
                <a href="?page=log" class="menu-item">감사 로그 조회</a>
            </div>
        </div>

        <!-- Main -->
        <div class="main">
        <div class="header">
                <div class="user-info">

                    <?php foreach ($data['admins'] as $admin): ?>
                    <span class="name"><?= htmlspecialchars($admin['admin_type']) ?></span>
                    <button>로그아웃</button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="content" id="main-content">
    <?php
    $page = $_GET['page'] ?? ""; 
    $view_path = "sub_menu/";

    switch ($page) {
        case"super":
            include $view_path . "super_admin.php";
            break;
        case "admin":
            include $view_path . "admin_info.php";
            break;
        case "dept":
            include $view_path . "department_info.php";
            break;
        case "log":
            include $view_path . "log_view.php";
            break;
        case "task":
            include $view_path . "task_manage.php";
            break;
        case "delete":
            include $view_path . "delete_manage.php";
            break;
        case "export":
            include $view_path . "export_manage.php";
            break;
        case "agent":
            include $view_path . "agent_info.php";
            break;
        case "agentlog":
            include $view_path . "agent_log.php";
            break;
        default:
            include $view_path . "home.php";
    }
    ?>
</div>

        </div>
    </div>
</body>
</html>
