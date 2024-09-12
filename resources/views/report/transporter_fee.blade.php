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
                <table class="text-left text-md text-nowrap" id="transportsTable">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">

                            <td class="break-all w-16 overflow-auto px-2 py-3">
                                №
                            </td>

                            @foreach ($resColumns as $key => $column)
                                <th scope="col" class="px-2 py-3  hover:cursor-pointer" id="th_{{ $key }}"
                                    @switch($key)
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
                            $total_balance = 0;
                            $total_fee = 0;
                        @endphp

                        @foreach ($entityItems as $entityItem)
                            @php
                                $total_balance += $entityItem->contact->balance ?? 0;
                                $total_fee += $entityItem->delivery_fee ?? 0;
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
                                            <td class="break-all max-w-60 overflow-hidden px-2 py-3 text-left">
                                                {{ $entityItem->contact->name ?? '-' }}
                                            </td>
                                        @break

                                        @case('debt')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                {{ $entityItem->contact->balance ?? 0 }}</td>
                                        @break

                                        @case('current month')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                {{ $entityItem->delivery_fee ?? 0 }}
                                            </td>
                                        @break

                                        @case('total')
                                            <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                                {{ $entityItem->delivery_fee - $entityItem->contact->balance ?? 0 }}</td>
                                        @break
                                    @endswitch
                                @endforeach

                            </tr>
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">

                            <td class="break-all text-right overflow-auto px-6 py-3">
                                ВСЕГО:
                            </td>

                            @foreach ($resColumns as $column => $title)
                                @switch($column)
                                    @case('debt')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_balance ?? 0 }}</td>
                                    @break

                                    @case('current month')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_fee ?? 0 }}
                                        </td>
                                    @break

                                    @case('total')
                                        <td class="break-all max-w-96 overflow-auto px-2 py-3 text-right">
                                            {{ $total_fee - $total_balance ?? 0 }}</td>
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
            let th_debt = document.getElementById('th_debt');
            let th_current_month = document.getElementById('th_current month');
            let th_total = document.getElementById('th_total');

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

                    th_debt.innerText = 'Долг (сейчас)';
                    th_current_month.innerText = 'Текущий месяц';
                    th_total.innerText = 'Итого';
                    break;

                case 'debt':
                    if (th_debt.innerText == `Долг (сейчас) ↓`) {
                        th_debt.innerText = `Долг (сейчас) ↑`
                        sortedRows.sort((rowA, rowB) => rowA.cells[2].innerText > rowB.cells[2].innerText ? 1 : -
                            1);
                    } else {
                        th_debt.innerText = `Долг (сейчас) ↓`;
                        sortedRows.sort((rowA, rowB) => rowA.cells[2].innerText < rowB.cells[2].innerText ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик'
                    th_current_month.innerText = 'Текущий месяц';
                    th_total.innerText = 'Итого';
                    break;

                case 'current month':
                    if (th_current_month.innerText == `Текущий месяц ↓`) {
                        th_current_month.innerText = `Текущий месяц ↑`
                        sortedRows.sort((rowA, rowB) => rowA.cells[3].innerText > rowB.cells[3].innerText ? 1 : -
                            1);
                    } else {
                        th_current_month.innerText = `Текущий месяц ↓`;
                        sortedRows.sort((rowA, rowB) => rowA.cells[3].innerText < rowB.cells[3].innerText ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик'
                    th_debt.innerText = 'Долг (сейчас)';
                    th_total.innerText = 'Итого';
                    break;

                case 'total':
                    if (th_total.innerText == `Итого ↓`) {
                        th_total.innerText = `Итого ↑`
                        sortedRows.sort((rowA, rowB) => rowA.cells[4].innerText > rowB.cells[4].innerText ? 1 : -
                            1);
                    } else {
                        th_total.innerText = `Итого ↓`;
                        sortedRows.sort((rowA, rowB) => rowA.cells[4].innerText < rowB.cells[4].innerText ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик'
                    th_debt.innerText = 'Долг (сейчас)';
                    th_current_month.innerText = 'Текущий месяц';
                    break;

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
