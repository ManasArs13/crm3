<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8 max-w-10xl">
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
                <table class="text-left text-mdtext-nowrap" id="chartsTable">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_id" onclick="orderBy('id')">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_techchart_id" onclick="orderBy('techchart_id')">
                                {{ __('column.techchart_id') }}
                            </th>
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_product_id" onclick="orderBy('product_id')">
                                {{ __('column.product_id') }}
                            </th>
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_quantity" onclick="orderBy('quantity')">
                                {{ __('column.quantity') }}
                            </th>
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_created_at" onclick="orderBy('created_at')">
                                {{ __('column.created_at') }}
                            </th>
                            <th scope="col" class="px-6 py-2 cursor-pointer" id="th_updated_at" onclick="orderBy('updated_at')">
                                {{ __('column.updated_at') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tech_chart_products as $product)
                            <tr class="border-b-2">
                                <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                    {{ $product->id }}
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                    <a class="text-blue-700 hover:text-blue-500"
                                        href="{{ route('techcharts.show', ['techchart' => $product->tech_chart_id]) }}">
                                        {{ $product->tech_chart_id }}
                                    </a>
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                    <a class="text-blue-700 hover:text-blue-500"
                                        href="{{ route('product.show', ['product' => $product->product_id]) }}">
                                        {{ $product->product_id }}
                                    </a>
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                    {{ $product->quantity }}
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-2">
                                    {{ $product->created_at }}
                                </td>
                                <td class="break-all max-w-96 overflow-hidden px-6 py-2">
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


    <script type="text/javascript">
        function orderBy(column) {

            let sortedRows = Array.from(chartsTable.rows).slice(1, -1);
            let totalRow = Array.from(chartsTable.rows).slice(chartsTable.rows.length - 1);


            let th_id = document.getElementById('th_id');
            let th_techchart_id = document.getElementById('th_techchart_id');
            let th_product_id = document.getElementById('th_product_id');
            let th_quantity = document.getElementById('th_quantity');
            let th_created_at = document.getElementById('th_created_at');
            let th_updated_at = document.getElementById('th_updated_at');

            switch (column) {

                case 'id':
                    if (th_id.innerText == `№ ↓`) {
                        th_id.innerText = `№ ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[0].innerText) > parseInt(rowB.cells[0]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_id.innerText = `№ ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[0].innerText) < parseInt(rowB.cells[0]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_techchart_id.innerText = 'Карта';
                    th_product_id.innerText = 'Товар';
                    th_quantity.innerText = 'Количество';
                    th_created_at.innerText = 'Дата создания';
                    th_updated_at.innerText = 'Дата обновления';
                    break;

                case 'techchart_id':
                    if (th_techchart_id.innerText == `Карта ↓`) {
                        th_techchart_id.innerText = `Карта ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[1].innerText) > parseInt(rowB.cells[1]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_techchart_id.innerText = `Карта ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[1].innerText) < parseInt(rowB.cells[1]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_id.innerText = '№';
                    th_product_id.innerText = 'Товар';
                    th_quantity.innerText = 'Количество';
                    th_created_at.innerText = 'Дата создания';
                    th_updated_at.innerText = 'Дата обновления';
                    break;

                case 'product_id':
                    if (th_product_id.innerText == `Товар ↓`) {
                        th_product_id.innerText = `Товар ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) > parseInt(rowB.cells[2]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_product_id.innerText = `Товар ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[2].innerText) < parseInt(rowB.cells[2]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_id.innerText = '№';
                    th_techchart_id.innerText = 'Карта';
                    th_quantity.innerText = 'Количество';
                    th_created_at.innerText = 'Дата создания';
                    th_updated_at.innerText = 'Дата обновления';
                    break;

                case 'quantity':
                    if (th_quantity.innerText == `Количество ↓`) {
                        th_quantity.innerText = `Количество ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) > parseInt(rowB.cells[3]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_quantity.innerText = `Количество ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[3].innerText) < parseInt(rowB.cells[3]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_id.innerText = '№';
                    th_techchart_id.innerText = 'Карта';
                    th_product_id.innerText = 'Товар';
                    th_created_at.innerText = 'Дата создания';
                    th_updated_at.innerText = 'Дата обновления';
                    break;

                case 'created_at':
                    if (th_created_at.innerText == `Дата создания ↓`) {
                        th_created_at.innerText = `Дата создания ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[4].innerText) > parseInt(rowB.cells[4]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_created_at.innerText = `Дата создания ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[4].innerText) < parseInt(rowB.cells[4]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_id.innerText = '№';
                    th_techchart_id.innerText = 'Карта';
                    th_product_id.innerText = 'Товар';
                    th_quantity.innerText = 'Количество';
                    th_updated_at.innerText = 'Дата обновления';
                    break;

                case 'updated_at':
                    if (th_updated_at.innerText == `Дата обновления ↓`) {
                        th_updated_at.innerText = `Дата обновления ↑`
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[5].innerText) > parseInt(rowB.cells[5]
                            .innerText) ? 1 : -
                            1);
                    } else {
                        th_updated_at.innerText = `Дата обновления ↓`;
                        sortedRows.sort((rowA, rowB) => parseInt(rowA.cells[5].innerText) < parseInt(rowB.cells[5]
                            .innerText) ? 1 : -
                            1);
                    }

                    th_id.innerText = '№';
                    th_techchart_id.innerText = 'Карта';
                    th_product_id.innerText = 'Товар';
                    th_quantity.innerText = 'Количество';
                    th_created_at.innerText = 'Дата создания';
                    break;


            }

            sortedRows.push(totalRow[0])
            chartsTable.tBodies[0].append(...sortedRows);
        }
    </script>


</x-app-layout>
