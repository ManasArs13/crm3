<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        @vite(['resources/css/calculator.css','resources/js/calculator.js','resources/js/jquery-ui.min.js','resources/js/jquery.ui.touch-punch.js'])
    </x-slot>
<div class="main-1 w-11/12 mx-auto px-4 sm:px-6 lg:px-8">

    <div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
        <span id="message"></span>
        <div class="tabs">
            <a class="tab-link active" href="#content-1">Калькулятор заборов</a>
            <a class="tab-link" href="#content-2">Калькулятор блоков</a>
            <a class="tab-link" href="#content-3">Калькулятор бетона</a>
        </div>
    </div>

    <div class="content">
         <div class="CEB CEB-1 w-11/12 max-w-7xl mx-auto pb-10">
            <div class="tab-content" id="content-1">
                @include("calculator.block", array("left_menu"=>true, 'productsByGroup' => $productsByFence, 'form'=>"calcFence"))
            </div>

            <div class="tab-content" id="content-2">
                @include("calculator.block", array("left_menu"=>false, 'form'=>'calcBlock'))
            </div>

            <div class="tab-content" id="content-3">
                @include("calculator.block", array("left_menu"=>false, 'productsByGroup' => $productsByBeton,'form'=>'calcBeton', 'vehicleTypes'=>$vehicleTypesBeton))
            </div>
        </div>
        <div class="datetime-popup">
            <div class="CEB__row">
                <div class="dates">
                    @foreach ($dates as $date)
                        @php
                            $dateFormat=new DateTime($date->date)
                        @endphp
                        <div class="date">{{ $dateFormat->format("d.m.Y")}}</div>
                        <div class="times">
                            @foreach ($times as $time)
                                <span data-time="{{$date->date.' '.$time->time}}" class="time"> {{$time->time_slot}} </span>
                            @endforeach
                        </div>
                    @endforeach;
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
<script>
  let shippingPrices= {!! $shippingPrices !!};
</script>

