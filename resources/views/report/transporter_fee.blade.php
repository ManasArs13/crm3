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
                                        @case('link')
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
                                        @case('link')
                                            <td class="break-all max-w-60 truncate px-2 py-3 text-left">
                                                @if(isset($entityItem->contact->id))
                                                    <span class="link" data-link="{{ route('carrier.index', ['id' => $entityItem->contact->id,'hash' => hash('sha256', $entityItem->contact->id . 'b8b89f347cdf8fb9915d4452b43101')]) }}">
                                                        <svg class="w-4 h-4 cursor-pointer fill-gray-500" width="428" height="428" viewBox="0 0 428 428" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M182.397 0.699995C164.397 2.89999 147.297 11 134.197 23.4C124.397 32.6 117.097 43.2 112.797 54.4C106.697 70.2 106.797 68.2 107.097 175.1L107.397 272.5L109.697 279.8C118.597 308.6 138.597 329.2 166.897 338.7L175.397 341.5H266.897H358.397L365.897 339.2C395.897 329.7 416.697 308.3 425.197 278.1C427.297 270.7 427.297 268.5 427.697 196.8L427.997 123.2L395.197 122.7C358.997 122.3 356.497 121.9 343.997 115.5C335.197 111 332.497 109.1 326.097 102.8C319.797 96.7 315.897 91.3 311.897 83.2C305.997 71.3 305.597 68.4 305.197 32.7L304.697 -5.08875e-06L245.097 0.0999949C212.297 0.199995 184.097 0.499995 182.397 0.699995Z" />
                                                            <path d="M336.097 35.7C336.597 65.6 336.897 67.5 343.697 76.5C345.797 79.2 349.897 83.2 352.897 85.2C361.797 91.3 366.897 92.1 395.497 91.8L420.397 91.5L379.397 50.2C356.897 27.6 337.797 9.00002 337.097 9.00002C335.997 9.00002 335.797 13.9 336.097 35.7Z" />
                                                            <path d="M67.2969 87.5C38.7969 93.7 14.2969 115.5 4.89694 143.2C-0.10306 157.6 -0.20306 161.4 0.0969402 262C0.39694 355.4 0.39694 355.5 2.69694 363.5C11.3969 394.6 33.3969 416.5 64.3969 425.2C72.2969 427.4 72.9969 427.4 155.397 427.8C244.897 428.1 247.897 428 262.997 423C275.997 418.7 284.997 412.9 295.897 402C303.597 394.3 306.297 390.7 310.197 383.2L314.997 374H255.697C191.297 374 178.497 373.4 163.597 369.6C152.597 366.8 136.397 359.5 126.797 353C106.797 339.3 89.7969 316.9 81.9969 293.4C75.8969 275.2 75.8969 275.6 75.8969 176.4C75.8969 104.7 75.5969 86 74.6969 86.1C73.9969 86.1 70.6969 86.8 67.2969 87.5Z" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </td>
                                        @break

                                        @case('contact_name')
                                            <td class="break-all max-w-60 truncate px-2 py-3 text-left">
                                                {{ $entityItem->contact->name ?? '-' }}
                                            </td>
                                        @break

                                        @case('debt')
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                {{ $entityItem->contact->balance ?? 0 }}</td>
                                        @break

                                        @case('current month')
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                {{ $entityItem->delivery_fee ?? 0 }}
                                            </td>
                                        @break

                                        @case('total')
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                {{ $entityItem->delivery_fee + $entityItem->contact->balance ?? 0 }}</td>
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
                                            {{ $total_fee + $total_balance ?? 0 }}</td>
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

    <style>
        .link::after {
            content: "Ссылка скопирована!";
            display: none;
            margin-left: 10px;
            color: green;
            font-size: 0.9em;
        }

        .link.copied::after {
            display: inline;
        }
    </style>

    <script type="text/javascript">
        document.querySelectorAll('.link').forEach(function(linkElement) {
            linkElement.addEventListener('click', function() {
                var link = linkElement.getAttribute('data-link');

                var tempTextarea = document.createElement('textarea');
                tempTextarea.value = link;

                document.body.appendChild(tempTextarea);

                tempTextarea.select();
                tempTextarea.setSelectionRange(0, 99999);

                try {
                    document.execCommand('copy');

                    var icon = linkElement.querySelector('svg');

                    icon.classList.remove('fill-gray-500');
                    icon.classList.add('fill-green-500');

                    setTimeout(function() {
                        icon.classList.remove('fill-green-500');
                        icon.classList.add('fill-gray-500');
                    }, 1000);

                    console.log('Ссылка скопирована:', link);
                } catch (err) {
                    console.error('Ошибка при копировании:', err);
                }

                document.body.removeChild(tempTextarea);
            });
        });
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
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) > parseInt(rowB.cells[2].innerText) ? 1 : -
                            1);
                    } else {
                        th_debt.innerText = `Долг (сейчас) ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) < parseInt(rowB.cells[2].innerText) ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик'
                    th_current_month.innerText = 'Текущий месяц';
                    th_total.innerText = 'Итого';
                    break;

                case 'current month':
                    if (th_current_month.innerText == `Текущий месяц ↓`) {
                        th_current_month.innerText = `Текущий месяц ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) > parseInt(rowB.cells[3].innerText) ? 1 : -
                            1);
                    } else {
                        th_current_month.innerText = `Текущий месяц ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) < parseInt(rowB.cells[3].innerText) ? 1 : -
                            1);
                    }

                    th_contact_name.innerText = 'Перевозчик'
                    th_debt.innerText = 'Долг (сейчас)';
                    th_total.innerText = 'Итого';
                    break;

                case 'total':
                    if (th_total.innerText == `Итого ↓`) {
                        th_total.innerText = `Итого ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[4].innerText) > parseInt(rowB.cells[4].innerText) ? 1 : -
                            1);
                    } else {
                        th_total.innerText = `Итого ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[4].innerText) < parseInt(rowB.cells[4].innerText) ? 1 : -
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
