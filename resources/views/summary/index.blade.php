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
<div class="main-1 w-11/12 mx-auto px-4 sm:px-6 lg:px-8">
    <div class="content">
            <div class="CEB__wrapTable">
                    <table class="CEB__table sum">
                        <tr>
                            <th>{{__('summary.mutualSettlement')}}</th>
                            <td>{{$sumMutualSettlement}}</td>
                        </tr>
                        <tr>
                            <th>{{__('summary.mutualSettlementMain')}}</th>
                            <td>{{$sumMutualSettlementMain}}</td>
                        </tr>
                        <tr>
                            <th>{{__('summary.materials')}}</th>
                            <td>{{$sumMaterials}}</td>
                        </tr>
                        <tr>
                            <th>{{__('summary.products')}}</th>
                            <td>{{$sumProducts}}</td>
                        </tr>
                    </table>

            </div>

    </div>
</div>

</x-app-layout>

