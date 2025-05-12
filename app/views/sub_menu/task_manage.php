<?php
// task_manage.php
?>
<link rel="stylesheet" href="css/task_manage.css" />

<div class="task-manage-wrapper">
    <div class="form-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Agent 예약작업 관리</h2>
        <a href="?url=MainController/index&page=task&form=show">
            <button class="btn-confirm">추가</button>
        </a>
    </div>

    <?php $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; ?>

    <?php if (!$formMode): ?>
    <div class="task-table-wrapper" id="task-table-wrapper"></div>
    <?php endif; ?>

    <?php if ($formMode): ?>
    <!-- 예약작업 등록 폼 -->
    <div class="form-card">
        <form id="taskForm" name="taskForm" method="post" action="/?url=TempDelController/tempDel">
            <input type="hidden" name="type" id="type" value="" />
            <input type="hidden" name="num" id="num" value="" />

            <div class="form-row">
                <strong>부서명</strong><br />
                <select class="form-input" name="department" required>
                    <option value="">부서 선택</option>
                    <option value="network">(주)에스엠에스</option>
                    <option value="security">보안팀</option>
                    <option value="infra">인프라팀</option>
                </select>
            </div>

            <div class="form-row">
                <strong>예약작업 종류</strong><br />
                <select class="form-input" name="job_type" required>
                    <option value="">선택</option>
                    <option value="파일 암호화">파일 암호화</option>
                </select>
            </div>

            <div class="form-row">
                <strong>작업 주기</strong><br />
                <select class="form-input" name="period" id="period" required onchange="handlePeriodChange()">
                    <option value="">작업 주기 선택</option>
                    <option value="한번">한번</option>
                    <option value="매일">매일</option>
                    <option value="매주">매주</option>
                    <option value="매월">매월</option>
                </select>
            </div>

            <div id="onceFields" style="display: none;">
                <div class="form-row">
                    <input class="form-input" type="datetime-local" id="once_datetime" name="once_datetime" />
                </div>
            </div>

            <div id="dailyFields" style="display: none;">
                <div class="form-row">
                    <input class="form-input" type="time" id="daily_time" name="daily_time" />
                </div>
            </div>

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
                    <input class="form-input" type="time" name="monthly_time" id="monthly_time" />
                </div>
            </div>

            <div class="form-row">
                <strong>작업 대상 (디스크/폴더명)</strong><br />
                <input type="text" class="form-input" name="target_path" id="target_path"
                    placeholder="예: C:/Documents/Important" required />
            </div>

            <div class="form-buttons">
                <a href="?url=MainController/index&page=task">
                    <button type="button" class="btn-cancel">취소</button>
                </a>
                <button type="submit" class="btn-confirm">확인</button>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
function handlePeriodChange() {
    const period = document.getElementById("period").value;
    document.getElementById("onceFields").style.display = period === "한번" ? "block" : "none";
    document.getElementById("dailyFields").style.display = period === "매일" ? "block" : "none";
    document.getElementById("weeklyFields").style.display = period === "매주" ? "block" : "none";
    document.getElementById("monthlyFields").style.display = period === "매월" ? "block" : "none";
}

// 목록 렌더링 (조회 항목 기준: 부서명, 작업 종류, 주기, 대상)
let currentPage = 1;
const itemsPerPage = 10;
let resultData = [];

fetch('/?url=TempDelController/tempDelList')
    .then(res => res.json())
    .then(data => {
        resultData = data;
        renderPage(1);
    });

function renderPage(page) {
    currentPage = page;
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = resultData.slice(start, end);

    let html = `<table class="task-table">
    <thead>
      <tr>
        <th>번호</th>
        <th>부서명</th>
        <th>예약작업 종류</th>
        <th>작업 주기</th>
        <th>작업 대상</th>
      </tr>
    </thead>
    <tbody>`;

    for (let i = 0; i < pageData.length; i++) {
        const item = pageData[i];
        const number = resultData.length - ((page - 1) * itemsPerPage + i);

        html += `
      <tr>
        <td>${number}</td>
        <td>${item.department}</td>
        <td>${item.job_type}</td>
        <td>${item.period_display}</td>
        <td>${item.target_path}</td>
      </tr>`;
    }

    html += '</tbody></table><div class="pagination">';
    const totalPages = Math.ceil(resultData.length / itemsPerPage);
    if (page > 1) html += `<button onclick="renderPage(${page - 1})">이전</button>`;
    for (let i = 1; i <= totalPages; i++) {
        html += `<button onclick="renderPage(${i})" ${i === page ? 'style="font-weight:bold;"' : ''}>${i}</button>`;
    }
    if (page < totalPages) html += `<button onclick="renderPage(${page + 1})">다음</button>`;
    html += '</div>';

    document.getElementById("task-table-wrapper").innerHTML = html;
}
</script>