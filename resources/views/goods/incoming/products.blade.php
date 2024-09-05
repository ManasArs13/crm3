<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entity }}
        </x-slot>
    @endif

    <div class="w-11/12 mx-auto py-8 max-w-10xl">

        @if (session('success'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                {{ session('success') }}
            </div>
        @endif

        @if (session('danger'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-red-200 px-6 py-5 text-red-700 ">
                {{ session('danger') }}
            </div>
        @endif

        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ $entity }}</h3>
        @endif
        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <div class="flex flex-row gap-1">
                        <div>
                            @if (url()->current() == route('incomings.index'))
                                <a href="{{ route('incomings.index') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Приход</a>
                            @else
                                <a href="{{ route('incomings.index') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Приход</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('incomings.products'))
                                <a href="{{ route('incomings.products') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Состав</a>
                            @else
                                <a href="{{ route('incomings.products') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Состав</a>
                            @endif
                        </div>
                    </div>
                    <div class="flex px-3 text-center font-bold">
                        <a href="{{ route($entityCreate) }}"
                            class="inline-flex items-center rounded bg-green-400 px-3 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                            {{ __('label.create') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-scroll">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-3">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('column.incoming') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('column.product_id') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('column.quantity') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('column.price') }}
                            </th>
                            <td class="px-6 py-3">
                                {{ __('column.sum') }}
                            </td>
                            <th scope="col" class="px-6 py-3">
                                {{ __('column.created_at') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('column.updated_at') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incoming_products as $product)
                            <tr class="border-b-2">
                                <td class="text-blue-600 px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    <a href="{{ route('incomings.show', ['incoming' => $product->incoming_id]) }}">
                                        {{ $product->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    @if ($product->incoming)
                                        <a href="{{ route('incomings.show', ['incoming' => $product->incoming_id]) }}">
                                            {{ $product->incoming_id }}
                                        </a>
                                    @else
                                        {{ __('column.no') }}
                                    @endif
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    @if ($product->products)
                                        <a href="{{ route('product.show', ['product' => $product->product_id]) }}">
                                            {{ $product->products->name }}
                                        </a>
                                    @else
                                        {{ __('column.no') }}
                                    @endif
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ $product->quantity }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ number_format((int) $product->price, 0, ',', ' ') }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ number_format((int) $product->summa, 0, ',', ' ') }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ $product->created_at }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ $product->updated_at }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $incoming_products->appends(request()->query())->links() }}
            </div>

        </div>
</x-app-layout>
