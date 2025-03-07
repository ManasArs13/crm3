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
                                                                value="{{ $filter['minChecked'] == '' ? $filter['min'] : $filter['minChecked'] }}"
                                                                data-default="{{ $filter['min'] }}"
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
                                                                value="{{ $filter['maxChecked'] == '' ? $filter['max'] : $filter['maxChecked'] }}"
                                                                data-default="{{ $filter['max'] }}"
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
                                                            name="filters[{{ $filter['name'] }}]"
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
                                    // Список ID чекбоксов, которые нужно отметить
                                    const checkedCheckboxes = [{!! '"' . implode('", "', array_values($all_columns)) . '"' !!}];

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
                                        dateInput.value = dateInput.getAttribute('data-default');
                                    });

                                    const selects = document.querySelectorAll('.select-default');
                                    selects.forEach(select => {
                                        if (select.options.length > 0) {
                                            select.selectedIndex = 0;
                                        }
                                    });

                                    // const allCheckboxes = document.querySelectorAll('.columns_all2');
                                    // allCheckboxes.forEach(checkbox => {
                                    //     checkbox.checked = true;
                                    // });

                                });

                            });
                        </script>
                    </form>
                    @can('shipment_position_edit')
                        @if (isset($urlCreate) && $urlCreate != '')
                            <div class="flex px-3 text-center font-bold">
                                <a href="{{ route($urlCreate) }}"
                                   class="inline-flex items-center rounded bg-green-400 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                    {{ __('label.create') }}
                                </a>
                            </div>
                        @endif
                    @endcan
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            @foreach ($resColumns as $key => $column)
                                @php $key = $key != 'deviation_price' ? $key : 'price_norm' @endphp
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
                                <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPrice = $totals['total_price'];
                        @endphp
                        @foreach ($entityItems as $entityItem)
                            <tr class="border-b-2">
                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-60 xl:max-w-44 truncate px-6 py-2"
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                                        @if (preg_match('/_id\z/u', $column))
                                            @if ($column == 'contact_id')
                                                {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                            @elseif ($column == 'product_id')
                                                <a href="{{ route('product.show', $entityItem->product_id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $entityItem->product ? $entityItem->product->short_name : '-' }}
                                                </a>
                                            @elseif ($column == 'shipment_id')
                                                <a href="{{ route('shipment.show', $entityItem->shipment_id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $entityItem->shipment_id }}
                                                </a>
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
                                        @elseif($column == 'deviation_price')
                                            {{ $entityItem->price_norm }}
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
                                            @if($column == 'price')
                                                {{ number_format((int) $entityItem->$column, 0, ',', ' ') }}
                                            @else
                                                {{ $entityItem->$column }}
                                            @endif
                                        @endif
                                    </td>
                                @endforeach

                                    {{-- Management --}}
                                    <td class="text-nowrap px-6 py-2 flex">

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
                                            </x-slot>
                                        </x-dropdown>
                                    </td>

                            </tr>
                        @endforeach
                        <tr class="bg-neutral-200">
                            @foreach($resColumns as $key => $column)
                                @if($key == 'price')
                                    <td class="px-6 py-2">{{ number_format((int) $totalPrice, 0, ',', ' ') }}</td>
                                @else
                                    <td class="px-6 py-2"></td>
                                @endif
                            @endforeach
                            <th></th>
                        </tr>
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
