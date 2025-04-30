<?php
session_start();
if (!isset($_SESSION['user_role'])) {
    header("Location: ../../login.php"); // 또는 절대경로 확인
    exit;
}
?>

<?php
$_SESSION['user_role'] = 'super'; // 또는 'middle'

$page = $_GET['page'] ?? 'home'; // 기본 페이지는 home
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>i-Mon 관리</title>
    <link rel="stylesheet" href="../css/main.css" /> <!-- 경로 확인 필요 -->
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>i-Mon Admin</h2>

            

            <?php if ($_SESSION['user_role'] === 'super'): ?>
            <div class="menu-group">
                <div class="menu-group-title">조직 및 관리자</div>
                <a href="?page=admin" class="menu-item">관리자 정보 관리</a>
                <a href="?page=dept" class="menu-item">부서 정보 관리</a>
                <a href="?page=log" class="menu-item">감사 로그 조회</a>
            </div>

            <div class="menu-group">
                <div class="menu-group-title">정책 설정</div>
                <a href="?page=task" class="menu-item">예약 작업 관리</a>
                <a href="?page=delete" class="menu-item">삭제 환경 관리</a>
                <a href="?page=export" class="menu-item">외부 반출 승인 관리</a>
            </div>
            <?php endif; ?>

            <div class="menu-group">
                <div class="menu-group-title">정보 조회</div>
                <a href="?page=agent" class="menu-item">Agent 정보 조회</a>
                <a href="?page=agentlog" class="menu-item">Agent 로그 조회</a>
            </div>
        </div>

        <!-- Main -->
        <div class="main">
        <div class="header">
                <div class="user-info">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="name"><?= $_SESSION['user_id'] ?></span>
                        <form method="post" action="logout.php" style="display:inline;">
                            <button type="submit">로그아웃</button>
                        </form>
                    <?php else: ?>
                        <form method="get" action="login.php" style="display:inline;">
                            <button type="submit">로그인</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="content" id="main-content">
                <?php
                // 페이지에 따라 내용 불러오기
                $view_path = "";  
                switch ($page) {
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
