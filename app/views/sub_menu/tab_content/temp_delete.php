<!-- temp_delete.php -->
<script>
    let obj = {
        internet_temp:{
            key:"internet_temp",
            value:"인터넷 임시파일"
        },
        cookie:{
            key:"cookie",
            value:"인터넷 쿠키파일"
        },
        history:{
            key:"history",
            value:"인터넷 작업히스토리"
        },
        windows_temp:{
            key:"windows_temp",
            value:"윈도우 임시파일"
        },
        boot:{
            key:"boot",
            value:"부팅 시 예약 실행"
        },
        shutdown:{
            key:"shutdown",
            value:"종료 시 예약 실행"
        },
    }

    $.ajax({
        type: "json",
        method : "get",
        dataType: "json",
		url : "/?url=TempDelController/tempDelList",
		success: function(result){
            console.log(result)
            let resultHtml = `<table class="task-table">
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
                for(let i = 0; i<result.length; i++){
                    var work_potin_arr = result[i].work_potin.split(",");
                    var del_target_arr = result[i].del_target.split(",");
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

                    console.log(matched_values)
                    resultHtml += `
                                        <tr>
                                            <td>${result[i].code_name}</td>
                                            <td>${result[i].reser_date}<br />2025-01-01 14:00:00</td>
                                            <td>`;
                                        for(let j = 0; j<del_target_arr.length; j++){
                                            if(j > 0){
                                                resultHtml += ` ,`;
                                            }
                                            resultHtml += `${matched_values2[j]}`;
                                        }
                    resultHtml +=     `</td>
                                        <td>`;
                                        for(let j = 0; j<work_potin_arr.length; j++){
                                            if(j > 0){
                                                resultHtml += ` ,`;
                                            }
                                            resultHtml += `${matched_values[j]}`;
                                        }
                    resultHtml +=  `</td>
                                    <td><a href="#">수정</a></td>
                                        <td><a href="#">삭제</a></td>
                                    </tr>`
                }
                resultHtml += `</tbody>
                                </table>

                                <div class="pagination">
                                    <button>이전</button>
                                    <span>1</span>
                                    <button>다음</button>
                                </div>`;
            $("#task-table-wrapper").html(resultHtml);
		},
		error:function(err){  
            console.log(err)
		}
    })
</script>
<div class="task-form">
    <input type="hidden" name="temp_del" value="temp_del"/>
    <h3>임시파일 삭제 예약 추가</h3>

    <?php
    $formMode = isset($_GET['form']) && $_GET['form'] === 'show';
    ?>

    <?php if (!$formMode): ?>
    <!-- 목록 테이블만 보이는 영역 -->
    <div class="task-table-wrapper" id="task-table-wrapper">
    </div>

    <div class="add-button-wrapper">
        <a href="?url=MainController/index&page=task&tab=temp_delete&form=show">
            <button class="btn-confirm">추가</button>
        </a>
    </div>
    <?php endif; ?>

    <!-- 추가 폼은 form=show일 때만 보임 -->
    <?php if ($formMode): ?>
    <?php include('temp_delete_form.php'); ?>
    <?php endif; ?>
