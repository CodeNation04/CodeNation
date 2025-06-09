<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>Agent 로그 조회</title>
    <link rel="stylesheet" href="css/agent_info.css" />
    <link rel="stylesheet" href="css/pagination.css">
    <script src="js/pagination.js"></script>
</head>

<body>
    <div class="container">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>Agent 로그 조회</h1>
        </div>
        <form method="get">
            <div>
                <label for="name">사용자명</label>
                <input type="text" id="name" name="name" placeholder="사용자명 입력">
            </div>

            <div>
                <label for="hostname">Hostname</label>
                <input type="text" id="hostname" name="hostname" placeholder="Hostname 입력">
            </div>

            <div class="dropdown-wrapper">
                <label for="type">작업 종류</label>
                <div class="custom-select-wrapper">
                    <select id="type" name="type" class="custom-select">
                        <option value="">-- 작업 종류 선택 --</option>
                        <option value="로그인">로그인</option>
                        <option value="로그아웃">로그아웃</option>
                        <option value="체크">체크</option>
                        <option value="삭제로그">삭제로그</option>
                        <option value="설정정보요청">설정 정보 요청</option>
                    </select>
                    <span class="custom-arrow">▼</span>
                </div>
            </div>

            <?php if ($_SESSION['admin_type'] === '최고관리자'): ?>
            <div class="dropdown-wrapper">
                <label for="dept">부서 선택</label>
                <div class="custom-select-wrapper">
                    <select id="dept" name="dept" class="custom-select">
                        <option value="">-- 부서 선택 --</option>
                    </select>
                    <span class="custom-arrow">▼</span>
                </div>
            </div>
            <?php endif;?>

            <div class="button-group">
                <button type="button" onclick="searchLogs()">검색</button>
            </div>
        </form>

        <div id="result" style="margin-top: 20px;"></div>

        <div class="save-buttons" style="display: none;">
            <button onclick="saveAsFile('csv')">엑셀로 저장</button>
            <button onclick="saveAsFile('txt')">텍스트로 저장</button>
        </div>

        <!-- ✅ 가운데 정렬을 위한 래퍼 div 추가 -->
        <div style="display: flex; justify-content: center; margin-top: 24px;">
            <div class="pagination" id="pagination" style="display: none;"></div>
        </div>
    </div>

    <script>
    let filteredLogs = [];
    let logs;
    let currentSort = {
        column: null,
        direction: 'asc'
    };

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/selectDeptList",
        success: function(result) {
            let html = '<option value="">전체</option>';
            result.forEach((item) => {
                html += `<option value="${item.code_id}">${item.code_name}</option>`;
            });
            $("#dept").html(html);
        },
        error: function(err) {
            console.error("부서 옵션 로딩 실패:", err);
        }
    });

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/selectLogList",
        success: function(result) {
            logs = result;
        },
        error: function(err) {
            console.error("로그 목록 불러오기 실패:", err);
        }
    });

    function searchLogs() {
        const name = document.getElementById("name").value.trim();
        const hostname = document.getElementById("hostname").value.trim();
        const type = document.getElementById("type").value.trim();
        const dept = document.getElementById("dept")?.value.trim() || "";

        filteredLogs = logs.filter(log =>
            (!name || log.user_name.includes(name)) &&
            (!hostname || log.host_name.includes(hostname)) &&
            (!type || log.work_type.includes(type)) &&
            (!dept || log.code_code_id === dept)
        );

        currentSort = {
            column: null,
            direction: 'asc'
        };

        document.querySelector('.save-buttons').style.display = filteredLogs.length > 0 ? "flex" : "none";
        document.getElementById("pagination").style.display = filteredLogs.length > 0 ? "block" : "none";

        setupPagination({
            data: filteredLogs,
            itemsPerPage: 10,
            containerId: "result",
            paginationClass: "pagination",
            renderRowHTML: renderRowHTML
        });

        updateSortArrows();
    }

    function setSort(column) {
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === "asc" ? "desc" : "asc";
        } else {
            currentSort.column = column;
            currentSort.direction = "asc";
        }

        filteredLogs.sort((a, b) => {
            let aValue = a[column] || "";
            let bValue = b[column] || "";
            return currentSort.direction === "asc" ?
                aValue.localeCompare(bValue, 'ko') :
                bValue.localeCompare(aValue, 'ko');
        });

        setupPagination({
            data: filteredLogs,
            itemsPerPage: 10,
            containerId: "result",
            paginationClass: "pagination",
            renderRowHTML: renderRowHTML
        });

        updateSortArrows();
    }

    function updateSortArrows() {
        const columns = ['code_name', 'user_name', 'host_name', 'work_type', 'work_result', 'work_info'];
        columns.forEach(col => {
            const el = document.getElementById(`sort-${col}`);
            if (!el) return;
            el.textContent = col === currentSort.column ?
                (currentSort.direction === 'asc' ? '▲' : '▼') :
                '⇅';
        });
    }

    function renderRowHTML(logs, startIndex) {
        if (logs.length === 0) return "<p>검색 결과가 없습니다.</p>";

        let html = `<table class="result-table">
            <thead>
                <tr>
                    <th onclick="setSort('code_name')">부서명 <span id="sort-code_name">⇅</span></th>
                    <th onclick="setSort('user_name')">사용자명 <span id="sort-user_name">⇅</span></th>
                    <th onclick="setSort('host_name')">Hostname <span id="sort-host_name">⇅</span></th>
                    <th onclick="setSort('work_type')">작업 종류 <span id="sort-work_type">⇅</span></th>
                    <th onclick="setSort('work_result')">작업 결과 <span id="sort-work_result">⇅</span></th>
                    <th onclick="setSort('work_info')">작업 정보 <span id="sort-work_info">⇅</span></th>
                </tr>
            </thead>
            <tbody>`;

        logs.forEach(log => {
            html += `<tr>
                <td>${log.code_name}</td>
                <td>${log.user_name}</td>
                <td>${log.host_name}</td>
                <td>${log.work_type}</td>
                <td>${log.work_result}</td>
                <td>${log.work_info}</td>
            </tr>`;
        });

        html += `</tbody></table>`;
        return html;
    }

    function saveAsFile(type) {
        if (filteredLogs.length === 0) return;

        let content = "\uFEFF부서명,사용자명,작업 종류,작업 결과,작업 정보\n";
        filteredLogs.forEach(log => {
            content +=
                `${log.code_name},${log.user_name},${log.work_type},${log.work_result},${log.work_info}\n`;
        });

        const blob = new Blob([content], {
            type: "text/plain;charset=utf-8"
        });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `agent_logs.${type}`;
        link.click();
    }
    </script>
</body>

</html>