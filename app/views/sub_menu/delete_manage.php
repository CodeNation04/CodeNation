```php
<link rel="stylesheet" href="css/delete_manage.css" />
<link rel="stylesheet" href="css/sub_title.css" />
<link rel="stylesheet" href="css/pagination.css" />
<script src="js/pagination.js"></script>

<?php
// delete_manage.php
$session_type = $_SESSION['admin_type'];
if ($session_type !== "최고관리자") {
    echo "<script>
            alert('잘못된 접근입니다.')
            location.href='/?url=MainController/index'
          </script>";
}
?>

<div class="wrapper">
    <div class="form-header">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>암호화 환경 관리</h1>
        </div>
        <button id="toggleFormBtn" class="btn-register">등록</button>
    </div>

    <?php
    $page = $_GET['form'] ?? "";
    if ($page === "show") {
    ?>
    <div id="formContainer">
        <?php include "delete_manage_form.php"; ?>
    </div>
    <?php } else { ?>
    <div id="listContainer" class="delete-manage-list">
        <table>
            <thead>
                <tr>
                    <th onclick="setSort('code_name')">부서명 <span id="sort-code_name">⇅</span></th>
                    <th onclick="setSort('file_ext')">암호화 대상 확장자 <span id="sort-file_ext">⇅</span></th>
                    <th onclick="setSort('exclude_path')">예외 폴더 <span id="sort-exclude_path">⇅</span></th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody id="data-content"></tbody>
        </table>
        <div class="pagination"></div>
    </div>
    <?php } ?>
</div>

<script>
const toggleBtn = document.getElementById("toggleFormBtn");
let deleteDataList = [];
let currentSort = {
    column: null,
    direction: 'asc'
};

toggleBtn.addEventListener("click", () => {
    location.href = "/?url=MainController/index&page=deleteData&form=show";
});

function cancelForm() {
    location.href = "/?url=MainController/index&page=deleteData";
}

function setSort(column) {
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.direction = 'asc';
    }
    renderTable();
}

function updateSortArrows() {
    const cols = ['code_name', 'file_ext', 'exclude_path'];
    cols.forEach(col => {
        const el = document.getElementById(`sort-${col}`);
        if (!el) return;
        el.textContent = (col === currentSort.column) ?
            (currentSort.direction === 'asc' ? '▲' : '▼') :
            '⇅';
    });
}

function renderTable() {
    // 정렬 적용
    const sortedList = [...deleteDataList];
    if (currentSort.column) {
        sortedList.sort((a, b) => {
            const aVal = a[currentSort.column] || '';
            const bVal = b[currentSort.column] || '';
            return currentSort.direction === 'asc' ?
                aVal.localeCompare(bVal, 'ko') :
                bVal.localeCompare(aVal, 'ko');
        });
    }

    // 페이징 처리
    setupPagination({
        data: sortedList,
        itemsPerPage: 10,
        containerId: "data-content",
        paginationClass: "pagination",
        renderRowHTML: (pageData) => pageData.map(item => `
            <tr>
                <td>${item.code_name}</td>
                <td>${item.file_ext}</td>
                <td>${item.exclude_path}</td>
                <td><button class="edit-btn" onclick="listModidfy(${item.del_idx})">수정</button></td>
                <td><button class="delete-btn" onclick="manageListDel(${item.del_idx})">삭제</button></td>
            </tr>
        `).join('')
    });

    updateSortArrows();
}

function listModidfy(num) {
    location.href = "/?url=MainController/index&page=deleteData&form=show&type=moddify&num=" + num;
}

function manageListDel(num) {
    if (confirm("삭제하시겠습니까?")) {
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                num
            },
            url: "/?url=DeleteManageController/manageListdelete",
            success: function(result) {
                if (result.success) {
                    alert('삭제완료되었습니다.');
                    location.reload();
                }
            },
            error: function(err) {
                console.error("삭제 실패:", err);
            }
        });
    }
}

// 초기 데이터 로드
$.ajax({
    type: "GET",
    dataType: "json",
    url: "/?url=DeleteManageController/deleteManageList",
    success: function(result) {
        deleteDataList = result;
        renderTable();
    },
    error: function(err) {
        console.error("데이터 불러오기 실패:", err);
    }
});
</script>