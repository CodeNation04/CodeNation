<link rel="stylesheet" href="css/add_department.css" />

<div class="add-dept-wrapper">
    <div class="form-header">
        <h1>부서 추가</h1>
    </div>

    <form id="addDeptForm" method="post" action="/?url=DepartmentController/addDepartment">
        <div class="form-row">
            <label for="dept_name">부서명</label>
            <input type="text" id="dept_name" name="dept_name" required placeholder="예: 보안팀" />
        </div>

        <div class="form-row">
            <label for="dept_code">부서코드</label>
            <input type="text" id="dept_code" name="dept_code" required placeholder="예: SEC001" />
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn-confirm">저장</button>
            <button type="button" class="btn-cancel" onclick="history.back()">취소</button>
        </div>
    </form>
</div>