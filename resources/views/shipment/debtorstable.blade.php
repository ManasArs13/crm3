
        @if (count($shipments)>0)
            <div class="CEB__wrapTable mb-5">
                <table class="sum">
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2 pr-2 ">
                            {{ __('column.name') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                                {{ __('column.date_of_last_shipment') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                                {{ __('column.days') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                                    {{ __('column.balance') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                                    {{ __('column.description') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                                        {{ __('column.carrier') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-2 pb-2  pr-2">
                                        {{ __('column.cnt') }}</th>
                    </tr>
                    @php
                        $sum=0;
                    @endphp
                    @foreach($shipments as $shipment)
                        <tr>
                            <td class="text-start pl-2 pt-2 pb-2 pr-2"><a href="https://online.moysklad.ru/app/#Company/edit?id={{ $shipment->ms_id }}" target="__blank">{{ $shipment->name }}</a></td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2">{{ $shipment->moment}}</td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2">{{ $shipment->days }}</td>
                            <td class="text-end pl-2 pt-2 pb-2 pr-2">{{ $shipment->balance }}</td>
                            <td class="text-start pl-2 pt-2 pb-2 pr-2">{{ $shipment->description }}</td>
                            <td class="text-start pl-2 pt-2 pb-2 pr-2">{{ $shipment->carrier }}</td>
                            <td class="text-start pl-2 pt-2 pb-2 pr-2">
                                @if (!is_null($shipment->ship))
                                    {{ $shipment->cnt }}
                                @endif
                            </td>
                        </tr>
                        @php
                            $sum+=$shipment->balance;
                        @endphp
                    @endforeach
                    <tr>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2"></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2"></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2"></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2"><b>{{ $sum}}</b></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2"></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2"></td>
                        <td class="text-end pl-2 pt-2 pb-2 pr-2"></td>
                    </tr>

                </table>
            </div>
        @endif
