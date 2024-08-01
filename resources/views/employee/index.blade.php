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
                            @if (request()->routeIs('employee.index'))
                                <a href="{{ route('employee.index', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @else
                                <a href="{{ route('employee.index', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('employee.index.block'))
                                <a href="{{ route('employee.index.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @else
                                <a href="{{ route('employee.index.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('employee.index.concrete'))
                                <a href="{{ route('employee.index.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @else
                                <a href="{{ route('employee.index.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @endif
                        </div>
                    </div>

                    <div class="flex px-3 text-center font-bold">
                        @if (request()->routeIs('employee.index.block'))
                            <a href="{{ route('employee.index.block', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('employee.index.block', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @elseif(request()->routeIs('employee.index.concrete'))
                            <a href="{{ route('employee.index.concrete', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('employee.index.concrete', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @else
                            <a href="{{ route('employee.index', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('employee.index', ['date' => $dateNext]) }}"
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

                        @foreach ($entityItems as $entityItem)
                            @php
                                $totalOrders += $entityItem->orders_count;
                                $totalSum += $entityItem->orders_sum_sum;
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
                                                {{ $entityItem->orders_count }}
                                            @break

                                            @case('sum_orders')
                                                {{ $entityItem->orders_sum_sum ? $entityItem->orders_sum_sum : '-' }}
                                            @break

                                            @case('percent')
                                                @if ($entityItem->orders_sum_sum && $entityItem->orders_sum_sum !== 0 && $totalSum && $totalSum !== 0)
                                                    {{ round(100 / ($totalSum / +$entityItem->orders_sum_sum), 2) }}
                                                @else
                                                    -
                                                @endif
                                            @break

                                            @case('new_orders')
                                                {{ $entityItem->new_orders }}
                                            @break

                                            @case('sum_new_orders')
                                                {{ $entityItem->new_orders_sum ? $entityItem->new_orders_sum : '-' }}
                                            @break

                                            @case('percent_new_orders')
                                                @if ($entityItem->new_orders_sum && $entityItem->new_orders_sum !== 0 && $totalNewSumOrders && $totalNewSumOrders !== 0)
                                                    {{ round(100 / ($totalNewSumOrders / +$entityItem->new_orders_sum), 2) }}
                                                @else
                                                    -
                                                @endif
                                            @break

                                            @default
                                                {{ $entityItem->$column }}
                                        @endswitch

                                    </td>
                                @endforeach

                            </tr>
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">

                            @foreach ($resColumns as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-4" style="text-align:right"
                                    @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                    @switch($column)
                                        @case('count_orders')
                                            {{ $totalOrders }}
                                        @break

                                        @case('sum_orders')
                                            {{ $totalSum }}
                                        @break

                                        @case('percent')
                                            100%
                                        @break

                                        @case('new_orders')
                                            {{ $totalNewOrders }}
                                        @break

                                        @case('sum_new_orders')
                                            {{ $totalNewSumOrders }}
                                        @break

                                        @case('percent_new_orders')
                                            100%
                                        @break

                                        @default
                                    @endswitch

                                </td>
                            @endforeach

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



</x-app-layout>
