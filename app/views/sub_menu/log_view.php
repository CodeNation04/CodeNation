<!DOCTYPE html>
<html lang="ko">

<?php
 $session_type = $_SESSION['admin_type'];
 if($session_type !== "최고관리자"){
    echo "<script>
            alert('잘못된 접근입니다.')
            location.href='/?url=MainController/index'
            </script>";
 }
?>

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
                        <option value="예약 수정">예약 수정</option>
                        <option value="예약 삭제">예약 삭제</option>
                        <option value="중간관리자 등록">중간관리자 등록</option>
                        <option value="중간관리자 수정">중간관리자 수정</option>
                        <option value="중간관리자 삭제">중간관리자 삭제</option>
                        <option value="부서 등록">부서 등록</option>
                        <option value="부서 수정">부서 수정</option>
                        <option value="부서 삭제">부서 삭제</option>
                        <option value="암호화 환경 등록">암호화 환경 등록</option>
                        <option value="암호화 환경 수정">암호화 환경 수정</option>
                        <option value="암호화 환경 삭제">암호화 환경 삭제</option>
                        <option value="외부 반출 요청 승인">외부 반출 요청 승인</option>
                        <option value="외부 반출 요청 반려">외부 반출 요청 반려</option>
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

        <!-- 가운데 정렬된 페이징 -->
        <div style="display: flex; justify-content: center; margin-top: 24px;">
            <div class="pagination" id="pagination" style="display: none;"></div>
        </div>
    </div>

    <script>
    let filteredLogs = [];
    let logs = [];
    let currentSort = {
        column: null,
        direction: 'asc'
    };

    const columnKeyMap = {
        dept: "code_name",
        id: "admin_id",
        type: "work_type",
        info: "work_info",
        time: "create_date"
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
        url: "/?url=AgentUserController/selectAdminLogList",
        success: function(result) {
            logs = result;
        },
        error: function(err) {
            console.error("감사 로그 불러오기 실패:", err);
        }
    });

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
        const key = columnKeyMap[column];
        if (!key) return;

        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === "asc" ? "desc" : "asc";
        } else {
            currentSort.column = column;
            currentSort.direction = "asc";
        }

        filteredLogs.sort((a, b) => {
            let aVal = a[key] || "";
            let bVal = b[key] || "";

            if (key === "create_date") {
                return currentSort.direction === "asc" ?
                    new Date(aVal) - new Date(bVal) :
                    new Date(bVal) - new Date(aVal);
            }

            return currentSort.direction === "asc" ?
                aVal.localeCompare(bVal, 'ko') :
                bVal.localeCompare(aVal, 'ko');
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
            el.textContent = col === currentSort.column ?
                (currentSort.direction === 'asc' ? '▲' : '▼') :
                '⇅';
        });
    }

    function renderRowHTML(logs) {
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