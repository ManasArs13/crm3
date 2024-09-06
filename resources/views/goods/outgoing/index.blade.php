<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entity }}
        </x-slot>
    @endif

    <x-slot:head>
        <script>
            document.addEventListener("DOMContentLoaded", function(event) {

                let buttons = document.querySelectorAll(".buttonForOpen")
                for (var i = 0; i < buttons.length; i++) {
                    let attrib = buttons[i].getAttribute("data-id");
                    let but = buttons[i];

                    function cl(attr, b) {
                        let positions = document.querySelectorAll(".position_column_" + attr, b);
                        for (var i = 0; i < positions.length; i++) {
                            console.log(positions[i].style.display)
                            if (positions[i].style.display === 'none') {
                                positions[i].style.display = ''
                                b.textContent = '-'
                            } else {
                                positions[i].style.display = 'none'
                                b.textContent = '+'
                            }
                        }
                    }
                    buttons[i].addEventListener("click", cl.bind(null, attrib, but));
                }
            });
        </script>
    </x-slot>

    <div class="w-11/12 mx-auto py-8 max-w-10xl">

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
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <div class="flex flex-row gap-1">
                        <div>
                            @if (url()->current() == route('outgoings.index'))
                                <a href="{{ route('outgoings.index') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Расход</a>
                            @else
                                <a href="{{ route('outgoings.index') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Расход</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('outgoings.products'))
                                <a href="{{ route('outgoings.products') }}"
                                    class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Состав</a>
                            @else
                                <a href="{{ route('outgoings.products') }}"
                                    class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Состав</a>
                            @endif
                        </div>
                    </div>
                    <div class="flex px-3 text-center font-bold">
                        <a href="{{ route($entityCreate) }}"
                            class="inline-flex items-center rounded bg-green-400 px-3 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                            {{ __('label.create') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-scroll">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th></th>
                            <th scope="col" class="px-6 py-2">
                                {{ __('column.id') }}
                            </th>
                            <th scope="col" class="px-6 py-2">
                                {{ __('column.created_at') }}
                            </th>
                            <th scope="col" class="px-6 py-2">
                                {{ __('column.updated_at') }}
                            </th>
                            <th scope="col" class="px-6 py-2">
                                {{ __('column.contact_id') }}
                            </th>
                            <th scope="col" class="px-6 py-2">
                                {{ __('column.description') }}
                            </th>
                            <th scope="col" class="px-6 py-2">
                                {{ __('column.sum') }}
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalPrice = 0;
                        @endphp
                        @foreach ($outgoings as $outgoing)
                            @php
                                $totalPrice += $outgoing->summa;
                            @endphp
                            <tr class="border-b-2">
                                @if (count($outgoing->products) > 0)
                                    <td class="text-nowrap px-3 py-2">
                                        <button class="buttonForOpen text-normal font-bold"
                                            data-id="{!! $outgoing->id !!}">+</button>
                                    </td>
                                @else
                                    <td class="text-nowrap px-3 py-2">
                                    </td>
                                @endif
                                <td class="text-blue-600 px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    <a href="{{ route('outgoings.show', ['outgoing' => $outgoing->id]) }}">
                                        {{ $outgoing->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ $outgoing->created_at }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ $outgoing->updated_at }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    @if ($outgoing->contact)
                                        <a href="{{ route('contact.show', ['contact' => $outgoing->contact->id]) }}">
                                            {{ $outgoing->contact->name }}
                                        </a>
                                    @else
                                        {{ __('column.no') }}
                                    @endif
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ $outgoing->description }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ number_format((int) $outgoing->summa, 0, ',', ' ') }}
                                </td>

                                {{-- Delete --}}
                                <td class="text-nowrap px-3 py-2">

                                    <form action="{{ route($urlDelete, $outgoing->id) }}" method="Post"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="rounded-lg p-1 font-semibold hover:bg-red-500 hover:text-white border border-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6 stroke-red-500 hover:stroke-white">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </button>
                                    </form>
                                </td>
                            </tr>

                            @foreach ($outgoing->products as $product)
                                <tr style="display: none"
                                    class="border-b-2 bg-green-100 position_column_{!! $outgoing->id !!}">
                                    <td class="text-nowrap px-3 py-2">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2">
                                        {{ $product->pivot->id }}
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2" colspan="2">
                                        {{ $product->name }}
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2">
                                        {{ $product->pivot->quantity }} ед.
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2">
                                        {{ $product->pivot->price }} руб.
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2">
                                        {{ $product->pivot->summa }}
                                    </td>
                                    <td class="text-nowrap px-3 py-2">
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr class="bg-neutral-100">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="px-6 py-2">{{ number_format((int) $totalPrice, 0, ',', ' ') }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $outgoings->appends(request()->query())->links() }}
            </div>

        </div>
</x-app-layout>
