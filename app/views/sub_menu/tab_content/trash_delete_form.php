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
                
                $("#department").val(result.code_code_id);
                $("#department").trigger("change");

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
    <form id="trashForm" name="trashForm" method="post" action="/?url=TrashDelController/trashDel">
        <input type="hidden" name="temp_del" value="trash_delete" />
        <input type="hidden" name="type" id="type" value=""/>
        <input type="hidden" name="num" id="num" value=""/>
        <!-- 부서명 -->
        <div class="form-row">
            <strong>부서명</strong><br />
            <select class="form-input" id="department" name="department" required>
                <option value="">부서 선택</option>
                <option value="network">(주)에스엠에스</option>
                <option value="security">보안팀</option>
                <option value="infra">인프라팀</option>
            </select>
        </div>

        <!-- 작업 주기 -->
        <div class="form-row">
            <strong>작업 주기</strong><br />
            <select class="form-input" name="period" id="period" required onchange="handleTrashPeriodChange()">
                <option value="">작업 주기 선택</option>
                <option value="한번">한번</option>
                <option value="매일">매일</option>
                <option value="매주">매주</option>
                <option value="매월">매월</option>
            </select>
        </div>

        <!-- 주기별 조건 -->
        <div id="trashOnceFields" style="display: none;">
            <div class="form-row"><input class="form-input" type="date" id="once_date" name="once_date" /></div>
            <div class="form-row"><input class="form-input" type="time" id="once_time" name="once_time" /></div>
        </div>
        <div id="trashDailyFields" style="display: none;">
            <div class="form-row"><input class="form-input" type="time" id="daily_time" name="daily_time" /></div>
        </div>
        <div id="trashWeeklyFields" style="display: none;">
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
            <div class="form-row"><input class="form-input" type="time" id="weekly_time" name="weekly_time" /></div>
        </div>
        <div id="trashMonthlyFields" style="display: none;">
            <div class="form-row">
                <select class="form-input" id="monthly_day" name="monthly_day">
                    <option value="">일자 선택</option>
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?>일</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-row"><input class="form-input" type="time" id="monthly_time" name="monthly_time" /></div>
        </div>

        <!-- 작업 시점 -->
        <div class="form-checks">
            <strong>작업 시점</strong><br />
            <label><input type="checkbox" name="schedule" value="boot"> 부팅 시 예약 실행</label><br />
            <label><input type="checkbox" name="schedule" value="shutdown"> 종료 시 예약 실행</label>
            <input type="hidden" id="schedules" name="schedules" />
        </div>

        <!-- 버튼 -->
        <div class="form-buttons">
            <a href="?url=MainController/index&page=task&tab=trash_delete">
                <button type="button" class="btn-cancel">취소</button>
            </a>
            <button type="button" class="btn-confirm" onclick="submitBtn()">확인</button>
        </div>
    </form>
</div>

<script>
function handleTrashPeriodChange() {
    const period = document.getElementById("period").value;
    document.getElementById("trashOnceFields").style.display = (period === "한번") ? "block" : "none";
    document.getElementById("trashDailyFields").style.display = (period === "매일") ? "block" : "none";
    document.getElementById("trashWeeklyFields").style.display = (period === "매주") ? "block" : "none";
    document.getElementById("trashMonthlyFields").style.display = (period === "매월") ? "block" : "none";
}

document.querySelector("form#trashForm").addEventListener("submit", function() {
    const schedules = document.querySelectorAll("input[name=schedule]:checked");
    let values = [];
    schedules.forEach(s => values.push(s.value));
    document.getElementById("schedules").value = values.join(",");
});

function submitBtn() {
    const form = $("#trashForm");
    const formData = form.serializeArray();
    let schedules = document.querySelectorAll("input[name=schedule]");
    let str = "";

    for (schedule of schedules) {
        if (schedule.checked) {
            if (str !== "") {
                str += ",";
            }
            str += schedule.value;
        }
    }

    $("#schedules").val(str);
    console.log(formData)
    if (confirm("저장하시겠습니까?")) {
        form.submit();
    }
}
</script>