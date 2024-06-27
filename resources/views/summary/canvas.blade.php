<div class="border-t-2 p-5">
    <div class="chart-container" style="height:20vh;">
        <canvas id="chart"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener("DOMContentLoaded", async function() {

            let charts = [];
            let labels = [];

            await fetch('/api/shipments/get/month_category')
            .then((response) => response.json())
            .then((data) => {
                    charts=data.charts;
                    labels=data.labels;
                    console.log(charts);
            })
            .catch((error) => {
                    console.error("Error fetching data:", error);
            });

            let ctx = document.getElementById("chart");
            let myChart;

            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                            label: "Сумма выручки по блоку",
                            hidden: true,
                            data: charts["продукция"],
                            backgroundColor: "rgb(145,202,246)",
                            borderColor: "rgb(145,202,246)",
                            borderWidth: 4,
                        },
                        {
                            label: "Сумма выручки по бетону",
                            data: charts["бетон"],
                            hidden: true,
                            backgroundColor: "rgb(236,112,112)",
                            borderColor: "rgb(236,112,112)",
                            borderWidth: 4,
                        },
                        {
                            label: "Сумма выручки по доставке",
                            data: charts["доставка"],
                            backgroundColor: "rgb(255, 205, 86)",
                            borderColor: "rgb(255, 205, 86)",
                            borderWidth: 4,
                        }
                    ],
                },
                options: {
                    scale: {
                        ticks: {
                            beginAtZero: true,
                            max: 4
                        }
                    },
                    interaction: {
                        mode: 'index'
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    width: 400,
                },
            });
        });
    </script>
</div>
