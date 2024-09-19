<x-app-layout>

    @if (isset($entity) && $entity != '')
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

        @if (isset($entity) && $entity != '')
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
                                                                value="{{ $filter['minChecked'] == '' && $filter['name'] == 'date_plan' ? date("Y-m-d") : $filter['minChecked'] }}"
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
                                                                value="{{ $filter['maxChecked'] == '' && $filter['name'] == 'date_plan' ? date("Y-m-d") : $filter['maxChecked'] }}"
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

                                    const allCheckboxes = document.querySelectorAll('.columns_all2');
                                    allCheckboxes.forEach(checkbox => {
                                        checkbox.checked = true;
                                    });

                                });

                            });
                        </script>
                    </form>







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
                            <th scope="col" class="px-2 py-2"></th>
                            @foreach ($resColumns as $key => $column)
                                @if ($key === 'remainder' || $key == 'positions_count')
                                    <th scope="col" class="px-2 py-2">{{ $column }}</th>
                                @elseif(isset($orderBy) && $orderBy == 'desc')
                                    <th scope="col" class="px-2 py-2"
                                    @if (
                                        (   $column == '№' ||
                                            $column == 'Сумма оплачено' ||
                                            $column == 'Имя' ||
                                            $column == 'Сумма' ||
                                            $column == 'Кол-во' ||
                                            $column == 'Остаток' ||
                                            $column == 'Отг-но' ||
                                            $column == 'Цена доставки' ||
                                            $column == 'Сумма зарезервировано' ||
                                            $column == 'Вес' ||
                                            $column == 'Дата документа' ||
                                            $column == 'Плановая дата' ||
                                            $column == 'Сумма отгружено' ||
                                            $column == 'Дата создания' ||
                                            $column == 'Дата обновления' ||
                                            $column == 'Долг' || is_int($column)) &&
                                            !preg_match('/_id\z/u', $column) &&
                                            $column !== 'sostav') style="text-align:right"
                                    @elseif($column == 'Статус') style='text-align:center'
                                    @else style="text-align:left" @endif>
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                            &#9650;
                                        @endif
                                    </th>
                                @else
                                    <th scope="col" class="px-2 py-2"
                                        @if (
                                            (   $column == '№' ||
                                                $column == 'Сумма оплачено' ||
                                                $column == 'Имя' ||
                                                $column == 'Сумма' ||
                                                $column == 'Кол-во' ||
                                                $column == 'Остаток' ||
                                                $column == 'Отг-но' ||
                                                $column == 'Цена доставки' ||
                                                $column == 'Сумма зарезервировано' ||
                                                $column == 'Вес' ||
                                                $column == 'Дата документа' ||
                                                $column == 'Плановая дата' ||
                                                $column == 'Сумма отгружено' ||
                                                $column == 'Дата создания' ||
                                                $column == 'Дата обновления' ||
                                                $column == 'Долг' || is_int($column)) &&
                                                !preg_match('/_id\z/u', $column) &&
                                                $column !== 'sostav') style="text-align:right"
                                        @elseif($column == 'Статус') style='text-align:center'
                                        @else style="text-align:left" @endif>
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
                            $totalSum = 0;
                            $totalCount = 0;
                            $totalShipped = 0;
                            $totalDeliveryPrice = 0;
                            $totalPayedSum = 0;
                            $totalShippedSum = 0;
                            $totalReservedSum = 0;
                            $totalDebt = 0;
                        @endphp

                        @foreach ($entityItems as $entityItem)
                            @php
                                $totalSum += $entityItem->sum;
                            @endphp

                            @php
                                $totalReservedSum += $entityItem->reserved_sum;
                            @endphp

                            @php
                                $totalDeliveryPrice += $entityItem->delivery_price;
                            @endphp

                            @php
                                $totalShippedSum += $entityItem->shipped_sum;
                            @endphp

                            @php
                                $totalPayedSum += $entityItem->payed_sum;
                            @endphp

                            @php
                                $totalDebt += $entityItem->debt;
                            @endphp


                            @php
                                $total_quantity = 0;
                                $total_shipped_count = 0;
                            @endphp

                            @foreach ($entityItem->positions as $position)
                                @php
                                    if (
                                        $position->product->building_material !== 'доставка' &&
                                        $position->product->building_material !== 'не выбрано'
                                    ) {
                                        $total_quantity += $position->quantity;
                                        $totalCount += $position->quantity;
                                    }
                                @endphp
                            @endforeach

                            @foreach ($entityItem->shipments as $shipment)
                                @foreach ($shipment->products as $position)
                                    @php
                                        $total_shipped_count += $position->quantity;
                                        $totalShipped += $position->quantity;
                                    @endphp
                                @endforeach
                            @endforeach

                            <tr class="border-b-2">
                                @if (count($entityItem->shipments) > 0)
                                    <td class="text-nowrap px-2 py-2">
                                        <button class="buttonForOpen text-normal font-bold"
                                            data-id="{!! $entityItem->id !!}">+</button>
                                    </td>
                                @else
                                    <td class="text-nowrap px-2 py-2">
                                    </td>
                                @endif

                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-96 overflow-auto px-2 py-2"
                                        @if (
                                            (is_int($entityItem->$column) ||
                                                $column == 'id' ||
                                                $column == 'payed_sum' ||
                                                $column == 'name' ||
                                                $column == 'sum' ||
                                                $column == 'positions_count' ||
                                                $column == 'residual_count' ||
                                                $column == 'shipped_count' ||
                                                $column == 'shipped_sum' ||
                                                $column == 'reserved_sum' ||
                                                $column == 'weight' ||
                                                $column == 'date_moment' ||
                                                $column == 'date_plan' ||
                                                $column == 'date_fact' ||
                                                $column == 'delivery_price_norm' ||
                                                $column == 'created_at' ||
                                                $column == 'updated_at' ||
                                                $column == 'debt') &&
                                                !preg_match('/_id\z/u', $column) &&
                                                $column !== 'sostav') style="text-align:right"
                                        @elseif($column == 'status') style='text-align:center'
                                        @else style="text-align:left" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                                        @if (preg_match('/_id\z/u', $column))
                                            @if ($column == 'contact_id')
                                                {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                            @elseif($column == 'delivery_id')
                                                {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                            @elseif($column == 'transport_type_id')
                                                {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                            @elseif($column == 'status_id')
                                                @switch($entityItem->$column)
                                                    @case(1)
                                                        <div id="status" class=" px-2 py-1 text-center">
                                                            <span>[N] Новый</span>
                                                        </div>
                                                    @break

                                                    @case(2)
                                                        <div id="status"
                                                            class=" px-2 py-1 text-center">
                                                            <span>Думают</span>
                                                        </div>
                                                    @break

                                                    @case(3)
                                                        <div id="status"
                                                            class=" px-2 py-1 text-center">
                                                            <span>[DN] Подтвержден</span>
                                                        </div>
                                                    @break

                                                    @case(4)
                                                        <div id="status"
                                                            class=" px-2 py-1 text-center">
                                                            <span>На брони</span>
                                                        </div>
                                                    @break

                                                    @case(5)
                                                        <div id="status"
                                                            class=" px-2 py-1 text-center">
                                                            <span>[DD] Отгружен с долгом</span>
                                                        </div>
                                                    @break

                                                    @case(6)
                                                        <div id="status"
                                                            class=" px-2 py-1 text-center">
                                                            <span>[DF] Отгружен и закрыт</span>
                                                        </div>
                                                    @break

                                                    @case(7)
                                                        <div id="status"
                                                            class=" px-2 py-1 text-center">
                                                            <span>[C] Отменен</span>
                                                        </div>
                                                    @break

                                                    @default
                                                        -
                                                @endswitch
                                            @endif
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
                                        @elseif($column == 'name' || $column == 'id')
                                            <a href="{{ route($urlShow, $entityItem->id) }}"
                                                class="text-blue-500 hover:text-blue-600">
                                                {{ $entityItem->$column }}
                                            </a>
                                        @elseif($column == 'positions_count')
                                            {{ $total_quantity }}
                                        @elseif($column == 'shipped_count')
                                            {{ $total_shipped_count }}
                                        @elseif($column == 'residual_count')
                                            {{ $total_quantity - $total_shipped_count >= 0 ? $total_quantity - $total_shipped_count : 0 }}
                                        @elseif($column == 'ms_link' && $entityItem->ms_id)
                                            <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}"
                                                class="flex justify-center" target="_blank">
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
                                        @elseif($column == 'sostav')
                                            @if (isset($entityItem->positions[0]) && isset($entityItem->positions[0]->product))
                                                {{ $entityItem->positions[0]->product->building_material == 'бетон' ? $entityItem->positions[0]->product->short_name : '-' }}
                                            @else
                                                -
                                            @endif
                                        @else
                                            @if($column == 'sum' || $column == 'payed_sum' || $column == 'shipped_sum' || $column == 'reserved_sum' || $column == 'delivery_price' || $column == 'debt')
                                                {{ number_format((int) $entityItem->$column, 0, ',', ' ') }}
                                            @else
                                                {{ $entityItem->$column }}
                                            @endif
                                        @endif
                                    </td>
                                @endforeach

                                {{-- Management --}}
                                <td class="text-nowrap px-2 py-2 flex">
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
                                                <a href="#" class="block px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 flex items-center space-x-2" role="menuitem" tabindex="-1" id="menu-item-{{ $entityItem->id }}-5">
                                                    <svg class="w-4 h-4 fill-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M148.711 1.19301C134.219 4.10901 121.956 10.742 110.804 21.695C94.0776 38.125 87.9626 55.931 87.8806 88.445L87.8546 98.695H255.512H423.17L422.541 81.445C422.09 69.075 421.336 61.948 419.875 56.253C414.384 34.84 400.277 17.495 380.355 7.66301C364.277 -0.271986 372.629 0.248014 257.855 0.041014C175.697 -0.107986 154.037 0.121014 148.711 1.19301ZM60.7106 130.199C46.2386 133.098 33.9606 139.737 22.8036 150.695C9.33359 163.926 2.6016 178.707 0.732596 199.152C0.0925957 206.156 -0.152404 234.197 0.0945956 272.35C0.481596 332.234 0.563596 334.447 2.6826 342.137C8.7146 364.026 22.5246 380.94 42.3546 390.727C53.5136 396.234 60.3226 397.81 75.6046 398.424L88.8546 398.956V358.825V318.695L82.6046 318.693C78.6506 318.692 75.0766 318.05 72.8746 316.943C62.1696 311.565 62.1696 295.825 72.8746 290.447C76.1486 288.801 86.9326 288.698 255.355 288.698C423.777 288.698 434.561 288.801 437.835 290.447C448.54 295.825 448.54 311.565 437.835 316.943C435.633 318.05 432.059 318.692 428.105 318.693L421.855 318.695V358.823V398.951L435.605 398.426C451.407 397.822 458.048 396.307 469.355 390.727C489.158 380.954 502.872 364.176 509.073 342.137C511.29 334.259 511.308 333.626 511.308 263.695C511.308 193.764 511.29 193.131 509.073 185.253C502.872 163.214 489.158 146.436 469.355 136.663C452.827 128.506 472.577 129.22 258.355 129.034C102.839 128.899 66.1206 129.115 60.7106 130.199ZM133.835 194.447C138.831 196.956 141.855 201.953 141.855 207.695C141.855 213.437 138.831 218.434 133.835 220.943C130.779 222.479 127.067 222.692 103.355 222.692C79.6426 222.692 75.9306 222.479 72.8746 220.943C62.1696 215.565 62.1696 199.825 72.8746 194.447C75.9306 192.911 79.6426 192.698 103.355 192.698C127.067 192.698 130.779 192.911 133.835 194.447ZM118.063 403.445L118.355 488.195L120.737 493.465C123.885 500.431 131.912 507.764 138.837 510C143.641 511.552 153.498 511.695 255.355 511.695C357.212 511.695 367.069 511.552 371.873 510C378.798 507.764 386.825 500.431 389.973 493.465L392.355 488.195L392.647 403.445L392.94 318.695H255.355H117.77L118.063 403.445ZM301.835 354.447C312.54 359.825 312.54 375.565 301.835 380.943C298.683 382.527 294.299 382.692 255.355 382.692C207.414 382.692 207.762 382.738 203.125 375.797C198.368 368.676 201.155 358.325 208.875 354.447C212.027 352.863 216.411 352.698 255.355 352.698C294.299 352.698 298.683 352.863 301.835 354.447ZM301.835 418.447C312.54 423.825 312.54 439.565 301.835 444.943C298.683 446.527 294.299 446.692 255.355 446.692C207.414 446.692 207.762 446.738 203.125 439.797C198.368 432.676 201.155 422.325 208.875 418.447C212.027 416.863 216.411 416.698 255.355 416.698C294.299 416.698 298.683 416.863 301.835 418.447Z" />
                                                    </svg>
                                                    <span>Распечать заказ</span>
                                                </a>
                                                <a href="#" class="block px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 flex items-center space-x-2" role="menuitem" tabindex="-1" id="menu-item-{{ $entityItem->id }}-5">
                                                    <svg class="w-4 h-4 fill-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M148.711 1.19301C134.219 4.10901 121.956 10.742 110.804 21.695C94.0776 38.125 87.9626 55.931 87.8806 88.445L87.8546 98.695H255.512H423.17L422.541 81.445C422.09 69.075 421.336 61.948 419.875 56.253C414.384 34.84 400.277 17.495 380.355 7.66301C364.277 -0.271986 372.629 0.248014 257.855 0.041014C175.697 -0.107986 154.037 0.121014 148.711 1.19301ZM60.7106 130.199C46.2386 133.098 33.9606 139.737 22.8036 150.695C9.33359 163.926 2.6016 178.707 0.732596 199.152C0.0925957 206.156 -0.152404 234.197 0.0945956 272.35C0.481596 332.234 0.563596 334.447 2.6826 342.137C8.7146 364.026 22.5246 380.94 42.3546 390.727C53.5136 396.234 60.3226 397.81 75.6046 398.424L88.8546 398.956V358.825V318.695L82.6046 318.693C78.6506 318.692 75.0766 318.05 72.8746 316.943C62.1696 311.565 62.1696 295.825 72.8746 290.447C76.1486 288.801 86.9326 288.698 255.355 288.698C423.777 288.698 434.561 288.801 437.835 290.447C448.54 295.825 448.54 311.565 437.835 316.943C435.633 318.05 432.059 318.692 428.105 318.693L421.855 318.695V358.823V398.951L435.605 398.426C451.407 397.822 458.048 396.307 469.355 390.727C489.158 380.954 502.872 364.176 509.073 342.137C511.29 334.259 511.308 333.626 511.308 263.695C511.308 193.764 511.29 193.131 509.073 185.253C502.872 163.214 489.158 146.436 469.355 136.663C452.827 128.506 472.577 129.22 258.355 129.034C102.839 128.899 66.1206 129.115 60.7106 130.199ZM133.835 194.447C138.831 196.956 141.855 201.953 141.855 207.695C141.855 213.437 138.831 218.434 133.835 220.943C130.779 222.479 127.067 222.692 103.355 222.692C79.6426 222.692 75.9306 222.479 72.8746 220.943C62.1696 215.565 62.1696 199.825 72.8746 194.447C75.9306 192.911 79.6426 192.698 103.355 192.698C127.067 192.698 130.779 192.911 133.835 194.447ZM118.063 403.445L118.355 488.195L120.737 493.465C123.885 500.431 131.912 507.764 138.837 510C143.641 511.552 153.498 511.695 255.355 511.695C357.212 511.695 367.069 511.552 371.873 510C378.798 507.764 386.825 500.431 389.973 493.465L392.355 488.195L392.647 403.445L392.94 318.695H255.355H117.77L118.063 403.445ZM301.835 354.447C312.54 359.825 312.54 375.565 301.835 380.943C298.683 382.527 294.299 382.692 255.355 382.692C207.414 382.692 207.762 382.738 203.125 375.797C198.368 368.676 201.155 358.325 208.875 354.447C212.027 352.863 216.411 352.698 255.355 352.698C294.299 352.698 298.683 352.863 301.835 354.447ZM301.835 418.447C312.54 423.825 312.54 439.565 301.835 444.943C298.683 446.527 294.299 446.692 255.355 446.692C207.414 446.692 207.762 446.738 203.125 439.797C198.368 432.676 201.155 422.325 208.875 418.447C212.027 416.863 216.411 416.698 255.355 416.698C294.299 416.698 298.683 416.863 301.835 418.447Z" />
                                                    </svg>
                                                    <span>Распечать счет</span>
                                                </a>
                                            </div>
                                            <div class="py-1 border-t" role="none">
                                                <form action="{{ route($urlDelete, $entityItem->id) }}" method="Post"
                                                      class="block px-4 text-sm font-medium text-red-500 hover:bg-gray-100 cursor-pointer">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full h-full py-2 flex items-center space-x-2">
                                                        <svg class="w-4 h-4 fill-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="text-red-500">Удалить</span>
                                                    </button>
                                                </form>
                                            </div>

                                        </x-slot>
                                    </x-dropdown>
                                </td>

                            </tr>

                            @foreach ($entityItem->shipments as $shipment)
                                <tr style="display: none"
                                    class="border-b-2 bg-green-100 position_column_{!! $entityItem->id !!}">
                                    <td class="text-nowrap px-3 py-2">
                                        {{ $loop->iteration }}
                                    </td>
                                    @foreach ($resColumns as $column => $title)
                                        <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-2 py-2"
                                            @if (is_int($shipment->$column)) style="text-align:left" @else style="text-align:right" @endif
                                            @if ($shipment->$column) title="{{ $shipment->$column }}" @endif>
                                            @if (preg_match('/_id\z/u', $column))
                                                @if ($column == 'contact_id')
                                                @elseif($column == 'order_id')
                                                    <a href="{{ route('order.show', $shipment->id) }}"
                                                        class="text-blue-500 hover:text-blue-600">
                                                        {{ $shipment->order->name }}
                                                    </a>
                                                @elseif($column == 'delivery_id')
                                                    {{ $shipment->delivery ? $shipment->delivery->name : '-' }}
                                                @elseif($column == 'transport_id')
                                                    {{ $shipment->transport ? $shipment->transport->name : '-' }}
                                                @elseif($column == 'transport_type_id')
                                                    {{ $shipment->transport_type ? $shipment->transport_type->name : '-' }}
                                                @elseif($column == 'status_id')
                                                    {{ $shipment->status }}
                                                @else
                                                    {{ $shipment->$column }}
                                                @endif
                                            @elseif($column == 'remainder')
                                                @if ($shipment->residual_norm !== 0 && $shipment->residual_norm !== null && $shipment->type !== 'не выбрано')
                                                    {{ round(($shipment->residual / $shipment->residual_norm) * 100) }}
                                                    %
                                                @else
                                                    {{ null }}
                                                @endif
                                            @elseif(preg_match('/_link/u', $column) && $shipment->$column !== null && $shipment->$column !== '')
                                                <a href="{{ $shipment->$column }}" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                        </path>
                                                        <path fill-rule="evenodd"
                                                            d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @elseif($column == 'id')
                                                <a href="{{ route('shipment.show', $shipment->id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $shipment->id }}
                                                </a>
                                            @elseif($column == 'date_moment' || $column == 'date_plan')
                                                {{ $shipment->created_at }}
                                            @elseif($column == 'name')
                                                <a href="{{ route('shipment.show', $shipment->id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $shipment->name }}
                                                </a>
                                            @elseif($column == 'comment')
                                                {{ $shipment->description }}
                                            @elseif($column == 'positions_count')
                                                @php
                                                    $total_quantity_shipment = 0;
                                                @endphp

                                                @foreach ($shipment->products as $position)
                                                    @php
                                                        $total_quantity_shipment += $position->quantity;
                                                    @endphp
                                                @endforeach

                                                {{ $total_quantity_shipment }}
                                            @else
                                                {{ $shipment->$column }}
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="text-nowrap px-3 py-2">
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">
                            <td>
                            </td>
                            @foreach ($resColumns as $column => $title)
                                @if ($column == 'sum')
                                    <td class="px-2 py-2" style="text-align:right">
                                        {{ number_format((int) $totalSum, 0, '.', ' ') }}
                                    </td>
                                @elseif($column == 'positions_count')
                                    <td class="overflow-auto px-2 py-2" style="text-align:right">
                                        {{ $totalCount }}
                                    </td>
                                @elseif($column == 'shipped_count')
                                    <td class="overflow-auto px-2 py-2" style="text-align:right">
                                        {{ $totalShipped }}
                                    </td>
                                @elseif($column == 'residual_count')
                                    <td class="overflow-auto px-2 py-2" style="text-align:right">
                                        {{ $totalCount - $totalShipped }}
                                    </td>
                                @elseif($column == 'delivery_price')
                                    <td class="overflow-auto px-2 py-2" style="text-align:right">
                                        {{ number_format((int) $totalDeliveryPrice, 0, ',', ' ') }}
                                    </td>
                                @elseif($column == 'payed_sum')
                                    <td class="overflow-auto px-2 py-2" style="text-align:right">
                                        {{ number_format((int) $totalPayedSum, 0, ',', ' ') }}
                                    </td>
                                @elseif($column == 'shipped_sum')
                                    <td class="overflow-auto px-2 py-2" style="text-align:right">
                                        {{ number_format((int) $totalShippedSum, 0, ',', ' ') }}
                                    </td>
                                @elseif($column == 'reserved_sum')
                                    <td class="overflow-auto px-2 py-2" style="text-align:right">
                                        {{ number_format((int) $totalReservedSum, 0, ',', ' ') }}
                                    </td>
                                @elseif($column == 'debt')
                                    <td class="overflow-auto px-2 py-2" style="text-align:right">
                                        {{ number_format((int) $totalDebt, 0, ',', ' ') }}
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const buttons = document.querySelectorAll('[id^="menu-button-"]');

                buttons.forEach(button => {
                    button.addEventListener('click', function (event) {
                        const id = event.currentTarget.getAttribute('data-id');
                        const dropdownMenu = document.getElementById(`dropdown-menu-${id}`);


                        document.querySelectorAll('.dropdown-menu').forEach(menu => {
                            if (menu !== dropdownMenu) {
                                menu.classList.add('hidden');
                            }
                        });


                        dropdownMenu.classList.toggle('hidden');


                        const isExpanded = button.getAttribute('aria-expanded') === 'true';
                        button.setAttribute('aria-expanded', !isExpanded);
                    });
                });


                document.addEventListener('click', function (event) {
                    buttons.forEach(button => {
                        const id = button.getAttribute('data-id');
                        const dropdownMenu = document.getElementById(`dropdown-menu-${id}`);


                        if (!button.contains(event.target) && !dropdownMenu.contains(event.target)) {
                            dropdownMenu.classList.add('hidden');
                            button.setAttribute('aria-expanded', 'false');
                        }
                    });
                });
            });

        </script>



</x-app-layout>
