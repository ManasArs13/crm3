<x-app-layout>
    <div class="flex flex-col lg:flex-row flex-nowrap gap-3 w-11/12 mx-auto py-10">
        <div class="flex flex-col basis-3/4 bg-white rounded-md shadow overflow-x-auto">
            <div class="flex flex-row w-full p-3 justify-between">
                <div class="flex gap-2">
                    <div>
                        @if (request()->filter == 'now' || request()->filter == null)
                            <a href="{{ route('dashboard', ['filter' => 'now']) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Сегодня</a>
                        @else
                            <a href="{{ route('dashboard', ['filter' => 'now']) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Сегодня</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->filter == 'tomorrow')
                            <a href="{{ route('dashboard', ['filter' => 'tomorrow']) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Завтра</a>
                        @else
                            <a href="{{ route('dashboard', ['filter' => 'tomorrow']) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Завтра</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->filter == 'three-day')
                            <a href="{{ route('dashboard', ['filter' => 'three-day']) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">3
                                дня</a>
                        @else
                            <a href="{{ route('dashboard', ['filter' => 'three-day']) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">3
                                дня</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->filter == 'week')
                            <a href="{{ route('dashboard', ['filter' => 'week']) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Неделя</a>
                        @else
                            <a href="{{ route('dashboard', ['filter' => 'week']) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Неделя</a>
                        @endif
                    </div>

                    <div class="ml-10">
                        @if (request()->routeIs('dashboard'))
                            <a href="{{ route('dashboard') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                        @else
                            <a href="{{ route('dashboard') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->routeIs('dashboard-2'))
                            <a href="{{ route('dashboard-2') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                        @else
                            <a href="{{ route('dashboard-2') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->routeIs('dashboard-3'))
                            <a href="{{ route('dashboard-3') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                        @else
                            <a href="{{ route('dashboard-3') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                        @endif
                    </div>
                </div>
                <div class="flex px-3 text-center font-bold">
                    {{ \Illuminate\Support\Carbon::now()->format('Y-m-d') }}
                </div>
            </div>
            @include('Dashboard.components.canvas')
            <div class="block border-t-2 py-5 overflow-x-scroll">
                @include('Dashboard.components.orderTable', ['filter' => 'index'])
            </div>
        </div>
        <div class="flex flex-col gap-4 basis-1/4">
            <div class="flex flex-col p-1 bg-white rounded-md shadow overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th class="justify-content-center items-center mb-2">
                                <span class="text-lg font-semibold">Материалы</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $material)
                            <tr class="border-b-2">
                                <td class="m-2 justify-content-beetwen">
                                    {{ $material->name }}
                                </td>
                                <td>
                                    @if ($material->residual_norm !== 0 && $material->residual_norm !== null && $material->type !== 'не выбрано')
                                        <div
                                            @if (round(($material->residual / $material->residual_norm) * 100) <= 30) class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center" @elseif(round(($material->residual / $material->residual_norm) * 100) > 30 &&
                                                    round(($material->residual / $material->residual_norm) * 100) <= 70) class="bg-yellow-300 rounded-sm p-1 h-6 flex justify-center items-center" @else class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center" @endif>
                                            {{ round(($material->residual / $material->residual_norm) * 100) }}%
                                        </div>
                                    @else
                                        {{ null }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col p-1 bg-white rounded-md shadow overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th class="justify-content-center items-center mb-2">
                                <span class="text-lg font-semibold">Товары</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            @if ($product->residual_norm)
                                <tr class="border-b-2">
                                    <td class="m-2 justify-content-beetwen">
                                        {{ $product->name }}
                                    </td>
                                    <td>
                                        @if ($product->residual_norm !== 0 && $product->residual_norm !== null && $product->type !== 'не выбрано')
                                            <div
                                                @if (round(($product->residual / $product->residual_norm) * 100) <= 30) class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center" @elseif(round(($product->residual / $product->residual_norm) * 100) > 30 &&
                                                        round(($product->residual / $product->residual_norm) * 100) <= 70) class="bg-yellow-300 rounded-sm p-1 h-6 flex justify-center items-center" @else class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center" @endif>
                                                {{ round(($product->residual / $product->residual_norm) * 100) }}%
                                            </div>
                                        @else
                                            {{ null }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
