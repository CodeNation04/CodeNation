<script>
let currentPage = 1;
const itemsPerPage = 10;
let resultData = [];
let obj = {
    internet_temp: {
        key: "internet_temp",
        value: "인터넷 임시파일"
    },
    cookie: {
        key: "cookie",
        value: "인터넷 쿠키파일"
    },
    history: {
        key: "history",
        value: "인터넷 작업히스토리"
    },
    windows_temp: {
        key: "windows_temp",
        value: "윈도우 임시파일"
    },
    boot: {
        key: "boot",
        value: "부팅 시 예약 실행"
    },
    shutdown: {
        key: "shutdown",
        value: "종료 시 예약 실행"
    },
}

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

function renderPage(page) {
    currentPage = page;
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageData = resultData.slice(start, end);
    console.log(pageData)
    let resultHtml = `<table class=\"task-table\">
        <thead>
            <tr>
                <th>번호</th>
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
        var work_potin_arr = pageData[i].work_potin.split(",");
        var del_target_arr = pageData[i].del_target.split(",");
        var matched_values = [];
        var matched_values2 = [];

        for (let j = 0; j < work_potin_arr.length; j++) {
            let key = work_potin_arr[j].trim(); // 공백 제거
            if (obj[key]) {
                matched_values.push(obj[key].value);
            }
        }

        for (let j = 0; j < del_target_arr.length; j++) {
            let key = del_target_arr[j].trim(); // 공백 제거
            if (obj[key]) {
                matched_values2.push(obj[key].value);
            }
        }

        const number = resultData.length - ((page - 1) * itemsPerPage + i);

        resultHtml += `
                                <tr>
                                    <td>${number}</td>
                                    <td>${pageData[i].code_name}</td>
                                    <td>${pageData[i].reser_date}<br />`
        if (pageData[i].reser_date == "매월") {

            resultHtml += `${pageData[i].reser_date_day}일 ${pageData[i].reser_date_time}`;

        } else if (pageData[i].reser_date == "매주") {

            resultHtml += `${pageData[i].reser_date_time} ${pageData[i].reser_date_week}`;

        } else if (pageData[i].reser_date == "매일") {

            resultHtml += `${pageData[i].reser_date_time}`;

        } else {

            resultHtml += `${pageData[i].reser_date_ymd} ${pageData[i].reser_date_time}`;

        }
        resultHtml += `         </td>
                                    <td>`;
        for (let j = 0; j < del_target_arr.length; j++) {
            if (j > 0) {
                resultHtml += ` ,`;
            }
            resultHtml += `${matched_values2[j]}`;
        }
        resultHtml += `</td>
                                <td>`;
        for (let j = 0; j < work_potin_arr.length; j++) {
            if (j > 0) {
                resultHtml += ` ,`;
            }
            resultHtml += `${matched_values[j]}`;
        }
        resultHtml += `</td>
                            <td><a href="#">수정</a></td>
                                <td><a href="#">삭제</a></td>
                            </tr>`
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