<!-- views/sub_menu/delete_manage.php -->
<link rel="stylesheet" href="css/delete_manage.css" />

<div class="delete-manage-wrapper">
    <!-- 제목 + 등록 버튼 -->
    <div class="title-bar">
        <h2>삭제 환경 관리</h2>
        <button id="toggleFormBtn">등록</button>
    </div>

    <!-- 등록/수정 폼 (초기 숨김) -->
    <div id="formContainer" style="display: none;">
        <?php include "delete_manage_form.php"; ?>
    </div>

    <!-- 리스트 -->
    <div id="listContainer" class="delete-manage-list">
        <table>
            <thead>
                <tr>
                    <th>부서명</th>
                    <th>설정 변경 허용</th>
                    <th>삭제 방법</th>
                    <th>덮어쓰기 횟수</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
                <!-- 샘플 데이터 -->
                <tr data-department="정보보안팀" data-allow="1" data-method="DoD 5220.22-M" data-count="3">
                    <td>정보보안팀</td>
                    <td>허용</td>
                    <td>DoD 5220.22-M</td>
                    <td>3</td>
                    <td><button class="edit-btn">수정</button></td>
                    <td><button class="delete-btn">삭제</button></td>
                </tr>
                <tr data-department="개발팀" data-allow="0" data-method="Quick Erase-FF" data-count="1">
                    <td>개발팀</td>
                    <td>불허</td>
                    <td>Quick Erase-FF</td>
                    <td>1</td>
                    <td><button class="edit-btn">수정</button></td>
                    <td><button class="delete-btn">삭제</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
const formContainer = document.getElementById("formContainer");
const listContainer = document.getElementById("listContainer");
const toggleBtn = document.getElementById("toggleFormBtn");

// 등록 버튼 클릭 → 폼 보이고 리스트 숨김
toggleBtn.addEventListener("click", () => {
    document.getElementById("form-title").innerText = "삭제 환경 등록";
    document.getElementById("submitBtn").innerText = "등록";
    document.getElementById("deleteManageForm").reset();

    formContainer.style.display = "block";
    listContainer.style.display = "none";
    toggleBtn.style.display = "none";
});

// 수정 버튼 클릭 → 폼에 기존 값 입력
document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        const row = e.target.closest("tr");
        document.getElementById("department").value = row.dataset.department;
        document.querySelector(`input[name="allow_change"][value="${row.dataset.allow}"]`).checked =
            true;
        document.getElementById("delete_method").value = row.dataset.method;
        document.getElementById("overwrite_count").value = row.dataset.count;

        document.getElementById("form-title").innerText = "삭제 환경 수정";
        document.getElementById("submitBtn").innerText = "수정";

        formContainer.style.display = "block";
        listContainer.style.display = "none";
        toggleBtn.style.display = "none";
    });
});

// 삭제 버튼 클릭 시 (기능은 이후 구현 가능)
document.querySelectorAll(".delete-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        const confirmed = confirm("정말 삭제하시겠습니까?");
        if (confirmed) {
            // 삭제 처리 로직 (예: Ajax 또는 폼 전송 등)
            alert("삭제 처리 예정 (DB 연동 필요)");
        }
    });
});

// 취소 버튼 (폼 내부에서 호출)
function cancelForm() {
    formContainer.style.display = "none";
    listContainer.style.display = "block";
    toggleBtn.style.display = "inline-block";
}
</script>