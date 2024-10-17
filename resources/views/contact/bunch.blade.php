<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entity }}
            </x-slot>
            @endif


            <div class="w-11/12 mx-auto py-8 max-w-10xl">

                @if (session('succes'))
                    <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                        {{ session('succes') }}
                    </div>
                @endif

                @if (isset($entity) && $entity != '')
                    <h3 class="text-4xl font-bold mb-6">{{ $entity }}</h3>
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
                                                                           value="{{ $filter['minChecked'] == '' ? $filter['max'] : $filter['minChecked'] }}"
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
                        </div>
                    </div>

                    {{-- body --}}
                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                @foreach ($resColumns as $key => $column)
                                    @if ($key === 'remainder')
                                        <th scope="col" class="px-2 py-2 text-left">{{ $column }}</th>
                                    @elseif(isset($orderBy) && $orderBy == 'desc')
                                        <th scope="col" class="px-2 py-2 text-left">
                                            <a class="text-black"
                                               href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                            @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                                &#9650;
                                            @endif
                                        </th>
                                    @else
                                        <th scope="col" class="px-2 py-2 text-left">
                                            <a class="text-black"
                                               href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                            @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'asc')
                                                &#9660;
                                            @endif
                                        </th>
                                    @endif
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($entityItems as $entityItem)
                                <tr class="border-b-2">
                                    @foreach ($resColumns as $column => $title)
                                        <td class="break-all max-w-64 overflow-auto px-2 py-2 truncate"
                                            @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                                            @if($column == 'created_at')
                                                {{ $entityItem->created_at ?? '' }}
                                            @elseif($column == 'contact_id')

                                                @if(isset($entityItem->AmoContact->contact_ms_link))
                                                    <a href="{{ $entityItem->AmoContact->contact_ms_link }}" target="_blank" class="text-blue-700">
                                                        {{ $entityItem->contact->name ?? '' }}
                                                    </a>
                                                @else
                                                    {{ $entityItem->contact->name ?? '' }}
                                                @endif

                                            @elseif($column == 'contact_amo_id')

                                                @if(isset($entityItem->contact->contact_amo_link))
                                                    <a href="{{ $entityItem->contact->contact_amo_link }}" target="_blank" class="text-blue-700">
                                                        {{ $entityItem->AmoContact->name ?? '' }}
                                                    </a>
                                                @else
                                                    {{ $entityItem->AmoContact->name ?? '' }}
                                                @endif

                                            @elseif($column == 'created_at_ms')
                                                {{ $entityItem->contact->created_at ?? '' }}
                                            @elseif($column == 'created_at_amo')
                                                {{ $entityItem->AmoContact->created_at ?? '' }}
                                            @elseif($column == 'shipment_id_ms')
                                                {{ isset($entityItem->contact->shipments) ? number_format((int) $entityItem->contact->shipments->sum('products_sum_quantity_price'), 0, ',', ' ') : '-' }}
                                            @elseif($column == 'shipment_id_amo')
                                                {{ isset($entityItem->AmoContact->amo_order) ? number_format((int) $entityItem->AmoContact->amo_order->sum('price'), 0, ',', ' ') : '-' }}
                                            @elseif($column == 'manager_id_ms')
                                                {{ $entityItem->contact->manager->name ?? '' }}
                                            @elseif($column == 'manager_id_amo')
                                                {{ $entityItem->contact->manager->name ?? '' }}
                                            @endif
                                        </td>
                                    @endforeach

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
