<!-- task_manage.php -->
<link rel="stylesheet" href="../../../public/css/task_manage.css" />

<div class="task-manage-wrapper">
    <h2>예약 작업 관리</h2>

    <!-- 탭 메뉴 -->
    <ul class="tab-menu">
        <li class="active" onclick="showTab(0)">임시파일 삭제</li>
        <li onclick="showTab(1)">폴더 삭제</li>
        <li onclick="showTab(2)">최근 파일 삭제</li>
        <li onclick="showTab(3)">휴지통 완전비우기</li>
    </ul>

    <!-- 탭 콘텐츠 -->
    <div class="tab-content">
        <div class="tab-pane active">
            <?php include 'tab_content/temp_delete.php'; ?>
        </div>
        <div class="tab-pane">
            <?php include 'tab_content/folder_delete.php'; ?>
        </div>
        <div class="tab-pane">
            <?php include 'tab_content/recent_delete.php'; ?>
        </div>
        <div class="tab-pane">
            <?php include 'tab_content/trash_delete.php'; ?>
        </div>
    </div>
</div>

<script>
function showTab(index) {
    document.querySelectorAll('.tab-menu li').forEach((tab, i) => {
        tab.classList.toggle('active', i === index);
    });
    document.querySelectorAll('.tab-pane').forEach((pane, i) => {
        pane.classList.toggle('active', i === index);
    });
}
</script>