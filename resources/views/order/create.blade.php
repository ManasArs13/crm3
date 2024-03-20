<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            Добавить {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <form action="{{ route($action) }}" method="post">
                    @csrf
                    @method('post')

                    <div class="min-h-6 p-5">

                        {{-- Order --}}
                        <div class="w-full mb-2 flex flex-row gap-3">
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="flex basis-1/4 items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        Заказ №</span>
                                    <input type="number" name="name" min="79999" value="{{ strtotime($date) }}" required
                                        class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                                </div>
                            </div>
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                    <span
                                        class="flex basis-1/4 items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                        Статус</span>
                                    <select name="status" required
                                        class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-blue-400 px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                        @foreach ($statuses as $status)
                                            <option class="bg-white" value="{{ $status->id }}">{{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Contacts --}}
                        <div class="flex flex-row mb-3 w-full">
                            <span
                                class="basis-[11%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                Контрагент</span>
                            <select name="contact" required
                                class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                <option value="" selected disabled>не выбрано</option>
                                @foreach ($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Delivery --}}
                        <div class="flex flex-row mb-3 w-full gap-3">
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                <span
                                    class="basis-1/4 flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                    Транспорт</span>
                                <select name="transport" required
                                    class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                    <option value="" selected disabled>не выбрано</option>
                                    @foreach ($transports as $transport)
                                        <option value="{{ $transport->id }}">{{ $transport->name }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="basis-1/2">
                                <div class="flex flex-row mb-1 w-full">
                                <span
                                    class="basis-1/4 flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                    Доставка</span>
                                <select name="delivery" required
                                    class="relative m-0 flex basis-full rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                    <option value="" selected disabled>не выбрано</option>
                                    @foreach ($deliveries as $delivery)
                                        <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="flex flex-row mb-5 w-full">
                            <span
                                class="basis-[10%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                Плановая дата</span>
                            <input type="datetime-local" min="0" value="{{ $date }}" name="date" required
                                class="relative m-0 flex basis-1/5 rounded border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />
                        </div>

                        <hr class="w-full">

                        {{-- Products --}}
                        <div x-data="products">
                            <h4 class="text-left font-semibold text-lg my-3">Товары</h4>

                            <template x-for="(row, index) in rows" :key="index">
                                <div class="flex flex-row mb-3 w-full">

                                    <select x-bind:name="`products[${row.id}][product]`"
                                        x-model.number="row.product" x-init="$watch('row', (row) => changeProduct(row.product, row.id))"
                                        class="relative m-0 flex basis-full rounded-l border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">

                                        <option value="" selected disabled>не выбрано</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach

                                    </select>

                                    <input x-model.number="row.count" x-init="$watch('row', (row) => changeProduct(row.product, row.id))" min="1"
                                        x-bind:max="row.residual" type="number" x-bind:name="`products[${row.id}][count]`" required
                                        class="relative m-0 flex basis-1/5 border border-solid border-neutral-200 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary"
                                        placeholder="количество" />


                                    <span x-text="row.weight_kg"
                                        class="flex basis-1/6 items-center whitespace-nowrap border border-solid border-neutral-200 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-gray-500 bg-gray-100">
                                    </span>
                                    <span x-text="row.price"
                                        class="flex basis-1/6 items-center whitespace-nowrap border border-solid border-neutral-200 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-gray-500 bg-gray-100">
                                    </span>
                                    <span x-text="row.sum"
                                        class="flex basis-1/6 rounded-r items-center whitespace-nowrap border border-solid border-neutral-200 px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-gray-500 bg-gray-100">
                                    </span>

                                    <button @click="removeRow(row)" type="button"
                                        class="flex basis-1/10 items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-surface text-red-600 hover:text-red-700">
                                        удалить
                                    </button>

                                </div>
                            </template>

                            <div class="flex flex-row mb-3 w-full">
                                <button @click="addNewRow()" type="button"
                                    class="flex mx-auto text-center items-center border rounded whitespace-nowrap px-3 py-[0.25rem] text-lg font-normal leading-[1.6] text-surface text-green-600 hover:text-green-700 bg-gray-100">
                                    добавить товар
                                </button>
                            </div>
                        </div>

                        <hr class="w-full">

                        {{-- Comment --}}
                        <div class="flex flex-row my-5 w-full">
                            <textarea name="comment" class="w-full rounded border-neutral-200" placeholder="Комментарий"></textarea>
                        </div>
                        <script>
                            document.addEventListener('alpine:init', () => {
                                Alpine.data('products', () => ({
                                    entities: {!! $products !!},
                                    entity: '',
                                    rows: [{
                                        id: 0,
                                        product: '',
                                        count: null,
                                        residual: 0,
                                        weight_kg: 'вес',
                                        price: 'цена',
                                        sum: 'сумма'
                                    }],

                                    changeProduct(Id, index) {
                                        if (this.entities.find(x => x.id == Id) !== undefined) {
                                            this.rows[index].weight_kg = this.entities.find(x => x.id == Id).weight_kg
                                            this.rows[index].price = this.entities.find(x => x.id == Id).price
                                            this.rows[index].residual = this.entities.find(x => x.id == Id).residual
                                            this.rows[index].sum = this.rows[index].price * this.rows[index].count
                                        }
                                    },

                                    addNewRow() {
                                        this.rows.push({
                                            id: this.rows.length,
                                            product: '',
                                            count: null,
                                            weight_kg: 'вес',
                                            price: 'цена',
                                            residual: 0,
                                            sum: 'сумма'
                                        });
                                    },

                                    removeRow(row) {
                                        this.rows.splice(this.rows.indexOf(row), 1);
                                    }
                                }))
                            })
                        </script>

                    </div>

                    <div class="p-5 w-full">
                        <button type="submit"
                            class="w-full p-2 bg-green-400 hover:bg-green-600 rounded">{{ __('label.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
