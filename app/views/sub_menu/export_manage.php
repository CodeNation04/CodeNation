<?php
// export_manage.php
$isSuperAdmin = true; // 최고관리자 여부에 따라 true/false 분기
?>
<link rel="stylesheet" href="css/export_manage.css" />
<link rel="stylesheet" href="css/pagination.css" />
<script src="js/pagination.js"></script>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code_id      = $_POST["department"] ?? '';
        $host_name    = $_POST["hostname"]   ?? '';
        $user_name    = $_POST["username"]   ?? '';
        $externally   = $_POST["target"]     ?? '';
        $exter_status = $_POST["status"]     ?? '';
    }

    $session_code = $_SESSION["code_id"];
    $session_type = $_SESSION["admin_type"];
?>

<div class="export-wrapper">
    <div class="export-header">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>외부 반출 요청 관리</h1>
        </div>
        <div class="header-controls">
            <select id="filterSelect" onchange="filterByStatus()">
                <option value="전체">전체</option>
                <option value="요청">요청</option>
                <option value="승인">승인</option>
                <option value="반려">반려</option>
            </select>
            <button id="searchToggleBtn" class="btn-confirm" onclick="toggleSearch()">검색</button>
        </div>
    </div>

    <!-- 검색 필터 -->
    <div class="search-section" id="searchSection" style="display: none;">
        <form id="searchForm" method="POST" action="/?url=MainController/index&page=export">
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <input type="hidden" id="code_id" value="<?=$code_id?>" />
            <input type="hidden" id="host_name" value="<?=$host_name?>" />
            <input type="hidden" id="user_name" value="<?=$user_name?>" />
            <input type="hidden" id="externally" value="<?=$externally?>" />
            <input type="hidden" id="exter_status" value="<?=$exter_status?>" />
            <?php endif; ?>

            <input type="hidden" id="admin_code_id" value="<?=$session_code?>" />
            <input type="hidden" id="admin_type" value="<?=$session_type?>" />

            <?php if ($session_type === '최고관리자'): ?>
            <div class="form-row">
                <label>부서명</label>
                <select class="form-input" id="dept_name" name="department" required></select>
            </div>
            <?php endif; ?>

            <div class="form-row">
                <label>Hostname</label>
                <input type="text" name="hostname" placeholder="Hostname" />
            </div>
            <div class="form-row">
                <label>사용자명</label>
                <input type="text" name="username" placeholder="사용자명" />
            </div>
            <div class="form-row">
                <label>외부 반출 대상</label>
                <input type="text" name="target" placeholder="예: USB, 이메일 등" />
            </div>
            <div class="form-row">
                <label>처리 상태</label>
                <select name="status">
                    <option value="">전체</option>
                    <option value="요청">요청</option>
                    <option value="승인">승인</option>
                    <option value="반려">반려</option>
                </select>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn-confirm">목록 검색</button>
                <button type="button" class="btn-cancel" onclick="cancelSearch()">취소</button>
            </div>
        </form>
    </div>

    <!-- 결과 테이블 -->
    <div class="table-wrapper">
        <table class="export-table">
            <thead>
                <tr>
                    <th onclick="setSort('code_name')">부서명 <span id="sort-code_name">⇅</span></th>
                    <th onclick="setSort('host_name')">Hostname <span id="sort-host_name">⇅</span></th>
                    <th onclick="setSort('user_name')">사용자명 <span id="sort-user_name">⇅</span></th>
                    <th onclick="setSort('externally')">외부 반출 대상 <span id="sort-externally">⇅</span></th>
                    <th onclick="setSort('reason')">사유 <span id="sort-reason">⇅</span></th>
                    <th onclick="setSort('exter_status')">처리 상태 <span id="sort-exter_status">⇅</span></th>
                    <th>처리</th>
                </tr>
            </thead>
            <tbody id="exportTableBody"></tbody>
        </table>
        <div class="pagination"></div>
    </div>
</div>

<script>
const isSuperAdmin = <?php echo $isSuperAdmin ? 'true' : 'false'; ?>;
let dataList = [];
let currentSort = {
    column: null,
    direction: 'asc'
};

function setSort(column) {
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }
    filterByStatus();
    updateSortArrows();
}

function updateSortArrows() {
    const cols = ['code_name', 'host_name', 'user_name', 'externally', 'reason', 'exter_status'];
    cols.forEach(col => {
        const el = document.getElementById(`sort-${col}`);
        if (!el) return;
        el.textContent = (col === currentSort.column) ?
            (currentSort.direction === 'asc' ? '▲' : '▼') :
            '⇅';
    });
}

$(document).ready(function() {
    const codePram = $("#code_id").val();
    const hostPram = $("#host_name").val();
    const namePram = $("#user_name").val();
    const externallyPram = $("#externally").val();
    const statusPram = $("#exter_status").val();
    const adminCodeId = $("#admin_code_id").val();
    const adminType = $("#admin_type").val();

    $.ajax({
        type: "GET",
        dataType: "json",
        data: {
            code_id: codePram,
            host_name: hostPram,
            user_name: namePram,
            externally: externallyPram,
            exter_status: statusPram,
            admin_code_id: adminCodeId,
            admin_type: adminType
        },
        url: "/?url=ExportController/exportList",
        success: function(result) {
            dataList = result;
            filterByStatus();
        },
        error: function(err) {
            console.error("데이터 불러오기 실패:", err);
        }
    });

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/selectDeptList",
        success: function(result) {
            let html = '';
            result.forEach(item => {
                html += `<option value="${item.code_id}">${item.code_name}</option>`;
            });
            $("#dept_name").html(html);
        },
        error: function(err) {
            console.error("부서 데이터 실패:", err);
        }
    });
});

function processRequest(id, status) {
    if (confirm(`${status} 처리하시겠습니까?`)) {
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                id,
                status
            },
            url: "/?url=ExportController/exportStatusReq",
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                }
            },
            error: function(err) {
                console.error("요청 실패:", err);
            }
        });
    }
}

function filterByStatus() {
    const status = $("#filterSelect").val();
    const filtered = status === "전체" ?
        dataList :
        dataList.filter(d => d.exter_status === status);

    // 정렬 적용
    let sorted = [...filtered];
    if (currentSort.column) {
        sorted.sort((a, b) => {
            const aVal = a[currentSort.column] || '';
            const bVal = b[currentSort.column] || '';
            return currentSort.direction === 'asc' ?
                aVal.localeCompare(bVal, 'ko') :
                bVal.localeCompare(aVal, 'ko');
        });
    }

    // 페이징 처리
    setupPagination({
        data: sorted,
        itemsPerPage: 10,
        containerId: "exportTableBody",
        paginationClass: "pagination",
        renderRowHTML: pageData => pageData.map(item => `
            <tr>
                <td>${item.code_name}</td>
                <td>${item.host_name}</td>
                <td>${item.user_name}</td>
                <td>${item.externally}</td>
                <td>${item.reason}</td>
                <td>${item.exter_status}</td>
                <td>${
                    item.exter_status === "요청"
                        ? `<button class='btn-confirm' onclick='processRequest(${item.exter_idx},"승인")'>승인</button>
                           <button class='btn-cancel' onclick='processRequest(${item.exter_idx},"반려")'>반려</button>`
                        : `-`
                }</td>
            </tr>
        `).join('')
    });
}

function toggleSearch() {
    $("#searchToggleBtn, #filterSelect").hide();
    $("#searchSection").css("display", "flex");
}

function cancelSearch() {
    $("#searchToggleBtn, #filterSelect").show();
    $("#searchSection").hide();
}
</script>