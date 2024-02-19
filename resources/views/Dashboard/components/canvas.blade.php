<div class="border-t-2 p-5">
    <div class="chart-container" style="height:40vh;">
        <canvas  id="orderChart" ></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function getDay() {
            let currentDate = new Date();
            let futureDate = new Date();
            futureDate.setDate(currentDate.getDate() + 10);
            let days = [];

            while (currentDate <= futureDate) {
                days.push(currentDate.getDate());
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return days;
        }

        window.addEventListener("DOMContentLoaded",async function () {
            let count = [] ;
            let count2 =[];
            await fetch('/month-orders')
                .then((response) => response.json())
                .then((data) => {
                    for (let item in data.entityItems) {
                        count.push(data.entityItems[item]);
                    }
                    for (let item in data.orders) {
                        count2.push(data.orders[item])
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
                    labels: getDay(),
                    datasets: [
                        {
                            label: "Сумма заказов",
                            data: count,
                            backgroundColor: "rgb(145,202,246)",
                            borderColor: "rgb(36,135,217)",
                            borderWidth:4,
                        },
                        {
                            label: "Кол-во заказов",
                            data: count2,
                            backgroundColor: "rgb(236,112,112)",
                            borderColor: "rgb(192,37,37)",
                            borderWidth:4,
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
