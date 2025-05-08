<!-- task_manage.php -->
<link rel="stylesheet" href="css/task_manage.css" />

<div class="task-manage-wrapper">
    <h2>예약 작업 관리</h2>

    <!-- 탭 메뉴 -->
    <ul class="tab-menu">
        <li><a href="/?url=MainController/login&page=task&tab=temp_delete"
                class="<?= ($_GET['tab'] ?? 'temp_delete') === 'temp_delete' ? 'active' : '' ?>">임시파일</a></li>
        <li><a href="/?url=MainController/login&page=task&tab=folder_delete"
                class="<?= ($_GET['tab'] ?? '') === 'folder_delete' ? 'active' : '' ?>">폴더</a></li>
        <li><a href="/?url=MainController/login&page=task&tab=recent_delete"
                class="<?= ($_GET['tab'] ?? '') === 'recent_delete' ? 'active' : '' ?>">최근 파일</a></li>
        <li><a href="/?url=MainController/login&page=task&tab=trash_delete"
                class="<?= ($_GET['tab'] ?? '') === 'trash_delete' ? 'active' : '' ?>">휴지통</a></li>
    </ul>

    <!-- 탭 콘텐츠 -->
    <div class="tab-content">
        <?php
        // 현재 탭 파라미터 확인
        $currentTab = $_GET['tab'] ?? 'temp_delete';

        // 각 탭의 파일 경로 설정
        $tabFiles = [
            'temp_delete' => 'tab_content/temp_delete.php',
            'folder_delete' => 'tab_content/folder_delete.php',
            'recent_delete' => 'tab_content/recent_delete.php',
            'trash_delete' => 'tab_content/trash_delete.php',
        ];

        // 존재하는 파일이면 포함
        if (array_key_exists($currentTab, $tabFiles)) {
            include $tabFiles[$currentTab];
        } else {
            echo "<p>잘못된 탭입니다.</p>";
        }
        ?>
    </div>
</div>