<x-app-layout>

    @if (isset($entityName) && $entityName != '')
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

                @if (isset($entityName) && $entityName != '')
                    <h3 class="text-4xl font-bold mb-6">{{ $entityName }}</h3>
                @endif

                <div
                    class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

                    {{-- header card --}}
                    <div class="border-b-2 border-neutral-100">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex gap-2"></div>
                            <div class="flex px-3 text-center font-bold">
                                <a href="{{ route('double_of_orders', ['date' => $datePrev]) }}" class="mx-2 text-lg">&#9668;</a>
                                <p class="mx-2 text-lg">{{ $dateRus }}</p>
                                <a href="{{ route('double_of_orders', ['date' => $dateNext]) }}" class="mx-2 text-lg">&#9658;</a>
                            </div>
                        </div>
                    </div>

                    {{-- body card --}}
                    <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                @foreach ($resColumns as $key => $column)
                                    <th scope="col" class="px-2 py-3">
                                        {{ $column }}
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($entityItems as $entityItem)
                                <tr class="border-b-2">

                                    @foreach ($resColumns as $column => $title)
                                        <td class="break-all max-w-96 truncate px-2 py-3"
                                            @if ($entityItem->$column) title="{{ $entityItem->$column }}" @endif>

                                            @switch($column)

                                                @case('contact_amo_id')
                                                    @if(isset($entityItem->name))
                                                        {{ $entityItem->name }}
                                                    @elseif(isset($entityItem->contact->name))
                                                        {{ $entityItem->contact->name }}
                                                    @else
                                                        -
                                                    @endif
                                                @break

                                                @case('count_orders')
                                                    @if(isset($entityItem->amo_order_count) && $entityItem->amo_order_count > 0)
                                                        <a href="{{ route('amo-order.index', ['filters[contacts][]' => $entityItem->id]) }}" class="text-blue-700">
                                                            {{ $entityItem->amo_order_count }}
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                @break

                                                @default
                                                    {{ $entityItem->$column }}

                                            @endswitch

                                        </td>
                                    @endforeach

                                </tr>
                            @endforeach

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
