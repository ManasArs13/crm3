<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        @vite(['resources/css/calculator.css' , 'resources/js/jquery-ui.min.js' , 'resources/js/jquery.ui.touch-punch.js', 'resources/js/calculator.js'])
    </x-slot>

    <div class="CEB w-11/12 max-w-7xl mx-auto py-4 pb-10" id="CEB">
        <div class="CEB__wrapContent df">
            <div class="CEB">
                <span id="message"></span>
                <form id="form">
                    @csrf
                    <div class="CEB__row">
                        <div class="CEB__text2">Результат</div>
                        <div class="CEB__wrapTable">
                        @if (count($productsByGroup)>0)
                            <table class="CEB__table">
                                <tr>
                                    <td>позиция</td>
                                    <td>кол-во</td>
                                    <td>цвет</td>
                                    <td>вес, кг</td>
                                    <td>цена, руб/ед</td>
                                    <td>сумма, руб</td>
                                </tr>
                                @foreach($productsByGroup as $group)
                                    <tr>
                                        <td>{{$group["name"]}}</td>
                                        <td>
                                            <input type="number" name="positions[{{$group["id"]}}][quantity]" data-id="{{$group["id"]}}" value=0 min=0 class="CMR__change_js">

                                        </td>
                                        <td>
                                            <select name="positions[{{ $group["id"]}}][product_id]" data-id="{{$group["id"]}}" class="CEB__select_color_js CEB__select_color">
                                                @foreach($group["colors"] as $color)
                                                    <option data-price="{{$color["price"]}}"
                                                            data-weight="{{$color["weight"]}}"
                                                            value="{{$color["product"]}}"
                                                            data-codeColor="#{{$color["hex"]}}"
                                                            data-codecolortext="#{{$color["font_color"]}}"
                                                            style="background-color:#{{$color["hex"]}};">
                                                            {{ $color["name"] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <span id="weight_total_{{$group["id"]}}" class="weight">0</span>
                                        </td>
                                        <td>
                                            <span id="price_client_{{$group["id"]}}">{{$group["colors"][0]["price"]}}</span>
                                            <input type="hidden" name="positions[{{$group["id"]}}][price]" value="{{$group["colors"][0]["price"]}}">
                                        </td>
                                        <td><span id="price_total_{{$group["id"]}}" class="price">0</span></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>итог:</td>
                                    <td></td>
                                    <td></td>
                                    <td><span id="weight_total">0</span></td>
                                    <td></td>
                                    <td><span id="price_total">0</span></td>
                                </tr>
                            </table>
                        @endif
                        </div>
                    </div>

                    <div class="CEB__row">
                        <div class="CEB__text2">Доставка</div>
                        <div class="CEB__wrapSlider">
                            <select name="attributes[delivery][id]" class="change_delivery" style="border: 1px solid gray;padding: 4px;" id="delivery">
                                @foreach ($deliveries as $delivery)
                                    <option data-distance="{{ $delivery->distance }}"  value="{{ $delivery->ms_id }}" style="margin: 4px;">{{ $delivery->name }}</option>
                                @endforeach
                            </select>
                            <select name="attributes[vehicle_type][id]" class="change_delivery" style="border: 1px solid gray;padding: 4px;width:40%;" id="vehicleType">
                                @foreach ($vehicleTypes as $type)
                                    <option value="{{ $type->ms_id }}" style="margin: 4px;">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="CEB__wrapSlider" id="resultAll" style="font-weight: 700;text-align: center;"></div>
                    </div>

                    <div class="CEB__row">
                        <div class="CEB__text2">Пользователь</div>
                        <div class="CEB__wrapSlider">
                            <input type="text" name="agent[name]" style="border: 1px solid gray;padding: 4px;">
                            <input type="text" name="agent[phone]" class="phone" style="border: 1px solid gray;padding: 4px;">
                            <input type="date" name="deliveryPlannedMoment" class="plan" style="border: 1px solid gray;padding: 4px;">
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center rounded bg-green-400 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">Отправить в мс</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        let shippingPrices = {!! $shippingPrices !!}
    </script>

</x-app-layout>
