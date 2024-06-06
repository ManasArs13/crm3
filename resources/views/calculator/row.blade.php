<tr>
    <td>
        @if ($form!="calcBeton")
            {{ $group["name"] }}
        @else
            <div class="select">
                <div class="select__head"></div>
                <ul class="select__list CEB__select_beton_js CEB__select_beton select_product"  data-id="{{$group["id"]}}">
                    @php
                        $value="";
                    @endphp
                    @foreach($productsByGroup as $productByGroup)
                        <li class="select__item {{($loop->first)?"selected":""}}"
                                data-price="{{$productByGroup["price"]}}"
                                data-weight="{{$productByGroup["weight"]}}"
                                data-id="{{$productByGroup["id"]}}"
                                data-value="{{$productByGroup["product"]}}"
                        >
                            {{ $productByGroup["name"] }}
                        </li>
                        @if ($loop->first)
                           @php $value=$productByGroup["product"]; @endphp
                        @endif
                    @endforeach
                </ul>
                <input class="select__input" type="hidden" value="{{$value}}" name="positions[{{ $group["id"]}}][product_id]">
            </div>
        @endif
    </td>
    <td>
        <div class="quantity {{($left_menu)?"cursor":""}}">
            <input type="number"
            name="positions[{{$group["id"]}}][quantity]"
            data-color="{{(isset($group["colors"]))?1:0}}"
            data-id="{{$group["id"]}}"
            {{
                ($left_menu)?'readonly="readonly"':''
            }}
            value=0
            min=0
            class="change_js">
            <div class="quantity-nav">
                <div class="quantity-button quantity-up">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up" viewBox="0 0 16 16">
                        <path d="M3.204 11h9.592L8 5.519 3.204 11zm-.753-.659 4.796-5.48a1 1 0 0 1 1.506 0l4.796 5.48c.566.647.106 1.659-.753 1.659H3.204a1 1 0 0 1-.753-1.659z"/>
                    </svg>
                </div>
                <div class="quantity-button quantity-down">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down" viewBox="0 0 16 16">
                        <path d="M3.204 5h9.592L8 10.481 3.204 5zm-.753.659 4.796 5.48a1 1 0 0 0 1.506 0l4.796-5.48c.566-.647.106-1.659-.753-1.659H3.204a1 1 0 0 0-.753 1.659z"/>
                    </svg>
                </div>
            </div>
        </div>
    </td>
    @if (isset($group["colors"]))
        <td>
            <div class="select">
                <div class="select__head"></div>
                <ul class="select__list CEB__select_color_js CEB__select_color select_product"  data-id="{{$group["id"]}}">
                    @php
                        $value="";
                    @endphp
                    @foreach($group["colors"] as $color)
                        <li class="select__item {{$color["selected"]}}"
                            data-price="{{$color["price"]}}"
                            data-weight="{{$color["weight"]}}"
                            data-value="{{$color["product"]}}"
                            data-codeColor="#{{$color["hex"]}}"
                            data-codecolortext="#{{$color["font_color"]}}"
                            style="background-color:#{{$color["hex"]}}; color:#{{$color["font_color"]}}; padding: 10px"
                        >
                            {{ $color["name"] }}
                        </li>

                        @if ($color["selected"]=="selected")
                            @php
                                $value=$color["product"];
                            @endphp
                        @endif
                    @endforeach
                </ul>
                <input class="select__input" type="hidden" value="{{$value}}" name="positions[{{ $group["id"]}}][product_id]">
            </div>
        </td>
    @endif

    <td>
        <span id="weight_total_{{$group["id"]}}" class="weight">0</span>
    </td>
    <td>
        <span id="price_client_{{$group["id"]}}">0</span>
        <input type="hidden" name="positions[{{$group["id"]}}][price]" value="0">
    </td>
    <td><span id="price_total_{{$group["id"]}}" class="price">0</span></td>
</tr>
