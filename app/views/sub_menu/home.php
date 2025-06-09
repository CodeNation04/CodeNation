<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="public/css/home.css" />
    <style>
    /* 차트 사이즈 고정 */
    canvas#line-chart,
    canvas#bar-chart {
        height: 400px !important;
        width: 100% !important;
        display: block;
        margin: 0 auto;
    }
    </style>
</head>

<body>
    <div class="placeholder" id="item-wrap">
        <div>
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h1>로그인 횟수</h1>
                <div>
                    <label for="login-unit-selector">단위:</label>
                    <select id="login-unit-selector" name="login_date">
                        <option value="year" selected>년</option>
                        <option value="month">월</option>
                        <option value="week">주</option>
                    </select>
                </div>
                <div>
                    <label for="login-type-selector">구분:</label>
                    <select id="login-type-selector" name="login_type">
                        <option value="전체" selected>전체</option>
                        <option value="중간관리자">중간 관리자</option>
                        <option value="에이전트">에이전트</option>
                    </select>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="bar-chart"></canvas>
            </div>
        </div>

        <div>
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h1>외부반출승인관리</h1>
                <div>
                    <label for="unit-selector">단위:</label>
                    <select id="unit-selector" name="exter_date">
                        <option value="year" selected>년</option>
                        <option value="month">월</option>
                        <option value="week">주</option>
                    </select>
                </div>
                <div>
                    <label for="exter-selector">상태:</label>
                    <select id="exter-selector" name="exter_status">
                        <option value="요청" selected>요청</option>
                        <option value="승인">승인</option>
                        <option value="반려">반려</option>
                    </select>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="line-chart"></canvas>
            </div>
        </div>
    </div>

    <script>
    let lineChart = null;
    let barChart = null;

    function padZero(n) {
        return n < 10 ? '0' + n : n;
    }

    function getDateList(from, to, type) {
        const result = [];
        const start = new Date(from);
        const end = new Date(to);

        if (type === "year") {
            const startYear = start.getFullYear();
            const endYear = end.getFullYear();
            for (let y = startYear; y <= endYear; y++) {
                result.push(String(y));
            }
        } else if (type === "month") {
            start.setDate(1);
            end.setDate(1);
            while (start <= end) {
                result.push(`${start.getFullYear()}-${padZero(start.getMonth() + 1)}`);
                start.setMonth(start.getMonth() + 1);
            }
        } else {
            while (start <= end) {
                result.push(`${start.getFullYear()}-${padZero(start.getMonth() + 1)}-${padZero(start.getDate())}`);
                start.setDate(start.getDate() + 1);
            }
        }

        return result;
    }

    function drawLineChart(dataList, unit, status) {
        const dataMap = {};
        let minDate = null,
            maxDate = null;

        dataList.forEach(item => {
            let label = unit === "year" ?
                String(item.period).substring(0, 4) :
                String(item.period);
            const count = Number(item.count);
            dataMap[label] = count;

            const dateObj = new Date(
                label.length === 4 ? `${label}-01-01` :
                label.length === 7 ? `${label}-01` :
                label
            );
            if (!minDate || dateObj < minDate) minDate = dateObj;
            if (!maxDate || dateObj > maxDate) maxDate = dateObj;
        });

        const labels = getDateList(minDate, maxDate, unit);
        const data = labels.map(l => dataMap[l] || 0);

        // 20단위 올림
        const maxCountLine = data.length ? Math.max(...data) : 0;
        const suggestedMaxLine = Math.ceil(maxCountLine / 20) * 20;

        if (lineChart) lineChart.destroy();
        const ctx = document.getElementById("line-chart").getContext("2d");
        lineChart = new Chart(ctx, {
            type: "line",
            data: {
                labels,
                datasets: [{
                    label: status,
                    data,
                    borderColor: "#3e95cd",
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: `외부반출 ${status} 건수 (단위: ${unit})`
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            autoSkip: false
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: suggestedMaxLine // 20단위 올림된 값
                        }
                    }]
                }
            }
        });
    }

    function selectExter() {
        const exter_status = $("#exter-selector").val();
        const exter_date = $("#unit-selector").val();

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/?url=ExportController/exportCnt",
            data: {
                exter_status,
                exter_date
            },
            success: res => {
                if (!res || !res.length) {
                    if (lineChart) lineChart.destroy();
                    return;
                }
                drawLineChart(res, exter_date, exter_status);
            },
            error: err => console.error("데이터 불러오기 실패:", err)
        });
    }

    function drawBarChart(dataList, unit, userType) {
        const labels = [],
            data = [];
        dataList.sort((a, b) => String(a.period).localeCompare(String(b.period)));
        dataList.forEach(item => {
            labels.push(item.period);
            data.push(Number(item.count));
        });

        const maxCount = data.length ? Math.max(...data) : 0;
        const suggestedMax = Math.ceil(maxCount / 20) * 20;

        if (barChart) barChart.destroy();
        const ctx = document.getElementById("bar-chart").getContext("2d");
        barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: userType,
                    backgroundColor: '#3e95cd',
                    data
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: `로그인 횟수 (${userType}, 단위: ${unit})`
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: suggestedMax
                        }
                    }],
                    xAxes: [{
                        barThickness: 40,
                        maxBarThickness: 50,
                        ticks: {
                            autoSkip: false
                        }
                    }]
                }
            }
        });
    }

    function selectLogin() {
        const user_type = $("#login-type-selector").val();
        const login_date = $("#login-unit-selector").val();

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/?url=AgentUserController/loginCnt",
            data: {
                user_type,
                date: login_date
            },
            success: res => {
                if (!res || !res.length) {
                    if (barChart) barChart.destroy();
                    return;
                }
                drawBarChart(res, login_date, user_type);
            },
            error: err => console.error("데이터 불러오기 실패:", err)
        });
    }

    $(function() {
        selectExter();
        selectLogin();
        $("#unit-selector, #exter-selector").change(selectExter);
        $("#login-unit-selector, #login-type-selector").change(selectLogin);
    });
    </script>
</body>

</html>