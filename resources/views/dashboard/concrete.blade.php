<x-app-layout>
    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    </x-slot>
    <div class="max-w-10xl flex flex-col lg:flex-row flex-nowrap gap-3 w-11/12 mx-auto py-10">
        <div class="flex flex-col basis-3/4 bg-white rounded-md shadow overflow-x-auto">
            <div class="flex flex-row w-full p-3 justify-between">
                <div class="flex gap-2">
                    <div class="">
                        @if (request()->routeIs('dashboard'))
                            <a href="{{ route('dashboard', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                        @else
                            <a href="{{ route('dashboard', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">ВСЕ</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->routeIs('dashboard-2'))
                            <a href="{{ route('dashboard-2', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                        @else
                            <a href="{{ route('dashboard-2', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЛОК</a>
                        @endif
                    </div>
                    <div>
                        @if (request()->routeIs('dashboard-3'))
                            <a href="{{ route('dashboard-3', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                        @else
                            <a href="{{ route('dashboard-3', ['date_plan' => $date]) }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">БЕТОН</a>
                        @endif
                    </div>
                </div>

                <div class="flex px-3 text-center font-bold">
                    <div class="flex gap-2">
                        <div class="font-medium mx-1 bg-yellow-200 rounded-md px-2">
                            Кол-во: <span id="QuantityProduct">0</span>
                        </div>
                        <div class="font-medium mx-1 bg-green-200 rounded-md px-2">
                            Отг-но: <span id="QuantityShipment">0</span>
                        </div>
                        <div class="font-medium mx-1 bg-red-200 rounded-md px-2">
                            Остаток: <span id="QuantityResidual">0</span>
                        </div>
                    </div>
                    <a href="{{ route('dashboard-3', ['date_plan' => $datePrev]) }}" class="mx-2 text-lg">&#9668;</a>
                    <p class="mx-2 text-lg">{{ $date }}</p>
                    <a href="{{ route('dashboard-3', ['date_plan' => $dateNext]) }}" class="mx-2 text-lg">&#9658;</a>
                </div>
            </div>
            @include('dashboard.components.canvas', ['date' => $date])
            <div class="block border-t-2 py-5 overflow-x-scroll">
                @include('dashboard.components.orderTable')
            </div>
        </div>
        <div class="flex flex-col gap-4 basis-1/4">
            <div class="flex flex-col bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <thead>
                        <tr class="font-light border-b-2 bg-neutral-200">
                            <th colspan="4" class="font-light px-1 py-3"></th>
                            <th class="border-l-2 px-1 py-3">Начало</th>
                            <th class="border-x-2 px-1 py-3">Приход</th>
                            <th class="border-r-2 px-1 py-3">Расход</th>
                            <th class="px-1 py-3">Конец</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $material)
                            @php
                                $residual_percent =
                                    (($material['residual'] - ($material['rashod'] ? $material['rashod'] : 0)) /
                                        $material['residual_norm']) *
                                    100;
                                $color = match (true) {
                                    $residual_percent <= 30 => 'bg-red-300',
                                    $residual_percent > 30 && $residual_percent <= 70 => 'bg-yellow-300',
                                    default => 'bg-green-300',
                                };
                            @endphp
                            <tr class="border-b-2">
                                <td class="px-1 m-2 py-2 max-w-[150px] truncate" colspan="4">
                                    {{ $material['short_name'] }}
                                </td>
                                <td class="px-1 m-2 text-right border-x-2 py-2" colspan="1">
                                    {{ round($material['residual'] / 1000) }}
                                </td>
                                <td class="px-1 m-2 text-right border-x-2 py-2" colspan="1">
                                    -
                                </td>
                                <td class="px-1 m-2 text-right border-x-2 py-2" colspan="1">
                                    {{ $material['rashod'] ? round($material['rashod'] / 1000) : 0 }}
                                </td>
                                <td class="px-1 m-2 text-right py-2" colspan="1">
                                    <div
                                        class="{{ $color }} rounded-sm p-1 h-6 flex justify-center items-center">
                                        {{ round(($material['residual'] - ($material['rashod'] ? $material['rashod'] : 0)) / 1000) }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <div class="flex flex-col bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <thead>
                        <tr class="font-light border-b-2 text-sm bg-neutral-200">
                            <th class="min-w-[59px]"></th>
                            <th class="border-l-2 py-2 px-1 text-center">Транспорт</th>
                            <th class="border-r-2 py-2 px-1 min-w-[59px]">ГП</th>
                            <th class="border-r-2 py-2 px-1">Статус</th>
                            <th class="py-2 px-1">База</th>
                            <th class="py-2 px-1">P</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $key => $shipment)
                            @if (
                                (isset($shipment->first()->transport) &&
                                    $shipment->first()->transport->shifts->isNotEmpty() &&
                                    !isset($shipment->first()->transport->shifts[0]['end_shift']) &&
                                    isset($shipment->first()->transport->shifts[0]['start_shift'])) ||
                                    !isset($shipment->first()->transport) ||
                                    $shipment->first()->transport->shifts->isEmpty())
                                @php
                                    $transportName = optional($shipment->first()->transport)->name ?? '-';
                                    $transportNumber = optional($shipment->first()->transport)->car_number ?? '-';
                                    $transportTonnage = optional($shipment->first()->transport)->tonnage ?? '-';

                                    $currentTime = Carbon\Carbon::now();
                                    $firstCreatedAt = Carbon\Carbon::parse($shipment->first()->created_at);
                                    $firstTimeToCome = Carbon\Carbon::parse($shipment->first()->time_to_come);
                                    $firstTimeToOut = Carbon\Carbon::parse($shipment->first()->time_to_out);
                                    $firstToReturn = Carbon\Carbon::parse($shipment->first()->time_to_return);

                                    if ($currentTime->between($firstCreatedAt, $firstTimeToCome)) {
                                        $statusColor = 'bg-yellow-100';
                                        $statusInfo = 'Отгружен';
                                    } elseif ($currentTime->between($firstTimeToCome, $firstTimeToOut)) {
                                        $statusColor = 'bg-sky-200';
                                        $statusInfo = 'На объекте';
                                    } elseif ($currentTime->between($firstTimeToOut, $firstToReturn)) {
                                        $statusColor = 'bg-sky-100';
                                        $statusInfo = 'Обратно';
                                    } else {
                                        $statusColor = 'bg-green-100';
                                        $statusInfo = 'На базе';
                                    }
                                @endphp
                                <tr class="border-b-2 {{ $statusColor }} group">
                                    <td class="px-1 m-2 border-r-2 py-3 text-center ships-group-show text-blue-700 cursor-pointer"
                                        data-ships="{{ $shipment->first()->id ?? '' }}">{{ $transportNumber }}</td>
                                    <td class="px-1 m-2 border-r-2 text-left py-3 max-w-[150px] text-center truncate">
                                        {{ $transportName ? $transportName : 'не указано' }}
                                    </td>
                                    <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $transportTonnage }}</td>
                                    <td class="px-1 m-2 border-x-2 text-center py-3 truncate">{{ $statusInfo }}</td>
                                    <td class="px-1 m-2 text-center py-3">{{ $firstToReturn->format('H:i') }}</td>
                                    <td class="border-l-2 text-nowrap px-2 py-2">{{ $shipment->count() }}</td>
                                </tr>
                            @endif
                        @endforeach
                        @foreach ($shifts as $shift)
                            @if (!isset($shift->end_shift))
                                <tr class="border-b-2 group">
                                    <td class="px-1 m-2 border-r-2 py-3 text-center shifts-group-show text-blue-700 cursor-pointer"
                                        data-shifts="{{ $shift->id ?? '' }}">{{ $shift->transport->car_number }}</td>
                                    <td class="px-1 m-2 border-r-2 text-left py-3 max-w-[150px] text-center truncate">
                                        {{ $shift->transport->name ? $shift->transport->name : 'не указано' }}
                                    </td>
                                    <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $shift->transport->tonnage }}
                                    </td>
                                    <td class="px-1 m-2 border-x-2 text-center py-3 truncate">-</td>
                                    <td class="px-1 m-2 text-center py-3"></td>
                                    <td class="border-l-2 text-nowrap px-2 py-2"></td>

                                </tr>
                            @endif
                        @endforeach

                    </tbody>
                </table>

            </div>

            <div class="flex flex-col bg-white rounded-md shadow overflow-x-auto">

                <table>
                    <thead>
                        <tr class="font-light border-b-2 text-sm bg-neutral-200">
                            <th class="min-w-[59px]"></th>
                            <th class="border-l-2 py-2 px-1 text-center">Транспорт</th>
                            <th class="border-r-2 py-2 px-1 min-w-[59px]">ГП</th>
                            <th class="border-r-2 py-2 px-1">Статус</th>
                            <th class="py-2 px-1">База</th>
                            <th class="py-2 px-1">P</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $key => $shipment)
                            @if (isset($shipment->first()->transport) &&
                                    $shipment->first()->transport->shifts->isNotEmpty() &&
                                    isset($shipment->first()->transport->shifts[0]['end_shift']) &&
                                    isset($shipment->first()->transport->shifts[0]['start_shift']))
                                @php
                                    $transportName = optional($shipment->first()->transport)->name ?? '-';
                                    $transportNumber = optional($shipment->first()->transport)->car_number ?? '-';
                                    $transportTonnage = optional($shipment->first()->transport)->tonnage ?? '-';

                                    $currentTime = Carbon\Carbon::now();
                                    $firstCreatedAt = Carbon\Carbon::parse($shipment->first()->created_at);
                                    $firstTimeToCome = Carbon\Carbon::parse($shipment->first()->time_to_come);
                                    $firstTimeToOut = Carbon\Carbon::parse($shipment->first()->time_to_out);
                                    $firstToReturn = Carbon\Carbon::parse($shipment->first()->time_to_return);

                                    if ($currentTime->between($firstCreatedAt, $firstTimeToCome)) {
                                        $statusColor = 'bg-yellow-100';
                                        $statusInfo = 'Отгружен';
                                    } elseif ($currentTime->between($firstTimeToCome, $firstTimeToOut)) {
                                        $statusColor = 'bg-sky-200';
                                        $statusInfo = 'На объекте';
                                    } elseif ($currentTime->between($firstTimeToOut, $firstToReturn)) {
                                        $statusColor = 'bg-sky-100';
                                        $statusInfo = 'Обратно';
                                    } else {
                                        $statusColor = 'bg-green-100';
                                        $statusInfo = 'На базе';
                                    }
                                @endphp
                                <tr class="border-b-2 {{ $statusColor }} group">
                                    <td class="px-1 m-2 border-r-2 py-3 text-center ships-group-show text-blue-700 cursor-pointer"
                                        data-ships="{{ $shipment->first()->id ?? '' }}">{{ $transportNumber }}</td>
                                    <td class="px-1 m-2 border-r-2 text-left py-3 max-w-[150px] text-center truncate">
                                        {{ $transportName ? $transportName : 'не указано' }}
                                    </td>
                                    <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $transportTonnage }}</td>
                                    <td class="px-1 m-2 border-x-2 text-center py-3 truncate">{{ $statusInfo }}</td>
                                    <td class="px-1 m-2 text-center py-3">{{ $firstToReturn->format('H:i') }}</td>
                                    <td class="border-l-2 text-nowrap px-2 py-2">{{ $shipment->count() }}</td>

                                </tr>
                            @endif
                        @endforeach
                        @foreach ($shifts as $shift)
                            @if (isset($shift->end_shift))
                                <tr class="border-b-2 group">
                                    <td class="px-1 m-2 border-r-2 py-3 text-center shifts-group-show text-blue-700 cursor-pointer"
                                        data-shifts="{{ $shift->id ?? '' }}">{{ $shift->transport->car_number }}</td>
                                    <td class="px-1 m-2 border-r-2 text-left py-3 max-w-[150px] text-center truncate">
                                        {{ $shift->transport->name ? $shift->transport->name : 'не указано' }}
                                    </td>
                                    <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $shift->transport->tonnage }}
                                    </td>
                                    <td class="px-1 m-2 border-x-2 text-center py-3 truncate">-</td>
                                    <td class="px-1 m-2 text-center py-3"></td>
                                    <td class="border-l-2 text-nowrap px-2 py-2"></td>

                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex">
                <button class="text-blue-600 bg-neutral-200 p-2 rounded" onclick="toggleShiftModal()">Добавить в
                    смену</button>
            </div>
        </div>
    </div>

    <x-shipment-modal :shipments="$shipments" title="Отгрузки" :date="$date" />

    <x-shift-modal :shifts="$shifts" title="Отгрузки" :date="$date" />

    <div class="relative z-10 hidden" id="shift_modal">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form id="shift_form">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                    <h3 class="text-center font-semibold leading-6 text-2xl text-gray-900"
                                        id="modal-title">Добавить смену</h3>
                                    <div class="mt-2 flex space-x-4">
                                        <div class="mt-1 w-[60%]">
                                            <label for="transport"
                                                class="block text-sm font-medium leading-6 text-gray-900 text-left">Транспорт</label>
                                            <div class="mt-2 w-full">
                                                <select name="transport" id="transport" multiple="multiple"
                                                    class="select2 h-[36px] block !w-full rounded-md py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                    @foreach ($transports as $transport)
                                                        <option value="{{ $transport->id }}"
                                                            @if ($transport->shifts->isNotEmpty()) disabled @endif>
                                                            {{ $transport->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-1 w-[40%]">
                                            <label for="start_shift"
                                                class="block text-sm font-medium leading-6 text-gray-900 text-left truncate">Начало
                                                смены</label>
                                            <div class="mt-2">
                                                <input type="time" value="08:00" name="start_shift"
                                                    id="start_shift" autocomplete="given-name"
                                                    class="block w-full h-[38px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex space-x-4">
                                        <div class="mt-1 w-full">
                                            <label for="shift_description"
                                                class="block text-sm font-medium leading-6 text-gray-900 text-left">Комментарий</label>
                                            <div class="mt-2 w-full">
                                                <textarea id="shift_description" name="description"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                    name="" id="" cols="30" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="shift_success" class="text-green-500 text-sm hidden">Изменения сохранены</div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button id="shift_send" type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto">Сохранить</button>
                            <button type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                onclick="toggleShiftModal()">Закрыть</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .select2-container--default .select2-results>.select2-results__options {
            min-height: 24rem;
            width: 100%;
        }

        .select2-selection,
        .select2-selection--single {
            /*padding: 5px;*/
            min-height: 36px !important;
            max-height: 120px;
            width: 100% !important;
            overflow: auto;
            border: 1px solid #d1d5db !important;
            border-radius: 5px !important;
        }

        .select2 {
            width: 100% !important;
        }

        .select2-selection__arrow {
            top: 4px !important;
        }
    </style>

    <script>
        const day = "{{ $date }}";
        const modal = document.getElementById('shift_modal');

        function toggleShiftModal() {
            modal.classList.toggle('hidden');
        }

        $(document).ready(function() {
            $("#shift_form").on("submit", function() {
                $.ajax({
                    url: '{{ route('api.get.shift_create') }}',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        transports: $("#transport").val(),
                        time: $("#start_shift").val(),
                        description: $("#shift_description").val(),
                        day: day
                    },
                    beforeSend: function() {
                        $("#shift_success").hide();
                    },
                    success: function(data) {
                        if (data['success'] == true) {
                            $("#shift_success").show('slow');
                            location.reload();
                        }
                    }
                });
                return false;
            });

            $(".select2").select2({
                tags: true,
                closeOnSelect: false
            });

            $(".ships-group-show").on("click", function() {
                var id = $(this).attr('data-ships');
                $("#shipments_group_" + id).removeClass("hidden");
            });

            $(".shifts-group-show").on("click", function() {
                var id = $(this).attr('data-shifts');
                $("#shifts_group_" + id).removeClass("hidden");
            });

            $('.shifts-hide').on('click', function() {
                $(this).closest('.hide-get').addClass('hidden');
                $(".comment-success, .comment-sending").addClass("hidden");
            });

            let timeout = null;
            $(".shift_description").on("input", function() {
                let description = $(this).val();
                let transport_id = $(this).attr("data-transport_id");
                clearTimeout(timeout);

                timeout = setTimeout(function() {
                    $.ajax({
                        url: '/api/Shift/change?id=' + transport_id +
                            '&date={{ $date }}',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            description: description
                        },
                        beforeSend: function() {
                            $(".comment-sending").removeClass("hidden");
                            $(".comment-success").addClass("hidden");
                        },
                        success: function() {
                            $(".comment-success").removeClass("hidden");
                            $(".comment-sending").addClass("hidden");
                        },
                        error: function(error) {
                            console.log('Ошибка при отправке данных.', error);
                        }
                    });
                }, 1000);
            });
        });
    </script>
</x-app-layout>
