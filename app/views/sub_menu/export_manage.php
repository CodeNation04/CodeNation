<?php
// export_manage.php
$isSuperAdmin = true; // 최고관리자 여부에 따라 true/false 분기
?>
<link rel="stylesheet" href="/css/export_manage.css" />

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code_id = $_POST["department"] ?? '';
        $host_name = $_POST["hostname"] ?? '';
        $user_name = $_POST["username"] ?? '';
        $externally = $_POST["target"] ?? '';
        $exter_status = $_POST["status"] ?? '';
    }
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
            <?php  if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
            <input type="hidden" id="code_id" value="<?=$code_id?>" />
            <input type="hidden" id="host_name" value="<?=$host_name?>" />
            <input type="hidden" id="user_name" value="<?=$user_name?>" />
            <input type="hidden" id="externally" value="<?=$externally?>" />
            <input type="hidden" id="exter_status" value="<?=$exter_status?>" />
            <?php }?>
            <?php if ($_SESSION['admin_type'] === '최고관리자'): ?>
            <div class="form-row">
                <label>부서명</label>
                <select class="form-input" id="dept_name" name="department" required>
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

let dataList = [];

$(document).ready(function() {
    const codePram = $("#code_id").val();
    const hostPram = $("#host_name").val();
    const namePram = $("#user_name").val();
    const externallyPram = $("#externally").val();
    const statusPram = $("#exter_status").val();

    $.ajax({
        type: "GET",
        dataType: "json",
        data: {
            code_id: codePram,
            host_name: hostPram,
            user_name: namePram,
            externally: externallyPram,
            exter_status: statusPram
        },
        url: "/?url=ExportController/exportList", // 수정할 부서 정보 API
        success: function(result) {
            result.forEach((item) => {
                dataList.push(item)
            })
            filterByStatus()
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
            result.forEach((item) => {
                console.log(item)
                html += `<option value="${item.code_id}">${item.code_name}</option>`;
            });
            $("#dept_name").html(html)
        },
        error: function(err) {
            console.error("데이터 불러오기 실패:", err);
        }
    });

})

function processRequest(id, status) {
    if (confirm(`${status} 처리하시겠습니까?`)) {
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                status: status
            },
            url: "/?url=ExportController/exportStatusReq",
            success: function(result) {
                if (result.success == true) {
                    alert(result.message);
                    location.reload();
                }
            },
            error: function(err) {
                console.error("데이터 불러오기 실패:", err);
            }
        });
    }
}

function renderTable(data) {
    const tbody = document.getElementById("exportTableBody");
    tbody.innerHTML = "";
    data.forEach(item => {
        const tr = document.createElement("tr");
        const actionButtons =
            item.exter_status === "요청" ?
            `<button class='btn-confirm' onclick='processRequest(${item.exter_idx}, "승인")'>승인</button>
        <button class='btn-cancel' onclick='processRequest(${item.exter_idx}, "반려")'>반려</button>` :
            "-";
        tr.innerHTML = `
    <td>${item.code_name}</td>
    <td>${item.host_name}</td>
    <td>${item.user_name}</td>
    <td>${item.externally}</td>
    <td>${item.reason}</td>
    <td>${item.exter_status}</td>
    <td>${actionButtons}</td>
    `;
        tbody.appendChild(tr);
    });
}

function filterByStatus() {
    const status = document.getElementById("filterSelect").value;
    console.log(status)
    const filtered = status === "전체" ? dataList : dataList.filter(d => d.exter_status === status);
    renderTable(filtered);
}

function toggleSearch() {
    document.getElementById("searchToggleBtn").style.display = "none";
    document.getElementById("filterSelect").style.display = "none";
    document.getElementById("searchSection").style.display = "flex";
}

function cancelSearch() {
    document.getElementById("searchToggleBtn").style.display = "inline-block";
    document.getElementById("filterSelect").style.display = "inline-block";
    document.getElementById("searchSection").style.display = "none";
}
</script>