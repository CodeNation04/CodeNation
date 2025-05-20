<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>i-Mon 관리</title>
    <link rel="stylesheet" href="css/main.css" />
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <img src="asset/logo_light.png" style="width:120px; margin-left:55px; margin-bottom:8px;" />
            
            <?php
            $page = $_GET['page'] ?? "";
            if ($_SESSION['admin_type'] === '최고관리자'):
            ?>
            <div class="menu-group">
                <div class="menu-group-title">조직 및 관리자</div>
                <a href="/?url=MainController/index&page=super"
                    class="menu-item <?= ($page === 'super') ? 'active' : '' ?>">최고관리자</a>
                <a href="/?url=MainController/index&page=admin"
                    class="menu-item <?= ($page === 'admin') ? 'active' : '' ?>">관리자 목록</a>
                <a href="/?url=MainController/index&page=department"
                    class="menu-item <?= ($page === 'department') ? 'active' : '' ?>">부서 관리</a>
                <a href="/?url=MainController/index&page=dept"
                    class="menu-item <?= ($page === 'dept') ? 'active' : '' ?>">부서 정보 관리</a>
                <a href="/?url=MainController/index&page=export"
                    class="menu-item <?= ($page === 'export') ? 'active' : '' ?>">외부 반출 승인 관리</a>
            </div>

            <div class="menu-group">
                <div class="menu-group-title">정책 설정</div>
                <a href="/?url=MainController/index&page=task"
                    class="menu-item <?= ($page === 'task') ? 'active' : '' ?>">Agent 예약 작업 관리</a>
                <a href="/?url=MainController/index&page=delete"
                    class="menu-item <?= ($page === 'delete') ? 'active' : '' ?>">Agent 암호화 환경 관리</a>
            </div>
            <?php endif; ?>

            <div class="menu-group">
                <div class="menu-group-title">정보 조회</div>
                <a href="/?url=MainController/index&page=agent"
                    class="menu-item <?= ($page === 'agent') ? 'active' : '' ?>">Agent 정보 조회</a>
                <a href="/?url=MainController/index&page=agentlog"
                    class="menu-item <?= ($page === 'agentlog') ? 'active' : '' ?>">Agent 로그 조회</a>
                <a href="/?url=MainController/index&page=log"
                    class="menu-item <?= ($page === 'log') ? 'active' : '' ?>">감사 로그 조회</a>
            </div>
        </div>
        
        <!-- Main -->
        <div class="main">
            <div class="header">
                <div class="user-info">
                    <?php foreach ($data['admins'] as $admin): ?>
                    <span class="name"><?= htmlspecialchars($admin['id']) ?></span>
                    <form method="post" action="/?url=AuthController/logout">
                        <button>로그아웃</button>
                    </form>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="content" id="main-content">
                <?php
                $view_path = "sub_menu/";
                switch ($page) {
                    case "super": include $view_path . "super_admin.php"; break;
                    case "admin": include $view_path . "admin_info.php"; break;
                    case "department": include $view_path . "add_department.php"; break; // ✅ 추가된 부분
                    case "dept": include $view_path . "department_info.php"; break;
                    case "log": include $view_path . "log_view.php"; break;
                    case "task": include $view_path . "task_manage.php"; break;
                    case "delete": include $view_path . "delete_manage.php"; break;
                    case "export": include $view_path . "export_manage.php"; break;
                    case "agent": include $view_path . "agent_info.php"; break;
                    case "agentlog": include $view_path . "agent_log.php"; break;
                    default: include $view_path . "home.php";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>