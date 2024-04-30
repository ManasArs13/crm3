<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entity }} №{{ $incoming->id }}
        </x-slot>
    @endif

    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ $entity }} №{{ $incoming->id }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04) mb-8">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <h5> от {{ $incoming->created_at }}</h5>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-nowrap">
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
                                {{ __('column.updated_at') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b-2">
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                {{ $incoming->id }}
                            </td>
                            <td class="break-all max-w-[24rem] overflow-x-auto px-6 py-4 text-blue-600">
                                @if ($incoming->contact)
                                    <a href="{{ route('contact.show', ['contact' => $incoming->contact->id]) }}">
                                        {{ $incoming->contact->name }}
                                    </a>
                                @else
                                    {{ __('column.no') }}
                                @endif
                            </td>
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                {{ $incoming->sum }}
                            </td>
                            <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                {{ $incoming->updated_at }}
                            </td>
                        </tr>
                        <tr class="overflow-hidden px-6 py-4">
                            <td colspan="6" class="overflow-hidden px-6 py-4">
                                {{ __('column.description') }} :
                                @if ($incoming->description)
                                    {{ $incoming->description }}
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
                <table class="text-left text-nowrap">
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
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.sum') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incoming->products as $product)
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
                                <td class="break-all max-w-32 overflow-hidden px-6 py-4">
                                    {{ $product->pivot->sum }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</x-app-layout>
