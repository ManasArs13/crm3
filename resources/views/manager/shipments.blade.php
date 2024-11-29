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






                {{-- Counterpaty --}}
                <div
                    class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04) mt-10">

                    {{-- header card --}}
                    <div class="border-b-2 border-neutral-100">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex gap-2">
                                <div class="">
                                    @if (request()->routeIs('manager.managerShipments'))
                                        <a href="{{ route('manager.managerShipments', ['date' => $date]) }}"
                                           class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                                    @else
                                        <a href="{{ route('manager.managerShipments', ['date' => $date]) }}"
                                           class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                                    @endif
                                </div>
                                <div>
                                    @if (request()->routeIs('manager.managerShipments.block'))
                                        <a href="{{ route('manager.managerShipments.block', ['date' => $date]) }}"
                                           class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                                    @else
                                        <a href="{{ route('manager.managerShipments.block', ['date' => $date]) }}"
                                           class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                                    @endif
                                </div>
                                <div>
                                    @if (request()->routeIs('manager.managerShipments.concerte'))
                                        <a href="{{ route('manager.managerShipments.concerte', ['date' => $date]) }}"
                                           class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                                    @else
                                        <a href="{{ route('manager.managerShipments.concerte', ['date' => $date]) }}"
                                           class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                                    @endif
                                </div>
                            </div>

                            <div class="flex px-3 text-center font-bold">
                                @if (request()->routeIs('manager.managerShipments.block'))
                                    <a href="{{ route('manager.managerShipments.block', ['date' => $datePrev]) }}"
                                       class="mx-2 text-lg">&#9668;</a>
                                    <p class="mx-2 text-lg">{{ $dateRus }}</p>
                                    <a href="{{ route('manager.managerShipments.block', ['date' => $dateNext]) }}"
                                       class="mx-2 text-lg">&#9658;</a>
                                @elseif(request()->routeIs('manager.managerShipments.concerte'))
                                    <a href="{{ route('manager.managerShipments.concerte', ['date' => $datePrev]) }}"
                                       class="mx-2 text-lg">&#9668;</a>
                                    <p class="mx-2 text-lg">{{ $dateRus }}</p>
                                    <a href="{{ route('manager.managerShipments.concerte', ['date' => $dateNext]) }}"
                                       class="mx-2 text-lg">&#9658;</a>
                                @else
                                    <a href="{{ route('manager.managerShipments', ['date' => $datePrev]) }}"
                                       class="mx-2 text-lg">&#9668;</a>
                                    <p class="mx-2 text-lg">{{ $dateRus }}</p>
                                    <a href="{{ route('manager.managerShipments', ['date' => $dateNext]) }}"
                                       class="mx-2 text-lg">&#9658;</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- body card --}}
                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap" id="ContactsTable">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">

                                <td class="break-all w-16 overflow-auto px-2 py-3">
                                    №
                                </td>

                                @foreach ($resColumnsContacts as $key => $column)
                                    <th scope="col" class="px-2 py-3 hover:cursor-pointer" id="th_{{ $key }}"
                                        onclick="orderBy(`{{ $key }}`)"
                                        @if ($column == 'Имя') style="text-align:left" @else style="text-align:right" @endif>
                                        {{ $column }}
                                    </th>
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


                            @foreach ($contactsWithCount as $entityItem)
                                @php
                                    $totalOrdersCount += $entityItem->orders->count();
                                    $totalOrdersSum += $entityItem->orders->sum('sum');
                                    $totalShipmentsCount += $entityItem->shipments->count();
                                    $totalShipmentsSum += $entityItem->shipments->sum('suma');
                                @endphp
                            @endforeach

                            @foreach ($contactsWithCount as $entityItem)
                                <tr class="border-b-2">

                                    <td class="break-all overflow-auto px-2 py-3 text-sm">
                                        {{ $loop->iteration }}
                                    </td>

                                    @foreach ($resColumnsContacts as $column => $title)
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3"
                                            @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif
                                            @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                            @switch($column)
                                                @case('manager_name')
                                                {{ $entityItem->manager->name ?? '-' }}
                                                @break

                                                @case('name')
                                                <a href="https://online.moysklad.ru/app/#Company/edit?id={{ $entityItem->ms_id }}"
                                                   target="_blank" class="text-blue-700 hover:text-blue-500">
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

                            </tbody>
                        </table>
                        <table class="text-left text-md text-nowrap">
                            <tbody>
                            <tr class="border-b-2 bg-gray-100">

                                <td class="break-all text-right overflow-auto px-2 py-3">
                                    ВСЕГО:
                                </td>

                                @foreach ($resColumnsContacts as $column => $title)
                                    @switch($column)
                                        @case('count_orders')
                                        <td class="break-all text-right max-w-96 overflow-auto px-2 py-3 w-[14rem]">
                                            {{ $totalOrdersCount }}
                                        </td>
                                        </td>
                                        @break

                                        @case('sum_orders')
                                        <td class="break-all text-right max-w-96 overflow-auto px-2 py-3 w-[12rem]">
                                            {{ number_format($totalOrdersSum, 0, '.', ' ') }}
                                        </td>
                                        @break

                                        @case('count_shipments')
                                        <td class="break-all text-right max-w-96 overflow-auto px-2 py-3 w-[15rem]">
                                            {{ $totalShipmentsCount }}
                                        </td>
                                        @break

                                        @case('sum_shipments')
                                        <td class="break-all text-right max-w-96 overflow-auto px-2 py-3 w-[12rem]">
                                            {{ number_format($totalShipmentsSum, 0, '.', ' ') }}
                                        </td>
                                        @break
                                    @endswitch
                                @endforeach

                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <script type="text/javascript">
                        function orderBy(column) {

                            let sortedRows = Array.from(ContactsTable.rows).slice(1)

                            let th_column_name = document.getElementById('th_name');
                            let th_column_manager = document.getElementById('th_manager_name');
                            let th_column_count_orders = document.getElementById('th_count_orders');
                            let th_column_sum_orders = document.getElementById('th_sum_orders');
                            let th_column_count_shipments = document.getElementById('th_count_shipments');
                            let th_column_sum_shipments = document.getElementById('th_sum_shipments');

                            switch (column) {
                                case 'name':
                                    if (th_column_name.innerText == `Имя ↓`) {
                                        th_column_name.innerText = `Имя ↑`
                                        sortedRows.sort((rowA, rowB) => rowA.cells[0].innerText > rowB.cells[0].innerText ? 1 : -
                                            1);
                                    } else {
                                        th_column_name.innerText = `Имя ↓`;
                                        sortedRows.sort((rowA, rowB) => rowA.cells[0].innerText < rowB.cells[0].innerText ? 1 : -
                                            1);
                                    }

                                    th_column_manager.innerText = `Менеджер`;
                                    th_column_count_orders.innerText = `Количество заказов`;
                                    th_column_sum_orders.innerText = `Сумма заказов`;
                                    th_column_count_shipments.innerText = `Количество отгрузок`;
                                    th_column_sum_shipments.innerText = `Сумма отгрузок`;
                                    break;


                                case 'manager_name':
                                    if (th_column_manager.innerText == `Менеджер ↓`) {
                                        th_column_manager.innerText = `Менеджер ↑`
                                        sortedRows.sort((rowA, rowB) => rowA.cells[1].innerText > rowB.cells[1].innerText ? 1 : -
                                            1);
                                    } else {
                                        th_column_manager.innerText = `Менеджер ↓`;
                                        sortedRows.sort((rowA, rowB) => rowA.cells[1].innerText < rowB.cells[1].innerText ? 1 : -
                                            1);
                                    }

                                    th_column_name.innerText = `Имя`;
                                    th_column_count_orders.innerText = `Количество заказов`;
                                    th_column_sum_orders.innerText = `Сумма заказов`;
                                    th_column_count_shipments.innerText = `Количество отгрузок`;
                                    th_column_sum_shipments.innerText = `Сумма отгрузок`;
                                    break;

                                case 'count_orders':
                                    if (th_column_count_orders.innerText == `Количество заказов ↓`) {
                                        th_column_count_orders.innerText = `Количество заказов ↑`
                                        sortedRows.sort((rowA, rowB) => rowA.cells[2].innerText > rowB.cells[2].innerText ? 1 : -
                                            1);
                                    } else {
                                        th_column_count_orders.innerText = `Количество заказов ↓`;
                                        sortedRows.sort((rowA, rowB) => rowA.cells[2].innerText < rowB.cells[2].innerText ? 1 : -
                                            1);
                                    }

                                    th_column_manager.innerText = `Менеджер`;
                                    th_column_name.innerText = `Имя`;
                                    th_column_sum_orders.innerText = `Сумма заказов`;
                                    th_column_count_shipments.innerText = `Количество отгрузок`;
                                    th_column_sum_shipments.innerText = `Сумма отгрузок`;
                                    break;

                                case 'sum_orders':
                                    if (th_column_sum_orders.innerText == `Сумма заказов ↓`) {
                                        th_column_sum_orders.innerText = `Сумма заказов ↑`
                                        sortedRows.sort((rowA, rowB) => rowA.cells[3].innerText > rowB.cells[3].innerText ? 1 : -
                                            1);
                                    } else {
                                        th_column_sum_orders.innerText = `Сумма заказов ↓`;
                                        sortedRows.sort((rowA, rowB) => rowA.cells[3].innerText < rowB.cells[3].innerText ? 1 : -
                                            1);
                                    }

                                    th_column_manager.innerText = `Менеджер`;
                                    th_column_count_orders.innerText = `Количество заказов`;
                                    th_column_name.innerText = `Имя`;
                                    th_column_count_shipments.innerText = `Количество отгрузок`;
                                    th_column_sum_shipments.innerText = `Сумма отгрузок`;
                                    break;

                                case 'count_shipments':
                                    if (th_column_count_shipments.innerText == `Количество отгрузок ↓`) {
                                        th_column_count_shipments.innerText = `Количество отгрузок ↑`
                                        sortedRows.sort((rowA, rowB) => rowA.cells[4].innerText > rowB.cells[4].innerText ? 1 : -
                                            1);
                                    } else {
                                        th_column_count_shipments.innerText = `Количество отгрузок ↓`;
                                        sortedRows.sort((rowA, rowB) => rowA.cells[4].innerText < rowB.cells[4].innerText ? 1 : -
                                            1);
                                    }

                                    th_column_manager.innerText = `Менеджер`;
                                    th_column_count_orders.innerText = `Количество заказов`;
                                    th_column_sum_orders.innerText = `Сумма заказов`;
                                    th_column_name.innerText = `Имя`;
                                    th_column_sum_shipments.innerText = `Сумма отгрузок`;
                                    break;

                                case 'sum_shipments':
                                    if (th_column_sum_shipments.innerText == `Сумма отгрузок ↓`) {
                                        th_column_sum_shipments.innerText = `Сумма отгрузок ↑`
                                        sortedRows.sort((rowA, rowB) => rowA.cells[5].innerText > rowB.cells[5].innerText ? 1 : -
                                            1);
                                    } else {
                                        th_column_sum_shipments.innerText = `Сумма отгрузок ↓`;
                                        sortedRows.sort((rowA, rowB) => rowA.cells[5].innerText < rowB.cells[5].innerText ? 1 : -
                                            1);
                                    }

                                    th_column_manager.innerText = `Менеджер`;
                                    th_column_count_orders.innerText = `Количество заказов`;
                                    th_column_sum_orders.innerText = `Сумма заказов`;
                                    th_column_name.innerText = `Имя`;
                                    th_column_count_shipments.innerText = `Количество отгрузок`;
                                    break;
                            }

                            ContactsTable.tBodies[0].append(...sortedRows);
                        }
                    </script>

                </div>
            </div>



</x-app-layout>
