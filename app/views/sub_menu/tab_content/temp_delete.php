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
    <h3>임시파일 삭제 예약 등록</h3>

    <div class="form-group">
        <label>부서 선택</label>
        <select name="department">
            <option value="">부서를 선택하세요</option>
            <option value="network">네트워크팀</option>
            <option value="security">보안팀</option>
            <option value="infra">인프라팀</option>
        </select>
    </div>

    <div class="form-group">
        <label>예약작업 주기</label>
        <select name="period">
            <option value="">주기를 선택하세요</option>
            <option value="once">1회</option>
            <option value="daily">매일</option>
            <option value="weekly">매주</option>
        </select>
    </div>

    <div class="form-group">
        <label>작업 시점</label>
        <select name="schedule">
            <option value="">시점을 선택하세요</option>
            <option value="boot">부팅 시</option>
            <option value="shutdown">종료 시</option>
        </select>
    </div>

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