<link rel="stylesheet" href="css/delete_manage.css" />
<link rel="stylesheet" href="css/sub_title.css" />

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
        if($page == "show"){
    ?>
    <div id="formContainer">
        <?php include "delete_manage_form.php";?>
    </div>
    <?php } else{?>
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
    </div>
    <?php }?>
</div>

<script>
const formContainer = document.getElementById("formContainer");
const listContainer = document.getElementById("listContainer");
const toggleBtn = document.getElementById("toggleFormBtn");

let deleteDataList = [];
let currentSort = {
    column: null,
    direction: 'asc'
};

toggleBtn.addEventListener("click", () => {
    location.href = "/?url=MainController/index&page=deleteData&form=show"
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
    const columns = ['code_name', 'file_ext', 'exclude_path'];
    columns.forEach(col => {
        const el = document.getElementById(`sort-${col}`);
        if (!el) return;
        if (col === currentSort.column) {
            el.textContent = currentSort.direction === 'asc' ? '▲' : '▼';
        } else {
            el.textContent = '⇅';
        }
    });
}

function renderTable() {
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
    let html = '';
    sortedList.forEach(item => {
        html += `
            <tr>
                <td>${item.code_name}</td>
                <td>${item.file_ext}</td>
                <td>${item.exclude_path}</td>
                <td><button class="edit-btn" onclick="listModidfy(${item.del_idx})">수정</button></td>
                <td><button class="delete-btn" onclick="manageListDel(${item.del_idx})">삭제</button></td>
            </tr>`;
    });
    document.getElementById("data-content").innerHTML = html;
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
                num: num
            },
            url: "/?url=DeleteManageController/manageListdelete",
            success: function(result) {
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