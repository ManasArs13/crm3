<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        @vite(['resources/css/calculator.css'])
    </x-slot>
    <div class="w-11/12 mx-auto py-8">
        <h3 class="text-4xl font-bold mb-6">{{ __('title.debtors') }}</h3>

        @if (count($shipments)>0)
            <div class="CEB__wrapTable mb-5">
                <table class="sum">
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 ">
                            {{ __('column.name') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                                {{ __('column.date_of_last_shipment') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                                {{ __('column.days') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                                    {{ __('column.balance') }}</th>
                    </tr>
                    @foreach($shipments as $shipment)
                        <tr>
                            <td class="text-start pl-2 pt-2 pb-2 pr-2"><a href="https://online.moysklad.ru/app/#Company/edit?id={{ $shipment->ms_id }}" target="__blank">{{ $shipment->name }}</a></td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2">{{ $shipment->moment}}</td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2">{{ $shipment->days }}</td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2">{{ $shipment->balance }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif


    </div>

</x-app-layout>
