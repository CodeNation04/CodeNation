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
                        <th>부서</th>
                        <th>아이디</th>
                        <th>접근가능 IP</th>
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
    <!-- </div> -->

    <script>
    // 페이지 로드 시 폼 상태 확인
    let managerList = [];
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const formMode = urlParams.get('form');
        const editIndex = urlParams.get('edit');
        const typeIndex = urlParams.get('type');
        let list_section = document.getElementById('manager-list-section')

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/?url=AdminInfoController/adminInfoList",
            success: function(result) {
                result.forEach((item) => {
                    managerList.push(item);
                })
                renderManagerList()
            },
            error: function(err) {
                console.error("데이터 불러오기 실패:", err);
            }
        });

        if (formMode === 'show') {
            showForm();
        } else {
            hideForm();
        }

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/?url=AgentUserController/selectDeptList",
            success: function(result) {
                let html = '';
                result.forEach((item) => {
                    console.log(item)
                    html += `<option value="${item.code_id}">${item.code_name}</option>`;
                });
                $("#mgr-dept").html(html)
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
                    $("#mgr-id").val(result.id)
                    $("#admin_id").val(result.id)
                    $("#mgr-pw").val(result.pw_decoded)
                    $("#mgr-pw-confirm").val(result.pw_decoded)
                    $("#mgr-ip").val(result.access_ip)
                    $("#mgr-dept").val(result.code_code_id).trigger("change");
                },
                error: function(err) {
                    console.error("데이터 불러오기 실패:", err);
                }
            });
        }

        // 폼 표시
        function showForm() {
            document.getElementById('register-form').style.display = 'block';
            if (list_section) {
                document.getElementById('manager-list-section').style.display = 'none'
            };
        }

        // 폼 숨기기
        function hideForm() {
            document.getElementById('register-form').style.display = 'none';
            if (list_section) {
                document.getElementById('manager-list-section').style.display = 'block'
            };
        }

        // 중간 관리자 등록/수정
        function registerManager(event) {
            event.preventDefault();
            const id = document.getElementById('mgr-id').value.trim();
            const pw = document.getElementById('mgr-pw').value.trim();
            const pwConfirm = document.getElementById('mgr-pw-confirm').value.trim();
            const ip = document.getElementById('mgr-ip').value.trim();
            const dept = document.getElementById('mgr-dept').value;

            if (pw !== pwConfirm) {
                alert("비밀번호가 일치하지 않습니다.");
                return;
            }

            if (editIndex >= 0) {
                // 수정 모드
                managerList[editIndex].pw = pw;
                managerList[editIndex].ip = ip;
                alert("중간 관리자 정보가 수정되었습니다.");
                editIndex = -1;
            } else {
                // 등록 모드
                const duplicate = document.getElementById("duplicate").value
                if (duplicate !== true) {
                    alert("중복체크를 확인해주세요.");
                    return;
                } else if (duplicate == null || duplicate == "" || duplicate == undefined) {
                    alert("중복체크를 확인해주세요.");
                    return;
                }
            }

            window.location.href = "?url=MainController/index&page=admin"; // 목록으로 이동
        }

        // 중간 관리자 목록 렌더링
        function renderManagerList() {
            const list = document.getElementById('manager-list');
            if (list) {
                list.innerHTML = '';

                managerList.forEach((manager, index) => {
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
            }
        }
    })

    // 중간 관리자 삭제
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
        console.log(mgr_id)
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                id: mgr_id
            },
            url: "/?url=AdminInfoController/duplicateCheck",
            success: function(result) {
                console.log(result)
                alert(result.message)
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