<div class="flex flex-col">
    <table class="text-left text-md text-nowrap">
        <thead>
            <tr class="bg-neutral-200">
                <th scope="col" class="px-2 py-3"></th>
                @foreach ($resColumns as $key => $column)
                    <th scope="col" class="px-2 py-3 mx-1 border-spacing-x-px"
                        @if ($column == 'Контакт МС' || $column == 'Доставка' || $column == 'Комментарий' || is_int($column)) style="text-align:left"
                    @elseif($column == 'Статус' || $column == 'Ссылка МС' || $column == 'Отгружено')
                        style="text-align:center"
                    @else style="text-align:right" @endif>
                        {{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $totalSum = 0;
                $totalCount = 0;
                $totalShipped = 0;
            @endphp

            @foreach ($entityItems as $entityItem)
                @php
                    $totalSum += $entityItem->sum;
                @endphp

                @php
                    $products_quantity = 0;
                    $products_shipped_count = 0;
                @endphp

                @foreach ($entityItem->positions as $position)
                    @php
                        if (
                            $position->product->building_material !== 'доставка' &&
                            $position->product->building_material !== 'не выбрано'
                        ) {
                            $products_quantity += $position->quantity;
                            $totalCount += $position->quantity;
                        }
                    @endphp
                @endforeach

                @foreach ($entityItem->shipments as $shipment)
                    @foreach ($shipment->products as $position)
                        @php
                            if (
                                $position->product->building_material !== 'доставка' &&
                                $position->product->building_material !== 'не выбрано'
                            ) {
                                $products_shipped_count += $position->quantity;
                                $totalShipped += $position->quantity;
                            }
                        @endphp
                    @endforeach
                @endforeach

                @if ($entityItem->is_demand)
                    <tr class="border-b-2 bg-green-100">
                        @if (count($entityItem->shipments) > 0)
                            <td class="text-nowrap px-2 py-2">
                                <button class="buttonForOpen text-normal font-bold"
                                    data-id="{!! $entityItem->id !!}">+</button>
                            </td>
                        @else
                            <td class="text-nowrap px-2 py-2">
                            </td>
                        @endif

                        @foreach ($resColumns as $column => $title)
                            <td class="break-all max-w-96 overflow-auto px-2 py-2"
                                @switch($column)
                                            @case('contact_id')
                                                style="text-align:left"
                                                @break
                                            @case('delivery_id')
                                                style="text-align:left"
                                                @break
                                            @case('comment')
                                                style="text-align:left"
                                                @break
                                            @case('status')
                                                style="text-align:center"
                                                @break
                                            @default
                                                style="text-align:right"
                                        @endswitch
                                @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                @switch($column)
                                    @case('contact_id')
                                        {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                    @break

                                    @case('delivery_id')
                                        {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                    @break

                                    @case('transport_type_id')
                                        {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                    @break

                                    @case('status_id')
                                        @switch($entityItem->$column)
                                            @case(1)
                                                <div id="status"
                                                    class="rounded border-yellow-500 bg-yellow-400 px-2 py-1 text-center">
                                                    <span>[N] Новый</span>
                                                </div>
                                            @break

                                            @case(2)
                                                <div id="status" class="rounded border-blue-500 bg-blue-400 px-2 py-1 text-center">
                                                    <span>Думают</span>
                                                </div>
                                            @break

                                            @case(3)
                                                <div id="status" class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                    <span>[DN] Подтвержден</span>
                                                </div>
                                            @break

                                            @case(4)
                                                <div id="status"
                                                    class="rounded border-purple-500 bg-purple-400 px-2 py-1 text-center">
                                                    <span>На брони</span>
                                                </div>
                                            @break

                                            @case(5)
                                                <div id="status"
                                                    class="rounded border-orange-500 bg-orange-400 px-2 py-1 text-center">
                                                    <span>[DD] Отгружен с долгом</span>
                                                </div>
                                            @break

                                            @case(6)
                                                <div id="status" class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                    <span>[DF] Отгружен и закрыт</span>
                                                </div>
                                            @break

                                            @case(7)
                                                <div id="status" class="rounded border-red-500 bg-red-400 px-2 py-1 text-center">
                                                    <span>[C] Отменен</span>
                                                </div>
                                            @break

                                            @default
                                                -
                                        @endswitch
                                    @break

                                    @case('remainder')
                                        @if ($entityItem->residual_norm !== 0 && $entityItem->residual_norm !== null && $entityItem->type !== 'не выбрано')
                                            {{ round(($entityItem->residual / $entityItem->residual_norm) * 100) }}
                                            %
                                        @else
                                            {{ null }}
                                        @endif
                                    @break

                                    @case(preg_match('/_id\z/u', $column))
                                        <a href="{{ $entityItem->$column }}" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                </path>
                                                <path fill-rule="evenodd"
                                                    d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                </path>
                                            </svg>
                                        </a>
                                    @break

                                    @case('transport_type_id')
                                        {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                    @break

                                    @case($column == 'name' || $column == 'id')
                                        <a href="{{ route($urlShow, $entityItem->id) }}"
                                            class="text-blue-500 hover:text-blue-600">
                                            {{ $entityItem->$column }}
                                        </a>
                                    @break

                                    @case('positions_count')
                                        {{ $products_quantity }}
                                    @break

                                    @case('residual_count')
                                        {{ $products_shipped_count }}
                                    @break

                                    @case($column == 'ms_link' && $entityItem->ms_id)
                                        <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}"
                                            class="flex justify-center" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                </path>
                                                <path fill-rule="evenodd"
                                                    d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                </path>
                                            </svg>
                                        </a>
                                    @break

                                    @case('date_plan')
                                        {{ \Illuminate\Support\Carbon::parse($entityItem->$column)->format('H:i') }}
                                    @break

                                    @case('is_demand')
                                        @if ($entityItem->$column)
                                            <div class="bg-green-400 rounded-full w-3 h-3 mx-auto"></div>
                                        @else
                                            <div class="bg-red-400 rounded-full w-3 h-3 mx-auto"></div>
                                        @endif
                                    @break

                                    @case('sostav')
                                        @if (
                                            $entityItem->positions->first(function ($value, $key) {
                                                if ($value->product->building_material == 'бетон') {
                                                    return $value->product->short_name;
                                                }
                                            }))
                                            {{ $entityItem->positions->first(function ($value, $key) {
                                                if ($value->product->building_material == 'бетон') {
                                                    return $value->product->short_name;
                                                }
                                            })->product->short_name }}
                                        @else
                                            -
                                        @endif
                                    @break

                                    @default
                                        {{ number_format((int) $entityItem->$column, 0, '.', ' ') }}
                                @endswitch
                            </td>
                        @endforeach
                    </tr>
                @else
                    @if (count($entityItem->shipments) > 0)
                        <tr class="border-b-2 bg-yellow-100">
                            @if (count($entityItem->shipments) > 0)
                                <td class="text-nowrap px-2 py-2">
                                    <button class="buttonForOpen text-normal font-bold"
                                        data-id="{!! $entityItem->id !!}">+</button>
                                </td>
                            @else
                                <td class="text-nowrap px-2 py-2">
                                </td>
                            @endif

                            @foreach ($resColumns as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-2"
                                    @switch($column)
                                            @case('contact_id')
                                                style="text-align:left"
                                                @break
                                            @case('delivery_id')
                                                style="text-align:left"
                                                @break
                                            @case('comment')
                                                style="text-align:left"
                                                @break
                                            @case('status')
                                                style="text-align:center"
                                                @break
                                            @default
                                                style="text-align:right"
                                        @endswitch
                                    @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                    @switch($column)
                                        @case('contact_id')
                                            {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                        @break

                                        @case('delivery_id')
                                            {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                        @break

                                        @case('transport_type_id')
                                            {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                        @break

                                        @case('status_id')
                                            @switch($entityItem->$column)
                                                @case(1)
                                                    <div id="status"
                                                        class="rounded border-yellow-500 bg-yellow-400 px-2 py-1 text-center">
                                                        <span>[N] Новый</span>
                                                    </div>
                                                @break

                                                @case(2)
                                                    <div id="status"
                                                        class="rounded border-blue-500 bg-blue-400 px-2 py-1 text-center">
                                                        <span>Думают</span>
                                                    </div>
                                                @break

                                                @case(3)
                                                    <div id="status"
                                                        class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                        <span>[DN] Подтвержден</span>
                                                    </div>
                                                @break

                                                @case(4)
                                                    <div id="status"
                                                        class="rounded border-purple-500 bg-purple-400 px-2 py-1 text-center">
                                                        <span>На брони</span>
                                                    </div>
                                                @break

                                                @case(5)
                                                    <div id="status"
                                                        class="rounded border-orange-500 bg-orange-400 px-2 py-1 text-center">
                                                        <span>[DD] Отгружен с долгом</span>
                                                    </div>
                                                @break

                                                @case(6)
                                                    <div id="status"
                                                        class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                        <span>[DF] Отгружен и закрыт</span>
                                                    </div>
                                                @break

                                                @case(7)
                                                    <div id="status" class="rounded border-red-500 bg-red-400 px-2 py-1 text-center">
                                                        <span>[C] Отменен</span>
                                                    </div>
                                                @break

                                                @default
                                                    -
                                            @endswitch
                                        @break

                                        @case('remainder')
                                            @if ($entityItem->residual_norm !== 0 && $entityItem->residual_norm !== null && $entityItem->type !== 'не выбрано')
                                                {{ round(($entityItem->residual / $entityItem->residual_norm) * 100) }}
                                                %
                                            @else
                                                {{ null }}
                                            @endif
                                        @break

                                        @case(preg_match('/_id\z/u', $column))
                                            <a href="{{ $entityItem->$column }}" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @break

                                        @case('transport_type_id')
                                            {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                        @break

                                        @case($column == 'name' || $column == 'id')
                                            <a href="{{ route($urlShow, $entityItem->id) }}"
                                                class="text-blue-500 hover:text-blue-600">
                                                {{ $entityItem->$column }}
                                            </a>
                                        @break

                                        @case('positions_count')
                                            {{ $products_quantity }}
                                        @break

                                        @case('residual_count')
                                            {{ $products_shipped_count }}
                                        @break

                                        @case($column == 'ms_link' && $entityItem->ms_id)
                                            <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}"
                                                class="flex justify-center" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @break

                                        @case('date_plan')
                                            {{ \Illuminate\Support\Carbon::parse($entityItem->$column)->format('H:i') }}
                                        @break

                                        @case('is_demand')
                                            @if ($entityItem->$column)
                                                <div class="bg-green-400 rounded-full w-3 h-3 mx-auto"></div>
                                            @else
                                                <div class="bg-red-400 rounded-full w-3 h-3 mx-auto"></div>
                                            @endif
                                        @break

                                        @case('sostav')
                                            @if (
                                                $entityItem->positions->first(function ($value, $key) {
                                                    if ($value->product->building_material == 'бетон') {
                                                        return $value->product->short_name;
                                                    }
                                                }))
                                                {{ $entityItem->positions->first(function ($value, $key) {
                                                    if ($value->product->building_material == 'бетон') {
                                                        return $value->product->short_name;
                                                    }
                                                })->product->short_name }}
                                            @else
                                                -
                                            @endif
                                        @break

                                        @default
                                            {{ number_format((int) $entityItem->$column, 0, '.', ' ') }}
                                    @endswitch
                                </td>
                            @endforeach
                        </tr>
                    @else
                        @if ($entityItem->date_plan < \Carbon\Carbon::now()->format('d-m-Y H:i') && $entityItem->status_id !== 7)
                            <tr class="border-b-2 bg-red-100">
                                @if (count($entityItem->shipments) > 0)
                                    <td class="text-nowrap px-2 py-2">
                                        <button class="buttonForOpen text-normal font-bold"
                                            data-id="{!! $entityItem->id !!}">+</button>
                                    </td>
                                @else
                                    <td class="text-nowrap px-2 py-2">
                                    </td>
                                @endif

                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-96 overflow-auto px-2 py-2"
                                        @switch($column)
                                            @case('contact_id')
                                                style="text-align:left"
                                                @break
                                            @case('delivery_id')
                                                style="text-align:left"
                                                @break
                                            @case('comment')
                                                style="text-align:left"
                                                @break
                                            @case('status')
                                                style="text-align:center"
                                                @break
                                            @default
                                                style="text-align:right"
                                        @endswitch
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                        @switch($column)
                                            @case('contact_id')
                                                {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                            @break

                                            @case('delivery_id')
                                                {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                            @break

                                            @case('transport_type_id')
                                                {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                            @break

                                            @case('status_id')
                                                @switch($entityItem->$column)
                                                    @case(1)
                                                        <div id="status"
                                                            class="rounded border-yellow-500 bg-yellow-400 px-2 py-1 text-center">
                                                            <span>[N] Новый</span>
                                                        </div>
                                                    @break

                                                    @case(2)
                                                        <div id="status"
                                                            class="rounded border-blue-500 bg-blue-400 px-2 py-1 text-center">
                                                            <span>Думают</span>
                                                        </div>
                                                    @break

                                                    @case(3)
                                                        <div id="status"
                                                            class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                            <span>[DN] Подтвержден</span>
                                                        </div>
                                                    @break

                                                    @case(4)
                                                        <div id="status"
                                                            class="rounded border-purple-500 bg-purple-400 px-2 py-1 text-center">
                                                            <span>На брони</span>
                                                        </div>
                                                    @break

                                                    @case(5)
                                                        <div id="status"
                                                            class="rounded border-orange-500 bg-orange-400 px-2 py-1 text-center">
                                                            <span>[DD] Отгружен с долгом</span>
                                                        </div>
                                                    @break

                                                    @case(6)
                                                        <div id="status"
                                                            class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                            <span>[DF] Отгружен и закрыт</span>
                                                        </div>
                                                    @break

                                                    @case(7)
                                                        <div id="status"
                                                            class="rounded border-red-500 bg-red-400 px-2 py-1 text-center">
                                                            <span>[C] Отменен</span>
                                                        </div>
                                                    @break

                                                    @default
                                                        -
                                                @endswitch
                                            @break

                                            @case('remainder')
                                                @if ($entityItem->residual_norm !== 0 && $entityItem->residual_norm !== null && $entityItem->type !== 'не выбрано')
                                                    {{ round(($entityItem->residual / $entityItem->residual_norm) * 100) }}
                                                    %
                                                @else
                                                    {{ null }}
                                                @endif
                                            @break

                                            @case(preg_match('/_id\z/u', $column))
                                                <a href="{{ $entityItem->$column }}" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-box-arrow-in-up-right"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                        </path>
                                                        <path fill-rule="evenodd"
                                                            d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @break

                                            @case('transport_type_id')
                                                {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                            @break

                                            @case($column == 'name' || $column == 'id')
                                                <a href="{{ route($urlShow, $entityItem->id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $entityItem->$column }}
                                                </a>
                                            @break

                                            @case('positions_count')
                                                {{ $products_quantity }}
                                            @break

                                            @case('residual_count')
                                                {{ $products_shipped_count }}
                                            @break

                                            @case($column == 'ms_link' && $entityItem->ms_id)
                                                <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}"
                                                    class="flex justify-center" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-box-arrow-in-up-right"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                        </path>
                                                        <path fill-rule="evenodd"
                                                            d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @break

                                            @case('date_plan')
                                                {{ \Illuminate\Support\Carbon::parse($entityItem->$column)->format('H:i') }}
                                            @break

                                            @case('is_demand')
                                                @if ($entityItem->$column)
                                                    <div class="bg-green-400 rounded-full w-3 h-3 mx-auto"></div>
                                                @else
                                                    <div class="bg-red-400 rounded-full w-3 h-3 mx-auto"></div>
                                                @endif
                                            @break

                                            @case('sostav')
                                                @if (
                                                    $entityItem->positions->first(function ($value, $key) {
                                                        if ($value->product->building_material == 'бетон') {
                                                            return $value->product->short_name;
                                                        }
                                                    }))
                                                    {{ $entityItem->positions->first(function ($value, $key) {
                                                        if ($value->product->building_material == 'бетон') {
                                                            return $value->product->short_name;
                                                        }
                                                    })->product->short_name }}
                                                @else
                                                    -
                                                @endif
                                            @break

                                            @default
                                                {{ number_format((int) $entityItem->$column, 0, '.', ' ') }}
                                        @endswitch
                                    </td>
                                @endforeach
                            </tr>
                        @else
                            <tr class="border-b-2">
                                @if (count($entityItem->shipments) > 0)
                                    <td class="text-nowrap px-2 py-2">
                                        <button class="buttonForOpen text-normal font-bold"
                                            data-id="{!! $entityItem->id !!}">+</button>
                                    </td>
                                @else
                                    <td class="text-nowrap px-2 py-2">
                                    </td>
                                @endif

                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-96 overflow-auto px-2 py-2"
                                        @switch($column)
                                            @case('contact_id')
                                                style="text-align:left"
                                                @break
                                            @case('delivery_id')
                                                style="text-align:left"
                                                @break
                                            @case('comment')
                                                style="text-align:left"
                                                @break
                                            @case('status')
                                                style="text-align:center"
                                                @break
                                            @default
                                                style="text-align:right"
                                        @endswitch
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                        @switch($column)
                                            @case('contact_id')
                                                {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                            @break

                                            @case('delivery_id')
                                                {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                            @break

                                            @case('transport_type_id')
                                                {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                            @break

                                            @case('status_id')
                                                @switch($entityItem->$column)
                                                    @case(1)
                                                        <div id="status"
                                                            class="rounded border-yellow-500 bg-yellow-400 px-2 py-1 text-center">
                                                            <span>[N] Новый</span>
                                                        </div>
                                                    @break

                                                    @case(2)
                                                        <div id="status"
                                                            class="rounded border-blue-500 bg-blue-400 px-2 py-1 text-center">
                                                            <span>Думают</span>
                                                        </div>
                                                    @break

                                                    @case(3)
                                                        <div id="status"
                                                            class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                            <span>[DN] Подтвержден</span>
                                                        </div>
                                                    @break

                                                    @case(4)
                                                        <div id="status"
                                                            class="rounded border-purple-500 bg-purple-400 px-2 py-1 text-center">
                                                            <span>На брони</span>
                                                        </div>
                                                    @break

                                                    @case(5)
                                                        <div id="status"
                                                            class="rounded border-orange-500 bg-orange-400 px-2 py-1 text-center">
                                                            <span>[DD] Отгружен с долгом</span>
                                                        </div>
                                                    @break

                                                    @case(6)
                                                        <div id="status"
                                                            class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                            <span>[DF] Отгружен и закрыт</span>
                                                        </div>
                                                    @break

                                                    @case(7)
                                                        <div id="status"
                                                            class="rounded border-red-500 bg-red-400 px-2 py-1 text-center">
                                                            <span>[C] Отменен</span>
                                                        </div>
                                                    @break

                                                    @default
                                                        -
                                                @endswitch
                                            @break

                                            @case('remainder')
                                                @if ($entityItem->residual_norm !== 0 && $entityItem->residual_norm !== null && $entityItem->type !== 'не выбрано')
                                                    {{ round(($entityItem->residual / $entityItem->residual_norm) * 100) }}
                                                    %
                                                @else
                                                    {{ null }}
                                                @endif
                                            @break

                                            @case(preg_match('/_id\z/u', $column))
                                                <a href="{{ $entityItem->$column }}" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-box-arrow-in-up-right"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                        </path>
                                                        <path fill-rule="evenodd"
                                                            d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @break

                                            @case('transport_type_id')
                                                {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                            @break

                                            @case($column == 'name' || $column == 'id')
                                                <a href="{{ route($urlShow, $entityItem->id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $entityItem->$column }}
                                                </a>
                                            @break

                                            @case('positions_count')
                                                {{ $products_quantity }}
                                            @break

                                            @case('residual_count')
                                                {{ $products_shipped_count }}
                                            @break

                                            @case($column == 'ms_link' && $entityItem->ms_id)
                                                <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}"
                                                    class="flex justify-center" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-box-arrow-in-up-right"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                        </path>
                                                        <path fill-rule="evenodd"
                                                            d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @break

                                            @case('date_plan')
                                                {{ \Illuminate\Support\Carbon::parse($entityItem->$column)->format('H:i') }}
                                            @break

                                            @case('is_demand')
                                                @if ($entityItem->$column)
                                                    <div class="bg-green-400 rounded-full w-3 h-3 mx-auto"></div>
                                                @else
                                                    <div class="bg-red-400 rounded-full w-3 h-3 mx-auto"></div>
                                                @endif
                                            @break

                                            @case('sostav')
                                                @if (
                                                    $entityItem->positions->first(function ($value, $key) {
                                                        if ($value->product->building_material == 'бетон') {
                                                            return $value->product->short_name;
                                                        }
                                                    }))
                                                    {{ $entityItem->positions->first(function ($value, $key) {
                                                        if ($value->product->building_material == 'бетон') {
                                                            return $value->product->short_name;
                                                        }
                                                    })->product->short_name }}
                                                @else
                                                    -
                                                @endif
                                            @break

                                            @default
                                                {{ number_format((int) $entityItem->$column, 0, '.', ' ') }}
                                        @endswitch
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    @endif
                @endif

                @foreach ($entityItem->shipments as $shipment)
                    <tr style="display: none" class="border-b-2 bg-green-100 position_column_{!! $entityItem->id !!}">
                        <td class="text-nowrap px-3 py-2">
                            {{ $loop->iteration }}
                        </td>
                        @foreach ($resColumns as $column => $title)
                            <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-2 py-2"
                                @if (
                                    (is_int($shipment->$column) ||
                                        $column == 'payed_sum' ||
                                        $column !== 'status' ||
                                        $column !== 'description' ||
                                        $column == 'positions_count' ||
                                        $column == 'residual_count' ||
                                        $column == 'shipped_count' ||
                                        $column == 'shipped_sum' ||
                                        $column == 'reserved_sum' ||
                                        $column == 'weight' ||
                                        $column == 'debt') &&
                                        !preg_match('/_id\z/u', $column) &&
                                        $column !== 'sostav') style="text-align:right" @else style="text-align:left" @endif
                                @if ($shipment->$column) title="{{ $shipment->$column }}" @endif>
                                @if (preg_match('/_id\z/u', $column))
                                    @if ($column == 'contact_id')
                                    @elseif($column == 'order_id')
                                        <a href="{{ route('order.show', $shipment->id) }}"
                                            class="text-blue-500 hover:text-blue-600">
                                            {{ $shipment->order->name }}
                                        </a>
                                    @elseif($column == 'delivery_id')
                                        {{ $shipment->delivery ? $shipment->delivery->name : '-' }}
                                    @elseif($column == 'transport_id')
                                        {{ $shipment->transport ? $shipment->transport->name : '-' }}
                                    @elseif($column == 'transport_type_id')
                                        {{ $shipment->transport_type ? $shipment->transport_type->name : '-' }}
                                    @elseif($column == 'status_id')
                                        {{ $shipment->status }}
                                    @else
                                        {{ $shipment->$column }}
                                    @endif
                                @elseif($column == 'remainder')
                                    @if ($shipment->residual_norm !== 0 && $shipment->residual_norm !== null && $shipment->type !== 'не выбрано')
                                        {{ round(($shipment->residual / $shipment->residual_norm) * 100) }}
                                        %
                                    @else
                                        {{ null }}
                                    @endif
                                @elseif($column == 'ms_link')
                                    <a href="{{ $shipment->service_link }}" target="_blank"
                                        class="flex justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-box-arrow-in-up-right"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                            </path>
                                            <path fill-rule="evenodd"
                                                d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                            </path>
                                        </svg>
                                    </a>
                                @elseif($column == 'id')
                                    <a href="{{ route('shipment.show', $shipment->id) }}"
                                        class="text-blue-500 hover:text-blue-600">
                                        {{ $shipment->id }}
                                    </a>
                                @elseif($column == 'date_moment' || $column == 'date_plan')
                                    {{ \Illuminate\Support\Carbon::parse($shipment->created_at)->format('H:i') }}
                                @elseif($column == 'name')
                                    <a href="{{ route('shipment.show', $shipment->id) }}"
                                        class="text-blue-500 hover:text-blue-600">
                                        {{ $shipment->name }}
                                    </a>
                                @elseif($column == 'comment')
                                    {{ $shipment->description }}
                                @elseif($column == 'positions_count')
                                    @php
                                        $products_quantity_shipment = 0;
                                    @endphp

                                    @foreach ($shipment->products as $position)
                                        @php
                                            if (
                                                $position->product->building_material !== 'доставка' &&
                                                $position->product->building_material !== 'не выбрано'
                                            ) {
                                                $products_quantity_shipment += $position->quantity;
                                            }
                                        @endphp
                                    @endforeach

                                    {{ $products_quantity_shipment }}
                                @else
                                    {{ $shipment->$column }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach

            <tr class="border-b-2 bg-gray-100">
                <td class="px-2 py-2 text-right">
                </td>
                @foreach ($resColumns as $column => $title)
                    @if ($column == 'sum')
                        <td class="px-2 py-2 text-right">
                            {{ number_format((int) $totalSum, '0', '.', ' ') }}
                        </td>
                    @elseif($column == 'positions_count')
                        <td class="overflow-auto px-2 py-2 text-right">
                            {{ $totalCount }}
                        </td>
                    @elseif($column == 'shipped_count')
                        <td class="overflow-auto px-2 py-2 text-right">
                            {{ $totalShipped }}
                        </td>
                    @elseif($column == 'residual_count')
                        <td class="overflow-auto px-2 py-2 text-right">
                            {{ $totalCount - $totalShipped }}
                        </td>
                    @else
                        <td>
                        </td>
                    @endif
                @endforeach
            </tr>
        </tbody>
    </table>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {

            let buttons = document.querySelectorAll(".buttonForOpen")
            for (var i = 0; i < buttons.length; i++) {
                let attrib = buttons[i].getAttribute("data-id");
                let but = buttons[i];

                function cl(attr, b) {
                    let positions = document.querySelectorAll(".position_column_" + attr, b);
                    for (var i = 0; i < positions.length; i++) {
                        console.log(positions[i].style.display)
                        if (positions[i].style.display === 'none') {
                            positions[i].style.display = ''
                            b.textContent = '-'
                        } else {
                            positions[i].style.display = 'none'
                            b.textContent = '+'
                        }
                    }
                }
                buttons[i].addEventListener("click", cl.bind(null, attrib, but));
            }
        });
    </script>
</div>
