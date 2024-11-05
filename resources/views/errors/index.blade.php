<x-app-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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

        @if (isset($entityName))
            <x-slot:title>
                {{ $entityName }}
                </x-slot>

                <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
                @endif

                <div
                    class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

                    {{-- header card --}}
                    <div class="border-b-2 border-neutral-100">
                        <div class="flex flex-row w-full p-3 justify-between">

                            <form method="get" action="{{ route('errors.index') }}" class="flex gap-1">
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
                                                                           value="{{ $filter['minChecked'] == '' && $filter['name'] == 'date_plan' ? date('Y-m-d') : $filter['minChecked'] }}"
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
                                                                           value="{{ $filter['maxChecked'] == '' && $filter['name'] == 'date_plan' ? date('Y-m-d') : $filter['maxChecked'] }}"
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
                                                                <select
                                                                    class="contact change_name select-default2" multiple="multiple"
                                                                    name="filters[{{ $filter['name'] }}][]"
                                                                    id="contact_name" data-placeholder="Выберите контакт">
                                                                    @foreach($filter['values'] as $value)
                                                                        <option value="{{ $value['value'] }}" selected>{{ $value['name'] }}</option>
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
                            <form class="flex" action="{{ route('api.update.errors') }}" method="post">
                                <button type="submit" class="inline-flex items-center rounded bg-green-400 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                    {{ __('label.update') }}
                                </button>
                            </form>


                        </div>
                    </div>

                    {{-- body card --}}
                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                @foreach ($resColumns as $key => $column)
                                    <th scope="col" class="px-2 py-2 text-black">
                                        @switch($key)
                                            @case('status')
                                                {{ __('column.status_id') }}
                                            @break

                                            @default
                                                {{ $column }}
                                        @endswitch
                                    </th>

                                @endforeach
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($entityItems as $entityItem)
                                <tr class="border-b-2">

                                    @foreach ($resColumns as $column => $title)
                                        <td class="break-all max-w-96 truncate px-2 py-2">
                                            @switch($column)

                                                @case('type')
                                                    {{ $entityItem->type->name ?? '-' }}
                                                @break

                                                @case('responsible_user')
                                                    {{ $entityItem->responsible->name ?? '-' }}
                                                @break

                                                @case('link')
                                                    <a href="{{ $entityItem->link }}" target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                            </path>
                                                            <path fill-rule="evenodd" d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @break

                                                @case('id')
                                                    <a href="{{ $entityItem->link . '?error_fix=' . $entityItem->id }}" class="text-blue-700" target="_blank">
                                                        {{ $entityItem->id ?? '-' }}
                                                    </a>
                                                @break

                                                @default
                                                    {{ $entityItem->$column }}
                                            @endswitch
                                        </td>
                                    @endforeach

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
    <style>
        .select2, .select2-selection{
            width: 100% !important;
            min-height: 42px !important;
            overflow: auto;
            max-height: 120px !important;

        }
        .select2-search__field{
            width: 200px !important;
        }
        .select2-selection, .select2-selection--multiple{
            padding-top: 3px;
            padding-left: 7px;
            border: 1px solid #d4d4d4 !important;
        }

    </style>
    <script>
        $(document).ready(function(){
            $(".select2").select2();

            $("#contact_name").select2({
                width: '372px',
                tags: true,
                ajax: {
                    delay: 250,
                    url: '/api/contacts_amo/get',
                    data: function(params) {
                        var queryParameters = {
                            term: params.term,
                            page: params.page || 1
                        }
                        return queryParameters;
                    },
                    processResults: function(data, params) {
                        params.current_page = params.current_page || 1;
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    text: item.phone,
                                    id: item.id,
                                    attr1: item.name,
                                }
                            }),
                            pagination: {
                                more: (params.current_page * data.per_page) < data.total
                            }
                        };
                    }
                },
            });

            $("#reset-button").on("click", function(){
                const checkedCheckboxes = [{!! '"' . implode('", "', array_values($select)) . '"' !!}];

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

            $("#reset-button2").on("click", function(){
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
                $('.select-default2').empty();
            });
        });
    </script>
</x-app-layout>
