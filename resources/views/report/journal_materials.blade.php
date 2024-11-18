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
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                @foreach ($resColumns as $key => $column)
                                    @if($key == 'material')
                                        <th scope="col" class="px-2 py-3">
                                            @foreach ($filter['values'] as $value)
                                                @if($value['value'] == $filter['checked_value'] && $value['value'] == 'index')
                                                    {{ $column }}
                                                @elseif($value['value'] == $filter['checked_value'] && $value['value'] != 'material')
                                                    {{ $value['name'] }}
                                                @endif
                                            @endforeach
                                        </th>
                                    @else
                                        <th scope="col" class="px-2 py-3">
                                            {{ $column }}
                                        </th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $residualDay = 0;
                            @endphp

                            @foreach ($report as $day => $counts)
                                <tr class="border-b-2">

                                    @foreach ($resColumns as $column => $title)
                                        <td class="break-all max-w-96 truncate px-2 py-3">
                                            @switch($column)

                                                @case('date')
                                                    {{ $day }}
                                                @break
                                                @case('material')
                                                    <a target="_blank">
                                                        {{ $residual }}
                                                    </a>
                                                @break
                                                @case('incoming')
                                                    <a target="_blank">
                                                        {{ $counts['incoming'] }}
                                                    </a>
                                                @break
                                                @case('outgoing')
                                                    <a target="_blank">
                                                        {{ $counts['outgoing'] }}
                                                    </a>
                                                @break
                                                @case('residual')
                                                    <a target="_blank">
                                                        {{ $residual = $residual + ($counts['incoming'] - $counts['outgoing']) }}
                                                    </a>
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
                                            @case('amo_orders')
                                            {{ $totals['order_amos'] }}
                                            @break
                                            @case('contacts_amo')
                                            {{ $totals['contact_amos'] }}
                                            @break
                                            @case('success_transactions')
                                            {{ $totals['success_transactions'] }}
                                            @break
                                            @case('closed_transactions')
                                            {{ $totals['closed_transactions'] }}
                                            @break
                                            @case('count_shipments')
                                            {{ $totals['shipments'] }}
                                            @break
                                            @case('sum_shipments')
                                            {{ number_format((int) $totals['sum_shipments'], 0, ',', ' ') }}
                                            @break
                                            @case('contacts_ms')
                                            {{ $totals['contacts'] }}
                                            @break
                                            @case('pieces_cycle')
                                            {{ number_format((int) $totals['cycles'], 0, ',', ' ') }}
                                            @break
                                            @case('incoming_calls')
                                            {{ number_format((int) $totals['incoming_calls'], 0, ',', ' ') }}
                                            @break
                                            @case('outgoing_calls')
                                            {{ number_format((int) $totals['outgoing_calls'], 0, ',', ' ') }}
                                            @break
                                            @case('conversations')
                                            {{ number_format((int) $totals['talk_amos'], 0, ',', ' ') }}
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
</x-app-layout>
