<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entityName }}
        </x-slot>
    @endif

    <div class="w-11/12 mx-auto py-8">

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

        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header card --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <div class="flex gap-2">
                        <div class="">
                            @if (request()->routeIs('manager.index'))
                                <a href="{{ route('manager.index', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @else
                                <a href="{{ route('manager.index', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('manager.index.block'))
                                <a href="{{ route('manager.index.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @else
                                <a href="{{ route('manager.index.block', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                            @endif
                        </div>
                        <div>
                            @if (request()->routeIs('manager.index.concrete'))
                                <a href="{{ route('manager.index.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @else
                                <a href="{{ route('manager.index.concrete', ['date' => $date]) }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                            @endif
                        </div>
                    </div>

                    <div class="flex px-3 text-center font-bold">
                        @if (request()->routeIs('manager.index.block'))
                            <a href="{{ route('manager.index.block', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('manager.index.block', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @elseif(request()->routeIs('manager.index.concrete'))
                            <a href="{{ route('manager.index.concrete', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('manager.index.concrete', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @else
                            <a href="{{ route('manager.index', ['date' => $datePrev]) }}"
                                class="mx-2 text-lg">&#9668;</a>
                            <p class="mx-2 text-lg">{{ $dateRus }}</p>
                            <a href="{{ route('manager.index', ['date' => $dateNext]) }}"
                                class="mx-2 text-lg">&#9658;</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            @foreach ($resColumns as $key => $column)
                                <th scope="col" class="px-2 py-4"
                                    @if ($column == 'Имя') style="text-align:left" @else style="text-align:right" @endif>
                                    {{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $TotalCountContacts = 0;
                            $TotalSumShipments = 0;

                            $TotalCountContactsNew = 0;
                            $TotalSumShipmentsNew = 0;
                        @endphp

                        @foreach ($entityItems as $entityItem)
                            @php
                                $TotalCountContacts += $entityItem->all_contacts;
                                $TotalCountContactsNew += $entityItem->new_contacts;

                                $TotalSumShipments += $entityItem->contacts->sum(function ($contact) {
                                    return $contact->shipments->sum('suma');
                                });
                                $TotalSumShipmentsNew += $entityItem->contacts->sum(function ($contact) use (
                                    $date,
                                    $dateY,
                                ) {
                                    if (substr($contact->created_at, 3, -6) == $date . '-' . $dateY) {
                                        return $contact->shipments->sum('suma');
                                    } else {
                                        return 0;
                                    }
                                });
                            @endphp
                        @endforeach

                        @foreach ($entityItems as $entityItem)
                            @php

                                $sum_shipments = $entityItem->contacts->sum(function ($contact) {
                                    return $contact->shipments->sum('suma');
                                });
                                $sum_shipments_new = $entityItem->contacts->sum(function ($contact) use (
                                    $date,
                                    $dateY,
                                ) {
                                    if (substr($contact->created_at, 3, -6) == $date . '-' . $dateY) {
                                        return $contact->shipments->sum('suma');
                                    } else {
                                        return 0;
                                    }
                                });
                            @endphp

                            <tr class="border-b-2">

                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-96 overflow-auto px-2 py-4"
                                        @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                        @switch($column)
                                            @case('count_contacts')
                                                {{ $entityItem->all_contacts }}
                                            @break

                                            @case('percent_contacts')
                                                @if ($entityItem->all_contacts && $entityItem->all_contacts !== 0 && $TotalCountContacts && $TotalCountContacts !== 0)
                                                    {{ round(100 / ($TotalCountContacts / +$entityItem->all_contacts)) }} %
                                                @else
                                                    0%
                                                @endif
                                            @break

                                            @case('sum_shipments')
                                                {{ $sum_shipments }}
                                            @break

                                            @case('percent_shipments')
                                                @if ($sum_shipments && $sum_shipments !== 0 && $TotalSumShipments && $TotalSumShipments !== 0)
                                                    {{ round(100 / ($TotalSumShipments / +$sum_shipments)) }} %
                                                @else
                                                    0%
                                                @endif
                                            @break

                                            @case('count_contacts_new')
                                                {{ $entityItem->new_contacts }}
                                            @break

                                            @case('percent_contacts_new')
                                                @if (
                                                    $entityItem->new_contacts &&
                                                        $entityItem->new_contacts !== 0 &&
                                                        $TotalCountContactsNew &&
                                                        $TotalCountContactsNew !== 0)
                                                    {{ round(100 / ($TotalCountContactsNew / +$entityItem->new_contacts)) }} %
                                                @else
                                                    0%
                                                @endif
                                            @break

                                            @case('sum_shipments_new')
                                                {{ $sum_shipments_new }}
                                            @break

                                            @case('percent_shipments_new')
                                                @if ($sum_shipments_new && $sum_shipments_new !== 0 && $TotalSumShipmentsNew && $TotalSumShipmentsNew !== 0)
                                                    {{ round(100 / ($TotalSumShipmentsNew / +$sum_shipments_new)) }} %
                                                @else
                                                    0%
                                                @endif
                                            @break

                                            @default
                                                {{ $entityItem->$column }}
                                        @endswitch

                                    </td>
                                @endforeach

                            </tr>
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">

                            @foreach ($resColumns as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-4"
                                    @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif>

                                    @switch($column)
                                        @case('name')
                                            Всего:
                                        @break

                                        @case('count_contacts')
                                            {{ $TotalCountContacts }}
                                        @break

                                        @case('percent_contacts')
                                            {{ $TotalCountContacts ? '100%' : '0%' }}
                                        @break

                                        @case('sum_shipments')
                                            {{ $TotalSumShipments }}
                                        @break

                                        @case('percent_shipments')
                                            {{ $TotalSumShipments ? '100%' : '0%' }}
                                        @break

                                        @case('count_contacts_new')
                                            {{ $TotalCountContactsNew }}
                                        @break

                                        @case('percent_contacts_new')
                                            {{ $TotalCountContactsNew ? '100%' : '0' }}
                                        @break

                                        @case('sum_shipments_new')
                                            {{ $TotalSumShipmentsNew }}
                                        @break

                                        @case('percent_shipments_new')
                                            {{ $TotalSumShipmentsNew ? '100%' : '0%' }}
                                        @break

                                        @default
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
