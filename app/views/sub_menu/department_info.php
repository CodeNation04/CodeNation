<?php
// department_info.php
?>
<link rel="stylesheet" href="/css/department_info.css" />
<link rel="stylesheet" href="css/pagination.css">
<link rel="stylesheet" href="css/sub_title.css">
<script src="/js/pagination.js"></script>

<div class="wrapper">
    <div class="form-header">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>부서 정보 관리</h1>
        </div>
        <a href="?url=MainController/index&page=dept&form=show">
            <button class="btn-register">등록</button>
        </a>
    </div>

    <?php 
    $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; 
    ?>

    <?php if (!$formMode): ?>
    <div class="dept-table-wrapper" id="deptTableBody"></div>
    <div class="pagination"></div>
    <?php endif; ?>

    <?php if ($formMode): ?>
    <div class="dept-form-card" id="formSection">
        <form id="deptForm" method="post" action="/?url=AgentUserController/agentUserSubmit">
            <input type="hidden" id="type" name="type" />
            <input type="hidden" id="num" name="num" />

            <div class="form-row">
                <label for="dept_name">부서명</label>
                <select class="form-input" id="dept_name" name="department"></select>
            </div>
            <div class="form-row">
                <label for="manager">담당자명</label>
                <input type="text" id="manager" name="name" required />
            </div>
            <div class="form-row">
                <label for="phone">전화번호</label>
                <input type="text" id="phone" name="phone" />
            </div>
            <div class="form-row">
                <label for="email">이메일</label>
                <input type="email" id="email" name="email" placeholder="example@domain.com" required />
            </div>
            <div class="form-row">
                <label for="note">비고</label>
                <textarea id="note" name="etc"></textarea>
            </div>

            <div class="form-buttons-wrapper">
                <div class="form-buttons">
                    <button type="button" class="btn-confirm" onclick="submitBtn()">확인</button>
                    <a href="?url=MainController/index&page=dept">
                        <button type="button" class="btn-cancel">취소</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
let resultData = [];
let registeredDeptCodes = [];
let editingDeptCode = null;
let currentSort = {
    column: null,
    direction: 'asc'
};

window.onload = function() {
    const params = new URLSearchParams(window.location.search);
    const type = params.get('type');
    const num = params.get('num');

    if (document.getElementById("dept_name")) {
        loadRegisteredDeptCodes(() => {
            loadDeptSelectOptions(() => {
                if (type === 'moddify' && num) {
                    loadEditForm(num);
                }
            });
        });
    } else {
        loadDepartmentList();
    }
};

function loadRegisteredDeptCodes(callback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/agentUserList",
        success: function(result) {
            registeredDeptCodes = result.map(item => item.code_code_id);
            if (callback) callback();
        },
        error: function(err) {
            console.error("등록된 부서 목록 로딩 실패:", err);
        }
    });
}

function loadDeptSelectOptions(callback) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/selectDeptList",
        success: function(result) {
            let html = '';
            result.forEach((item) => {
                if (!registeredDeptCodes.includes(item.code_id) || item.code_id ===
                    editingDeptCode) {
                    html += `<option value="${item.code_id}">${item.code_name}</option>`;
                }
            });
            $("#dept_name").html(html);
            if (callback) callback();
        },
        error: function(err) {
            console.error("부서 옵션 로딩 실패:", err);
        }
    });
}

function loadEditForm(num) {
    $("#type").val("moddify");
    $.ajax({
        type: "GET",
        dataType: "json",
        data: {
            num: num
        },
        url: "/?url=AgentUserController/agentUserInfo",
        success: function(result) {
            $("#num").val(result.user_idx);
            editingDeptCode = result.code_code_id;
            loadDeptSelectOptions(() => {
                $("#dept_name").val(editingDeptCode).prop("disabled", true);
                if ($('#deptForm input[name="department"]').length === 0) {
                    $('<input type="hidden" name="department">').val(editingDeptCode).appendTo(
                        "#deptForm");
                } else {
                    $('#deptForm input[name="department"]').val(editingDeptCode);
                }
            });
            $("#manager").val(result.user_name);
            $("#phone").val(result.user_phone);
            $("#email").val(result.user_email);
            $("#note").val(result.etc);
        },
        error: function(err) {
            console.error("수정 데이터 불러오기 실패:", err);
        }
    });
}

function loadDepartmentList() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/agentUserList",
        success: function(result) {
            resultData = result;
            renderTable();
        },
        error: function(err) {
            console.error("부서 목록 불러오기 실패:", err);
        }
    });
}

function renderTable() {
    let sortedData = [...resultData];
    if (currentSort.column) {
        sortedData.sort((a, b) => {
            const aVal = a[currentSort.column] || '';
            const bVal = b[currentSort.column] || '';
            return currentSort.direction === 'asc' ?
                aVal.localeCompare(bVal, 'ko') :
                bVal.localeCompare(aVal, 'ko');
        });
    }
    setupPagination({
        data: sortedData,
        itemsPerPage: 10,
        containerId: "deptTableBody",
        paginationClass: "pagination",
        renderRowHTML: (pageData) => {
            return `<table class="dept-table">
                <thead>
                    <tr>
                        <th onclick="setSort('code_name')">부서명 <span id="sort-code_name">⇅</span></th>
                        <th onclick="setSort('user_name')">담당자명 <span id="sort-user_name">⇅</span></th>
                        <th onclick="setSort('user_phone')">전화번호 <span id="sort-user_phone">⇅</span></th>
                        <th onclick="setSort('user_email')">이메일 <span id="sort-user_email">⇅</span></th>
                        <th>비고</th>
                        <th>수정</th>
                        <th>삭제</th>
                    </tr>
                </thead>
                <tbody>` +
                pageData.map(item => `
                    <tr>
                        <td>${item.code_name}</td>
                        <td>${item.user_name}</td>
                        <td>${item.user_phone}</td>
                        <td>${item.user_email}</td>
                        <td>${item.etc}</td>
                        <td><button class="edit-btn" onclick="editDept(${item.user_idx})">수정</button></td>
                        <td><button class="delete-btn" onclick="deleteDept(${item.user_idx})">삭제</button></td>
                    </tr>`).join('') + '</tbody></table>';
        }
    });
    updateSortArrows();
}

function setSort(column) {
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }
    renderTable();
}

function updateSortArrows() {
    const columns = ['code_name', 'user_name', 'user_phone', 'user_email'];
    columns.forEach(col => {
        const el = document.getElementById(`sort-${col}`);
        if (!el) return;
        el.textContent = (col === currentSort.column) ?
            (currentSort.direction === 'asc' ? '▲' : '▼') :
            '⇅';
    });
}

function editDept(num) {
    location.href = "/?url=MainController/index&page=dept&form=show&type=moddify&num=" + num;
}

function submitBtn() {
    const name = $("#dept_name").val();
    const email = $("#email").val();
    // 이메일 비어있는지 및 형식 체크
    if (!email) {
        alert("이메일을 입력해주세요.");
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("유효한 이메일 주소를 입력해주세요.");
        return;
    }
    if (!name) {
        alert("부서명은 필수입니다.");
        return;
    }
    if (confirm("저장하시겠습니까?")) {
        $("#deptForm").submit();
    }
}

function deleteDept(num) {
    if (confirm("정말 삭제하시겠습니까?")) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/?url=AgentUserController/agentUserDel",
            data: {
                num: num
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(err) {
                console.error("삭제 실패:", err);
            }
        });
    }
}
</script>