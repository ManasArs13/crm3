<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entityName }}
        </x-slot>
    @endif

    <div class="w-11/12 mx-auto py-8">

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

        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header card --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <div class="flex gap-2">
                        <div class="">
                            @if (request()->routeIs('manager.index'))
                                <a href="{{ route('manager.index', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @else
                                <a href="{{ route('manager.index', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('manager.index.block'))
                                <a href="{{ route('manager.index.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @else
                                <a href="{{ route('manager.index.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('manager.index.concrete'))
                                <a href="{{ route('manager.index.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @else
                                <a href="{{ route('manager.index.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @endif
                        </div>
                    </div>

                    <div class="flex px-3 text-center font-bold">
                        @if (request()->routeIs('manager.index.block'))
                            <a href="{{ route('manager.index.block', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('manager.index.block', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @elseif(request()->routeIs('manager.index.concrete'))
                            <a href="{{ route('manager.index.concrete', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('manager.index.concrete', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @else
                            <a href="{{ route('manager.index', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('manager.index', ['date' => $dateNext]) }}"
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
                            @foreach ($resColumns as $key => $column)
                                <th scope="col" class="px-2 py-4"
                                    @if ($column == 'Имя') style="text-align:left" @else style="text-align:right" @endif>
                                    {{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $totalOrders = 0;
                            $totalSum = 0;
                            $totalNewOrders = 0;
                            $totalNewSumOrders = 0;
                        @endphp

                        @php
                            if ($orders) {
                                $totalOrders += count($orders);
                                $totalSum += $orders->sum('sum');
                            }
                            if ($ordersNew) {
                                $totalNewOrders += count($ordersNew);
                                $totalNewSumOrders += $ordersNew->sum('sum');
                            }
                        @endphp

                        @foreach ($entityItems as $entityItem)
                            @php
                                $totalOrders += $entityItem->all_orders;
                                $totalSum += $entityItem->all_orders_sum;
                                $totalNewOrders += $entityItem->new_orders;
                                $totalNewSumOrders += $entityItem->new_orders_sum;
                            @endphp
                        @endforeach

                        @foreach ($entityItems as $entityItem)
                            <tr class="border-b-2">

                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-96 overflow-auto px-2 py-4"
                                        @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                        @switch($column)
                                            @case('count_orders')
                                                {{ $entityItem->all_orders }}
                                            @break

                                            @case('sum_orders')
                                                {{ $entityItem->all_orders_sum ? $entityItem->all_orders_sum : '0' }}
                                            @break

                                            @case('percent')
                                                @if ($entityItem->all_orders_sum && $entityItem->all_orders_sum !== 0 && $totalSum && $totalSum !== 0)
                                                    {{ round(100 / ($totalSum / +$entityItem->all_orders_sum), 2) }} %
                                                @else
                                                    0%
                                                @endif
                                            @break

                                            @case('new_orders')
                                                {{ $entityItem->new_orders }}
                                            @break

                                            @case('sum_new_orders')
                                                {{ $entityItem->new_orders_sum ? $entityItem->new_orders_sum : '0' }}
                                            @break

                                            @case('percent_new_orders')
                                                @if ($entityItem->new_orders_sum && $entityItem->new_orders_sum !== 0 && $totalNewSumOrders && $totalNewSumOrders !== 0)
                                                    {{ round(100 / ($totalNewSumOrders / +$entityItem->new_orders_sum), 2) }} %
                                                @else
                                                    0%
                                                @endif
                                            @break

                                            @default
                                                {{ $entityItem->$column }}
                                        @endswitch

                                    </td>
                                @endforeach

                            </tr>
                        @endforeach

                        {{-- Orders without Manager --}}
                        <tr class="border-b-2">

                            @foreach ($resColumns as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-4"
                                    @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif>

                                    @switch($column)
                                        @case('name')
                                            Не выбрано
                                        @break

                                        @case('count_orders')
                                            {{ count($orders) }}
                                        @break

                                        @case('sum_orders')
                                            {{ $orders ? $orders->sum('sum') : 0 }}
                                        @break

                                        @case('percent')
                                            @if ($orders->sum('sum') && $orders->sum('sum') !== 0 && $totalSum && $totalSum !== 0)
                                                {{ round(100 / ($totalSum / +$orders->sum('sum')), 2) }} %
                                            @else
                                                0%
                                            @endif
                                        @break

                                        @case('new_orders')
                                            {{ count($ordersNew) }}
                                        @break

                                        @case('sum_new_orders')
                                            {{ $ordersNew ? $ordersNew->sum('sum') : 0 }}
                                        @break

                                        @case('percent_new_orders')
                                            @if ($ordersNew->sum('sum') && $ordersNew->sum('sum') !== 0 && $totalNewSumOrders && $totalNewSumOrders !== 0)
                                                {{ round(100 / ($totalNewSumOrders / +$ordersNew->sum('sum')), 2) }} %
                                            @else
                                                0%
                                            @endif
                                        @break

                                        @default
                                    @endswitch

                                </td>
                            @endforeach

                        </tr>

                        <tr class="border-b-2 bg-gray-100">

                            @foreach ($resColumns as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-4"
                                    @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif>

                                    @switch($column)
                                        @case('name')
                                            Всего:
                                        @break

                                        @case('count_orders')
                                            {{ $totalOrders }}
                                        @break

                                        @case('sum_orders')
                                            {{ $totalSum }}
                                        @break

                                        @case('percent')
                                            {{ $totalSum ? '100%' : '0%' }}
                                        @break

                                        @case('new_orders')
                                            {{ $totalNewOrders }}
                                        @break

                                        @case('sum_new_orders')
                                            {{ $totalNewSumOrders }}
                                        @break

                                        @case('percent_new_orders')
                                            {{ $totalNewSumOrders ? '100%' : '0%' }}
                                        @break

                                        @default
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