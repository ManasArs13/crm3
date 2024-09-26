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


    <div class="w-11/12 max-w-10xl mx-auto py-8">

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
            class="max-w-10xl mx-auto block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <div id="message" class="text-red-700"></div>
                <form action="{{ route($action, $entityItem->id) }}" method="post">
                    @csrf
                    @method('PATCH')

                    <div class="min-h-6 px-5 pb-3">

                        <div class="flex flex-row basis-full justify-end my-2">
                            <span class="font-light text-sm">обновлено: {{ $entityItem->updated_at }}</span>
                        </div>


                        {{-- Shipment --}}
                        <div class="w-full mb-2 flex flex-row">
                            <div class="basis-1/3">
                                <div class="flex flex-row mb-1 w-full">
                                    <span class="w-[170px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Отгрузка №</span>

                                    <input type="text" name="name" id="name" value="{{ $entityItem->name }}" readonly
                                           class="w-[137px] relative m-0 rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                                    <input type="datetime-local" min="2020-01-01"
                                           value="{{ date('Y-m-d h:m:s', strtotime($entityItem->created_at)) }}"
                                           name="date_created" required
                                           class="ml-2 relative m-0 rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />

                                </div>
                            </div>



                        </div>

                        {{-- Contacts --}}
                        <div class="flex flex-row mb-3 basis-full justify-between align-middle">

                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span class="w-[170px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Контрагент</span>

                                        <select name="contact" required class="select2 w-[350px]">
                                            @if ($entityItem->contact_id)
                                                <option value="{{ $entityItem->contact_id }}" selected>
                                                    {{ $entityItem->contact->name }}</option>
                                            @else
                                                <option value="" selected disabled>не выбрано</option>
                                            @endif

                                            @foreach ($contacts as $contact)
                                                <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                            @endforeach
                                        </select>

                                </div>
                            </div>

                            <div class="basic-1/2">

                                {{-- balance --}}
                                <div class="{{( $entityItem->contact->balance >=0)? "bg-green-300": "bg-red-300" }} p-1  rounded " >
                                    {{ $entityItem->contact->balance }}
                                </div>
                            </div>

                        </div>


                        <div class="flex flex-row mb-3 w-full">
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span class="w-[170px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Cтатус</span>

                                    <select name="status" required
                                        class="w-[350px] h-[37px] relative m-0 rounded border border-solid border-neutral-200 bg-blue-400 px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                        <option selected class="bg-white" value="{{ $entityItem->status }}">
                                            {{ $entityItem->status }}
                                        </option>
                                        <option value="Не оплачен" selected>Не оплачен</option>
                                        <option value="Оплачен" selected>Оплачен</option>
                                        <option class="bg-white" value="В долг знакомые">В долг знакомые</option>
                                        <option value="На руках" selected>На руках</option>
                                    </select>

                                </div>
                            </div>
                        </div>

                        {{-- Delivery --}}
                        <div class="flex flex-row mb-3 w-full">
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[170px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Транспорт</span>
                                    <select name="transport" required
                                            class="w-[350px] h-[37px] relative m-0 rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                        @if ($entityItem->transport)
                                            <option value="{{ $entityItem->transport_id }}" selected>
                                                {{ $entityItem->transport->name }}</option>
                                        @else
                                            <option value="" selected disabled>не выбрано</option>
                                        @endif
                                        @foreach ($transports as $transport)
                                            <option value="{{ $transport->id }}">{{ $transport->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row mb-3 w-full">
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="w-[170px] whitespace-nowrap px-3 py-[0.25rem] text-left text-base text-surface">
                                        Доставка</span>
                                    <select name="delivery" required class="select2 w-[350px]">
                                        @if ($entityItem->delivery_id)
                                            <option value="{{ $entityItem->delivery_id }}" selected>
                                                {{ $entityItem->delivery->name }}</option>
                                        @else
                                            <option value="" selected disabled>не выбрано</option>
                                        @endif
                                        <option value="{{ $entityItem->delivery_id }}" selected>
                                            {{ $entityItem->delivery->name }}</option>
                                        @foreach ($deliveries as $delivery)
                                            <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="w-full">

                        {{-- Products --}}
                        <div x-data="products">
                            <h4 class="text-left font-semibold text-lg my-3">Товары</h4>

                            <div class="flex flex-row mb-1 w-full bg-gray-100 rounded">
                                <div class="flex basis-8/12 justify-center text-gray-700">
                                    наименование
                                </div>
                                <div class="flex basis-1/12 justify-center text-gray-700">
                                    кол-во
                                </div>
                                <div class="flex basis-1/12 justify-center text-gray-700">
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
                                        required x-init="$watch('row', (row) => changeProduct(row.product, row.id))"
                                        class="relative m-0 flex basis-8/12 rounded-l border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">

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
                                        class="relative text-right m-0 flex basis-1/12 border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary"
                                        placeholder="количество" />

                                    <span x-text="row.price"
                                        class="flex basis-1/12 justify-end items-center whitespace-nowrap border border-solid border-neutral-200 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-gray-500 bg-gray-100">
                                    </span>
                                    <span x-text="row.sum"
                                        class="flex basis-2/12 justify-end items-center whitespace-nowrap border border-solid border-neutral-200 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-gray-500 bg-gray-100">
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

                            <input type="text" class="hidden" name="weight" x-model="allWeight">

                            <button @click="addNewRow()" type="button"
                                class="flex mx-auto text-center justify-center items-center border rounded font-semibold px-8 py-[0.25rem] text-gray-600 bg-gray-100 hover:bg-gray-200">
                                добавить товар
                            </button>


                            <div class="flex flex-row mb-1 w-full rounded p-2">
                                <div class="flex basis-10/12">
                                    <textarea name="comment" class="w-full rounded border-neutral-200" placeholder="Комментарий">{{ $entityItem->description }}</textarea>
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
                                        positions = {!! $positions !!};
                                        ents = {!! $products !!};
                                        this.entities = ents;

                                        let row_i = 0;

                                        if (positions.length > 0) {
                                            this.rows = positions.map(pos => ({
                                                id: row_i++,
                                                product: pos.product_id,
                                                count: pos.quantity,
                                                residual: ents.find(x => x.id == pos.product_id) && ents.find(
                                                    x => x.id == pos.product_id).residual,
                                                sum: ents.find(x => x.id == pos.product_id) && ents.find(x =>
                                                    x.id == pos.product_id).price ? Math.round(
                                                    ents.find(x => x.id == pos.product_id)
                                                    .price * pos.quantity * 100) / 100 : 0,
                                                price: pos.price,
                                            }));

                                            this.allSum = this.rows.map(item => item.sum).reduce((prev, curr) => prev +
                                                curr, 0);
                                            this.allWeight = 'Вес: ' + {!! $entityItem->weight !!};
                                            this.allCount = this.rows.map(item => item.count).reduce((prev, curr) => prev +
                                                curr, 0);
                                        }
                                    },
                                    entities: [],
                                    rows: [{
                                        id: 0,
                                        product: '',
                                        count: 0,
                                        residual: 0,
                                        // weight_kg: 0,
                                        // weight: 0,
                                        price: 0,
                                        sum: 0
                                    }],
                                    allSum: 0,
                                    allWeight: 0,
                                    allCount: 0,

                                    changeProduct(Id, index) {
                                        if (this.entities.find(x => x.id == Id) !== undefined) {
                                            if (this.rows[index]) {
                                                this.rows[index].weight_kg = +this.entities.find(x => x.id ==
                                                        Id)
                                                    .weight_kg
                                                this.rows[index].weight = Math.round(this.entities.find(x => x.id == Id)
                                                    .weight_kg * this
                                                    .rows[index].count * 100) / 100;
                                                this.rows[index].price = this.entities.find(x => x.id == Id)
                                                    .price
                                                this.rows[index].residual = this.entities.find(x => x.id == Id)
                                                    .residual
                                                this.rows[index].sum = this.rows[index].price * this.rows[index]
                                                    .count
                                            }
                                            this.allSum = this.rows.map(item => item.sum).reduce((prev, curr) => prev +
                                                curr, 0);
                                            this.allWeight = 'Вес: ' + Math.round(this.rows.map(item => item.weight)
                                                .reduce((prev, curr) => prev + curr, 0) * 100) / 100;

                                            this.allCount = this.rows.map(item => item.count).reduce((prev,
                                                    curr) =>
                                                prev +
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
                                        });
                                    },

                                    removeRow(row) {
                                        this.rows.splice(this.rows.indexOf(row), 1);

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
                        <button type="submit"
                            class=" p-1 bg-yellow-500 hover:bg-yellow-600 text-white hover:text-gray-700 rounded font-bold uppercase">Обновить</button>

                        <button formaction="/api/shipment_ms/create" data-id= "{{ $entityItem->id }}"
                            class=" p-1 bg-green-500 hover:bg-green-600 text-white hover:text-gray-700 rounded font-bold uppercase create_to_ms">Отправить
                            в мс</button>

                        <div class="ml-auto">
                            @if ($entityItem->ms_id != null)
                                <a href="https://online.moysklad.ru/app/#demand/edit?id={{ $entityItem->ms_id }}"
                                   target="_blank" class="p-2 mr-1 text-sm bg-slate-400 hover:bg-slate-500 text-white rounded uppercase">
                                    Перейти в мс
                                </a>
                            @endif
                            <button onclick="printShipment({{ $entityItem->id }})" type="button"
                                    class="p-1 bg-slate-400 hover:bg-slate-500 text-white rounded uppercase">Распечать</button>
                            <button form="formDelete" type="submit" class="p-1 bg-slate-400 hover:bg-slate-500 text-white rounded uppercase">Удалить</button>
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





    </div>

    <div class="w-11/12 max-w-10xl mx-auto py-8">
        <div class="mx-auto block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

                <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                {{--привязанный заказ --}}

                @if ($entityItem->order)
                <table class="text-left text-md text-nowrap">
                    <tbody>
                        <tr class="border-b-2 bg-gray-100 py-2">
                            <td class="px-6 py-4" style="text-align:right">
                                <a href="{{ route("order.show", ["order"=>$entityItem->order->id] )}}">{{ $entityItem->order->name }}</a>
                            </td>
                            <td class="px-6 py-4" style="text-align:right">
                                {{ $entityItem->order->created_at }}
                            </td>
                            <td class="px-6 py-4" style="text-align:left">
                                {{ $entityItem->order->contact->name }}
                            </td>
                            <td class="px-6 py-4" style="text-align:right">
                                {{ $entityItem->order->sum }}
                            </td>
                            <td class="px-6 py-4" style="text-align:left">
                                {{ $entityItem->order->status->name }}
                            </td>
                            <td class="px-6 py-4" style="text-align:left">
                                {{ $entityItem->order->comment }}
                            </td>
                            <td class="px-6 py-4" style="text-align:left">
                                {{ $entityItem->order->delivery_price }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                @endif
                </div>
            </div>

    </div>


    <style>
        .select2-selection, .select2-selection--single{
            padding: 5px;
            height: 37px !important;
        }
        .select2-selection__arrow{
            top: 4px !important;
        }
    </style>

    <script>
        function printShipment(shipmentId) {
            var printUrl = '{{ route('print.shipment') }}';


            fetch(printUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: shipmentId })
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
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: `{{ route($searchOrders) }}`,
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
                                    text: item.name,
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
