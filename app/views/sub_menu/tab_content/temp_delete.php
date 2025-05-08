<!-- temp_delete.php -->
<script>
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
                    resultHtml += `
                                        <tr>
                                            <td>${result[i].code_code_id}</td>
                                            <td>${result[i].period}<br />2025-01-01 14:00:00</td>
                                            <td>${result[i].del_target}</td>
                                            <td>${result[i].work_potin}</td>
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
