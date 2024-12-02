<x-app-layout>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    </x-slot>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entity }}
        </x-slot>
    @endif


    <div class="w-11/12 max-w-10xl mx-auto py-8">

        @if (session('success'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                {{ session('success') }}
            </div>
        @endif

        @if (session('danger'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-red-200 px-6 py-5 text-red-700 ">
                {{ session('danger') }}
            </div>
        @endif

        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ $entity }}</h3>
        @endif

        <div
            class="max-w-10xl mx-auto block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <form action="{{ route($action) }}" method="post">
                    @csrf
                    @method('post')

                    <div class="min-h-6 px-5 pt-5 pb-3">

                        <div class="flex flex-row mb-1">
                            <label for="input_name" class="font-bold flex basis-1/3">Имя</label>
                            <div class="flex basis-4/5">
                                <input type="text" class="rounded w-full" id="input_name" name="name" required
                                    placeholder="Введите имя">
                            </div>
                        </div>

                        <div class="flex flex-row mb-1">
                            <label for="input_description" class="font-bold flex basis-1/3">Описание</label>
                            <div class="flex basis-4/5">
                                <input type="text" class="rounded w-full" id="input_description" name="description"
                                    placeholder="Введите описание">
                            </div>
                        </div>

                        <div class="flex flex-row mb-1">
                            <label for="input_tonnage" class="font-bold flex basis-1/3">Грузоподьёмность</label>
                            <div class="flex basis-4/5">
                                <input type="number" class="rounded w-full" id="input_tonnage" name="tonnage"
                                    placeholder="Введите значение">
                            </div>
                        </div>

                        <div class="flex flex-row mb-1">
                            <label for="input_car_number" class="font-bold flex basis-1/3">гос. номер</label>
                            <div class="flex basis-4/5">
                                <input type="text" class="rounded w-full" id="input_car_number" name="car_number"
                                    placeholder="Введите номер транспорта">
                            </div>
                        </div>

                        <div class="flex flex-row mb-1">
                            <label for="input_driver" class="font-bold flex basis-1/3">Водитель</label>
                            <div class="flex basis-4/5">
                                <input type="text" class="rounded w-full" id="input_driver" name="driver"
                                       placeholder="Водитель">
                            </div>
                        </div>

                        <div class="flex flex-row mb-1">
                            <label for="input_driver_phone" class="font-bold flex basis-1/3">Телефон водителя</label>
                            <div class="flex basis-4/5">
                                <input type="text" class="rounded w-full" id="input_driver_phone" name="phone"
                                       placeholder="Укажите телефон водителя">
                            </div>
                        </div>

                        <div class="flex flex-row mb-1">
                            <label for="input_transport_ts" class="font-bold flex basis-1/3">Вид ТС</label>
                            <div class="flex basis-4/5">
                                <select name="type_id" id="transport_type" style="width: 100%" class="select1">
                                    <option value="" selected disabled>не выбрано</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-row mb-1">
                            <label for="input_contact" class="font-bold flex basis-1/3">Контрагент</label>
                            <div class="flex basis-4/5">
                                <select name="contact_id" id="input_contact" style="width: 100%" class="select2">
                                    <option value="" selected disabled>не выбрано</option>
                                </select>
                            </div>
                        </div>


                    </div>

                    <div class="px-5 mb-3 w-full">
                        <button type="submit"
                            class="w-full p-1 bg-green-500 hover:bg-green-600 text-white hover:text-gray-700 rounded font-bold uppercase">{{ __('label.save') }}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <style>
        .select2-container--default .select2-results>.select2-results__options {
            /* min-height: 24rem; */
        }
    </style>
    <script>
        $(document).ready(function() {
            //change selectboxes to selectize mode to be searchable


            $('.select2').select2({
                ajax: {
                    url: '/api/contacts/get/name',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data,params) {
                        params.current_page = params.current_page || 1;

                        return {
                            results: data.data,
                            pagination: {
                                more: (params.current_page * data.per_page) < data.total
                            }
                        };
                    },
                    cache: true,
                    error: function(response) {
                        $("#message").html(response.responseJSON.message+response.responseJSON.file+response.responseJSON.line);
                    }
                },
                templateResult: formatName,
                templateSelection: formatName
            });

            function formatName(state) {
                if (!state.id){
                    return state.text;
                }
                var $state = $(
                    '<span data-id="'+state.ms_id+'" data-phone="'+state.phone+'" data-name="'+state.name+'" data-change="change_phone">'+state.name+'</span>'
                );
                return $state;
            };
            $('.select1').select2({
                ajax: {
                    url: `{{ route($searchTransportTypes) }}`,
                    data: function(params) {

                        var queryParameters = {
                            term: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.name  + '   ' + (item.description ? item.description : ' '),
                                    id: item.id,
                                }
                            })
                        };
                    }
                },
            });
        });
    </script>
</x-app-layout>
