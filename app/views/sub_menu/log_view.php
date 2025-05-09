<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>감사 로그 조회</title>
    <link rel="stylesheet" href="/css/agent_info.css" />
</head>

<body>
    <div class="container">
        <h2>감사 로그 조회</h2>
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
                        <option value="부서 등록">부서 등록</option>
                        <option value="삭제 환경 등록">삭제 환경 등록</option>
                        <option value="삭제 환경 삭제">삭제 환경 삭제</option>
                    </select>
                    <span class="custom-arrow">▼</span>
                </div>
            </div>


            <label for="dept">부서 선택</label>
            <div class="custom-select-wrapper">
                <select id="dept" name="dept" class="custom-select">
                    <option value="">-- 부서 선택 --</option>
                    <!-- 샘플부서, db에서 불러와야함 -->
                    <option value="의무기록과">의무기록과</option>
                    <option value="전산실">전산실</option>
                    <option value="원무과">원무과</option>
                    <option value="진료지원팀">진료지원팀</option>
                </select>
                <span class="custom-arrow">▼</span>
            </div>


            <div class="button-group">
                <button type="button" onclick="searchAuditLogs()">검색</button>
            </div>
        </form>


        <div id="result" style="margin-top: 20px;"></div>
        <div class="pagination" id="pagination" style="display: none;"></div>
    </div>

    <script>
    const itemsPerPage = 10;
    let currentPage = 1;
    let currentSort = {
        column: null,
        direction: 'asc'
    };
    let filtered = [];

    // ✅ 샘플데이터 (나중에 DB에서 받아올 예정)
    const auditLogs = Array.from({
        length: 50
    }, (_, i) => ({
        dept: ["의무기록과", "전산실", "원무과", "진료지원팀"][i % 4],
        id: `admin${i + 1}`,
        type: ["로그인", "로그아웃", "예약 추가", "예약 삭제", "부서 등록"][i % 5],
        info: `로그 정보 ${i + 1}`,
        time: `2025-05-08 ${String(i % 24).padStart(2, '0')}:00`
    }));

    // ✅ 로그 검색
    function searchAuditLogs() {
        const dept = document.getElementById('dept').value.trim();
        const id = document.getElementById('id').value.trim();
        const type = document.getElementById('type').value.trim();

        filtered = auditLogs.filter(log =>
            (!dept || log.dept === dept) &&
            (!id || log.id.includes(id)) &&
            (!type || log.type === type)
        );

        currentPage = 1;
        renderTable();
        renderPagination();
    }



    // ✅ 정렬 설정 함수 (헤더 클릭)
    function setSort(column) {
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === "asc" ? "desc" : "asc";
        } else {
            currentSort.column = column;
            currentSort.direction = "asc";
        }
        sortFiltered();
        renderTable();
    }

    // ✅ 정렬 함수 (검색된 결과만 정렬)
    function sortFiltered() {
        filtered.sort((a, b) => {
            const aValue = a[currentSort.column];
            const bValue = b[currentSort.column];

            if (currentSort.column === "time") {
                return currentSort.direction === "asc" ?
                    new Date(aValue) - new Date(bValue) :
                    new Date(bValue) - new Date(aValue);
            }

            return currentSort.direction === "asc" ?
                aValue.localeCompare(bValue, 'ko') :
                bValue.localeCompare(aValue, 'ko');
        });
    }

    // ✅ 표 렌더링 함수
    function renderTable() {
        const resultDiv = document.getElementById("result");
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedLogs = filtered.slice(start, end);

        if (paginatedLogs.length > 0) {
            let table = `<table class="result-table">
                <thead>
                    <tr>
                        <th onclick="setSort('dept')" class="sortable">부서명 <span>↑↓</span></th>
                        <th onclick="setSort('id')" class="sortable">아이디 <span>↑↓</span></th>
                        <th onclick="setSort('type')" class="sortable">작업 종류 <span>↑↓</span></th>
                        <th onclick="setSort('time')" class="sortable">작업 시각 <span>↑↓</span></th>
                    </tr>
                </thead>
                <tbody>`;

            paginatedLogs.forEach(log => {
                table += `<tr>
                    <td>${log.dept}</td>
                    <td>${log.id}</td>
                    <td>${log.type}</td>
                    <td>${log.time}</td>
                </tr>`;
            });

            table += `</tbody></table>`;
            resultDiv.innerHTML = table;
        } else {
            resultDiv.innerHTML = "<p>검색 결과가 없습니다.</p>";
        }
    }

    // ✅ 페이지네이션 렌더링 함수
    function renderPagination() {
        const paginationDiv = document.getElementById("pagination");
        const totalPages = Math.ceil(filtered.length / itemsPerPage);

        paginationDiv.style.display = totalPages > 1 ? "flex" : "none";
        paginationDiv.innerHTML = "";

        if (totalPages <= 1) return;

        let html =
            `<button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? "disabled" : ""}>◀ 이전</button>`;

        for (let i = Math.max(1, currentPage - 1); i <= Math.min(totalPages, currentPage + 1); i++) {
            html += `<button onclick="changePage(${i})" ${i === currentPage ? "class='active'" : ""}>${i}</button>`;
        }

        html +=
            `<button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? "disabled" : ""}>다음 ▶</button>`;
        paginationDiv.innerHTML = html;
    }

    // ✅ 페이지 변경
    function changePage(page) {
        const totalPages = Math.ceil(filtered.length / itemsPerPage);
        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;

        currentPage = page;
        renderTable();
        renderPagination();
    }
    </script>

</body>

</html>