<x-app-layout>

    <x-slot:title>
        Калькулятор (БЛОК)
    </x-slot>

    <x-slot:head>
        @vite(['resources/css/calculator.css'])
    </x-slot>
    <div class="w-11/12 mx-auto py-8">
        <h3 class="text-4xl font-bold mb-6">{{ __('title.debtors') }}</h3>


        @include("shipment.debtorstable")
    </div>

</x-app-layout>
