<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ $entityName }}
            </x-slot>
            @endif

            <div class="w-11/12 mx-auto py-8 max-w-10xl">

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

                    {{-- header card --}}
                    <div class="border-b-2 border-neutral-100">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex flex-row gap-1">
                                <div>
                                    @if(url()->current() == route('transport.shift.index'))
                                        <a href="{{ route('transport.shift.index') }}" class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Все смены
                                        </a>
                                    @else
                                        <a href="{{ route('transport.shift.index') }}" class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Все смены
                                        </a>
                                    @endif
                                </div>
                                <div>
                                    @if(url()->current() == route('transport.shifts', ['shift'=>'onshift']))
                                        <a href="{{ route('transport.shifts', ['shift'=>'onshift']) }}" class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            На смене
                                        </a>
                                    @else
                                        <a href="{{ route('transport.shifts', ['shift'=>'onshift']) }}" class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            На смене
                                        </a>
                                    @endif
                                </div>
                                <div>
                                    @if(url()->current() == route('transport.shifts', ['shift'=>'offshift']))
                                        <a href="{{ route('transport.shifts', ['shift'=>'offshift']) }}" class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Вне смены
                                        </a>
                                    @else
                                        <a href="{{ route('transport.shifts', ['shift'=>'offshift']) }}" class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                            Вне смены
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- body card --}}
                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                @foreach ($resColumns as $key => $column)
                                    <th scope="col" class="px-2 py-2"
                                        @switch($column)
                                        @case('№')
                                        style="text-align:right"
                                        @break

                                        @case('Имя')
                                        style="text-align:left"
                                        @break

                                        @case('Комментарий')
                                        style="text-align:right"
                                        @break

                                        @case('Дата создания')
                                        style="text-align:right"
                                        @break

                                        @case('Дата обновления')
                                        style="text-align:right"
                                        @break

                                        @case('Тоннаж')
                                        style="text-align:right"
                                        @break

                                        @case('Контакт МС')
                                        style="text-align:left"
                                        @break

                                        @case('Uuid в МойСклад')
                                        style="text-align:right"
                                        @break

                                        @default
                                        style="text-align:right"
                                        @endswitch>
                                        @if (isset($orderBy) && $orderBy == 'desc')
                                            <a class="text-black"
                                               href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'desc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                            @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'desc')
                                                &#9650;
                                            @endif
                                        @else
                                            <a class="text-black"
                                               href="{{ request()->fullUrlWithQuery(['column' => $key, 'orderBy' => 'asc', 'type' => request()->type ?? null]) }}">{{ $column }}</a>
                                            @if (isset($selectColumn) && $selectColumn == $key && $orderBy == 'asc')
                                                &#9660;
                                            @endif
                                        @endif
                                    </th>
                                @endforeach

                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($entityItems as $entityItem)
                                <tr class="border-b-2">
                                    @foreach ($resColumns as $column => $title)
                                        @switch($column)
                                            @case('id')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                            @break

                                            @case('name')
                                            <td class="break-all max-w-96 truncate px-2 py-2">
                                                @if(isset($entityItem->transport->name))
                                                    {{ $entityItem->transport->name }}
                                                @endif
                                            </td>
                                            @break

                                            @case('description')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                            @break

                                            @case('created_at')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                            @break

                                            @case('updated_at')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                            @break

                                            @case('tonnage')
                                            @if ($entityItem->tonnage)
                                                <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                    {{ $entityItem->$column }}
                                                </td>
                                            @else
                                                <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                    -
                                                </td>
                                            @endif
                                            @break

                                            @case('contact_id')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-left">
                                                @if ($entityItem->contact)
                                                    <a href="{{ route('contact.show', $entityItem->contact->id) }}"
                                                       class="text-blue-500 hover:text-blue-600">
                                                        {{ $entityItem->contact->name }}
                                                    </a>
                                                @else
                                                    не назначено
                                                @endif
                                            </td>
                                            @break

                                            @case('ms_id')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                            @break

                                            @case('type_id')
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                @if(isset($entityItem->type->name))
                                                    <a href="{{ route($urlShow, $entityItem->type_id) }}"
                                                       class="text-blue-500 hover:text-blue-600">
                                                        {{ $entityItem->type->name }}
                                                    </a>
                                                @endif
                                            </td>
                                            @break

                                            @default
                                            <td class="break-all max-w-96 truncate px-2 py-2 text-right">
                                                {{ $entityItem->$column }}
                                            </td>
                                        @endswitch
                                    @endforeach

                                    {{-- Management --}}
                                    <td class="text-nowrap px-4 py-2">
                                        <form action="{{ route('api.get.shift_change', ['id' => $entityItem->transport_id, 'date' => \Carbon\Carbon::now()->format('Y-m-d')]) }}" method="POST">
                                            @csrf
                                            @if(isset($entityItem->end_shift))
                                                <button type="submit" class="rounded bg-green-400 px-3 py-2 text-xs font-medium uppercase text-white hover:bg-green-700">Поставить на смену</button>
                                            @else
                                                <button type="submit" class="rounded bg-yellow-400 px-3 py-2 text-xs font-medium uppercase text-black hover:bg-yellow-700">Снять со смены</button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- footer card --}}
                    <div class="border-t-2 border-neutral-100 px-3 py-3 dark:border-neutral-600 dark:text-neutral-50">
                        {{ $entityItems->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>



</x-app-layout>
