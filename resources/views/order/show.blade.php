<x-app-layout>

    <x-slot:head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        @vite(['resources/js/main.js'])
    </x-slot>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entity }}{{ $entityItem->name }}
        </x-slot>
    @endif


    <div class="w-11/12 max-w-10xl mx-auto py-8 mb-3">

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
            <h3 class="text-4xl font-bold mb-6">{{ $entity }}{{ $entityItem->id }}</h3>
        @endif

        <div
            class="mx-auto block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <div id="message" class="text-red-700"></div>
                <form action="{{ route($action, $entityItem->id) }}" method="post">
                    @csrf
                    @method("PATCH")

                    <div class="min-h-6 px-5 pb-3">

                        <div class="flex flex-row basis-full justify-end my-2">
                            <span class="font-light text-sm">обновлено: {{ $entityItem->updated_at}}</span>
                        </div>

                        {{-- Order --}}
                        <div class="w-full mb-2 flex flex-row gap-3">
                            <div class="flex flex-row basis-2/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="flex basis-[42%] items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        Заказ №</span>
                                    <input type="text" value="{{ $entityItem->id }}"

                                        readonly
                                        class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />


                                        @if ($entityItem->ms_id!=null)
                                        <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}" target="_blank" class="flex justify-center items-center ml-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                </path>
                                                <path fill-rule="evenodd" d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                </path>
                                            </svg>
                                        </a>
                                        @endif
                                </div>

                                <div class="flex flex-row mb-1 w-full">

                                    <span
                                        class="flex basis-[42%] items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        мс №</span>
                                    <input type="text" value="{{ $entityItem->name }}"
                                        readonly
                                        class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />

                                </div>


                            </div>
                            <div class="flex flex-row mb-1 w-full">
                                <div class="flex flex-row">
                                    <span
                                        class="basis-1/4 flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        дата создания</span>
                                    <input type="datetime-local" min="2020-01-01"
                                        value="{{ date('Y-m-d h:m:s', strtotime($entityItem->created_at)) }}"
                                        name="date_created" required
                                        class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                                </div>
                            </div>

                        </div>

                        {{-- Contacts --}}
                        <div class="flex flex-row mb-3 w-full justify-between">
                            <div class="flex flex-row basis-2/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="basis-[15%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        Контрагент</span>
                                    <select name="contact" required style="width:35%" class="select2">
                                        <option value="{{ $entityItem->contact_id }}" selected>
                                            {{ $entityItem->contact->name }}</option>
                                        @foreach ($contacts as $contact)
                                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                        @endforeach
                                    </select>


                                    {{-- Add contact button --}}
                                    <button type="button" id="button-modal"
                                        class="inline-block rounded  align-middle text-black hover:text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                                {{-- balance --}}
                                <div class="{{( $entityItem->contact->balance >=0)? "bg-green-300": "bg-red-300" }} p-1 px-2 rounded " >
                                    {{ $entityItem->contact->balance }}
                                </div>

                        </div>

                        {{-- Delivery --}}
                        <div class="flex flex-row mb-3 w-full gap-3">
                            <div class="basis-1/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="flex basis-[41%]  items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        Доставка</span>
                                    <select name="delivery" required class="select2" style="width: 100%">
                                        @if ($entityItem->delivery_id)
                                            <option value="{{ $entityItem->delivery_id }}" selected>
                                                {{ $entityItem->delivery->name }}</option>
                                        @else
                                            <option value="" selected disabled>не выбрано</option>
                                        @endif
                                        @foreach ($deliveries as $delivery)
                                            <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                        </div>

                        {{-- status --}}
                        <div class="flex flex-row mb-5 w-full">
                            <div class="basis-1/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="flex basis-[41%] items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        Статус</span>
                                    <select name="status" required
                                        class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-blue-400 px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                        <option selected class="bg-white" value="{{ $entityItem->status_id }}">
                                            {{ $entityItem->status->name }}
                                        </option>
                                        @foreach ($statuses as $status)
                                            <option class="bg-white" value="{{ $status->id }}">{{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-row mb-5 w-full">
                            <div class="basis-1/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="flex basis-[41%] items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        Адрес</span>
                                        <input type="text"  name="address" required value="{{ $entityItem->address }}"  class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                </div>
                            </div>
                        </div>
                        {{-- Date --}}

                        <div class="flex flex-row mb-5 w-full">
                            <div class="flex flex-row">
                                <span style="width:152px" class="basis-[100%] flex items-center whitespace-nowrap px-2 py-[0.25rem] text-center text-base text-surface">
                                    Плановая дата</span>
                                <input type="date" min="2020-01-01" value="{{ date('Y-m-d', strtotime($entityItem->date_plan)) }}" name="date" required="" class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                            </div>
                            <div class="flex flex-row">
                                <span class="basis-[10%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                    время</span>
                                <select name="time" class="rounded border border-solid border-neutral-400">
                                    <option value="{{ date('h', strtotime($entityItem->date_plan)) }}:00">{{ date('h', strtotime($entityItem->date_plan)) }}:00</option>
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
                                <div class="flex basis-[44%] justify-center text-gray-700">
                                    наименование
                                </div>
                                <div class="flex basis-[20%] justify-center text-gray-700">
                                    кол-во
                                </div>
                                <div class="flex basis-1/12 justify-center text-gray-700">
                                    отг-но
                                </div>
                                <div class="flex basis-2/12 justify-center text-gray-700">
                                    цена
                                </div>
                                <div class="flex basis-2/12 justify-center text-gray-700">
                                    сумма
                                </div>
                                <div class="w-6 mx-2">

                                </div>
                            </div>
                            <template x-for="(row, index) in rows" :key="index">
                                <div class="flex flex-row mb-1 w-full">

                                    <select x-bind:name="`products[${row.id}][product]`" x-model.number="row.product"
                                        x-init="$watch('row', (row) => changeProduct(row.product, row.id))"
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

                                    <input x-model.number="row.count" x-init="$watch('row', (row) => changeProduct(row.product, row.id))" min="0"
                                        type="number" x-bind:name="`products[${row.id}][count]`" required
                                        class="relative m-0 flex text-right basis-1/12 border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary"
                                        placeholder="количество" />

                                    <span x-text="row.shipped"
                                        class="flex basis-1/12 justify-end items-center whitespace-nowrap border border-solid border-neutral-200 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-gray-500 bg-gray-100">
                                    </span>
                                    <span x-text="row.price"
                                        class="flex basis-2/12 justify-end items-center whitespace-nowrap border border-solid border-neutral-200 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-gray-500 bg-gray-100">
                                    </span>

                                    <span x-text="row.sum"
                                        class="flex justify-end basis-2/12 overflow-hidden rounded-r items-center whitespace-nowrap border border-solid border-neutral-200 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-gray-500 bg-gray-100">
                                    </span>

                                    <button @click="removeRow(row)" type="button"
                                        class="justify-center text-lg rounded-full mx-2">
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
                                <div class="flex basis-8/12">
                                    <textarea name="comment"
                                     class="w-full rounded border-neutral-200" placeholder="Комментарий">{{ $entityItem->comment }}</textarea>
                                </div>
                                <div class="flex flex-col basis-4/12 font-semibold">
                                    <div class="flex justify-between px-6">
                                        <label>Количество:</label>
                                        <span class="" x-text="allCount">
                                    </div>
                                    <div class="flex justify-between px-6">
                                        <label>Общий вес:</label>
                                        <span class="" x-text="allWeight">
                                    </div>
                                    <div class="flex justify-between px-6">
                                        <label>ИТОГО:</label>
                                        <span class="" x-text="allSum">
                                    </div>
                                </div>

                            </div>

                        </div>

                        <script>
                            document.addEventListener('alpine:init', () => {
                                Alpine.data('products', () => ({
                                    init() {
                                        positions = {!! $entityItem->positions !!};
                                        ents = {!! $products !!};
                                        this.entities = ents;

                                        let row_i = 0;

                                        if (positions.length > 0) {
                                            this.rows = positions.map(pos => ({
                                                id: row_i++,
                                                product: pos.product_id,
                                                count: pos.quantity,
                                                shipped: pos.shipped,
                                                residual: ents.find(x => x.id == pos.product_id) && ents.find(
                                                    x => x.id == pos.product_id).residual,
                                                weight_kg: ents.find(x => x.id == pos.product_id) && ents.find(
                                                    x => x.id == pos.product_id).weight_kg ? ents.find(x =>
                                                    x.id == pos.product_id).weight_kg : 0,
                                                weight: ents.find(x => x.id == pos.product_id) && ents.find(x =>
                                                    x.id == pos.product_id).weight_kg ? Math.round(
                                                    ents.find(x => x.id == pos.product_id)
                                                    .weight_kg * pos.quantity * 100) / 100 : 0,
                                                price: pos.price,
                                                sum: pos.price * pos.quantity
                                            }));

                                            this.allSum = this.rows.map(item => item.sum).reduce((prev, curr) => prev +
                                                curr, 0);
                                            this.allWeight = Math.round(this.rows.map(item => item.weight).reduce((prev,
                                                    curr) => prev +
                                                curr, 0) * 100) / 100;
                                            this.allCount = this.rows.map(item => item.count).reduce((prev, curr) => prev +
                                                curr, 0);
                                        }
                                    },
                                    entities: [],
                                    rows: [{
                                        id: 0,
                                        product: '',
                                        count: 0,
                                        shipped: 0,
                                        residual: 0,
                                        weight_kg: 0,
                                        weight: 0,
                                        price: 0,
                                        sum: 0
                                    }],

                                    allSum: 0,
                                    allWeight: 0,
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
                                            this.allWeight = Math.round(this.rows.map(item => item.weight).reduce((prev,
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
                                            shipped: 0,
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
                                        this.allWeight = this.rows.map(item => item.weight).reduce((prev, curr) => prev +
                                            curr, 0);
                                        this.allCount = this.rows.map(item => item.count).reduce((prev, curr) => prev +
                                            curr, 0);
                                    }
                                }))
                            })
                        </script>

                    </div>

                    <div class="px-5 mb-3 w-full flex flex-row gap-3">
                        <button type="submit" name="action" value="save"
                            class=" p-1 bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-700 rounded font-bold uppercase">Обновить</button>

                        <button formaction="/api/order_ms/create2" data-id= "{{ $entityItem->id }}"
                                class=" p-1 bg-green-500 hover:bg-green-600 text-white hover:text-gray-700 rounded font-bold uppercase create_to_ms">Отправить
                                в мс</button>
                        <button type="submit" name="action" value="create"

                            class="p-1 bg-green-500 hover:bg-green-600 text-white hover:text-gray-700 rounded font-bold uppercase create_to_ms">Создать отгрузку
                        </button>
                        <div class="ml-auto">
                            <button onclick="printOrder({{ $entityItem->id }})" type="button"
                                    class="p-1 bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-700 rounded font-bold uppercase">Распечать</button>
                            <button form="formDelete" type="submit" class="p-1 bg-red-500 hover:bg-red-600 text-white hover:text-gray-700 rounded font-bold uppercase">Удалить</button>
                        </div>
                    </div>
                </form>
                <form id="formDelete" action="{{ route($urlDelete, $entityItem->id) }}" method="Post"
                      class="block px-4 text-sm font-medium text-red-500 hover:bg-gray-100 cursor-pointer">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>

        <div class="hidden absolute top-[13%] border-2 border-black w-full bg-gray-200 text-center p-5 mx-auto z-50 rounded-md shadow-lg max-w-7xl"
            id="contact-modal">
            <div class="absolute top-5 right-5 cursor-pointer" id="close-modal">закрыть</div>
            <h4 class="text-3xl max-w-7xl mx-auto font-bold mb-6">Добавить контакт</h4>
            <form action="{{ route($newContact) }}" method="post" id="newContact">
                @csrf
                @method('post')
                <div class="flex flex-row w-full px-1">
                    <span
                        class="flex basis-[11%] items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                        Имя</span>
                    <input type="text" name="name" required placeholder="ФИО или название контрагента"
                        class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                </div>
                <div class="flex flex-row w-full px-1 my-2">
                    <div class="flex basis-1/2">
                        <span
                            class="flex basis-1/4 items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                            Телефон</span>
                        <input type="tel" name="tel" placeholder="+7(000)000-00-00" required
                            class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                    </div>
                    <div class="flex basis-1/2">
                        <span
                            class="flex basis-1/4 items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                            Почта</span>
                        <input type="mail" name="mail" placeholder="example@example.com" required
                            class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                    </div>
                </div>
                <div class="flex flex-row w-full px-1 my-2">
                    <button type="submit"
                        class="w-full p-1 bg-green-500 hover:bg-green-400 text-white hover:text-gray-700 rounded font-semibold uppercase">сохранить
                        контакт</button>
                </div>
            </form>
        </div>

    </div>



    <div class="w-11/12 max-w-10xl mx-auto py-8">
        <div class="mx-auto block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

                <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                {{--привязанный заказ --}}

                @if (count($entityItem->shipments)>0)
                <table class="text-left text-md text-nowrap">

                    <tbody>
                        @foreach ($entityItem->shipments as $sh)
                        <tr class="border-b-2 bg-gray-100 py-2">
                            <td class="px-6 py-4" style="text-align:right">
                                <a href="{{ route("shipment.show", ["shipment"=>$sh->id]) }}">{{ $sh->name }}</a>
                            </td>
                            <td class="px-6 py-4" style="text-align:right">
                                {{ $sh->created_at }}
                            </td>
                            <td class="px-6 py-4" style="text-align:left">
                                {{ $sh->contact->name }}
                            </td>
                            <td class="px-6 py-4" style="text-align:right">
                                {{ $sh->suma }}
                            </td>
                            <td class="px-6 py-4" style="text-align:left">
                                {{ $sh->status}}
                            </td>
                            <td class="px-6 py-4" style="text-align:left">
                                {{ $sh->comment }}
                            </td>
                            <td class="px-6 py-4" style="text-align:left">
                                {{ $sh->delivery_price }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                </div>
            </div>

    </div>

    <style>
        .select2-container--default .select2-results>.select2-results__options {
            min-height: 24rem;
        }
    </style>
    <script>
        function printOrder(orderId) {
            var printUrl = '{{ route('print.order') }}';


            fetch(printUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Добавляем CSRF-токен
                },
                body: JSON.stringify({ id: orderId })
            })
                .then(response => response.text())
                .then(html => {

                    var printFrame = document.createElement('iframe');
                    printFrame.style.position = 'absolute';
                    printFrame.style.width = '0px';
                    printFrame.style.height = '0px';
                    printFrame.style.border = 'none';


                    document.body.appendChild(printFrame);


                    var frameDoc = printFrame.contentWindow.document;
                    frameDoc.open();
                    frameDoc.write(html);
                    frameDoc.close();

                    printFrame.onload = function() {
                        printFrame.contentWindow.focus();
                        printFrame.contentWindow.print();

                        document.body.removeChild(printFrame);
                    };
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
        }
        $(document).ready(function() {
            //change selectboxes to selectize mode to be searchable
            $(".select2").select2();
        });

        document.addEventListener('DOMContentLoaded', function() {

            var modal = document.getElementById("contact-modal");
            var btn = document.getElementById("button-modal");
            var close = document.getElementById('close-modal');

            btn.onclick = function() {
                modal.style.display = "block";
            }

            close.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>
</x-app-layout>
