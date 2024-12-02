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



        <h3 class="text-4xl font-bold mb-6">{{ __("title.summaryRemains") }}</h3>


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
                            <td class="break-all w-16 overflow-auto px-2 py-3">
                                {{ __("summary.orders") }}
                            </td>
                            <td class="break-all w-16 overflow-auto px-2 py-3">
                                {{ __("summary.demands") }}
                            </td>
                            <td class="break-all w-16 overflow-auto px-2 py-3">
                                {{ __("summary.contacts") }}
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b-2">
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                    crm
                            </td>
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                {{ $cntOrdersSite }}
                            </td>
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                {{ $cntShipmentsSite }}
                            </td>
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                {{ $cntContactsSite }}
                            </td>
                        </tr>

                        <tr class="border-b-2">
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                    ms
                            </td>
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                {{ $cntOrdersMS }}
                            </td>
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                {{ $cntShipmentsMS}}

                            </td>
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                {{ $cntContactsMS  }}
                            </td>
                        </tr>

                        <tr class="border-b-2">
                            <td class="break-all overflow-auto px-2 py-3 text-sm">

                            </td>
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                {{ $cntOrdersSite-$cntOrdersMS }}

                                @foreach($ordersSite as $order)

                                 <p><a  class="underline text-blue-600 decoration-sky-600 md:decoration-blue-400" href="/order/{{  $order->id }}">Заказ №{{ $order->id }}</a></p>

                                @endforeach
                            </td>
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                {{$cntShipmentsSite-$cntShipmentsMS}}

                                @foreach($shipmentsSite as $shipment)

                                <p><a  class="underline text-blue-600 decoration-sky-600 md:decoration-blue-400" href="/shipment/{{  $shipment->id }}">Отгрузка №{{ $order->id }}</a></p>

                                @endforeach
                            </td>
                            <td class="break-all overflow-auto px-2 py-3 text-sm">
                                {{ $cntContactsSite-$cntContactsMS  }}
                                @foreach($contactsSite as $contact)
                                  <p><a  class="underline text-blue-600 decoration-sky-600 md:decoration-blue-400" href="/contact/{{  $contact->id }}">Контакт №{{ $contact->id }}</a></p>
                                @endforeach
                                @foreach($contactsSite1 as $contact)
                                <p><a  class="underline text-blue-600 decoration-sky-600 md:decoration-blue-400" href="/contact/{{  $contact->id }}">Контакт №{{ $contact->id }}</a></p>
                              @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>



    </div>
</x-app-layout>
