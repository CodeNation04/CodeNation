<script>
    const params = new URLSearchParams(window.location.search);
    const type = params.get('type');
    const num = params.get('num');

    if(type === 'moddify'){
        $("#type").val(type);
        $("#num").val(num);

        $.ajax({
            type: "GET",
            dataType: "json",
            data: { num: num },
            url: "/?url=TempDelController/tempDelInfo",
            success: function(result) {
                console.log(result)
                
                $("#period").val(result.reser_date);
                $("#period").trigger("change");

                if(result.reser_date == "매월"){
                    $("#monthly_day").val(result.reser_date_day);
                    $("#monthly_day").trigger("change");
                    $("#monthly_time").val(result.reser_date_time);
                }else if(result.reser_date == "매주"){
                    $("#weekly_day").val(result.reser_date_week);
                    $("#weekly_day").trigger("change");
                    $("#weekly_time").val(result.reser_date_time);
                }else if(result.reser_date == "매일"){
                    $("#daily_time").val(result.reser_date_time);
                }else{
                    $("#once_date").val(result.reser_date_ymd);
                    $("#once_date").trigger("change");
                    $("#once_time").val(result.reser_date_time);
                }
                
                $("#department").val(result.code_code_id);
                $("#department").trigger("change");

                $("#targets").val(result.del_target);
                const targets = $("#targets").val();

                const targetsSplit = targets.split(",");
                console.log(targetsSplit)

                for(target of targetsSplit){
                    $(`input[name=target][value="${target.trim()}"]`).prop("checked", true);
                }

                $("#schedules").val(result.work_potin);
                const schedules = $("#schedules").val();

                const schedulesSplit = schedules.split(",");
                console.log(schedulesSplit)

                for(schedule of schedulesSplit){
                    $(`input[name=schedule][value="${schedule.trim()}"]`).prop("checked", true);
                }
            },
            error: function(err) {
                console.error("데이터 불러오기 실패:", err.responseText);
            }
        });
    }
</script>
<div class="form-card">
    <h4 class="form-title">임시파일 삭제 예약 추가</h4>
    <form id="taskForm" name="taskForm" method="post" action="/?url=TempDelController/tempDel">
        <input type="hidden" name="type" id="type" value=""/>
        <input type="hidden" name="num" id="num" value=""/>
        <!-- 주기 선택 -->
        <div class="form-row">
            <select class="form-input" name="period" id="period" required onchange="handlePeriodChange()">
                <option value="">작업 주기 선택</option>
                <option value="한번">한번</option>
                <option value="매일">매일</option>
                <option value="매주">매주</option>
                <option value="매월">매월</option>
            </select>
        </div>

        <!-- 한번: 날짜 + 시/분 -->
        <div id="onceFields" style="display: none;">
            <div class="form-row">
                <input class="form-input" type="date" id="once_date" name="once_date" />
            </div>
            <div class="form-row">
                <input class="form-input" type="time" id="once_time" name="once_time" />
            </div>
        </div>

        <!-- 매일: 시/분 -->
        <div id="dailyFields" style="display: none;">
            <div class="form-row">
                <input class="form-input" type="time" id="daily_time" name="daily_time" />
            </div>
        </div>

        <!-- 매주: 요일 + 시/분 -->
        <div id="weeklyFields" style="display: none;">
            <div class="form-row">
                <select class="form-input" id="weekly_day" name="weekly_day">
                    <option value="">요일 선택</option>
                    <option value="월요일">월요일</option>
                    <option value="화요일">화요일</option>
                    <option value="수요일">수요일</option>
                    <option value="목요일">목요일</option>
                    <option value="금요일">금요일</option>
                    <option value="토요일">토요일</option>
                    <option value="일요일">일요일</option>
                </select>
            </div>
            <div class="form-row">
                <input class="form-input" type="time" id="weekly_time" name="weekly_time" />
            </div>
        </div>

        <!-- 매월: 일 + 시/분 -->
        <div id="monthlyFields" style="display: none;">
            <div class="form-row">
                <select class="form-input" name="monthly_day" id="monthly_day">
                    <option value="">일자 선택</option>
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?>일</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-row">
                <input class="form-input" type="time" name="monthly_time" id="monthly_time"/>
            </div>
        </div>

        <!-- 부서 선택 -->
        <div class="form-row">
            <select class="form-input" name="department" id="department" required>
                <option value="">부서 선택</option>
                <option value="network">(주)에스엠에스</option>
                <option value="security">보안팀</option>
                <option value="infra">인프라팀</option>
            </select>
        </div>

        <!-- ✅ 작업 대상 체크박스 -->
        <div class="form-checks">
            <strong>작업 대상</strong><br />
            <label><input type="checkbox" name="target" value="internet_temp"> 인터넷 임시파일</label><br />
            <label><input type="checkbox" name="target" value="cookie"> 인터넷 쿠키파일</label><br />
            <label><input type="checkbox" name="target" value="history"> 인터넷 작업히스토리</label><br />
            <label><input type="checkbox" name="target" value="windows_temp"> 윈도우 임시파일</label>
            <input type="hidden" id="targets" name="targets" />
        </div>

        <!-- 예약 실행 조건 -->
        <div class="form-checks">
            <strong>작업 시점</strong><br />
            <label><input type="checkbox" name="schedule" value="boot" /> 부팅 시 예약 실행</label><br />
            <label><input type="checkbox" name="schedule" value="shutdown" /> 종료 시 예약 실행</label>
            <input type="hidden" id="schedules" name="schedules" />
        </div>

        <!-- 버튼 -->
        <div class="form-buttons">
            <a href="?url=MainController/index&page=task&tab=temp_delete">
                <button type="button" class="btn-cancel">취소</button>
            </a>
            <button type="button" class="btn-confirm" onclick="submitBtn()">확인</button>
        </div>
    </form>
</div>

<script>
function handlePeriodChange() {
    const period = document.getElementById("period").value;
    document.getElementById("onceFields").style.display = (period === "한번") ? "block" : "none";
    document.getElementById("dailyFields").style.display = (period === "매일") ? "block" : "none";
    document.getElementById("weeklyFields").style.display = (period === "매주") ? "block" : "none";
    document.getElementById("monthlyFields").style.display = (period === "매월") ? "block" : "none";
}

function submitBtn() {
    const form = $("#taskForm");
    const formData = form.serializeArray();
    let targets = document.querySelectorAll("input[name=target]");
    let schedules = document.querySelectorAll("input[name=schedule]");
    let str = "";
    let str2 = "";
    for (target of targets) {
        if (target.checked) {
            if (str !== "") {
                str += ",";
            }
            str += target.value;
        }
    }

    for (schedule of schedules) {
        if (schedule.checked) {
            if (str2 !== "") {
                str2 += ",";
            }
            str2 += schedule.value;
        }
    }

    $("#targets").val(str);
    $("#schedules").val(str2);
    console.log(formData)
    if(confirm("저장하시겠습니까?")){
        form.submit();
    }
}
</script>