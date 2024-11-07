<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }} {{ $enter->name }}
        </x-slot>
    @endif

    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }} {{ $enter->name }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04) mb-8">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <h5> от {{ $enter->moment }}</h5>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-4 text-right">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-right">
                                {{ __('column.sum') }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-right">
                                {{ __('column.description') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b-2">
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-right">
                                {{ $enter->id }}
                            </td>
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-right">
                                {{ $enter->sum }}
                            </td>
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-right">
                                {{ $enter->description }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>



        </div>

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <h5>{{ __('column.productions') }}</h5>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-4 text-right">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.product_id') }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-right">
                                {{ __('column.quantity') }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-right">
                                {{ __('column.price') }}
                            </th>
                            <th scope="col" class="px-6 py-4 text-right">
                                {{ __('column.total') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enter->positions as $product)
                            <tr class="border-b-2">
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-blue-600 text-right">
                                    <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                        {{ $product->id }}
                                    </a>
                                </td>
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-blue-600">
                                    <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                        {{ $product->product->name }}
                                    </a>
                                </td>
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-right text-right">
                                    {{ $product->quantity }}
                                </td>
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-right text-right">
                                    {{ $product->price }}
                                </td>
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-right text-right">
                                    {{ $product->sum }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</x-app-layout>
