<x-app-layout>
    <div class="max-w-10xl flex flex-col lg:flex-row flex-nowrap gap-3 w-11/12 mx-auto py-10">
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
                    <a href="{{ route('dashboard-3', ['date_plan' => $datePrev]) }}" class="mx-2 text-lg">&#9668;</a>
                    <p class="mx-2 text-lg">{{ $date }}</p>
                    <a href="{{ route('dashboard-3', ['date_plan' => $dateNext]) }}" class="mx-2 text-lg">&#9658;</a>
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
                        <tr class="font-light border-b-2 bg-neutral-200">
                            <th colspan="4" class="font-light px-1 py-3"></th>
                            <th class="border-l-2 px-1 py-3">Начало</th>
                            <th class="border-x-2 px-1 py-3">Приход</th>
                            <th class="border-r-2 px-1 py-3">Расход</th>
                            <th class="px-1 py-3">Конец</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($materials as $material)
                        @php
                            $residual_percent = ($material['residual'] - ($material['rashod'] ? $material['rashod'] : 0)) /  $material['residual_norm'] * 100;
                            $color = match (true) {
                                $residual_percent <= 30 => 'bg-red-300',
                                $residual_percent > 30 && $residual_percent <= 70 => 'bg-yellow-300',
                                default => 'bg-green-300'
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
                                <div class="{{ $color }} rounded-sm p-1 h-6 flex justify-center items-center">
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
                    <tr class="font-light border-b-2 text-sm bg-neutral-200">
                        <th></th>
                        <th class="border-r-2 py-2 px-1">№</th>
                        <th class="border-l-2 py-2 px-1 text-center">Транспорт</th>
                        <th class="border-r-2 py-2 px-1">ГП</th>
                        <th class="border-r-2 py-2 px-1">Статус</th>
                        <th class="py-2 px-1">База</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($shipments as $key => $shipment)
                        @php
                            $transportName = optional($shipment->first()->transport)->name ?? '-';
                            $transportNumber = optional($shipment->first()->transport)->car_number ?? '-';
                            $transportTonnage = optional($shipment->first()->transport)->tonnage ?? '-';

                            $currentTime = Carbon\Carbon::now();
                            $firstCreatedAt = Carbon\Carbon::parse($shipment->first()->created_at);
                            $firstTimeToCome = Carbon\Carbon::parse($shipment->first()->time_to_come);
                            $firstTimeToOut = Carbon\Carbon::parse($shipment->first()->time_to_out);
                            $firstToReturn = Carbon\Carbon::parse($shipment->first()->time_to_return);

                            if($currentTime->between($firstCreatedAt, $firstTimeToCome)){
                                $statusColor = 'bg-yellow-100';
                                $statusInfo = 'Отгружен';
                            } elseif($currentTime->between($firstTimeToCome, $firstTimeToOut)){
                                $statusColor = 'bg-sky-200';
                                $statusInfo = 'На объекте';
                            } elseif($currentTime->between($firstTimeToOut, $firstToReturn)){
                                $statusColor = 'bg-sky-100';
                                $statusInfo = 'Обратно';
                            } else{
                                $statusColor = 'bg-green-100';
                                $statusInfo = 'На базе';
                            }
                        @endphp
                        <tr class="border-b-2 {{ $statusColor }}">
                            <td class="border-r-2 text-nowrap px-2 py-2">
                                <button class="buttonForOpen text-normal font-bold text-blue-700 d-count"
                                        data-id="shipment_{{ $key }}">{{ $shipment->count() }}</button>
                            </td>
                            <td class="px-1 m-2 border-r-2 py-3 text-center">{{ $transportNumber }}</td>
                            <td class="px-1 m-2 border-r-2 text-left py-3 max-w-[150px] text-center truncate">
                                {{ $transportName ? $transportName : 'не указано' }}
                            </td>
                            <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $transportTonnage }}</td>
                            <td class="px-1 m-2 border-x-2 text-center py-3 truncate">{{ $statusInfo }}</td>
                            <td class="px-1 m-2 text-center py-3">{{ $firstToReturn->format('H:i') }}</td>
                        </tr>
                        @foreach($shipment as $num => $transport)
                            @php
                                $createdAt = Carbon\Carbon::parse($transport->created_at);
                                $timeToCome = Carbon\Carbon::parse($transport->time_to_come);
                                $timeToOut = Carbon\Carbon::parse($transport->time_to_out);
                                $timeToReturn = Carbon\Carbon::parse($transport->time_to_return);
                            @endphp
                            <tr style="display: none;" class="border-y-2 position_column_shipment_{{ $key }}">
                                <td class="border-r-2 text-nowrap px-2 py-2">
                                    {{ $num+1 }}
                                </td>
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
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
