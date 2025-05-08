<script>
let currentPage = 1;
const itemsPerPage = 10;
let resultData = [];

// 초기 데이터 불러오기
$.ajax({
    type: "GET",
    dataType: "json",
    url: "/?url=TempDelController/tempDelList",
    success: function(result) {
        resultData = result;
        renderPage(1);
    },
    error: function(err) {
        console.error("데이터 불러오기 실패:", err);
    }
});

// 페이지 렌더링 함수
function renderPage(page) {
    currentPage = page;
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = resultData.slice(start, end);

    let resultHtml = `<table class=\"task-table\">
        <thead>
            <tr>
                <th>부서명</th>
                <th>작업 주기</th>
                <th>작업 대상</th>
                <th>작업 시점</th>
                <th>수정</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody>`;

    for (let i = 0; i < pageData.length; i++) {
        const item = pageData[i];
        resultHtml += `
            <tr>
                <td>${item.code_code_id}</td>
                <td>${item.period}<br />2025-01-01 14:00:00</td>
                <td>${item.del_target}</td>
                <td>${item.work_potin}</td>
                <td><a href=\"#\">수정</a></td>
                <td><a href=\"#\">삭제</a></td>
            </tr>`;
    }

    resultHtml += `</tbody></table>`;

    const totalPages = Math.ceil(resultData.length / itemsPerPage);
    resultHtml += `<div class=\"pagination\">`;

    if (page > 1) {
        resultHtml += `<button onclick=\"renderPage(${page - 1})\">이전</button>`;
    }

    for (let i = 1; i <= totalPages; i++) {
        resultHtml +=
            `<button onclick=\"renderPage(${i})\" ${i === page ? 'style=\"font-weight:bold;\"' : ''}>${i}</button>`;
    }

    if (page < totalPages) {
        resultHtml += `<button onclick=\"renderPage(${page + 1})\">다음</button>`;
    }

    resultHtml += `</div>`;

    $("#task-table-wrapper").html(resultHtml);
}
</script>

<div class="task-form">
    <input type="hidden" name="temp_del" value="temp_del" />

    <div class="form-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0;">임시파일 삭제 예약 추가</h3>
        <a href="?url=MainController/index&page=task&tab=temp_delete&form=show">
            <button class="btn-confirm">추가</button>
        </a>
    </div>

    <?php
    $formMode = isset($_GET['form']) && $_GET['form'] === 'show';
    ?>

    <?php if (!$formMode): ?>
    <!-- 목록 테이블만 보이는 영역 -->
    <div class="task-table-wrapper" id="task-table-wrapper">
    </div>
    <?php endif; ?>

    <!-- 추가 폼은 form=show일 때만 보임 -->
    <?php if ($formMode): ?>
    <?php include('temp_delete_form.php'); ?>
    <?php endif; ?>