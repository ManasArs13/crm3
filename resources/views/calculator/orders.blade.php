<div class="flex p-4 text-center font-bold">
    <button class="mx-2 text-lg" id="backDate">&#9668;</button>
    <p class="mx-4 text-lg" id="nowDate"></p>
    <button class="mx-4 text-lg" id="nextDate">&#9658;</button>
</div>

<div class="block border-t-2 py-5 overflow-x-scroll">
    <div class="flex flex-col">
        <table class="text-left text-md text-nowrap">
            <thead>
                <tr class="bg-neutral-200">
                      @foreach ($resColumns as $key => $column)
                        <th scope="col" class="px-2 py-4 mx-1 border-spacing-x-px"
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
    
                    <tr class="border-b-2">
    
                        @foreach ($resColumns as $column => $title)
                            <td class="break-all max-w-96 overflow-auto px-2 py-4"
                                @if ($column == 'contact_id' || $column == 'delivery_id' || $column == 'comment' || is_int($column)) style="text-align:left"
                                @elseif($column == 'status') style="text-align:center" 
                                @else style="text-align:right" @endif
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
                                    {{ $products_quantity }}
                                @elseif($column == 'shipped_count')
                                    {{ $products_shipped_count }}
                                @elseif($column == 'residual_count')
                                    {{ $products_quantity - $products_shipped_count }}
                                @elseif($column == 'ms_link' && $entityItem->ms_id)
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
                                @elseif($column == 'date_plan')
                                    {{ \Illuminate\Support\Carbon::parse($entityItem->$column)->format('H:i') }}
                                @elseif($column == 'is_demand')
                                    @if ($entityItem->$column)
                                        <div class="bg-green-400 rounded-full w-3 h-3 mx-auto"></div>
                                    @else
                                        <div class="bg-red-400 rounded-full w-3 h-3 mx-auto"></div>
                                    @endif
                                @elseif($column == 'sostav')
                                    @if (isset($entityItem->positions[0]) && isset($entityItem->positions[0]->product))
                                        {{ $entityItem->positions[0]->product->building_material == 'бетон' ? $entityItem->positions[0]->product->short_name : '-' }}
                                    @else
                                        -
                                    @endif
                                @else
                                    {{ $entityItem->$column }}
                                @endif
                            </td>
                        @endforeach
    
                    </tr>
    
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
    </div>
</div>

    <script type="text/javascript">
        // document.addEventListener("DOMContentLoaded", () => {
        //     now = document.getElementById('nowDate');
        //     now.innerText = new Date().toISOString().slice(0, 10);
        //     data = now.innerText

        //     $.ajax({
        //         url: '/api/get/orders/',
        //         method: 'get',
        //         dataType: 'json',
        //         data: data,
        //         success: function(data) {
        //             console.log(data)
        //         },
        //         error: function(response) {
        //             $("#message").html(response.responseJSON.error);
        //         }
        //     });
        // });

        // function next() {}

        // function back() {}
    </script>
