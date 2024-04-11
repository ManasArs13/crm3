<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }} {{ $supply->name }}
        </x-slot>
    @endif

    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }} {{ $supply->name }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04) mb-8">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <h5> от {{ $supply->moment }}</h5>
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
                                {{ __('column.contact_id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.sum') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.incoming_number') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.incoming_date') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b-2">
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                {{ $supply->id }}
                            </td>
                            <td class="break-all max-w-[24rem] overflow-x-auto px-6 py-4 text-blue-600">
                                @if ($supply->contact)
                                    <a href="{{ route('contact.show', ['contact' => $supply->contact->id]) }}">
                                        {{ $supply->contact->name }}
                                    </a>
                                @else
                                    {{ __('column.no') }}
                                @endif
                            </td>
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                {{ $supply->sum }}
                            </td>
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                {{ $supply->incoming_number }}
                            </td>
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                {{ $supply->incoming_date }}
                            </td>
                        </tr>
                        <tr class="overflow-hidden px-6 py-4">
                            <td colspan="6" class="overflow-hidden px-6 py-4">
                                {{ __('column.description') }} :
                                @if ($supply->description)
                                    {{ $supply->description }}
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
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <h5>{{ __('column.productions') }}</h5>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left font-light text-nowrap">
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
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.price') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supply->products as $product)
                            <tr class="border-b-2">
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-blue-600">
                                    <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                        {{ $product->pivot->id }}
                                    </a>
                                </td>
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4 text-blue-600">
                                    <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                    {{ $product->pivot->quantity }}
                                </td>
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                    {{ $product->pivot->price }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</x-app-layout>
