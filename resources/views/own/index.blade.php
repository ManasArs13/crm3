<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8 max-w-10xl">

        @if (session('succes'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                {{ session('succes') }}
            </div>
        @endif

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
                                        class="inline-flex rounded border-2 border-blue-600 text-blue-600 px-4 py-2 text-md font-medium leading-normal hover:bg-blue-700 hover:text-white">
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
                                            <label>
                                                <input type="checkbox" id="change_all">
                                                Выбрать все
                                            </label>
                                        </div>
                                        @foreach ($resColumnsAll as $key => $column)
                                            <div class="flex basis-1/3">
                                                <label>
                                                    <input name="columns[]" class="columns_all" type="checkbox"
                                                        value="{{ $key }}"
                                                           id="checkbox-{{ $key }}"
                                                        @if ($column['checked'] == true) checked @endif>
                                                    {{ $column['name_rus'] }}
                                                </label>
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
                                        class="inline-flex rounded border-2 border-blue-600 text-blue-600 px-4 py-2 text-md font-medium leading-normal hover:bg-blue-700 hover:text-white">
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
                                                        <label>
                                                            {{ $filter['name_rus'] }}
                                                        </label>
                                                    </div>
                                                    <div class="basis-2/5">
                                                        <div class="relative mb-4 flex flex-wrap items-stretch">
                                                            <span
                                                                class="flex items-center whitespace-nowrap rounded-l border border-r-0 border-solid border-neutral-300 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700 dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200">
                                                                от</span>
                                                            <input name="filters[{{ $filter['name'] }}][min]"
                                                                step="0.1" type="{{ $filter['type'] }}"
                                                                min="{{ $filter['min'] }}" max="{{ $filter['max'] }}"
                                                                value="{{ $filter['minChecked'] == '' ? $filter['min'] : $filter['minChecked'] }}" data-default="{{ $filter['min'] }}"
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
                                                                value="{{ $filter['maxChecked'] == '' ? $filter['max'] : $filter['maxChecked'] }}" data-default="{{ $filter['max'] }}"
                                                                class="inp-default relative m-0 block w-[1px] min-w-0 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary">
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($filter['type'] == 'select')
                                                <div class="flex flex-row gap-1 w-100">
                                                    <div class="basis-1/5">
                                                        <label>
                                                            {{ $filter['name_rus'] }}
                                                        </label>
                                                    </div>
                                                    <div class="basis-4/5">
                                                        <select
                                                            class="select-default border border-solid border-neutral-300 rounded w-full py-2 mb-4"
                                                            , name="filters[{{ $filter['name'] }}]"
                                                            data-offset="false">
                                                            <option @if ($filter['checked_value'] == 'all') selected @endif
                                                                value="all">Все</option>
                                                            @foreach ($filter['values'] as $value)
                                                                <option
                                                                    @if ($value['category_id'] == $filter['checked_value']) selected @endif
                                                                    value="{{ $value['category_id'] }} ">
                                                                    {{ $value['name'] }}</option>
                                                            @endforeach
                                                        </select>
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


                                    const checkedCheckboxes = [{!! '"' . implode('", "', array_values($selectedColumns)) . '"' !!}];


                                    const allCheckboxes = document.querySelectorAll('.columns_all');
                                    allCheckboxes.forEach(checkbox => {
                                        checkbox.checked = false;
                                    });


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
                                        dateInput.value = dateInput.getAttribute('data-default');
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
                    @if (isset($urlCreate) && $urlCreate != '')
                        <div class="flex px-3 text-center font-bold">
                            <a href="{{ route($urlCreate) }}"
                                class="inline-flex items-center rounded bg-green-400 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                {{ __('label.create') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            @foreach ($resColumns as $key => $column)
                                @if ($key === 'remainder')
                                    <th scope="col" class="px-6 py-2">{{ $column }}</th>
                                @elseif(isset($orderBy) && $orderBy == 'desc')
                                    <th scope="col" class="px-6 py-2">
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                            &#9650;
                                        @endif
                                    </th>
                                @else
                                    <th scope="col" class="px-6 py-2">
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'asc')
                                            &#9660;
                                        @endif
                                    </th>
                                @endif
                            @endforeach
                            @if (isset($needMenuForItem) && $needMenuForItem)
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entityItems as $entityItem)
                            <tr class="border-b-2">
                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-60 xl:max-w-44 overflow-hidden px-6 py-2"
                                        @if (is_int($entityItem->$column) ||
                                                $column == 'weight_kg' ||
                                                $column == 'category_id' ||
                                                preg_match('/_id\z/u', $column)) style="text-align:right" @else style="text-align:left" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                                        @if (preg_match('/_id\z/u', $column))
                                            @if ($column == 'contact_id')
                                                {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                            @else
                                                {{ $entityItem->$column }}
                                            @endif
                                        @elseif($column == 'status')
                                            @switch($entityItem->$column)
                                                @case('[DN] Подтвержден')
                                                    <div id="status" class="border border-green-500 bg-green-400">
                                                        <span>{{ $entityItem->$column->name }}</span>
                                                    </div>
                                                @break

                                                @case('На брони')
                                                    <div id="status" class="border border-purple-500 bg-purple-400">
                                                        <span>{{ $entityItem->$column->name }}</span>
                                                    </div>
                                                @break

                                                @case('[C] Отменен')
                                                    <div id="status" class="border border-red-500 bg-red-400">
                                                        <span>{{ $entityItem->$column->name }}</span>
                                                    </div>
                                                @break

                                                @case('Думают')
                                                    <div id="status" class="border border-blue-500 bg-blue-400">
                                                        <span>{{ $entityItem->$column->name }}</span>
                                                    </div>
                                                @break

                                                @case('[DD] Отгружен с долгом')
                                                    <div id="status" class="border border-orange-500 bg-orange-400">
                                                        <span>{{ $entityItem->$column->name }}</span>
                                                    </div>
                                                @break

                                                @case('[DF] Отгружен и закрыт')
                                                    <div id="status" class="border border-green-500 bg-green-400">
                                                        <span>{{ $entityItem->$column->name }}</span>
                                                    </div>
                                                @break

                                                @case('[N] Новый')
                                                    <div id="status" class="border border-yellow-500 bg-yellow-400">
                                                        <span>{{ $entityItem->$column->name }}</span>
                                                    </div>
                                                @break

                                                @default
                                                    {{ $entityItem->$column }}
                                            @endswitch
                                        @elseif($column == 'remainder')
                                            @if ($entityItem->residual_norm !== 0 && $entityItem->residual_norm !== null && $entityItem->type !== 'не выбрано')
                                                {{ round(($entityItem->residual / $entityItem->residual_norm) * 100) }}
                                                %
                                            @else
                                                {{ null }}
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
                                        @else
                                            @if($column == 'price' || $column == 'balance' || $column == 'unloading_price' || $column == 'min_price' || $column == 'ton_price')
                                                {{ number_format((int) $entityItem->$column, 0, ',', ' ') }}
                                            @else
                                                {{ $entityItem->$column }}
                                            @endif
                                        @endif
                                    </td>
                                @endforeach

                                @if (isset($needMenuForItem) && $needMenuForItem)
                                    <td class=" text-nowrap px-6 py-2 flex">

                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">

                                                        <div class="ms-1">
                                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                            </svg>
                                                        </div>
                                                    </button>
                                                </x-slot>

                                                <x-slot name="content">

                                                    <div class="py-1" role="none">
                                                        @if (isset($urlShow) && $urlShow != '')
                                                            <a href="{{ route($urlShow, $entityItem->id) }}" class="block px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 flex items-center space-x-2" role="menuitem" tabindex="-1" id="menu-item-{{ $entityItem->id }}-5">
                                                                <svg class="w-4 h-4 fill-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 513 305">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M240.41 0.488786C155.858 5.36079 76.056 50.6498 12.434 129.868C-4.17804 150.552 -4.16504 154.227 12.596 175.153C76.087 254.424 156.164 299.687 241.115 304.323C337.263 309.571 428.112 264.143 499.682 175.028C516.294 154.344 516.281 150.669 499.52 129.743C453.14 71.8368 397.961 32.0518 336.94 12.5218C317.984 6.45478 295.019 2.02179 277.058 0.960786C257.457 -0.196214 253.231 -0.250214 240.41 0.488786ZM277.209 47.0468C305.95 52.4568 333.568 72.0058 348.526 97.5288C366.578 128.33 368.482 165.588 353.614 197.071C347.811 209.357 342.267 217.348 332.716 227.191C279.261 282.278 188.187 265.436 157.185 194.73C145.997 169.214 145.786 138.045 156.622 111.448C167.164 85.5718 190.088 62.9148 216.127 52.6378C235.929 44.8228 255.751 43.0078 277.209 47.0468ZM245.558 95.4078C229.296 98.9248 214.902 109.069 206.659 122.823C200.599 132.934 198.566 140.441 198.636 152.448C198.705 164.034 199.736 168.707 204.367 178.422C208.623 187.348 221.052 199.738 230.165 204.139C262.256 219.635 299.582 203.873 311.131 169.948C314.435 160.243 314.527 145.028 311.343 134.906C304.074 111.805 282.858 95.7078 258.558 94.8568C253.608 94.6838 247.758 94.9318 245.558 95.4078Z" />
                                                                </svg>
                                                                <span>{{ __('label.view') }}</span>
                                                            </a>
                                                        @endif
                                                        @if (isset($urlEdit) && $urlEdit != '')
                                                            <a href="{{ route($urlEdit, $entityItem->id) }}" class="block px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 flex items-center space-x-2" role="menuitem" tabindex="-1" id="menu-item-{{ $entityItem->id }}-5">
                                                                <svg class="w-4 h-4 fill-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 511">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M408.68 1.82615C396.293 5.83515 393.092 8.27415 369.68 31.5402L347.18 53.8991L401.926 108.664L456.672 163.428L477.787 142.433C500.324 120.025 505.515 113.417 508.757 103.01C513.294 88.4511 512.255 75.0461 505.496 60.9381C501.647 52.9041 500.239 51.2542 479.802 30.8382C456.749 7.80815 452.803 4.91815 439.635 1.41315C431.93 -0.63685 415.617 -0.41985 408.68 1.82615ZM173.968 227.189L31.7598 369.438L15.5438 434.438C3.65184 482.102 -0.481161 500.194 0.0438393 502.274C0.942839 505.83 4.69484 509.653 8.13284 510.515C10.8938 511.208 135.804 480.916 140.68 478.371C142.055 477.653 206.743 413.474 284.431 335.75L425.682 194.433L370.929 139.687L316.175 84.9401L173.968 227.189Z" />
                                                                </svg>
                                                                <span>{{ __('label.edit') }}</span>
                                                            </a>
                                                        @endif
                                                    </div>

                                                    @if (isset($urlDelete) && $urlDelete != '')
                                                        <div class="py-1 border-t" role="none">
                                                            <form action="{{ route($urlDelete, $entityItem->id) }}" method="Post"
                                                                  class="block px-4 text-sm font-medium text-red-500 hover:bg-gray-100 cursor-pointer">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="w-full h-full py-2 flex items-center space-x-2">
                                                                    <svg class="w-4 h-4 fill-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    <span class="text-red-500">{{ __('label.delete') }}</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif

                                                </x-slot>
                                            </x-dropdown>


                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $entityItems->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</x-app-layout>
