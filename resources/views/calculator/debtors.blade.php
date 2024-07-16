<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entityName }}
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

    <div class="w-11/12 max-w-7xl mx-auto pb-10 my-10">

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
            <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- body card --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            @foreach ($resColumns as $key => $column)
                                @if ($key === 'remainder' || $key == 'positions_count')
                                    <th scope="col" class="px-2 py-4">{{ $column }}</th>
                                @elseif(isset($orderBy) && $orderBy == 'desc')
                                    <th scope="col" class="px-2 py-4">
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                            &#9650;
                                        @endif
                                    </th>
                                @else
                                    <th scope="col" class="px-2 py-4">
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'asc')
                                            &#9660;
                                        @endif
                                    </th>
                                @endif
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entityItems as $entityItem)
                            <tr class="border-b-2">

                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-96 overflow-auto px-2 py-4">
                                        @if (preg_match('/_id\z/u', $column))
                                            @if ($column == 'contact_id')
                                                {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                            @elseif($column == 'delivery_id')
                                                {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                            @elseif($column == 'transport_type_id')
                                                {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                            @elseif($column == 'status_id')
                                                @switch($entityItem->$column)
                                                    @case(1)
                                                        <div id="status"
                                                            class="rounded border-yellow-500 bg-yellow-400 px-2 py-1 text-center">
                                                            <span>[N] Новый</span>
                                                        </div>
                                                    @break

                                                    @case(2)
                                                        <div id="status"
                                                            class="rounded border-blue-500 bg-blue-400 px-2 py-1 text-center">
                                                            <span>Думают</span>
                                                        </div>
                                                    @break

                                                    @case(3)
                                                        <div id="status"
                                                            class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                            <span>[DN] Подтвержден</span>
                                                        </div>
                                                    @break

                                                    @case(4)
                                                        <div id="status"
                                                            class="rounded border-purple-500 bg-purple-400 px-2 py-1 text-center">
                                                            <span>На брони</span>
                                                        </div>
                                                    @break

                                                    @case(5)
                                                        <div id="status"
                                                            class="rounded border-orange-500 bg-orange-400 px-2 py-1 text-center">
                                                            <span>[DD] Отгружен с долгом</span>
                                                        </div>
                                                    @break

                                                    @case(6)
                                                        <div id="status"
                                                            class="rounded border-green-500 bg-green-400 px-2 py-1 text-center">
                                                            <span>[DF] Отгружен и закрыт</span>
                                                        </div>
                                                    @break

                                                    @case(7)
                                                        <div id="status"
                                                            class="rounded border-red-500 bg-red-400 px-2 py-1 text-center">
                                                            <span>[C] Отменен</span>
                                                        </div>
                                                    @break

                                                    @default
                                                        -
                                                @endswitch
                                            @endif
                                        @elseif($column == 'remainder')
                                            @if ($entityItem->residual_norm !== 0 && $entityItem->residual_norm !== null && $entityItem->type !== 'не выбрано')
                                                {{ round(($entityItem->residual / $entityItem->residual_norm) * 100) }}
                                                %
                                            @else
                                                {{ null }}
                                            @endif
                                        @elseif(preg_match('/_link/u', $column) && $entityItem->$column !== null && $entityItem->$column !== '')
                                            <a href="{{ $entityItem->$column }}" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-box-arrow-in-up-right"
                                                    viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @elseif($column == 'name' || $column == 'id')
                                            <a href="https://online.moysklad.ru/app/#Company/edit?id={{ $entityItem->ms_id }}"
                                                target="__blank" class="text-blue-500 hover:text-blue-600">
                                                {{ $entityItem->$column }}
                                            </a>
                                        @elseif($column == 'ship')
                                            @if (!is_null($entityItem->ship))
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" class="mx-auto"
                                                    viewBox="0,0,256,256" width="32px" height="32px">
                                                    <g fill-opacity="1" fill="#B3B3B3" fill-rule="nonzero"
                                                        stroke="none" stroke-width="1" stroke-linecap="butt"
                                                        stroke-linejoin="miter" stroke-miterlimit="10"
                                                        stroke-dasharray="" stroke-dashoffset="0" font-family="none"
                                                        font-weight="none" font-size="none" text-anchor="none"
                                                        style="mix-blend-mode: normal">
                                                        <g transform="scale(8,8)">
                                                            <path
                                                                d="M28.28125,6.28125l-17.28125,17.28125l-7.28125,-7.28125l-1.4375,1.4375l8,8l0.71875,0.6875l0.71875,-0.6875l18,-18z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            @endif
                                        @elseif($column == 'shipped_count')
                                            {{ $total_shipped_count }}
                                        @elseif($column == 'residual_count')
                                            {{ $total_quantity - $total_shipped_count >= 0 ? $total_quantity - $total_shipped_count : 0 }}
                                        @elseif($column == 'ms_link' && $entityItem->ms_id)
                                            <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}"
                                                class="flex justify-center" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-box-arrow-in-up-right"
                                                    viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @elseif($column == 'sostav')
                                            @if (isset($entityItem->positions[0]) && isset($entityItem->positions[0]->product))
                                                {{ $entityItem->positions[0]->product->building_material == 'бетон' ? $entityItem->positions[0]->product->name : '-' }}
                                            @else
                                                -
                                            @endif
                                        @else
                                            {{ $entityItem->$column }}
                                        @endif
                                    </td>
                                @endforeach

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>



</x-app-layout>
