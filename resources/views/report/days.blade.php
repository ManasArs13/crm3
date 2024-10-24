<x-app-layout>

    @if (isset($entityName) && $entityName != '')
        <x-slot:title>{{ $entityName }}</x-slot>
    @endif

            <div class="w-11/12 mx-auto py-8 max-w-10xl">

                @if (isset($entityName) && $entityName != '')
                    <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
                @endif

                <div
                    class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

                    {{-- header card --}}
                    <div class="border-b-2 border-neutral-100">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex gap-2"></div>
                            <div class="flex px-3 text-center font-bold">
                                <a href="{{ route('report.days', ['date' => $datePrev]) }}" class="mx-2 text-lg">&#9668;</a>
                                <p class="mx-2 text-lg">{{ $dateRus }}</p>
                                <a href="{{ route('report.days', ['date' => $dateNext]) }}" class="mx-2 text-lg">&#9658;</a>
                            </div>
                        </div>
                    </div>

                    {{-- body card --}}
                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                @foreach ($resColumns as $key => $column)
                                    <th scope="col" class="px-2 py-3">
                                        {{ $column }}
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($report as $day => $counts)
                                <tr class="border-b-2">

                                    @foreach ($resColumns as $column => $title)
                                        <td class="break-all max-w-96 truncate px-2 py-3">
                                            @switch($column)

                                                @case('date')
                                                    {{ $day }}
                                                @break
                                                @case('amo_orders')
                                                    <a target="_blank" href="{{ route('amo-order.index', ['filters[created_at][min]' => $day, 'filters[created_at][max]' => $day]) }}">
                                                        {{ $counts['order_amos'] }}
                                                    </a>
                                                @break
                                                @case('contacts_amo')
                                                    <a target="_blank" href="{{ route('contactAmo.index', ['filters[created_at][min]' => $day, 'filters[created_at][max]' => $day]) }}">
                                                        {{ $counts['contact_amos'] }}
                                                    </a>
                                                @break
                                                @case('success_transactions')
                                                    {{ $counts['success_transactions'] }}
                                                @break
                                                @case('closed_transactions')
                                                    {{ $counts['closed_transactions'] }}
                                                @break
                                                @case('count_shipments')
                                                    <a target="_blank" href="{{ route('shipment.index', ['filters[created_at][min]' => $day, 'filters[created_at][max]' => $day]) }}">
                                                        {{ $counts['shipments'] }}
                                                    </a>
                                                @break
                                                @case('sum_shipments')
                                                    <a target="_blank" href="{{ route('shipment.index', ['filters[created_at][min]' => $day, 'filters[created_at][max]' => $day]) }}">
                                                        {{ number_format((int) $counts['sum_shipments'], 0, ',', ' ') }}
                                                    </a>
                                                @break
                                                @case('contacts_ms')
                                                    <a target="_blank" href="{{ route('contact.index', ['filters[created_at][min]' => $day, 'filters[created_at][max]' => $day]) }}">
                                                        {{ $counts['contacts'] }}
                                                    </a>
                                                @break
                                                @case('pieces_cycle')
                                                    {{ number_format((int) $counts['cycles'], 0, ',', ' ') }}
                                                @break
                                                @case('incoming_calls')
                                                    <a target="_blank" href="{{ route('calls', ['filters[created_at][min]' => $day, 'filters[created_at][max]' => $day]) }}">
                                                        {{ $counts['incoming_calls'] }}
                                                    </a>
                                                @break
                                                @case('outgoing_calls')
                                                    <a target="_blank" href="{{ route('calls', ['filters[created_at][min]' => $day, 'filters[created_at][max]' => $day]) }}">
                                                        {{ $counts['outgoing_calls'] }}
                                                    </a>
                                                @break
                                                @case('conversations')
                                                    <a target="_blank" href="{{ route('conversations', ['filters[created_at][min]' => $day, 'filters[created_at][max]' => $day]) }}">
                                                        {{ $counts['talk_amos'] }}
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
</x-app-layout>
