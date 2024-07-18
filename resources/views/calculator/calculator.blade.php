<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
            integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://api-maps.yandex.ru/2.1/?apikey=83a5c651-59e9-4762-9fa9-d222e4aa50ab&lang=ru_RU"
            type="text/javascript"></script>
        @vite(['resources/js/jquery-ui.min.js', 'resources/js/jquery.ui.touch-punch.js', 'resources/css/calculator.css', 'resources/js/calculator.js'])
    </x-slot>
    <div class="main-1 w-11/12 mx-auto px-4 sm:px-6 lg:px-8">

        <div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
            <span id="message" class="error"></span>

            <div class="flex justify-between">
                <fieldset class="CEB__wrapParams1">
                    <label class="labelCustomRadio labelCustomRadio_js1">
                        <input checked="" class="labelCustomRadio__input CMR__input_calc_js" type="radio"
                            name="typeFence" value="{{ __('calculator.type_1') }}" data-content="calcFence">
                        <span class="labelCustomRadio__psevdo_border"></span>
                        <p class="labelCustomRadio__text2">{{ __('calculator.calc_fence') }}</p>
                    </label>
                    <label class="labelCustomRadio labelCustomRadio_js1">
                        <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence"
                            value="{{ __('calculator.type_2') }}" data-content="calcBlock">
                        <span class="labelCustomRadio__psevdo_border"></span>
                        <p class="labelCustomRadio__text2">{{ __('calculator.calc_block') }}</p>
                    </label>
                    <label class="labelCustomRadio labelCustomRadio_js1">
                        <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence"
                            value="{{ __('calculator.type_3') }}" data-content="calcBeton">
                        <span class="labelCustomRadio__psevdo_border"></span>
                        <p class="labelCustomRadio__text2">{{ __('calculator.calc_beton') }}</p>
                    </label>

                    <label class="labelCustomRadio labelCustomRadio_js1">
                        <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence"
                            value="{{ __('calculator.mixers') }}" data-content="calcMixers">
                        <span class="labelCustomRadio__psevdo_border"></span>
                        <p class="labelCustomRadio__text2">{{ __('calculator.mixers') }}</p>
                    </label>
                    <label class="labelCustomRadio labelCustomRadio_js1">
                        <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence"
                            value="{{ __('calculator.pumps') }}" data-content="calcPumps">
                        <span class="labelCustomRadio__psevdo_border"></span>
                        <p class="labelCustomRadio__text2">{{ __('calculator.pumps') }}</p>
                    </label>
                </fieldset>

                @php
                    $Totalsum = 0;
                    if (count($shipments) > 0) {
                        foreach ($shipments as $shipment) {
                            $Totalsum += $shipment->balance;
                        }
                    }
                @endphp

                <label class="labelCustomRadio labelCustomRadio_js1"
                    @if ($Totalsum < -3000000) style="background-color: #ff836e;" @endif>
                    <input class="labelCustomRadio__input CMR__input_calc_js" type="radio" name="typeFence"
                        value="{{ __('title.debtors') }}" data-content="calcDebt">
                    <span class="labelCustomRadio__psevdo_border"></span>
                    <p class="labelCustomRadio__text2">{{ __('title.debtors') }}</p>
                </label>
            </div>
        </div>

        <div class="content">

            <div class="tab-content active" id="calcFence">
                @include('calculator.block', [
                    'menu' => true,
                    'productsByGroup' => $productsByFence,
                    'form' => 'calcFence',
                    'datesFinish' => $datesBlockFinish,
                ])
            </div>

            <div class="tab-content" id="calcBlock">
                @include('calculator.block', [
                    'menu' => false,
                    'form' => 'calcBlock',
                    'datesFinish' => $datesBlockFinish,
                ])
            </div>

            <div class="tab-content" id="calcBeton">
                @include('calculator.block', [
                    'menu' => false,
                    'productsByGroup' => $productsByBeton,
                    'form' => 'calcBeton',
                    'vehicleTypes' => $vehicleTypesBeton,
                    'datesFinish' => $datesBetonFinish,
                ])
            </div>

            <div class="tab-content" id="calcMixers">
                <div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
                    <div class="CEB__row h-100">
                        <img src="{{ Storage::url('transports.jpg') }}" alt="{{ __('column.transport') }}">
                    </div>
                </div>
            </div>

            <div class="tab-content" id="calcPumps">
                <div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
                    <div class="CEB__row">
                        <div class="img_delivery">
                            <img src="{{ Storage::url('pumps.jpg') }}" alt="{{ __('calculator.pumps') }}">
                        </div>
                    </div>
                </div>
            </div>


            <div class="tab-content" id="calcDebt">
                <div class="CEB w-11/12 max-w-7xl mx-auto pb-10">
                    <div class="CEB__row">
                        @include('shipment.debtorstable', ['shipments' => $shipments])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function exportHTMLtoPDF() {

            var opt = {
                margin: 0,
                filename: 'calculator.pdf',
                html2canvas: {
                    scale: 2,
                    logging: false,
                    width: 1200,
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'landscape'
                }
            };

            if (document.getElementById('calcBeton').classList.contains("active")) {
                let htmlElement = document.getElementById('calcBeton_to_pdf');
                html2pdf().set(opt).from(htmlElement).save();
            } else if (document.getElementById('calcBlock').classList.contains("active")) {
                let htmlElement = document.getElementById('calcBlock_to_pdf');
                html2pdf().set(opt).from(htmlElement).save();
            } else {
                let htmlElement = document.getElementById('calcFence_to_pdf');
                html2pdf().set(opt).from(htmlElement).save();
            }
        }

        function copyText() {

            let COPY_TEXT = "Example text to copy";

            if (document.getElementById('calcBeton').classList.contains("active")) {

            } else if (document.getElementById('calcBlock').classList.contains("active")) {

            } else {
                // Конструкция стены
                let CEB__textReserve = document.getElementById('CEB__textReserve').value;
                let ZAPAS = `Запас ${CEB__textReserve}%;`;

                let CEB__textLength = document.getElementById('CEB__textLength').value;
                let ZABOR_LENGHT = `Длина забора ${CEB__textLength}м. `;

                let CEB__textPost_quantity = document.getElementById('CEB__textPost_quantity').value;
                let COUNT_POST = `Количество столбов ${CEB__textPost_quantity}шт.`;

                let CEB__text_wallHeight = document.getElementById('CEB__text_wallHeight').value;
                let WALL_HEIGHT = `Высота стенки ${CEB__text_wallHeight}см. `;

                let CEB__text_columnHeight = document.getElementById('CEB__text_columnHeight').value;
                let COLUMN_HEIGHT = `Высота колонны ${CEB__text_columnHeight}см.`;

                // 0 Декор для забора
                let positions_6_quantity = document.getElementsByName('positions[6][quantity]');
                let weight_total_6 = document.getElementById('weight_total_6');
                let select__head = document.getElementsByClassName('select__head');
                let positions_6_price = document.getElementById('price_client_6');
                let price_total_6 = document.getElementById('price_total_6');
                let PRODUCT_6 =
                    `0 Декор для забора: кол-во ${positions_6_quantity[0].value}; цвет ${select__head[0].innerText}; Вес ${weight_total_6.innerText}кг.; Цена ${positions_6_price.innerText}; Сумма ${price_total_6.innerText};`;

                // 0 Заборный блок
                let positions_12_quantity = document.getElementsByName('positions[12][quantity]');
                let weight_total_12 = document.getElementById('weight_total_12');
                let positions_12_price = document.getElementById('price_client_12');
                let price_total_12 = document.getElementById('price_total_12');
                let PRODUCT_12 =
                    `0 Заборный блок: кол-во ${positions_12_quantity[0].value}; цвет ${select__head[1].innerText}; Вес ${weight_total_12.innerText}кг.; Цена ${positions_12_price.innerText}; Сумма ${price_total_12.innerText};`;

                // 0  Колонны на забор
                let positions_21_quantity = document.getElementsByName('positions[21][quantity]');
                let weight_total_21 = document.getElementById('weight_total_21');
                let positions_21_price = document.getElementById('price_client_21');
                let price_total_21 = document.getElementById('price_total_21');
                let PRODUCT_21 =
                    `0 Колонны на забор: кол-во ${positions_21_quantity[0].value}; цвет ${select__head[2].innerText}; Вес ${weight_total_21.innerText}кг.; Цена ${positions_21_price.innerText}; Сумма ${price_total_21.innerText};`;

                // 0  Крышки колонны
                let positions_15_quantity = document.getElementsByName('positions[15][quantity]');
                let weight_total_15 = document.getElementById('weight_total_15');
                let positions_15_price = document.getElementById('price_client_15');
                let price_total_15 = document.getElementById('price_total_15');
                let PRODUCT_15 =
                    `0 Крышки колонны: кол-во ${positions_15_quantity[0].value}; цвет ${select__head[3].innerText}; Вес ${weight_total_15.innerText}кг.; Цена ${positions_15_price.innerText}; Сумма ${price_total_15.innerText};`;

                // 0  Парапеты
                let positions_11_quantity = document.getElementsByName('positions[11][quantity]');
                let weight_total_11 = document.getElementById('weight_total_11');
                let positions_11_price = document.getElementById('price_client_11');
                let price_total_11 = document.getElementById('price_total_11');
                let PRODUCT_11 =
                    `0 Парапеты: кол-во ${positions_11_quantity[0].value}; цвет ${select__head[4].innerText}; Вес ${weight_total_11.innerText}кг.; Цена ${positions_11_price.innerText}; Сумма ${price_total_11.innerText};`;

                // 0  Поддон 120х80 (euro)
                let positions_pallet_quantity = document.getElementsByName('positions[pallet][quantity]');
                let weight_total_pallet = document.getElementById('weight_total_pallet');
                let PRODUCT_pallet =
                    `0  Поддон 120х80 (euro): кол-во ${positions_pallet_quantity[0].value}; Вес ${weight_total_pallet.innerText}кг.;`;

                let weight_total = document.getElementById('weight_total');
                let price_total = document.getElementById('price_total');

                let delivery = document.getElementsByClassName('selection')[0].innerText;
                let transport_type = document.getElementsByClassName('selection')[1].innerText;

                let total = document.getElementsByClassName('total')[0].innerText;
  
                COPY_TEXT = ZAPAS + `\n` + ZABOR_LENGHT + COUNT_POST + `\n` + WALL_HEIGHT + COLUMN_HEIGHT + `\n\n` +
                    PRODUCT_6 + `\n` + PRODUCT_12 + `\n` + PRODUCT_21 + `\n` + PRODUCT_15 + `\n` + PRODUCT_11 + '\n' +
                    PRODUCT_pallet + '\n\n' + `Итог: ${weight_total.innerText} тонн; ${price_total.innerText} р.` + '\n\n' +
                    `\n` + delivery + `; ${transport_type}` + `\n` +
                    `ИТОГО С ДОСТАВКОЙ ${total}`;

            }

            if (navigator.clipboard) {
                navigator.clipboard.writeText(COPY_TEXT).then(function() {
                    console.log('Async: Copying to clipboard was successful!');
                }, function(err) {
                    console.error('Oops, unable to copy');
                });
            } else {
                try {

                    let inputForeCopy = document.createElement('textarea');
                    inputForeCopy.innerHTML = COPY_TEXT;
                    document.body.appendChild(inputForeCopy);
                    inputForeCopy.select();
                    let result = document.execCommand('copy');
                    document.body.removeChild(inputForeCopy);

                } catch (err) {
                    console.log('Oops, unable to copy');
                }
            }
        }
    </script>

</x-app-layout>
