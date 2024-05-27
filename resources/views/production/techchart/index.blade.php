
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
                            @if (url()->current() == route('techcharts.index'))
                                <a href="{{ route('techcharts.index') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Общая таблица</a>
                            @else
                                <a href="{{ route('techcharts.index') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Общая таблица</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('techcharts.products'))
                                <a href="{{ route('techcharts.products') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (продукты)</a>
                            @else
                                <a href="{{ route('techcharts.products') }}"
                                class="rounded bg-blue-300 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (продукты)</a>
                            @endif
                        </div>
                        <div>
                            @if (url()->current() == route('techcharts.materials'))
                                <a href="{{ route('techcharts.materials') }}"
                                class="rounded bg-blue-600 px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:bg-blue-700">
                                Связь (материалы)</a>
                            @else
                                <a href="{{ route('techcharts.materials') }}"
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
                                {{__("column.price")}}
                            </th>
                            <th scope="col" class="px-6 py-4">
                                {{__("column.description")}}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($techcharts as $techchart)
                        <tr class="border-b-2">
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4 text-right">
                                <a href="{{ route('techcharts.show', ['techchart' => $techchart->id]) }}" class="text-blue-500 hover:text-blue-600">
                                    {{ $techchart->id}}
                                </a>
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $techchart->name}}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4 text-right">
                                {{ $techchart->cost}}
                            </td>
                            <td class="break-all max-w-96 overflow-hidden px-6 py-4">
                                {{ $techchart->description}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-app-layout>
