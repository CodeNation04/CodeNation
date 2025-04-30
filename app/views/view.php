<?php
session_start();
$_SESSION['user_role'] = 'super'; // 임시 최고 관리자
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>i-Mon 관리</title>
    <script defer src="../../js/main.js"></script>
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
    }

    .layout {
        display: flex;
        height: 100vh;
    }

    .sidebar {
        width: 240px;
        background-color: #2c3e50;
        color: white;
        padding: 20px 0;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 18px;
    }

    .menu-group {
        margin-bottom: 25px;
    }

    .menu-group-title {
        padding: 10px 20px;
        font-weight: bold;
        font-size: 15px;
        background-color: #34495e;
    }

    .menu-item {
        padding: 10px 30px;
        font-size: 14px;
        cursor: pointer;
        color: #ecf0f1;
    }

    .menu-item:hover {
        background-color: #3e5870;
    }

    .main {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .header {
        background-color: #ffffff;
        padding: 15px 20px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        border-bottom: 1px solid #ddd;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-info .name {
        font-weight: bold;
        color: #e74c3c;
    }

    .user-info button {
        background: none;
        border: 1px solid #e74c3c;
        color: #e74c3c;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 4px;
        transition: 0.3s;
    }

    .user-info button:hover {
        background-color: #e74c3c;
        color: white;
    }

    .content {
        padding: 30px;
    }

    .placeholder {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    </style>
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
                    <span class="name">최고관리자</span>
                    <button>로그아웃</button>
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