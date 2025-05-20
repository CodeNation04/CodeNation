<?php
// add_department.php
?>
<link rel="stylesheet" href="/css/add_department.css" />

<div class="add-dept-wrapper">
    <?php 
        $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; 
        $typeMode = isset($_GET['type']) && $_GET['type'] === 'moddify'; 
    ?>

    <!-- 제목 + 추가 버튼 -->
    <div class="form-header">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>부서 관리</h1>
        </div>

        <?php if (!$formMode): ?>
        <a href="?url=MainController/index&page=department&form=show">
            <button class="btn-confirm">+</button>
        </a>
        <?php endif; ?>
    </div>

    <?php if (!$formMode): ?>
    <!-- ✅ 리스트 화면 -->
    <div class="dept-table-wrapper" id="task-table-wrapper"></div>
    <?php else: ?>
    <!-- ✅ 등록 폼 화면 -->
    <div class="form-card">
        <form id="addDeptForm" method="post" action="/?url=AgentUserController/deptInfoAdd">
            <input type="hidden" id="type" name="type" required />
            <div class="form-row">
                <label for="dept_name">부서명</label>
                <input type="text" id="dept_name" name="dept_name" required placeholder="예: 보안팀" />
            </div>
            <?php if (!$typeMode): ?>
            <div class="form-row">
                <label for="dept_code">부서코드</label>
                <input type="text" id="dept_code" name="dept_code" required placeholder="예: SEC001" />
            </div>
            <?php else: ?>
            <input type="hidden" id="dept_code" name="dept_code" required />
            <?php endif; ?>

            <!-- ✅ 버튼을 입력창 길이 기준으로 정렬 -->
            <div class="form-buttons-wrapper">
                <div class="form-buttons">
                    <button type="submit" class="btn-confirm">저장</button>
                    <a href="?url=MainController/index&page=department">
                        <button type="button" class="btn-cancel">취소</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    const params = new URLSearchParams(window.location.search);
    const type = params.get('type');
    const num = params.get('num');

    if (type === 'moddify') {
        document.getElementById("type").value = type;
        $.ajax({
            type: "GET",
            dataType: "json",
            data: {
                num: num
            },
            url: "/?url=AgentUserController/deptInfo",
            success: function(result) {
                $("#dept_code").val(result.code_id);
                $("#dept_name").val(result.code_name);
            },
            error: function(err) {
                console.error("데이터 불러오기 실패:", err);
            }
        });
    }

    let currentPage = 1;
    const itemsPerPage = 10;
    let resultData = [];

    function renderPage(page) {
        currentPage = page;
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageData = resultData.slice(start, end);

        let html = `<table class="dept-table">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>부서명</th>
                    <th>부서코드</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>`;

        for (let i = 0; i < pageData.length; i++) {
            const item = pageData[i];
            const number = resultData.length - ((page - 1) * itemsPerPage + i);

            html += `
                <tr>
                    <td>${number}</td>
                    <td>${item.code_name}</td>
                    <td>${item.code_id}</td>
                    <td><button class="edit-btn" onclick="location.href='?url=MainController/index&page=department&form=show&type=moddify&num=${item.code_id}'">수정</button></td>
                    <td><button class="delete-btn" onclick="deleteDept('${item.code_id}')">삭제</button></td>
                </tr>`;
        }

        html += '</tbody></table><div class="pagination">';

        const totalPages = Math.ceil(resultData.length / itemsPerPage);
        if (page > 1) html += `<button onclick="renderPage(${page - 1})">이전</button>`;
        for (let i = 1; i <= totalPages; i++) {
            html +=
                `<button onclick="renderPage(${i})" ${i === page ? 'style="font-weight:bold;"' : ''}>${i}</button>`;
        }
        if (page < totalPages) html += `<button onclick="renderPage(${page + 1})">다음</button>`;

        html += '</div>';

        const wrapper = document.getElementById("task-table-wrapper");
        if (wrapper) wrapper.innerHTML = html;
    }

    window.renderPage = renderPage;

    $.ajax({
        type: "GET",
        dataType: "json",
        url: "/?url=AgentUserController/selectDeptList",
        success: function(result) {
            resultData = result;
            renderPage(1);
        },
        error: function(err) {
            console.error("데이터 불러오기 실패:", err);
        }
    });
});

function deleteDept(num) {
    if (confirm("정말 삭제하시겠습니까?")) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/?url=AgentUserController/deptInfoDel",
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