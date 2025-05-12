<link rel="stylesheet" href="css/delete_manage.css" />

<div class="delete-manage-wrapper">
    <!-- 제목 + 등록 버튼 -->
    <div class="title-bar">
        <h2>삭제 환경 관리</h2>
        <button id="toggleFormBtn">등록</button>
    </div>

    <!-- 등록/수정 폼 -->
    <div id="formContainer" style="display: none;">
        <?php include "delete_manage_form.php"; ?>
    </div>

    <!-- 리스트 -->
    <div id="listContainer" class="delete-manage-list">
        <table>
            <thead>
                <tr>
                    <th>부서명</th>
                    <th>암호화 대상 확장자</th>
                    <th>예외 폴더</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
                <tr data-department="보안팀" data-ext="docx,xlsx,pdf" data-exclude="C:/예외1,D:/예외2">
                    <td>보안팀</td>
                    <td>docx, xlsx, pdf</td>
                    <td>C:/예외1, D:/예외2</td>
                    <td><button class="edit-btn">수정</button></td>
                    <td><button class="delete-btn">삭제</button></td>
                </tr>
                <tr data-department="개발팀" data-ext="js,ts,java" data-exclude="D:/log,E:/temp">
                    <td>개발팀</td>
                    <td>js, ts, java</td>
                    <td>D:/log, E:/temp</td>
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

// 등록 버튼
toggleBtn.addEventListener("click", () => {
    document.getElementById("form-title").innerText = "삭제 환경 등록";
    document.getElementById("submitBtn").innerText = "등록";
    document.getElementById("form").reset();
    formContainer.style.display = "block";
    listContainer.style.display = "none";
    toggleBtn.style.display = "none";
});

// 수정 버튼
document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        const row = e.target.closest("tr");
        document.getElementById("department").value = row.dataset.department;
        document.getElementById("file_ext").value = row.dataset.ext;
        document.getElementById("exclude_path").value = row.dataset.exclude;

        document.getElementById("form-title").innerText = "삭제 환경 수정";
        document.getElementById("submitBtn").innerText = "수정";

        formContainer.style.display = "block";
        listContainer.style.display = "none";
        toggleBtn.style.display = "none";
    });
});

// 삭제 버튼
document.querySelectorAll(".delete-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        if (confirm("정말 삭제하시겠습니까?")) {
            alert("삭제 처리 예정 (DB 연동 필요)");
        }
    });
});

function cancelForm() {
    formContainer.style.display = "none";
    listContainer.style.display = "block";
    toggleBtn.style.display = "inline-block";
}
</script>