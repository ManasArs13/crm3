<x-app-layout>

    @if (isset($entity) && $entity != '')
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
                            @if (request()->routeIs('report.transport'))
                                <a href="{{ route('report.transport', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @else
                                <a href="{{ route('report.transport', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('report.transport.block'))
                                <a href="{{ route('report.transport.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @else
                                <a href="{{ route('report.transport.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('report.transport.concrete'))
                                <a href="{{ route('report.transport.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @else
                                <a href="{{ route('report.transport.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @endif
                        </div>
                    </div>

                    <div class="flex px-3 text-center font-bold">
                        @if (request()->routeIs('report.transport.block'))
                            <a href="{{ route('report.transport.block', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('report.transport.block', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @elseif(request()->routeIs('report.transport.concrete'))
                            <a href="{{ route('report.transport.concrete', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('report.transport.concrete', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @else
                            <a href="{{ route('report.transport', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('report.transport', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap" id="transportsTable">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">

                            <td class="break-all w-16 overflow-auto px-2 py-3">
                                №
                            </td>

                            @foreach ($resColumns as $key => $column)
                                <th scope="col" class="px-2 py-3">{{ $column }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $TotalCountShipments = 0;
                        @endphp

                        @foreach ($entityItems as $entityItem)
                            @php
                                $TotalCountShipments += $entityItem->shipments_count ?? 0;
                            @endphp
                        @endforeach

                        @foreach ($entityItems as $entityItem)
                            <tr class="border-b-2">

                                <td class="break-all overflow-auto px-2 py-3 text-sm">
                                    {{ $loop->iteration }}
                                </td>


                                @foreach ($resColumns as $column => $title)
                                    @switch($column)
                                        @case('contact_name')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-left">
                                                {{ $entityItem->contact->name ?? '-' }}
                                            </td>
                                        @break

                                        @case('name')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-left">
                                                <a href="https://online.moysklad.ru/app/#Company/edit?id={{ $entityItem->ms_id }}"
                                                    target="_blank" class="text-blue-700 hover:text-blue-500">
                                                    {{ $entityItem->$column }}
                                                </a>
                                            </td>
                                        @break

                                        @case('count_shipments')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                {{ $entityItem->shipments_count ?? 0 }}
                                            </td>
                                        @break

                                        @case('price_norm')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                {{ $entityItem->price_norm ?? 0 }}
                                            </td>
                                        @break

                                        @case('price')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                {{ $entityItem->delivery_price ?? 0 }}
                                            </td>
                                        @break

                                        @case('delivery_fee')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                {{ $entityItem->delivery_fee ?? 0 }}
                                            </td>
                                        @break

                                        @case('difference_norm')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                {{ $entityItem->saldo ?? 0 }}
                                            </td>
                                        @break

                                        @case('difference_norm_percent')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                @if ($entityItem->saldo && $entityItem->saldo !== 0 && $entityItem->price_norm && $entityItem->price_norm !== 0)
                                                    {{ round(100 / ($entityItem->price_norm / +$entityItem->saldo)) }} %
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                        @break

                                        @case('difference_price')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                {{ abs($entityItem->delivery_price - $entityItem->delivery_fee) ?? 0 }}
                                            </td>
                                        @break

                                        @case('difference_price_percent')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                @if (
                                                    $entityItem->delivery_fee &&
                                                        $entityItem->delivery_fee !== 0 &&
                                                        $entityItem->pdelivery_price &&
                                                        $entityItem->delivery_price !== 0)
                                                    {{ round(100 / ($entityItem->delivery_price / abs($entityItem->delivery_price - $entityItem->delivery_fee))) }}
                                                    %
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                        @break
                                    @endswitch
                                @endforeach

                            </tr>
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">

                            <td class="break-all overflow-auto px-2 py-3">
                                ВСЕГО:
                            </td>

                            @foreach ($resColumns as $column => $title)
                                @switch($column)
                                    @case('count_shipments')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $TotalCountShipments ?? 0 }}
                                        </td>
                                    @break

                                    @default
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                        </td>
                                @endswitch
                            @endforeach

                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        function orderBy(column) {

            let sortedRows = Array.from(transportsTable.rows).slice(1)

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

            transportsTable.tBodies[0].append(...sortedRows);
        }
    </script>

</x-app-layout>
