<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <link rel="stylesheet" href="public/css/home.css" />
<div class="placeholder" id="item-wrap">
    <div>
        <h1>바차트</h1>
        <canvas id="bar-chart" style="height:400px; width:100%"></canvas>
    </div>

    <div>
        <h1>라인차트</h1>
        <canvas id="line-chart" style="height:400px; width:100%"></canvas>
    </div>

    <div>
        <h1>파이 차트</h1>
        <canvas id="pie-chart" style="height:400px; width:100%"></canvas>
    </div>

    <div>
        <h1>레이더 차트</h1>
        <canvas id="radar-chart" style="height:400px; width:100%"></canvas>
    </div>
    
    <div>
        <h1>도넛 차트</h1>
        <canvas id="doughnut-chart" style="height:400px; width:100%"></canvas>
    </div>

    <div>
        <h1>수평 바 차트</h1>
        <canvas id="bar-chart-horizontal" style="height:400px; width:100%"></canvas>
    </div>
</div>

<script>
    new Chart(document.getElementById("bar-chart"), {
        type: 'bar',
        data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [
            {
            label: "Population (millions)",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [2478,5267,734,784,433]
            }
        ]
        },
        options: {
        legend: { display: false },
        title: {
            display: true,
            text: 'Predicted world population (millions) in 2050'
        }
        }
    });

    new Chart(document.getElementById("line-chart"), {
        type: 'line',
        data: {
            labels: [1500,1600,1700,1750,1800,1850,1900,1950,1999,2050],
            datasets: [{ 
                data: [86,114,106,106,107,111,133,221,783,2478],
                label: "Africa",
                borderColor: "#3e95cd",
                fill: false
            }, { 
                data: [282,350,411,502,635,809,947,1402,3700,5267],
                label: "Asia",
                borderColor: "#8e5ea2",
                fill: false
            }, { 
                data: [168,170,178,190,203,276,408,547,675,734],
                label: "Europe",
                borderColor: "#3cba9f",
                fill: false
            }, { 
                data: [40,20,10,16,24,38,74,167,508,784],
                label: "Latin America",
                borderColor: "#e8c3b9",
                fill: false
            }, { 
                data: [6,3,2,2,7,26,82,172,312,433],
                label: "North America",
                borderColor: "#c45850",
                fill: false
            }
            ]
        },
        options: {
            title: {
            display: true,
            text: 'World population per region (in millions)'
            }
        }
    });

    new Chart(document.getElementById("pie-chart"), {
        type: 'pie',
        data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [{
            label: "Population (millions)",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [2478,5267,734,784,433]
        }]
        },
        options: {
        title: {
            display: true,
            text: 'Predicted world population (millions) in 2050'
        }
        }
    });

    new Chart(document.getElementById("radar-chart"), {
        type: 'radar',
        data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [
            {
            label: "1950",
            fill: true,
            backgroundColor: "rgba(179,181,198,0.2)",
            borderColor: "rgba(179,181,198,1)",
            pointBorderColor: "#fff",
            pointBackgroundColor: "rgba(179,181,198,1)",
            data: [8.77,55.61,21.69,6.62,6.82]
            }, {
            label: "2050",
            fill: true,
            backgroundColor: "rgba(255,99,132,0.2)",
            borderColor: "rgba(255,99,132,1)",
            pointBorderColor: "#fff",
            pointBackgroundColor: "rgba(255,99,132,1)",
            pointBorderColor: "#fff",
            data: [25.48,54.16,7.61,8.06,4.45]
            }
        ]
        },
        options: {
        title: {
            display: true,
            text: 'Distribution in % of world population'
        }
        }
    });

    new Chart(document.getElementById("doughnut-chart"), {
        type: 'doughnut',
        data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [
            {
            label: "Population (millions)",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [2478,5267,734,784,433]
            }
        ]
        },
        options: {
        title: {
            display: true,
            text: 'Predicted world population (millions) in 2050'
        }
        }
    });

    new Chart(document.getElementById("bar-chart-horizontal"), {
        type: 'horizontalBar',
        data: {
        labels: ["Africa", "Asia", "Europe", "Latin America", "North America"],
        datasets: [
            {
            label: "Population (millions)",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [2478,5267,734,784,433]
            }
        ]
        },
        options: {
        legend: { display: false },
        title: {
            display: true,
            text: 'Predicted world population (millions) in 2050'
        }
        }
    });
</script>