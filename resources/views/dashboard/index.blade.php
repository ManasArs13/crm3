<x-app-layout>
    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    </x-slot>
    <div class="flex flex-col lg:flex-row flex-nowrap gap-3 w-11/12 mx-auto py-10 max-w-10xl">
        <div class="flex flex-col basis-3/4 bg-white rounded-md shadow overflow-x-auto">
            <div class="flex flex-row w-full p-3 justify-between">
                <div class="flex gap-2">
                    <div class="">
                        @if (request()->routeIs('dashboard'))
                            <a href="{{ route('dashboard', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                        @else
                            <a href="{{ route('dashboard', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->routeIs('dashboard-2'))
                            <a href="{{ route('dashboard-2', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                        @else
                            <a href="{{ route('dashboard-2', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->routeIs('dashboard-3'))
                            <a href="{{ route('dashboard-3', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                        @else
                            <a href="{{ route('dashboard-3', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                        @endif
                    </div>
                </div>

                <div class="flex px-3 text-center font-bold">
                    <div class="flex gap-2">
                        <div class="font-medium mx-1 bg-yellow-200 rounded-md px-2">
                            Кол-во: <span id="QuantityProduct">0</span>
                        </div>
                        <div class="font-medium mx-1 bg-green-200 rounded-md px-2">
                            Отг-но: <span id="QuantityShipment">0</span>
                        </div>
                        <div class="font-medium mx-1 bg-red-200 rounded-md px-2">
                            Остаток: <span id="QuantityResidual">0</span>
                        </div>
                    </div>
                    <a href="{{ route('dashboard', ['date_plan' => $datePrev]) }}" class="mx-2 text-lg">&#9668;</a>
                    <p class="mx-2 text-lg">{{ $date }}</p>
                    <a href="{{ route('dashboard', ['date_plan' => $dateNext]) }}" class="mx-2 text-lg">&#9658;</a>
                </div>
            </div>
            @include('dashboard.components.canvas', ['date' => $date])
            <div class="block border-t-2 py-5 overflow-x-scroll">
                @include('dashboard.components.orderTable')
            </div>
        </div>
        <div class="flex flex-col gap-4 basis-1/4">
            <div class="flex flex-col bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <thead>
                        <tr class="font-light bg-neutral-200">
                            <th colspan="4" class="font-light px-1 py-3"></th>
                            <th class="border-l-2 px-1 py-3">Начало</th>
                            <th class="border-x-2 px-1 py-3">Приход</th>
                            <th class="border-r-2 px-1 py-3">Расход</th>
                            <th class="px-1">Конец</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $material)
                            @php
                                $residual_percent =
                                    (($material['residual'] - ($material['rashod'] ? $material['rashod'] : 0)) /
                                        $material['residual_norm']) *
                                    100;
                                $color = match (true) {
                                    $residual_percent <= 30 => 'bg-red-300',
                                    $residual_percent > 30 && $residual_percent <= 70 => 'bg-yellow-300',
                                    default => 'bg-green-300',
                                };
                            @endphp
                            <tr class="border-b-2">
                                <td class="px-1 m-2 py-2 max-w-[150px] truncate" colspan="4">
                                    {{ $material['short_name'] }}
                                </td>
                                <td class="px-1 m-2 text-right border-x-2 py-2" colspan="1">
                                    {{ round($material['residual'] / 1000) }}
                                </td>
                                <td class="px-1 m-2 text-right border-x-2 py-2" colspan="1">
                                    -
                                </td>
                                <td class="px-1 m-2 text-right border-x-2 py-2" colspan="1">
                                    {{ $material['rashod'] ? round($material['rashod'] / 1000) : 0 }}
                                </td>
                                <td class="px-1 m-2 text-right py-2" colspan="1">
                                    <div
                                        class="{{ $color }} rounded-sm p-1 h-6 flex justify-center items-center">
                                        {{ round(($material['residual'] - ($material['rashod'] ? $material['rashod'] : 0)) / 1000) }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="flex flex-col bg-white rounded-md shadow overflow-x-auto">
                <table>
                    <thead>
                        <tr class="font-light bg-neutral-200">
                            <th class="border-r-2 px-2 py-3 text-left">Категория</th>
                            <th class="px-1">Остаток</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            @if (isset($category->remainder))
                                <tr class="border-b-2">
                                    <td class="px-2 m-2 py-2 justify-content-beetwen">
                                        {{ $category->short_name }}
                                    </td>
                                    <td>
                                        <div
                                            @if (round($category->remainder) <= 30) class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center"
                                        @elseif(round($category->remainder) > 30 && round($category->remainder) <= 70)
                                        class="bg-yellow-300 rounded-sm p-1 h-6 flex justify-center items-center"
                                        @else
                                        class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center" @endif>
                                            {{ round($category->remainder) }}%
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <thead>
                        <tr class="font-light border-b-2 text-sm bg-neutral-200">
                            <th class="min-w-[59px]"></th>
                            <th class="border-l-2 py-2 px-1 text-center">Транспорт</th>
                            <th class="border-r-2 py-2 px-1 min-w-[59px]">ГП</th>
                            <th class="border-r-2 py-2 px-1">Статус</th>
                            <th class="py-2 px-1">База</th>
                            <th class="py-2 px-1">P</th>
                            <th class="py-2 px-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $key => $shipment)
                            @if((isset($shipment->first()->transport) &&
                            $shipment->first()->transport->shifts->isNotEmpty() &&
                            !isset($shipment->first()->transport->shifts[0]['end_shift']) &&
                            isset($shipment->first()->transport->shifts[0]['start_shift'])
                            ) || !isset($shipment->first()->transport) || $shipment->first()->transport->shifts->isEmpty())
                            @php
                                $transportName = optional($shipment->first()->transport)->name ?? '-';
                                $transportNumber = optional($shipment->first()->transport)->car_number ?? '-';
                                $transportTonnage = optional($shipment->first()->transport)->tonnage ?? '-';

                                $currentTime = Carbon\Carbon::now();
                                $firstCreatedAt = Carbon\Carbon::parse($shipment->first()->created_at);
                                $firstTimeToCome = Carbon\Carbon::parse($shipment->first()->time_to_come);
                                $firstTimeToOut = Carbon\Carbon::parse($shipment->first()->time_to_out);
                                $firstToReturn = Carbon\Carbon::parse($shipment->first()->time_to_return);

                                if ($currentTime->between($firstCreatedAt, $firstTimeToCome)) {
                                    $statusColor = 'bg-yellow-100';
                                    $statusInfo = 'Отгружен';
                                } elseif ($currentTime->between($firstTimeToCome, $firstTimeToOut)) {
                                    $statusColor = 'bg-sky-200';
                                    $statusInfo = 'На объекте';
                                } elseif ($currentTime->between($firstTimeToOut, $firstToReturn)) {
                                    $statusColor = 'bg-sky-100';
                                    $statusInfo = 'Обратно';
                                } else {
                                    $statusColor = 'bg-green-100';
                                    $statusInfo = 'На базе';
                                }
                            @endphp
                            <tr class="border-b-2 {{ $statusColor }} group">
                                <td class="px-1 m-2 border-r-2 py-3 text-center">{{ $transportNumber }}</td>
                                <td class="px-1 m-2 border-r-2 text-left py-3 max-w-[150px] text-center truncate">
                                    {{ $transportName ? $transportName : 'не указано' }}
                                </td>
                                <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $transportTonnage }}</td>
                                <td class="px-1 m-2 border-x-2 text-center py-3 truncate">{{ $statusInfo }}</td>
                                <td class="px-1 m-2 text-center py-3">{{ $firstToReturn->format('H:i') }}</td>
                                <td class="border-l-2 text-nowrap px-2 py-2">
                                    <button class="buttonForOpen text-normal font-bold text-blue-700 d-count"
                                            data-id="shipment_{{ $key }}">{{ $shipment->count() }}</button>
                                </td>
                                <td class="border-l-2 px-2 m-2 pt-2">
                                    @if(isset($shipment[0]['transport_id']))
                                        <form action="{{ route('api.get.shift_change', ['id' => $shipment[0]['transport_id'], 'date' => $date]) }}" method="post">
                                            <button>
                                                <svg class="group-hover:fill-red-500 fill-red-200" width="10" height="17" viewBox="0 0 343 470" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M341.277 291.181C339.277 285.381 331.977 279.081 325.777 277.781C320.177 276.581 315.877 277.181 310.777 279.781C308.877 280.781 281.877 306.981 250.777 338.081L194.277 394.681L193.777 204.981L193.277 15.1812L190.777 10.6812C189.377 8.28117 186.277 4.88117 183.977 3.18117C179.977 0.481168 178.877 0.181168 171.777 0.181168C164.677 0.181168 163.577 0.481168 159.577 3.18117C157.277 4.88117 154.177 8.28117 152.777 10.6812L150.277 15.1812L149.777 204.981L149.277 394.681L92.2765 337.581C53.7765 299.081 33.9765 279.881 31.2765 278.581C20.8765 273.681 8.17651 278.281 2.67651 288.881C0.37651 293.481 0.17651 302.281 2.27651 307.481C4.47651 312.581 156.577 464.881 162.377 467.681C167.677 470.281 175.877 470.281 181.177 467.681C183.977 466.381 210.677 440.281 262.877 387.981C330.077 320.581 340.577 309.581 341.577 306.081C343.077 300.681 342.977 296.181 341.277 291.181Z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @foreach ($shipment as $num => $transport)
                                @php
                                    $createdAt = Carbon\Carbon::parse($transport->created_at);
                                    $timeToCome = Carbon\Carbon::parse($transport->time_to_come);
                                    $timeToOut = Carbon\Carbon::parse($transport->time_to_out);
                                    $timeToReturn = Carbon\Carbon::parse($transport->time_to_return);
                                @endphp
                                <tr style="display: none;"
                                    class="border-y-2 position_column_shipment_{{ $key }}">
                                    <td class="px-1 m-2 border-r-2 py-3 text-center">
                                        {{ $createdAt->format('H:i') }}
                                    </td>
                                    <td class="px-1 m-2 border-x-2 py-3 text-center">
                                        {{ $timeToCome->format('H:i') }}
                                    </td>
                                    <td class="px-1 m-2 border-x-2 text-center py-3">
                                        {{ $timeToOut->format('H:i') }}
                                    </td>
                                    <td class="px-1 m-2 text-center py-3" colspan="2">
                                        {{ $timeToReturn->format('H:i') }}
                                    </td>
                                    <td class="border-l-2 text-nowrap px-2 py-2">
                                        {{ $num + 1 }}
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        @endforeach
                        @foreach($shifts as $shift)
                            @if(isset($shift->end_shift))
                                <tr class="border-b-2 group">
                                    <td class="px-1 m-2 border-r-2 py-3 text-center">{{ $shift->transport->car_number }}</td>
                                    <td class="px-1 m-2 border-r-2 text-left py-3 max-w-[150px] text-center truncate">
                                        {{ $shift->transport->name ? $shift->transport->name : 'не указано' }}
                                    </td>
                                    <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $shift->transport->tonnage }}</td>
                                    <td class="px-1 m-2 border-x-2 text-center py-3 truncate">-</td>
                                    <td class="px-1 m-2 text-center py-3"></td>
                                    <td class="border-l-2 text-nowrap px-2 py-2"></td>
                                    <td class="border-l-2 px-2 m-2 pt-2">
                                        <form action="{{ route('api.get.shift_change', ['id' => $shift->transport_id, 'date' => $date]) }}" method="post">
                                            <form action="{{ route('api.get.shift_change', ['id' => $shift->transport_id, 'date' => $date]) }}" method="post">
                                                <button>
                                                    <svg class="group-hover:fill-red-500 fill-red-200" width="10" height="17" viewBox="0 0 343 470" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M341.277 291.181C339.277 285.381 331.977 279.081 325.777 277.781C320.177 276.581 315.877 277.181 310.777 279.781C308.877 280.781 281.877 306.981 250.777 338.081L194.277 394.681L193.777 204.981L193.277 15.1812L190.777 10.6812C189.377 8.28117 186.277 4.88117 183.977 3.18117C179.977 0.481168 178.877 0.181168 171.777 0.181168C164.677 0.181168 163.577 0.481168 159.577 3.18117C157.277 4.88117 154.177 8.28117 152.777 10.6812L150.277 15.1812L149.777 204.981L149.277 394.681L92.2765 337.581C53.7765 299.081 33.9765 279.881 31.2765 278.581C20.8765 273.681 8.17651 278.281 2.67651 288.881C0.37651 293.481 0.17651 302.281 2.27651 307.481C4.47651 312.581 156.577 464.881 162.377 467.681C167.677 470.281 175.877 470.281 181.177 467.681C183.977 466.381 210.677 440.281 262.877 387.981C330.077 320.581 340.577 309.581 341.577 306.081C343.077 300.681 342.977 296.181 341.277 291.181Z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </form>
                                    </td>

                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                </table>

            </div>

            <div class="flex flex-col bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <thead>
                    <tr class="font-light border-b-2 text-sm bg-neutral-200">
                        <th class="min-w-[59px]"></th>
                        <th class="border-l-2 py-2 px-1 text-center">Транспорт</th>
                        <th class="border-r-2 py-2 px-1 min-w-[59px]">ГП</th>
                        <th class="border-r-2 py-2 px-1">Статус</th>
                        <th class="py-2 px-1">База</th>
                        <th class="py-2 px-1">P</th>
                        <th class="py-2 px-1"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($shipments as $key => $shipment)
                        @if(isset($shipment->first()->transport) &&
                            $shipment->first()->transport->shifts->isNotEmpty() &&
                            isset($shipment->first()->transport->shifts[0]['end_shift']) &&
                            isset($shipment->first()->transport->shifts[0]['start_shift'])
                            )
                        @php
                            $transportName = optional($shipment->first()->transport)->name ?? '-';
                            $transportNumber = optional($shipment->first()->transport)->car_number ?? '-';
                            $transportTonnage = optional($shipment->first()->transport)->tonnage ?? '-';

                            $currentTime = Carbon\Carbon::now();
                            $firstCreatedAt = Carbon\Carbon::parse($shipment->first()->created_at);
                            $firstTimeToCome = Carbon\Carbon::parse($shipment->first()->time_to_come);
                            $firstTimeToOut = Carbon\Carbon::parse($shipment->first()->time_to_out);
                            $firstToReturn = Carbon\Carbon::parse($shipment->first()->time_to_return);

                            if ($currentTime->between($firstCreatedAt, $firstTimeToCome)) {
                                $statusColor = 'bg-yellow-100';
                                $statusInfo = 'Отгружен';
                            } elseif ($currentTime->between($firstTimeToCome, $firstTimeToOut)) {
                                $statusColor = 'bg-sky-200';
                                $statusInfo = 'На объекте';
                            } elseif ($currentTime->between($firstTimeToOut, $firstToReturn)) {
                                $statusColor = 'bg-sky-100';
                                $statusInfo = 'Обратно';
                            } else {
                                $statusColor = 'bg-green-100';
                                $statusInfo = 'На базе';
                            }
                        @endphp
                        <tr class="border-b-2 {{ $statusColor }} group">
                            <td class="px-1 m-2 border-r-2 py-3 text-center">{{ $transportNumber }}</td>
                            <td class="px-1 m-2 border-r-2 text-left py-3 max-w-[150px] text-center truncate">
                                {{ $transportName ? $transportName : 'не указано' }}
                            </td>
                            <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $transportTonnage }}</td>
                            <td class="px-1 m-2 border-x-2 text-center py-3 truncate">{{ $statusInfo }}</td>
                            <td class="px-1 m-2 text-center py-3">{{ $firstToReturn->format('H:i') }}</td>
                            <td class="border-l-2 text-nowrap px-2 py-2">
                                <button class="buttonForOpen text-normal font-bold text-blue-700 d-count"
                                        data-id="shipment_{{ $key }}">{{ $shipment->count() }}</button>
                            </td>
                            <td class="border-l-2 px-2 m-2 pt-2">
                                @if(isset($shipment[0]['transport_id']))
                                    <form action="{{ route('api.get.shift_change', ['id' => $shipment[0]['transport_id'], 'date' => $date]) }}" method="post">
                                        <button>
                                            <svg class="group-hover:fill-blue-600 fill-blue-300" width="10" height="17" viewBox="0 0 343 470" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.17355 178.631C4.17355 184.431 11.4735 190.731 17.6736 192.031C23.2736 193.231 27.5735 192.631 32.6736 190.031C34.5735 189.031 61.5735 162.831 92.6736 131.731L149.174 75.1312L149.674 264.831L150.174 454.631L152.674 459.131C154.074 461.531 157.174 464.931 159.474 466.631C163.474 469.331 164.574 469.631 171.674 469.631C178.774 469.631 179.874 469.331 183.874 466.631C186.174 464.931 189.274 461.531 190.674 459.131L193.174 454.631L193.674 264.831L194.174 75.1312L251.174 132.231C289.674 170.731 309.474 189.931 312.174 191.231C322.574 196.131 335.274 191.531 340.774 180.931C343.074 176.331 343.274 167.531 341.174 162.331C338.974 157.231 186.874 4.93115 181.074 2.13116C175.774 -0.468842 167.574 -0.468842 162.274 2.13116C159.474 3.43115 132.774 29.5312 80.5735 81.8311C13.3736 149.231 2.87357 160.231 1.87357 163.731C0.373566 169.131 0.473541 173.631 2.17355 178.631Z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                        @foreach ($shipment as $num => $transport)
                            @php
                                $createdAt = Carbon\Carbon::parse($transport->created_at);
                                $timeToCome = Carbon\Carbon::parse($transport->time_to_come);
                                $timeToOut = Carbon\Carbon::parse($transport->time_to_out);
                                $timeToReturn = Carbon\Carbon::parse($transport->time_to_return);
                            @endphp
                            <tr style="display: none;"
                                class="border-y-2 position_column_shipment_{{ $key }}">
                                <td class="px-1 m-2 border-r-2 py-3 text-center">
                                    {{ $createdAt->format('H:i') }}
                                </td>
                                <td class="px-1 m-2 border-x-2 py-3 text-center">
                                    {{ $timeToCome->format('H:i') }}
                                </td>
                                <td class="px-1 m-2 border-x-2 text-center py-3">
                                    {{ $timeToOut->format('H:i') }}
                                </td>
                                <td class="px-1 m-2 text-center py-3" colspan="2">
                                    {{ $timeToReturn->format('H:i') }}
                                </td>
                                <td class="border-l-2 text-nowrap px-2 py-2">
                                    {{ $num + 1 }}
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    @endforeach
                    @foreach($shifts as $shift)
                        @if(!isset($shift->end_shift))
                            <tr class="border-b-2 group">
                                <td class="px-1 m-2 border-r-2 py-3 text-center">{{ $shift->transport->car_number }}</td>
                                <td class="px-1 m-2 border-r-2 text-left py-3 max-w-[150px] text-center truncate">
                                    {{ $shift->transport->name ? $shift->transport->name : 'не указано' }}
                                </td>
                                <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $shift->transport->tonnage }}</td>
                                <td class="px-1 m-2 border-x-2 text-center py-3 truncate">-</td>
                                <td class="px-1 m-2 text-center py-3"></td>
                                <td class="border-l-2 text-nowrap px-2 py-2"></td>
                                <td class="border-l-2 px-2 m-2 pt-2">
                                    <form action="{{ route('api.get.shift_change', ['id' => $shift->transport_id, 'date' => $date]) }}" method="post">
                                        <button>
                                            <svg class="group-hover:fill-blue-600 fill-blue-300" width="10" height="17" viewBox="0 0 343 470" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.17355 178.631C4.17355 184.431 11.4735 190.731 17.6736 192.031C23.2736 193.231 27.5735 192.631 32.6736 190.031C34.5735 189.031 61.5735 162.831 92.6736 131.731L149.174 75.1312L149.674 264.831L150.174 454.631L152.674 459.131C154.074 461.531 157.174 464.931 159.474 466.631C163.474 469.331 164.574 469.631 171.674 469.631C178.774 469.631 179.874 469.331 183.874 466.631C186.174 464.931 189.274 461.531 190.674 459.131L193.174 454.631L193.674 264.831L194.174 75.1312L251.174 132.231C289.674 170.731 309.474 189.931 312.174 191.231C322.574 196.131 335.274 191.531 340.774 180.931C343.074 176.331 343.274 167.531 341.174 162.331C338.974 157.231 186.874 4.93115 181.074 2.13116C175.774 -0.468842 167.574 -0.468842 162.274 2.13116C159.474 3.43115 132.774 29.5312 80.5735 81.8311C13.3736 149.231 2.87357 160.231 1.87357 163.731C0.373566 169.131 0.473541 173.631 2.17355 178.631Z" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex">
                <button class="text-blue-600 bg-neutral-200 p-2 rounded" onclick="toggleShiftModal()">Добавить в смену</button>
            </div>
        </div>
    </div>



    <div class="relative z-10 hidden" id="shift_modal">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form id="shift_form">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                    <h3 class="text-center font-semibold leading-6 text-2xl text-gray-900" id="modal-title">Добавить смену</h3>
                                    <div class="mt-2 flex space-x-4">
                                        <div class="mt-1 w-[60%]">
                                            <label for="transport" class="block text-sm font-medium leading-6 text-gray-900 text-left">Транспорт</label>
                                            <div class="mt-2 w-full">
                                                <select name="transport" id="transport" multiple="multiple" class="select2 h-[36px] block !w-full rounded-md py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                    @foreach($transports as $transport)
                                                        <option value="{{ $transport->id }}" @if($transport->shifts->isNotEmpty()) selected @endif>{{ $transport->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-1 w-[40%]">
                                            <label for="start_shift" class="block text-sm font-medium leading-6 text-gray-900 text-left truncate">Начало смены</label>
                                            <div class="mt-2">
                                                <input type="time" value="08:00" name="start_shift" id="start_shift" autocomplete="given-name" class="block w-full h-[38px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="shift_success" class="text-green-500 text-sm hidden">Изменения сохранены</div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button id="shift_send" type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto">Сохранить</button>
                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="toggleShiftModal()">Закрыть</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .select2-container--default .select2-results>.select2-results__options {
            min-height: 24rem;
            width: 100%;
        }
        .select2-selection, .select2-selection--single{
            /*padding: 5px;*/
            min-height: 36px !important;
            max-height: 120px;
            width: 100% !important;
            overflow: auto;
            border: 1px solid #d1d5db !important;
            border-radius: 5px !important;
        }
        .select2{
            width: 100% !important;
        }
        .select2-selection__arrow{
            top: 4px !important;
        }
    </style>

    <script>
        const day = "{{ $date }}";
        const modal = document.getElementById('shift_modal');
        function toggleShiftModal(){
            modal.classList.toggle('hidden');
        }

        $(document).ready(function() {
            $("#shift_form").on("submit", function(){
                $.ajax({
                    url: '{{ route('api.get.shift_create') }}',
                    method: 'post',
                    dataType: 'json',
                    data: {transports: $("#transport").val(), time: $("#start_shift").val(), day: day},
                    beforeSend: function(){
                        $("#shift_success").hide();
                    },
                    success: function(data){
                        if(data['success'] == true){
                            $("#shift_success").show('slow');
                            location.reload();
                        }
                    }
                });
                return false;
            });

            $(".select2").select2({
                tags: true,
                closeOnSelect: false
            });

        });
    </script>
</x-app-layout>
