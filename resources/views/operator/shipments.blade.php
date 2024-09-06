<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8 max-w-10xl">

        @if (session('succes'))
            <div class="w-full mb-4 items-center rounded-lg text-lg bg-green-200 px-6 py-5 text-green-700 ">
                {{ session('succes') }}
            </div>
        @endif

        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

            {{-- header --}}
            <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">

                    @if (isset($urlCreate) && $urlCreate != '')
                        <div class="flex px-3 text-center font-bold">
                            <a href="{{ route($urlCreate) }}"
                                class="inline-flex items-center rounded bg-green-400 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                {{ __('label.create') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            @foreach ($resColumns as $key => $column)
                                @if ($key === 'remainder')
                                    <th scope="col" class="px-6 py-2">{{ $column }}</th>
                                @elseif(isset($orderBy) && $orderBy == 'desc')
                                    <th scope="col" class="px-6 py-2">
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                            &#9650;
                                        @endif
                                    </th>
                                @else
                                    <th scope="col" class="px-6 py-2">
                                        <a class="text-black"
                                            href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                        @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'asc')
                                            &#9660;
                                        @endif
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalSum = 0;
                            $totalCount = 0;
                        @endphp
                        @foreach ($entityItems as $entityItem)
                            @php
                                $totalSum += $entityItem->suma;
                            @endphp
                            <tr class="border-b-2">
                                @foreach ($resColumns as $column => $title)
                                    <td class="break-all max-w-60 xl:max-w-44 overflow-auto px-6 py-2"
                                        @if (
                                            (is_int($entityItem->$column) ||
                                                $column == 'payed_sum' ||
                                                $column !== 'status' ||
                                                $column !== 'description' ||
                                                $column == 'positions_count' ||
                                                $column == 'residual_count' ||
                                                $column == 'shipped_count' ||
                                                $column == 'shipped_sum' ||
                                                $column == 'reserved_sum' ||
                                                $column == 'weight' ||
                                                $column == 'debt') &&
                                                !preg_match('/_id\z/u', $column) &&
                                                $column !== 'sostav') style="text-align:right" @else style="text-align:left" @endif
                                        @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>
                                        @if (preg_match('/_id\z/u', $column))
                                            @if ($column == 'contact_id')
                                                {{ $entityItem->contact ? $entityItem->contact->name : '-' }}
                                            @elseif($column == 'order_id')
                                                <a href="{{ route('order.show', $entityItem->id) }}"
                                                    class="text-blue-500 hover:text-blue-600">
                                                    {{ $entityItem->$column }}
                                                </a>
                                            @elseif($column == 'delivery_id')
                                                {{ $entityItem->delivery ? $entityItem->delivery->name : '-' }}
                                            @elseif($column == 'transport_id')
                                                {{ $entityItem->transport ? $entityItem->transport->name : '-' }}
                                            @elseif($column == 'transport_type_id')
                                                {{ $entityItem->transport_type ? $entityItem->transport_type->name : '-' }}
                                            @else
                                                {{ $entityItem->$column }}
                                            @endif
                                        @elseif($column == 'status')
                                            {{ $entityItem->$column }}
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
                                            <a href="{{ route($urlShow, $entityItem->id) }}"
                                                class="text-blue-500 hover:text-blue-600">
                                                {{ $entityItem->$column }}
                                            </a>
                                        @elseif($column == 'products_count')
                                            @php
                                                $total_quantity = 0;
                                            @endphp

                                            @foreach ($entityItem->products as $position)
                                                @php
                                                    $total_quantity += $position->quantity;
                                                    $totalCount += $position->quantity;
                                                @endphp
                                            @endforeach

                                            {{ $total_quantity }}
                                        @elseif($column == 'ms_link' && $entityItem->ms_id)
                                            <a href="https://online.moysklad.ru/app/#customerorder/edit?id={{ $entityItem->ms_id }}"
                                                target="_blank">
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
                                        @elseif($column == 'created_at')
                                            {{ \Illuminate\Support\Carbon::parse($entityItem->$column)->format('H:i') }}
                                        @elseif($column == 'sostav')
                                            {{ $entityItem->products[0]->product->name }}
                                        @else
                                            {{ $entityItem->$column }}
                                        @endif
                                    </td>
                                @endforeach

                            </tr>
                        @endforeach
                        <tr class="border-b-2 bg-gray-100">
                            @foreach ($resColumns as $column => $title)
                                @if ($column == 'suma')
                                    <td class="px-6 py-2">
                                        {{ $totalSum }}
                                    </td>
                                @elseif($column == 'products_count')
                                    <td class="overflow-auto px-6 py-2">
                                        {{ $totalCount }}
                                    </td>
                                @else
                                    <td>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $entityItems->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</x-app-layout>
