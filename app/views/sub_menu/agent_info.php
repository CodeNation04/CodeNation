<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>Agent 정보 조회</title>
    <link rel="stylesheet" href="css/agent_info.css" />

</head>

<body>
    <div class="container">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>Agent 정보 조회</h1>
        </div>
        <form method="get">
            <div>
                <label for="name">사용자명</label>
                <input type="text" id="name" name="name" placeholder="사용자명 입력">
            </div>
            <div>
                <label for="ip">IP</label>
                <input type="text" id="ip" name="ip" placeholder="IP 입력 (예: 192.168.0.1)">
            </div>
            <div>
                <label for="hostname">Hostname</label>
                <input type="text" id="hostname" name="hostname" placeholder="Hostname 입력">
            </div>

            <!-- 최고관리자만 부서검색 가능 -->
            <?php if ($_SESSION['admin_type'] === '최고관리자'): ?>
            <div class="dropdown-wrapper">
                <label for="dept">부서 선택</label>
                <div class="custom-select-wrapper">
                    <select id="dept" name="dept" class="custom-select">
                        <option value="">-- 부서 선택 --</option>
                        <!-- 샘플부서, db에서 불러와야함 -->
                    </select>
                    <span class="custom-arrow">▼</span>
                </div>
            </div>
            <?php endif;?>

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

     $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/selectDeptList",
        success: function(result) {
            let html = '<option value="">전체</option>';
            result.forEach((item) => {
                html +=
                    `<option value="${item.code_id}">${item.code_name}</option>`;
            });
            $("#dept").html(html);
        },
        error: function(err) {
            console.error("부서 옵션 로딩 실패:", err);
        }
    });

    let agents;

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/agentUserList",
        success: function(result) {
            agents = result;
        },
        error: function(err) {
            console.error("부서 목록 불러오기 실패:", err);
        }
    });

    function searchAgents() {
        const name = document.getElementById("name").value.trim();
        const ip = document.getElementById("ip").value.trim();
        const hostname = document.getElementById("hostname").value.trim();
        const dept = document.getElementById("dept").value.trim();
        console.log("agents :: ",agents)
        filtered = agents.filter(agent =>
            (!name || agent.user_name.includes(name)) &&
            (!ip || agent.user_ip.includes(ip)) &&
            (!hostname || agent.host_name.includes(hostname)) &&
            (!dept || agent.code_code_id === dept)
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
        console.log(paginatedData)
        if (paginatedData.length > 0) {
            let table = `<table class="result-table">
                    <thead>
                        <tr>
                            <th onclick="setSort('dept')" class="sortable">부서명<span class="sort-arrows"> ↑↓</span></th>
                            <th onclick="setSort('name')" class="sortable">사용자명<span class="sort-arrows"> ↑↓</span></th>
                            <th onclick="setSort('ip')" class="sortable">IP<span class="sort-arrows"> ↑↓</span></th>
                              <th onclick="setSort('hostname')" class="sortable">Hostname<span class="sort-arrows"> ↑↓</span></th>
                            <th onclick="setSort('last_login')" class="sortable">최종접속일<span class="sort-arrows"> ↑↓</span></th>
                        </tr>
                    </thead>
                    <tbody>`;
            paginatedData.forEach(agent => {
                table += `<tr>
                        <td>${agent.code_name}</td>
                        <td>${agent.user_name}</td>
                        <td>${agent.user_ip}</td>
                        <td>${agent.host_name}</td>
                        <td>${agent.update_date}</td>
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

    function changePage(page) {
        currentPage = page;
        renderTable();
        renderPagination();
    }

    function saveAsFile(type) {
        if (filtered.length === 0) return;

        let content = "\uFEFF부서명,사용자명,IP,최종접속일\n";

        filtered.forEach(agent => {
            content += `${agent.dept},${agent.name},${agent.ip},${agent.last_login}\n`;
        });

        const blob = new Blob([content], {
            type: "text/plain;charset=utf-8"
        });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `agent_info_${new Date().toISOString().slice(0, 16).replace('T', '_')}.${type}`;
        link.click();
    }
    </script>
</body>

</html>