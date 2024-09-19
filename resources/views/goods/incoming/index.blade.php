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
                    $totalSum = 0;
                    @endphp
                        @foreach ($incomings as $incoming)
                            @php
                                $totalSum += $incoming->summa;
                            @endphp
                            <tr class="border-b-2">
                                @if (count($incoming->products) > 0)
                                    <td class="text-nowrap px-3 py-2">
                                        <button class="buttonForOpen text-normal font-bold"
                                            data-id="{!! $incoming->id !!}">+</button>
                                    </td>
                                @else
                                    <td class="text-nowrap px-3 py-2">
                                    </td>
                                @endif
                                <td class="text-blue-600 px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    <a href="{{ route('incomings.show', ['incoming' => $incoming->id]) }}">
                                        {{ $incoming->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ $incoming->created_at }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ $incoming->updated_at }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    @if ($incoming->contact)
                                        <a href="{{ route('contact.show', ['contact' => $incoming->contact->id]) }}">
                                            {{ $incoming->contact->name }}
                                        </a>
                                    @else
                                        {{ __('column.no') }}
                                    @endif
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ $incoming->description }}
                                </td>
                                <td class="px-6 py-2 break-all max-w-60 xl:max-w-44 overflow-auto">
                                    {{ number_format((int) $incoming->summa, 0, ',', ' ') }}
                                </td>

                                {{-- Delete --}}
                                <td class="text-nowrap px-3 py-2 flex">

                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="inline-flex items-center px-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">

                                                <div class="ms-1">
                                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                        <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                    </svg>
                                                </div>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <div class="py-1" role="none">
                                                <form action="{{ route($urlDelete, $incoming->id) }}" method="Post"
                                                      class="block px-4 text-sm font-medium text-red-500 hover:bg-gray-100 cursor-pointer">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full h-full py-2 flex items-center space-x-2">
                                                        <svg class="w-4 h-4 fill-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="text-red-500">{{ __('label.delete') }}</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </x-slot>
                                    </x-dropdown>
                                </td>



                            </tr>

                            @foreach ($incoming->products as $product)
                                <tr style="display: none"
                                    class="border-b-2 bg-green-100 position_column_{!! $incoming->id !!}">
                                    <td class="text-nowrap px-3 py-2">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2">
                                        {{ $product->pivot->id}}
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2" colspan="2">
                                        {{ $product->name}}
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2">
                                        {{ $product->pivot->quantity}} ед.
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2">
                                        {{ $product->pivot->price}} руб.
                                    </td>
                                    <td class="break-all max-w-[28rem] overflow-auto px-3 py-2">
                                        {{ $product->pivot->summa}}
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
                            <td class="px-6 py-2 font-normal">
                                {{ number_format((int) $totalSum, 0, ',', ' ') }}
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $incomings->appends(request()->query())->links() }}
            </div>


        </div>
</x-app-layout>
