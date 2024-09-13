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


            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap" id="DebtorsTable">
                    <thead>

                        <tr class="bg-neutral-200 font-semibold">
                            <th class="px-2 py-3  hover:cursor-pointer" onclick="orderBy(0)" id="th_name">
                                {{ __('column.name') }}</th>
                            <th class="px-2 py-3  hover:cursor-pointer text-right" onclick="orderBy(1)"
                                id="th_date_of_last_shipment">
                                {{ __('column.date_of_last_shipment') }}</th>
                            <th class="px-2 py-3  hover:cursor-pointer text-right" onclick="orderBy(2)" id="th_days">
                                {{ __('column.days') }}</th>
                            <th class="px-2 py-3  hover:cursor-pointer text-right" onclick="orderBy(3)" id="th_balance">
                                {{ __('column.balance') }}</th>
                            <th class="px-2 py-3  hover:cursor-pointer" onclick="orderBy(4)" id="th_description">
                                {{ __('column.description') }}</th>
                            <th class="px-2 py-3  hover:cursor-pointer text-center" onclick="orderBy(5)" id="th_cnt">
                                {{ __('column.cnt') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $sum = 0;
                        @endphp

                        @foreach ($shipments as $shipment)
                            @php
                                $sum += $shipment->balance;
                            @endphp

                            <tr 
                                @if (str_contains(mb_strtolower($shipment->description), 'норм')) class="bg-green-100" @elseif(str_contains(mb_strtolower($shipment->description), 'проблем')) class="bg-yellow-100" @endif>
                                <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-left"><a
                                        href="https://online.moysklad.ru/app/#Company/edit?id={{ $shipment->ms_id }}"
                                        target="__blank">{{ $shipment->name }}</a></td>
                                <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-right">
                                    {{ $shipment->moment }}</td>
                                <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-right">
                                    {{ $shipment->days }}</td>
                                <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-right">
                                    {{ $shipment->balance }}</td>
                                <td class="break-all max-w-60 overflow-hidden px-2 py-3">
                                    {{ $shipment->description }}
                                </td>
                                <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-right">
                                    @if (!is_null($shipment->ship))
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" class="mx-auto"
                                            viewBox="0,0,256,256" width="32px" height="32px">
                                            <g fill-opacity="1" fill="#B3B3B3" fill-rule="nonzero" stroke="none"
                                                stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter"
                                                stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0"
                                                font-family="none" font-weight="none" font-size="none"
                                                text-anchor="none" style="mix-blend-mode: normal">
                                                <g transform="scale(8,8)">
                                                    <path
                                                        d="M28.28125,6.28125l-17.28125,17.28125l-7.28125,-7.28125l-1.4375,1.4375l8,8l0.71875,0.6875l0.71875,-0.6875l18,-18z">
                                                    </path>
                                                </g>
                                            </g>
                                        </svg>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">

                            <td class="break-all text-right overflow-auto px-6 py-3">
                                ВСЕГО:
                            </td>

                            <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-right"></td>
                            <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-right"></td>
                            <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-right">
                                <b>{{ $sum }}</b>
                            </td>
                            <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-right"></td>
                            <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-right"></td>

                        </tr>

                    </tbody>
                </table>
            </div>

        </div>


        <script type="text/javascript">
            function orderBy(column) {

                let sortedRows = Array.from(DebtorsTable.rows).slice(1, -1)
                let totalRow = Array.from(DebtorsTable.rows).slice(DebtorsTable.rows.length - 1);

                let th_column_name = document.getElementById('th_name');
                let th_column_date_of_last_shipment = document.getElementById('th_date_of_last_shipment');
                let th_column_days = document.getElementById('th_days');
                let th_column_balance = document.getElementById('th_balance');
                let th_column_description = document.getElementById('th_description');
                let th_column_cnt = document.getElementById('th_cnt');

                switch (column) {
                    case 0:
                        if (th_column_name.innerText == `Имя ↓`) {
                            th_column_name.innerText = `Имя ↑`
                            sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText > rowB.cells[column].innerText ? 1 : -
                                1);
                        } else {
                            th_column_name.innerText = `Имя ↓`;
                            sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText < rowB.cells[column].innerText ? 1 : -
                                1);
                        }

                        th_column_date_of_last_shipment.innerText = `Дата последней отгрузки`;
                        th_column_days.innerText = `Дни`;
                        th_column_balance.innerText = `Баланс`;
                        th_column_description.innerText = `Комментарий`;
                        th_column_cnt.innerText = `Количество`;
                        break;


                    case 1:
                        if (th_column_date_of_last_shipment.innerText == `Дата последней отгрузки ↓`) {
                            th_column_date_of_last_shipment.innerText = `Дата последней отгрузки ↑`
                            sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText > rowB.cells[column].innerText ? 1 : -
                                1);
                        } else {
                            th_column_date_of_last_shipment.innerText = `Дата последней отгрузки ↓`;
                            sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText < rowB.cells[column].innerText ? 1 : -
                                1);
                        }

                        th_column_name.innerText = `Имя`;
                        th_column_days.innerText = `Дни`;
                        th_column_balance.innerText = `Баланс`;
                        th_column_description.innerText = `Комментарий`;
                        th_column_cnt.innerText = `Количество`;
                        break;

                    case 2:
                        if (th_column_days.innerText == `Дни ↓`) {
                            th_column_days.innerText = `Дни ↑`
                            sortedRows.sort(function(rowA, rowB) {
                                return parseInt(rowA.cells[column].innerText) - parseInt(rowB.cells[column].innerText)
                            });
                        } else {
                            th_column_days.innerText = `Дни ↓`;
                            sortedRows.sort(function(rowA, rowB) {
                                return parseInt(rowB.cells[column].innerText) - parseInt(rowA.cells[column].innerText)
                            });
                        }

                        th_column_date_of_last_shipment.innerText = `Дата последней отгрузки`;
                        th_column_name.innerText = `Имя`;
                        th_column_balance.innerText = `Баланс`;
                        th_column_description.innerText = `Комментарий`;
                        th_column_cnt.innerText = `Количество`;
                        break;

                    case 3:
                        if (th_column_balance.innerText == `Баланс ↓`) {
                            th_column_balance.innerText = `Баланс ↑`
                            sortedRows.sort(function(rowA, rowB) {
                                return parseInt(rowA.cells[column].innerText) - parseInt(rowB.cells[column].innerText)
                            });
                        } else {
                            th_column_balance.innerText = `Баланс ↓`;
                            sortedRows.sort(function(rowA, rowB) {
                                return parseInt(rowB.cells[column].innerText) - parseInt(rowA.cells[column].innerText)
                            });
                        }

                        th_column_date_of_last_shipment.innerText = `Дата последней отгрузки`;
                        th_column_days.innerText = `Дни`;
                        th_column_name.innerText = `Имя`;
                        th_column_description.innerText = `Комментарий`;
                        th_column_cnt.innerText = `Количество`;
                        break;

                    case 4:
                        if (th_column_description.innerText == `Комментарий ↓`) {
                            th_column_description.innerText = `Комментарий ↑`
                            sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText > rowB.cells[column].innerText ? 1 : -
                                1);
                        } else {
                            th_column_description.innerText = `Комментарий ↓`;
                            sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText < rowB.cells[column].innerText ? 1 : -
                                1);
                        }

                        th_column_date_of_last_shipment.innerText = `Дата последней отгрузки`;
                        th_column_days.innerText = `Дни`;
                        th_column_balance.innerText = `Баланс`;
                        th_column_name.innerText = `Имя`;
                        th_column_cnt.innerText = `Количество`;
                        break;

                    case 5:
                        if (th_column_cnt.innerText == `Количество ↓`) {
                            th_column_cnt.innerText = `Количество ↑`
                            sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[column].innerText) > parseInt(rowB.cells[column]
                                    .innerText) ? 1 : -
                                1);
                        } else {
                            th_column_cnt.innerText = `Количество ↓`;
                            sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[column].innerText) < parseInt(rowB.cells[column]
                                    .innerText) ? 1 : -
                                1);
                        }

                        th_column_date_of_last_shipment.innerText = `Дата последней отгрузки`;
                        th_column_days.innerText = `Дни`;
                        th_column_balance.innerText = `Баланс`;
                        th_column_name.innerText = `Имя`;
                        th_column_description.innerText = `Комментарий`;
                        break;
                }

                sortedRows.push(totalRow[0])
                DebtorsTable.tBodies[0].append(...sortedRows);
            }
        </script>

    </div>



</x-app-layout>
