<x-app-layout>

    @if (isset($entity) && $entity != '')
        <x-slot:title>
            {{ __('entity.' . $entity) }}
        </x-slot>
    @endif


    <div class="w-11/12 mx-auto py-8">
        @if (isset($entity) && $entity != '')
            <h3 class="text-4xl font-bold mb-6">{{ __('entity.' . $entity) }} №{{ $entityItem->id }}</h3>
        @endif

        <div
            class="block rounded-lg bg-white text-center shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)">
            <div class="flex flex-col w-100 p-1 bg-white overflow-x-auto">
                <table class="text-left text-md text-nowrap">
                    <tbody>
                        @foreach ($columns as $column)
                            <tr class="border-b-2">
                                <td class="whitespace-nowrap px-6 py-4">{{ __('column.' . $column) }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if (preg_match('/_id\z/u', $column))
                                        @php
                                            $column = substr($column, 0, -3);
                                        @endphp
                                        @if ($entityItem->$column != null)
                                            {{ $entityItem->$column->name }}
                                        @endif
                                    @else
                                        {{ $entityItem->$column }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
