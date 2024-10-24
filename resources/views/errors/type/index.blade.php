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
                    <div class="border-b-2 border-neutral-100">
                        <div class="flex flex-row w-full p-3 justify-between">
                            <div class="flex px-3 text-center font-bold">
                                <a href="{{ route($urlCreate) }}" class="inline-flex items-center rounded bg-green-400 px-6 py-2 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700">
                                    {{ __('label.create') }}
                                </a>
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
                                    <th>Управление</th>
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
                                                    <a href="{{ route('amo-order.index', ['filters[contacts][]' => $entityItem->id, 'filters[created_at][min]' => $firstTime, 'filters[created_at][max]' => $lastTime]) }}" class="text-blue-700">
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
                                        <td class="text-nowrap px-6 py-2 flex">
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
                                                        <a href="{{ route($urlEdit, $entityItem->id) }}" class="block px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 flex items-center space-x-2" role="menuitem" tabindex="-1" id="menu-item-{{ $entityItem->id }}-5">
                                                            <svg class="w-4 h-4 fill-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 511">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M408.68 1.82615C396.293 5.83515 393.092 8.27415 369.68 31.5402L347.18 53.8991L401.926 108.664L456.672 163.428L477.787 142.433C500.324 120.025 505.515 113.417 508.757 103.01C513.294 88.4511 512.255 75.0461 505.496 60.9381C501.647 52.9041 500.239 51.2542 479.802 30.8382C456.749 7.80815 452.803 4.91815 439.635 1.41315C431.93 -0.63685 415.617 -0.41985 408.68 1.82615ZM173.968 227.189L31.7598 369.438L15.5438 434.438C3.65184 482.102 -0.481161 500.194 0.0438393 502.274C0.942839 505.83 4.69484 509.653 8.13284 510.515C10.8938 511.208 135.804 480.916 140.68 478.371C142.055 477.653 206.743 413.474 284.431 335.75L425.682 194.433L370.929 139.687L316.175 84.9401L173.968 227.189Z" />
                                                            </svg>
                                                            <span>{{ __('label.edit') }}</span>
                                                        </a>
                                                    </div>

                                                </x-slot>
                                            </x-dropdown>
                                        </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>



</x-app-layout>
