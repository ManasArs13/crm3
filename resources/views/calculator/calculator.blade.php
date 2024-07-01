<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <script src="https://api-maps.yandex.ru/2.1/?apikey=83a5c651-59e9-4762-9fa9-d222e4aa50ab&lang=ru_RU" type="text/javascript"></script>

        @vite(['resources/css/calculator.css','resources/js/calculator.js','resources/js/jquery-ui.min.js','resources/js/jquery.ui.touch-punch.js'])
    </x-slot>
<div class="main-1 w-11/12 mx-auto px-4 sm:px-6 lg:px-8">

    <div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
        <span id="message" class="error"></span>

        <div class="flex justify-between">
            <fieldset class="CEB__wrapParams1">
                <label class="labelCustomRadio labelCustomRadio_js1">
                    <input checked="" class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence" value="{{__("calculator.type_1")}}" data-content="calcFence" >
                    <span class="labelCustomRadio__psevdo_border"></span>
                    <p class="labelCustomRadio__text2">{{__("calculator.calc_fence")}}</p>
                </label>
                <label class="labelCustomRadio labelCustomRadio_js1">
                    <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence" value="{{__("calculator.type_2")}}" data-content="calcBlock">
                    <span class="labelCustomRadio__psevdo_border"></span>
                    <p class="labelCustomRadio__text2">{{__("calculator.calc_block")}}</p>
                </label>
                <label class="labelCustomRadio labelCustomRadio_js1">
                    <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence" value="{{__("calculator.type_3")}}" data-content="calcBeton">
                    <span class="labelCustomRadio__psevdo_border"></span>
                    <p class="labelCustomRadio__text2">{{__("calculator.calc_beton")}}</p>
                </label>

                <label class="labelCustomRadio labelCustomRadio_js1">
                    <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence" value="{{__("calculator.mixers")}}" data-content="calcMixers">
                    <span class="labelCustomRadio__psevdo_border"></span>
                    <p class="labelCustomRadio__text2">{{__("calculator.mixers")}}</p>
                </label>
                <label class="labelCustomRadio labelCustomRadio_js1">
                    <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence" value="{{__("calculator.pumps")}}" data-content="calcPumps">
                    <span class="labelCustomRadio__psevdo_border"></span>
                    <p class="labelCustomRadio__text2">{{__("calculator.pumps")}}</p>
                </label>
            </fieldset>

            <label class="labelCustomRadio labelCustomRadio_js1">
                <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence" value="{{__('title.debtors')}}" data-content="calcDebt">
                <span class="labelCustomRadio__psevdo_border"></span>
                <p class="labelCustomRadio__text2">{{__('title.debtors')}}</p>
            </label>
        </div>
    </div>

    <div class="content">

            <div class="tab-content active" id="calcFence">
                @include("calculator.block", array("menu"=>true, 'productsByGroup' => $productsByFence, 'form'=>"calcFence", "datesFinish"=>$datesBlockFinish))
            </div>

            <div class="tab-content" id="calcBlock">
                @include("calculator.block", array("menu"=>false, 'form'=>'calcBlock',"datesFinish"=>$datesBlockFinish))
            </div>

            <div class="tab-content" id="calcBeton">
                @include("calculator.block", array("menu"=>false, 'productsByGroup' => $productsByBeton,'form'=>'calcBeton', 'vehicleTypes'=>$vehicleTypesBeton, "datesFinish"=>$datesBetonFinish))
            </div>

            <div class="tab-content" id="calcMixers">
                <div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
                    <div class="CEB__row">

                    </div>
                </div>
            </div>

            <div class="tab-content" id="calcPumps">
                <div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
                    <div class="CEB__row">
                        <div class="img_delivery">
                            <img src="{{ Storage::url('pumps.jpg') }}"
                                 alt="{{ __('calculator.pumps') }}">
                        </div>
                    </div>
                </div>
            </div>


            <div class="tab-content" id="calcDebt">
                <div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
                    <div class="CEB__row">
                        @include("shipment.debtorstable", array("shipments"=>$shipments))
                    </div>
                </div>
            </div>
    </div>
</div>

</x-app-layout>


