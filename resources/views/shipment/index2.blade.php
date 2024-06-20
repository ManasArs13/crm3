<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8">

        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
        @endif

        @if (count($shipments)>0)
            <div class="CEB__wrapTable mt-5">
                <table class="sum">
                    <tr>
                        <th class="border bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2">{{__('column.name')}}</th>
                        <th class="border bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2">{{__('column.transport')}}</th>
                    </tr>
                    @foreach($shipments as $shipment)
                        <tr class="border">
                            <td class="border border-gray-300">{{$shipment->name}}</td>
                            <td class="border border-gray-300 text-end pl-2 pt-2 pb-2 pr-2">{{$shipment->transportName}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif

    </div>

</x-app-layout>
