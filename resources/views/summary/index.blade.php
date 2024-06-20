<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        @vite(['resources/css/calculator.css', 'resources/js/jquery-ui.min.js', 'resources/js/jquery.ui.touch-punch.js'])
    </x-slot>
    <div class="w-11/12 mx-auto py-8">

        <h3 class="text-4xl font-bold mb-6">{{ __('title.summary') }}</h3>

        <div class="CEB__wrapTable mb-5">
            <table class="sum">
                <tr>
                    <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 ">
                        {{ __('summary.mutualSettlement') }}</th>
                    <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format($sumMutualSettlement, 1, '.', ' ') }}</td>
                </tr>
                <tr>
                    <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                        {{ __('summary.mutualSettlementMain') }}</th>
                    <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format($sumMutualSettlementMain, 1, '.', ' ') }}
                    </td>
                </tr>
                <tr>
                    <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 ">{{ __('summary.buyers') }}
                    </th>
                    <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format($sumBuyer, 1, '.', ' ') }}</td>
                </tr>
                <tr>
                    <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2">{{ __('summary.carriers') }}
                    </th>
                    <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format($sumCarriers, 1, '.', ' ') }}</td>
                </tr>
                <tr>
                    <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">{{ __('column.another') }}
                    </th>
                    <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format($sumAnother, 1, '.', ' ') }}</td>
                </tr>
                <tr>
                    <td colspan=2></td>
                </tr>

                <tr>
                    <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                        {{ __('summary.materials') }}</th>
                    <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format($sumMaterials, 1, '.', ' ') }}</td>
                </tr>
                <tr>
                    <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2">
                        {{ __('summary.products') }}</th>
                    <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format($sumProducts, 1, '.', ' ') }}</td>
                </tr>

            </table>
        </div>

        <div class="flex">
            <div class="CEB__wrapTable">
                <table class="sum">
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 ">
                            {{ __('column.mutualSettlement') }}</th>
                        <th class="bg-neutral-200 font-semibold text-end pl-2 pt-2 pb-2 pr-2">
                            {{ __('column.balance') }}</th>
                    </tr>
                    @foreach ($contactsMutualSettlement as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2 ">
                                {{ number_format(ceil($contact->balance), 0, '.', ' ') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 ">
                            {{ number_format(ceil($sumMutualSettlementMainDebt), 0, '.', ' ') }}</td>
                    </tr>
                </table>
            </div>

            @if (count($contactsCarrier)>0)
            <div class="CEB__wrapTable">
                <table class="sum">
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 ">
                            {{ __('column.carrier') }}</th>
                        <th class="bg-neutral-200 font-semibold text-end pl-2 pt-2 pb-2 pr-2  ">
                            {{ __('column.balance') }}</th>
                    </tr>
                    @foreach ($contactsCarrier as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2 ">
                                {{ number_format(ceil($contact->balance), 0, '.', ' ') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format(ceil($sumCarriersDebt), 0, '.', ' ') }}
                        </td>
                    </tr>
                </table>
            </div>
            @endif

            @if (count($contactsBuyer)>0)
            <div class="CEB__wrapTable">
                <table class="sum">
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 ">
                            {{ __('column.buyer') }}</th>
                        <th class="bg-neutral-200 font-semibold text-end pl-2 pt-2 pb-2 pr-2 ">
                            {{ __('column.balance') }}</th>
                    </tr>
                    @foreach ($contactsBuyer as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2 ">
                                {{ number_format(ceil($contact->balance), 0, '.', ' ') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format(ceil($sumBuyerDebt), 0, '.', ' ') }}
                        </td>
                    </tr>
                </table>
            </div>
            @endif

            @if (count($contactsAnother)>0)
            <div class="CEB__wrapTable">
                <table class="sum">
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 ">
                            {{ __('column.another') }}</th>
                        <th class="bg-neutral-200 font-semibold text-end pl-2 pt-2 pb-2 pr-2 ">
                            {{ __('column.balance') }}</th>
                    </tr>
                    @foreach ($contactsAnother as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2 ">
                                {{ number_format(ceil($contact->balance), 0, '.', ' ') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2 ">{{ number_format(ceil($sumAnotherDebt), 0, '.', ' ') }}
                        </td>
                    </tr>
                </table>
            </div>
            @endif
        </div>

        @if (count($shipments)>0)
        <div class="CEB__wrapTable mt-5">
            <table class="sum">
                <tr>
                    <th>{{__('column.name')}}</th>
                    <th>{{__('column.transport')}}</th>
                </tr>
                @foreach($shipments as $shipment)
                    <tr>
                        <td>{{$shipment->name}}</td>
                        <td>{{$shipment->transport->name}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        @endif
    </div>

</x-app-layout>
