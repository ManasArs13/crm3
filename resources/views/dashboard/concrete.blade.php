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
            <div class="flex flex-col p-1 bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <thead>
                        <tr class="font-light border-b-2 bg-neutral-200">
                            <th colspan="4" class="font-light"></th>
                            <th class="border-l-2">Начало</th>
                            <th class="border-x-2">Приход</th>
                            <th class="border-r-2">Расход</th>
                            <th>Конец</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $material)
                            <tr class="border-b-2">
                                <td class="m-2" colspan="4">
                                    {{ $material->short_name }}
                                </td>
                                <td class="m-2 text-right" colspan="1">
                                    {{ $material->residual / 1000 }}
                                </td>
                                <td class="m-2 text-right" colspan="1">
                                    -
                                </td>
                                <td class="m-2 text-right" colspan="1">
                                    {{ $material->rashod ? $material->rashod / 1000 : 0 }}
                                </td>
                                <td class="m-2 text-right" colspan="1">
                                    {{ ($material->residual - ($material->rashod ? $material->rashod : 0)) / 1000 }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <div class="flex flex-col p-1 bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <thead>
                        <tr class="font-light border-b-2 text-sm bg-neutral-200">
                            <th class="border-r-2">Время</th>
                            <th class="border-l-2 text-center">Транспорт</th>
                            <th class="border-r-2">На обьекте</th>
                            <th class="border-r-2">Конец рейса</th>
                            <th>Возврат</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                            <tr class="border-b-2 text-sm">
                                <td class="m-2 border-r-2 text-center">
                                    {{ Carbon\Carbon::parse($shipment->created_at)->format('H:i') }}
                                </td>
                                <td class="m-2 text-left">
                                    {{ $shipment->transport ? $shipment->transport->name : 'не указано' }}
                                </td>
                                <td class="m-2 border-x-2 text-center">
                                    {{ Carbon\Carbon::parse($shipment->time_to_come)->format('H:i') }}
                                </td>
                                <td class="m-2 text-center">
                                    {{ Carbon\Carbon::parse($shipment->time_to_out)->format('H:i') }}
                                </td>
                                <td class="m-2 text-center">
                                    {{ Carbon\Carbon::parse($shipment->time_to_return)->format('H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
