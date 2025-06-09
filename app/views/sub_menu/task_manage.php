<?php
// task_manage.php
?>
<link rel="stylesheet" href="/css/task_manage.css" />
<link rel="stylesheet" href="css/pagination.css">
<link rel="stylesheet" href="css/sub_title.css">
<script src="js/pagination.js"></script>

<?php
 $session_type = $_SESSION['admin_type'];
 if($session_type !== "최고관리자"){
    echo "<script>
            alert('잘못된 접근입니다.')
            location.href='/?url=MainController/index'
            </script>";
 }
?>

<div class="wrapper">
    <div class="form-header">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>예약작업 관리</h1>
        </div>
        <a href="?url=MainController/index&page=task&form=show">
            <button class="btn-register">등록</button>
        </a>
    </div>

    <?php $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; ?>

    <?php if (!$formMode): ?>
    <div class="task-table-wrapper" id="task-table-wrapper"></div>
    <div class="pagination"></div>
    <?php endif; ?>

    <?php if ($formMode): ?>
    <div class="form-card">
        <form id="taskForm" name="taskForm" method="post" action="/?url=TempDelController/tempDel">
            <input type="hidden" name="type" id="type" value="" />
            <input type="hidden" name="num" id="num" value="" />

            <div class="form-row">
                <label>부서명</label>
                <select class="form-input" id="department" name="department" required></select>
            </div>
            <div class="form-row">
                <label>예약작업 종류</label>
                <select class="form-input" id="job_type" name="job_type" required>
                    <option value="">선택</option>
                    <option value="파일 암호화">파일 암호화</option>
                </select>
            </div>
            <div class="form-row">
                <label>작업 주기</label>
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
                <label>작업 대상 (디스크/폴더명)</label>
                <input type="text" class="form-input" name="target_path" id="target_path"
                    placeholder="예: C:/Documents/Important" required />
            </div>
            <div class="form-buttons-wrapper">
                <div class="form-buttons">
                    <button type="button" class="btn-confirm" onclick="submitBtn()">확인</button>
                    <a href="?url=MainController/index&page=task">
                        <button type="button" class="btn-cancel">취소</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
let taskData = [];
let currentSort = {
    column: null,
    direction: 'asc'
};

function handlePeriodChange() {
    const period = document.getElementById("period").value;
    document.getElementById("onceFields").style.display = period === "한번" ? "block" : "none";
    document.getElementById("dailyFields").style.display = period === "매일" ? "block" : "none";
    document.getElementById("weeklyFields").style.display = period === "매주" ? "block" : "none";
    document.getElementById("monthlyFields").style.display = period === "매월" ? "block" : "none";
}

function formatPeriodDetail(item) {
    if (item.reser_date === "매월") {
        return `${item.reser_date_day}일 ${item.reser_date_time}`;
    } else if (item.reser_date === "매주") {
        return `${item.reser_date_time} ${item.reser_date_week}`;
    } else if (item.reser_date === "매일") {
        return item.reser_date_time;
    } else {
        return `${item.reser_date_time}`;
    }
}

function deleteTask(num) {
    if (confirm("정말 삭제하시겠습니까?")) {
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                num: num
            },
            url: "/?url=TempDelController/tempListDelete",
            success: function(result) {
                alert(result.message);
                location.reload();
            },
            error: function(err) {
                console.error("삭제 실패:", err);
            }
        });
    }
}

function submitBtn() {
    const form = $("#taskForm");
    const formData = form.serializeArray();
    if (confirm("저장하시겠습니까?")) {
        form.submit();
    }
}

function setSort(column) {
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }
    renderTaskTable();
}

function updateSortArrows() {
    const columns = ['code_name', 'job_type', 'reser_date', 'folder_path'];
    columns.forEach(col => {
        const el = document.getElementById(`sort-${col}`);
        if (!el) return;
        if (col === currentSort.column) {
            el.textContent = currentSort.direction === 'asc' ? '▲' : '▼';
        } else {
            el.textContent = '⇅';
        }
    });
}

function renderTaskTable() {
    const wrapper = document.getElementById("task-table-wrapper");
    let sorted = [...taskData];
    if (currentSort.column) {
        sorted.sort((a, b) => {
            const aVal = a[currentSort.column] || '';
            const bVal = b[currentSort.column] || '';
            return currentSort.direction === 'asc' ?
                aVal.localeCompare(bVal, 'ko') :
                bVal.localeCompare(aVal, 'ko');
        });
    }

    setupPagination({
        data: sorted,
        itemsPerPage: 10,
        containerId: "task-table-wrapper",
        paginationClass: "pagination",
        renderRowHTML: (pageData, offset) => {
            return `<table class="task-table">
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th onclick="setSort('code_name')">부서명 <span id="sort-code_name">⇅</span></th>
                                <th onclick="setSort('job_type')">예약작업 종류 <span id="sort-job_type">⇅</span></th>
                                <th onclick="setSort('reser_date')">작업 주기 <span id="sort-reser_date">⇅</span></th>
                                <th onclick="setSort('folder_path')">작업 대상 <span id="sort-folder_path">⇅</span></th>
                                <th>수정</th>
                                <th>삭제</th>
                            </tr>
                        </thead>
                        <tbody>` +
                pageData.map((item, i) => `
                    <tr>
                        <td>${taskData.length - (offset + i)}</td>
                        <td>${item.code_name}</td>
                        <td>${item.job_type}</td>
                        <td>${item.reser_date} (${formatPeriodDetail(item)})</td>
                        <td>${item.folder_path}</td>
                        <td><button class="edit-btn" onclick="location.href='?url=MainController/index&page=task&form=show&type=moddify&num=${item.del_idx}'">수정</button></td>
                        <td><button class="delete-btn" onclick="deleteTask(${item.del_idx})">삭제</button></td>
                    </tr>`).join('') +
                '</tbody></table>';
        }
    });
    updateSortArrows();
}

$(document).ready(function() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/selectDeptList",
        success: function(result) {
            let html = '';
            result.forEach((item) => {
                html += `<option value="${item.code_id}">${item.code_name}</option>`;
            });
            $("#department").html(html);
        }
    });

    const params = new URLSearchParams(window.location.search);
    const type = params.get("type");
    const num = params.get("num");

    if (type === "moddify") {
        $("#type").val(type);
        $("#num").val(num);
        $.ajax({
            type: "GET",
            dataType: "json",
            data: {
                num: num
            },
            url: "/?url=TempDelController/tempDelInfo",
            success: function(result) {
                $("#department").val(result.code_code_id);
                $("#job_type").val(result.job_type);
                $("#period").val(result.reser_date).trigger("change");
                if (result.reser_date === "매월") {
                    $("#monthly_day").val(result.reser_date_day);
                    $("#monthly_time").val(result.reser_date_time);
                } else if (result.reser_date === "매주") {
                    $("#weekly_day").val(result.reser_date_week);
                    $("#weekly_time").val(result.reser_date_time);
                } else if (result.reser_date === "매일") {
                    $("#daily_time").val(result.reser_date_time);
                } else {
                    $("#once_datetime").val(result.reser_date_time);
                }
                $("#target_path").val(result.folder_path);
            }
        });
    }

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=TempDelController/tempDelList",
        success: function(result) {
            taskData = result;
            renderTaskTable();
        }
    });
});
</script>