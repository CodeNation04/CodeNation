<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<link rel="stylesheet" href="public/css/home.css" />

<div class="placeholder" id="item-wrap">
    <!-- 바 차트 -->
    <div>
        <h1>바차트</h1>
        <canvas id="bar-chart" style="height:400px; width:100%"></canvas>
    </div>

    <!-- 라인 차트 -->
    <div>
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h1>라인차트</h1>
            <div>
                <label for="unit-selector">단위:</label>
                <select id="unit-selector">
                    <option value="year">년</option>
                    <option value="month">월</option>
                    <option value="week">주</option>
                </select>
            </div>
        </div>
        <canvas id="line-chart" style="height:400px; width:100%"></canvas>
    </div>
</div>

<script>
// 바차트
new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [{
            label: "Population (millions)",
            backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
            data: [2478, 5267, 734, 784, 433]
        }]
    },
    options: {
        legend: {
            display: false
        },
        title: {
            display: true,
            text: 'Predicted world population (millions) in 2050'
        }
    }
});

// 라인차트 데이터셋
const chartData = {
    year: {
        labels: [1500, 1600, 1700, 1750, 1800, 1850, 1900, 1950, 1999, 2050],
        datasets: [{
                data: [86, 114, 106, 106, 107, 111, 133, 221, 783, 2478],
                label: "Africa",
                borderColor: "#3e95cd",
                fill: false
            },
            {
                data: [282, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267],
                label: "Asia",
                borderColor: "#8e5ea2",
                fill: false
            },
            {
                data: [168, 170, 178, 190, 203, 276, 408, 547, 675, 734],
                label: "Europe",
                borderColor: "#3cba9f",
                fill: false
            },
            {
                data: [40, 20, 10, 16, 24, 38, 74, 167, 508, 784],
                label: "Latin America",
                borderColor: "#e8c3b9",
                fill: false
            },
            {
                data: [6, 3, 2, 2, 7, 26, 82, 172, 312, 433],
                label: "North America",
                borderColor: "#c45850",
                fill: false
            }
        ]
    },
    month: {
        labels: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
        datasets: [{
                data: [10, 20, 30, 25, 35, 40, 50, 45, 55, 60, 65, 70],
                label: "Africa",
                borderColor: "#3e95cd",
                fill: false
            },
            {
                data: [20, 25, 35, 30, 40, 45, 55, 60, 65, 70, 75, 80],
                label: "Asia",
                borderColor: "#8e5ea2",
                fill: false
            },
            {
                data: [5, 10, 15, 10, 20, 25, 30, 35, 30, 25, 20, 15],
                label: "Europe",
                borderColor: "#3cba9f",
                fill: false
            },
            {
                data: [8, 12, 18, 15, 22, 28, 35, 32, 38, 42, 45, 50],
                label: "Latin America",
                borderColor: "#e8c3b9",
                fill: false
            },
            {
                data: [2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24],
                label: "North America",
                borderColor: "#c45850",
                fill: false
            }
        ]
    },
    week: {
        labels: ["1주차", "2주차", "3주차", "4주차"],
        datasets: [{
                data: [5, 10, 15, 20],
                label: "Africa",
                borderColor: "#3e95cd",
                fill: false
            },
            {
                data: [8, 12, 18, 22],
                label: "Asia",
                borderColor: "#8e5ea2",
                fill: false
            },
            {
                data: [4, 6, 8, 10],
                label: "Europe",
                borderColor: "#3cba9f",
                fill: false
            },
            {
                data: [3, 5, 7, 9],
                label: "Latin America",
                borderColor: "#e8c3b9",
                fill: false
            },
            {
                data: [2, 4, 6, 8],
                label: "North America",
                borderColor: "#c45850",
                fill: false
            }
        ]
    }
};

// 초기 라인차트 생성 (년 단위)
const ctx = document.getElementById("line-chart").getContext("2d");
const lineChart = new Chart(ctx, {
    type: "line",
    data: {
        labels: chartData.year.labels,
        datasets: chartData.year.datasets
    },
    options: {
        title: {
            display: true,
            text: 'World population per region (in millions)'
        }
    }
});

// 단위 선택 시 라인차트 업데이트
document.getElementById("unit-selector").addEventListener("change", function() {
    const selected = this.value;
    const data = chartData[selected];

    lineChart.data.labels = data.labels;
    lineChart.data.datasets = data.datasets;
    lineChart.update();
});
</script>