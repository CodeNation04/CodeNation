<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>Agent 정보 조회</title>
    <link rel="stylesheet" href="css/agent_info.css">
</head>

<body>
    <div class="container">
        <h2>Agent 정보 조회</h2>
        <form id="searchForm">
            <div>
                <label for="name">사용자명</label>
                <input type="text" id="name" name="name" placeholder="사용자명 입력">
            </div>
            <div>
                <label for="ip">IP</label>
                <input type="text" id="ip" name="ip" placeholder="IP 입력 (예: 192.168.0.1)">
            </div>
            <div id="deptField" style="display: none;">
                <label for="dept">부서명</label>
                <select id="dept" name="dept">
                    <!-- DB에서 부서목록 받아와야됨 -->
                    <!-- 샘플 부서명 데이터 -->
                    <option value="">-- 부서 선택 --</option>
                    <option value="의무기록과">의무기록과</option>
                    <option value="전산실">전산실</option>
                    <option value="원무과">원무과</option>
                    <option value="진료지원팀">진료지원팀</option>
                </select>
            </div>
            <button type="button" onclick="searchAgents()">검색</button>
        </form>

        <div id="result" style="margin-top: 20px;"></div>
    </div>

    <script>
    // 최고관리자 여부 (true: 최고관리자, false: 중간관리자)
    const isSuperAdmin = true; // 변경 가능

    // 최고관리자일 때만 부서 검색 표시
    if (isSuperAdmin) {
        document.getElementById("deptField").style.display = "block";
    }

    // 검색 함수 (예제)
    function searchAgents() {
        const name = document.getElementById("name").value.trim();
        const ip = document.getElementById("ip").value.trim();
        const dept = isSuperAdmin ? document.getElementById("dept").value.trim() : '';
        //DB에서 Agent 목록 받아와야함
        //샘플 agent 데이터
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

        const filtered = agents.filter(agent =>
            (!name || agent.name.includes(name)) &&
            (!ip || agent.ip.includes(ip)) &&
            (!dept || agent.dept === dept)
        );

        const resultDiv = document.getElementById("result");
        if (filtered.length > 0) {
            let table = `
                    <table>
                        <tr>
                            <th>부서명</th>
                            <th>사용자명</th>
                            <th>IP</th>
                            <th>최종접속일</th>
                        </tr>`;
            filtered.forEach(agent => {
                table += `
                        <tr>
                            <td>${agent.dept}</td>
                            <td>${agent.name}</td>
                            <td>${agent.ip}</td>
                            <td>${agent.last_login}</td>
                        </tr>`;
            });
            table += `</table>`;
            resultDiv.innerHTML = table;
        } else {
            resultDiv.innerHTML = "<p>검색 결과가 없습니다.</p>";
        }
    }
    </script>
</body>

</html>