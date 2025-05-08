<!-- temp_delete.php -->
<script>
    function submitBtn(){
        const form = $("#taskForm");
        const formData = form.serializeArray();
        let targets = document.querySelectorAll("input[name=target]");
        let str = "";
        for(target of targets){
            if (target.checked) {
                if (str !== "") {
                    str += ",";
                }
                str += target.value;
            }
        }
        $("#targets").val(str);
        form.submit();
    }
</script>
<form class="task-form" id="taskForm" name="taskForm" method="post" action="/?url=tempDelController/tempDel">
    <input type="hidden" name="temp_del" value="temp_del"/>
    <h3>임시파일 삭제 예약 추가</h3>

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
                <th>작업 대상</th>
                <th>작업 시점</th>
                <th>수정</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>(주)에스엠에스</td>
                <td>한번<br />2025-01-01 14:00:00</td>
                <td>인터넷 임시파일</td>
                <td>부팅 시</td>
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

<<<<<<< HEAD
    <div class="form-group">
        <label>삭제 대상</label><br />
        <label><input type="checkbox" name="target" value="internet_temp" /> 인터넷 임시파일</label><br />
        <label><input type="checkbox" name="target" value="cookie" /> 인터넷 쿠키</label><br />
        <label><input type="checkbox" name="target" value="history" /> 인터넷 작업히스토리</label><br />
        <label><input type="checkbox" name="target" value="windows_temp" /> 윈도우 임시파일</label>
        <input type="hidden" id="targets" name="targets"/>
    </div>

    <div class="form-buttons">
        <button type="button" onclick="submitBtn()">등록</button>
        <button type="reset">초기화</button>
    </div>
</form>
=======
<div class="add-button-wrapper">
    <a href="?url=MainController/login&page=task&tab=temp_delete&form=show">
        <button class="btn-confirm">추가</button>
    </a>
</div>
<?php endif; ?>

<!-- 추가 폼은 form=show일 때만 보임 -->
<?php if ($formMode): ?>
<?php include('temp_delete_form.php'); ?>
<?php endif; ?>
>>>>>>> 31904c7a81cdff77b70b44e59ebf671e847fd4c1
