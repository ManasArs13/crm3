    <div class="CEB w-11/12 max-w-7xl mx-auto py-4 pb-10" id="CEB">
        <div class="CEB__wrapContent df">
            <div class="CEB">

                <form class="form">
                    @csrf
                    <div class="CEB__row">
                        <div class="CEB__text2">Результат</div>
                        <div class="CEB__wrapTable">
                        @if (count($productsByGroup)>0)
                            <table class="CEB__table">
                                <tr>
                                    <td>позиция</td>
                                    <td>кол-во</td>
                                    @if (isset($productsByGroup[array_key_first($productsByGroup)]["colors"]))
                                        <td>цвет</td>
                                    @endif
                                    <td>вес, кг</td>
                                    <td>цена, руб/ед</td>
                                    <td>сумма, руб</td>
                                </tr>
                                @foreach($productsByGroup as $group)
                                    <tr>
                                        <td>{{$group["name"]}}</td>
                                        <td>
                                            <input type="number"
                                            name="positions[{{$group["id"]}}][quantity]"
                                            data-color="{{ (isset($group["colors"]))?1:0}}"
                                            data-id="{{$group["id"]}}" value=0 min=0 class="change_js">
                                        </td>
                                        @if (isset($group["colors"]))
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
                                        @else
                                            <input type="hidden" name="positions[{{$group["id"]}}][product_id]"
                                            value="{{ $group["product"] }}"
                                            data-id="{{$group["id"]}}"
                                            data-price="{{$group["price"]}}"
                                            data-weight="{{$group["weight"]}}">
                                        @endif

                                        <td>
                                            <span id="weight_total_{{$group["id"]}}" class="weight">0</span>
                                        </td>
                                        <td>
                                            <span id="price_client_{{$group["id"]}}">
                                                @if (isset($group["colors"]))
                                                    {{$group["colors"][0]["price"]}}
                                                @else
                                                    {{$group["price"]}}
                                                @endif
                                            </span>
                                            <input type="hidden" name="positions[{{$group["id"]}}][price]" value="{{isset($group["colors"])?$group["colors"][0]["price"]:$group["price"]}}">
                                        </td>
                                        <td><span id="price_total_{{$group["id"]}}" class="price">0</span></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>итог:</td>
                                    <td></td>
                                    @if (isset($productsByGroup[array_key_first($productsByGroup)]["colors"]))
                                        <td></td>
                                    @endif
                                    <td><span id="weight_total">0</span></td>
                                    <td></td>
                                    <td><span id="price_total">0</span></td>
                                </tr>
                            </table>
                        @endif
                        </div>
                    </div>

                    @include("calculator.info")
                </form>
            </div>
        </div>
    </div>
