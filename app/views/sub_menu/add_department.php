<?php
// add_department.php
?>
<link rel="stylesheet" href="css/add_department.css" />

<div class="add-dept-wrapper">
    <?php $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; ?>

    <!-- 제목 + 추가 버튼 -->
    <div class="form-header">
        <div style="display:flex; align-items:center">
            <h1 style="font-weight:900; margin-right:12px;">| </h1>
            <h1>부서 관리</h1>
        </div>

        <?php if (!$formMode): ?>
        <a href="?url=MainController/index&page=department&form=show">
            <button class="btn-confirm">추가</button>
        </a>
        <?php endif; ?>
    </div>

    <?php if (!$formMode): ?>
    <!-- ✅ 리스트 화면 -->
    <div class="dept-table-wrapper">
        <table class="dept-table">
            <thead>
                <tr>
                    <th>부서명</th>
                    <th>부서코드</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>보안팀</td>
                    <td>SEC001</td>
                    <td><button class="edit-btn">수정</button></td>
                    <td><button class="delete-btn">삭제</button></td>
                </tr>
                <tr>
                    <td>인프라팀</td>
                    <td>INF002</td>
                    <td><button class="edit-btn">수정</button></td>
                    <td><button class="delete-btn">삭제</button></td>
                </tr>
                <!-- 추후 PHP 루프 처리 -->
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <!-- ✅ 등록 폼 화면 -->
    <div class="form-card">
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
                <a href="?url=MainController/index&page=department">
                    <button type="button" class="btn-cancel">취소</button>
                </a>
                <button type="submit" class="btn-confirm">저장</button>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>