<?php
// department_info.php
?>
<link rel="stylesheet" href="css/department_info.css" />

<div class="dept-wrapper">
    <div class="dept-header">
        <h2>부서 정보 관리</h2>
        <button class="btn-confirm" onclick="showForm()">등록</button>
    </div>

    <!-- 등록/수정 폼 -->
    <div class="dept-form-card" id="formSection" style="display: none;">
        <form id="deptForm">
            <input type="hidden" id="mode" value="insert" />
            <input type="hidden" id="dept_id" />

            <div class="form-row">
                <label for="dept_name">부서명</label>
                <input type="text" id="dept_name" required />
            </div>
            <div class="form-row">
                <label for="manager">담당자명</label>
                <input type="text" id="manager" required />
            </div>
            <div class="form-row">
                <label for="phone">전화번호</label>
                <input type="text" id="phone" />
            </div>
            <div class="form-row">
                <label for="email">이메일</label>
                <input type="email" id="email" />
            </div>
            <div class="form-row">
                <label for="note">비고</label>
                <textarea id="note"></textarea>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-confirm">저장</button>
                <button type="button" class="btn-cancel" onclick="hideForm()">취소</button>
            </div>
        </form>
    </div>

    <!-- 목록 테이블 -->
    <div class="dept-table-wrapper" id="tableSection">
        <table class="dept-table">
            <thead>
                <tr>
                    <th>부서명</th>
                    <th>담당자명</th>
                    <th>전화번호</th>
                    <th>이메일</th>
                    <th>비고</th>
                    <th>수정</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody id="deptTableBody"></tbody>
        </table>
    </div>
</div>

<script>
let departments = [{
        id: 1,
        name: "보안팀",
        manager: "김보안",
        phone: "010-1234-5678",
        email: "boan@example.com",
        note: ""
    },
    {
        id: 2,
        name: "인프라팀",
        manager: "홍인프라",
        phone: "010-5678-1234",
        email: "infra@example.com",
        note: "서버 관리"
    }
];

function renderTable() {
    const tbody = document.getElementById("deptTableBody");
    tbody.innerHTML = "";
    departments.forEach(dept => {
        const row = document.createElement("tr");
        row.innerHTML = `
      <td>${dept.name}</td>
      <td>${dept.manager}</td>
      <td>${dept.phone}</td>
      <td>${dept.email}</td>
      <td>${dept.note}</td>
      <td><button class="edit-btn" onclick="editDept(${dept.id})">수정</button></td>
      <td><button class="delete-btn" onclick="deleteDept(${dept.id})">삭제</button></td>
    `;
        tbody.appendChild(row);
    });
}

function showForm(isEdit = false) {
    document.getElementById("formSection").style.display = "block";
    document.getElementById("tableSection").style.display = "none";
    if (!isEdit) resetForm();
}

function hideForm() {
    document.getElementById("formSection").style.display = "none";
    document.getElementById("tableSection").style.display = "block";
    resetForm();
}

function editDept(id) {
    const dept = departments.find(d => d.id === id);
    if (!dept) return;

    document.getElementById("mode").value = "update";
    document.getElementById("dept_id").value = dept.id;
    document.getElementById("dept_name").value = dept.name;
    document.getElementById("dept_name").disabled = true;
    document.getElementById("manager").value = dept.manager;
    document.getElementById("phone").value = dept.phone;
    document.getElementById("email").value = dept.email;
    document.getElementById("note").value = dept.note;

    showForm(true);
}

function deleteDept(id) {
    if (confirm("정말 삭제하시겠습니까?")) {
        departments = departments.filter(d => d.id !== id);
        renderTable();
    }
}

function resetForm() {
    document.getElementById("mode").value = "insert";
    document.getElementById("deptForm").reset();
    document.getElementById("dept_id").value = "";
    document.getElementById("dept_name").disabled = false;
}

// 저장 시 처리

document.getElementById("deptForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const mode = document.getElementById("mode").value;
    const id = document.getElementById("dept_id").value;
    const name = document.getElementById("dept_name").value.trim();
    const manager = document.getElementById("manager").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const note = document.getElementById("note").value.trim();

    if (!name || !manager) {
        alert("부서명과 담당자명은 필수입니다.");
        return;
    }

    if (mode === "insert") {
        if (departments.some(d => d.name === name)) {
            alert("이미 존재하는 부서명입니다.");
            return;
        }
        departments.push({
            id: Date.now(),
            name,
            manager,
            phone,
            email,
            note
        });
    } else {
        const dept = departments.find(d => d.id == id);
        if (dept) {
            dept.manager = manager;
            dept.phone = phone;
            dept.email = email;
            dept.note = note;
        }
    }
    hideForm();
    renderTable();
});

// 초기 렌더
renderTable();
</script>