<link rel="stylesheet" href="/css/delete_manage_form.css" />

<div class="delete-manage-form">
    <form method="post" action="/?url=DeleteManageController/deleteManage" id="deleteManageForm"
        name="deleteManageForm">
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
        <div class="form-row buttons">
            <button type="button" id="submitBtn">확인</button>
            <button type="button" onclick="cancelForm()">취소</button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
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
})

$("#submitBtn").click(function() {
    const form = $("#deleteManageForm");
    const formData = form.serializeArray();

    console.log(formData)
    if (confirm("저장하시겠습니까?")) {
        // form.submit();
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