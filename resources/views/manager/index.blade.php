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


        {{-- Managers MS --}}

        @php
            $TotalCountContacts = 0;
            $TotalSumShipments = 0;

            $TotalCountContactsNew = 0;
            $TotalSumShipmentsNew = 0;

            $total_sum_euroblock = 0;
            $total_sum_managars = 0;
        @endphp

        @php
            $withOutManagerCount = $contacts
                ->filter(function ($contact) use ($date, $dateY) {
                    return substr($contact->created_at, 3, -6) == $date . '-' . $dateY;
                })
                ->count();

            $withOutManagerSumNew = $contacts->sum(function ($contact) use ($date, $dateY) {
                if (substr($contact->created_at, 3, -6) == $date . '-' . $dateY) {
                    return $contact->shipments->sum('suma');
                } else {
                    return 0;
                }
            });

            $TotalSumShipments += $contacts->sum('shipments_sum_suma');
            $TotalCountContacts += $contacts->count();
            $TotalCountContactsNew += $withOutManagerCount;
            $TotalSumShipmentsNew += $withOutManagerSumNew;
        @endphp

        @foreach ($entityItems as $entityItem)
            @php
                if ($entityItem->name == 'Общая Еврогрупп') {
                    $total_sum_euroblock += $entityItem->contacts->sum(function ($contact) {
                        return $contact->shipments->sum('suma');
                    });
                } else {
                    $total_sum_managars += $entityItem->contacts->sum(function ($contact) {
                        return $contact->shipments->sum('suma');
                    });
                }
            @endphp

            @php
                $TotalCountContacts += $entityItem->all_contacts;
                $TotalCountContactsNew += $entityItem->new_contacts;

                $TotalSumShipments += $entityItem->contacts->sum(function ($contact) {
                    return $contact->shipments->sum('suma');
                });
                $TotalSumShipmentsNew += $entityItem->contacts->sum(function ($contact) use ($date, $dateY) {
                    if (substr($contact->created_at, 3, -6) == $date . '-' . $dateY) {
                        return $contact->shipments->sum('suma');
                    } else {
                        return 0;
                    }
                });
            @endphp
        @endforeach

        {{-- Salary --}}
        <div class="flex flex-row mt-3 mb-10">
            @foreach ($entityItems as $entityItem)
                @php
                    $sum_shipments = $entityItem->contacts->sum(function ($contact) {
                        return $contact->shipments->sum('suma');
                    });
                @endphp

                <div
                    class="flex flex-col basis-2/12 rounded-lg bg-sky-100 border border-1 text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04) p-3 mr-5">
                    <div class="flex flex-row justify-between basis-full">
                        <div class="flex text-lg">
                            @switch($entityItem->name)
                                @case('Ярослав')
                                    Ярослав
                                @break

                                @case('Екатерина')
                                    Екатерина
                                @break

                                @case('Общая Еврогрупп')
                                    Еврогрупп
                                @break

                                @default
                                    {{ $entityItem->name }}
                            @endswitch
                        </div>
                        <div class="flex font-bold">
                            @switch($entityItem->name)
                                @case('Ярослав')
                                    {{ number_format(
                                        round($total_sum_euroblock * $percent + 2 * $percent * $sum_shipments, 2),
                                        0,
                                        '',
                                        ' ',
                                    ) }}
                                    р.
                                @break

                                @case('Екатерина')
                                    {{ number_format(
                                        round($total_sum_euroblock * $percent + 2 * $percent * $sum_shipments, 2),
                                        0,
                                        '',
                                        ' ',
                                    ) }}
                                    р.
                                @break
                            @endswitch
                        </div>
                    </div>

                    <div class="flex flex-row justify-between mt-2">
                        <div class="flex font-bold text-2xl">
                            {{ number_format(
                                $entityItem->contacts->sum(function ($contact) {
                                    return $contact->shipments->sum('suma');
                                }),
                                0,
                                '',
                                ' ',
                            ) }}
                        </div>
                        <div class="flex font-bold text-2xl">
                            @if ($sum_shipments && $sum_shipments !== 0 && $TotalSumShipments && $TotalSumShipments !== 0)
                                {{ round(100 / ($TotalSumShipments / +$sum_shipments)) }}
                                %
                            @else
                                0%
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

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
                                @if ($column == 'Имя')
                                    <th scope="col" class="px-2 py-3 text-left">МОЙ СКЛАД</th>
                                @else
                                    <th scope="col" class="px-2 py-3 text-right">{{ $column }}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

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
                                    <td class="break-all max-w-96 overflow-auto px-2 py-3"
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

                        {{-- Не выбрано --}}
                        <tr class="border-b-2">

                            @foreach ($resColumns as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-3"
                                    @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif
                                    @if (isset($entityItem->$column)) title="{{ $entityItem->$column }}" @endif>

                                    @switch($column)
                                        @case('count_contacts')
                                            @if ($contacts->count() > 0)
                                                <div class="bg-red-300 inline p-1 rounded-lg font-bold">
                                                    {{ $contacts->count() }}
                                                </div>
                                            @else
                                                <div class="inline p-1 rounded-lg">
                                                    {{ $contacts->count() }}
                                                </div>
                                            @endif
                                        @break

                                        @case('percent_contacts')
                                            @if ($contacts->count() && $contacts->count() !== 0 && $TotalCountContacts && $TotalCountContacts !== 0)
                                                {{ round(100 / ($TotalCountContacts / +$contacts->count())) }} %
                                            @else
                                                0%
                                            @endif
                                        @break

                                        @case('sum_shipments')
                                            {{ $contacts->sum('shipments_sum_suma') }}
                                        @break

                                        @case('percent_shipments')
                                            @if (
                                                $contacts->sum('shipments_sum_suma') &&
                                                    $contacts->sum('shipments_sum_suma') !== 0 &&
                                                    $TotalSumShipments &&
                                                    $TotalSumShipments !== 0)
                                                {{ round(100 / ($TotalSumShipments / +$contacts->sum('shipments_sum_suma'))) }}
                                                %
                                            @else
                                                0%
                                            @endif
                                        @break

                                        @case('count_contacts_new')
                                            {{ $withOutManagerCount }}
                                        @break

                                        @case('percent_contacts_new')
                                            @if ($withOutManagerCount && $withOutManagerCount !== 0 && $TotalCountContactsNew && $TotalCountContactsNew !== 0)
                                                {{ round(100 / ($TotalCountContactsNew / +$withOutManagerCount)) }} %
                                            @else
                                                0%
                                            @endif
                                        @break

                                        @case('sum_shipments_new')
                                            {{ $withOutManagerSumNew }}
                                        @break

                                        @case('percent_shipments_new')
                                            @if ($withOutManagerSumNew && $withOutManagerSumNew !== 0 && $TotalSumShipmentsNew && $TotalSumShipmentsNew !== 0)
                                                {{ round(100 / ($TotalSumShipmentsNew / +$withOutManagerSumNew)) }} %
                                            @else
                                                0%
                                            @endif
                                        @break

                                        @default
                                            {{ 'Не выбрано' }}
                                    @endswitch

                                </td>
                            @endforeach

                        </tr>

                        {{-- Всего --}}
                        <tr class="border-b-2 bg-gray-100">

                            @foreach ($resColumns as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-3"
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

        {{-- Managers Amo --}}
        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04) mt-10">

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            @foreach ($resColumnsAmo as $key => $column)
                                @if ($column == 'Имя')
                                    <th scope="col" class="px-2 py-3 text-left">АМО СРМ</th>
                                @else
                                    <th scope="col" class="px-2 py-3 text-right">{{ $column }}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $TotalOrders = $amo_orders['all_orders'];
                            $TotalSuccessOrders = $amo_orders['success_orders'];
                            $TotalNoSuccessOrders = $amo_orders['no_success_orders'];
                        @endphp

                        @foreach ($AmoManagers as $entityItem)
                            @php
                                $TotalOrders += $entityItem->all_orders;
                                $TotalSuccessOrders += $entityItem->success_orders;
                                $TotalNoSuccessOrders += $entityItem->no_success_orders;
                            @endphp
                        @endforeach

                        @foreach ($AmoManagers as $entityItem)
                            <tr class="border-b-2">

                                @foreach ($resColumnsAmo as $column => $title)
                                    <td class="break-all max-w-96 overflow-auto px-2 py-3"
                                        @if ($column == 'name') style="text-align:left"  @else style="text-align:right" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                        @switch($column)
                                            @case('all_orders')
                                                {{ $entityItem->all_orders }}
                                            @break

                                            @case('percent_all_orders')
                                                @if ($entityItem->all_orders && $entityItem->all_orders !== 0 && $TotalOrders && $TotalOrders !== 0)
                                                    {{ round(100 / ($TotalOrders / +$entityItem->all_orders)) }} %
                                                @else
                                                    0%
                                                @endif
                                            @break

                                            @case('success_orders')
                                                {{ $entityItem->success_orders }}
                                            @break

                                            @case('percent_success_orders')
                                                @if (
                                                    $entityItem->success_orders &&
                                                        $entityItem->success_orders !== 0 &&
                                                        $entityItem->all_orders &&
                                                        $entityItem->all_orders !== 0)
                                                    {{ round(100 / ($entityItem->all_orders / +$entityItem->success_orders)) }}
                                                    %
                                                @else
                                                    0%
                                                @endif
                                            @break

                                            @case('no_success_orders')
                                                {{ $entityItem->no_success_orders }}
                                            @break

                                            @case('percent_no_success_orders')
                                                @if (
                                                    $entityItem->no_success_orders &&
                                                        $entityItem->no_success_orders !== 0 &&
                                                        $entityItem->all_orders &&
                                                        $entityItem->all_orders !== 0)
                                                    {{ round(100 / ($entityItem->all_orders / +$entityItem->no_success_orders)) }}
                                                    %
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

                        {{-- Не выбрано --}}
                        <tr class="border-b-2">

                            @foreach ($resColumnsAmo as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-3"
                                    @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif>

                                    @switch($column)
                                        @case('name')
                                            {{ $amo_orders['name'] }}
                                        @break

                                        @case('all_orders')
                                            {{ $amo_orders['all_orders'] }}
                                        @break

                                        @case('success_orders')
                                            {{ $amo_orders['success_orders'] }}
                                        @break

                                        @case('no_success_orders')
                                            {{ $amo_orders['no_success_orders'] }}
                                        @break

                                        @case('percent_all_orders')
                                            @if ($amo_orders['all_orders'] && $amo_orders['all_orders'] !== 0 && $TotalOrders && $TotalOrders !== 0)
                                                {{ round(100 / ($TotalOrders / +$amo_orders['all_orders'])) }} %
                                            @else
                                                0%
                                            @endif
                                        @break

                                        @case('percent_success_orders')
                                            @if (
                                                $amo_orders['success_orders'] &&
                                                    $amo_orders['success_orders'] !== 0 &&
                                                    $amo_orders['all_orders'] &&
                                                    $amo_orders['all_orders'] !== 0)
                                                {{ round(100 / ($amo_orders['all_orders'] / +$amo_orders['success_orders'])) }}
                                                %
                                            @else
                                                0%
                                            @endif
                                        @break

                                        @case('percent_no_success_orders')
                                            @if (
                                                $amo_orders['no_success_orders'] &&
                                                    $amo_orders['no_success_orders'] !== 0 &&
                                                    $amo_orders['all_orders'] &&
                                                    $amo_orders['all_orders'] !== 0)
                                                {{ round(100 / ($amo_orders['all_orders'] / +$amo_orders['no_success_orders'])) }}
                                                %
                                            @else
                                                0%
                                            @endif
                                        @break
                                    @endswitch

                                </td>
                            @endforeach

                        </tr>


                        {{-- Всего --}}
                        <tr class="border-b-2 bg-gray-100">

                            @foreach ($resColumnsAmo as $column => $title)
                                <td class="break-all max-w-96 overflow-auto px-2 py-3"
                                    @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif>

                                    @switch($column)
                                        @case('name')
                                            Всего:
                                        @break

                                        @case('all_orders')
                                            {{ $TotalOrders }}
                                        @break

                                        @case('success_orders')
                                            {{ $TotalSuccessOrders }}
                                        @break

                                        @case('no_success_orders')
                                            {{ $TotalNoSuccessOrders }}
                                        @break

                                        @case('percent_all_orders')
                                            {{ $TotalOrders ? '100%' : '0%' }}
                                        @break

                                        @case('percent_success_orders')
                                            @if ($TotalOrders && $TotalOrders !== 0 && $TotalSuccessOrders && $TotalSuccessOrders !== 0)
                                                {{ round(100 / ($TotalOrders / +$TotalSuccessOrders)) }}
                                                %
                                            @else
                                                0%
                                            @endif
                                        @break

                                        @case('percent_no_success_orders')
                                            @if ($TotalOrders && $TotalOrders !== 0 && $TotalNoSuccessOrders && $TotalNoSuccessOrders !== 0)
                                                {{ round(100 / ($TotalOrders / +$TotalNoSuccessOrders)) }}
                                                %
                                            @else
                                                0%
                                            @endif
                                        @break
                                    @endswitch

                                </td>
                            @endforeach

                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Counterpaty --}}
        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04) mt-10">

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap" id="ContactsTable">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">

                            <td class="break-all w-16 overflow-auto px-2 py-3">
                                №
                            </td>

                            @foreach ($resColumnsContacts as $key => $column)
                                <th scope="col" class="px-2 py-3 hover:cursor-pointer" id="th_{{ $key }}"
                                    onclick="orderBy(`{{ $key }}`)"
                                    @if ($column == 'Имя') style="text-align:left" @else style="text-align:right" @endif>
                                    {{ $column }}
                                </th>
                            @endforeach

                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $totalOrdersCount = 0;
                            $totalOrdersSum = 0;
                            $totalShipmentsCount = 0;
                            $totalShipmentsSum = 0;
                        @endphp


                        @foreach ($contactsWithCount as $entityItem)
                            @php
                                $totalOrdersCount += $entityItem->orders->count();
                                $totalOrdersSum += $entityItem->orders->sum('sum');
                                $totalShipmentsCount += $entityItem->shipments->count();
                                $totalShipmentsSum += $entityItem->shipments->sum('suma');
                            @endphp
                        @endforeach

                        @foreach ($contactsWithCount as $entityItem)
                            <tr class="border-b-2">

                                <td class="break-all overflow-auto px-2 py-3 text-sm">
                                    {{ $loop->iteration }}
                                </td>

                                @foreach ($resColumnsContacts as $column => $title)
                                    <td class="break-all max-w-96 overflow-auto px-2 py-3"
                                        @if ($column == 'name') style="text-align:left" @else style="text-align:right" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                        @switch($column)
                                            @case('manager_name')
                                                {{ $entityItem->manager->name ?? '-' }}
                                            @break

                                            @case('name')
                                                <a href="https://online.moysklad.ru/app/#Company/edit?id={{ $entityItem->ms_id }}"
                                                    target="_blank" class="text-blue-700 hover:text-blue-500">
                                                    {{ $entityItem->$column }}
                                                </a>
                                            @break

                                            @case('count_orders')
                                                {{ $entityItem->orders->count() }}
                                            @break

                                            @case('sum_orders')
                                                {{ number_format($entityItem->orders->sum('sum') ? $entityItem->orders->sum('sum') : '0', 0, '.', ' ') }}
                                            @break

                                            @case('count_shipments')
                                                {{ $entityItem->shipments->count() }}
                                            @break

                                            @case('sum_shipments')
                                                {{ number_format($entityItem->shipments->sum('suma') ? $entityItem->shipments->sum('suma') : '0', 0, '.', ' ') }}
                                            @break

                                            @default
                                                {{ $entityItem->$column }}
                                        @endswitch

                                    </td>
                                @endforeach

                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <table class="text-left text-md text-nowrap">
                    <tbody>
                        <tr class="border-b-2 bg-gray-100">

                            <td class="break-all text-right overflow-auto px-2 py-3">
                                ВСЕГО:
                            </td>

                            @foreach ($resColumnsContacts as $column => $title)
                                @switch($column)
                                    @case('count_orders')
                                        <td class="break-all text-right max-w-96 overflow-auto px-2 py-3 w-[14rem]">
                                            {{ $totalOrdersCount }}
                                        </td>
                                        </td>
                                    @break

                                    @case('sum_orders')
                                        <td class="break-all text-right max-w-96 overflow-auto px-2 py-3 w-[12rem]">
                                            {{ number_format($totalOrdersSum, 0, '.', ' ') }}
                                        </td>
                                    @break

                                    @case('count_shipments')
                                        <td class="break-all text-right max-w-96 overflow-auto px-2 py-3 w-[15rem]">
                                            {{ $totalShipmentsCount }}
                                        </td>
                                    @break

                                    @case('sum_shipments')
                                        <td class="break-all text-right max-w-96 overflow-auto px-2 py-3 w-[12rem]">
                                            {{ number_format($totalShipmentsSum, 0, '.', ' ') }}
                                        </td>
                                    @break
                                @endswitch
                            @endforeach

                        </tr>

                    </tbody>
                </table>
            </div>

            <script type="text/javascript">
                function orderBy(column) {

                    let sortedRows = Array.from(ContactsTable.rows).slice(1)

                    let th_column_name = document.getElementById('th_name');
                    let th_column_manager = document.getElementById('th_manager_name');
                    let th_column_count_orders = document.getElementById('th_count_orders');
                    let th_column_sum_orders = document.getElementById('th_sum_orders');
                    let th_column_count_shipments = document.getElementById('th_count_shipments');
                    let th_column_sum_shipments = document.getElementById('th_sum_shipments');

                    switch (column) {
                        case 'name':
                            if (th_column_name.innerText == `Имя ↓`) {
                                th_column_name.innerText = `Имя ↑`
                                sortedRows.sort((rowA, rowB) => rowA.cells[0].innerText > rowB.cells[0].innerText ? 1 : -
                                    1);
                            } else {
                                th_column_name.innerText = `Имя ↓`;
                                sortedRows.sort((rowA, rowB) => rowA.cells[0].innerText < rowB.cells[0].innerText ? 1 : -
                                    1);
                            }

                            th_column_manager.innerText = `Менеджер`;
                            th_column_count_orders.innerText = `Количество заказов`;
                            th_column_sum_orders.innerText = `Сумма заказов`;
                            th_column_count_shipments.innerText = `Количество отгрузок`;
                            th_column_sum_shipments.innerText = `Сумма отгрузок`;
                            break;


                        case 'manager_name':
                            if (th_column_manager.innerText == `Менеджер ↓`) {
                                th_column_manager.innerText = `Менеджер ↑`
                                sortedRows.sort((rowA, rowB) => rowA.cells[1].innerText > rowB.cells[1].innerText ? 1 : -
                                    1);
                            } else {
                                th_column_manager.innerText = `Менеджер ↓`;
                                sortedRows.sort((rowA, rowB) => rowA.cells[1].innerText < rowB.cells[1].innerText ? 1 : -
                                    1);
                            }

                            th_column_name.innerText = `Имя`;
                            th_column_count_orders.innerText = `Количество заказов`;
                            th_column_sum_orders.innerText = `Сумма заказов`;
                            th_column_count_shipments.innerText = `Количество отгрузок`;
                            th_column_sum_shipments.innerText = `Сумма отгрузок`;
                            break;

                        case 'count_orders':
                            if (th_column_count_orders.innerText == `Количество заказов ↓`) {
                                th_column_count_orders.innerText = `Количество заказов ↑`
                                sortedRows.sort((rowA, rowB) => rowA.cells[2].innerText > rowB.cells[2].innerText ? 1 : -
                                    1);
                            } else {
                                th_column_count_orders.innerText = `Количество заказов ↓`;
                                sortedRows.sort((rowA, rowB) => rowA.cells[2].innerText < rowB.cells[2].innerText ? 1 : -
                                    1);
                            }

                            th_column_manager.innerText = `Менеджер`;
                            th_column_name.innerText = `Имя`;
                            th_column_sum_orders.innerText = `Сумма заказов`;
                            th_column_count_shipments.innerText = `Количество отгрузок`;
                            th_column_sum_shipments.innerText = `Сумма отгрузок`;
                            break;

                        case 'sum_orders':
                            if (th_column_sum_orders.innerText == `Сумма заказов ↓`) {
                                th_column_sum_orders.innerText = `Сумма заказов ↑`
                                sortedRows.sort((rowA, rowB) => rowA.cells[3].innerText > rowB.cells[3].innerText ? 1 : -
                                    1);
                            } else {
                                th_column_sum_orders.innerText = `Сумма заказов ↓`;
                                sortedRows.sort((rowA, rowB) => rowA.cells[3].innerText < rowB.cells[3].innerText ? 1 : -
                                    1);
                            }

                            th_column_manager.innerText = `Менеджер`;
                            th_column_count_orders.innerText = `Количество заказов`;
                            th_column_name.innerText = `Имя`;
                            th_column_count_shipments.innerText = `Количество отгрузок`;
                            th_column_sum_shipments.innerText = `Сумма отгрузок`;
                            break;

                        case 'count_shipments':
                            if (th_column_count_shipments.innerText == `Количество отгрузок ↓`) {
                                th_column_count_shipments.innerText = `Количество отгрузок ↑`
                                sortedRows.sort((rowA, rowB) => rowA.cells[4].innerText > rowB.cells[4].innerText ? 1 : -
                                    1);
                            } else {
                                th_column_count_shipments.innerText = `Количество отгрузок ↓`;
                                sortedRows.sort((rowA, rowB) => rowA.cells[4].innerText < rowB.cells[4].innerText ? 1 : -
                                    1);
                            }

                            th_column_manager.innerText = `Менеджер`;
                            th_column_count_orders.innerText = `Количество заказов`;
                            th_column_sum_orders.innerText = `Сумма заказов`;
                            th_column_name.innerText = `Имя`;
                            th_column_sum_shipments.innerText = `Сумма отгрузок`;
                            break;

                        case 'sum_shipments':
                            if (th_column_sum_shipments.innerText == `Сумма отгрузок ↓`) {
                                th_column_sum_shipments.innerText = `Сумма отгрузок ↑`
                                sortedRows.sort((rowA, rowB) => rowA.cells[5].innerText > rowB.cells[5].innerText ? 1 : -
                                    1);
                            } else {
                                th_column_sum_shipments.innerText = `Сумма отгрузок ↓`;
                                sortedRows.sort((rowA, rowB) => rowA.cells[5].innerText < rowB.cells[5].innerText ? 1 : -
                                    1);
                            }

                            th_column_manager.innerText = `Менеджер`;
                            th_column_count_orders.innerText = `Количество заказов`;
                            th_column_sum_orders.innerText = `Сумма заказов`;
                            th_column_name.innerText = `Имя`;
                            th_column_count_shipments.innerText = `Количество отгрузок`;
                            break;
                    }

                    ContactsTable.tBodies[0].append(...sortedRows);
                }
            </script>

        </div>
    </div>



</x-app-layout>
