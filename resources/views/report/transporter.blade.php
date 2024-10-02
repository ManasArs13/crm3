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
                            @if (request()->routeIs('report.transporter'))
                                <a href="{{ route('report.transporter', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @else
                                <a href="{{ route('report.transporter', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('report.transporter.block'))
                                <a href="{{ route('report.transporter.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @else
                                <a href="{{ route('report.transporter.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('report.transporter.concrete'))
                                <a href="{{ route('report.transporter.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @else
                                <a href="{{ route('report.transporter.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @endif
                        </div>
                    </div>

                    <div class="flex px-3 text-center font-bold">
                        @if (request()->routeIs('report.transporter.block'))
                            <a href="{{ route('report.transporter.block', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('report.transporter.block', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @elseif(request()->routeIs('report.transporter.concrete'))
                            <a href="{{ route('report.transporter.concrete', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('report.transporter.concrete', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @else
                            <a href="{{ route('report.transporter', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('report.transporter', ['date' => $dateNext]) }}"
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
                                <th scope="col" class="px-2 py-3  hover:cursor-pointer" id="th_{{ $key }}"
                                    @switch($key)
                                        @case('name')
                                            style="text-align:left"
                                            @break
                                        @case('contact_name')
                                            style="text-align:left"
                                            @break
                                        @default
                                            style="text-align:right"
                                    @endswitch
                                    onclick="orderBy(`{{ $key }}`)">{{ $column }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_count_shipments = 0;
                            $total_price_norm = 0;
                            $total_price = 0;
                            $total_delivery_fee = 0;
                            $total_difference_norm = 0;
                            $total_difference_price = 0;
                            $total_difference_price_percent = 0;
                        @endphp

                        @foreach ($entityItems as $entityItem)
                            @php
                                $total_count_shipments += $entityItem['shipments_count'];
                                $total_price_norm += $entityItem['price_norm'];
                                $total_price += $entityItem['price'];
                                $total_delivery_fee += $entityItem['delivery_fee'];

                                if (
                                    $entityItem['delivery_fee'] &&
                                    $entityItem['delivery_fee'] !== 0 &&
                                    $entityItem['price'] &&
                                    $entityItem['price'] !== 0 &&
                                    abs($entityItem['delivery_fee'] - $entityItem['price']) !== 0
                                ) {
                                    $total_difference_price_percent += round(
                                        (abs($entityItem['delivery_fee'] - $entityItem['price']) /
                                            $entityItem['delivery_fee']) *
                                            100,
                                    );
                                }
                            @endphp
                        @endforeach

                        @foreach ($entityItems as $entityItem)
                            @if ($entityItem['shipments_count'] > 0)
                                <tr class="border-b-2">

                                    <td class="break-all truncate px-2 py-3 text-sm">
                                        {{ $loop->iteration }}
                                    </td>


                                    @foreach ($resColumns as $column => $title)
                                        @switch($column)
                                            @case('contact_name')
                                                <td class="break-all max-w-60 truncate px-2 py-3 text-left">
                                                    {{ $entityItem['contact_name'] ?? '-' }}
                                                </td>
                                            @break

                                            @case('name')
                                                <td class="break-all max-w-60 truncate px-2 py-3 text-left">
                                                    <a href="https://online.moysklad.ru/app/#Company/edit?id={{ $entityItem->ms_id }}"
                                                        target="_blank" class="text-blue-700 hover:text-blue-500">
                                                        {{ $entityItem->$column }}
                                                    </a>
                                                </td>
                                            @break

                                            @case('count_shipments')
                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    {{ $entityItem['shipments_count'] ?? 0 }}
                                                </td>
                                            @break

                                            @case('price_norm')
                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    {{ $entityItem['price_norm'] ?? 0 }}
                                                </td>
                                            @break

                                            @case('price')
                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    {{ $entityItem['price'] ?? 0 }}
                                                </td>
                                            @break

                                            @case('delivery_fee')
                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    {{ $entityItem['delivery_fee'] ?? 0 }}
                                                </td>
                                            @break

                                            @case('difference_norm')
                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    {{ $entityItem['delivery_fee'] - $entityItem['price_norm'] ?? 0 }}</td>
                                            @break

                                            @case('difference_norm_percent')
                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    @if (
                                                        $entityItem['delivery_fee'] &&
                                                            $entityItem['delivery_fee'] !== 0 &&
                                                            $entityItem['price_norm'] &&
                                                            $entityItem['price_norm'] !== 0 &&
                                                            abs($entityItem['delivery_fee'] - $entityItem['price_norm']) !== 0)
                                                        {{ round((abs($entityItem['delivery_fee'] - $entityItem['price_norm']) / $entityItem['delivery_fee']) * 100) }}%
                                                    @else
                                                        0%
                                                    @endif
                                                </td>
                                            @break

                                            @case('difference_price')
                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    {{ $entityItem['delivery_fee'] - $entityItem['price'] ?? 0 }}</td>
                                            @break

                                            @case('difference_price_percent')
                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    @if (
                                                        $entityItem['delivery_fee'] &&
                                                            $entityItem['delivery_fee'] !== 0 &&
                                                            $entityItem['price'] &&
                                                            $entityItem['price'] !== 0 &&
                                                            abs($entityItem['delivery_fee'] - $entityItem['price']) !== 0)
                                                        {{ round((abs($entityItem['delivery_fee'] - $entityItem['price']) / $entityItem['delivery_fee']) * 100) }}%
                                                    @else
                                                        0%
                                                    @endif
                                                </td>
                                            @break
                                        @endswitch
                                    @endforeach

                                </tr>
                            @endif
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">

                            <td class="break-all text-right overflow-auto px-6 py-3">
                                ВСЕГО:
                            </td>

                            @foreach ($resColumns as $column => $title)
                                @switch($column)
                                    @case('count_shipments')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_count_shipments ?? 0 }}
                                        </td>
                                    @break

                                    @case('price_norm')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_price_norm ?? 0 }}
                                        </td>
                                    @break

                                    @case('price')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_price ?? 0 }}
                                        </td>
                                    @break

                                    @case('delivery_fee')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_delivery_fee ?? 0 }}
                                        </td>
                                    @break

                                    @case('difference_norm')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_delivery_fee - $total_price_norm ?? 0 }}</td>
                                    @break

                                    @case('difference_price_norm_percent')
                                    @break

                                    @case('difference_price')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_delivery_fee - $total_price ?? 0 }}</td>
                                    @break

                                    @case('difference_price_percent')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_difference_price_percent ?? 0 }}%</td>
                                    @break

                                    @default
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right"></td>
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

            let sortedRows = Array.from(transportsTable.rows).slice(1, -1);
            let totalRow = Array.from(transportsTable.rows).slice(transportsTable.rows.length - 1);

            let th_contact_name = document.getElementById('th_contact_name');
            let th_count_shipments = document.getElementById('th_count_shipments');
            let th_price_norm = document.getElementById('th_price_norm');
            let th_price = document.getElementById('th_price');
            let th_delivery_fee = document.getElementById('th_delivery_fee');
            let th_difference_price = document.getElementById('th_difference_price');
            let th_difference_price_percent = document.getElementById('th_difference_price_percent');

            switch (column) {
                case 'contact_name':
                    if (th_contact_name.innerText == `Перевозчик ↓`) {
                        th_contact_name.innerText = `Перевозчик ↑`
                        sortedRows.sort((rowA, rowB) => rowA.cells[1].innerText > rowB.cells[1].innerText ? 1 : -
                            1);
                    } else {
                        th_contact_name.innerText = `Перевозчик ↓`;
                        sortedRows.sort((rowA, rowB) => rowA.cells[1].innerText < rowB.cells[1].innerText ? 1 : -
                            1);
                    }

                    th_count_shipments.innerText = `Отгрузок`;
                    th_price_norm.innerText = 'Норма';
                    th_price.innerText = 'Цена';
                    th_delivery_fee.innerText = 'Стоимость';
                    th_difference_price.innerText = '- от цены';
                    th_difference_price_percent.innerText = '- от цены %';
                    break;

                case 'count_shipments':
                    if (th_count_shipments.innerText == `Отгрузок ↓`) {
                        th_count_shipments.innerText = `Отгрузок ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) > parseInt(rowB.cells[2]
                                .innerText) ? 1 : -
                            1);
                    } else {
                        th_count_shipments.innerText = `Отгрузок ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) < parseInt(rowB.cells[2]
                                .innerText) ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик';
                    th_price_norm.innerText = 'Норма';
                    th_price.innerText = 'Цена';
                    th_delivery_fee.innerText = 'Стоимость';
                    th_difference_price.innerText = '- от цены';
                    th_difference_price_percent.innerText = '- от цены %';
                    break;

                case 'price_norm':
                    if (th_price_norm.innerText == `Норма ↓`) {
                        th_price_norm.innerText = `Норма ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) > parseInt(rowB.cells[3]
                                .innerText) ? 1 : -
                            1);
                    } else {
                        th_price_norm.innerText = `Норма ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) < parseInt(rowB.cells[3]
                                .innerText) ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик';
                    th_count_shipments.innerText = 'Отгрузок';
                    th_price.innerText = 'Цена';
                    th_delivery_fee.innerText = 'Стоимость';
                    th_difference_price.innerText = '- от цены';
                    th_difference_price_percent.innerText = '- от цены %';
                    break;

                case 'price':
                    if (th_price.innerText == `Цена ↓`) {
                        th_price.innerText = `Цена ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[4].innerText) > parseInt(rowB.cells[4]
                                .innerText) ? 1 : -
                            1);
                    } else {
                        th_price.innerText = `Цена ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[4].innerText) < parseInt(rowB.cells[4]
                                .innerText) ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик';
                    th_count_shipments.innerText = 'Отгрузок';
                    th_price_norm.innerText = 'Норма';
                    th_delivery_fee.innerText = 'Стоимость';
                    th_difference_price.innerText = '- от цены';
                    th_difference_price_percent.innerText = '- от цены %';
                    break;

                case 'delivery_fee':
                    if (th_delivery_fee.innerText == `Стоимость ↓`) {
                        th_delivery_fee.innerText = `Стоимость ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[5].innerText) > parseInt(rowB.cells[5]
                                .innerText) ? 1 : -
                            1);
                    } else {
                        th_delivery_fee.innerText = `Стоимость ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[5].innerText) < parseInt(rowB.cells[5]
                                .innerText) ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик';
                    th_count_shipments.innerText = 'Отгрузок';
                    th_price_norm.innerText = 'Норма';
                    th_price.innerText = 'Цена';
                    th_difference_price.innerText = '- от цены';
                    th_difference_price_percent.innerText = '- от цены %';
                    break;


                case 'difference_price':
                    if (th_difference_price.innerText == `- от цены ↓`) {
                        th_difference_price.innerText = `- от цены ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[6].innerText) > parseInt(rowB.cells[6]
                                .innerText) ? 1 : -
                            1);
                    } else {
                        th_difference_price.innerText = `- от цены ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[6].innerText) < parseInt(rowB.cells[6]
                                .innerText) ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик';
                    th_count_shipments.innerText = 'Отгрузок';
                    th_price_norm.innerText = 'Норма';
                    th_price.innerText = 'Цена';
                    th_delivery_fee.innerText = 'Стоимость';
                    th_difference_price_percent.innerText = '- от цены %';
                    break;

                case 'difference_price_percent':
                    if (th_difference_price_percent.innerText == `- от цены % ↓`) {
                        th_difference_price_percent.innerText = `- от цены % ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[7].innerText) > parseInt(rowB.cells[7]
                                .innerText) ? 1 : -
                            1);
                    } else {
                        th_difference_price_percent.innerText = `- от цены % ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[7].innerText) < parseInt(rowB.cells[7]
                                .innerText) ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик';
                    th_count_shipments.innerText = 'Отгрузок';
                    th_price_norm.innerText = 'Норма';
                    th_price.innerText = 'Цена';
                    th_delivery_fee.innerText = 'Стоимость';
                    th_difference_price.innerText = '- от цены';
                    break;

            }

            sortedRows.push(totalRow[0])
            transportsTable.tBodies[0].append(...sortedRows);
        }
    </script>

</x-app-layout>
