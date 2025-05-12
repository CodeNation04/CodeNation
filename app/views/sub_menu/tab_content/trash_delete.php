<!-- 휴지통 완전 비우기 예약 탭 -->
<div class="task-form">
    <input type="hidden" name="trash_del" value="trash_del" />

    <div class="form-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0;">휴지통 완전 비우기 예약 관리</h3>
        <a href="?url=MainController/index&page=task&tab=trash_delete&form=show">
            <button class="btn-confirm">추가</button>
        </a>
    </div>

    <?php $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; ?>

    <?php if (!$formMode): ?>
    <div class="task-table-wrapper" id="task-table-wrapper">
        <!-- JS로 목록 테이블 출력 -->
    </div>
    <?php endif; ?>

    <?php if ($formMode): ?>
    <?php include('trash_delete_form.php'); ?>
    <?php endif; ?>
</div>

<script>
let currentPage = 1;
const itemsPerPage = 10;
let resultData = [];

const params = new URLSearchParams(window.location.search);
const tab = params.get('tab');

$.ajax({
    type: "GET",
    dataType: "json",
    url: "/?url=TempDelController/tempDelList",
    data:{tab:tab},
    success: function(result) {
        resultData = result;
        renderPage(1);
    },
    error: function(err) {
        console.error("데이터 불러오기 실패:", err);
    }
});

function renderPage(page) {
    currentPage = page;
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = resultData.slice(start, end);

    let html = `<table class="task-table">
        <thead>
            <tr>
                <th>번호</th>
                <th>부서명</th>
                <th>작업 주기</th>
                <th>작업 시점</th>
                <th>수정</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody>`;

    for (let i = 0; i < pageData.length; i++) {
        const number = resultData.length - ((page - 1) * itemsPerPage + i);

        html += `<tr>
            <td>${number}</td>
            <td>${pageData[i].code_name}</td>
            <td>${pageData[i].reser_date}<br />`;

        if (pageData[i].reser_date === "매월") {
            html += `${pageData[i].reser_date_day}일 ${pageData[i].reser_date_time}`;
        } else if (pageData[i].reser_date === "매주") {
            html += `${pageData[i].reser_date_time} ${pageData[i].reser_date_week}`;
        } else if (pageData[i].reser_date === "매일") {
            html += `${pageData[i].reser_date_time}`;
        } else {
            html += `${pageData[i].reser_date_ymd} ${pageData[i].reser_date_time}`;
        }

        html += `</td>
            <td>${pageData[i].schedule_type || "-"}</td>
            <td><a href="/?url=MainController/index&page=task&tab=trash_delete&form=show&type=moddify&num=${pageData[i].del_idx}">수정</a></td>
            <td><a onclick="delSubmit(${pageData[i].del_idx})">삭제</a></td>
        </tr>`;
    }

    html += `</tbody></table>`;

    const totalPages = Math.ceil(resultData.length / itemsPerPage);
    html += `<div class="pagination">`;

    if (page > 1) {
        html += `<button onclick="renderPage(${page - 1})">이전</button>`;
    }

    for (let i = 1; i <= totalPages; i++) {
        html += `<button onclick="renderPage(${i})" ${i === page ? 'style="font-weight:bold;"' : ''}>${i}</button>`;
    }

    if (page < totalPages) {
        html += `<button onclick="renderPage(${page + 1})">다음</button>`;
    }

    html += `</div>`;

    $("#task-table-wrapper").html(html);
}

function delSubmit(num){
    console.log(num)
    if(confirm("삭제하시겠습니까?")){
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {num:num},
            url: "/?url=TempDelController/tempListDelete",
            success: function(result) {
                alert(result.message);
                location.reload();
            },
            error: function(err) {
                console.error("데이터 불러오기 실패:", err);
            }
        });
    }
}
</script>