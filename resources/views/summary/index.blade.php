<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        @vite(['resources/js/jquery-ui.min.js', 'resources/js/jquery.ui.touch-punch.js'])
    </x-slot>
    <div class="w-11/12 mx-auto py-8 max-w-10xl">

        <h3 class="text-4xl font-bold mb-6">{{ __('title.summary') }}</h3>
        {{-- header --}}


        <div class="flex flex-col basis-3/4 bg-white rounded-md shadow overflow-x-auto">
            <div class="flex flex-row w-full p-3 justify-between">
                <div class="flex flex-wrap gap-1 flex-row items-start">
                    <div class="">
                        <button data-dataset="бетон" class="toggle-dataset rounded bg-blue-600 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</button>
                    </div>
                    <div>
                        <button data-dataset="блок" class="toggle-dataset rounded bg-blue-600 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</button>
                    </div>
                    <div>
                        <button data-dataset="доставка" class="toggle-dataset rounded bg-blue-600 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ДОСТАВКА</button>
                    </div>
                    <div>
                        <button data-dataset="Сделок амо" class="toggle-dataset rounded bg-blue-300 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">СДЕЛОК АМО</button>
                    </div>
                    <div>
                        <button data-dataset="Закрыто сделок" class="toggle-dataset rounded bg-blue-300 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ЗАКРЫТО СДЕЛОК</button>
                    </div>
                    <div>
                        <button data-dataset="Успешных сделок" class="toggle-dataset rounded bg-blue-300 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">УСПЕШНЫХ СДЕЛОК</button>
                    </div>
                    <div>
                        <button data-dataset="Циклы" class="toggle-dataset rounded bg-blue-300 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ЦИКЛЫ</button>
                    </div>
                    <div>
                        <button data-dataset="Входящие звонки" class="toggle-dataset rounded bg-blue-300 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВХОДЯЩИЕ ЗВОНКИ</button>
                    </div>
                    <div>
                        <button data-dataset="Исходящие звонки" class="toggle-dataset rounded bg-blue-300 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ИСХОДЯЩИЕ ЗВОНКИ</button>
                    </div>
                    <div>
                        <button data-dataset="Беседы" class="toggle-dataset rounded bg-blue-300 px-6 pb-2 pt-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕСЕДЫ</button>
                    </div>
                </div>
                <div class="flex flex-row gap-1 w-100">
                    <select id="period" class="select-default border border-solid border-neutral-300 rounded w-full py-2">
                        <option value="month">Месяц</option>
                        <option value="week">Неделя</option>
                    </select>
                </div>
            </div>
            @include('summary.canvas')
        </div>
        <div class="flex flex-wrap mt-5">
            <div class="CEB__wrapTable mb-5 w-full md:w-1/2 xl:w-1/4 sm:pr-2">
                <table class="sum border w-full">
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.mutualSettlementMain') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumMutualSettlementMain, 1, '.', ' ') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.suppliers') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($mainSuppliers, 1, '.', ' ') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ __('summary.carriers') }}
                        </th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumCarriers, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ __('summary.buyers') }}
                        </th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumBuyer, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ __('summary.others') }}
                        </th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumAnother, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ __('summary.unfilled') }}
                        </th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumUnfilled, 1, '.', ' ') }}</td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>

                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.total') }}</th>
                        <td class="text-end font-semibold pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($total, 1, '.', ' ') }}</td>
                    </tr>

                </table>
            </div>
            <div class="CEB__wrapTable mb-5 w-full md:w-1/2 xl:w-1/4 xl:pr-2">
                <table class="sum border w-full">
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.materials') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumMaterials, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.products') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumProducts, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.balanceMs') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($msBalance, 1, '.', ' ') }}</td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>

                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.total') }}</th>
                        <td class="text-end font-semibold pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumMaterials +$sumProducts + $msBalance, 1, '.', ' ') }}</td>
                    </tr>

                </table>
            </div>
            <div class="CEB__wrapTable mb-5 w-full md:w-1/2 xl:w-1/4 sm:pr-2">
                <table class="sum border w-full">
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.balanceMs') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($msBalance, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.balanceOur') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($ourBalance, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.saldo') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($ourBalance - $msBalance, 1, '.', ' ') }}</td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>
                    <tr class="h-[41px] border-x border-gray-200 bg-gray-200">
                        <th class=""></th>
                        <td class=""></td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.norm_material') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($materialNorm->value ?? 0, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.fact') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format(($sumMaterials - $sumMutualSettlementMain), 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.saldo') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format(($sumMaterials - $sumMutualSettlementMain) - $materialNorm->value, 1, '.', ' ') }}</td>
                    </tr>

                </table>
            </div>
            <div class="CEB__wrapTable mb-5 w-full md:w-1/2 xl:w-1/4">
                <table class="sum border w-full">
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.materials') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumMaterials, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.products') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($sumProducts, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.balanceMs') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($msBalance, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.mutualSettlementMain') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format(-$sumMutualSettlementMain, 1, '.', ' ') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ __('summary.carriers') }}
                        </th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format(-$sumCarriers, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ __('summary.carriers_two') }}
                        </th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format(-1 * $carriers_two, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ __('summary.box_office') }}
                        </th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format(-$kassaAndTinkoff->firstWhere('name', 'Касса 2')->balance ?? 0, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-normal text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ __('summary.rabotnikoff') }}
                        </th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format(-$kassaAndTinkoff->firstWhere('name', 'Работникофф')->balance ?? 0, 1, '.', ' ') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 border border-gray-300">
                            {{ __('summary.total') }}</th>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 border border-gray-300">{{ number_format($totals_two, 1, '.', ' ') }}</td>
                    </tr>
                </table>
            </div>

        </div>


        <div class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            {{-- header --}}
            <div class="border-b-2 border-neutral-100 mb-2 py-2">
                <div class="flex flex-wrap w-full p-3 items-start">
                    <div class="flex flex-wrap gap-1 flex-col sm:flex-row items-start">
                        <div>
                            <button data-type="all" type="button" class="fetchMutualSettlements rounded bg-blue-600 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                {{ __('summary.all') }}</button>
                        </div>
                        <div>
                            <button data-type="10" type="button" class="fetchMutualSettlements rounded bg-blue-300 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                {{ __('summary.suppliers') }}</button>
                        </div>
                        <div>
                            <button data-type="8" type="button" class="fetchMutualSettlements rounded bg-blue-300 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                {{ __('summary.mutualSettlementMain') }}</button>
                        </div>
                        <div>
                            <button data-type="9" type="button" class="fetchMutualSettlements rounded bg-blue-300 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                {{ __('summary.carriers') }}</button>
                        </div>
                        <div>
                            <button data-type="4" type="button" class="fetchMutualSettlements rounded bg-blue-300 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                {{ __('summary.buyers') }}</button>
                        </div>
                        <div>
                            <button data-type="24" type="button" class="fetchMutualSettlements rounded bg-blue-300 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                {{ __('summary.others') }}</button>
                        </div>
                        <div>
                            <button data-type="other" type="button" class="fetchMutualSettlements rounded bg-blue-300 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                {{ __('summary.unfilled') }}</button>
                        </div>
                    </div>
                </div>
            </div>


            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap" id="DebtorsTable">
                    <thead>

                    <tr class="bg-neutral-200 font-semibold">
                        <th class="px-2 py-3 hover:cursor-pointer" onclick="orderBy(0)" id="th_name">
                            {{ __('column.name') }}</th>
                        <th class="px-2 py-3 hover:cursor-pointer text-right" onclick="orderBy(1)"
                            id="th_date_of_last_shipment">
                            {{ __('column.date_of_last_operation') }}</th>
                        <th class="px-2 py-3 hover:cursor-pointer text-right" onclick="orderBy(2)" id="th_days">
                            {{ __('column.days') }}</th>
                        <th class="px-2 py-3 hover:cursor-pointer text-right" onclick="orderBy(3)" id="th_balance">
                            {{ __('column.balance') }}</th>
                        <th class="px-2 py-3 hover:cursor-pointer" onclick="orderBy(4)" id="th_description">
                            {{ __('column.description') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                <div id="pagination"></div>
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

                        th_column_date_of_last_shipment.innerText = `Дата последней операции`;
                        th_column_days.innerText = `Дни`;
                        th_column_balance.innerText = `Баланс`;
                        th_column_description.innerText = `Комментарий`;
                        break;


                    case 1:
                        if (th_column_date_of_last_shipment.innerText == `Дата последней операции ↓`) {
                            th_column_date_of_last_shipment.innerText = `Дата последней операции ↑`
                            sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText > rowB.cells[column].innerText ? 1 : -
                                1);
                        } else {
                            th_column_date_of_last_shipment.innerText = `Дата последней операции ↓`;
                            sortedRows.sort((rowA, rowB) => rowA.cells[column].innerText < rowB.cells[column].innerText ? 1 : -
                                1);
                        }

                        th_column_name.innerText = `Имя`;
                        th_column_days.innerText = `Дни`;
                        th_column_balance.innerText = `Баланс`;
                        th_column_description.innerText = `Комментарий`;
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

                        th_column_date_of_last_shipment.innerText = `Дата последней операции`;
                        th_column_name.innerText = `Имя`;
                        th_column_balance.innerText = `Баланс`;
                        th_column_description.innerText = `Комментарий`;
                        break;

                    case 3:
                        if (th_column_balance.innerText == `Баланс ↓`) {
                            th_column_balance.innerText = `Баланс ↑`
                            sortedRows.sort(function(rowA, rowB) {
                                const valueA = parseFloat(rowA.cells[column].innerText.replace(/\s+/g, ''));
                                const valueB = parseFloat(rowB.cells[column].innerText.replace(/\s+/g, ''));
                                return valueA - valueB;
                            });
                        } else {
                            th_column_balance.innerText = `Баланс ↓`;
                            sortedRows.sort(function(rowA, rowB) {
                                const valueA = parseFloat(rowA.cells[column].innerText.replace(/\s+/g, ''));
                                const valueB = parseFloat(rowB.cells[column].innerText.replace(/\s+/g, ''));
                                return valueB - valueA;
                            });
                        }

                        th_column_date_of_last_shipment.innerText = `Дата последней операции`;
                        th_column_days.innerText = `Дни`;
                        th_column_name.innerText = `Имя`;
                        th_column_description.innerText = `Комментарий`;
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

                        th_column_date_of_last_shipment.innerText = `Дата последней операции`;
                        th_column_days.innerText = `Дни`;
                        th_column_balance.innerText = `Баланс`;
                        th_column_name.innerText = `Имя`;
                        break;
                }

                sortedRows.push(totalRow[0])
                DebtorsTable.tBodies[0].append(...sortedRows);
            }
        </script>

        <script>
            let currentPage = 1;
            let type = 'all';
            $(".fetchMutualSettlements").on("click", function() {
                type = $(this).data('type');
                $(".fetchMutualSettlements").removeClass("bg-blue-600").addClass("bg-blue-300");
                $(this).addClass("bg-blue-600");

                fetchMutualSettlements(1, type);
            });


            function fetchMutualSettlements(page, type) {
                $.ajax({
                    url: '{{ route('api.get.mutual_settlements') }}',
                    type: 'GET',
                    data: { type: type, page: page },
                    success: function(response) {
                        const totalBalance = response.data.reduce((sum, item) => sum + (parseFloat(item.balance) || 0), 0);
                        const totalDays = response.data.reduce((sum, item) => sum + (parseFloat(item.days_since_latest) || 0), 0);


                        renderTable(response.data, totalBalance, totalDays);
                        renderPagination(response);

                    },
                    error: function(error) {
                        console.log('Ошибка:', error);
                    }
                });
            }


            function renderTable(data, totalBalance, totalDays) {
                const tableBody = $('#DebtorsTable tbody');
                tableBody.empty();

                data.forEach(item => {
                    const row = `
                        <tr class="bg-green-100 border">
                            <td class="break-all border max-w-60 truncate px-2 py-3 text-left"><a href='https://online.moysklad.ru/app/#Company/edit?id=${item.ms_id}' target='__blank'>${item.name}</a></td>
                            <td class="break-all border max-w-60 truncate px-2 py-3 text-right">${item.latest_created_at !== "0000-00-00" ? item.latest_created_at : ''}</td>
                            <td class="break-all border max-w-60 truncate px-2 py-3 text-right">${item.days_since_latest !== null ? item.days_since_latest : 0}</td>
                            <td class="break-all border max-w-60 truncate px-2 py-3 text-right">${item.balance !== null ? Number(item.balance).toFixed(1).replace(/\B(?=(\d{3})+(?!\d))/g, " ") : ''}</td>
                            <td class="break-all border max-w-60 truncate px-2 py-3">${item.description || '-'}</td>
                        </tr>
                    `;
                    tableBody.append(row);
                });

                tableBody.append(`
                    <tr class="border-b-2 bg-gray-100">
                        <td class="break-all border text-right overflow-auto px-6 py-3">ВСЕГО:</td>
                        <td class="break-all border max-w-60 overflow-hidden px-2 py-3 text-right"></td>
                        <td class="break-all border max-w-60 overflow-hidden px-2 py-3 text-right">${totalDays.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")}</td>
                        <td class="break-all border max-w-60 overflow-hidden px-2 py-3 text-right">${totalBalance.toFixed(1).replace(/\B(?=(\d{3})+(?!\d))/g, " ")}</td>
                        <td class="break-all border max-w-60 overflow-hidden px-2 py-3 text-right"></td>
                    </tr>
                `);
            }

            function renderPagination(response) {
                const paginationContainer = $('#pagination');
                paginationContainer.html(response.pagination);

                paginationContainer.find('a').on('click', function(e) {
                    e.preventDefault();
                    const url = $(this).attr('href');
                    const page = new URL(url).searchParams.get('page');
                    const type = $(".fetchMutualSettlements.bg-blue-600").data('type');

                    fetchMutualSettlements(page, type);
                });
            }

            $(document).ready(function(){
                fetchMutualSettlements(1, type);
            })

        </script>

    </div>


</x-app-layout>
