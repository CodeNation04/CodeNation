<link rel="stylesheet" href="/css/delete_manage.css" />

<div class="delete-manage-wrapper">
    <!-- 제목 + 등록 버튼 -->
    <div class="title-bar">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>암호화 환경 관리</h1>
        </div>
        <button id="toggleFormBtn">등록</button>
    </div>

    <!-- 등록/수정 폼 -->
    <?php 
        $page = $_GET['form'] ?? ""; 
        if($page == "show"){
    ?>
    <div id="formContainer">
        <?php include "delete_manage_form.php";?>
    </div>
    <?php } else{?>
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
            <tbody id="data-content">
            </tbody>
        </table>
    </div>
    <?php }?>
</div>

<script>
const formContainer = document.getElementById("formContainer");
const listContainer = document.getElementById("listContainer");
const toggleBtn = document.getElementById("toggleFormBtn");

// 등록 버튼
toggleBtn.addEventListener("click", () => {
    // document.getElementById("form-title").innerText = "암호화 환경 등록";
    // document.getElementById("submitBtn").innerText = "등록";
    // document.getElementById("deleteManageForm").reset();
    // formContainer.style.display = "block";
    // listContainer.style.display = "none";
    // toggleBtn.style.display = "none";
    location.href = "/?url=MainController/index&page=delete&form=show"
});

// 수정 버튼
document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        const row = e.target.closest("tr");
        document.getElementById("department").value = row.dataset.department;
        document.getElementById("file_ext").value = row.dataset.ext;
        document.getElementById("exclude_path").value = row.dataset.exclude;

        document.getElementById("form-title").innerText = "암호화 환경 수정";
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
    // formContainer.style.display = "none";
    // listContainer.style.display = "block";
    // toggleBtn.style.display = "inline-block";
    location.href = "/?url=MainController/index&page=delete";
}

$.ajax({
    type: "GET",
    dataType: "json",
    url: "/?url=DeleteManageController/deleteManageList",
    success: function(result) {
        console.log(result)
        let html;
        for (let i = 0; i < result.length; i++) {
            html += `
                        <tr>
                            <td>${result[i].code_name}</td>
                            <td>${result[i].file_ext}</td>
                            <td>${result[i].exclude_path}</td>
                            <td><button class="edit-btn" onclick="listModidfy(${result[i].del_idx})">수정</button></td>
                            <td><button class="delete-btn" onclick="manageListDel(${result[i].del_idx})">삭제</button></td>
                        </tr>
                        `;
        }
        $("#data-content").html(html)
    },
    error: function(err) {
        console.error("데이터 불러오기 실패:", err);
    }
});

function listModidfy(num) {
    location.href = "/?url=MainController/index&page=delete&form=show&type=moddify&num=" + num;
}

function manageListDel(num) {
    if (confirm("삭제하시겠습니까?")) {
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                num: num
            },
            url: "/?url=DeleteManageController/manageListdelete",
            success: function(result) {
                console.log(result)
                if (result.success == true) {
                    alert('삭제완료되었습니다.');
                    location.reload();
                }
            },
            error: function(err) {
                console.error("데이터 불러오기 실패:", err.responseText);
            }
        });
    }
}
</script>