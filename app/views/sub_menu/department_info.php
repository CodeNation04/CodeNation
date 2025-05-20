<?php
// department_info.php
?>
<link rel="stylesheet" href="css/department_info.css" />

<div class="dept-wrapper">
    <div class="dept-header">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>부서 정보 관리</h1>
        </div>
        <a href="?url=MainController/index&page=dept&form=show">
            <button class="btn-confirm">+</button>
        </a>
    </div>

    <?php 
    $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; 
    ?>

    <?php if (!$formMode): ?>
    <div class="dept-table-wrapper" id="deptTableBody"></div>
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
                <input type="email" id="email" name="email" />
            </div>
            <div class="form-row">
                <label for="note">비고</label>
                <textarea id="note" name="etc"></textarea>
            </div>

            <div class="form-buttons-wrapper">
                <div class="form-buttons">
                    <button type="button" class="btn-confirm" onclick="submitBtn()">저장</button>
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
                const isDisabled = registeredDeptCodes.includes(item.code_id);
                html +=
                    `<option value="${item.code_id}" ${isDisabled ? 'disabled' : ''}>${item.code_name}${isDisabled ? ' (등록됨)' : ''}</option>`;
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
            $("#dept_name").val(result.code_code_id).prop("disabled", true);
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
            renderPage(1);
        },
        error: function(err) {
            console.error("부서 목록 불러오기 실패:", err);
        }
    });
}

function renderPage(page) {
    const itemsPerPage = 10;
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = resultData.slice(start, end);

    let html = `<table class="dept-table">
        <thead>
            <tr>
                <th>부서명</th>
                <th>담당자명</th>
                <th>전화번호</th>
                <th>이메일</th>
                <th>비고</th>
                <th>수정</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody>`;

    pageData.forEach((item) => {
        html += `
        <tr>
            <td>${item.code_name}</td>
            <td>${item.user_name}</td>
            <td>${item.user_phone}</td>
            <td>${item.user_email}</td>
            <td>${item.etc}</td>
            <td><button class="edit-btn" onclick="editDept(${item.user_idx})">수정</button></td>
            <td><button class="delete-btn" onclick="deleteDept(${item.user_idx})">삭제</button></td>
        </tr>`;
    });

    html += '</tbody></table>';

    // 페이징 UI
    const totalPages = Math.ceil(resultData.length / itemsPerPage);
    html += '<div class="pagination">';
    if (page > 1) html += `<button onclick="renderPage(${page - 1})">이전</button>`;
    for (let i = 1; i <= totalPages; i++) {
        html += `<button onclick="renderPage(${i})" class="${i === page ? 'active' : ''}">${i}</button>`;
    }
    if (page < totalPages) html += `<button onclick="renderPage(${page + 1})">다음</button>`;
    html += '</div>';

    document.getElementById("deptTableBody").innerHTML = html;
}

function editDept(num) {
    location.href = "/?url=MainController/index&page=dept&form=show&type=moddify&num=" + num;
}

function submitBtn() {
    const name = $("#dept_name").val();
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