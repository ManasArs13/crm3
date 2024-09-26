<x-app-layout>

    @if (isset($entityName) && $entityName != '')
        <x-slot:title>
            {{ __('entity.' . $entityName) }}
            </x-slot>
            @endif


            <div class="w-11/12 mx-auto py-8 max-w-10xl">

                @if (session('succes'))
                    <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                        {{ session('succes') }}
                    </div>
                @endif

                @if (isset($entityName) && $entityName != '')
                    <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
                @endif


                <div class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
                    {{-- body --}}
                    <div class="flex flex-col w-100 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold py-2">
                                @foreach ($resColumns as $key => $column)
                                    @if ($key === 'remainder')
                                        <th scope="col" class="px-6 py-4">{{ $column }}</th>
                                    @elseif(isset($orderBy) && $orderBy == 'desc')
                                        <th scope="col" class="px-6 py-4"
                                            @if (
                                                $column == 'Имя' ||
                                                    $column == 'Сумма' ||
                                                    $column == 'Кол-во' ||
                                                    $column == 'Дата обновления' ||
                                                    $column == 'Вес') style="text-align:right" @else style="text-align:left" @endif>
                                            <a class="text-black"
                                               href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                            @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                                &#9650;
                                            @endif
                                        </th>
                                    @else
                                        <th scope="col" class="px-6 py-4"
                                            @if (
                                                $column == 'Имя' ||
                                                    $column == '№' ||
                                                    $column == 'Дата создания' ||
                                                    $column == 'Сумма' ||
                                                    $column == 'Кол-во' ||
                                                    $column == 'Дата обновления' ||
                                                    $column == 'Вес') style="text-align:right" @else style="text-align:left" @endif>
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
                                $totalDeliveryPrice = 0;
                                $totalDeliveryPriceNorm = 0;
                                $totalDeliverySum = 0;
                                $totalPaidSum = 0;
                            @endphp
                            @foreach ($entityItems as $entityItem)
                                @php
                                    $totalSum += $entityItem->suma;
                                @endphp

                                @php
                                    $totalDeliveryPrice += $entityItem->delivery_price;
                                @endphp

                                @php
                                    $totalDeliveryPriceNorm += $entityItem->delivery_price_norm;
                                @endphp

                                @php
                                    $totalDeliverySum += $entityItem->delivery_fee;
                                @endphp

                                @php
                                    $totalPaidSum += $entityItem->paid_sum;
                                @endphp

                                <tr class="border-b-2 py-2">
                                    @foreach ($resColumns as $column => $title)
                                        <td class="break-all max-w-96 px-6 py-2 truncate"
                                            @if (
                                                (is_int($entityItem->$column) ||
                                                    $column == 'id' ||
                                                    $column == 'name' ||
                                                    $column == 'payed_sum' ||
                                                    $column == 'positions_count' ||
                                                    $column == 'residual_count' ||
                                                    $column == 'shipped_count' ||
                                                    $column == 'shipped_sum' ||
                                                    $column == 'reserved_sum' ||
                                                    $column == 'weight' ||
                                                    $column == 'debt') &&
                                                    !preg_match('/_id\z/u', $column) &&
                                                    $column !== 'sostav') style="text-align:right" @else style="text-align:left" @endif
                                            @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                                            @if (preg_match('/_id\z/u', $column))
                                                @if ($column == 'contact_id')
                                                    {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                                @elseif($column == 'order_id')
                                                    <div>
                                                        {{ $entityItem->$column }}
                                                    </div>
                                                @elseif($column == 'delivery_id')
                                                    {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                                @elseif($column == 'transport_id')
                                                    {{ $entityItem->transport ? $entityItem->transport->name : '-' }}
                                                @elseif($column == 'transport_type_id')
                                                    {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                                @else
                                                    {{ $entityItem->$column }}
                                                @endif
                                            @elseif($column == 'status')
                                                {{ $entityItem->$column }}
                                            @elseif($column == 'car_number')
                                                {{ $entityItem->transport->car_number ? $entityItem->transport->car_number : '-' }}
                                            @elseif($column == 'driver')
                                                {{ $entityItem->transport->driver ? $entityItem->transport->driver : '-' }}
                                            @elseif($column == 'remainder')
                                                @if ($entityItem->residual_norm !== 0 && $entityItem->residual_norm !== null && $entityItem->type !== 'не выбрано')
                                                    {{ round(($entityItem->residual / $entityItem->residual_norm) * 100) }}
                                                    %
                                                @else
                                                    {{ null }}
                                                @endif
                                            @elseif(preg_match('/_link/u', $column) && $entityItem->$column !== null && $entityItem->$column !== '')
                                                <a href="{{ $entityItem->$column }}" target="_blank"
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
                                            @elseif($column == 'name' || $column == 'id')
                                                <div>
                                                    @if ($entityItem->column!=null)
                                                        {{ $entityItem->$column }}
                                                    @else
                                                        ---
                                                    @endif
                                                </div>
                                            @elseif($column == 'products_count')
                                                @php
                                                    $total_quantity = 0;
                                                @endphp

                                                @foreach ($entityItem->products as $position)
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

                                                {{ $total_quantity }}
                                            @elseif($column == 'ms_link' && $entityItem->ms_id)
                                                <a href="https://online.moysklad.ru/app/#demand/edit?id={{ $entityItem->ms_id }}"
                                                   target="_blank" class="flex justify-center">
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
                                                @if (isset($entityItem->products[0]) && isset($entityItem->products[0]->product))
                                                    {{ $entityItem->products[0]->product->building_material == 'бетон' ? $entityItem->products[0]->product->name : '-' }}
                                                @else
                                                    -
                                                @endif
                                            @else
                                                @if($column == 'suma' || $column == 'paid_sum' || $column == 'delivery_price' || $column == 'delivery_price_norm' || $column == 'delivery_fee')
                                                    {{ isset($entityItem->$column) ? number_format((int) $entityItem->$column, 0, ',', ' ') : '' }}
                                                @else
                                                    {{ $entityItem->$column }}
                                                @endif
                                            @endif
                                        </td>
                                    @endforeach

                                    {{-- Management --}}
                                    <td class="text-nowrap px-6 py-2 flex">
                                        <button class="rounded bg-yellow-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-black hover:bg-yellow-400">Отгрузить</button>
                                    </td>

                                </tr>
                            @endforeach
                            <tr class="border-b-2 bg-gray-100 py-2">
                                @foreach ($resColumns as $column => $title)
                                    @if ($column == 'suma')
                                        <td class="px-6 py-2">
                                            {{ number_format((int) $totalSum, 0, ',', ' ') }}
                                        </td>
                                    @elseif($column == 'products_count')
                                        <td class="overflow-auto px-6 py-4">
                                            {{ $totalCount }}
                                        </td>
                                    @elseif($column == 'delivery_price')
                                        <td class="overflow-auto px-6 py-4 text-right">
                                            {{ number_format((int) $totalDeliveryPrice, 0, ',', ' ') }}
                                        </td>
                                    @elseif($column == 'delivery_price_norm')
                                        <td class="overflow-auto px-6 py-4 text-right">
                                            {{ number_format((int) $totalDeliveryPriceNorm, 0, ',', ' ') }}
                                        </td>
                                    @elseif($column == 'delivery_fee')
                                        <td class="overflow-auto px-6 py-4 text-right">
                                            {{ number_format((int) $totalDeliverySum, 0, ',', ' ') }}
                                        </td>
                                    @elseif($column == 'paid_sum')
                                        <td class="overflow-auto px-6 py-4 text-right">
                                            {{ number_format((int) $totalPaidSum, 0, ',', ' ') }}
                                        </td>
                                    @else
                                        <td>
                                        </td>
                                    @endif
                                @endforeach
                                    <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- footer --}}
                    <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                        {{ $entityItems->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

            <script>
                function printShipment(shipmentId) {
                    var printUrl = '{{ route('print.shipment') }}';


                    fetch(printUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id: shipmentId })
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
            </script>

</x-app-layout>
