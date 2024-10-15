<div class="border-t-2 p-5">
    <div class="chart-container" style="height:20vh;">
        <canvas id="orderChart"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener("DOMContentLoaded", async function() {

            let residual_count = [];
            let positions_count = [];
            let shipped_count = [];
            let labels = [];

            let QuantityProduct = document.getElementById('QuantityProduct');
            let QuantityShipment = document.getElementById('QuantityShipment');
            let QuantityResidual = document.getElementById('QuantityResidual');

            function count(arr) {
                let sum = 0;
                arr.forEach(function(item) {
                    sum += item
                })
                return sum;
            }

            await fetch('/get-orders/{!! $date !!}')
                .then((response) => response.json())
                .then((data) => {
                    for (let item in data.residual_count) {
                        residual_count.push(data.residual_count[item]);
                    }

                    for (let item in data.positions_count) {
                        positions_count.push(data.positions_count[item])
                    }

                    for (let item in data.labels) {
                        labels.push(data.labels[item])
                    }

                    for (let item in data.shipped_count) {
                        shipped_count.push(data.shipped_count[item])
                    }

                    QuantityProduct.innerText = count(positions_count);
                    QuantityShipment.innerText = count(shipped_count);
                    QuantityResidual.innerText = count(residual_count);

                })
                .catch((error) => {
                    console.error("Error fetching data:", error);
                });

            let ctx = document.getElementById("orderChart");
            let myChart;

            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                            label: "Кол-во продуктов" + ' (' + count(positions_count) + ' шт.)',
                            data: positions_count,
                            backgroundColor: "rgb(255, 205, 86)",
                            borderColor: "rgb(255, 205, 86)",
                            borderWidth: 4,
                        },
                        {
                            label: "Отгружено" + ' (' + count(shipped_count) + ' шт.)',
                            data: shipped_count,
                            backgroundColor: "rgb(134 239 172)",
                            borderColor: "rgb(134 239 172)",
                            borderWidth: 4,
                        },
                        {
                            label: "Осталось" + ' (' + count(residual_count) + ' шт.)',
                            data: shipped_count,
                            backgroundColor: "rgb(236,112,112)",
                            borderColor: "rgb(236,112,112)",
                            borderWidth: 4,
                            hidden: true,
                        }
                    ],
                },
                options: {
                    scales: {
                        y: {
                            suggestedMin: 0,
                         }
                    },
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
        });
    </script>
</div>
