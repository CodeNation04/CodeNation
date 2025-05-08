<div class="form-card">
    <form id="recentForm" name="recentForm" method="post" action="/?url=RecentDelController/recentDel">

        <!-- 1. 부서명 -->
        <div class="form-row">
            <strong>부서명</strong><br />
            <select class="form-input" name="department" required>
                <option value="">부서 선택</option>
                <option value="network">(주)에스엠에스</option>
                <option value="security">보안팀</option>
                <option value="infra">인프라팀</option>
            </select>
        </div>

        <!-- 2. 작업 주기 -->
        <div class="form-row">
            <strong>작업 주기</strong><br />
            <select class="form-input" name="period" id="recent_period" required onchange="handleRecentPeriodChange()">
                <option value="">작업 주기 선택</option>
                <option value="한번">한번</option>
                <option value="매일">매일</option>
                <option value="매주">매주</option>
                <option value="매월">매월</option>
            </select>
        </div>

        <!-- 3. 주기별 조건 필드 -->
        <div id="recentOnceFields" style="display: none;">
            <div class="form-row"><input class="form-input" type="date" name="once_date" /></div>
            <div class="form-row"><input class="form-input" type="time" name="once_time" /></div>
        </div>
        <div id="recentDailyFields" style="display: none;">
            <div class="form-row"><input class="form-input" type="time" name="daily_time" /></div>
        </div>
        <div id="recentWeeklyFields" style="display: none;">
            <div class="form-row">
                <select class="form-input" name="weekly_day">
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
            <div class="form-row"><input class="form-input" type="time" name="weekly_time" /></div>
        </div>
        <div id="recentMonthlyFields" style="display: none;">
            <div class="form-row">
                <select class="form-input" name="monthly_day">
                    <option value="">일자 선택</option>
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?>일</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-row"><input class="form-input" type="time" name="monthly_time" /></div>
        </div>

        <!-- 4. 삭제 대상 선택 -->
        <div class="form-checks">
            <strong>삭제 대상</strong><br />
            <label><input type="checkbox" name="recent_target" value="windows_recent"> 윈도우 최근파일</label><br />
            <label><input type="checkbox" name="recent_target" value="program_recent"> 응용프로그램 최근파일</label><br />
            <label><input type="checkbox" name="recent_target" value="usb_recent"> USB 최근파일</label><br />
            <label><input type="checkbox" name="recent_target" value="network_recent"> 네트워크 드라이브 최근파일</label>
            <input type="hidden" id="recent_targets" name="recent_targets" />
        </div>

        <!-- 5. 버튼 -->
        <div class="form-buttons">
            <a href="?url=MainController/index&page=task&tab=recent_delete">
                <button type="button" class="btn-cancel">취소</button>
            </a>
            <button type="submit" class="btn-confirm">확인</button>
        </div>
    </form>
</div>

<script>
function handleRecentPeriodChange() {
    const period = document.getElementById("recent_period").value;
    document.getElementById("recentOnceFields").style.display = (period === "한번") ? "block" : "none";
    document.getElementById("recentDailyFields").style.display = (period === "매일") ? "block" : "none";
    document.getElementById("recentWeeklyFields").style.display = (period === "매주") ? "block" : "none";
    document.getElementById("recentMonthlyFields").style.display = (period === "매월") ? "block" : "none";
}

document.querySelector("form#recentForm").addEventListener("submit", function() {
    const targets = document.querySelectorAll("input[name=recent_target]:checked");
    let values = [];
    targets.forEach(t => values.push(t.value));
    document.getElementById("recent_targets").value = values.join(",");
});
</script>