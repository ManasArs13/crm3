<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }} {{ $tech_chart->id }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-left">
                    <h5 class="font-bold text-lg text-left"> обнавлено: {{ $tech_chart->updated_at }}</h5>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.name') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.price') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b-2">
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $tech_chart->id }}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $tech_chart->name }}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $tech_chart->cost }}
                            </td>
                        </tr>
                        <tr class="border-b-2">
                            <td colspan="6" class="break-all overflow-hidden px-6 py-4">
                                {{ __('column.description') }} :
                                @if ($tech_chart->description)
                                    {{ $tech_chart->description }}
                                @else
                                    {{ __('column.no') }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>


        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04) my-10">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-left">
                    <h5 class="font-bold text-lg text-left">{{ __('column.productions') }}</h5>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.product_id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.quantity') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tech_chart->products as $product)
                            <tr class="border-b-2">
                                <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                    {{ $product->pivot->id }}
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                    <a class="text-blue-700 hover:text-blue-500"
                                        href="{{ route('product.show', ['product' => $product->id]) }}">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                    {{ $product->pivot->quantity }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-left">
                    <h5 class="font-bold text-lg text-left">{{ __('column.materials') }}</h5>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.product_id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.quantity') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tech_chart->materials as $product)
                        <tr class="border-b-2">
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $product->pivot->id }}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                <a class="text-blue-700 hover:text-blue-500"
                                href="{{ route('product.show', ['product' => $product->id]) }}">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $product->pivot->quantity }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</x-app-layout>
