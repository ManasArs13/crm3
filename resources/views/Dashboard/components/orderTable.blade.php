<div class="flex flex-col">
    <table class="text-left text-md text-nowrap">
        <thead>
            <tr class="bg-neutral-200 font-semibold">
                <th scope="col" class="px-2 py-4"></th>
                @foreach ($resColumns as $key => $column)
                    <th scope="col" class="px-2 py-4">{{ $column }}</th>
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
                    $total_quantity = 0;
                    $total_shipped_count = 0;
                @endphp

                @foreach ($entityItem->positions as $position)
                    @php
                        $total_quantity += $position->quantity;
                        $totalCount += $position->quantity;
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
                        <td class="text-nowrap px-2 py-4">
                            <button class="buttonForOpen text-normal font-bold"
                                data-id="{!! $entityItem->id !!}">+</button>
                        </td>
                    @else
                        <td class="text-nowrap px-2 py-4">
                        </td>
                    @endif

                    @foreach ($resColumns as $column => $title)
                        <td class="break-all max-w-[15rem] overflow-auto px-2 py-4"
                            @if (is_int($entityItem->$column)) style="text-align:left" @else style="text-align:right" @endif
                            @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                            @if (preg_match('/_id\z/u', $column))
                                @if ($column == 'contact_id')
                                    {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                @elseif($column == 'delivery_id')
                                    {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                @elseif($column == 'transport_type_id')
                                    {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                @elseif($column == 'status_id')
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
                                        fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                        </path>
                                        <path fill-rule="evenodd"
                                            d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                        </path>
                                    </svg>
                                </a>
                            @elseif($column == 'name' || $column == 'id')
                                <a href="{{ route($urlShow, $entityItem->id) }}"
                                    class="text-blue-500 hover:text-blue-600">
                                    {{ $entityItem->$column }}
                                </a>
                            @elseif($column == 'positions_count')
                                {{ $total_quantity }}
                            @elseif($column == 'shipped_count')
                                {{ $total_shipped_count }}
                            @elseif($column == 'residual_count')
                                {{ $total_quantity - $total_shipped_count >= 0 ? $total_quantity - $total_shipped_count : 0 }}
                            @elseif($column == 'ms_link' && $entityItem->ms_id)
                                <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}"
                                    target="_blank">
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
                            @elseif($column == 'date_plan')
                                {{ \Illuminate\Support\Carbon::parse($entityItem->$column)->format('H:i') }}
                            @elseif($column == 'sostav')
                                @if (isset($entityItem->positions[0]) && isset($entityItem->positions[0]->product))
                                    {{ $entityItem->positions[0]->product->building_material == 'бетон' ? $entityItem->positions[0]->product->name : '-' }}
                                @else
                                    -
                                @endif
                            @else
                                {{ $entityItem->$column }}
                            @endif
                        </td>
                    @endforeach

                </tr>

                @foreach ($entityItem->shipments as $shipment)
                    <tr style="display: none" class="border-b-2 bg-green-100 position_column_{!! $entityItem->id !!}">
                        <td class="text-nowrap px-3 py-4">
                            {{ $loop->iteration }}
                        </td>
                        @foreach ($resColumns as $column => $title)
                            <td class="break-all max-w-[15rem] overflow-auto px-2 py-4"
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
                                @elseif(preg_match('/_link/u', $column) && $shipment->$column !== null && $shipment->$column !== '')
                                    <a href="{{ $shipment->$column }}" target="_blank">
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
                                @elseif($column == 'id')
                                    <a href="{{ route('shipment.show', $shipment->id) }}"
                                        class="text-blue-500 hover:text-blue-600">
                                        {{ $shipment->id }}
                                    </a>
                                @elseif($column == 'date_moment' || $column == 'date_plan')
                                    {{ $shipment->created_at }}
                                @elseif($column == 'name')
                                    <a href="{{ route('shipment.show', $shipment->id) }}"
                                        class="text-blue-500 hover:text-blue-600">
                                        {{ $shipment->name }}
                                    </a>
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
                        <td class="text-nowrap px-3 py-4">
                        </td>
                    </tr>
                @endforeach
            @endforeach

            <tr class="border-b-2 bg-gray-100">
                <td>
                </td>
                @foreach ($resColumns as $column => $title)
                    @if ($column == 'sum')
                        <td class="px-2 py-4 text-right">
                            {{ $totalSum }}
                        </td>
                    @elseif($column == 'positions_count')
                        <td class="overflow-auto px-2 py-4 text-right">
                            {{ $totalCount }}
                        </td>
                    @elseif($column == 'shipped_count')
                        <td class="overflow-auto px-2 py-4 text-right">
                            {{ $totalShipped }}
                        </td>
                    @elseif($column == 'residual_count')
                        <td class="overflow-auto px-2 py-4 text-right">
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
