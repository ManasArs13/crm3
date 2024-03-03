<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <div class="flex flex-row gap-1">
                        <div>
                            @if (url()->current() == route('techcharts.index'))
                                <a href="{{ route('techcharts.index') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Общая таблица</a>
                            @else
                                <a href="{{ route('techcharts.index') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Общая таблица</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('techcharts.products'))
                                <a href="{{ route('techcharts.products') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Связь (продукты)</a>
                            @else
                                <a href="{{ route('techcharts.products') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Связь (продукты)</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('techcharts.materials'))
                                <a href="{{ route('techcharts.materials') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Связь (материалы)</a>
                            @else
                                <a href="{{ route('techcharts.materials') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Связь (материалы)</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-mdtext-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.techchart_id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.product_id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.quantity') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.created_at') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.updated_at') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tech_chart_products as $product)
                            <tr class="border-b-2">
                                <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                    {{ $product->id }}
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                    <a class="text-blue-700 hover:text-blue-500"
                                        href="{{ route('techcharts.show', ['techchart' => $product->tech_chart_id]) }}">
                                        {{ $product->tech_chart_id }}
                                    </a>
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                    <a class="text-blue-700 hover:text-blue-500"
                                        href="{{ route('product.show', ['product' => $product->product_id]) }}">
                                        {{ $product->product_id }}
                                    </a>
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                    {{ $product->quantity }}
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                    {{ $product->created_at }}
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                    {{ $product->updated_at }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $tech_chart_products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</x-app-layout>