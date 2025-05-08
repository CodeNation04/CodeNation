<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>Agent 정보 조회</title>
    <link rel="stylesheet" href="/css/agent_info.css" />

</head>

<body>
    <div class="container">
        <h2>Agent 정보 조회</h2>
        <form method="get">
            <div>
                <label for="name">사용자명</label>
                <input type="text" id="name" name="name" placeholder="사용자명 입력">
            </div>
            <div>
                <label for="ip">IP</label>
                <input type="text" id="ip" name="ip" placeholder="IP 입력 (예: 192.168.0.1)">
            </div>
            <div class="dropdown-wrapper">
                <label for="dept">부서 선택</label>
                <select id="dept" name="dept" class="custom-select">
                    <option value="">-- 부서 선택 --</option>
                    <option value="의무기록과">의무기록과</option>
                    <option value="전산실">전산실</option>
                    <option value="원무과">원무과</option>
                    <option value="진료지원팀">진료지원팀</option>
                </select>
            </div>
            <div class="button-group">
                <button type="button" onclick="searchAgents()">검색</button>
            </div>
        </form>

        <div id="result" style="margin-top: 20px;"></div>
        <div class="save-buttons">
            <button onclick="saveAsFile('csv')">엑셀로 저장</button>
            <button onclick="saveAsFile('txt')">텍스트로 저장</button>
        </div>
        <div class="pagination" id="pagination"></div>
    </div>

    <script>
    let filtered = [];
    let currentPage = 1;
    const itemsPerPage = 10;
    let currentSort = {
        column: null,
        direction: 'asc'
    };

    // ✅ 샘플 데이터
    const agents = Array.from({
        length: 50
    }, (_, i) => ({
        dept: ["의무기록과", "전산실", "원무과", "진료지원팀"][i % 4],
        name: `사용자${i + 1}`,
        ip: `192.168.0.${100 + i}`,
        last_login: `2025-05-08 ${String(i % 24).padStart(2, '0')}:00`
    }));

    function searchAgents() {
        const name = document.getElementById("name").value.trim();
        const ip = document.getElementById("ip").value.trim();
        const dept = document.getElementById("dept").value.trim();

        filtered = agents.filter(agent =>
            (!name || agent.name.includes(name)) &&
            (!ip || agent.ip.includes(ip)) &&
            (!dept || agent.dept === dept)
        );

        currentPage = 1;
        renderTable();
        renderPagination();
        document.querySelector('.save-buttons').style.display = filtered.length > 0 ? "flex" : "none";
    }

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

    function sortFiltered() {
        filtered.sort((a, b) => {
            const aValue = a[currentSort.column];
            const bValue = b[currentSort.column];

            if (currentSort.column === "last_login") {
                return currentSort.direction === "asc" ?
                    new Date(aValue) - new Date(bValue) :
                    new Date(bValue) - new Date(aValue);
            }

            return currentSort.direction === "asc" ? aValue.localeCompare(bValue) : bValue.localeCompare(
                aValue);
        });
    }

    function renderTable() {
        const resultDiv = document.getElementById("result");
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedData = filtered.slice(start, end);

        if (paginatedData.length > 0) {
            let table = `<table class="result-table">
                    <thead>
                        <tr>
                            <th onclick="setSort('dept')" class="sortable">부서명<span class="sort-arrows"> ↑↓</span></th>
                            <th onclick="setSort('name')" class="sortable">사용자명<span class="sort-arrows"> ↑↓</span></th>
                            <th onclick="setSort('ip')" class="sortable">IP<span class="sort-arrows"> ↑↓</span></th>
                            <th onclick="setSort('last_login')" class="sortable">최종접속일<span class="sort-arrows"> ↑↓</span></th>
                        </tr>
                    </thead>
                    <tbody>`;
            paginatedData.forEach(agent => {
                table += `<tr>
                        <td>${agent.dept}</td>
                        <td>${agent.name}</td>
                        <td>${agent.ip}</td>
                        <td>${agent.last_login}</td>
                    </tr>`;
            });
            table += `</tbody></table>`;
            resultDiv.innerHTML = table;
        } else {
            resultDiv.innerHTML = "<p>검색 결과가 없습니다.</p>";
        }
    }

    function renderPagination() {
        const paginationDiv = document.getElementById("pagination");
        const totalPages = Math.ceil(filtered.length / itemsPerPage);
        paginationDiv.style.display = totalPages > 1 ? "flex" : "none";

        let html = "";
        if (totalPages > 1) {
            if (currentPage > 1) html += `<button onclick="changePage(${currentPage - 1})">◀ 이전</button>`;
            for (let i = 1; i <= totalPages; i++) {
                html += `<button onclick="changePage(${i})" ${i === currentPage ? "class='active'" : ""}>${i}</button>`;
            }
            if (currentPage < totalPages) html += `<button onclick="changePage(${currentPage + 1})">다음 ▶</button>`;
        }
        paginationDiv.innerHTML = html;
    }

    function changePage(page) {
        currentPage = page;
        renderTable();
        renderPagination();
    }

    function saveAsFile(type) {
        const content = filtered.map(agent => `${agent.dept},${agent.name},${agent.ip},${agent.last_login}`).join("\n");
        const blob = new Blob([content], {
            type: "text/plain;charset=utf-8"
        });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `agent_data.${type}`;
        link.click();
    }
    </script>
</body>

</html>