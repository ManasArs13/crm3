<x-app-layout>

    @if (isset($entityName) && $entityName != '')
        <x-slot:title>{{ $entityName }}</x-slot>
    @endif

            <x-slot:head>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
            </x-slot>

            <div class="w-11/12 mx-auto py-8 max-w-10xl">

                @if (isset($entityName) && $entityName != '')
                    <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
                @endif

                <div
                    class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

                    {{-- header card --}}
                    <div class="border-b-2 border-neutral-100">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex gap-2">
                                @foreach ($filters as $filter)
                                    @if ($filter['type'] == 'select')
                                        <div class="flex flex-row gap-1 w-100">
                                            <div class="basis-4/5">
                                                <select
                                                    class="select-default border border-solid border-neutral-300 rounded w-full py-2"
                                                    name="filters[{{ $filter['name'] }}]"
                                                    data-offset="false">
                                                    @foreach ($filter['values'] as $value)
                                                        <option
                                                            @if ($value['value'] == $filter['checked_value']) selected @endif
                                                        value="{{ $value['value'] }}">
                                                            {{ $value['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="flex px-3 text-center font-bold">
                                <a href="{{ route('report.material_manager', array_merge(request()->query(), ['date' => $datePrev])) }}" class="mx-2 text-lg">&#9668;</a>
                                <p class="mx-2 text-lg">{{ $dateRus }}</p>
                                <a href="{{ route('report.material_manager', array_merge(request()->query(), ['date' => $dateNext])) }}" class="mx-2 text-lg">&#9658;</a>
                            </div>
                        </div>
                    </div>

                    {{-- body card --}}
                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap" id="journalsTable">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                @foreach ($resColumns as $key => $column)
                                    @if($key == 'material')
                                        <th scope="col" class="px-2 py-3 hover:cursor-pointer" id="th_{{ $key }}" onclick="orderBy(`{{ $key }}`)">
                                            @foreach ($filter['values'] as $value)
                                                @if($value['value'] == $filter['checked_value'] && $value['value'] == 'index')
                                                    {{ $column }}
                                                @elseif($value['value'] == $filter['checked_value'] && $value['value'] != 'material')
                                                    {{ $value['name'] }}
                                                @endif
                                            @endforeach
                                        </th>
                                    @else
                                        <th scope="col" class="px-2 py-3 hover:cursor-pointer" id="th_{{ $key }}" onclick="orderBy(`{{ $key }}`)">
                                            {{ $column }}
                                        </th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $incomingTotal = 0;
                                $outgoingTotal = 0;
                            @endphp

                            @foreach ($report as $day => $counts)
                                <tr class="border-b-2">

                                    @foreach ($resColumns as $column => $title)
                                        @php
                                            $incomingTotal += $counts['incoming'];
                                            $outgoingTotal += $counts['outgoing'];
                                        @endphp
                                        <td class="break-all max-w-96 truncate px-2 py-3">
                                            @switch($column)

                                                @case('date')
                                                    {{ $day }}
                                                @break
                                                @case('material')
                                                    {{ number_format((int) $residual, 1, '.', ' ') }}
                                                @break
                                                @case('incoming')
                                                    {{ number_format((int) $counts['incoming'], 1, '.', ' ') }}
                                                @break
                                                @case('outgoing')
                                                    {{ number_format((int) $counts['outgoing'], 1, '.', ' ') }}
                                                @break
                                                @case('residual')
                                                    {{ number_format((int) $residual = $residual + ($counts['incoming'] - $counts['outgoing']), 1, '.', ' ') }}
                                                @break

                                            @endswitch
                                        </td>
                                    @endforeach

                                </tr>
                            @endforeach
                            <tr class="bg-neutral-100">
                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-96 truncate px-2 py-3">
                                        @switch($column)
                                            @case('date')
                                                Итоги:
                                            @break
                                            @case('incoming')
                                                {{ number_format((int) $incomingTotal, 1, '.', ' ') }}
                                            @break
                                            @case('outgoing')
                                                {{ number_format((int) $outgoingTotal, 1, '.', ' ') }}
                                            @break

                                        @endswitch
                                    </td>
                                @endforeach
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

                <script>
                    $(document).ready(function () {
                        $('select[name^="filters"]').on('change', function () {
                            const url = new URL(window.location.href);
                            const params = new URLSearchParams(url.search);

                            const filterName = $(this).attr('name').match(/\[(.*)\]/)[1];
                            const filterValue = $(this).val();

                            params.set(`filters[${filterName}]`, filterValue);

                            window.location.href = `${url.pathname}?${params.toString()}`;
                        });
                    });
                </script>

                <script type="text/javascript">
                    function orderBy(column) {

                        let sortedRows = Array.from(journalsTable.rows).slice(1, -1);
                        let totalRow = Array.from(journalsTable.rows).slice(journalsTable.rows.length - 1);

                        let th_date = document.getElementById('th_date');
                        let th_material = document.getElementById('th_material');
                        let th_incoming = document.getElementById('th_incoming');
                        let th_outgoing = document.getElementById('th_outgoing');
                        let th_residual = document.getElementById('th_residual');

                        switch (column) {

                            case 'date':
                                if (th_date.innerText == `Дата ↓`) {
                                    th_date.innerText = `Дата ↑`
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[0].innerText) > parseInt(rowB.cells[0]
                                        .innerText) ? 1 : -
                                        1);
                                } else {
                                    th_date.innerText = `Дата ↓`;
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[0].innerText) < parseInt(rowB.cells[0]
                                        .innerText) ? 1 : -
                                        1);
                                }

                                th_material.innerText = 'Материал';
                                th_incoming.innerText = 'Приход';
                                th_outgoing.innerText = 'Расход';
                                th_residual.innerText = 'Остаток';
                                break;

                            case 'material':
                                if (th_material.innerText == `Материал ↓`) {
                                    th_material.innerText = `Материал ↑`
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[1].innerText) > parseInt(rowB.cells[1]
                                        .innerText) ? 1 : -
                                        1);
                                } else {
                                    th_material.innerText = `Материал ↓`;
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[1].innerText) < parseInt(rowB.cells[1]
                                        .innerText) ? 1 : -
                                        1);
                                }

                                th_date.innerText = 'Дата';
                                th_incoming.innerText = 'Приход';
                                th_outgoing.innerText = 'Расход';
                                th_residual.innerText = 'Остаток';
                                break;

                            case 'incoming':
                                if (th_incoming.innerText == `Приход ↓`) {
                                    th_incoming.innerText = `Приход ↑`
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) > parseInt(rowB.cells[2]
                                        .innerText) ? 1 : -
                                        1);
                                } else {
                                    th_incoming.innerText = `Приход ↓`;
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) < parseInt(rowB.cells[2]
                                        .innerText) ? 1 : -
                                        1);
                                }

                                th_date.innerText = 'Дата';
                                th_material.innerText = 'Материал';
                                th_outgoing.innerText = 'Расход';
                                th_residual.innerText = 'Остаток';
                                break;

                            case 'outgoing':
                                if (th_outgoing.innerText == `Расход ↓`) {
                                    th_outgoing.innerText = `Расход ↑`
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) > parseInt(rowB.cells[3]
                                        .innerText) ? 1 : -
                                        1);
                                } else {
                                    th_outgoing.innerText = `Расход ↓`;
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) < parseInt(rowB.cells[3]
                                        .innerText) ? 1 : -
                                        1);
                                }

                                th_date.innerText = 'Дата';
                                th_material.innerText = 'Материал';
                                th_incoming.innerText = 'Приход';
                                th_residual.innerText = 'Остаток';
                                break;

                            case 'residual':
                                if (th_residual.innerText == `Остаток ↓`) {
                                    th_residual.innerText = `Остаток ↑`
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[4].innerText) > parseInt(rowB.cells[4]
                                        .innerText) ? 1 : -
                                        1);
                                } else {
                                    th_residual.innerText = `Остаток ↓`;
                                    sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[4].innerText) < parseInt(rowB.cells[4]
                                        .innerText) ? 1 : -
                                        1);
                                }

                                th_date.innerText = 'Дата';
                                th_material.innerText = 'Материал';
                                th_incoming.innerText = 'Приход';
                                th_outgoing.innerText = 'Расход';
                                break;

                        }

                        sortedRows.push(totalRow[0])
                        journalsTable.tBodies[0].append(...sortedRows);
                    }
                </script>
</x-app-layout>
