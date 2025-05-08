<h3>폴더 삭제 예약 추가</h3>

<?php
$formMode = isset($_GET['form']) && $_GET['form'] === 'show';
?>

<?php if (!$formMode): ?>
<!-- 목록 테이블만 보이는 영역 -->
<div class="task-table-wrapper">
    <table class="task-table">
        <thead>
            <tr>
                <th>부서명</th>
                <th>작업 주기</th>
                <th>대상 폴더명</th>
                <th>작업 시점</th>
                <th>수정</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>(주)에스엠에스</td>
                <td>매주<br />금요일 16:00</td>
                <td>대상 폴더명</td>
                <td>종료 시</td>
                <td><a href="#">수정</a></td>
                <td><a href="#">삭제</a></td>
            </tr>
        </tbody>
    </table>

    <div class="pagination">
        <button>이전</button>
        <span>1</span>
        <button>다음</button>
    </div>
</div>

<div class="add-button-wrapper">
    <a href="?url=MainController/login&page=task&tab=folder_delete&form=show">
        <button class="btn-confirm">추가</button>
    </a>
</div>
<?php endif; ?>

<?php if ($formMode): ?>
<?php include('folder_delete_form.php'); ?>
<?php endif; ?>