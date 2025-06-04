<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>감사 로그 조회</title>
    <link rel="stylesheet" href="css/agent_info.css" />
    <link rel="stylesheet" href="css/pagination.css">
    <script src="/js/pagination.js"></script>
</head>

<body>
    <div class="container">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>감사 로그 조회</h1>
        </div>
        <form method="get">
            <div>
                <label for="id">아이디</label>
                <input type="text" id="id" name="id" placeholder="아이디 입력">
            </div>

            <div class="dropdown-wrapper">
                <label for="type">작업 종류</label>
                <div class="custom-select-wrapper">
                    <select id="type" name="type" class="custom-select">
                        <option value="">-- 작업 종류 선택 --</option>
                        <option value="로그인">로그인</option>
                        <option value="로그아웃">로그아웃</option>
                        <option value="예약 추가">예약 추가</option>
                        <option value="예약 삭제">예약 삭제</option>
                        <option value="중간관리자 등록">중간관리자 등록</option>
                        <option value="중간관리자 수정">중간관리자 수정</option>
                        <option value="부서 등록">부서 등록</option>
                        <option value="부서 수정">부서 수정</option>
                        <option value="암호화 환경 등록">암호화 환경 등록</option>
                        <option value="암호화 환경 수정">암호화 환경 수정</option>
                        <option value="암호화 환경 삭제">암호화 환경 삭제</option>
                        <option value="외부 반출 요청 승인/반려">외부 반출 요청 승인/반려</option>
                    </select>
                    <span class="custom-arrow">▼</span>
                </div>
            </div>

            <div class="dropdown-wrapper">
                <label for="dept">부서 선택</label>
                <div class="custom-select-wrapper">
                    <select id="dept" name="dept" class="custom-select">
                        <option value="">-- 부서 선택 --</option>
                    </select>
                    <span class="custom-arrow">▼</span>
                </div>
            </div>

            <div class="button-group">
                <button type="button" onclick="searchLogs()">검색</button>
            </div>
        </form>

        <div id="result" style="margin-top: 20px;"></div>
        <div class="pagination" id="pagination" style="display: none;"></div>
    </div>

    <script>
    let filteredLogs = [];
    let currentSort = {
        column: null,
        direction: 'asc'
    };

    // 부서 옵션 불러오기
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

    let logs;

    // 로그 데이터 불러오기
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/selectAdminLogList",
        success: function(result) {
            logs = result;
        },
        error: function(err) {
            console.error("감사 로그 불러오기 실패:", err);
        }
    });

    // 검색
    function searchLogs() {
        const id = document.getElementById("id").value.trim();
        const type = document.getElementById("type").value.trim();
        const dept = document.getElementById("dept")?.value.trim() || "";

        filteredLogs = logs.filter(log =>
            (!id || log.admin_id.includes(id)) &&
            (!type || log.work_type.includes(type)) &&
            (!dept || log.code_code_id === dept)
        );

        currentSort = {
            column: null,
            direction: 'asc'
        };

        setupPagination({
            data: filteredLogs,
            itemsPerPage: 10,
            containerId: "result",
            paginationClass: "pagination",
            renderRowHTML: renderRowHTML
        });

        updateSortArrows();
    }

    // 정렬
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

            if (column === "time") {
                return currentSort.direction === "asc" ?
                    new Date(aValue) - new Date(bValue) :
                    new Date(bValue) - new Date(aValue);
            }

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
        const columns = ['dept', 'id', 'type', 'info', 'time'];
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

    function renderRowHTML(logs, startIndex) {
        if (logs.length === 0) return "<p>검색 결과가 없습니다.</p>";

        let html = `<table class="result-table">
            <thead>
                <tr>
                    <th onclick="setSort('dept')">부서명 <span id="sort-dept">⇅</span></th>
                    <th onclick="setSort('id')">아이디 <span id="sort-id">⇅</span></th>
                    <th onclick="setSort('type')">작업 종류 <span id="sort-type">⇅</span></th>
                    <th onclick="setSort('info')">작업 정보 <span id="sort-info">⇅</span></th>
                    <th onclick="setSort('time')">작업 시각 <span id="sort-time">⇅</span></th>
                </tr>
            </thead>
            <tbody>`;

        logs.forEach(log => {
            html += `<tr>
                <td>${log.code_name}</td>
                <td>${log.admin_id}</td>
                <td>${log.work_type}</td>
                <td>${log.work_info}</td>
                <td>${log.create_date}</td>
            </tr>`;
        });

        html += `</tbody></table>`;
        return html;
    }
    </script>
</body>

</html>