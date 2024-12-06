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
                        </div>
                    </div>

                    {{-- body card --}}
                    <div class="flex flex-col w-100 bg-white overflow-x-auto">
                        <table class="text-left text-md text-nowrap">
                            <thead>
                            <tr class="bg-neutral-200 font-semibold">
                                <th class="font-semibold px-2">Дистанция (км)</th>
                                @foreach($weightRanges as $index => $weightRange)
                                    <th scope="col" class="px-2 py-3 font-semibold">{{ $index + 6 }} Тон</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>

                                @foreach($resultTable as $i => $row)
                                    <tr class="border-b-2">
                                        <td class="break-all max-w-96 truncate px-2 py-3 font-semibold">{{ $distanceRanges[$i]['min'] }} - {{ $distanceRanges[$i]['max'] }}</td>
                                        @foreach($row as $cell)
                                            <td class="break-all max-w-96 truncate px-2 py-3 border-l-2">{{ $cell }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>



</x-app-layout>
