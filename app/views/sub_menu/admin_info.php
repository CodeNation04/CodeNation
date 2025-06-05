<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>중간 관리자 목록</title>
    <link rel="stylesheet" href="css/admin_info.css">
    <link rel="stylesheet" href="css/sub_title.css">
</head>

<body>

    <div class="wrapper">
        <div class="form-header">
            <div style="display:flex; align-items:center">
                <h1 style="font-weight:900; margin-right:12px;">| </h1>
                <h1>중간 관리자 목록</h1>
            </div>
            <a href="?url=MainController/index&page=admin&form=show">
                <button class="btn-register">등록</button>
            </a>
        </div>

        <?php $formMode = isset($_GET['form']) && $_GET['form'] === 'show'; ?>

        <?php if ($formMode): ?>
        <!-- 중간 관리자 등록 폼 -->
        <div id="register-form">
            <form onsubmit="return registerManager(event)" method="POST" action="/?url=AdminInfoController/adminInfo">
                <input type="hidden" id="type" name="type" />
                <div class="form-fields">
                    <div class="input-wrapper">
                        <label>아이디</label>
                        <?php
                            $type = $_GET['type'] ?? ""; 
                            if($type !== "moddify"){
                        ?>
                        <div id="id_input">
                            <input type="text" id="mgr-id" name="admin_id" required />
                            <button class="check_btn" onclick="duplicateCheck()">ID중복확인</button>
                        </div>
                        <?php }else{ ?>
                        <input type="text" id="mgr-id" required disabled />
                        <input type="hidden" id="admin_id" name="admin_id" />
                        <?php } ?>
                        <input type="hidden" id="duplicate" />
                    </div>
                    <div class="input-wrapper">
                        <label>접근 가능 IP</label>
                        <input type="text" id="mgr-ip" name="ip" required />
                    </div>
                    <div class="input-wrapper">
                        <label>비밀번호</label>
                        <input type="password" id="mgr-pw" name="pw" required />
                    </div>
                    <div class="input-wrapper">
                        <label>비밀번호 확인</label>
                        <input type="password" id="mgr-pw-confirm" required />
                    </div>
                </div>

                <div class="form-row full-width">
                    <label>부서명</label>
                    <select id="mgr-dept" name="department">
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-button">확인</button>
                    <a href="?url=MainController/index&page=admin">
                        <button type="button" class="cancel-button">취소</button>
                    </a>
                </div>
            </form>
        </div>
        <?php else: ?>
        <!-- 중간 관리자 목록 -->
        <div id="manager-list-section">
            <table class="manager-table">
                <thead>
                    <tr>
                        <th onclick="setSort('code_name')">부서 <span id="sort-code_name">⇅</span></th>
                        <th onclick="setSort('id')">아이디 <span id="sort-id">⇅</span></th>
                        <th onclick="setSort('access_ip')">접근가능 IP <span id="sort-access_ip">⇅</span></th>
                        <th>수정</th>
                        <th>삭제</th>
                    </tr>
                </thead>
                <tbody id="manager-list">
                    <!-- 동적으로 추가될 관리자 목록 -->
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <script>
        let managerList = [];
        let currentSort = {
            column: null,
            direction: 'asc'
        };

        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const formMode = urlParams.get('form');
            const editIndex = urlParams.get('edit');
            const typeIndex = urlParams.get('type');
            let list_section = document.getElementById('manager-list-section');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/?url=AdminInfoController/adminInfoList",
                success: function(result) {
                    managerList = result;
                    renderManagerList();
                },
                error: function(err) {
                    console.error("데이터 불러오기 실패:", err);
                }
            });

            if (formMode === 'show') {
                document.getElementById('register-form').style.display = 'block';
                if (list_section) list_section.style.display = 'none';
            } else {
                document.getElementById('register-form').style.display = 'none';
                if (list_section) list_section.style.display = 'block';
            }

            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/?url=AgentUserController/selectDeptList",
                success: function(result) {
                    let html = '';
                    result.forEach((item) => {
                        html +=
                        `<option value="${item.code_id}">${item.code_name}</option>`;
                    });
                    $("#mgr-dept").html(html);
                },
                error: function(err) {
                    console.error("데이터 불러오기 실패:", err);
                }
            });

            if (typeIndex == "moddify") {
                document.getElementById("type").value = typeIndex;

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    data: {
                        id: editIndex
                    },
                    url: "/?url=AdminInfoController/adminInfoObj",
                    success: function(result) {
                        $("#mgr-id").val(result.id);
                        $("#admin_id").val(result.id);
                        $("#mgr-pw").val(result.pw_decoded);
                        $("#mgr-pw-confirm").val(result.pw_decoded);
                        $("#mgr-ip").val(result.access_ip);
                        $("#mgr-dept").val(result.code_code_id).trigger("change");
                    },
                    error: function(err) {
                        console.error("데이터 불러오기 실패:", err);
                    }
                });
            }
        });

        function renderManagerList() {
            const list = document.getElementById('manager-list');
            list.innerHTML = '';

            const sortedList = [...managerList];
            if (currentSort.column) {
                sortedList.sort((a, b) => {
                    const aVal = a[currentSort.column] || '';
                    const bVal = b[currentSort.column] || '';
                    return currentSort.direction === 'asc' ?
                        aVal.localeCompare(bVal, 'ko') :
                        bVal.localeCompare(aVal, 'ko');
                });
            }

            sortedList.forEach((manager) => {
                list.innerHTML += `
                    <tr>
                        <td>${manager.code_name}</td>
                        <td>${manager.id}</td>
                        <td>${manager.access_ip}</td>
                        <td style="text-align:center;">
                            <a href="?url=MainController/index&page=admin&form=show&type=moddify&edit=${manager.id}" class="edit-btn">수정</a>
                        </td>
                        <td style="text-align:center;">
                            <button onclick="deleteManager('${manager.id}')" class="delete-btn">삭제</button>
                        </td>
                    </tr>
                `;
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
            renderManagerList();
        }

        function updateSortArrows() {
            const columns = ['code_name', 'id', 'access_ip'];
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

        function deleteManager(index) {
            if (confirm("정말 삭제하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: {
                        id: index
                    },
                    url: "/?url=AdminInfoController/adminInfoDelete",
                    success: function(result) {
                        if (result.success == true) {
                            alert(`${result.message}`);
                            window.location.href = '/?url=MainController/index&page=admin';
                        }
                    },
                    error: function(err) {
                        console.error("데이터 불러오기 실패:", err);
                    }
                });
            }
        }

        function duplicateCheck() {
            const mgr_id = document.getElementById("mgr-id").value;
            $.ajax({
                type: "POST",
                dataType: "json",
                data: {
                    id: mgr_id
                },
                url: "/?url=AdminInfoController/duplicateCheck",
                success: function(result) {
                    alert(result.message);
                    document.getElementById("duplicate").value = result.success;
                },
                error: function(err) {
                    console.error("데이터 불러오기 실패:", err);
                }
            });
        }
        </script>
</body>

</html>