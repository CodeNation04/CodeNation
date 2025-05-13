<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>중간 관리자 목록</title>
    <link rel="stylesheet" href="css/admin_info.css">
</head>

<body>

    <div class="placeholder">
        <div class="form-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin-bottom:15px;">중간 관리자 목록</h2>
            <a href="?url=MainController/index&page=admin&form=show">
                <button class="btn-confirm" id="toggle-button">추가</button>
            </a>
        </div>

        <?php $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; ?>

        <?php if ($formMode): ?>
        <!-- 중간 관리자 등록 폼 -->
        <div id="register-form">
            <form onsubmit="return registerManager(event)">
                <div class="form-fields">
                    <div class="input-wrapper">
                        <label>아이디</label>
                        <input type="text" id="mgr-id" required />
                    </div>
                    <div class="input-wrapper">
                        <label>접근 가능 IP</label>
                        <input type="text" id="mgr-ip" required />
                    </div>
                    <div class="input-wrapper">
                        <label>비밀번호</label>
                        <input type="password" id="mgr-pw" required />
                    </div>
                    <div class="input-wrapper">
                        <label>비밀번호 확인</label>
                        <input type="password" id="mgr-pw-confirm" required />
                    </div>
                </div>

                <div class="form-row full-width">
                    <label>부서명</label>
                    <select id="mgr-dept">
                        <option value="(주)에스엠에스">(주)에스엠에스</option>
                        <option value="보안팀">보안팀</option>
                        <option value="인프라팀">인프라팀</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-button">등록</button>
                    <a href="?url=MainController/index&page=admin">
                        <button type="button" class="cancel-button">취소</button>
                    </a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <!-- 중간 관리자 목록 -->
        <div id="manager-list-section">
            <table class="manager-table">
                <thead>
                    <tr>
                        <th>부서</th>
                        <th>아이디</th>
                        <th>접근가능 IP</th>
                        <th>수정</th>
                        <th>삭제</th>
                    </tr>
                </thead>
                <tbody id="manager-list">
                    <!-- 동적으로 추가될 관리자 목록 -->
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <script>
    // 페이지 로드 시 폼 상태 확인
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const formMode = urlParams.get('form');
        const editIndex = urlParams.get('edit');

        if (formMode === 'show') {
            showForm();
            if (editIndex !== null) {
                loadEditForm(editIndex);
            }
        } else {
            hideForm();
        }
    };

    // 폼 표시
    function showForm() {
        document.getElementById('register-form').style.display = 'block';
        document.getElementById('manager-list-section').style.display = 'none';
    }

    // 폼 숨기기
    function hideForm() {
        document.getElementById('register-form').style.display = 'none';
        document.getElementById('manager-list-section').style.display = 'block';
    }

    // 중간 관리자 등록/수정
    function registerManager(event) {
        event.preventDefault();
        const id = document.getElementById('mgr-id').value.trim();
        const pw = document.getElementById('mgr-pw').value.trim();
        const pwConfirm = document.getElementById('mgr-pw-confirm').value.trim();
        const ip = document.getElementById('mgr-ip').value.trim();
        const dept = document.getElementById('mgr-dept').value;

        if (pw !== pwConfirm) {
            alert("비밀번호가 일치하지 않습니다.");
            return;
        }

        if (editIndex >= 0) {
            // 수정 모드
            managerList[editIndex].pw = pw;
            managerList[editIndex].ip = ip;
            alert("중간 관리자 정보가 수정되었습니다.");
            editIndex = -1;
        } else {
            // 등록 모드
            if (managerList.some((manager) => manager.id === id)) {
                alert("이미 존재하는 아이디입니다.");
                return;
            }
            managerList.push({
                id,
                pw,
                ip,
                dept
            });
            alert("중간 관리자가 등록되었습니다.");
        }

        renderManagerList();
        window.location.href = "?url=MainController/index&page=admin"; // 목록으로 이동
    }

    // 수정 모드 로드
    function loadEditForm(index) {
        const manager = managerList[index];
        document.getElementById('mgr-id').value = manager.id;
        document.getElementById('mgr-id').disabled = true;
        document.getElementById('mgr-dept').value = manager.dept;
        document.getElementById('mgr-ip').value = manager.ip;
        editIndex = index;
        showForm();
    }

    // 중간 관리자 목록 렌더링
    function renderManagerList() {
        const list = document.getElementById('manager-list');
        list.innerHTML = '';

        managerList.forEach((manager, index) => {
            list.innerHTML += `
            <tr>
                <td>${manager.dept}</td>
                <td>${manager.id}</td>
                <td>${manager.ip}</td>
                <td style="text-align:center;">
                    <a href="?url=MainController/index&page=admin&form=show&edit=${index}">수정</a>
                </td>
                <td style="text-align:center;">
                    <button onclick="deleteManager(${index})" class="delete-btn">삭제</button>
                </td>
            </tr>
        `;
        });
    }

    // 중간 관리자 삭제
    function deleteManager(index) {
        if (confirm("정말 삭제하시겠습니까?")) {
            managerList.splice(index, 1);
            renderManagerList();
        }
    }
    </script>
</body>

</html>