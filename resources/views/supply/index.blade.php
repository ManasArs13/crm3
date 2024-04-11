<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif

    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }}</h3>
        @endif
        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">

             {{-- header --}}
             <div class="border-b-2 border-neutral-100">
                <div class="flex flex-row w-full p-3 justify-between">
                    <div class="flex flex-row gap-1">
                        <div>
                            @if (url()->current() == route('supplies.index'))
                                <a href="{{ route('supplies.index') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Общая таблица</a>
                            @else
                                <a href="{{ route('supplies.index') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Общая таблица</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('supplies.products'))
                                <a href="{{ route('supplies.products') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Связь (продукты)</a>
                            @else
                                <a href="{{ route('supplies.products') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">Связь (продукты)</a>
                            @endif
                        </div>
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
                                {{ __('column.name') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.date_plan') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.contact_id') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.sum') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.incoming_number') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.incoming_date') }}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{ __('column.description') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplies as $supply)
                            <tr class="border-b-2">
                                <td class="px-6 py-4 text-blue-600">
                                    <a href="{{ route('supplies.show', ['supply' => $supply->id]) }}">
                                        {{ $supply->id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-blue-600">
                                    <a href="{{ route('supplies.show', ['supply' => $supply->id]) }}">
                                        {{ $supply->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $supply->moment }}
                                </td>
                                <td class="text-blue-600">
                                    @if($supply->contact)
                                     <a href="{{ route('contact.show', ['contact' => $supply->contact->id]) }}">
                                        {{ $supply->contact->name }}
                                     </a> 
                                     @else
                                     {{ __('column.no') }}
                                     @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $supply->sum }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $supply->incoming_number }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $supply->incoming_date }}
                                </td>
                                <td class="px-6 py-4 break-all max-w-[24rem] overflow-auto">
                                    {{ $supply->description }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $supplies->appends(request()->query())->links() }}
            </div>
            
        </div>
</x-app-layout>
