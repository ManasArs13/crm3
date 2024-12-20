
<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif
    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </x-slot>


    <div class="w-11/12 mx-auto py-8 max-w-10xl">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <div class="flex flex-row gap-1">
                        <div>
                            @if (url()->current() == route('techcharts.index'))
                                <a href="{{ route('techcharts.index') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Общая таблица</a>
                            @else
                                <a href="{{ route('techcharts.index') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Общая таблица</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('techcharts.products'))
                                <a href="{{ route('techcharts.products') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (продукты)</a>
                            @else
                                <a href="{{ route('techcharts.products') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (продукты)</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('techcharts.materials'))
                                <a href="{{ route('techcharts.materials') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (материалы)</a>
                            @else
                                <a href="{{ route('techcharts.materials') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (материалы)</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap" id="chartsTable">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_id" onclick="orderBy('id')">
                                {{__("column.id")}}
                            </th>
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_name" onclick="orderBy('name')">
                                {{__("column.name")}}
                            </th>
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_price" onclick="orderBy('price')">
                                {{__("column.price")}}
                            </th>
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_description" onclick="orderBy('description')">
                                {{__("column.description")}}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSum = 0;
                        @endphp

                        @foreach($techcharts as $techchart)
                        @php
                            $totalSum += $techchart->cost;
                        @endphp
                        <tr class="border-b-2">
                            <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                <a href="{{ route('techcharts.show', ['techchart' => $techchart->id]) }}" class="text-blue-500 hover:text-blue-600">
                                    {{ $techchart->id}}
                                </a>
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                {{ $techchart->name}}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                {{ number_format((int) $techchart->cost, 0, ',', ' ') }}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                {{ $techchart->description}}
                            </td>
                        </tr>
                        @endforeach


                        <tr class="border-b-2 bg-neutral-200">
                            <td></td>
                            <td></td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                {{ number_format((int) $totalSum, 0, ',', ' ') }}
                            </td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <script type="text/javascript">
        function orderBy(column) {

            let sortedRows = Array.from(chartsTable.rows).slice(1, -1);
            let totalRow = Array.from(chartsTable.rows).slice(chartsTable.rows.length - 1);

            let th_id = document.getElementById('th_id');
            let th_name = document.getElementById('th_name');
            let th_price = document.getElementById('th_price');
            let th_description = document.getElementById('th_description');

            switch (column) {

                case 'id':
                    if (th_id.innerText == `№ ↓`) {
                        th_id.innerText = `№ ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[0].innerText) > parseInt(rowB.cells[0]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_id.innerText = `№ ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[0].innerText) < parseInt(rowB.cells[0]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_name.innerText = 'Имя';
                    th_price.innerText = 'Цена';
                    th_description.innerText = 'Комментарий';
                    break;

                case 'name':
                    if (th_name.innerText == `Имя ↓`) {
                        th_name.innerText = `Имя ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[1].innerText) > parseInt(rowB.cells[1]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_name.innerText = `Имя ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[1].innerText) < parseInt(rowB.cells[1]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_id.innerText = '№';
                    th_price.innerText = 'Цена';
                    th_description.innerText = 'Комментарий';
                    break;

                case 'price':
                    if (th_price.innerText == `Цена ↓`) {
                        th_price.innerText = `Цена ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) > parseInt(rowB.cells[2]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_price.innerText = `Цена ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) < parseInt(rowB.cells[2]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_id.innerText = '№';
                    th_name.innerText = 'Имя';
                    th_description.innerText = 'Комментарий';
                    break;

                case 'description':
                    if (th_description.innerText == `Комментарий ↓`) {
                        th_description.innerText = `Комментарий ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) > parseInt(rowB.cells[3]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_description.innerText = `Комментарий ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) < parseInt(rowB.cells[3]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_id.innerText = '№';
                    th_name.innerText = 'Имя';
                    th_price.innerText = 'Цена';
                    break;


            }

            sortedRows.push(totalRow[0])
            chartsTable.tBodies[0].append(...sortedRows);
        }
    </script>
</x-app-layout>
