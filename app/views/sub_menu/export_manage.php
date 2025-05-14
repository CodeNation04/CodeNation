<?php
// export_manage.php
$isSuperAdmin = true; // 최고관리자 여부에 따라 true/false 분기
?>
<link rel="stylesheet" href="css/export_manage.css" />

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
        <form id="searchForm">
            <?php if ($_SESSION['admin_type'] === '최고관리자'): ?>
            <div class="form-row">
                <label>부서명</label>
                <select class="form-input" id="dept_name" name="department" required>
                    <option value="">부서 선택</option>
                    <option value="network">(주)에스엠에스</option>
                    <option value="security">보안팀</option>
                    <option value="infra">인프라팀</option>
                </select>
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
                    <th>부서명</th>
                    <th>Hostname</th>
                    <th>사용자명</th>
                    <th>외부 반출 대상</th>
                    <th>사유</th>
                    <th>처리 상태</th>
                    <th>처리</th>
                </tr>
            </thead>
            <tbody id="exportTableBody"></tbody>
        </table>
    </div>
</div>

<script>
const isSuperAdmin = <?php echo $isSuperAdmin ? 'true' : 'false'; ?>;

let dummyData = [{
        id: 1,
        department: "보안팀",
        hostname: "PC-01",
        username: "홍길동",
        target: "USB",
        reason: "업무 보고용",
        status: "요청"
    },
    {
        id: 2,
        department: "인프라팀",
        hostname: "PC-02",
        username: "김인프라",
        target: "이메일",
        reason: "외부 협업",
        status: "승인"
    },
    {
        id: 3,
        department: "개발팀",
        hostname: "PC-03",
        username: "이개발",
        target: "웹업로드",
        reason: "업무자료 제출",
        status: "반려"
    }
];

function renderTable(data) {
    const tbody = document.getElementById("exportTableBody");
    tbody.innerHTML = "";
    data.forEach(item => {
        const tr = document.createElement("tr");
        const actionButtons =
            item.status === "요청" ?
            `<button class='btn-confirm' onclick='processRequest(${item.id}, "승인")'>승인</button>
           <button class='btn-cancel' onclick='processRequest(${item.id}, "반려")'>반려</button>` :
            "-";
        tr.innerHTML = `
      <td>${item.department}</td>
      <td>${item.hostname}</td>
      <td>${item.username}</td>
      <td>${item.target}</td>
      <td>${item.reason}</td>
      <td>${item.status}</td>
      <td>${actionButtons}</td>
    `;
        tbody.appendChild(tr);
    });
}

function processRequest(id, status) {
    if (!confirm(`${status} 처리하시겠습니까?`)) return;
    fetch('/?url=ExportController/process', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id,
                status
            })
        })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            dummyData = dummyData.map(d => (d.id === id ? {
                ...d,
                status
            } : d));
            filterByStatus();
        })
        .catch(err => alert("처리 실패: " + err));
}

function filterByStatus() {
    const status = document.getElementById("filterSelect").value;
    const filtered = status === "전체" ? dummyData : dummyData.filter(d => d.status === status);
    renderTable(filtered);
}

function toggleSearch() {
    document.getElementById("searchToggleBtn").style.display = "none";
    document.getElementById("searchSection").style.display = "flex";
}

function cancelSearch() {
    document.getElementById("searchToggleBtn").style.display = "inline-block";
    document.getElementById("searchSection").style.display = "none";
}

// 최초: 전체 상태 렌더링
filterByStatus();
</script>