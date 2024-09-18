<x-app-layout>
    <div class="flex flex-col lg:flex-row flex-nowrap gap-3 w-11/12 mx-auto py-10 max-w-10xl">
        <div class="flex flex-col basis-3/4 bg-white rounded-md shadow overflow-x-auto">
            <div class="flex flex-row w-full p-3 justify-between">
                <div class="flex gap-2">
                    <div class="">
                        @if (request()->routeIs('dashboard'))
                            <a href="{{ route('dashboard', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                        @else
                            <a href="{{ route('dashboard', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->routeIs('dashboard-2'))
                            <a href="{{ route('dashboard-2', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                        @else
                            <a href="{{ route('dashboard-2', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->routeIs('dashboard-3'))
                            <a href="{{ route('dashboard-3', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                        @else
                            <a href="{{ route('dashboard-3', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                        @endif
                    </div>
                </div>
                <div class="flex px-3 text-center font-bold">
                    <a href="{{ route('dashboard', ['date_plan' => $datePrev]) }}" class="mx-2 text-lg">&#9668;</a>
                    <p class="mx-2 text-lg">{{ $date }}</p>
                    <a href="{{ route('dashboard', ['date_plan' => $dateNext]) }}" class="mx-2 text-lg">&#9658;</a>
                </div>
            </div>
            @include('dashboard.components.canvas', ['date' => $date])
            <div class="block border-t-2 py-5 overflow-x-scroll">
                @include('dashboard.components.orderTable')
            </div>
        </div>
        <div class="flex flex-col gap-4 basis-1/4">
            <div class="flex flex-col bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <thead>
                        <tr class="font-light bg-neutral-200">
                            <th colspan="4" class="font-light px-1 py-3"></th>
                            <th class="border-l-2 px-1 py-3">Начало</th>
                            <th class="border-x-2 px-1 py-3">Приход</th>
                            <th class="border-r-2 px-1 py-3">Расход</th>
                            <th class="px-1">Конец</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($materials as $material)
                        @php
                            $residual_percent = round($material['residual'] / $material['residual_norm'] * 100);
                            $color = match (true) {
                                $residual_percent <= 30 => 'bg-red-100',
                                $residual_percent > 30 && $residual_percent <= 70 => 'bg-yellow-100',
                                default => 'bg-green-100'
                            };
                        @endphp
                        <tr class="border-b-2 {{ $color }}">
                            <td class="px-1 m-2 py-2 max-w-[150px] truncate" colspan="4">
                                {{ $material['short_name'] }}
                            </td>
                            <td class="px-1 m-2 text-right border-x-2 py-2" colspan="1">
                                {{ round($material['residual'] / 1000) }}
                            </td>
                            <td class="px-1 m-2 text-right border-x-2 py-2" colspan="1">
                                -
                            </td>
                            <td class="px-1 m-2 text-right border-x-2 py-2" colspan="1">
                                {{ $material['rashod'] ? round($material['rashod'] / 1000) : 0 }}
                            </td>
                            <td class="px-1 m-2 text-right py-2" colspan="1">
                                {{ round(($material['residual'] - ($material['rashod'] ? $material['rashod'] : 0)) / 1000) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            <div class="flex flex-col bg-white rounded-md shadow overflow-x-auto">
                <table>
                    <thead>
                    <tr class="font-light bg-neutral-200">
                        <th class="border-r-2 px-2 py-3 text-left">Категория</th>
                        <th class="px-1">Остаток</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($categories as $category)
                        @if (isset($category->remainder))
                            <tr class="border-b-2">
                                <td class="px-2 m-2 py-2 justify-content-beetwen">
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
