<x-app-layout>

    @if (isset($entityName) && $entityName != '')
        <x-slot:title>
            {{ $entityName }}
        </x-slot>
    @endif

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
                                                        value="{{ $key }}" id="checkbox-{{ $key }}"
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
                                                                @if ($filter['name'] == 'moment' && !request()->has('filters') && $filter['minChecked'] == '') value="{{ date('Y-m-d') }}"
                                                                @else
                                                                    value="{{ $filter['minChecked'] == '' ? $filter['min'] : $filter['minChecked'] }}" @endif
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
                                                                @if (
                                                                    $filter['name'] == 'moment' &&
                                                                        $filter['maxChecked'] == '' &&
                                                                        !request()->has('filters') &&
                                                                        $filter['name'] == 'moment') value="{{ date('Y-m-d') }}"
                                                                @else
                                                                    value="{{ $filter['maxChecked'] == '' ? $filter['max'] : $filter['maxChecked'] }}" @endif
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
                                            @elseif ($filter['type'] == 'select2')
                                                <div class="flex flex-row gap-1 w-100">
                                                    <div class="basis-1/5">
                                                        <p>
                                                            {{ $filter['name_rus'] }}
                                                        </p>
                                                    </div>
                                                    <div class="basis-4/5 mb-4">
                                                        <select class="contact change_name select-default2"
                                                            multiple="multiple" name="filters[{{ $filter['name'] }}][]"
                                                            id="contact_name" data-placeholder="Выберите контакт">
                                                            @foreach ($filter['values'] as $value)
                                                                <option value="{{ $value['value'] }}" selected>
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
                                                                    class="columns_all2 border border-solid border-neutral-300 rounded"
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
                    </form>

                </div>
            </div>

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">

                            <td class="break-all w-16 overflow-auto px-2 py-3">
                                №
                            </td>

                            @foreach ($resColumns as $key => $column)
                                @if ($column == 'Сумма')
                                    <th scope="col" class="px-2 py-3 text-right">
                                        Приход
                                    </th>
                                    <th scope="col" class="px-2 py-3 text-right">
                                        Расход
                                    </th>
                                @else
                                    @if ($orderBy == 'asc')
                                        <th scope="col" class="px-2 py-3"
                                            @if ($column == 'Операция' || $column == 'Комментарий' || $column == 'Контакт МС') style="text-align:left" @else style="text-align:right" @endif>
                                            <a class="text-black"
                                                href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                            @if (isset($selectColumn) && $selectColumn == $key)
                                                &#9660;
                                            @endif
                                        </th>
                                    @elseif($orderBy == 'desc')
                                        <th scope="col" class="px-2 py-3"
                                            @if ($column == 'Операция' || $column == 'Комментарий' || $column == 'Контакт МС') style="text-align:left" @else style="text-align:right" @endif>
                                            <a class="text-black"
                                                href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                            @if (isset($selectColumn) && $selectColumn == $key)
                                                &#9650;
                                            @endif
                                        </th>
                                    @else
                                        <th scope="col" class="px-2 py-3"
                                            @if ($column == 'Операция' || $column == 'Комментарий' || $column == 'Контакт МС') style="text-align:left" @else style="text-align:right" @endif>
                                            <a class="text-black"
                                                href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                            @if (isset($selectColumn) && $selectColumn == $key)
                                                &#9660;
                                            @endif
                                        </th>
                                    @endif
                                @endif




                            @endforeach

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalIn = 0;
                            $totalOut = 0;
                        @endphp

                        @foreach ($entityItems as $entityItem)
                            <tr class="border-b-2">

                                <td class="break-all overflow-auto px-2 py-3 text-sm">
                                    {{ $loop->iteration }}
                                </td>

                                @foreach ($resColumns as $column => $title)
                                    @switch($column)
                                        @case('created_at')
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @break

                                        @case('updated_at')
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @break

                                        @case('moment')
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @break

                                        @case('name')
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @break

                                        @case('contact_id')
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-left">
                                                <a href="https://online.moysklad.ru/app/#Company/edit?id={{ $entityItem->contact->ms_id }}"
                                                    target="_blank" class="text-blue-700 hover:text-blue-500 ">
                                                    {{ $entityItem->contact->name }}
                                                </a>
                                            </td>
                                        @break

                                        @case('operation')
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-left">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @break

                                        @case('sum')
                                            @if ($entityItem->type == 'входящий платеж' || $entityItem->type == 'приходный ордер')
                                                @php
                                                    $totalIn += $entityItem->$column;
                                                @endphp

                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    {{ number_format($entityItem->$column, 0, '.', ' ') }}
                                                </td>
                                                <td>
                                                </td>
                                            @else
                                                @php
                                                    $totalOut += $entityItem->$column;
                                                @endphp

                                                <td>
                                                </td>
                                                <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                                    {{ number_format($entityItem->$column, 0, '.', ' ') }}
                                                </td>
                                            @endif
                                        @break

                                        @default
                                            <td class="break-all max-w-96 truncate px-2 py-3 text-left">
                                                {{ $entityItem->$column }}
                                            </td>
                                    @endswitch
                                @endforeach

                            </tr>
                        @endforeach

                        <tr>
                            <td>
                            </td>
                            @foreach ($resColumns as $column => $title)
                                @if ($column == 'sum')
                                    <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                        {{ number_format($totalIn, 0, '.', ' ') }}
                                    </td>
                                    <td class="break-all max-w-96 truncate px-2 py-3 text-right">
                                        {{ number_format($totalOut, 0, '.', ' ') }}
                                    </td>
                                @else
                                    <td>
                                    </td>
                                @endif
                            @endforeach

                        </tr>

                        <tr class="border-b-2">
                            <td class="break-all max-w-96 truncate px-2 py-3" colspan="9">
                                {{ $entityItems->appends(request()->query())->links() }}

                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>



</x-app-layout>
