<div class="CEB__row">
    <div class="CEB__text2">Доставка</div>
    <div class="CEB_block">
            <select name="attributes[delivery][id]" style="width:25%" class="select2 input change_delivery">
                @foreach ($deliveries as $delivery)
                    <option data-distance="{{ $delivery->distance }}" value="{{ $delivery->ms_id }}" {{($loop->first)?"selected":""}} style="margin: 4px;" >{{ $delivery->name }}</option>
                @endforeach
            </select>


            <select name="attributes[vehicle_type][id]" class="input {{($form!="calcBeton")?'select2':'hidden'}} change_delivery" style="width:25%;">
                    @foreach ($vehicleTypes as $type)
                        <option data-type='{{$type->id}}' value="{{$type->ms_id}}" {{($type->id==4)?"selected":""}} style="margin: 4px;">{{ $type->name }}</option>
                    @endforeach
            </select>

            <input type="text" class="weight-tn input input2" value=0 disabled>
            <input type="text" class="price-tn input input2" value=0 >
            <input type="text" name="attributes[deliveryPrice]" class="input input2" value=0 >

    </div>
    <input type="hidden" >
</div>

<div class="CEB__row">
    <div class="CEB__text2">Пользователь</div>
    <div class="CEB_block flex-column">
        <div class="flex">
            <select name="agent[id]" style="width:25%" class="select2 input">
                <option value="" selected disabled>не выбрано</option>
                @foreach ($contacts as $contact)
                    <option value="{{ $contact->ms_id }}">{{ $contact->name }}</option>
                @endforeach
            </select>

            {{-- Add contact button --}}
            <button type="button" id="button-modal"
                class="inline-block rounded px-2 align-middle text-black hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
            </button>

            <div class="datetime">
                <input type="text" class="input plan deliveryPlannedMoment" readonly placeholder='{{__('column.delivery_date')}}'>
                <input type="hidden"  name="deliveryPlannedMoment" placeholder='{{__('column.delivery_date')}}'>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-check" viewBox="0 0 16 16">
                    <path d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/>
                    <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </div>

            <select name="state" style="width:25%" class="select2 input">
                <option value="" selected disabled>не выбрано</option>
                @foreach ($states as $state)
                    <option value="{{ $state->ms_id }}">{{ $state->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="agent">
            <input type="text" name="agent[name]"  class="input" placeholder = "{{__('column.name')}}">
            <input type="text" name="agent[phone]" class="input phone" placeholder = "{{__('column.phone')}}">
        </div>
    </div>
</div>


<div class="CEB__row">
    <div class="CEB_flex">
        <textarea name="description" class="input" placeholder = "{{__('column.comment')}}"></textarea>
        <textarea name="shipmentAddressFull[addInfo]" class="input" placeholder = "{{__('column.shipment_address')}}"></textarea>
    </div>

    <button type="submit" class="inline-flex items-center rounded bg-green-400 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700 mt-5">Отправить в мс</button>
</div>


<div role="status" class="preloader">
    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
    </svg>
    <span class="sr-only">Loading...</span>
</div>

