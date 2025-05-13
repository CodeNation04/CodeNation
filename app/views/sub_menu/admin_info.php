<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>중간 관리자 목록</title>
    <link rel="stylesheet" href="css/admin_info.css">
</head>

<body>

    <div class="placeholder">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin-bottom:15px;">중간 관리자 목록</h2>
            <button onclick="toggleForm()" id="toggle-button">추가</button>
        </div>

        <!-- 중간 관리자 등록 폼 (초기 상태는 숨김) -->
        <div id="register-form" style="display: none;">
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
                    <button type="button" class="cancel-button" onclick="cancelEdit()">취소</button>
                </div>
            </form>
        </div>



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
    </div>

    <script>
    // 중간관리자 목록 저장할 배열
    const managerList = [];
    let editIndex = -1; // 수정 중인 관리자 인덱스

    // 폼 토글 (등록/수정 모드)
    function toggleForm() {
        const form = document.getElementById('register-form');
        const listSection = document.getElementById('manager-list-section');
        const button = document.getElementById('toggle-button');

        // 폼 표시/숨기기
        if (form.style.display === 'block') {
            form.style.display = 'none';
            listSection.style.display = 'block';
            button.textContent = '추가';
            clearForm(); // 등록 모드로 초기화
        } else {
            form.style.display = 'block';
            listSection.style.display = 'none';
            button.textContent = '목록 보기';
        }
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
        clearForm();
        toggleForm(); // 목록으로 돌아감
    }

    // 입력 폼 초기화
    function clearForm() {
        document.getElementById('mgr-id').value = '';
        document.getElementById('mgr-pw').value = '';
        document.getElementById('mgr-pw-confirm').value = '';
        document.getElementById('mgr-ip').value = '';
        document.getElementById('mgr-dept').selectedIndex = 0;
        document.getElementById('mgr-id').disabled = false;
        document.getElementById('mgr-dept').disabled = false;
        editIndex = -1;
    }

    // 수정 취소
    function cancelEdit() {
        clearForm();
        toggleForm(); // 폼 숨기고 목록으로 돌아감
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
                    <button onclick="editManager(${index})" class="edit-btn">수정</button>
                </td>
                <td style="text-align:center;">
                    <button onclick="deleteManager(${index})" class="delete-btn">삭제</button>
                </td>
            </tr>
        `;
        });
    }

    // 중간 관리자 수정
    function editManager(index) {
        editIndex = index;
        const manager = managerList[index];

        document.getElementById('mgr-id').value = manager.id;
        document.getElementById('mgr-id').disabled = true; // 아이디 수정 불가
        document.getElementById('mgr-dept').value = manager.dept;
        document.getElementById('mgr-dept').disabled = true; // 부서명 수정 불가
        document.getElementById('mgr-ip').value = manager.ip;
        document.getElementById('mgr-pw').value = '';
        document.getElementById('mgr-pw-confirm').value = '';

        toggleForm(); // 폼 보여주기
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