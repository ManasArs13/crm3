<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif

            <x-slot:head>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js" integrity="sha512-t3XNbzH2GEXeT9juLjifw/5ejswnjWWMMDxsdCg4+MmvrM+MwqGhxlWeFJ53xN/SBHPDnW0gXYvBx/afZZfGMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

                </x-slot>


                <div class="w-11/12 mx-auto py-8 max-w-10xl">

                    @if (session('succes'))
                        <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                            {{ session('succes') }}
                        </div>
                    @endif

                    @if (isset($entityName) && $entityName != '')
                        <h3 class="text-4xl font-bold mb-6">{{ $contact->name }}</h3>
                    @endif


                    <div
                        class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">


                        {{-- header --}}
                        <div class="border-b-2 border-neutral-100">
                            <div class="flex flex-row w-full p-3 justify-between">
                                <form method="get" action="{{ route($urlFilter) }}" class="flex gap-1">
                                    <input type="hidden" name="id" value="{{ request()->id }}">
                                    <input type="hidden" name="hash" value="{{ request()->hash }}">
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
                                                                               value="{{ $filter['minChecked'] == '' && !request()->has('filters') && $filter['name'] == 'created_at' ? date('Y-m-d') : $filter['minChecked'] }}"
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
                                                                               value="{{ $filter['maxChecked'] == '' && !request()->has('filters') && $filter['name'] == 'created_at' ? date('Y-m-d') : $filter['maxChecked'] }}"
                                                                               class="date-default relative m-0 block w-[1px] min-w-0 flex-auto rounded-r border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:border-primary focus:text-neutral-700 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary">
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
                                                                        class="select-default2" multiple="multiple"
                                                                        name="filters[{{ $filter['name'] }}][]"
                                                                        id="{{ $filter['name'] }}_select" data-placeholder="Выберите контакт">
                                                                        @foreach($filter['values'] as $value)
                                                                            <option value="{{ $value['value'] }}" selected>{{ $value['name'] }}</option>
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

                                </form>
                                <div class="flex px-3 text-center font-bold">
                                    <button id="print-table"
                                       class="inline-flex items-center rounded bg-green-400 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                        {{ __('label.print') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- body --}}
                        <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto get-print print:text-[3mm]">
                            <table class="text-left text-md text-nowrap">
                                <thead>
                                <tr class="bg-neutral-200 font-semibold py-2">
                                    @foreach ($resColumns as $key => $column)
                                        @if ($key === 'remainder')
                                            <th scope="col" class="px-6 py-4">{{ $column }}</th>
                                        @elseif ($key === 'delivery_price_norm')
                                                <th scope="col" class="px-6 py-4">{{ $column }}</th>
                                        @elseif(isset($orderBy) && $orderBy == 'desc')
                                            <th scope="col" class="px-6 py-4"
                                                @if (
                                                    $column == 'Имя' ||
                                                        $column == 'Дата создания' ||
                                                        $column == 'Сумма' ||
                                                        $column == 'Дата обновления' ||
                                                        $column == 'Вес') style="text-align:right" @else style="text-align:left" @endif>
                                                <a class="text-black"
                                                   href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                                @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                                    &#9650;
                                                @endif
                                            </th>
                                        @else
                                            <th scope="col" class="px-6 py-4"
                                                @if (
                                                    $column == 'Имя' ||
                                                        $column == 'Дата создания' ||
                                                        $column == 'Сумма' ||
                                                        $column == 'Дата обновления' ||
                                                        $column == 'Вес') style="text-align:right" @else style="text-align:left" @endif>
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
                                    $totalCount = $totals['totalCount'];
                                    $totalDeliveryPrice = $totals['totalDeliveryPrice'];
                                    $totalDeliveryPriceNorm = $totals['totalDeliveryPriceNorm'];
                                    $totalDeliverySum = 0;
                                    $totalPaidSum = 0;
                                @endphp
                                @foreach ($entityItems as $entityItem)
                                    @php
                                        $totalSum += $entityItem->suma;
                                    @endphp

                                    @php
                                        $totalDeliverySum += $entityItem->delivery_fee;
                                    @endphp

                                    @php
                                        $totalPaidSum += $entityItem->paid_sum;
                                    @endphp

                                    <tr class="border-b-2 py-2">
                                        @foreach ($resColumns as $column => $title)
                                            <td class="break-all max-w-96 px-6 py-2 truncate"
                                                @if (
                                                    (
                                                        $column == 'name' ||
                                                        $column == 'payed_sum' ||
                                                        $column == 'positions_count' ||
                                                        $column == 'residual_count' ||
                                                        $column == 'shipped_count' ||
                                                        $column == 'shipped_sum' ||
                                                        $column == 'reserved_sum' ||
                                                        $column == 'weight' ||
                                                        $column == 'debt') &&
                                                        !preg_match('/_id\z/u', $column) &&
                                                        $column !== 'sostav') style="text-align:right" @else style="text-align:left" @endif
                                                @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                                                @if (preg_match('/_id\z/u', $column))
                                                    @if ($column == 'contact_id')
                                                        {{ $entityItem->contact->name ?? '-' }}
                                                    @elseif($column == 'order_id')
                                                        {{ $entityItem->$column }}
                                                    @elseif($column == 'delivery_id')
                                                        {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                                    @elseif($column == 'transport_id')
                                                        {{ $entityItem->transport ? $entityItem->transport->name : '-' }}
                                                    @elseif($column == 'transport_type_id')
                                                        {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                                    @else
                                                        {{ $entityItem->$column }}
                                                    @endif
                                                @elseif($column == 'status')
                                                    {{ $entityItem->$column }}
                                                @elseif($column == 'remainder')
                                                    @if ($entityItem->residual_norm !== 0 && $entityItem->residual_norm !== null && $entityItem->type !== 'не выбрано')
                                                        {{ round(($entityItem->residual / $entityItem->residual_norm) * 100) }}
                                                        %
                                                    @else
                                                        {{ null }}
                                                    @endif
                                                @elseif(preg_match('/_link/u', $column) && $entityItem->$column !== null && $entityItem->$column !== '')
                                                    <a href="{{ $entityItem->$column }}" target="_blank"
                                                       class="flex justify-center">
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
                                                    @if ($entityItem->$column == null)
                                                        ---
                                                    @else
                                                        {{ $entityItem->$column }}
                                                    @endif
                                                @elseif($column == 'products_count')
                                                    @php
                                                        $total_quantity = 0;
                                                    @endphp

                                                    @foreach ($entityItem->products as $position)
                                                        @php
                                                            if (
                                                                $position->product->building_material !== 'доставка' &&
                                                                $position->product->building_material !== 'не выбрано'
                                                            ) {
                                                                $total_quantity += $position->quantity;
                                                            }
                                                        @endphp
                                                    @endforeach

                                                    {{ $total_quantity }}
                                                @elseif($column == 'ms_link' && $entityItem->ms_id)
                                                    <a href="https://online.moysklad.ru/app/#demand/edit?id={{ $entityItem->ms_id }}"
                                                       target="_blank" class="flex justify-center">
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
                                                    @if (isset($entityItem->products[0]) && isset($entityItem->products[0]->product))
                                                        {{ $entityItem->products[0]->product->building_material == 'бетон' ? $entityItem->products[0]->product->name : '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    @if (
                                                        $column == 'suma' ||
                                                            $column == 'paid_sum' ||
                                                            $column == 'delivery_price' ||
                                                            $column == 'delivery_price_norm' ||
                                                            $column == 'delivery_fee')
                                                        {{ isset($entityItem->$column) ? number_format((int) $entityItem->$column, 0, ',', ' ') : '' }}
                                                    @else
                                                        {{ $entityItem->$column }}
                                                    @endif
                                                @endif
                                            </td>
                                        @endforeach

                                        {{-- Management --}}
                                        <td class="text-nowrap px-6 py-2 flex"></td>

                                    </tr>
                                @endforeach
                                <tr class="border-b-2 bg-gray-100 py-2">
                                    @foreach ($resColumns as $column => $title)
                                        @if ($column == 'suma')
                                            <td class="px-6 py-2">
                                                {{ number_format((int) $totalSum, 0, ',', ' ') }}
                                            </td>
                                        @elseif($column == 'products_count')
                                            <td class="overflow-auto px-6 py-4">
                                                {{ number_format((int) $totalCount, 0, ',', ' ') }}
                                            </td>
                                        @elseif($column == 'delivery_price')
                                            <td class="overflow-auto px-6 py-4">
                                                {{ number_format((int) $totalDeliveryPrice, 0, ',', ' ') }}
                                            </td>
                                        @elseif($column == 'delivery_price_norm')
                                            <td class="overflow-auto px-6 py-4">
                                                {{ number_format((int) $totalDeliveryPriceNorm, 0, ',', ' ') }}
                                            </td>
                                        @elseif($column == 'delivery_fee')
                                            <td class="overflow-auto px-6 py-4">
                                                {{ number_format((int) $totalDeliverySum, 0, ',', ' ') }}
                                            </td>
                                        @elseif($column == 'paid_sum')
                                            <td class="overflow-auto px-6 py-4">
                                                {{ number_format((int) $totalPaidSum, 0, ',', ' ') }}
                                            </td>
                                        @else
                                            <td>
                                            </td>
                                        @endif
                                    @endforeach
                                        <td class="px-6 py-2"></td>
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

                <style>
                    @media print {
                        @page {
                            size: landscape;
                        }
                    }
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

                        $("#print-table").on("click", function(){
                            $('.get-print').print();
                        })

                        function initSelect2(elementId, url) {
                            $(elementId).select2({
                                width: '372px',
                                tags: true,
                                ajax: {
                                    delay: 250,
                                    url: url,
                                    data: function(params) {
                                        return { term: params.term };
                                    },
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return {
                                                    text: item.name,
                                                    id: item.id,
                                                    attr1: item.phone,
                                                };
                                            })
                                        };
                                    }
                                },
                            });
                        }

                        initSelect2("#contacts_select", '/api/contacts/get');
                        initSelect2("#carriers_select", '/api/carriers/get');
                    });


                    document.addEventListener("DOMContentLoaded", function(event) {

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
                            $('.select-default2').empty();
                        });


                    });
                </script>

</x-app-layout>
