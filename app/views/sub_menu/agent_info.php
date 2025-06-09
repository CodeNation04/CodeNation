<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>Agent 정보 조회</title>
    <link rel="stylesheet" href="css/agent_info.css" />
    <link rel="stylesheet" href="css/pagination.css">
    <script src="js/pagination.js"></script>
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
            <?php endif; ?>

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
    let agents;
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
            result.forEach(item => {
                html += `<option value="${item.code_id}">${item.code_name}</option>`;
            });
            $("#dept").html(html);
        }
    });

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/agentUserList",
        success: function(result) {
            agents = result;
        }
    });

    function searchAgents() {
        const name = document.getElementById("name").value.trim();
        const ip = document.getElementById("ip").value.trim();
        const hostname = document.getElementById("hostname").value.trim();
        const dept = document.getElementById("dept")?.value.trim() || "";

        filtered = agents.filter(agent =>
            (!name || agent.user_name.includes(name)) &&
            (!ip || agent.user_ip.includes(ip)) &&
            (!hostname || agent.host_name.includes(hostname)) &&
            (!dept || agent.code_code_id === dept)
        );

        document.querySelector('.save-buttons').style.display = filtered.length > 0 ? "flex" : "none";

        setupPagination({
            data: filtered,
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

        filtered.sort((a, b) => {
            let aValue = a[column] || "";
            let bValue = b[column] || "";

            if (column === "update_date") {
                return currentSort.direction === "asc" ?
                    new Date(aValue) - new Date(bValue) :
                    new Date(bValue) - new Date(aValue);
            }

            return currentSort.direction === "asc" ?
                aValue.localeCompare(bValue, 'ko') :
                bValue.localeCompare(aValue, 'ko');
        });

        setupPagination({
            data: filtered,
            itemsPerPage: 10,
            containerId: "result",
            paginationClass: "pagination",
            renderRowHTML: renderRowHTML
        });

        updateSortArrows();
    }

    function updateSortArrows() {
        const columns = ['code_name', 'user_name', 'user_ip', 'host_name', 'update_date'];
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

    function renderRowHTML(pageData, startIndex) {
        if (pageData.length === 0) return "<p>검색 결과가 없습니다.</p>";

        let html = `<table class="result-table">
        <thead>
            <tr>
                <th onclick="setSort('code_name')">부서명 <span id="sort-code_name">⇅</span></th>
                <th onclick="setSort('user_name')">사용자명 <span id="sort-user_name">⇅</span></th>
                <th onclick="setSort('user_ip')">IP <span id="sort-user_ip">⇅</span></th>
                <th onclick="setSort('host_name')">Hostname <span id="sort-host_name">⇅</span></th>
                <th onclick="setSort('update_date')">최종접속일 <span id="sort-update_date">⇅</span></th>
            </tr>
        </thead>
        <tbody>`;

        pageData.forEach(agent => {
            html += `<tr>
            <td>${agent.code_name}</td>
            <td>${agent.user_name}</td>
            <td>${agent.user_ip}</td>
            <td>${agent.host_name}</td>
            <td>${agent.update_date}</td>
        </tr>`;
        });

        html += `</tbody></table>`;
        return html;
    }

    function saveAsFile(type) {
        if (filtered.length === 0) return;

        let content = "\uFEFF부서명,사용자명,IP,Hostname,최종접속일\n";
        filtered.forEach(agent => {
            content +=
                `${agent.code_name},${agent.user_name},${agent.user_ip},${agent.host_name},${agent.update_date}\n`;
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