<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>Agent 로그 조회</title>
    <link rel="stylesheet" href="/css/agent_info.css" />

</head>

<body>
    <div class="container">
        <h2>Agent 로그 조회</h2>
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


            <div class="dropdown-wrapper">
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
            </div>





            <div class="button-group">
                <button type="button" onclick="searchLogs()">검색</button>
            </div>
        </form>

        <div id="result" style="margin-top: 20px;"></div>
        <div class="save-buttons" style="display: none;">
            <button onclick="saveAsFile('csv')">엑셀로 저장</button>
            <button onclick="saveAsFile('txt')">텍스트로 저장</button>
        </div>
        <div class="pagination" id="pagination" style="display: none;"></div>
    </div>

    <script>
    let filteredLogs = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let currentSort = {
        column: null,
        direction: 'asc'
    };

    // ✅ 샘플 로그 데이터
    const logs = Array.from({
        length: 50
    }, (_, i) => ({
        dept: ["의무기록과", "전산실", "원무과", "진료지원팀"][i % 4],
        name: `사용자${i + 1}`,
        hostname: `hostname${i+1}`,
        type: ["로그인", "로그아웃", "체크", "삭제로그", "설정정보요청"][i % 5],
        result: i % 2 === 0 ? "성공" : "실패",
        info: `작업 정보 ${i + 1}`
    }));

    // ✅ 로그 검색
    function searchLogs() {
        const name = document.getElementById("name").value.trim();
        const hostname = document.getElementById("hostname").value.trim();
        const type = document.getElementById("type").value.trim();
        const dept = document.getElementById("dept").value.trim();

        filteredLogs = logs.filter(log =>
            (!name || log.name.includes(name)) &&
            (!hostname || agent.hostname.includes(hostname)) &&
            (!type || log.type === type) &&
            (!dept || log.dept === dept)
        );

        if (currentSort.column) sortFilteredLogs();
        currentPage = 1;
        renderTable();
        renderPagination();
        document.querySelector('.save-buttons').style.display = filteredLogs.length > 0 ? "flex" : "none";
    }

    // ✅ 정렬 설정 함수 (헤더 클릭)
    function setSort(column) {
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === "asc" ? "desc" : "asc";
        } else {
            currentSort.column = column;
            currentSort.direction = "asc";
        }
        sortFilteredLogs();
        renderTable();
    }

    // ✅ 정렬 함수 (검색된 결과만 정렬)
    function sortFilteredLogs() {
        filteredLogs.sort((a, b) => {
            const aValue = a[currentSort.column];
            const bValue = b[currentSort.column];

            return currentSort.direction === "asc" ? aValue.localeCompare(bValue, 'ko') : bValue.localeCompare(
                aValue, 'ko');
        });
    }

    // ✅ 표 렌더링 함수
    function renderTable() {
        const resultDiv = document.getElementById("result");
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedLogs = filteredLogs.slice(start, end);

        if (paginatedLogs.length > 0) {
            let table = `<table class="result-table">
                    <thead>
                        <tr>
                            <th onclick="setSort('dept')" class="sortable">부서명 <span>↑↓</span></th>
                            <th onclick="setSort('name')" class="sortable">사용자명 <span>↑↓</span></th>
                            <th onclick="setSort('hostname')" class="sortable">Hostname<span class="sort-arrows"> ↑↓</span></th>
                            <th onclick="setSort('type')" class="sortable">작업 종류 <span>↑↓</span></th>
                            <th onclick="setSort('result')" class="sortable">작업 결과 <span>↑↓</span></th>
                            <th onclick="setSort('info')" class="sortable">작업 정보 <span>↑↓</span></th>
                        </tr>
                    </thead>
                    <tbody>`;
            paginatedLogs.forEach(log => {
                table += `<tr>
                        <td>${log.dept}</td>
                        <td>${log.name}</td>
                        <td>${log.hostname}</td>
                        <td>${log.type}</td>
                        <td>${log.result}</td>
                        <td>${log.info}</td>
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
        const totalPages = Math.ceil(filteredLogs.length / itemsPerPage);

        if (totalPages <= 1) {
            paginationDiv.style.display = "none";
            return;
        }

        paginationDiv.style.display = "flex";
        let html =
            `<button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? "disabled" : ""}>◀ 이전</button>`;

        // ✅ 최대 3개의 페이지 번호만 표시
        for (let i = Math.max(1, currentPage - 1); i <= Math.min(totalPages, currentPage + 1); i++) {
            html += `<button onclick="changePage(${i})" ${i === currentPage ? "class='active'" : ""}>${i}</button>`;
        }

        html +=
            `<button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? "disabled" : ""}>다음 ▶</button>`;
        paginationDiv.innerHTML = html;
    }

    // ✅ 페이지 변경
    function changePage(page) {
        const totalPages = Math.ceil(filteredLogs.length / itemsPerPage);
        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;

        currentPage = page;
        renderTable();
        renderPagination();
    }

    // ✅ 저장 파일 생성
    function saveAsFile(type) {
        if (filteredLogs.length === 0) return;

        let content = "\uFEFF부서명,사용자명,작업 종류,작업 결과,작업 정보\n";
        filteredLogs.forEach(log => {
            content += `${log.dept},${log.name},${log.type},${log.result},${log.info}\n`;
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