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

    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ $entity }}</h3>
        @endif
        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04) relative">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <div class="flex flex-row gap-1">
                        <div>
                            @if (url()->current() == route('incomings.index'))
                                <a href="{{ route('incomings.index') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Приход</a>
                            @else
                                <a href="{{ route('incomings.index') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Приход</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('incomings.products'))
                                <a href="{{ route('incomings.products') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Состав</a>
                            @else
                                <a href="{{ route('incomings.products') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Состав</a>
                            @endif
                        </div>
                    </div>
                    <div class="flex px-3 text-center font-bold">
                        <button type="button" id="button-modal"
                            class="inline-flex items-center rounded bg-green-400 px-3 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                            {{ __('label.create') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-scroll">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.created_at') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.updated_at') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.contact_id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.description') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.sum') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incomings as $incoming)
                            <tr class="border-b-2">
                                <td class="text-blue-600 break-all max-w-[20rem] overflow-auto px-3 py-4">
                                    <a href="{{ route('incomings.show', ['incoming' => $incoming->id]) }}">
                                        {{ $incoming->id }}
                                    </a>
                                </td>
                                <td class="break-all max-w-[20rem] overflow-auto px-3 py-4">
                                    {{ $incoming->created_at }}
                                </td>
                                <td class="break-all max-w-[20rem] overflow-auto px-3 py-4">
                                    {{ $incoming->updated_at }}
                                </td>
                                <td class="text-blue-600 break-all max-w-[20rem] overflow-auto px-3 py-4">
                                    @if ($incoming->contact)
                                        <a href="{{ route('contact.show', ['contact' => $incoming->contact->id]) }}">
                                            {{ $incoming->contact->name }}
                                        </a>
                                    @else
                                        {{ __('column.no') }}
                                    @endif
                                </td>
                                <td class="break-all max-w-[20rem] overflow-auto px-3 py-4">
                                    {{ $incoming->description }}
                                </td>
                                <td class="break-all max-w-[20rem] overflow-auto px-3 py-4">
                                    {{ $incoming->sum }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $incomings->appends(request()->query())->links() }}
            </div>

            {{-- Modal create --}}
            <div class="hidden absolute top-[10%] border-2 border-black w-full bg-gray-200 text-center p-5 mx-auto z-50 rounded-md shadow-lg"
                id="contact-modal">
                <div class="absolute top-5 right-5 cursor-pointer" id="close-modal">закрыть</div>
                <h4 class="text-3xl max-w-7xl mx-auto font-bold mb-6">Добавить приход</h4>
                <form action="{{ route($incomingCreate) }}" method="post" id="newIncoming">
                    @csrf
                    @method('post')
                    <div class="flex flex-row w-full px-1">
                        {{-- Contacts --}}
                        <div class="flex flex-row mb-3 w-full">
                            <span
                                class="basis-[10%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                Контрагент</span>
                            <select name="contact_id" required class="js-data-contact-ajax" style="width: 100%"
                                class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                <option value="" selected="selected">не выбрано
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col w-full px-1">
                        {{-- Products --}}
                        <div class="flex flex-row mb-3 w-full">
                            <span
                                class="basis-[11%] flex items-center whitespace-nowrap px-3 py-[0.25rem] text-center text-base text-surface">
                                Продукт</span>
                            <select name="product_id" required class="js-data-product-ajax" style="width: 90%"
                                class="relative m-0 flex basis-full rounded border border-solid border-neutral-400 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary">
                                <option value="" selected="selected">не выбрано
                                </option>
                            </select>
                            <input type="number" name="quantity" min="0" required
                                class="ml-3 flex basis-[3%] rounded border border-solid bg-white px-3 py-[0.25rem] text-base font-normal leading-[1.1] text-surface outline-none transition duration-200 ease-in-out placeholder:text-neutral-500 focus:z-[3] focus:border-primary focus:shadow-inset focus:outline-none motion-reduce:transition-none dark:border-white/10 dark:text-white dark:placeholder:text-neutral-200 dark:autofill:shadow-autofill dark:focus:border-primary" />

                        </div>
                        <div class="flex basis-full">
                            <textarea name="description" class="w-full rounded border-neutral-200" placeholder="Комментарий"></textarea>
                        </div>
                    </div>

                    <div class="flex flex-row w-full px-1 my-2">
                        <button type="submit"
                            class="w-full p-1 bg-green-500 hover:bg-green-400 text-white hover:text-gray-700 rounded font-semibold uppercase">добавить</button>
                    </div>
                </form>
            </div>

        </div>

        <script>
            $(document).ready(function() {
                //change selectboxes to selectize mode to be searchable
                $(".select2").select2();
                $('.js-data-contact-ajax').select2({
                    ajax: {
                        url: `{{ route($searchContacts) }}`,
                        data: function(params) {

                            var queryParameters = {
                                term: params.term
                            }
                            return queryParameters;
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    if (item.name && item.phone) {
                                        return {
                                            text: item.id + ' / ' + item.name + ' (' + item.phone +
                                                ')',
                                            id: item.id,
                                        }
                                    }
                                })
                            };
                        }
                    },
                });
                $('.js-data-product-ajax').select2({
                    ajax: {
                        url: `{{ route($searchProducts) }}`,
                        data: function(params) {

                            var queryParameters = {
                                term: params.term
                            }
                            return queryParameters;
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    if (item.name) {
                                        return {
                                            text: item.name + ' (' + item.price + ' руб.)',
                                            id: item.id,
                                        }
                                    }
                                })
                            };
                        }
                    },
                });
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
