<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
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
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
        @endif
        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <form method="get" action="{{ route($urlFilter) }}" class="flex gap-1">
                        <div>
                            <x-dropdown align="left" width="64" outside='false'>
                                <x-slot name="trigger">
                                    <button type="button"
                                        class="inline-flex rounded border-2 border-blue-600 text-blue-600 px-4 py-1 text-md font-medium leading-normal hover:bg-blue-700 hover:text-white">
                                        столбцы
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
                                    <div class="grid grid-cols-3 w-100 p-4 gap-1">
                                        <div class="flex basis-1/3">
                                            <p>
                                                <input type="checkbox" id="change_all">
                                                Выбрать все
                                            </p>
                                        </div>
                                        @foreach ($resColumnsAll as $key => $column)
                                            <div class="flex basis-1/3">
                                                <p>
                                                    <input name="columns[]" class="columns_all" type="checkbox"
                                                        value="{{ $key }}"
                                                           id="checkbox-{{ $key }}"
                                                        @if ($column['checked'] == true) checked @endif>
                                                    {{ $column['name_rus'] }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 flex justify-start mb-4 ml-4">
                                        <button type="submit"
                                                class="rounded bg-blue-600 border-2 border-blue-600 px-4 py-1 text-md font-medium leading-normal text-white hover:bg-blue-700">
                                            поиск
                                        </button>
                                        <button type="button" id="reset-button"
                                                class="ml-2 rounded bg-slate-300 border-2 border-slate-300 px-4 py-1 text-md font-medium leading-normal text-white hover:bg-slate-400">
                                            Сбросить
                                        </button>
                                    </div>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function(event) {
                                            var checkboxAll = document.querySelector("#change_all");
                                            checkboxAll.addEventListener('change', function() {
                                                let inputs = document.querySelectorAll(".columns_all")

                                                if (this.checked) {
                                                    inputs.forEach(element => {
                                                        element.checked = true
                                                    });
                                                } else {
                                                    inputs.forEach(element => {
                                                        element.checked = false
                                                    });
                                                }
                                            });

                                        });
                                    </script>
                                </x-slot>
                            </x-dropdown>
                        </div>
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
                                                                @if ($filter['name'] == 'sum') placeholder="{{ $filter['min'] }}" @endif
                                                                value="{{ $filter['minChecked'] }}"
                                                                data-default="{{ $filter['minChecked'] }}"
                                                                class="inp-default relative m-0 block w-[1px] min-w-0 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary">
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
                                                                @if ($filter['name'] == 'sum') placeholder="{{ $filter['max'] }}" @endif
                                                                value="{{ $filter['maxChecked'] }}"
                                                                data-default="{{ $filter['maxChecked'] }}"
                                                                class="inp-default relative m-0 block w-[1px] min-w-0 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary">
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
                                                <button id="reset-button2" type="button"
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

                                    // Список ID чекбоксов, которые нужно отметить
                                    const checkedCheckboxes = [{!! '"' . implode('", "', array_values($select)) . '"' !!}];

                                    // Сбрасываем все чекбоксы
                                    const allCheckboxes = document.querySelectorAll('.columns_all');
                                    allCheckboxes.forEach(checkbox => {
                                        checkbox.checked = false;
                                    });

                                    // Включаем нужные чекбоксы
                                    checkedCheckboxes.forEach(id => {
                                        const checkbox = document.getElementById(`checkbox-${id}`);
                                        if (checkbox) {
                                            checkbox.checked = true;
                                        }
                                    });

                                });

                                document.getElementById('reset-button2').addEventListener('click', function() {


                                    const dateInputs = document.querySelectorAll('.inp-default');
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

            </div>

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-2 py-2"></th>
                            @foreach ($resColumns as $key => $column)
                                @if ($key === 'remainder' || $key == 'positions_count')
                                    <th scope="col" class="px-2 py-2">{{ $column }}</th>
                                @elseif(isset($orderBy) && $orderBy == 'desc')
                                    <th scope="col" class="px-2 py-2"
                                        @if (
                                            $column == '№' ||
                                                $column == 'Дата создания' ||
                                                $column == 'Дата обновления' ||
                                                $column == 'Имя' ||
                                                $column == 'Фактическая дата' ||
                                                $column == 'Сумма') style="text-align: right" @endif>
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                            &#9650;
                                        @endif
                                    </th>
                                @else
                                    <th scope="col" class="px-2 py-2"
                                        @if (
                                            $column == '№' ||
                                                $column == 'Дата создания' ||
                                                $column == 'Дата обновления' ||
                                                $column == 'Имя' ||
                                                $column == 'Фактическая дата' ||
                                                $column == 'Сумма') style="text-align: right" @endif>
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
                            $totalSum = $totals['total_sum'];
                        @endphp
                        @foreach ($entityItems as $entityItem)
                            <tr class="border-b-2">

                                @if (count($entityItem->positions) > 0)
                                    <td class="text-nowrap px-2 py-2">
                                        <button class="buttonForOpen text-normal font-bold"
                                            data-id="{!! $entityItem->id !!}">+</button>
                                    </td>
                                @else
                                    <td class="text-nowrap px-2 py-2">
                                    </td>
                                @endif

                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-96 truncate px-2 py-2"
                                        @if (
                                            $column == 'id' ||
                                                $column == 'created_at' ||
                                                $column == 'updated_at' ||
                                                $column == 'name' ||
                                                $column == 'moment' ||
                                                $column == 'sum' ||
                                                $column == 'incoming_number' ||
                                                $column == 'incoming_date') style="text-align: right" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                                        @if (preg_match('/_id\z/u', $column))
                                            @if ($column == 'contact_id')
                                                {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                            @endif
                                        @elseif(preg_match('/_link/u', $column) && $entityItem->$column !== null && $entityItem->$column !== '')
                                            <a href="{{ $entityItem->$column }}" target="_blank">
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
                                        @elseif($column == 'name' || $column == 'id')
                                            <a href="{{ route($urlShow, $entityItem->id) }}"
                                                class="text-blue-500 hover:text-blue-600">
                                                {{ $entityItem->$column }}
                                            </a>
                                        @elseif($column == 'sum')
                                            {{ number_format((int) $entityItem->$column, 0, ',', ' ') }}
                                        @else
                                            {{ $entityItem->$column }}
                                        @endif
                                    </td>
                                @endforeach

                            </tr>

                            @foreach ($entityItem->positions as $product)
                                <tr style="display: none"
                                    class="border-b-2 bg-gray-100 position_column_{!! $entityItem->id !!}">
                                    <td class="text-nowrap px-3 py-2">
                                        {{ $loop->iteration }}
                                    </td>
                                    @foreach ($resColumns as $column => $title)
                                        <td class="break-all max-w-60 xl:max-w-44 truncate px-2 py-2"
                                            style="text-align: right">
                                            @if ($column == 'created_at' || $column == 'updated_at')
                                                {{ $entityItem->$column }}
                                            @elseif($column == 'name')
                                                {{ $product->product->name }}
                                            @elseif($column == 'contact_id')
                                                {{ $product->name }}
                                            @elseif($column == 'sum')
                                                {{ $product->price }} {{ '*' }} {{ $product->quantity }} = {{ $product->sum }}
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="text-nowrap px-3 py-2">
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                            <tr class="bg-neutral-100">
                                <td class="px-2 py-2"></td>
                                @foreach($resColumns as $column => $title)
                                    @if($column == 'sum')
                                        <td class="px-2 py-2 text-right">{{ number_format((int) $totalSum, 0, ',', ' ') }}</td>
                                    @else
                                        <td class="px-2 py-2"></td>
                                    @endif
                                @endforeach
                                    <td class="px-2 py-2"></td>
                            </tr>
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $entityItems->appends(request()->query())->links() }}
            </div>

        </div>
</x-app-layout>
