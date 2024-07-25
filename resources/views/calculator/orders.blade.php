<div class="flex p-4 text-center font-bold">
    <button class="mx-2 text-lg" id="backDate">&#9668;</button>
    <p class="mx-4 text-lg" id="nowDate"></p>
    <button class="mx-4 text-lg" id="nextDate">&#9658;</button>
</div>

<div class="block border-t-2 py-5 overflow-x-scroll">
    @include('dashboard.components.orderTable')
</div>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", () => {
            now = document.getElementById('nowDate');
            now.innerText = new Date().toISOString().slice(0, 10);
            data = now.innerText

            $.ajax({
                url: '/api/get/orders/',
                method: 'get',
                dataType: 'json',
                data: data,
                success: function(data) {
                    console.log(data)
                },
                error: function(response) {
                    $("#message").html(response.responseJSON.error);
                }
            });
        });

        function next() {}

        function back() {}
    </script>
