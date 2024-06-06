<tr>
    <td>
        @if ($form!="calcBeton")
            {{ $group["name"] }}
        @else
            <select name="positions[{{ $group["id"]}}][product_id]"
                data-id="{{$group["id"]}}"
                class="CEB__select_beton_js CEB__select_beton">
                @foreach($productsByGroup as $productByGroup)
                    <option data-price="{{$productByGroup["price"]}}"
                            data-weight="{{$productByGroup["weight"]}}"
                            data-id="{{$productByGroup["id"]}}"
                            value="{{$productByGroup["product"]}}">
                            {{$productByGroup["name"]}}
                    </option>
                @endforeach
            </select>
        @endif
    </td>
    <td>
        <div class="quantity">
            <input type="number"
            name="positions[{{$group["id"]}}][quantity]"
            data-color="{{(isset($group["colors"]))?1:0}}"
            data-id="{{$group["id"]}}"
            {{
                ($left_menu)?'readonly=""':''
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
            <select name="positions[{{ $group["id"]}}][product_id]"
                    data-id="{{$group["id"]}}"
                    class="CEB__select_color_js CEB__select_color select2">
                @foreach($group["colors"] as $color)
                    <option data-price="{{$color["price"]}}"
                            data-weight="{{$color["weight"]}}"
                            value="{{$color["product"]}}"
                            data-codeColor="#{{$color["hex"]}}"
                            data-codecolortext="#{{$color["font_color"]}}"
                            style="background-color:#{{$color["hex"]}}; padding: 10px"
                            {{$color["selected"]}}
                            >
                            {{ $color["name"] }}
                    </option>
                @endforeach
            </select>
        </td>
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
