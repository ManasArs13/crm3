<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        @vite(['resources/css/calculator.css','resources/js/calculator.js','resources/js/jquery-ui.min.js','resources/js/jquery.ui.touch-punch.js'])
    </x-slot>

    <div class="CEB w-11/12 max-w-7xl mx-auto py-4 pb-10" id="CEB">
        <span id="message"></span>
        <div class="tabs">
            <a class="tab-link active" href="#content-1">Калькулятор заборов</a>
            <a class="tab-link" href="#content-2">Калькулятор блоков</a>
            <a class="tab-link" href="#content-3">Калькулятор бетона</a>
        </div>

        <div class="tab-content" id="content-1">
            @include("calculator.block", array("left_menu"=>true, 'productsByGroup' => $productsByFence, 'form'=>"calcFence"))
        </div>

        <div class="tab-content" id="content-2">
            @include("calculator.block", array("left_menu"=>false, 'form'=>'calcBlock'))
        </div>

        <div class="tab-content" id="content-3">
            @include("calculator.block", array("left_menu"=>false, 'productsByGroup' => $productsByBeton,'form'=>'calcBeton'))
        </div>
    </div>
</x-app-layout>
<script>
  let shippingPrices= {!! $shippingPrices !!};
</script>

