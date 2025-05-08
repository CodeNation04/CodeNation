<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>Agent 정보 조회</title>
    <link rel="stylesheet" href="/css/agent_info.css" />

</head>

<body>
    <div class="container">
        <h2>Agent 정보 조회</h2>
        <form method="get">
            <div>
                <label for="name">사용자명</label>
                <input type="text" id="name" name="name" placeholder="사용자명 입력">
            </div>
            <div>
                <label for="ip">IP</label>
                <input type="text" id="ip" name="ip" placeholder="IP 입력 (예: 192.168.0.1)">
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
                <button type="button" onclick="searchAgents()">검색</button>
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

    let filtered = []; // 검색된 결과 저장

    // 페이지 로드 시 부서 필드 설정
    document.addEventListener("DOMContentLoaded", function() {
        toggleDeptField();
    });

    // ✅ 부서 선택 필드 제어 (최고관리자만 보임)
    function toggleDeptField() {
        const deptField = document.querySelector(".dropdown-wrapper");
        if (isSuperAdmin) {
            deptField.style.display = "block";
        } else {
            deptField.style.display = "none";
        }
    }

    // ✅ 로그 검색
    function searchAgents() {
        const name = document.getElementById("name").value.trim();
        const ip = document.getElementById("ip").value.trim();
        const dept = document.getElementById("dept").value.trim();

        // 샘플데이터 (나중에 DB에서 받아올 예정)
        const agents = [{
                dept: "의무기록과",
                name: "장수민",
                ip: "192.168.0.103",
                last_login: "2024-04-24 02:12"
            },
            {
                dept: "전산실",
                name: "이민호",
                ip: "192.168.0.106",
                last_login: "2024-04-30 05:03"
            },
            {
                dept: "원무과",
                name: "박지현",
                ip: "192.168.0.107",
                last_login: "2024-04-26 12:07"
            },
            {
                dept: "진료지원팀",
                name: "김영희",
                ip: "192.168.0.112",
                last_login: "2024-04-26 18:57"
            },
            {
                dept: "의무기록과",
                name: "최민수",
                ip: "192.168.0.110",
                last_login: "2024-05-01 09:45"
            },
            {
                dept: "전산실",
                name: "박준형",
                ip: "192.168.0.120",
                last_login: "2024-05-01 10:10"
            },
            {
                dept: "원무과",
                name: "이소라",
                ip: "192.168.0.130",
                last_login: "2024-05-01 11:15"
            },
            {
                dept: "진료지원팀",
                name: "정예린",
                ip: "192.168.0.140",
                last_login: "2024-05-01 11:30"
            },
            {
                dept: "의무기록과",
                name: "홍길동",
                ip: "192.168.0.150",
                last_login: "2024-05-01 12:00"
            },
            {
                dept: "전산실",
                name: "윤서연",
                ip: "192.168.0.160",
                last_login: "2024-05-01 12:30"
            }
        ];

        // ✅ 필터링
        filtered = agents.filter(agent =>
            (!name || agent.name.includes(name)) &&
            (!ip || agent.ip.includes(ip)) &&
            (!dept || agent.dept === dept)
        );

        renderTable();

        // ✅ 검색 후 입력 필드 초기화
        document.getElementById("name").value = "";
        document.getElementById("ip").value = "";
        document.getElementById("dept").value = "";
    }

    // ✅ 표 렌더링 함수
    function renderTable() {
        const resultDiv = document.getElementById("result");

        if (filtered.length > 0) {
            let table = `<table class="result-table">
            <thead>
                <tr>
                    <th onclick="setSort('dept')" class="sortable">부서명 <span class="sort-arrows"> ↑↓</span></th>
                    <th onclick="setSort('name')" class="sortable">사용자명 <span class="sort-arrows"> ↑↓</span></th>
                    <th onclick="setSort('ip')" class="sortable">IP <span class="sort-arrows"> ↑↓</span></th>
                    <th onclick="setSort('last_login')" class="sortable">최종접속일 <span class="sort-arrows"> ↑↓</span></th>
                </tr>
            </thead>
            <tbody>`;
            filtered.forEach(agent => {
                table += `<tr>
                <td>${agent.dept}</td>
                <td>${agent.name}</td>
                <td>${agent.ip}</td>
                <td>${agent.last_login}</td>
            </tr>`;
            });
            table += `</tbody></table>`;
            resultDiv.innerHTML = table;
            document.querySelector('.save-buttons').style.display = "flex";
        } else {
            resultDiv.innerHTML = "<p>검색 결과가 없습니다.</p>";
            document.querySelector('.save-buttons').style.display = "none";
        }
    }

    // ✅ 정렬 설정 함수 (헤더 클릭)
    function setSort(column) {
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === "asc" ? "desc" : "asc";
        } else {
            currentSort.column = column;
            currentSort.direction = "asc";
        }
        sortFiltered();
    }

    // ✅ 정렬 함수 (검색된 결과만 정렬)
    function sortFiltered() {
        filtered.sort((a, b) => {
            if (currentSort.column === "last_login") {
                return sortDate(a[currentSort.column], b[currentSort.column]);
            } else {
                return sortText(a[currentSort.column], b[currentSort.column]);
            }
        });

        if (currentSort.direction === "desc") {
            filtered.reverse();
        }

        renderTable();
    }

    // ✅ 정렬 함수 (문자열)
    function sortText(a, b) {
        return a.localeCompare(b, 'ko', {
            numeric: true,
            sensitivity: 'base'
        });
    }

    // ✅ 정렬 함수 (날짜)
    function sortDate(a, b) {
        return new Date(a) - new Date(b);
    }




    function saveAsFile(type) {
        const resultDiv = document.getElementById("result");
        const rows = Array.from(resultDiv.querySelectorAll("tr"));
        if (rows.length < 2) return; // 조회 결과가 없을 때

        let content = "";
        if (type === "csv") {
            content = "\uFEFF"; // UTF-8 BOM 추가 (Excel에서 인코딩 문제 해결)
            content += "부서명,사용자명,IP,최종접속일\n";
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

        // ✅ 현재 날짜 및 시간 추가 (YYYYMMDD_HHMM 형식)
        const now = new Date();
        const formattedDate = now.getFullYear().toString() +
            String(now.getMonth() + 1).padStart(2, '0') +
            String(now.getDate()).padStart(2, '0') + "_" +
            String(now.getHours()).padStart(2, '0') +
            String(now.getMinutes()).padStart(2, '0');

        const filename = `agent_data_${formattedDate}.${type}`;

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