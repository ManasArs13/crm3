@if (count($shipments) > 0)
    <div class="CEB__wrapTable mb-5">
        <table class="sum sum1" style="width:100%" id="DebtorsTable">
            <tr>
                <th class="bg-neutral-200 font-semibold text-start pl-2 pt-1 pb-2 pr-2 w-4/12 hover:cursor-pointer"
                    onclick="orderBy(0)" id="th_name">
                    {{ __('column.name') }}</th>
                <th class="bg-neutral-200 font-semibold text-end pl-2 pt-1 pb-2 pr-2 w-2/12 hover:cursor-pointer"
                    onclick="orderBy(1)" id="th_date_of_last_shipment">
                    {{ __('column.date_of_last_shipment') }}</th>
                <th class="bg-neutral-200 font-semibold text-end pl-2 pt-1 pb-2 pr-2 w-1/12 hover:cursor-pointer"
                    onclick="orderBy(2)" id="th_days">
                    {{ __('column.days') }}</th>
                <th class="bg-neutral-200 font-semibold text-end pl-2 pt-1 pb-2 pr-2 w-1/12 hover:cursor-pointer"
                    onclick="orderBy(3)" id="th_balance">
                    {{ __('column.balance') }}</th>
                <th class="bg-neutral-200 font-semibold text-start pl-2 pt-1 pb-2 pr-2 w-3/12 hover:cursor-pointer"
                    onclick="orderBy(4)" id="th_description">
                    {{ __('column.description') }}</th>
                <th class="bg-neutral-200 font-semibold text-center pl-2 pt-1 pb-2 pr-2 w-1/12 hover:cursor-pointer"
                    onclick="orderBy(5)" id="th_cnt">
                    {{ __('column.cnt') }}</th>
            </tr>
            @php
                $sum = 0;
            @endphp
            @foreach ($shipments as $shipment)
                <tr
                    @if (str_contains(mb_strtolower($shipment->description), 'норм')) class="bg-green-100" @elseif(str_contains(mb_strtolower($shipment->description), 'проблем')) class="bg-yellow-100" @endif>
                    <td class="text-start pl-2 pt-1 pb-2 pr-2 overflow-auto w-4/12"><a
                            href="https://online.moysklad.ru/app/#Company/edit?id={{ $shipment->ms_id }}"
                            target="__blank">{{ $shipment->name }}</a></td>
                    <td class="text-end pl-2 pt-1 pb-2 pr-2 w-2/12">{{ $shipment->moment }}</td>
                    <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12">{{ $shipment->days }}</td>
                    <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12">{{ $shipment->balance }}</td>
                    <td class="text-start pl-2 pt-1 pb-2 pr-2 overflow-auto w-3/12">{{ $shipment->description }}</td>
                    <td class="text-center pl-2 pt-1 pb-2 pr-2 w-1/12">
                        @if (!is_null($shipment->ship))
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                class="mx-auto" viewBox="0,0,256,256" width="32px" height="32px">
                                <g fill-opacity="1" fill="#B3B3B3" fill-rule="nonzero" stroke="none" stroke-width="1"
                                    stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10"
                                    stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none"
                                    font-size="none" text-anchor="none" style="mix-blend-mode: normal">
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
                @php
                    $sum += $shipment->balance;
                @endphp
            @endforeach
            {{-- <tr>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-4/12"></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-2/12"></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12"></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12"><b>{{ $sum }}</b></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-3/12"></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12"></td>
            </tr> --}}

        </table>

        <table class="sum sum1" style="width:100%">
            <tr>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-4/12"></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-2/12"></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12"></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12"><b>{{ $sum }}</b></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-3/12"></td>
                <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12"></td>
            </tr>
        </table>
    </div>

    <script type="text/javascript">
        function orderBy(column) {

            let sortedRows = Array.from(DebtorsTable.rows).slice(1)

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
                        sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText > rowB.cells[column].innerText ? 1 : -
                            1);
                    } else {
                        th_column_days.innerText = `Дни ↓`;
                        sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText < rowB.cells[column].innerText ? 1 : -
                            1);
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
                        sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText > rowB.cells[column].innerText ? 1 : -
                            1);
                    } else {
                        th_column_balance.innerText = `Баланс ↓`;
                        sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText < rowB.cells[column].innerText ? 1 : -
                            1);
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
                        sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText > rowB.cells[column].innerText ? 1 : -
                            1);
                    } else {
                        th_column_cnt.innerText = `Количество ↓`;
                        sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText < rowB.cells[column].innerText ? 1 : -
                            1);
                    }

                    th_column_date_of_last_shipment.innerText = `Дата последней отгрузки`;
                    th_column_days.innerText = `Дни`;
                    th_column_balance.innerText = `Баланс`;
                    th_column_name.innerText = `Имя`;
                    th_column_description.innerText = `Комментарий`;
                    break;

            }

            DebtorsTable.tBodies[0].append(...sortedRows);
        }
    </script>

@endif
