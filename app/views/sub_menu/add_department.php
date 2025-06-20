<?php
// add_department.php
?>
<link rel="stylesheet" href="/css/add_department.css" />
<link rel="stylesheet" href="css/pagination.css">
<link rel="stylesheet" href="css/sub_title.css">
<script src="js/pagination.js"></script>

<?php
 $session_type = $_SESSION['admin_type'];
 if($session_type !== "최고관리자"){
    echo "<script>
            alert('잘못된 접근입니다.')
            location.href='/?url=MainController/index'
            </script>";
 }
?>

<div class="add-dept-wrapper">
    <?php 
        $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; 
        $typeMode = isset($_GET['type']) && $_GET['type'] === 'moddify'; 
    ?>

    <div class="wrapper">
        <div class="form-header">
            <div style="display:flex; align-items:center">
                <h1 style="font-weight:900; margin-right:12px;">| </h1>
                <h1>부서 관리</h1>
            </div>
            <?php if (!$formMode): ?>
            <a href="?url=MainController/index&page=department&form=show">
                <button class="btn-register">등록</button>
            </a>
            <?php endif; ?>
        </div>

        <?php if (!$formMode): ?>
        <div class="dept-table-wrapper" id="task-table-wrapper"></div>
        <div class="pagination"></div>
        <?php else: ?>
        <div class="form-card">
            <form id="addDeptForm" method="post" action="/?url=AgentUserController/deptInfoAdd">
                <input type="hidden" id="type" name="type" required />
                <div class="form-row">
                    <label for="dept_name">부서명</label>
                    <input type="text" id="dept_name" name="dept_name" required placeholder="예: 보안팀" />
                </div>
                <?php if (!$typeMode): ?>
                <div class="form-row">
                    <label for="dept_code">부서코드</label>
                    <input type="text" id="dept_code" name="dept_code" required placeholder="예: SEC001" />
                </div>
                <?php else: ?>
                <input type="hidden" id="dept_code" name="dept_code" required />
                <?php endif; ?>
                <div class="form-buttons-wrapper">
                    <div class="form-buttons">
                        <button type="submit" class="btn-confirm">확인</button>
                        <a href="?url=MainController/index&page=department">
                            <button type="button" class="btn-cancel">취소</button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <script>
    let departmentList = [];
    let currentSort = {
        column: null,
        direction: 'asc'
    };

    $(document).ready(function() {
        const params = new URLSearchParams(window.location.search);
        const type = params.get('type');
        const num = params.get('num');

        if (type === 'moddify') {
            document.getElementById("type").value = type;
            $.ajax({
                type: "GET",
                dataType: "json",
                data: {
                    num: num
                },
                url: "/?url=AgentUserController/deptInfo",
                success: function(result) {
                    $("#dept_code").val(result.code_id);
                    $("#dept_name").val(result.code_name);
                },
                error: function(err) {
                    console.error("데이터 불러오기 실패:", err);
                }
            });
        }

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/?url=AgentUserController/selectDeptList",
            success: function(result) {
                departmentList = result;
                renderDepartmentTable();
            },
            error: function(err) {
                console.error("데이터 불러오기 실패:", err);
            }
        });
    });

    function renderDepartmentTable() {
        const sortedList = [...departmentList];
        if (currentSort.column) {
            sortedList.sort((a, b) => {
                const aVal = a[currentSort.column] || '';
                const bVal = b[currentSort.column] || '';
                return currentSort.direction === 'asc' ?
                    aVal.localeCompare(bVal, 'ko') :
                    bVal.localeCompare(aVal, 'ko');
            });
        }

        setupPagination({
            data: sortedList,
            itemsPerPage: 10,
            containerId: "task-table-wrapper",
            paginationClass: "pagination",
            renderRowHTML: (pageData, offset) => {
                return `<table class="dept-table">
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th onclick="setSort('code_name')">부서명 <span id="sort-code_name">⇅</span></th>
                            <th onclick="setSort('code_id')">부서코드 <span id="sort-code_id">⇅</span></th>
                            <th>수정</th>
                            <th>삭제</th>
                        </tr>
                    </thead>
                    <tbody>` +
                    pageData.map((item, i) => `
                        <tr>
                            <td>${departmentList.length - (offset + i)}</td>
                            <td>${item.code_name}</td>
                            <td>${item.code_id}</td>
                            <td><button class="edit-btn" onclick="location.href='?url=MainController/index&page=department&form=show&type=moddify&num=${item.code_id}'">수정</button></td>
                            <td><button class="delete-btn" onclick="deleteDept('${item.code_id}')">삭제</button></td>
                        </tr>`).join('') + '</tbody></table>';
            }
        });

        updateSortArrows();
    }

    function setSort(column) {
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = column;
            currentSort.direction = 'asc';
        }
        renderDepartmentTable();
    }

    function updateSortArrows() {
        const columns = ['code_name', 'code_id'];
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

    function deleteDept(num) {
        if (confirm("정말 삭제하시겠습니까?")) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "/?url=AgentUserController/deptInfoDel",
                data: {
                    num: num
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(err) {
                    console.error("삭제 실패:", err);
                }
            });
        }
    }
    </script>