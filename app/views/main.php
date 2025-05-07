<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_role'])) {
    $_SESSION['user_role'] = 'super';
}
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>i-Mon 관리</title>
    <link rel="stylesheet" href="css/main.css" /> <!-- ✅ 외부 CSS 연결 -->
    <script defer src="js/main.js"></script> <!-- ✅ JS도 상대경로 연결 -->
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>i-Mon Admin</h2>

            <?php if ($_SESSION['user_role'] === 'super'): ?>
            <div class="menu-group">
                <div class="menu-group-title">조직 및 관리자</div>
                <div class="menu-item" onclick="showContent('관리자 정보 관리')">관리자 정보 관리</div>
                <div class="menu-item" onclick="showContent('부서 정보 관리')">부서 정보 관리</div>
                <div class="menu-item" onclick="showContent('감사 로그 조회')">감사 로그 조회</div>
            </div>

            <div class="menu-group">
                <div class="menu-group-title">정책 설정</div>
                <div class="menu-item" onclick="showContent('예약 작업 관리')">예약 작업 관리</div>
                <div class="menu-item" onclick="showContent('삭제 환경 관리')">삭제 환경 관리</div>
                <div class="menu-item" onclick="showContent('외부 반출 승인 관리')">외부 반출 승인 관리</div>
            </div>
            <?php endif; ?>

            <div class="menu-group">
                <div class="menu-group-title">정보 조회</div>
                <div class="menu-item" onclick="showContent('Agent 정보 조회')">Agent 정보 조회</div>
                <div class="menu-item" onclick="showContent('Agent 로그 조회')">Agent 로그 조회</div>
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
                <div class="placeholder">
                    <h2>i-Mon 관리자 시스템</h2>
                    <p>왼쪽 메뉴를 선택하여 기능을 시작하세요.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>