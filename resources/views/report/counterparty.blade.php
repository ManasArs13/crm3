<x-app-layout>

    @if (isset($entityName) && $entityName != '')
        <x-slot:title>
            {{ $entityName }}
        </x-slot>
    @endif

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

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header card --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <div class="flex gap-2">
                        <div class="">
                            @if (request()->routeIs('report.counteparty'))
                                <a href="{{ route('report.counteparty', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @else
                                <a href="{{ route('report.counteparty', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('report.counteparty.block'))
                                <a href="{{ route('report.counteparty.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @else
                                <a href="{{ route('report.counteparty.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('report.counteparty.concrete'))
                                <a href="{{ route('report.counteparty.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @else
                                <a href="{{ route('report.counteparty.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @endif
                        </div>
                    </div>

                    <div class="flex px-3 text-center font-bold">
                        @if (request()->routeIs('report.counteparty.block'))
                            <a href="{{ route('report.counteparty.block', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('report.counteparty.block', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @elseif(request()->routeIs('report.counteparty.concrete'))
                            <a href="{{ route('report.counteparty.concrete', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('report.counteparty.concrete', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @else
                            <a href="{{ route('report.counteparty', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('report.counteparty', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">

                            <td class="break-all w-16 overflow-auto px-2 py-3">
                                №
                            </td>



                            @foreach ($resColumns as $key => $column)
                                @if ($orderBy == 'asc')
                                    <th scope="col" class="px-2 py-3"
                                        @if ($column == 'Имя') style="text-align:left" @else style="text-align:right" @endif>
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key)
                                            &#9660;
                                        @endif
                                    </th>
                                @elseif($orderBy == 'desc')
                                    <th scope="col" class="px-2 py-3"
                                        @if ($column == 'Имя') style="text-align:left" @else style="text-align:right" @endif>
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key)
                                            &#9650;
                                        @endif
                                    </th>
                                @else
                                    <th scope="col" class="px-2 py-3"
                                        @if ($column == 'Имя') style="text-align:left" @else style="text-align:right" @endif>
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key)
                                            &#9660;
                                        @endif
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $totalOrdersCount = 0;
                            $totalOrdersSum = 0;
                            $totalShipmentsCount = 0;
                            $totalShipmentsSum = 0;
                        @endphp


                        @foreach ($entityItems as $entityItem)
                            @php
                                $totalOrdersCount += $entityItem->orders->count();
                                $totalOrdersSum += $entityItem->orders->sum('sum');
                                $totalShipmentsCount += $entityItem->shipments->count();
                                $totalShipmentsSum += $entityItem->shipments->sum('suma');
                            @endphp
                        @endforeach

                        @foreach ($entityItems as $entityItem)
                            <tr class="border-b-2">

                                <td class="break-all overflow-auto px-2 py-3 text-sm">
                                    {{ $loop->iteration }}
                                </td>



                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-96 truncate px-2 py-3"
                                        @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                        @switch($column)
                                            @case('manager_name')
                                            {{ $entityItem->manager->name ?? 'No Manager' }}
                                            @break
                                            @case('name')
                                                <a href="https://online.moysklad.ru/app/#Company/edit?id={{ $entityItem->ms_id }}" target="_blank" class="text-blue-700 hover:text-blue-500">
                                                    {{ $entityItem->$column }}
                                                </a>
                                            @break

                                            @case('count_orders')
                                                {{ $entityItem->orders->count() }}
                                            @break

                                            @case('sum_orders')
                                                {{ number_format($entityItem->orders->sum('sum') ? $entityItem->orders->sum('sum') : '0', 0, '.', ' ') }}
                                            @break

                                            @case('count_shipments')
                                                {{ $entityItem->shipments->count() }}
                                            @break

                                            @case('sum_shipments')
                                                {{ number_format($entityItem->shipments->sum('suma') ? $entityItem->shipments->sum('suma') : '0', 0, '.', ' ') }}
                                            @break


                                            @default
                                                {{ $entityItem->$column }}
                                        @endswitch

                                    </td>
                                @endforeach

                            </tr>
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">

                            <td class="break-all overflow-auto px-2 py-3">
                                ВСЕГО:
                            </td>




                            @foreach ($resColumns as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-3"
                                    @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif>

                                    @switch($column)
                                        @case('name')

                                        @break

                                        @case('manager_name')

                                        @break

                                        @case('count_orders')
                                            {{ $totalOrdersCount }}
                                        @break

                                        @case('sum_orders')
                                            {{ number_format($totalOrdersSum, 0, '.', ' ') }}
                                        @break

                                        @case('count_shipments')
                                            {{ $totalShipmentsCount }}
                                        @break

                                        @case('sum_shipments')
                                            {{ number_format($totalShipmentsSum, 0, '.', ' ') }}
                                        @break

                                        @default
                                            {{ $entityItem->$column }}
                                    @endswitch

                                </td>
                            @endforeach

                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>



</x-app-layout>
