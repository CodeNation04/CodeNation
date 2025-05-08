<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>Agent 로그 조회</title>
    <link rel="stylesheet" href="/css/agent_info.css" />
</head>

<body>
    <div class="container">
        <h2>Agent 로그 조회</h2>
        <form method="get">
            <div>
                <label for="name">사용자명</label>
                <input type="text" id="name" name="name" placeholder="사용자명 입력">
            </div>
            <div class="dropdown-wrapper">
                <label for="type">작업 종류</label>
                <div class="custom-select-wrapper">
                    <select id="type" name="type" class="custom-select">
                        <option value="">-- 작업 종류 선택 --</option>
                        <option value="로그인">로그인</option>
                        <option value="로그아웃">로그아웃</option>
                        <option value="파일요청">파일요청</option>
                    </select>
                    <span class="custom-arrow">▼</span>
                </div>
            </div>

            <div id="deptField" style="display: none;">
                <!-- 기본으로 숨김 -->
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
                <button type="button" onclick="searchLogs()">검색</button>
            </div>
        </form>

        <div id="result" style="margin-top: 20px;"></div>
        <div class="save-buttons" style="display: none;">
            <button onclick="saveAsFile('csv')">엑셀로 저장</button>
            <button onclick="saveAsFile('txt')">텍스트로 저장</button>
        </div>
    </div>

    <script>
    let isSuperAdmin = true; // true: 최고관리자, false: 중간관리자 (권한 설정)
    let currentSort = {
        column: null,
        direction: 'asc' // asc (오름차순) / desc (내림차순)
    };

    // 페이지 로드 시 부서 필드 설정
    document.addEventListener("DOMContentLoaded", function() {
        toggleDeptField();
    });

    function toggleDeptField() {
        const deptField = document.getElementById("deptField");
        if (isSuperAdmin) {
            deptField.style.display = "block";
        } else {
            deptField.style.display = "none";
        }
    }

    // 로그 검색
    function searchLogs() {
        const logs = [{
                dept: "의무기록과",
                name: "장수민",
                type: "로그인",
                result: "성공",
                info: "로그인 성공"
            },
            {
                dept: "전산실",
                name: "이민호",
                type: "로그아웃",
                result: "성공",
                info: "정상 종료"
            },
            {
                dept: "원무과",
                name: "박지현",
                type: "파일요청",
                result: "실패",
                info: "파일 없음"
            },
            {
                dept: "진료지원팀",
                name: "김영희",
                type: "로그인",
                result: "성공",
                info: "로그인 성공"
            },
            {
                dept: "의무기록과",
                name: "최민수",
                type: "파일요청",
                result: "성공",
                info: "파일 다운로드"
            },
            {
                dept: "전산실",
                name: "박준형",
                type: "로그아웃",
                result: "성공",
                info: "정상 종료"
            },
            {
                dept: "원무과",
                name: "이소라",
                type: "로그인",
                result: "성공",
                info: "로그인 성공"
            },
            {
                dept: "진료지원팀",
                name: "정예린",
                type: "파일요청",
                result: "실패",
                info: "권한 없음"
            },
            {
                dept: "의무기록과",
                name: "홍길동",
                type: "로그아웃",
                result: "성공",
                info: "정상 종료"
            },
            {
                dept: "전산실",
                name: "윤서연",
                type: "로그인",
                result: "성공",
                info: "로그인 성공"
            }
        ];

        renderTable(logs);
    }

    // ✅ 표 렌더링 함수
    function renderTable(logs) {
        const resultDiv = document.getElementById("result");
        if (currentSort.column) {
            logs.sort((a, b) => {
                return currentSort.direction === 'asc' ?
                    sortText(a[currentSort.column], b[currentSort.column]) :
                    sortText(b[currentSort.column], a[currentSort.column]);
            });
        }

        if (logs.length > 0) {
            let table = `<table class="result-table">
            <thead>
                <tr>
                    <th onclick="setSort('dept')" class="sortable">부서명 <span class="sort-arrows">↑ ↓</span></th>
                    <th onclick="setSort('name')" class="sortable">사용자명 <span class="sort-arrows">↑ ↓</span></th>
                    <th onclick="setSort('type')" class="sortable">작업종류 <span class="sort-arrows">↑ ↓</span></th>
                    <th onclick="setSort('result')" class="sortable">작업결과 <span class="sort-arrows">↑ ↓</span></th>
                    <th onclick="setSort('info')" class="sortable">작업정보 <span class="sort-arrows">↑ ↓</span></th>
                </tr>
            </thead>
            <tbody>`;
            logs.forEach(log => {
                table += `<tr>
                        <td>${log.dept}</td>
                        <td>${log.name}</td>
                        <td>${log.type}</td>
                        <td>${log.result}</td>
                        <td>${log.info}</td>
                    </tr>`;
            });
            table += `</tbody></table>`;
            resultDiv.innerHTML = table;
            document.querySelector('.save-buttons').style.display = "flex";
        } else {
            resultDiv.innerHTML = "<p>검색 결과가 없습니다.</p>";
            document.querySelector('.save-buttons').style.display = "none";
        }
        updateSortStyles();
    }

    // ✅ 정렬 설정 함수 (헤더 클릭)
    function setSort(column) {
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === "asc" ? "desc" : "asc";
        } else {
            currentSort.column = column;
            currentSort.direction = "asc";
        }
        searchLogs();
    }

    // ✅ 정렬 스타일 업데이트 (화살표)
    function updateSortStyles() {
        const headers = document.querySelectorAll(".sortable");
        headers.forEach(header => {
            const column = header.getAttribute("onclick").split("'")[1];
            const arrows = header.querySelector(".sort-arrows");

            if (column === currentSort.column) {
                arrows.innerHTML = currentSort.direction === "asc" ?
                    "↑ <span style='color: #007bff;'>↓</span>" :
                    "<span style='color: #007bff;'>↑</span> ↓";
            } else {
                arrows.innerHTML = "↑ ↓";
            }
        });
    }

    // ✅ 정렬 함수 (문자열)
    function sortText(a, b) {
        return a.localeCompare(b, 'ko', {
            numeric: true,
            sensitivity: 'base'
        });
    }

    function saveAsFile(type) {
        const resultDiv = document.getElementById("result");
        const rows = Array.from(resultDiv.querySelectorAll("tr"));
        if (rows.length < 2) return;

        let content = "\uFEFF"; // UTF-8 BOM 추가 (Excel에서 인코딩 문제 해결)
        if (type === "csv") {
            content += isSuperAdmin ? "부서명,사용자명,작업종류,작업결과,작업정보\n" : "사용자명,작업종류,작업결과,작업정보\n";
            rows.slice(1).forEach(row => {
                const cells = row.querySelectorAll("td");
                const line = Array.from(cells).map(cell => `"${cell.textContent}"`).join(",");
                content += line + "\n";
            });
        } else if (type === "txt") {
            rows.slice(1).forEach(row => {
                const cells = row.querySelectorAll("td");
                const line = Array.from(cells).map(cell => cell.textContent).join(" | ");
                content += line + "\n";
            });
        }

        const now = new Date();
        const formattedDate = now.getFullYear().toString() +
            String(now.getMonth() + 1).padStart(2, '0') +
            String(now.getDate()).padStart(2, '0') + "_" +
            String(now.getHours()).padStart(2, '0') +
            String(now.getMinutes()).padStart(2, '0');

        const filename = `log_data_${formattedDate}.${type}`;

        const blob = new Blob([content], {
            type: "text/plain;charset=utf-8"
        });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }
    </script>
</body>

</html>