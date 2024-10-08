<x-app-layout>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    </x-slot>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            Добавить {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 max-w-10xl mx-auto py-8">

        @if (session('succes'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                {{ session('succes') }}
            </div>
        @endif

        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
        @endif

        <div
            class="mx-auto block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <span id="message" class="error"></span>
                <form action="{{ route($action) }}" method="post">
                    @csrf
                    @method('post')

                    <div class="min-h-6 px-5 pt-5 pb-3">

                        {{-- Order --}}
                        <div class="w-full mb-2 flex flex-row">

                            <div class="flex flex-row basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Заказ №</span>
                                    <input type="number" name="name" min="79999" value="" readonly
                                        class="w-[137px] w-relative m-0 rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                                    <input type="datetime-local" min="2020-01-01" value="{{ $dateNow }}"
                                        name="date_created" required
                                        class="w-[227px] ml-2 relative m-0 rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                                </div>
                            </div>
                        </div>

                        {{-- Contacts --}}
                        <div class="flex flex-row mb-3 w-full justify-between">

                            <div class="flex flex-row basis-1/2">

                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Имя</span>
                                    <select name="contact_name" class="contact change_name" data-change="change_phone"
                                        required id="contact_name" data-placeholder="Выберите или введите имя">
                                    </select>

                                </div>

                            </div>

                            <div class="basic-1/2 text-end">
                                {{-- balance --}}
                                <div class="balance p-1 rounded ">
                                </div>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="flex flex-row mb-3 w-full">
                            <div class="flex flex-row basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Телефон</span>
                                    <select name="contact_phone" class="contact change_phone" data-change="change_name"
                                        required id="contact_phone" data-placeholder="Выберите или введите телефон">
                                    </select>
                                </div>

                            </div>

                        </div>

                        {{-- Delivery --}}
                        <div class="flex flex-row mb-3 w-full">
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Доставка</span>
                                    <select name="delivery" required class="select2 w-[372px]"
                                        data-placeholder="Выберите доставку">
                                        <option value="" selected disabled>не выбрано</option>
                                        @foreach ($deliveries as $delivery)
                                            <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="flex flex-row mb-3 w-full">
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Статус</span>
                                    <select name="status" required style="background-color: {{ $statuses[0]->color }}"
                                        class="relative m-0 w-[372px] rounded border border-solid border-neutral-200 bg-blue-400 px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                        @foreach ($statuses as $status)
                                            <option style="background-color: {{ $status->color }}"
                                                data-color="{{ $status->color }}" value="{{ $status->id }}">
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="w-full mb-2 flex flex-row">
                            <div class="flex flex-row basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] h-[37px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Адрес</span>
                                    <input type="text" name="address" required
                                        class="w-[372px] relative m-0 rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                                </div>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="flex flex-row mb-5 w-full">
                            <div class="flex flex-row">
                                <span style="width:152px"
                                    class="basis-[100%] flex items-center whitespace-nowrap px-2 py-[0.25rem] text-center text-base text-surface">
                                    Плановая дата</span>
                                <input type="date" min="2020-01-01" value="{{ $date }}" name="date"
                                    required
                                    class="h-[37px] relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                            </div>
                            <div class="flex flex-row">
                                <span
                                    class="basis-[10%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                    время</span>
                                <select name="time" class="rounded border border-solid border-neutral-400 h-[37px]">
                                    <option value="08:00">08:00</option>
                                    <option value="09:00">09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                    <option value="13:00">13:00</option>
                                    <option value="14:00">14:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00">17:00</option>
                                    <option value="18:00">18:00</option>
                                    <option value="19:00">19:00</option>
                                    <option value="20:00">20:00</option>
                                </select>
                            </div>

                        </div>

                        <hr class="w-full">

                        {{-- Products --}}
                        <div x-data="products">
                            <h4 class="text-left font-semibold text-lg my-3">Товары</h4>

                            <div class="flex flex-row mb-1 w-full bg-gray-100 rounded">
                                <div class="flex basis-6/12 justify-center text-gray-700">
                                    наименование
                                </div>
                                <div class="flex basis-1/12 justify-center text-gray-700">
                                    кол-во
                                </div>
                                <div class="flex basis-2/12 justify-end text-gray-700">
                                    цена
                                </div>

                                <div class="flex basis-3/12 justify-center text-gray-700">
                                    сумма
                                </div>
                                <div class="w-6 mx-2">

                                </div>
                            </div>
                            <template x-for="(row, index) in rows" :key="index">
                                <div class="flex flex-row mb-1 w-full">

                                    <select x-bind:name="`products[${row.id}][product]`" x-model.number="row.product"
                                        required x-on:input.change="changeProduct($event.target.value, row.id)"
                                        class="relative m-0 flex basis-6/12 rounded-l border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">

                                        <option value="" selected disabled>не выбрано</option>
                                        <optgroup label="БЕТОН">
                                            @foreach ($products_block as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="БЛОК">
                                            @foreach ($products_concrete as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="ДОСТАВКА">
                                            @foreach ($products_delivery as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="ДРУГОЕ">
                                            @foreach ($products_another as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </optgroup>

                                    </select>

                                    <input x-model.number="row.count" min="0" step="any"
                                        x-on:input.change="changeCount(row.product, row.id)" type="number"
                                        x-bind:name="`products[${row.id}][count]`" required
                                        class="relative m-0 flex basis-1/12 border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary text-right"
                                        placeholder="количество" />

                                    <input x-model.number="row.price" type="number" step="any"
                                        x-bind:name="`products[${row.id}][price]`" required
                                        x-on:input.change="changePrice(row.product, row.id)"
                                        class="relative m-0 flex basis-2/12 border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary text-right"
                                        placeholder="цена" />

                                    <input x-model.number="row.sum" type="number" step="any"
                                        x-bind:name="`products[${row.id}][sum]`" required
                                        x-on:input.change="changeSum(row.product, row.id)"
                                        class="relative m-0 flex basis-3/12 border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary text-right"
                                        placeholder="сумма" />

                                    <button @click="removeRow(row)" type="button"
                                        class="w-6 justify-center text-lg rounded-full mx-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" data-slot="icon"
                                            class="w-6 h-6 m-auto fill-gray-400 hover:fill-red-500">
                                            <path fill-rule="evenodd"
                                                d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                </div>
                            </template>

                            <button @click="addNewRow()" type="button"
                                class="flex mx-auto text-center justify-center items-center border rounded font-semibold px-8 py-[0.25rem] text-gray-600 bg-gray-100 hover:bg-gray-200">
                                добавить товар
                            </button>


                            <div class="flex flex-row mb-1 w-full rounded p-2">
                                <div class="flex basis-10/12">
                                    <textarea name="comment" class="w-full rounded border-neutral-200" placeholder="Комментарий"></textarea>
                                </div>
                                <div class="flex flex-col basis-2/12 font-semibold">
                                    <div class="flex justify-between px-6">
                                        <label>ИТОГО:</label>
                                        <span class="" x-text="allSum">
                                    </div>
                                    <div class="flex justify-between px-6">
                                        <label></label>
                                        <span class="text-xs" x-text="allWeight">
                                    </div>
                                </div>

                            </div>

                        </div>

                        <script>
                            document.addEventListener('alpine:init', () => {
                                Alpine.data('products', () => ({
                                    entities: {!! $products !!},
                                    entity: '',
                                    rows: [{
                                        id: 0,
                                        product: '',
                                        count: 0,
                                        residual: 0,
                                        weight_kg: 0,
                                        weight: 0,
                                        price: 0,
                                        sum: 0
                                    }],

                                    allSum: 0,
                                    allWeight: 'Вес: ' + 0,
                                    allCount: 0,

                                    changeProduct(Id, index) {
                                        if (this.entities.find(x => x.id == Id) !== undefined) {
                                            if (this.rows[index]) {
                                                this.rows[index].weight_kg = +this.entities.find(x => x.id == Id).weight_kg
                                                this.rows[index].weight = +this.entities.find(x => x.id == Id).weight_kg *
                                                    this
                                                    .rows[index].count
                                                this.rows[index].price = this.entities.find(x => x.id == Id).price
                                                this.rows[index].residual = this.entities.find(x => x.id == Id).residual
                                                this.rows[index].sum = this.rows[index].price * this.rows[index].count
                                            }
                                            this.allSum = this.rows.map(item => item.sum).reduce((prev, curr) => prev +
                                                curr, 0);
                                            this.allWeight = 'Вес: ' + Math.round(this.rows.map(item => item.weight).reduce(
                                                (prev,
                                                    curr) => prev +
                                                curr, 0) * 100) / 100;
                                            this.allCount = this.rows.map(item => item.count).reduce((prev, curr) => prev +
                                                curr, 0);
                                        }
                                    },

                                    changeCount(Id, index) {
                                        if (this.entities.find(x => x.id == Id) !== undefined) {
                                            if (this.rows[index]) {
                                                this.rows[index].weight_kg = +this.entities.find(x => x.id == Id).weight_kg
                                                this.rows[index].weight = +this.entities.find(x => x.id == Id).weight_kg *
                                                    this
                                                    .rows[index].count
                                                //    this.rows[index].price = this.entities.find(x => x.id == Id).price
                                                this.rows[index].residual = this.entities.find(x => x.id == Id).residual
                                                this.rows[index].sum = this.rows[index].price * this.rows[index].count
                                            }
                                            this.allSum = this.rows.map(item => item.sum).reduce((prev, curr) => prev +
                                                curr, 0);
                                            this.allWeight = 'Вес: ' + Math.round(this.rows.map(item => item.weight).reduce(
                                                (prev,
                                                    curr) => prev +
                                                curr, 0) * 100) / 100;
                                            this.allCount = this.rows.map(item => item.count).reduce((prev, curr) => prev +
                                                curr, 0);
                                        }
                                    },

                                    changePrice(Id, index) {
                                        if (this.entities.find(x => x.id == Id) !== undefined) {
                                            if (this.rows[index]) {
                                                this.rows[index].weight_kg = +this.entities.find(x => x.id == Id).weight_kg
                                                this.rows[index].weight = +this.entities.find(x => x.id == Id).weight_kg *
                                                    this
                                                    .rows[index].count
                                                //    this.rows[index].price = this.entities.find(x => x.id == Id).price
                                                this.rows[index].residual = this.entities.find(x => x.id == Id).residual
                                                this.rows[index].sum = this.rows[index].price * this.rows[index].count
                                            }
                                            this.allSum = this.rows.map(item => item.sum).reduce((prev, curr) => prev +
                                                curr, 0);
                                            this.allWeight = 'Вес: ' + Math.round(this.rows.map(item => item.weight).reduce(
                                                (prev,
                                                    curr) => prev +
                                                curr, 0) * 100) / 100;
                                            this.allCount = this.rows.map(item => item.count).reduce((prev, curr) => prev +
                                                curr, 0);
                                        }
                                    },

                                    changeSum(Id, index) {
                                        if (this.entities.find(x => x.id == Id) !== undefined) {
                                            if (this.rows[index]) {
                                                this.rows[index].weight_kg = +this.entities.find(x => x.id == Id).weight_kg
                                                this.rows[index].weight = +this.entities.find(x => x.id == Id).weight_kg *
                                                    this
                                                    .rows[index].count
                                                this.rows[index].price = this.rows[index].sum / this.rows[index].count
                                                this.rows[index].residual = this.entities.find(x => x.id == Id).residual
                                                //    this.rows[index].sum = this.rows[index].price * this.rows[index].count
                                            }
                                            this.allSum = this.rows.map(item => item.sum).reduce((prev, curr) => prev +
                                                curr, 0);
                                            this.allWeight = 'Вес: ' + Math.round(this.rows.map(item => item.weight).reduce(
                                                (prev,
                                                    curr) => prev +
                                                curr, 0) * 100) / 100;
                                            this.allCount = this.rows.map(item => item.count).reduce((prev, curr) => prev +
                                                curr, 0);
                                        }
                                    },

                                    addNewRow() {
                                        this.rows.push({
                                            id: this.rows.length,
                                            product: '',
                                            count: 0,
                                            weight_kg: 0,
                                            weight: 0,
                                            price: 0,
                                            residual: 0,
                                            sum: 0
                                        });
                                    },

                                    removeRow(row) {
                                        this.rows.splice(this.rows.indexOf(row), 1);

                                        this.allSum = this.rows.map(item => item.sum).reduce((prev, curr) => prev + curr,
                                            0);
                                        this.allWeight = 'Вес: ' + this.rows.map(item => item.weight).reduce((prev, curr) =>
                                            prev +
                                            curr, 0);
                                        this.allCount = this.rows.map(item => item.count).reduce((prev, curr) => prev +
                                            curr, 0);
                                    }
                                }))
                            })
                        </script>

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
            min-height: 24rem;
        }

        .select2-selection,
        .select2-selection--single {
            padding: 5px;
            height: 37px !important;
        }

        .select2-selection__arrow {
            top: 4px !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            $(".select2").select2();

            $("#contact_name").select2({
                width: '372px',
                tags: true,
                ajax: {
                    delay: 250,
                    url: '/api/contacts/get',
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
                                    text: item.name,
                                    id: item.id,
                                    attr1: item.phone,
                                }
                            }),
                            pagination: {
                                more: (params.current_page * data.per_page) < data.total
                            }
                        };
                    }
                },
            });

            $("#contact_phone").select2({
                width: '372px',
                tags: true,
                ajax: {
                    delay: 250,
                    url: '/api/contacts/get',
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

            $('#contact_phone').on('select2:select', function(e) {
                var data = e.params.data;

                if (data.text !== data.id) {
                    if ($('#contact_name').find("option[value='" + data.id + "']").length) {
                        $('#contact_name').val(data.id).trigger('change');
                    } else {
                        var newOption = new Option(data.attr1, data.id, true, true);
                        $('#contact_name').append(newOption).trigger('change');
                    }
                }
            });

            $('#contact_name').on('select2:select', function(e) {
                var data = e.params.data;

                if (data.text !== data.id) {
                    if ($('#contact_phone').find("option[value='" + data.id + "']").length) {
                        $('#contact_phone').val(data.id).trigger('change');
                    } else {
                        var newOption = new Option(data.attr1, data.id, true, true);
                        $('#contact_phone').append(newOption).trigger('change');
                    }
                } else {
                    $('#contact_phone').val(null).trigger('change');
                }
            });
        });
    </script>
</x-app-layout>
