<div class="p-5 border-t-2">
    <div class="chart-container" style="height: 20vh;">
        <canvas id="chart"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener("DOMContentLoaded", async function () {
            const ctx = document.getElementById("chart");
            const periodSelect = document.getElementById("period");
            let myChart;

            async function loadData(period = "month") {
                const response = await fetch(`/api/shipments/get/month_category?period=${period}`);
                const data = await response.json();

                return {
                    labels: data.labels,
                    datasets: Object.values(data.datasets),
                };
            }

            async function initChart(period) {
                const { labels, datasets } = await loadData(period);

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
            }


            periodSelect.addEventListener("change", async () => {
                const selectedPeriod = periodSelect.value;
                await initChart(selectedPeriod);
            });

            await initChart("month");

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
