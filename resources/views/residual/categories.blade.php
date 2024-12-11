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
                    <div class="border-b-2 border-neutral-100 overflow-x-auto">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex flex-row gap-1">
                                <div>
                                    @if (url()->current() == route('residual.blocksCategories'))
                                        <a href="{{ route('residual.blocksCategories') }}"
                                           class="block rounded bg-blue-600 px-1 md:px-5 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.products') }}</a>
                                    @else
                                        <a href="{{ route('residual.blocksCategories') }}"
                                           class="block rounded bg-blue-300 px-1 md:px-5 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.products') }}</a>
                                    @endif
                                </div>
                                <div>
                                    @if (url()->current() == route('residual.blocksMaterials'))
                                        <a href="{{ route('residual.blocksMaterials') }}"
                                           class="block rounded bg-blue-600 px-1 md:px-5 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.materials') }}</a>
                                    @else
                                        <a href="{{ route('residual.blocksMaterials') }}"
                                           class="block rounded bg-blue-300 px-1 md:px-5 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">{{ __('column.materials') }}</a>
                                    @endif
                                </div>


                            </div>
                        </div>
                    </div>


                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-xs md:text-base text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold py-2">
                                <th scope="col" class="px-5 py-2 md:py-4 text-left"></th>
                                <th scope="col" class="px-5 py-2 md:py-4 text-left">
                                    {{ __('column.name') }}
                                </th>
                                <th scope="col" class="px-5 py-2 md:py-4">
                                    {{ __('column.status_id') }}
                                </th>
                                <th scope="col" class="px-5 py-2 md:py-4 text-right">
                                    {{ __('column.count_orders') }}
                                </th>
                                <th scope="col" class="px-5 py-2 md:py-4 text-right">
                                    {{ __('column.count_quantity') }}
                                </th>
                                <th scope="col" class="px-5 py-2 md:py-4 text-right">
                                    {{ __('column.residual_norm') }}
                                </th>

                                @if (url()->current() == route('residual.blocksProducts'))
                                    <th scope="col" class="px-5 py-2 md:py-4 text-center">
                                        {{ __('column.materials') }}
                                    </th>
                                @endif

                                <th scope="col" class="px-5 py-2 md:py-4 text-right">
                                    {{ __('column.residual') }}
                                </th>

                                <th scope="col" class="px-5 py-2 md:py-4 text-right">
                                    {{ __('column.enough_days') }}
                                </th>

                                <th scope="col" class="px-5 py-2 md:py-4  text-right">
                                    {{ __('column.need') }}
                                </th>

                                @if (url()->current() !== route('residual.concretesMaterials') && url()->current() !== route('residual.blocksMaterials'))
                                    <th scope="col" class="px-5 py-2 md:py-4 text-right">
                                        {{ __('column.making_dais') }}
                                    </th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($products as $product)
                                @php
                                    $residual_category = 0;
                                    $consumption_year_category = 0;
                                @endphp

                                @foreach($product->pre_products as $pre_product)
                                    @if($pre_product->residual && $pre_product->consumption_year)
                                        @php
                                            $residual_category += $pre_product->residual;
                                            $consumption_year_category += $pre_product->consumption_year;
                                        @endphp
                                    @endif
                                @endforeach

                                @if ($product->residual_norm)
                                    <tr class="border-b-2 font-normal py-2">
                                        <td class="text-nowrap px-5 py-2">
                                            <button class="buttonForOpen text-normal font-bold" data-id="{{ $product->id }}">+</button>
                                        </td>
                                        <th class="font-normal text-left px-5 py-2 md:py-4">
                                            <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                {{ $product->name }}
                                            </a>
                                        </th>

                                        <th class="font-normal break-all max-w-32 overflow-hidden px-5 py-2 md:py-4 text-right">
                                            @if ($product->residual_norm !== 0 && $product->residual_norm !== null)
                                                <div
                                                    @if (round(($product->residual / $product->residual_norm) * 100) <= 30) class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center" @elseif(round(($product->residual / $product->residual_norm) * 100) > 30 &&
                                                            round(($product->residual / $product->residual_norm) * 100) <= 70) class="bg-yellow-300 rounded-sm p-1 h-6 flex justify-center items-center" @else class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center" @endif>
                                                    {{ round(($product->residual / $product->residual_norm) * 100) }}%
                                                </div>
                                            @else
                                                {{ __('column.no') }}
                                            @endif
                                        </th>

                                        <th class="font-normal break-all max-w-32 overflow-hidden px-5 py-2 md:py-4 text-right">
                                            {{ number_format($product->totalOrderSum, 0, '.', ' ') }}
                                        </th>
                                        <th class="font-normal break-all max-w-32 overflow-hidden px-5 py-2 md:py-4 text-right">
                                            {{ number_format($product->totalOrderQuantity, 0, '.', ' ') }}
                                        </th>

                                        <th class="font-normal text-right px-5">
                                            @if ($product->residual_norm)
                                                {{ $product->residual_norm }}
                                            @else
                                                {{ __('column.no') }}
                                            @endif
                                        </th>

                                        @if (url()->current() == route('residual.blocksProducts'))
                                            <th class="font-normal break-all max-w-32 overflow-hidden px-5 py-2 md:py-4">
                                                @if ($product->materials == 'нет')
                                                    <div
                                                        class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center">
                                                        {{ $product->materials }}
                                                    </div>
                                                @elseif ($product->materials == 'да')
                                                    <div
                                                        class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center">
                                                        {{ $product->materials }}
                                                    </div>
                                                @else
                                                    {{ $product->materials }}
                                                @endif
                                            </th>
                                        @endif

                                        <th class="text-right px-5 font-normal">
                                            @if ($product->residual)
                                                {{ $product->residual }}
                                            @else
                                                {{ __('column.no') }}
                                            @endif
                                        </th>

                                        <th class="text-right px-5 font-normal">
                                            {{ $residual_category && $consumption_year_category ? round($residual_category / $consumption_year_category * 365) : '-' }}
                                        </th>

                                        <th class="font-normal text-right px-5">
                                            @if ($product->residual && $product->residual_norm)
                                                @if ($product->residual - $product->residual_norm < 0)
                                                    {{ abs($product->residual - $product->residual_norm) }}
                                                @else
                                                    {{ 0 }}
                                                @endif
                                            @else
                                                {{ __('column.no') }}
                                            @endif
                                        </th>

                                        @if (url()->current() !== route('residual.concretesMaterials') && url()->current() !== route('residual.blocksMaterials'))
                                            <th class="font-normal text-right px-5">
                                                @if ($product->making_day)
                                                    {{ $product->making_day }}
                                                @else
                                                    @if ($product->residual && $product->residual_norm && $product->release)
                                                        @if ($product->residual - $product->residual_norm >= 0)
                                                            {{ 0 }}
                                                        @else
                                                            {{ abs(round(($product->residual - $product->residual_norm) / $product->release, 0)) }}
                                                        @endif
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                @endif
                                            </th>
                                        @endif

                                    </tr>
                                    @foreach($product->pre_products as $pre_product)
                                    <tr style="display: none" class="border-b-2 bg-green-100 position_column_{{ $product->id }}">
                                        <td class="px-5 py-2">{{ $pre_product->id }}</td>
                                        <td class="font-normal text-left px-5 py-2 md:py-4">{{ $pre_product->short_name }}</td>
                                        <td class="font-normal break-all max-w-32 overflow-hidden px-5 py-2 md:py-4 text-right">
                                            @if ($pre_product->residual_norm !== 0 && $pre_product->residual_norm !== null)
                                                <div
                                                    @if (round(($pre_product->residual / $pre_product->residual_norm) * 100) <= 30) class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center" @elseif(round(($pre_product->residual / $pre_product->residual_norm) * 100) > 30 &&
                                                            round(($pre_product->residual / $pre_product->residual_norm) * 100) <= 70) class="bg-yellow-300 rounded-sm p-1 h-6 flex justify-center items-center" @else class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center" @endif>
                                                    {{ round(($pre_product->residual / $pre_product->residual_norm) * 100) }}%
                                                </div>
                                            @else
                                                {{ __('column.no') }}
                                            @endif
                                        </td>
                                        <td class="font-normal text-right px-5 py-2 md:py-4">
                                            {{ number_format($pre_product->totalOrderSum, 0, '.', ' ') }}
                                        </td>
                                        <td class="font-normal text-right px-5 py-2 md:py-4">
                                            {{ number_format($pre_product->totalOrderQuantity, 0, '.', ' ') }}
                                        </td>
                                        <td class="font-normal text-right px-5">
                                            @if ($pre_product->residual_norm)
                                                {{ $pre_product->residual_norm }}
                                            @else
                                                {{ __('column.no') }}
                                            @endif
                                        </td>
                                        <td class="font-normal text-right px-5">{{ $pre_product->residual }}</td>
                                        <td class="text-right px-5">{{ $pre_product->residual && $pre_product->consumption_year ? round($pre_product->residual / $pre_product->consumption_year * 365) : '-' }}</td>
                                        <td class="px-5 py-2 text-right">
                                            @if ($pre_product->residual && $pre_product->residual_norm)
                                                @if ($pre_product->residual - $pre_product->residual_norm < 0)
                                                    {{ abs($pre_product->residual - $pre_product->residual_norm) }}
                                                @else
                                                    {{ 0 }}
                                                @endif
                                            @else
                                                {{ __('column.no') }}
                                            @endif
                                        </td>
                                        <td class="font-normal text-right px-5">
                                            @if ($pre_product->residual && $pre_product->residual_norm && $pre_product->release)
                                                @if ($pre_product->residual - $pre_product->residual_norm >= 0)
                                                    {{ 0 }}
                                                @else
                                                    {{ abs(round(($pre_product->residual - $pre_product->residual_norm) / $pre_product->release, 0)) }}
                                                @endif
                                            @else
                                                {{ 0 }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach

                                @endif
                            @endforeach
                            </tbody>
                            <script>
                                document.addEventListener("DOMContentLoaded", function(event) {

                                    let buttons = document.querySelectorAll(".buttonForOpen")
                                    for (var i = 0; i < buttons.length; i++) {
                                        let attrib = buttons[i].getAttribute("data-id");
                                        let but = buttons[i];

                                        function cl(attr, b) {
                                            let positions = document.querySelectorAll(".position_column_" + attr, b);
                                            for (var i = 0; i < positions.length; i++) {
                                                console.log(positions[i].style.display)
                                                if (positions[i].style.display === 'none') {
                                                    positions[i].style.display = ''
                                                    b.textContent = '-'
                                                } else {
                                                    positions[i].style.display = 'none'
                                                    b.textContent = '+'
                                                }
                                            }
                                        }
                                        buttons[i].addEventListener("click", cl.bind(null, attrib, but));
                                    }
                                });
                            </script>
                        </table>
                    </div>

                </div>


            </div>

</x-app-layout>
