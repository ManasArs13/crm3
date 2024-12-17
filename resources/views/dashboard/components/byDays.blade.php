<div class="flex flex-col lg:flex-row flex-nowrap gap-3 w-11/12 mx-auto pb-1 max-w-10xl">
    <div class="flex flex-col w-full mb-10 bg-white overflow-x-auto shadow rounded-md">
        <table class="text-left text-md text-nowrap">
            <thead>
                <tr class="bg-neutral-200 font-semibold">
                    <th class="px-2 py-3 font-semibold min-w-[250px] max-w-[250px]">День</th>
                    @foreach($allFlights as $days)
                        <th class="px-2 py-3 text-center font-semibold min-w-[39px] max-w-[39px] w-[39px]">{{ $days['day'] }}</th>
                    @endforeach
                    <th class="px-2 py-3 font-semibold text-center">Итого</th>
                </tr>
            </thead>
            <tbody>
            @php
                $carsTotal = 0;
                $reisTotal = 0;
            @endphp
            <tr class="border-b-2">
                <td class="px-2 py-3 text-left font-semibold min-w-[250px] max-w-[250px] w-[250px]">Рейсов</td>
                @foreach($allFlights as $days)
                    @php $carsTotal += $days['shipments_count']; @endphp
                    <th class="px-2 py-3 border-l-2 text-center font-normal">{{ $days['shipments_count'] }}</th>
                @endforeach
                <td class="px-2 py-3 border-l-2 text-center font-normal">{{ $carsTotal }}</td>
            </tr>
            <tr class="border-b-2">
                <td class="px-2 py-3 text-left font-semibold min-w-[250px] max-w-[250px]">Машин</td>
                @foreach($allFlights as $days)
                    @php $reisTotal += $days['routes_count']; @endphp
                    <th class="px-2 py-3 border-l-2 text-center font-normal">{{ $days['routes_count'] }}</th>
                @endforeach
                <td class="px-2 py-3 border-l-2 text-center font-normal">{{ $reisTotal }}</td>
            </tr>
            <tr>
                <td class="px-2 py-3 text-left font-semibold min-w-[250px] max-w-[250px]">Средн</td>
                @foreach($allFlights as $days)
                    <th class="px-2 py-3 border-l-2 text-center font-normal">{{ $days['shipments_count'] != 0 && $days['routes_count'] != 0 ? round($days['shipments_count'] / $days['routes_count'], 1) : 0 }}</th>
                @endforeach
                <td class="px-2 py-3 border-l-2 text-center font-normal">{{ $carsTotal != 0 && $reisTotal != 0 ? round($carsTotal / $reisTotal, 1) : 0 }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="flex flex-col lg:flex-row flex-nowrap gap-3 w-11/12 mx-auto pb-10 max-w-10xl">
    <div class="flex flex-col w-full mb-10 bg-white overflow-x-auto shadow rounded-md">
        <table class="text-left text-md text-nowrap" id="flightsTable">
            <thead>
            <tr class="bg-neutral-200 font-semibold">
                <th class="px-2 py-3 font-semibold min-w-[250px] max-w-[250px] w-[250px]">Транспорт</th>
                @foreach ($flightsByDaysTransport['days'] as $day)
                    <th class="px-2 py-3 text-center font-semibold min-w-[39px] max-w-[39px] w-[39px]">{{ \Carbon\Carbon::parse($day)->format('d') }}</th>
                @endforeach
                <th class="px-2 py-3 text-center font-semibold" id="th_flightsTotal" onclick="orderBy('flightsTotal', null)">Итого</th>
            </tr>
            </thead>
            <tbody>

                @foreach ($flightsByDaysTransport['transports'] as $transportName => $flights)
                    @php
                        $transportTotal = 0;
                    @endphp
                    <tr class="border-b-2">
                        <td class="px-2 py-3 text-left font-semibold min-w-[250px] max-w-[250px] w-[250px] truncate">{{ $transportName }}</td>
                        @foreach ($flights as $count)
                            <td class="px-2 py-3 border-l-2 text-center font-normal">{{ $count }}</td>
                            @php $transportTotal += $count @endphp
                        @endforeach
                        <td class="px-2 py-3 border-l-2 text-center font-normal truncate">{{ $transportTotal }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
