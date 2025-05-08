<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>감사 로그 조회</title>
    <link rel="stylesheet" href="/css/agent_info.css" />
</head>

<body>
    <div class="container">
        <h2>감사 로그 조회</h2>
        <form method="get">
            <div>
                <label for="id">아이디</label>
                <input type="text" id="id" name="id" placeholder="아이디 입력">
            </div>
            <div class="dropdown-wrapper">
                <label for="type">작업 종류</label>
                <div class="custom-select-wrapper">
                    <select id="type" name="type" class="custom-select">
                        <option value="">-- 작업 종류 선택 --</option>
                        <option value="로그인">로그인</option>
                        <option value="로그아웃">로그아웃</option>
                        <option value="예약 추가">예약 추가</option>
                        <option value="예약 삭제">예약 삭제</option>
                        <option value="중간관리자 등록">중간관리자 등록</option>
                        <option value="부서 등록">부서 등록</option>
                        <option value="삭제 환경 등록">삭제 환경 등록</option>
                        <option value="삭제 환경 삭제">삭제 환경 삭제</option>
                    </select>
                    <span class="custom-arrow">▼</span>
                </div>
            </div>

            <div class="dropdown-wrapper">
                <label for="dept">부서 선택</label>
                <div class="custom-select-wrapper">
                    <select id="dept" name="dept" class="custom-select">
                        <option value="">-- 부서 선택 --</option>
                        <option value="의무기록과">의무기록과</option>
                        <option value="전산실">전산실</option>
                        <option value="원무과">원무과</option>
                        <option value="진료지원팀">진료지원팀</option>
                    </select>
                    <span class="custom-arrow">▼</span>
                </div>
            </div>

            <div class="button-group">
                <button type="button" onclick="searchAuditLogs()">검색</button>
            </div>
        </form>

        <div id="result" style="margin-top: 20px;"></div>
    </div>

    <script>
    // 샘플데이터 (나중에 DB에서 받아올 예정)
    const auditLogs = [{
            dept: "의무기록과",
            id: "admin1",
            type: "로그인",
            info: "로그인 성공",
            time: "2025-05-08 09:00"
        },
        {
            dept: "전산실",
            id: "admin2",
            type: "로그아웃",
            info: "로그아웃 성공",
            time: "2025-05-08 09:15"
        },
        {
            dept: "원무과",
            id: "admin3",
            type: "예약 추가",
            info: "예약 성공",
            time: "2025-05-08 09:30"
        },
        {
            dept: "진료지원팀",
            id: "admin4",
            type: "예약 삭제",
            info: "예약 취소",
            time: "2025-05-08 09:45"
        },
        {
            dept: "의무기록과",
            id: "admin5",
            type: "중간관리자 등록",
            info: "등록 완료",
            time: "2025-05-08 10:00"
        },
        {
            dept: "전산실",
            id: "admin6",
            type: "부서 등록",
            info: "부서 추가",
            time: "2025-05-08 10:15"
        },
        {
            dept: "원무과",
            id: "admin7",
            type: "삭제 환경 등록",
            info: "환경 설정 완료",
            time: "2025-05-08 10:30"
        },
        {
            dept: "진료지원팀",
            id: "admin8",
            type: "삭제 환경 삭제",
            info: "환경 삭제 완료",
            time: "2025-05-08 10:45"
        },
        {
            dept: "의무기록과",
            id: "admin9",
            type: "로그인",
            info: "로그인 성공",
            time: "2025-05-08 11:00"
        },
        {
            dept: "전산실",
            id: "admin10",
            type: "로그아웃",
            info: "로그아웃 성공",
            time: "2025-05-08 11:15"
        }
    ];


    let currentSort = {
        column: null,
        direction: 'asc'
    };

    let filtered = []; // 검색된 결과 저장

    function searchAuditLogs() {
        const dept = document.getElementById('dept').value.trim();
        const id = document.getElementById('id').value.trim();
        const type = document.getElementById('type').value.trim();

        // ✅ 필터링
        filtered = auditLogs.filter(log =>
            (!dept || log.dept === dept) &&
            (!id || log.id.includes(id)) &&
            (!type || log.type === type)
        );

        renderTable();
        // ✅ 검색 후 입력 필드 초기화
        document.getElementById('dept').value = "";
        document.getElementById('id').value = "";
        document.getElementById('type').value = "";

        document.getElementById('result').style.display = 'block';
    }

    function renderTable() {
        const resultDiv = document.getElementById("result");

        if (filtered.length > 0) {
            let table = `<table class="result-table">
                <thead>
                    <tr>
                        <th onclick="setSort('dept')" class="sortable">부서명<span class="sort-arrows"> ↑↓</span></th>
                        <th onclick="setSort('id')" class="sortable">아이디<span class="sort-arrows"> ↑↓</span></th>
                        <th onclick="setSort('type')" class="sortable">작업 종류<span class="sort-arrows"> ↑↓</span></th>
                        <th onclick="setSort('time')" class="sortable">작업 시각<span class="sort-arrows"> ↑↓</span></th>
                    </tr>
                </thead>
                <tbody>`;

            filtered.forEach(log => {
                table += `<tr>
                    <td>${log.dept}</td>
                    <td>${log.id}</td>
                    <td>${log.type}</td>
                    <td>${log.time}</td>
                </tr>`;
            });

            table += `</tbody></table>`;
            resultDiv.innerHTML = table;
        } else {
            resultDiv.innerHTML = "<p>검색 결과가 없습니다.</p>";
        }
    }

    function setSort(column) {
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = column;
            currentSort.direction = 'asc';
        }
        sortFiltered();
    }

    function sortFiltered() {
        filtered.sort((a, b) => {
            if (currentSort.column === "time") {
                return new Date(a[currentSort.column]) - new Date(b[currentSort.column]);
            } else {
                return a[currentSort.column].localeCompare(b[currentSort.column], 'ko');
            }
        });

        if (currentSort.direction === "desc") {
            filtered.reverse();
        }

        renderTable();
    }
    </script>
</body>

</html>