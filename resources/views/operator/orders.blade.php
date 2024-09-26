<x-app-layout>

    @if (isset($entityName) && $entityName != '')
        <x-slot:title> {{ $entityName }} </x-slot>
    @endif

            <x-slot:head>
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
                </x-slot>

                <div class="w-11/12 mx-auto py-8 max-w-10xl">

                    @if (session('success'))
                        <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="w-full mb-4 items-center rounded-lg text-lg bg-yellow-200 px-6 py-5 text-yellow-700 ">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if (isset($entityName) && $entityName != '')
                        <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
                    @endif

                    <div class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
                        {{-- body card --}}
                        <div class="flex flex-col w-100 bg-white overflow-x-auto">
                            <table class="text-left text-md text-nowrap">
                                <thead>
                                <tr class="bg-neutral-200 font-semibold">
                                    <th scope="col" class="px-2 py-2"></th>
                                    @foreach ($resColumns as $key => $column)
                                        @if ($key === 'remainder' || $key == 'positions_count')
                                            <th scope="col" class="px-2 py-4">{{ $column }}</th>
                                        @elseif(isset($orderBy) && $orderBy == 'desc')
                                            <th scope="col" class="px-2 py-4"
                                                @if (
                                                    (   $column == '№' ||
                                                        $column == 'Сумма оплачено' ||
                                                        $column == 'Имя' ||
                                                        $column == 'Сумма' ||
                                                        $column == 'Кол-во' ||
                                                        $column == 'Остаток' ||
                                                        $column == 'Отг-но' ||
                                                        $column == 'Цена доставки' ||
                                                        $column == 'Сумма зарезервировано' ||
                                                        $column == 'Вес' ||
                                                        $column == 'Дата документа' ||
                                                        $column == 'Плановая дата' ||
                                                        $column == 'Сумма отгружено' ||
                                                        $column == 'Дата создания' ||
                                                        $column == 'Дата обновления' ||
                                                        $column == 'Долг' || is_int($column)) &&
                                                        !preg_match('/_id\z/u', $column) &&
                                                        $column !== 'sostav') style="text-align:right"
                                                @elseif($column == 'Статус') style='text-align:center'
                                                @else style="text-align:left" @endif>
                                                <a class="text-black"
                                                   href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                                @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                                    &#9650;
                                                @endif
                                            </th>
                                        @else
                                            <th scope="col" class="px-2 py-4"
                                                @if (
                                                    (   $column == '№' ||
                                                        $column == 'Сумма оплачено' ||
                                                        $column == 'Имя' ||
                                                        $column == 'Сумма' ||
                                                        $column == 'Кол-во' ||
                                                        $column == 'Остаток' ||
                                                        $column == 'Отг-но' ||
                                                        $column == 'Цена доставки' ||
                                                        $column == 'Сумма зарезервировано' ||
                                                        $column == 'Вес' ||
                                                        $column == 'Дата документа' ||
                                                        $column == 'Плановая дата' ||
                                                        $column == 'Сумма отгружено' ||
                                                        $column == 'Дата создания' ||
                                                        $column == 'Дата обновления' ||
                                                        $column == 'Долг' || is_int($column)) &&
                                                        !preg_match('/_id\z/u', $column) &&
                                                        $column !== 'sostav') style="text-align:right"
                                                @elseif($column == 'Статус') style='text-align:center'
                                                @else style="text-align:left" @endif>
                                                <a class="text-black"
                                                   href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                                @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'asc')
                                                    &#9660;
                                                @endif
                                            </th>
                                        @endif
                                    @endforeach
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalSum = 0;
                                    $totalCount = 0;
                                    $totalShipped = 0;
                                    $totalDeliveryPrice = 0;
                                    $totalPayedSum = 0;
                                    $totalShippedSum = 0;
                                    $totalReservedSum = 0;
                                    $totalDebt = 0;
                                @endphp

                                @foreach ($entityItems as $entityItem)
                                    @php
                                        $totalSum += $entityItem->sum;
                                    @endphp

                                    @php
                                        $totalReservedSum += $entityItem->reserved_sum;
                                    @endphp

                                    @php
                                        $totalDeliveryPrice += $entityItem->delivery_price;
                                    @endphp

                                    @php
                                        $totalShippedSum += $entityItem->shipped_sum;
                                    @endphp

                                    @php
                                        $totalPayedSum += $entityItem->payed_sum;
                                    @endphp

                                    @php
                                        $totalDebt += $entityItem->debt;
                                    @endphp


                                    @php
                                        $total_quantity = 0;
                                        $total_shipped_count = 0;
                                    @endphp

                                    @foreach ($entityItem->positions as $position)
                                        @php
                                            if (
                                                $position->product->building_material !== 'доставка' &&
                                                $position->product->building_material !== 'не выбрано'
                                            ) {
                                                $total_quantity += $position->quantity;
                                                $totalCount += $position->quantity;
                                            }
                                        @endphp
                                    @endforeach

                                    @foreach ($entityItem->shipments as $shipment)
                                        @foreach ($shipment->products as $position)
                                            @php
                                                $total_shipped_count += $position->quantity;
                                                $totalShipped += $position->quantity;
                                            @endphp
                                        @endforeach
                                    @endforeach

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
                                            <td class="break-all max-w-96 px-2 py-2 truncate"
                                                @if (
                                                    (is_int($entityItem->$column) ||
                                                        $column == 'id' ||
                                                        $column == 'payed_sum' ||
                                                        $column == 'name' ||
                                                        $column == 'sum' ||
                                                        $column == 'residual_count' ||
                                                        $column == 'shipped_count' ||
                                                        $column == 'shipped_sum' ||
                                                        $column == 'reserved_sum' ||
                                                        $column == 'weight' ||
                                                        $column == 'date_moment' ||
                                                        $column == 'date_fact' ||
                                                        $column == 'delivery_price_norm' ||
                                                        $column == 'created_at' ||
                                                        $column == 'updated_at' ||
                                                        $column == 'debt') &&
                                                        !preg_match('/_id\z/u', $column) &&
                                                        $column !== 'sostav') style="text-align:right"
                                                @elseif($column == 'status') style='text-align:center'
                                                @else style="text-align:left" @endif
                                                @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                                                @if (preg_match('/_id\z/u', $column))
                                                    @if ($column == 'contact_id')
                                                        {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                                    @elseif($column == 'delivery_id')
                                                        {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                                    @elseif($column == 'transport_id')
                                                        {{ $entityItem->transport->name ? $entityItem->transport->name : '-' }}
                                                    @elseif($column == 'transport_type_id')
                                                        {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                                    @elseif($column == 'status_id')
                                                        @switch($entityItem->$column)
                                                            @case(1)
                                                            <div id="status" class=" px-2 py-1 text-center">
                                                                <span>[N] Новый</span>
                                                            </div>
                                                            @break

                                                            @case(2)
                                                            <div id="status"
                                                                 class=" px-2 py-1 text-center">
                                                                <span>Думают</span>
                                                            </div>
                                                            @break

                                                            @case(3)
                                                            <div id="status"
                                                                 class=" px-2 py-1 text-center">
                                                                <span>[DN] Подтвержден</span>
                                                            </div>
                                                            @break

                                                            @case(4)
                                                            <div id="status"
                                                                 class=" px-2 py-1 text-center">
                                                                <span>На брони</span>
                                                            </div>
                                                            @break

                                                            @case(5)
                                                            <div id="status"
                                                                 class=" px-2 py-1 text-center">
                                                                <span>[DD] Отгружен с долгом</span>
                                                            </div>
                                                            @break

                                                            @case(6)
                                                            <div id="status"
                                                                 class=" px-2 py-1 text-center">
                                                                <span>[DF] Отгружен и закрыт</span>
                                                            </div>
                                                            @break

                                                            @case(7)
                                                            <div id="status"
                                                                 class=" px-2 py-1 text-center">
                                                                <span>[C] Отменен</span>
                                                            </div>
                                                            @break

                                                            @default
                                                            -
                                                        @endswitch
                                                    @endif
                                                @elseif($column == 'remainder')
                                                    @if ($entityItem->residual_norm !== 0 && $entityItem->residual_norm !== null && $entityItem->type !== 'не выбрано')
                                                        {{ round(($entityItem->residual / $entityItem->residual_norm) * 100) }}
                                                        %
                                                    @else
                                                        {{ null }}
                                                    @endif
                                                @elseif(preg_match('/_link/u', $column) && $entityItem->$column !== null && $entityItem->$column !== '')
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
                                                @elseif($column == 'name' || $column == 'id')
                                                    <div>

                                                        @if ($entityItem->$column==null)
                                                            ---
                                                        @else
                                                            {{ $entityItem->$column }}
                                                        @endif
                                                    </div>
                                                @elseif($column == 'positions_count')
                                                    {{ $total_quantity }}
                                                @elseif($column == 'shipped_count')
                                                    {{ $total_shipped_count }}
                                                @elseif($column == 'residual_count')
                                                    {{ $total_quantity - $total_shipped_count >= 0 ? $total_quantity - $total_shipped_count : 0 }}
                                                @elseif($column == 'ms_link' && $entityItem->ms_id)
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
                                                @elseif($column == 'sostav')
                                                    @if (isset($entityItem->positions[0]) && isset($entityItem->positions[0]->product))
                                                        {{ $entityItem->positions[0]->product->building_material == 'бетон' ? $entityItem->positions[0]->product->short_name : '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    @if($column == 'sum' || $column == 'payed_sum' || $column == 'shipped_sum' || $column == 'reserved_sum' || $column == 'delivery_price' || $column == 'debt')
                                                        {{ number_format((int) $entityItem->$column, 0, ',', ' ') }}
                                                    @elseif($column == 'car_number')
                                                        {{ $entityItem->transport->car_number }}
                                                    @elseif($column == 'driver')
                                                        {{ $entityItem->transport->driver }}
                                                    @else
                                                        {{ $entityItem->$column }}
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-nowrap px-2 py-2 flex"></td>

                                    </tr>

                                    @foreach ($entityItem->shipments as $shipment)
                                        <tr style="display: none"
                                            class="border-b-2 bg-green-100 position_column_{!! $entityItem->id !!}">
                                            <td class="text-nowrap px-3 py-2">
                                                {{ $loop->iteration }}
                                            </td>
                                            @foreach ($resColumns as $column => $title)
                                                <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-2 py-2"
                                                    @if (is_int($shipment->$column)) style="text-align:left" @else style="text-align:right" @endif
                                                    @if ($shipment->$column) title="{{ $shipment->$column }}" @endif>
                                                    @if (preg_match('/_id\z/u', $column))
                                                        @if ($column == 'contact_id')
                                                        @elseif($column == 'order_id')
                                                            <div>
                                                                {{ $shipment->order->name }}
                                                            </div>
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
                                                    @elseif(preg_match('/_link/u', $column) && $shipment->$column !== null && $shipment->$column !== '')
                                                        <a href="{{ $shipment->$column }}" target="_blank">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                 height="16" fill="currentColor"
                                                                 class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd"
                                                                      d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                                </path>
                                                                <path fill-rule="evenodd"
                                                                      d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    @elseif($column == 'id')
                                                        <div>
                                                            {{ $shipment->id }}
                                                        </div>
                                                    @elseif($column == 'date_moment' || $column == 'date_plan')
                                                        {{ $shipment->created_at }}
                                                    @elseif($column == 'name')
                                                        <div>
                                                            {{ $shipment->name }}
                                                        </div>
                                                    @elseif($column == 'comment')
                                                        {{ $shipment->description }}
                                                    @elseif($column == 'positions_count')
                                                        @php
                                                            $total_quantity_shipment = 0;
                                                        @endphp

                                                        @foreach ($shipment->products as $position)
                                                            @php
                                                                $total_quantity_shipment += $position->quantity;
                                                            @endphp
                                                        @endforeach

                                                        {{ $total_quantity_shipment }}
                                                    @else
                                                        {{ $shipment->$column }}
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-nowrap px-3 py-2">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach

                                <tr class="border-b-2 bg-gray-100">
                                    <td>
                                    </td>
                                    @foreach ($resColumns as $column => $title)
                                        @if ($column == 'sum')
                                            <td class="px-2 py-2" style="text-align:right">
                                                {{ number_format((int) $totalSum, 0, '.', ' ') }}
                                            </td>
                                        @elseif($column == 'positions_count')
                                            <td class="overflow-auto px-2 py-2" style="text-align:left">
                                                {{ $totalCount }}
                                            </td>
                                        @elseif($column == 'shipped_count')
                                            <td class="overflow-auto px-2 py-2" style="text-align:right">
                                                {{ $totalShipped }}
                                            </td>
                                        @elseif($column == 'residual_count')
                                            <td class="overflow-auto px-2 py-2" style="text-align:right">
                                                {{ $totalCount - $totalShipped }}
                                            </td>
                                        @elseif($column == 'delivery_price')
                                            <td class="overflow-auto px-2 py-2" style="text-align:right">
                                                {{ number_format((int) $totalDeliveryPrice, 0, ',', ' ') }}
                                            </td>
                                        @elseif($column == 'payed_sum')
                                            <td class="overflow-auto px-2 py-2" style="text-align:right">
                                                {{ number_format((int) $totalPayedSum, 0, ',', ' ') }}
                                            </td>
                                        @elseif($column == 'shipped_sum')
                                            <td class="overflow-auto px-2 py-2" style="text-align:right">
                                                {{ number_format((int) $totalShippedSum, 0, ',', ' ') }}
                                            </td>
                                        @elseif($column == 'reserved_sum')
                                            <td class="overflow-auto px-2 py-2" style="text-align:right">
                                                {{ number_format((int) $totalReservedSum, 0, ',', ' ') }}
                                            </td>
                                        @elseif($column == 'debt')
                                            <td class="overflow-auto px-2 py-2" style="text-align:right">
                                                {{ number_format((int) $totalDebt, 0, ',', ' ') }}
                                            </td>
                                        @else
                                            <td>
                                            </td>
                                        @endif
                                    @endforeach
                                    <td>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- footer card --}}
                        <div class="border-t-2 border-neutral-100 px-3 py-3 dark:border-neutral-600 dark:text-neutral-50">
                            {{ $entityItems->appends(request()->query())->links() }}
                        </div>

                    </div>
                </div>
                <script>
                    function printOrder(orderId) {
                        var printUrl = '{{ route('print.order') }}';


                        fetch(printUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Добавляем CSRF-токен
                            },
                            body: JSON.stringify({ id: orderId })
                        })
                            .then(response => response.text())
                            .then(html => {

                                var printFrame = document.createElement('iframe');
                                printFrame.style.position = 'absolute';
                                printFrame.style.width = '0px';
                                printFrame.style.height = '0px';
                                printFrame.style.border = 'none';


                                document.body.appendChild(printFrame);


                                var frameDoc = printFrame.contentWindow.document;
                                frameDoc.open();
                                frameDoc.write(html);
                                frameDoc.close();

                                printFrame.onload = function() {
                                    printFrame.contentWindow.focus();
                                    printFrame.contentWindow.print();

                                    document.body.removeChild(printFrame);
                                };
                            })
                            .catch(error => {
                                console.error('Ошибка:', error);
                            });
                    }


                    document.addEventListener('DOMContentLoaded', function () {
                        const buttons = document.querySelectorAll('[id^="menu-button-"]');

                        buttons.forEach(button => {
                            button.addEventListener('click', function (event) {
                                const id = event.currentTarget.getAttribute('data-id');
                                const dropdownMenu = document.getElementById(`dropdown-menu-${id}`);


                                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                                    if (menu !== dropdownMenu) {
                                        menu.classList.add('hidden');
                                    }
                                });


                                dropdownMenu.classList.toggle('hidden');


                                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                                button.setAttribute('aria-expanded', !isExpanded);
                            });
                        });


                        document.addEventListener('click', function (event) {
                            buttons.forEach(button => {
                                const id = button.getAttribute('data-id');
                                const dropdownMenu = document.getElementById(`dropdown-menu-${id}`);


                                if (!button.contains(event.target) && !dropdownMenu.contains(event.target)) {
                                    dropdownMenu.classList.add('hidden');
                                    button.setAttribute('aria-expanded', 'false');
                                }
                            });
                        });


                    });

                </script>



</x-app-layout>
