<div class="border-t-2 p-5">
    <div class="chart-container" style="height:20vh;">
        <canvas  id="orderChart"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener("DOMContentLoaded",async function () {
            
            let sum  = [];
            let orders_count = [];
            let positions_count = [];
            let shipped_count = [];
            let labels = [];

            await fetch('/get-orders/{!! $date !!}')
                .then((response) => response.json())
                .then((data) => {
                    for (let item in data.sum) {
                        sum.push(data.sum[item]);
                    }

                    for (let item in data.orders_count) {
                        orders_count.push(data.orders_count[item])
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
                    datasets: [
                        {
                            label: "Сумма заказов",
                            hidden: true,
                            data: sum,
                            backgroundColor: "rgb(145,202,246)",
                            borderColor: "rgb(145,202,246)",
                            borderWidth: 4,
                        },
                        {
                            label: "Кол-во заказов",
                            data: orders_count,
                            hidden: true,
                            backgroundColor: "rgb(236,112,112)",
                            borderColor:  "rgb(236,112,112)",
                            borderWidth: 4,
                        },
                        {
                            label: "Кол-во продуктов",
                            data: positions_count,
                            backgroundColor: "rgb(255, 205, 86)",
                            borderColor:  "rgb(255, 205, 86)",
                            borderWidth: 4,
                        },
                        {
                            label: "Кол-во отгружено",
                            data: shipped_count,
                            backgroundColor: "rgb(134 239 172)",
                            borderColor:  "rgb(134 239 172)",
                            borderWidth: 4,
                        }
                    ],
                },
                options: {
                    scale: {
                        ticks:{
                            beginAtZero: true,
                            max: 4
                        }
                    },
                    interaction: {
                        mode: 'index'
                    } ,
                    responsive: true,
                    maintainAspectRatio: false,
                    width: 400,
                },
            });
        });
    </script>
</div>
