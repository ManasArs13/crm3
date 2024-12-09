<div class="p-5 border-t-2">
    <div class="chart-container" style="height:20vh;">
        <canvas id="chart"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener("DOMContentLoaded", async function() {

            let charts = [];
            let labels = [];
            let datasets = [];

            await fetch('/api/shipments/get/month_category')
            .then((response) => response.json())
            .then((data) => {

                    labels=data.labels;

                    for (let item in data.datasets) {
                        datasets.push(data.datasets[item])
                    }
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
                    datasets: datasets,
                },
                options: {
                    scale: {
                        ticks: {
                            beginAtZero: true,
                            max: 4
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
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

            const toggleButtons = document.querySelectorAll(".toggle-dataset");

            toggleButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const datasetLabel = this.getAttribute("data-dataset");

                    const dataset = myChart.data.datasets.find(ds => ds.label === datasetLabel);

                    if (dataset) {
                        dataset.hidden = !dataset.hidden;

                        myChart.update();

                        this.classList.toggle("bg-blue-600");
                        this.classList.toggle("bg-blue-300");

                    }
                });
            });
        });
    </script>
</div>
