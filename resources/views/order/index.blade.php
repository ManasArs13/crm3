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

    <div class="w-11/12 mx-auto py-8">

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
                                                        value="{{ $key }}"
                                                        @if ($column['checked'] == true) checked @endif>
                                                    {{ $column['name_rus'] }}
                                                </p>
                                            </div>
                                        @endforeach
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
                                                                class="relative m-0 block w-[1px] min-w-0 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary">
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
                                                                class="relative m-0 block w-[1px] min-w-0 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary">
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
                                                            class="border border-solid border-neutral-300 rounded w-full py-2 mb-4"
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
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                        <div>
                            <button type="submit"
                                class="inline-flex rounded bg-blue-600 border-2 border-blue-600 px-4 py-1 text-md font-medium leading-normal text-white hover:bg-blue-700">
                                поиск
                            </button>
                        </div>
                    </form>

                    {{-- Date --}}
                    <div class="flex flex-row gap-1">
                        @if ($queryPlan == 'all')
                            <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=1970-01-01&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateAll . '&filters%5Bmaterial%5D=' . $queryMaterial }}"
                                class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                        @else
                            <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=1970-01-01&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateAll . '&filters%5Bmaterial%5D=' . $queryMaterial }}"
                                class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                        @endif
                        @if ($queryPlan == 'today')
                            <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateToday . '&filters%5Bmaterial%5D=' . $queryMaterial }}"
                                class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Сегодня</a>
                        @else
                            <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateToday . '&filters%5Bmaterial%5D=' . $queryMaterial }}"
                                class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Сегодня</a>
                        @endif
                        @if ($queryPlan == 'threeday')
                            <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateThreeDay . '&filters%5Bmaterial%5D=' . $queryMaterial }}"
                                class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">3
                                дня</a>
                        @else
                            <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateThreeDay . '&filters%5Bmaterial%5D=' . $queryMaterial }}"
                                class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">3
                                дня</a>
                        @endif
                        @if ($queryPlan == 'week')
                            <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '0&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateWeek . '&filters%5Bmaterial%5D=' . $queryMaterial }}"
                                class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Неделя</a>
                        @else
                            <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateWeek . '&filters%5Bmaterial%5D=' . $queryMaterial }}"
                                class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Неделя</a>
                        @endif
                    </div>

                    {{-- Material --}}
                    <div class="flex flex-row gap-1">
                        @if ($queryPlan == 'all')

                            @if ($queryMaterial == 'index')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=1970-01-01&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateAll . '&filters%5Bmaterial%5D=index' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=1970-01-01&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateAll . '&filters%5Bmaterial%5D=index+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                            @endif
                            @if ($queryMaterial == 'block')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=1970-01-01&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateAll . '&filters%5Bmaterial%5D=block+' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Блок</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=1970-01-01&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateAll . '&filters%5Bmaterial%5D=block+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Блок</a>
                            @endif
                            @if ($queryMaterial == 'concrete')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=1970-01-01&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateAll . '&filters%5Bmaterial%5D=concrete+' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Бетон</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=1970-01-01&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateAll . '&filters%5Bmaterial%5D=concrete+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Бетон</a>
                            @endif
                        @elseif($queryPlan == 'today')
                            @if ($queryMaterial == 'index')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateToday . '&filters%5Bmaterial%5D=index' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateToday . '&filters%5Bmaterial%5D=index+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                            @endif
                            @if ($queryMaterial == 'block')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateToday . '&filters%5Bmaterial%5D=block+' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Блок</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateToday . '&filters%5Bmaterial%5D=block+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Блок</a>
                            @endif
                            @if ($queryMaterial == 'concrete')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateToday . '&filters%5Bmaterial%5D=concrete+' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Бетон</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateToday . '&filters%5Bmaterial%5D=concrete+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Бетон</a>
                            @endif
                        @elseif($queryPlan == 'threeday')
                            @if ($queryMaterial == 'index')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateThreeDay . '&filters%5Bmaterial%5D=index' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateThreeDay . '&filters%5Bmaterial%5D=index+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                            @endif
                            @if ($queryMaterial == 'block')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateThreeDay . '&filters%5Bmaterial%5D=block+' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Блок</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateThreeDay . '&filters%5Bmaterial%5D=block+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Блок</a>
                            @endif
                            @if ($queryMaterial == 'concrete')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateThreeDay . '&filters%5Bmaterial%5D=concrete+' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Бетон</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateThreeDay . '&filters%5Bmaterial%5D=concrete+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Бетон</a>
                            @endif
                        @else
                            @if ($queryMaterial == 'index')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateWeek . '&filters%5Bmaterial%5D=index' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateWeek . '&filters%5Bmaterial%5D=index+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Все</a>
                            @endif
                            @if ($queryMaterial == 'block')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateWeek . '&filters%5Bmaterial%5D=block+' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Блок</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateWeek . '&filters%5Bmaterial%5D=block+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Блок</a>
                            @endif
                            @if ($queryMaterial == 'concrete')
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateWeek . '&filters%5Bmaterial%5D=concrete+' }}"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Бетон</a>
                            @else
                                <a href="{{ route($urlFilter) . '?filters%5Bdate_plan%5D%5Bmin%5D=' . $dateToday . '&filters%5Bdate_plan%5D%5Bmax%5D=' . $dateWeek . '&filters%5Bmaterial%5D=concrete+' }}"
                                    class="inline-flex items-center rounded bg-blue-300 px-3 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Бетон</a>
                            @endif

                        @endif
                    </div>

                    {{-- Status --}}
                    <div class="flex flex-row gap-1">
                        <form method="get" action="{{ route($urlFilter) }}" class="flex gap-1">
                            <div>
                                <x-dropdown align="left" width="17" outside='false'>
                                    <x-slot name="trigger">
                                        <button type="button"
                                            class="inline-flex rounded border-2 border-blue-600 text-blue-600 px-4 py-1 text-md font-medium leading-normal hover:bg-blue-700 hover:text-white">
                                            статус
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
                                        <div class="p-4">
                                            @foreach ($filters as $filter)
                                                @if ($filter['type'] == 'checkbox')
                                                    <div class="flex flex-col gap-1 w-100">
                                                        @foreach ($filter['values'] as $value)
                                                            <div class="basis-full text-left">
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
                                                @endif
                                            @endforeach
                                        </div>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                            <div>
                                <button type="submit"
                                    class="inline-flex rounded bg-blue-600 border-2 border-blue-600 px-4 py-1 text-md font-medium leading-normal text-white hover:bg-blue-700">
                                    применить
                                </button>
                            </div>
                        </form>
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
                            <th scope="col" class="px-2 py-4"></th>
                            @foreach ($resColumns as $key => $column)
                                @if ($key === 'remainder' || $key == 'positions_count')
                                    <th scope="col" class="px-2 py-4">{{ $column }}</th>
                                @elseif(isset($orderBy) && $orderBy == 'desc')
                                    <th scope="col" class="px-2 py-4">
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                            &#9650;
                                        @endif
                                    </th>
                                @else
                                    <th scope="col" class="px-2 py-4">
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
                        @endphp

                        @foreach ($entityItems as $entityItem)
                            @php
                                $totalSum += $entityItem->sum;
                            @endphp

                            @php
                                $total_quantity = 0;
                                $total_shipped_count = 0;
                            @endphp

                            @foreach ($entityItem->positions as $position)
                                @php
                                    $total_quantity += $position->quantity;
                                    $totalCount += $position->quantity;
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
                                    <td class="text-nowrap px-2 py-4">
                                        <button class="buttonForOpen text-normal font-bold"
                                            data-id="{!! $entityItem->id !!}">+</button>
                                    </td>
                                @else
                                    <td class="text-nowrap px-2 py-4">
                                    </td>
                                @endif

                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-2 py-4"
                                        @if (
                                            (is_int($entityItem->$column) ||
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
                                                $column == "date_plan" ||
                                                $column == "date_fact" ||
                                                $column == "created_at" ||
                                                $column == "updated_at" ||
                                                $column == 'debt') &&
                                                !preg_match('/_id\z/u', $column) &&
                                                $column !== 'sostav') style="text-align:right" @else style="text-align:left" @endif
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
                                                        <div id="status"
                                                            class="rounded border-yellow-500 bg-yellow-400 px-2 py-1 text-center">
                                                            <span>[N] Новый</span>
                                                        </div>
                                                    @break

                                                    @case(2)
                                                        <div id="status"
                                                            class="rounded border-blue-500 bg-blue-400 px-2 py-1 text-center">
                                                            <span>Думают</span>
                                                        </div>
                                                    @break

                                                    @case(3)
                                                        <div id="status"
                                                            class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                            <span>[DN] Подтвержден</span>
                                                        </div>
                                                    @break

                                                    @case(4)
                                                        <div id="status"
                                                            class="rounded border-purple-500 bg-purple-400 px-2 py-1 text-center">
                                                            <span>На брони</span>
                                                        </div>
                                                    @break

                                                    @case(5)
                                                        <div id="status"
                                                            class="rounded border-orange-500 bg-orange-400 px-2 py-1 text-center">
                                                            <span>[DD] Отгружен с долгом</span>
                                                        </div>
                                                    @break

                                                    @case(6)
                                                        <div id="status"
                                                            class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                            <span>[DF] Отгружен и закрыт</span>
                                                        </div>
                                                    @break

                                                    @case(7)
                                                        <div id="status"
                                                            class="rounded border-red-500 bg-red-400 px-2 py-1 text-center">
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
                                        @elseif($column == 'sostav')
                                            @if (isset($entityItem->positions[0]) && isset($entityItem->positions[0]->product))
                                                {{ $entityItem->positions[0]->product->building_material == 'бетон' ? $entityItem->positions[0]->product->name : '-' }}
                                            @else
                                                -
                                            @endif
                                        @else
                                            {{ $entityItem->$column }}
                                        @endif
                                    </td>
                                @endforeach

                                {{-- Delete --}}
                                <td class="text-nowrap px-2 py-4">

                                    <form action="{{ route($urlDelete, $entityItem->id) }}" method="Post"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="rounded-lg p-1 font-semibold hover:bg-red-500 hover:text-white border border-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6 stroke-red-500 hover:stroke-white">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </button>
                                    </form>
                                </td>

                            </tr>

                            @foreach ($entityItem->shipments as $shipment)
                                <tr style="display: none"
                                    class="border-b-2 bg-green-100 position_column_{!! $entityItem->id !!}">
                                    <td class="text-nowrap px-3 py-4">
                                        {{ $loop->iteration }}
                                    </td>
                                    @foreach ($resColumns as $column => $title)
                                        <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-2 py-4"
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
                                    <td class="text-nowrap px-3 py-4">
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach

                        <tr class="border-b-2 bg-gray-100">
                            <td>
                            </td>
                            @foreach ($resColumns as $column => $title)
                                @if ($column == 'sum')
                                    <td class="px-2 py-4">
                                        {{ $totalSum }}
                                    </td>
                                @elseif($column == 'positions_count')
                                    <td class="overflow-auto px-2 py-4">
                                        {{ $totalCount }}
                                    </td>
                                @elseif($column == 'shipped_count')
                                    <td class="overflow-auto px-2 py-4">
                                        {{ $totalShipped }}
                                    </td>
                                @elseif($column == 'residual_count')
                                    <td class="overflow-auto px-2 py-4">
                                        {{ $totalCount - $totalShipped }}
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
