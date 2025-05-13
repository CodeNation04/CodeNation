<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>최고 관리자 정보</title>
    <link rel="stylesheet" href="css/super_admin.css">
</head>

<body>
    <div class="placeholder">
        <h2>최고 관리자 정보</h2>
        <form id="superForm" name="superForm" method="post">
            <div class="form-row">
                <label><strong>아이디</strong></label><br>
                <input type="text" value="admin" name="admin_id" readonly />
            </div>

            <div class="form-row">
                <label><strong>접근 가능 IP</strong></label><br>
                <input type="text" id="admin-ip" name="admin_ip" value="192.168.0.1" disabled />
            </div>

            <!-- 비밀번호 입력란 (수정 시에만 표시) -->
            <div id="password-fields">
                <div class="form-row">
                    <label><strong>새 비밀번호</strong></label><br>
                    <input type="password" id="admin-password" name="admin_password" placeholder="변경할 경우만 입력" />
                </div>
                <div class="form-row">
                    <label><strong>새 비밀번호 확인</strong></label><br>
                    <input type="password" id="admin-password-com" name="admin_password_com" placeholder="변경할 경우만 입력" />
                </div>
            </div>

            <div class="form-actions">
                <button id="edit-btn" type="button" onclick="toggleEditMode()">수정</button>
                <button id="save-btn" type="button" onclick="formSubmit()">저장</button>
                <button id="cancel-btn" type="button" onclick="cancelEdit()" class="cancel-btn">취소</button>
            </div>
        </form>
    </div>

    <script>
    function toggleEditMode() {
        const ipInput = document.getElementById("admin-ip");
        const passwordFields = document.getElementById("password-fields");
        const editBtn = document.getElementById("edit-btn");
        const saveBtn = document.getElementById("save-btn");
        const cancelBtn = document.getElementById("cancel-btn");

        if (ipInput.disabled) {
            // 수정 모드 활성화
            ipInput.disabled = false;
            passwordFields.style.display = "block";
            editBtn.style.display = "none";
            saveBtn.style.display = "inline-block";
            cancelBtn.style.display = "inline-block";
        } else {
            // 수정 모드 비활성화
            ipInput.disabled = true;
            passwordFields.style.display = "none";
            editBtn.style.display = "inline-block";
            saveBtn.style.display = "none";
            cancelBtn.style.display = "none";
        }
    }

    function formSubmit() {
        const ip = document.getElementById("admin-ip").value.trim();
        const pw = document.getElementById("admin-password").value.trim();
        const pwcm = document.getElementById("admin-password-com").value.trim();
        const form = document.getElementById("superForm");

        if (!ip) {
            alert("접근 가능 IP를 입력해주세요.");
            return;
        }

        if (pw && pw !== pwcm) {
            alert("비밀번호가 다릅니다. \n다시 입력해주세요.");
            return;
        }

        if (confirm("저장하시겠습니까?")) {
            form.action = "/?url=SuperAdminController/superAdmin";
            form.submit();
        }
    }

    function cancelEdit() {
        const ipInput = document.getElementById("admin-ip");
        const passwordFields = document.getElementById("password-fields");
        const editBtn = document.getElementById("edit-btn");
        const saveBtn = document.getElementById("save-btn");
        const cancelBtn = document.getElementById("cancel-btn");

        // 원래 값으로 되돌리기 (초기 값으로 설정)
        ipInput.value = "192.168.0.1"; // 여기에 초기 IP 값 설정
        document.getElementById("admin-password").value = "";
        document.getElementById("admin-password-com").value = "";

        // 수정 모드 비활성화
        ipInput.disabled = true;
        passwordFields.style.display = "none";
        editBtn.style.display = "inline-block";
        saveBtn.style.display = "none";
        cancelBtn.style.display = "none";
    }
    </script>
</body>

</html>