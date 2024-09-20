<x-app-layout>

    @if (isset($entityName) && $entityName != '')
        <x-slot:title>
            {{ $entityName }}
        </x-slot>
    @endif

    <x-slot:head>
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
    </x-slot>

    <div class="w-11/12 mx-auto py-8 max-w-10xl">

        @if (session('success'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                {{ session('success') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-yellow-200 px-6 py-5 text-yellow-700 ">
                {{ session('warning') }}
            </div>
        @endif

        @if (isset($entityName) && $entityName != '')
            <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header card --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <form method="get" action="{{ route($urlFilter) }}" class="flex gap-1">
                        <div>
                            <x-dropdown align="left" width="64" outside='false'>
                                <x-slot name="trigger">
                                    <button type="button"
                                        class="inline-flex rounded border-2 border-blue-600 text-blue-600 px-4 py-1 text-md font-medium leading-normal hover:bg-blue-700 hover:text-white">
                                        фильтр
                                        <div class="ms-1 mt-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="w-100 p-4">
                                        @foreach ($filters as $filter)
                                            @if ($filter['type'] == 'date' || $filter['type'] == 'number')
                                                <div class="flex flex-row gap-1 w-100">
                                                    <div class="basis-1/5">
                                                        <p>
                                                            {{ $filter['name_rus'] }}
                                                        </p>
                                                    </div>
                                                    <div class="basis-2/5">
                                                        <div class="relative mb-4 flex flex-wrap items-stretch">
                                                            <span
                                                                class="flex items-center whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200">
                                                                от</span>
                                                            <input name="filters[{{ $filter['name'] }}][min]"
                                                                step="0.1" type="{{ $filter['type'] }}"
                                                                min="{{ $filter['min'] }}" max="{{ $filter['max'] }}"
                                                                value="{{ $filter['minChecked'] }}"
                                                                class="date-default relative m-0 block w-[1px] min-w-0 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary">
                                                        </div>
                                                    </div>
                                                    <div class="basis-2/5">
                                                        <div class="relative mb-4 flex flex-wrap items-stretch">
                                                            <span
                                                                class="flex items-center whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200">
                                                                до</span>
                                                            <input name="filters[{{ $filter['name'] }}][max]"
                                                                step="0.1" type="{{ $filter['type'] }}"
                                                                min="{{ $filter['min'] }}" max="{{ $filter['max'] }}"
                                                                value="{{ $filter['maxChecked'] }}"
                                                                class="date-default relative m-0 block w-[1px] min-w-0 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary">
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($filter['type'] == 'select')
                                                <div class="flex flex-row gap-1 w-100">
                                                    <div class="basis-1/5">
                                                        <p>
                                                            {{ $filter['name_rus'] }}
                                                        </p>
                                                    </div>
                                                    <div class="basis-4/5">
                                                        <select
                                                            class="select-default border border-solid border-neutral-300 rounded w-full py-2 mb-4"
                                                            name="filters[{{ $filter['name'] }}]">
                                                            @foreach ($filter['values'] as $value)
                                                                <option
                                                                    @if ($value['value'] == $filter['checked_value']) selected @endif
                                                                    value="{{ $value['value'] }} ">
                                                                    {{ $value['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif($filter['type'] == 'checkbox')
                                                <div class="flex flex-row gap-1 w-100">
                                                    <div class="basis-1/5">
                                                        <p>
                                                            {{ $filter['name_rus'] }}
                                                        </p>
                                                    </div>
                                                    <div class="basis-4/5 flex flex-wrap">
                                                        @foreach ($filter['values'] as $value)
                                                            <div class="basis-1/3 text-left">
                                                                <input name="{{ $filter['name'] }}[]"
                                                                    @if ($value['checked'] == true) checked=checked @endif
                                                                    class="border border-solid border-neutral-300 rounded"
                                                                    type="checkbox" value="{{ $value['value'] }}" />
                                                                <p
                                                                    class="inline-block ps-[0.15rem] hover:cursor-pointer">
                                                                    {{ $value['name'] }}
                                                                </p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                            <div class="mt-4 flex justify-end">
                                                <button type="submit"
                                                        class="rounded bg-blue-600 border-2 border-blue-600 px-4 py-1 text-md font-medium leading-normal text-white hover:bg-blue-700">
                                                    поиск
                                                </button>
                                                <button id="reset-button" type="button"
                                                        class="ml-2 rounded bg-slate-300 border-2 border-slate-300 px-4 py-1 text-md font-medium leading-normal text-white hover:bg-slate-400">
                                                    Сбросить
                                                </button>
                                            </div>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function(event) {

                                document.getElementById('reset-button').addEventListener('click', function() {


                                    const dateInputs = document.querySelectorAll('.date-default');
                                    dateInputs.forEach(dateInput => {
                                        dateInput.value = '';
                                    });

                                    const selects = document.querySelectorAll('.select-default');
                                    selects.forEach(select => {
                                        if (select.options.length > 0) {
                                            select.selectedIndex = 0;
                                        }
                                    });

                                });

                            });
                        </script>
                    </form>

                    {{-- Nav --}}
                    <div class="flex flex-row gap-1">
                        <div>
                            @if (url()->current() == route('processings.index'))
                                <a href="{{ route('processings.index') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Процессы</a>
                            @else
                                <a href="{{ route('processings.index') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Процессы</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('processings.products'))
                                <a href="{{ route('processings.products') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Продукты</a>
                            @else
                                <a href="{{ route('processings.products') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Продукты</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('processings.materials'))
                                <a href="{{ route('processings.materials') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Материалы</a>
                            @else
                                <a href="{{ route('processings.materials') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                    Материалы</a>
                            @endif
                        </div>
                    </div>

                    @if (isset($urlCreate) && $urlCreate != '')
                        <div class="flex px-3 text-center font-bold">
                            <a href="{{ route($urlCreate) }}"
                                class="inline-flex items-center rounded bg-green-400 px-3 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                {{ __('label.create') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-3 py-3"></th>
                            @foreach ($resColumns as $key => $column)
                                @if ($key === 'remainder' || $key == 'positions_count')
                                    <th scope="col" class="px-3 py-3">{{ $column }}</th>
                                @elseif(isset($orderBy) && $orderBy == 'desc')
                                    <th scope="col" class="px-3 py-3">
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                            &#9650;
                                        @endif
                                    </th>
                                @else
                                    <th scope="col" class="px-3 py-3">
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'asc')
                                            &#9660;
                                        @endif
                                    </th>
                                @endif
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalQuantity = 0;
                            $totalSum = 0;
                            $totalHours = 0;
                            $totalCycles = 0;
                            $totalDefective = 0;
                        @endphp

                        @foreach ($entityItems as $entityItem)
                            @php
                                $totalQuantity += $entityItem->quantity;
                                $totalSum += $entityItem->sum;
                                $totalHours += $entityItem->hours;
                                $totalCycles += $entityItem->cycles;
                                $totalDefective += $entityItem->defective;
                            @endphp

                            <tr class="border-b-2">
                                @if (count($entityItem->products) > 0)
                                    <td class="text-nowrap px-3 py-3">
                                        <button class="buttonForOpen text-normal font-bold"
                                            data-id="{!! $entityItem->id !!}">+</button>
                                    </td>
                                @else
                                    <td class="text-nowrap px-3 py-3">
                                    </td>
                                @endif

                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-[20rem] truncate px-3 py-3"
                                    @if ((is_int($entityItem->$column) ||
                                            $column == 'quantity' ||
                                            $column == 'sum' ||
                                            $column == 'moment' ||
                                            $column == 'created_at') &&
                                            !preg_match('/_id\z/u', $column) &&
                                            $column !== 'sostav') style="text-align:right" @else style="text-align:left" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                        @if ($column == 'name' || $column == 'id')
                                            <a href="{{ route($urlShow, $entityItem->id) }}"
                                                class="text-blue-500 hover:text-blue-600">
                                                {{ $entityItem->$column }}
                                            </a>
                                        @elseif($column == 'tech_chart_id')
                                            @if ($entityItem->tech_chart)
                                                <a href="{{ route('techcharts.show', $entityItem->id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $entityItem->tech_chart->name }}
                                                </a>
                                            @else
                                                не указано
                                            @endif
                                        @elseif($column == 'ms_link' && $entityItem->ms_id)
                                            <a href="https://online.moysklad.ru/app/#processing/edit?id={{ $entityItem->ms_id }}"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-box-arrow-in-up-right"
                                                    viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @else
                                            @if($column == 'sum')
                                                {{ number_format((int) $entityItem->$column, 0, ',', ' ') }}
                                            @else
                                                {{ $entityItem->$column }}
                                            @endif
                                        @endif
                                    </td>
                                @endforeach


                            </tr>

                            @foreach ($entityItem->products as $product)
                                <tr style="display: none"
                                    class="border-b-2 bg-green-100 position_column_{!! $entityItem->id !!}">
                                    <td class="text-nowrap px-3 py-3">
                                    </td>
                                    <td class="text-nowrap px-3 py-3">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3 text-right">
                                        <a href="{{ route('processings.show', $entityItem->id) }}"
                                            class="text-blue-500 hover:text-blue-600">
                                            {{ $product->id }}
                                        </a>
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3" colspan="3">
                                        <a href="{{ route('product.show', ['product' => $product->id]) }}"
                                            class="text-blue-500 hover:text-blue-600">
                                            {{ $product->name }}
                                        </a>
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3 text-right">
                                        {{ $product->pivot->quantity }}
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3 text-right">
                                        {{ $product->pivot->sum }}
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3" colspan="4">
                                        {{ $entityItem->ms_id }}
                                    </td>

                                </tr>
                            @endforeach

                            @foreach ($entityItem->materials as $product)
                                <tr style="display: none"
                                    class="border-b-2 bg-red-100 position_column_{!! $entityItem->id !!}">
                                    <td class="text-nowrap px-3 py-3">
                                    </td>
                                    <td class="text-nowrap px-3 py-3">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3 text-right">
                                        <a href="{{ route('processings.show', $entityItem->id) }}"
                                            class="text-blue-500 hover:text-blue-600">
                                            {{ $product->id }}
                                        </a>
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3" colspan="3">
                                        <a href="{{ route('product.show', ['product' => $product->id]) }}"
                                            class="text-blue-500 hover:text-blue-600">
                                            {{ $product->name }}
                                        </a>
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3 text-right">
                                        {{ $product->pivot->quantity }}
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3 text-right">
                                        {{ $product->pivot->sum }}
                                    </td>

                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-3 py-3" colspan="4">
                                        {{ $entityItem->ms_id }}
                                    </td>

                                </tr>
                            @endforeach
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">
                            <td>
                            </td>
                            @foreach ($resColumns as $column => $title)
                                @if ($column == 'quantity')
                                    <td class="px-3 py-3 text-right">
                                        {{ $totalQuantity }}
                                    </td>
                                @elseif($column == 'hours')
                                    <td class="overflow-auto px-3 py-3 text-right">
                                        {{ $totalHours }}
                                    </td>
                                @elseif($column == 'cycles')
                                    <td class="overflow-auto px-3 py-3 text-right">
                                        {{ $totalCycles }}
                                    </td>
                                @elseif($column == 'defective')
                                    <td class="overflow-auto px-3 py-3 text-right">
                                        {{ $totalDefective }}
                                    </td>
                                @elseif($column == 'sum')
                                    <td class="overflow-auto px-3 py-3 text-right">
                                        {{ number_format((int) $totalSum, 0, ',', ' ') }}
                                    </td>
                                @else
                                    <td>
                                    </td>
                                @endif
                            @endforeach
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- footer card --}}
            <div class="border-t-2 border-neutral-100 px-3 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $entityItems->appends(request()->query())->links() }}
            </div>

        </div>
    </div>



</x-app-layout>
