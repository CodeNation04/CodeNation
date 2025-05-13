<?php
// department_info.php
?>
<link rel="stylesheet" href="css/department_info.css" />

<div class="dept-wrapper">
    <div class="dept-header">
        <h2>부서 정보 관리</h2>
        <a href="?url=MainController/index&page=dept&form=show">
            <button class="btn-confirm">등록</button>
        </a>
    </div>

    <?php 
    $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; 
    ?>

    <!-- 목록 테이블 -->
    <?php if (!$formMode): ?>
    <div class="dept-table-wrapper" id="deptTableBody"></div>
    <?php endif; ?>

    <!-- 등록/수정 폼 -->
    <?php if ($formMode): ?>
    <div class="dept-form-card" id="formSection">
        <form id="deptForm">
            <input type="hidden" id="mode" value="insert" />
            <input type="hidden" id="dept_id" />

            <div class="form-row">
                <label for="dept_name">부서명</label>
                <input type="text" id="dept_name" required />
            </div>
            <div class="form-row">
                <label for="manager">담당자명</label>
                <input type="text" id="manager" required />
            </div>
            <div class="form-row">
                <label for="phone">전화번호</label>
                <input type="text" id="phone" />
            </div>
            <div class="form-row">
                <label for="email">이메일</label>
                <input type="email" id="email" />
            </div>
            <div class="form-row">
                <label for="note">비고</label>
                <textarea id="note"></textarea>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-confirm">저장</button>
                <a href="?url=MainController/index&page=dept">
                    <button type="button" class="btn-cancel">취소</button>
                </a>
            </div>
        </form>
    </div>
    <script>
    window.onload = function() {
        renderTable();
        const urlParams = new URLSearchParams(window.location.search);
        const editId = urlParams.get('edit');
        if (editId) loadEditForm(parseInt(editId));
    };
    </script>
    <?php endif; ?>
</div>
<script>
let resultData = [];

// 최초 리스트 로딩 (DB에서 데이터 불러오기)
window.onload = function() {
    loadDepartmentList();
};

// DB에서 부서 목록 불러오기
function loadDepartmentList() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=TempDelController/tempDelList", // 부서 목록 API
        success: function(result) {
            resultData = result;
            renderPage(1);
        },
        error: function(err) {
            console.error("데이터 불러오기 실패:", err);
        }
    });
}

// 페이지 렌더링
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

    pageData.forEach((item, index) => {
        html += `
        <tr>
            <td>${item.code_name}</td>
            <td>${item.name}</td>
            <td>${item.phone}</td>
            <td>${item.email}</td>
            <td>${item.etc}</td>
            <td><button class="edit-btn" onclick="editDept(${item.del_idx})">수정</button></td>
            <td><button class="delete-btn" onclick="deleteDept(${item.del_idx})">삭제</button></td>
        </tr>`;
    });

    html += '</tbody></table>';
    document.getElementById("deptTableBody").innerHTML = html;
}

// 수정 모드 처리 (DB에서 불러오기)
function editDept(num) {
    $.ajax({
        type: "GET",
        dataType: "json",
        data: {
            num: num
        },
        url: "/?url=TempDelController/tempDelInfo", // 수정할 부서 정보 API
        success: function(result) {
            $("#dept_id").val(result.del_idx);
            $("#dept_name").val(result.code_name).prop("readonly", true); // 부서명 수정 불가
            $("#manager").val(result.name);
            $("#phone").val(result.phone);
            $("#email").val(result.email);
            $("#note").val(result.etc);
            $("#mode").val("update");
            history.pushState({}, '',
                `?url=MainController/index&page=department&form=show&type=moddify&num=${result.del_idx}`
                );
        },
        error: function(err) {
            console.error("데이터 불러오기 실패:", err);
        }
    });
}

// 저장/수정 버튼 클릭 시
document.getElementById("deptForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const mode = $("#mode").val();
    const id = $("#dept_id").val();
    const name = $("#dept_name").val().trim();
    const manager = $("#manager").val().trim();
    const phone = $("#phone").val().trim();
    const email = $("#email").val().trim();
    const note = $("#note").val().trim();

    if (!name) {
        alert("부서명은 필수입니다.");
        return;
    }

    // DB로 저장/수정 요청
    const url = mode === "update" ?
        "/?url=TempDelController/tempDelUpdate" :
        "/?url=TempDelController/tempDelInsert";

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: {
            id: id,
            name: name,
            manager: manager,
            phone: phone,
            email: email,
            note: note
        },
        success: function(response) {
            alert(response.message);
            history.pushState({}, '', '?url=MainController/index&page=department');
            loadDepartmentList(); // 목록 다시 불러오기
        },
        error: function(err) {
            console.error("저장/수정 실패:", err);
        }
    });
});

// 삭제
function deleteDept(num) {
    if (confirm("정말 삭제하시겠습니까?")) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/?url=TempDelController/tempDelDelete",
            data: {
                num: num
            },
            success: function(response) {
                alert(response.message);
                loadDepartmentList();
            },
            error: function(err) {
                console.error("삭제 실패:", err);
            }
        });
    }
}
</script>