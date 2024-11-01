<x-app-layout>

    @if (isset($entity) && $entity != '')
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
            <h3 class="text-4xl font-bold mb-6">{{ __($entityName) }}</h3>
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

                                // document.getElementById('reset-button2').addEventListener('click', function() {
                                //
                                //
                                //     const dateInputs = document.querySelectorAll('.inp-default');
                                //     dateInputs.forEach(dateInput => {
                                //         dateInput.value = dateInput.getAttribute('data-default');
                                //     });
                                //
                                //     const selects = document.querySelectorAll('.select-default');
                                //     selects.forEach(select => {
                                //         if (select.options.length > 0) {
                                //             select.selectedIndex = 0;
                                //         }
                                //     });
                                //
                                // });

                            });
                        </script>
                    </form>
                    @can('transport_edit')
                        @if (isset($urlCreate) && $urlCreate != '')
                            <div class="flex px-3 text-center font-bold">
                                <a href="{{ route($urlCreate) }}"
                                    class="inline-flex items-center rounded bg-green-400 px-3 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                    {{ __('label.create') }}
                                </a>
                            </div>
                        @endif
                    @endcan
                </div>
            </div>

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            @foreach ($resColumns as $key => $column)
                                <th scope="col" class="px-2 py-2"
                                    @switch($column)
                                        @case('№')
                                            style="text-align:right"
                                        @break

                                        @case('Имя')
                                            style="text-align:left"
                                        @break

                                        @case('Комментарий')
                                            style="text-align:right"
                                        @break

                                        @case('Дата создания')
                                            style="text-align:right"
                                        @break

                                        @case('Дата обновления')
                                            style="text-align:right"
                                        @break

                                        @case('Тоннаж')
                                            style="text-align:right"
                                        @break

                                        @case('Контакт МС')
                                             style="text-align:left"
                                        @break

                                        @case('Uuid в МойСклад')
                                            style="text-align:right"
                                        @break

                                        @default
                                        style="text-align:right"
                                    @endswitch>
                                    @if (isset($orderBy) && $orderBy == 'desc')
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                            &#9650;
                                        @endif
                                    @else
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'asc')
                                            &#9660;
                                        @endif
                                    @endif
                                </th>
                            @endforeach

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entityItems as $entityItem)
                            <tr class="border-b-2">
                                @foreach ($resColumns as $column => $title)
                                    @switch($column)
                                        @case('id')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                <a href="{{ route($urlEdit, $entityItem->id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $entityItem->$column }}
                                                </a>
                                            </td>
                                        @break

                                        @case('name')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-left">
                                                <a href="{{ route($urlEdit, $entityItem->id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $entityItem->$column }}
                                                </a>
                                            </td>
                                        @break

                                        @case('description')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @break

                                        @case('created_at')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @break

                                        @case('updated_at')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @break

                                        @case('tonnage')
                                            @if ($entityItem->tonnage)
                                                <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                    {{ $entityItem->$column }}
                                                </td>
                                            @else
                                                <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                    -
                                                </td>
                                            @endif
                                        @break

                                        @case('contact_id')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-left">
                                                @if ($entityItem->contact)
                                                    <a href="{{ route('contact.show', $entityItem->contact->id) }}"
                                                        class="text-blue-500 hover:text-blue-600">
                                                        {{ $entityItem->contact->name }}
                                                    </a>
                                                @else
                                                    не назначено
                                                @endif
                                            </td>
                                        @break

                                        @case('ms_id')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @break

                                        @case('type_id')
                                        <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                            @if(isset($entityItem->type->name))
                                            <a href="{{ route($urlShow, $entityItem->type_id) }}"
                                               class="text-blue-500 hover:text-blue-600">
                                                {{ $entityItem->type->name }}
                                            </a>
                                            @endif
                                        </td>
                                        @break

                                        @default
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                    @endswitch
                                @endforeach

                                {{-- Management --}}
                                <td class="text-nowrap px-4 py-2 flex">
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
