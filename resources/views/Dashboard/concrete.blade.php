<x-app-layout>
    <div class="flex flex-col lg:flex-row flex-nowrap gap-3 w-11/12 mx-auto py-10">
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
                    <a href="{{ route('dashboard-3', ['date_plan' => $datePrev ]) }}" class="mx-2 text-lg">&#9668;</a>
                    <p class="mx-2 text-lg">{{ $date }}</p>
                    <a href="{{ route('dashboard-3', ['date_plan' => $dateNext ]) }}" class="mx-2 text-lg">&#9658;</a>
                </div>
            </div>
            @include('Dashboard.components.canvas', ['date' => $date])
            <div class="block border-t-2 py-5 overflow-x-scroll">
                @include('Dashboard.components.orderTable')
            </div>
        </div>
        <div class="flex flex-col gap-4 basis-1/4">
            <div class="flex flex-col p-1 bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <caption class="text-lg font-semibold">Материалы</caption>
                    <thead>
                        <tr class="font-light">
                            <th colspan="4" class="font-light"></th>
                            <th class="font-normal border-l-2">Начало</th>
                            <th class="font-normal border-x-2">Приход</th>
                            <th class="font-normal border-r-2">Расход</th>
                            <th class="font-normal">Конец</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $material)
                            <tr class="border-b-2">
                                <td class="m-2" colspan="4">
                                    {{ $material->name }}
                                </td>
                                <td class="m-2 text-right" colspan="1">
                                    {{ $material->residual }}
                                </td>
                                <td class="m-2 text-right" colspan="1">
                                    -
                                </td>
                                <td class="m-2 text-right" colspan="1">
                                    {{ $material->rashod ? $material->rashod : 0 }}
                                </td>
                                <td class="m-2 text-right" colspan="1">
                                    {{ $material->residual -  ($material->rashod ? $material->rashod : 0) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
