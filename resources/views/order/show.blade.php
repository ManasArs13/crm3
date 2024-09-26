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
                    @method('PATCH')

                    <div class="min-h-6 px-5 pb-3">

                        <div class="flex flex-row basis-full justify-end my-2">
                            <span class="font-light text-sm">обновлено: {{ $entityItem->updated_at }}</span>
                        </div>

                        {{-- Order --}}
                        <div class="w-full mb-3 flex flex-row gap-3">
                            <div class="flex flex-row basis-2/3">
                                <span class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface"> Заказ №</span>
                                    <input type="text" value="{{ $entityItem->name }}" readonly
                                        class="w-[127px] relative m-0 rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                                <input type="datetime-local" min="2020-01-01"
                                       value="{{ date('Y-m-d h:m:s', strtotime($entityItem->created_at)) }}"
                                       name="date_created" required
                                       class="w-[216px] ml-2 relative m-0 rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />

                            </div>


                        </div>

                        {{-- Contacts --}}
                        <div class="flex flex-row mb-3 w-full justify-between">
                            <div class="flex flex-row basis-2/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Контрагент</span>
                                    <select name="contact" required class="select2 w-[309px]">
                                        <option value="{{ $entityItem->contact_id }}" selected>
                                            {{ $entityItem->contact->name }}</option>
                                        @foreach ($contacts as $contact)
                                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                        @endforeach
                                    </select>


                                    {{-- Add contact button --}}
                                    <button type="button" id="button-modal"
                                            class="inline-block rounded border border-solid border-neutral-400 hover:bg-neutral-200 ml-1 p-2 align-middle text-black hover:text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 367 368" >
                                            <path d="M171.7 2.39681C168.8 3.89681 165.3 6.49681 164 8.29681C158.3 15.7968 158.5 12.8968 158.5 88.7968V158.897L87.2 159.097L15.8 159.397L10.9 162.397C7.9 164.297 4.9 167.297 3 170.297C0.3 174.797 0 175.997 0 183.897C0 191.797 0.3 192.997 3 197.497C4.9 200.497 7.9 203.497 10.9 205.397L15.8 208.397L87.2 208.697L158.5 208.897L158.7 280.197L159 351.597L162 356.497C163.9 359.497 166.9 362.497 169.9 364.397C174.4 367.097 175.6 367.397 183.5 367.397C191.4 367.397 192.6 367.097 197.1 364.397C200.1 362.497 203.1 359.497 205 356.497L208 351.597L208.3 280.197L208.5 208.897L279.8 208.697L351.2 208.397L356.1 205.397C359.1 203.497 362.1 200.497 364 197.497C366.7 192.997 367 191.797 367 183.897C367 175.997 366.7 174.797 364 170.297C362.1 167.297 359.1 164.297 356.1 162.397L351.2 159.397L279.8 159.097L208.5 158.897L208.3 87.5968L208 16.1968L205 11.2968C200.4 3.89681 194.1 0.496811 184.6 0.0968114C178 -0.203189 176.3 0.0968114 171.7 2.39681Z" fill="#AAAAAA"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- balance --}}
                            <div
                                class="{{ $entityItem->contact->balance >= 0 ? 'bg-green-300' : 'bg-red-300' }} p-1 px-2 rounded ">
                                {{ $entityItem->contact->balance }}
                            </div>

                        </div>

                        {{-- Delivery --}}
                        <div class="flex flex-row mb-3 w-full gap-3">
                            <div class="basis-1/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Доставка</span>
                                    <select name="delivery" required class="select2 w-[350px]">
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
                        <div class="flex flex-row mb-3 w-full">
                            <div class="basis-1/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Статус</span>
                                    <select name="status" required style="background-color: {{ $entityItem->status->color }}"
                                        class="w-[350px] h-[37px] relative m-0 rounded border border-solid border-neutral-300 px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                        <option selected style="background-color: {{ $entityItem->status->color }}"
                                            value="{{ $entityItem->status_id }}">
                                            {{ $entityItem->status->name }}
                                        </option>
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

                        <div class="flex flex-row mb-3 w-full">
                            <div class="basis-1/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[150px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Адрес</span>
                                    <input type="text" name="address" required value="{{ $entityItem->address }}"
                                        class="w-[350px] relative m-0 rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                </div>
                            </div>
                        </div>
                        {{-- Date --}}

                        <div class="flex flex-row mb-5 w-full">
                            <div class="flex flex-row">
                                <span style="width:152px"
                                    class="basis-[100%] flex items-center whitespace-nowrap px-2 py-[0.25rem] text-center text-base text-surface">
                                    Плановая дата</span>
                                <input type="date" min="2020-01-01"
                                    value="{{ date('Y-m-d', strtotime($entityItem->date_plan)) }}" name="date"
                                    required=""
                                    class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                            </div>
                            <div class="flex flex-row">
                                <span
                                    class="basis-[10%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                    время</span>
                                <select name="time" class="rounded border border-solid border-neutral-400">
                                    <option value="{{ date('h', strtotime($entityItem->date_plan)) }}:00">
                                        {{ date('h', strtotime($entityItem->date_plan)) }}:00</option>
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
                                        x-on:input.change="changeProduct($event.target.value, row.id)"
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

                                    <input x-model.number="row.count" min="0"
                                        x-on:input.change="changeCount(row.product, row.id)" type="number"
                                        x-bind:name="`products[${row.id}][count]`" required
                                        class="relative m-0 flex basis-1/12 border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary text-right"
                                        placeholder="количество" />

                                    <span x-text="row.shipped"
                                        class="flex basis-1/12 justify-end items-center whitespace-nowrap border border-solid border-neutral-200 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-gray-500 bg-gray-100">
                                    </span>
                                    <input x-model.number="row.price" type="number"
                                        x-bind:name="`products[${row.id}][price]`" required
                                        x-on:input.change="changePrice(row.product, row.id)"
                                        class="relative m-0 flex basis-2/12 border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary text-right"
                                        placeholder="цена" />

                                    <input x-model.number="row.sum" type="number"
                                        x-bind:name="`products[${row.id}][sum]`" required
                                        x-on:input.change="changeSum(row.product, row.id)"
                                        class="relative m-0 flex basis-2/12 border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary text-right"
                                        placeholder="сумма" />

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
                                <div class="flex basis-10/12">
                                    <textarea name="comment" class="w-full rounded border-neutral-200" placeholder="Комментарий">{{ $entityItem->comment }}</textarea>
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
                                            this.allWeight = 'Вес: ' + Math.round(this.rows.map(item => item.weight).reduce((prev,
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
                                        this.allWeight = 'Вес: ' + this.rows.map(item => item.weight).reduce((prev, curr) => prev +
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
                        <a href="{{ route('shipment.createFromOrder', ['orderId' => $entityItem->id]) }}"
                            class="p-1 bg-green-500 hover:bg-green-600 text-white hover:text-gray-700 rounded font-bold uppercase">Создать
                            отгрузку
                        </a>
                        <div class="ml-auto">
                            @if ($entityItem->ms_id != null)
                                <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}"
                                   target="_blank" class="p-2 mr-1 text-sm bg-slate-400 hover:bg-slate-500 text-white rounded uppercase">
                                    Перейти в мс
                                </a>
                            @endif
                            <button onclick="printOrder({{ $entityItem->id }})" type="button"
                                class="p-1 bg-slate-400 hover:bg-slate-500 text-white rounded uppercase">Распечать</button>
                            <button form="formDelete" type="submit"
                                class="p-1 bg-slate-400 hover:bg-slate-500 text-white rounded uppercase">Удалить</button>
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
        <div
            class="mx-auto block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                {{-- привязанный заказ --}}

                @if (count($entityItem->shipments) > 0)
                    <table class="text-left text-md text-nowrap">

                        <tbody>
                            @foreach ($entityItem->shipments as $sh)
                                <tr class="border-b-2 bg-gray-100 py-2">
                                    <td class="px-6 py-4" style="text-align:right">
                                        <a
                                            href="{{ route('shipment.show', ['shipment' => $sh->id]) }}">{{ $sh->name }}</a>
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
                                        {{ $sh->status }}
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
        .select2-selection, .select2-selection--single{
            padding: 5px;
            height: 37px !important;
        }
        .select2-selection__arrow{
            top: 4px !important;
        }
    </style>
    <script>
        function printOrder(orderId) {
            var printUrl = '{{ route('print.order') }}';


            fetch(printUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content') // Добавляем CSRF-токен
                    },
                    body: JSON.stringify({
                        id: orderId
                    })
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

            $('select').on('change', function() {
                $(this).css({
                    backgroundColor: $(this).find('option:selected').data('color')
                });
            });
        });
    </script>
</x-app-layout>
