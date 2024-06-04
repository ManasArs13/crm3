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
    </td>
    @if (isset($group["colors"]))
        <td>
            <select name="positions[{{ $group["id"]}}][product_id]"
                    data-id="{{$group["id"]}}"
                    class="CEB__select_color_js CEB__select_color">
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
