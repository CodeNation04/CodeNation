<!-- views/sub_menu/delete_manage_form.php -->
<div class="delete-manage-form">
    <form method="post" action="/?url=DeleteManageController/deleteManage" id="deleteManageForm" name="deleteManageForm">

        <h3 id="form-title">삭제 환경 등록</h3>

        <div class="form-row">
            <label for="department">부서명</label>
            <select name="department" id="department" required>
                <option value="">부서 선택</option>
                <option value="정보보안팀">정보보안팀</option>
                <option value="개발팀">개발팀</option>
                <option value="운영팀">운영팀</option>
            </select>
        </div>

        <div class="form-row">
            <label>설정 변경 허용 여부</label>
            <label><input type="radio" name="allow_change" value="Y" checked> 허용</label>
            <label><input type="radio" name="allow_change" value="N"> 불허</label>
        </div>

        <div class="form-row">
            <label for="delete_method">삭제 방법</label>
            <select name="delete_method" id="delete_method" required>
                <option value="Kr Random">Kr Random</option>
                <option value="Kr Random-00-FF">Kr Random-00-FF</option>
                <option value="Quick Erase-FF">Quick Erase-FF</option>
                <option value="DoD Short">DoD Short</option>
                <option value="DoD 5220.22-M">DoD 5220.22-M</option>
                <option value="Gutmann Wipe">Gutmann Wipe</option>
            </select>
        </div>

        <div class="form-row">
            <label for="overwrite_count">덮어쓰기 횟수 (1~99)</label>
            <input type="number" name="overwrite_count" id="overwrite_count" min="1" max="99" required />
        </div>

        <div class="form-row buttons">
            <button type="button" id="submitBtn">등록</button>
            <button type="button" onclick="cancelForm()">취소</button>
        </div>
    </form>
</div>

<script>
    
    $("#submitBtn").click(function(){
        const form = $("#deleteManageForm");
        const formData = form.serializeArray();

        console.log(formData)
        if (confirm("저장하시겠습니까?")) {
            // form.submit();
        }
    })
</script>