<x-app-layout>
    <div class="flex flex-col lg:flex-row flex-nowrap gap-3 w-11/12 mx-auto py-10">
        <div class="flex flex-col basis-3/4 bg-white rounded-md shadow overflow-x-auto">
            <div class="flex flex-row w-full p-3 justify-between">
                <div class="flex gap-2">
                    <div>
                        @if (request()->filter == 'now' || request()->filter == null)
                            <a href="{{ route('dashboard-2', ['filter' => 'now']) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Сегодня</a>
                        @else
                            <a href="{{ route('dashboard-2', ['filter' => 'now']) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Сегодня</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->filter == 'tomorrow')
                            <a href="{{ route('dashboard-2', ['filter' => 'tomorrow']) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Завтра</a>
                        @else
                            <a href="{{ route('dashboard-2', ['filter' => 'tomorrow']) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Завтра</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->filter == 'three-day')
                            <a href="{{ route('dashboard-2', ['filter' => 'three-day']) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">3
                                дня</a>
                        @else
                            <a href="{{ route('dashboard-2', ['filter' => 'three-day']) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">3
                                дня</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->filter == 'week')
                            <a href="{{ route('dashboard-2', ['filter' => 'week']) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Неделя</a>
                        @else
                            <a href="{{ route('dashboard-2', ['filter' => 'week']) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Неделя</a>
                        @endif
                    </div>
                </div>
                <div class="flex px-3 text-center font-bold">
                    {{ \Illuminate\Support\Carbon::now()->format('Y-m-d') }}
                </div>
            </div>
            @include('Dashboard.components.canvas')
            <div class="block border-t-2 py-5 overflow-x-scroll">
                @include('Dashboard.components.orderTable', ['filter' => 'block'])
            </div>
        </div>
        <div class="flex flex-col gap-4 basis-1/4">
            <div class="flex flex-col p-1 bg-white rounded-md shadow overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th class="justify-content-center items-center mb-2">
                                <span class="text-lg font-semibold">Материалы - блок</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blocksMaterials as $blocksMaterial)
                            <tr class="border-b-2">
                                <td class="m-2 justify-content-beetwen">
                                    {{ $blocksMaterial->name }}
                                </td>
                                <td>
                                    @if (
                                        $blocksMaterial->residual_norm !== 0 &&
                                            $blocksMaterial->residual_norm !== null &&
                                            $blocksMaterial->type !== 'не выбрано')
                                        <div
                                            @if (round(($blocksMaterial->residual / $blocksMaterial->residual_norm) * 100) <= 30) class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center" @elseif(round(($blocksMaterial->residual / $blocksMaterial->residual_norm) * 100) > 30 &&
                                                    round(($blocksMaterial->residual / $blocksMaterial->residual_norm) * 100) <= 70) class="bg-yellow-300 rounded-sm p-1 h-6 flex justify-center items-center" @else class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center" @endif>
                                            {{ round(($blocksMaterial->residual / $blocksMaterial->residual_norm) * 100) }}%
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
                                <span class="text-lg font-semibold">Категории - блок</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            @if (isset($category->remainder))
                                <tr class="border-b-2">
                                    <td class="m-2 justify-content-beetwen">
                                        {{ $category->name }}
                                    </td>
                                    <td>
                                        <div
                                            @if (round($category->remainder) <= 30) class="bg-red-300 rounded-sm p-1 h-6 flex justify-center items-center"
                                        @elseif(round($category->remainder) > 30 && round($category->remainder) <= 70)
                                        class="bg-yellow-300 rounded-sm p-1 h-6 flex justify-center items-center"
                                        @else
                                        class="bg-green-300 rounded-sm p-1 h-6 flex justify-center items-center" @endif>
                                            {{ round($category->remainder) }}%
                                        </div>
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
