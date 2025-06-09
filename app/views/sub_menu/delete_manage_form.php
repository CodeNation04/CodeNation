<link rel="stylesheet" href="css/delete_manage_form.css" />

<?php
 $session_type = $_SESSION['admin_type'];
 if($session_type !== "최고관리자"){
    echo "<script>
            alert('잘못된 접근입니다.')
            location.href='/?url=MainController/index'
            </script>";
 }
?>

<div class="delete-manage-form">
    <form method="post" action="/?url=DeleteManageController/deleteManage" id="deleteManageForm"
        name="deleteManageForm">
        <input type="hidden" name="type" id="type"/>
        <input type="hidden" name="num" id="num"/>
        <!-- 부서명 -->
        <div class="form-row">
            <label for="department">부서명</label>
            <select name="department" id="department" required>
            </select>
        </div>

        <!-- 암호화 대상 파일 확장자 -->
        <div class="form-row">
            <div class="form-label-row">
                <label>암호화 대상 파일 확장자</label>
                <button type="button" class="add-btn" onclick="addField('extFields', 'file_ext')">+</button>
            </div>
            <div id="extFields" class="dynamic-field-group">
                <div class="dynamic-field">
                    <div class="input-wrapper">
                        <input type="hidden" id="file_exts" name="file_exts" />
                        <input type="text" name="file_ext" placeholder="예: docx" required />
                        <button type="button" class="remove-btn" disabled style="visibility: hidden;">×</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 예외 폴더 경로 -->
        <div class="form-row">
            <div class="form-label-row">
                <label>예외 폴더 경로</label>
                <button type="button" class="add-btn" onclick="addField('excludeFields', 'exclude_path')">+</button>
            </div>
            <div id="excludeFields" class="dynamic-field-group">
                <div class="dynamic-field">
                    <div class="input-wrapper">
                        <input type="hidden" id="exclude_paths" name="exclude_paths" />
                        <input type="text" name="exclude_path" placeholder="예: C:/예외1" required />
                        <button type="button" class="remove-btn" disabled style="visibility: hidden;">×</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 버튼 -->
        <div class="form-buttons-wrapper">
            <div class="form-row buttons">
                <button type="button" id="submitBtn">확인</button>
                <button type="button" onclick="cancelForm()">취소</button>
            </div>
        </div>
    </form>
</div>

<script>
 $(document).ready(function(){
    document.getElementById("deleteManageForm").reset();

    const params = new URLSearchParams(window.location.search);
    const type = params.get('type');
    const num = params.get('num');

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
            $("#department").html(html)
        },
        error: function(err) {
            console.error("데이터 불러오기 실패:", err);
        }
    });

    if(type === 'moddify'){
        $("#type").val(type);
        $("#num").val(num);

        $.ajax({
            type: "GET",
            dataType: "json",
            data: { num: num },
            url: "/?url=DeleteManageController/deleteManageInfo",
            success: function(result) {
                console.log(result)

                let file_ext = result.file_ext.split(",").map(e => e.trim()).filter(e => e);
                if (!file_ext.length) return;

                let container = document.getElementById("extFields");
                let baseInput = container.querySelector('input[name="file_ext"]');
                baseInput.value = file_ext[0];

                file_ext.slice(1).forEach(ext => {
                    container.insertAdjacentHTML("beforeend", `
                        <div class="dynamic-field">
                            <div class="input-wrapper">
                                <input type="text" name="file_ext" placeholder="예: docx" value="${ext}" required />
                                <button type="button" class="remove-btn" onclick="this.closest('.dynamic-field').remove()">×</button>
                            </div>
                        </div>
                    `);
                });

                let exclude_path = result.exclude_path.split(",").map(e => e.trim()).filter(e => e);
                if (!exclude_path.length) return;

                let container2 = document.getElementById("excludeFields");
                let baseInput2 = container2.querySelector('input[name="exclude_path"]');
                baseInput2.value = exclude_path[0];

                exclude_path.slice(1).forEach(ext => {
                    container2.insertAdjacentHTML("beforeend", `
                        <div class="dynamic-field">
                            <div class="input-wrapper">
                                <input type="text" name="exclude_path" placeholder="예: C:" value="${ext}" required />
                                <button type="button" class="remove-btn" onclick="this.closest('.dynamic-field').remove()">×</button>
                            </div>
                        </div>
                    `);
                });

                $("#department").val(result.code_code_id).attr("selected",true);
                
            },
            error: function(err) {
                console.error("데이터 불러오기 실패:", err.responseText);
            }
        });
    }
 })


$("#submitBtn").click(function(){
    const form = $("#deleteManageForm");
    const formData = form.serializeArray();
    let file_exts = document.querySelectorAll("input[name=file_ext]");
    let exclude_paths = document.querySelectorAll("input[name=exclude_path]");
    let str = "";
    let str2 = "";
    for (file_ext of file_exts) {
            if (str !== "") {
                str += ", ";
            }
            str += file_ext.value;
    }

    for (exclude_path of exclude_paths) {
            if (str2 !== "") {
                str2 += ", ";
            }
            str2 += exclude_path.value;
    }

    $("#file_exts").val(str);
    $("#exclude_paths").val(str2);
    
    if (confirm("저장하시겠습니까?")) {
        form.submit();
    }
})

function addField(containerId, inputName) {
    const container = document.getElementById(containerId);
    const div = document.createElement("div");
    div.className = "dynamic-field";

    const wrapper = document.createElement("div");
    wrapper.className = "input-wrapper";

    const input = document.createElement("input");
    input.type = "text";
    input.name = inputName;
    input.required = true;

    const removeBtn = document.createElement("button");
    removeBtn.type = "button";
    removeBtn.className = "remove-btn";
    removeBtn.textContent = "x";
    removeBtn.onclick = function() {
        if (container.querySelectorAll(".dynamic-field").length > 1) {
            container.removeChild(div);
        } else {
            alert("최소 1개 항목은 필요합니다.");
        }
    };

    wrapper.appendChild(input);
    wrapper.appendChild(removeBtn);
    div.appendChild(wrapper);
    container.appendChild(div);
}
</script>