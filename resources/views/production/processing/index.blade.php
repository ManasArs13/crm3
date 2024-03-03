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
                            @if (url()->current() == route('processings.index'))
                                <a href="{{ route('processings.index') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Общая таблица</a>
                            @else
                                <a href="{{ route('processings.index') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Общая таблица</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('processings.products'))
                                <a href="{{ route('processings.products') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (продукты)</a>
                            @else
                                <a href="{{ route('processings.products') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (продукты)</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('processings.materials'))
                                <a href="{{ route('processings.materials') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (материалы)</a>
                            @else
                                <a href="{{ route('processings.materials') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (материалы)</a>
                            @endif
                        </div>
                    </div>
                </div>              
            </div>

            {{-- body --}}
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <thead>
                        <tr class="bg-neutral-200 font-semibold">
                            <th scope="col" class="px-6 py-4">
                                {{__("column.id")}}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{__("column.name")}}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{__("column.date_plan")}}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{__("column.tech_chart")}}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{__("column.quantity")}}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{__("column.hours")}}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{__("column.cycles")}}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{__("column.defective")}}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{__("column.description")}}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processings as $processing)
                        <tr class="border-b-2">
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                <a href="{{ route('processings.show', ['processing' => $processing->id]) }}" class="text-blue-500 hover:text-blue-600">
                                    {{ $processing->id}}
                                </a>
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $processing->name}}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $processing->moment}}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                @if($processing->tech_chart)
                                {{ $processing->tech_chart->name}}
                                @else
                                {{ __('column.no')}}
                                @endif
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $processing->quantity}}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $processing->hours}}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $processing->cycles}}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $processing->defective}}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $processing->description}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="border-t-2 border-neutral-100 px-6 py-3 dark:border-neutral-600 dark:text-neutral-50">
                {{ $processings->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

</x-app-layout>