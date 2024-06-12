<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        @vite(['resources/css/calculator.css','resources/js/jquery-ui.min.js','resources/js/jquery.ui.touch-punch.js'])
    </x-slot>
<div class="w-11/12 mx-auto py-8">

            <h3 class="text-4xl font-bold mb-6">{{__('title.summary')}}</h3>

            <div class="CEB__wrapTable">
                    <table class="CEB__table sum">
                        <tr>
                            <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 ">{{__('summary.mutualSettlement')}}</th>
                            <td>{{$sumMutualSettlement}}</td>
                        </tr>
                        <tr>
                            <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 ">{{__('summary.mutualSettlementMain')}}</th>
                            <td>{{$sumMutualSettlementMain}}</td>
                        </tr>
                        <tr>
                            <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 ">{{__('summary.materials')}}</th>
                            <td>{{$sumMaterials}}</td>
                        </tr>
                        <tr>
                            <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 ">{{__('summary.products')}}</th>
                            <td>{{$sumProducts}}</td>
                        </tr>
                    </table>

            </div>


</div>

</x-app-layout>

