<div class="CEB__row">
    <div class="CEB__text2">Доставка</div>
    <div class="CEB__wrapSlider">
        <select name="attributes[delivery][id]" class="change_delivery" style="border: 1px solid gray;padding: 4px;" id="delivery">
            @foreach ($deliveries as $delivery)
            <option data-distance="{{ $delivery->distance }}" value="{{ $delivery->ms_id }}" style="margin: 4px;">{{ $delivery->name }}</option>
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
