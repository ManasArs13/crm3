<x-app-layout>

    @if (isset($entityName) && $entityName != '')
        <x-slot:title>{{ $entityName }}</x-slot>
            @endif

            <div class="w-11/12 mx-auto py-8 max-w-10xl">

                @if (isset($entityName) && $entityName != '')
                    <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
                @endif

                <div
                    class="block rounded-lg text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

                    {{-- header card --}}
                    <div class="border-b-2 border-neutral-100 bg-white rounded-t-lg">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex gap-2"></div>
                            <div class="flex px-3 text-center font-bold">
                                <a href="{{ route('manager.managerTwo', ['date' => $datePrev]) }}" class="mx-2 text-lg">&#9668;</a>
                                <p class="mx-2 text-lg">{{ $dateRus }}</p>
                                <a href="{{ route('manager.managerTwo', ['date' => $dateNext]) }}" class="mx-2 text-lg">&#9658;</a>
                            </div>
                        </div>
                    </div>

                    {{-- body card --}}
                    @foreach($managers as $managerKey => $manager)
                        <div class="flex flex-col w-100 mb-10 bg-white overflow-x-auto {{ $managerKey === 0 ? 'rounded-b-lg' : 'rounded-lg'  }}">
                            <table class="text-left text-md text-nowrap">
                                <thead>
                                <tr class="bg-neutral-200 font-semibold">
                                    <th class="px-2 py-3">{{ $manager }}</th>
                                    @foreach($report as $day => $counts)
                                        <th class="px-2 py-3 text-center">{{ Carbon\Carbon::parse($day)->day }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($resColumns as $key => $column)
                                        <tr class="border-b-2">
                                            <td class="break-all max-w-96 truncate px-2 py-3">{{ $column }}</td>
                                            @foreach($report as $day => $counts)
                                                @switch($key)
                                                    @case('incoming_calls')
                                                        <td class="px-2 py-3 border-l-2 text-center">{{ $counts[$managerKey]['incoming_calls'] ?? 0 }}</td>
                                                    @break
                                                    @case('outgoing_calls')
                                                        <td class="px-2 py-3 border-l-2 text-center">{{ $counts[$managerKey]['outgoing_calls'] ?? 0 }}</td>
                                                    @break
                                                    @case('conversations')
                                                        <td class="px-2 py-3 border-l-2 text-center">{{ $counts[$managerKey]['talk_amos'] ?? 0 }}</td>
                                                    @break
                                                    @case('created_transactions')
                                                        <td class="px-2 py-3 border-l-2 text-center">{{ $counts[$managerKey]['orders_created'] ?? 0 }}</td>
                                                    @break
                                                    @case('closed_transactions')
                                                        <td class="px-2 py-3 border-l-2 text-center">{{ $counts[$managerKey]['orders_closed'] ?? 0 }}</td>
                                                    @break
                                                    @case('success_transactions')
                                                        <td class="px-2 py-3 border-l-2 text-center">{{ $counts[$managerKey]['orders_success'] ?? 0 }}</td>
                                                    @break
                                                    @case('conversion')
                                                        <td class="px-2 py-3 border-l-2 text-center">{{ $counts[$managerKey]['conversion'] ?? 0 }}</td>
                                                    @break
                                                    @case('shipments')
                                                        <td class="px-2 py-3 border-l-2 text-center">{{ number_format((int) ($counts[$managerKey]['shipments'] ?? 0), 0, ',', ' ') }}</td>
                                                    @break

                                                @endswitch
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
</x-app-layout>
