
        @if (count($shipments)>0)
            <div class="CEB__wrapTable mb-5">
                <table class="sum sum1">
                    <tr>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-1 pb-2 pr-2 w-4/12">
                            {{ __('column.name') }}</th>
                        <th class="bg-neutral-200 font-semibold text-end pl-2 pt-1 pb-2 pr-2 w-2/12">
                                {{ __('column.date_of_last_shipment') }}</th>
                        <th class="bg-neutral-200 font-semibold text-end pl-2 pt-1 pb-2 pr-2 w-1/12">
                                {{ __('column.days') }}</th>
                        <th class="bg-neutral-200 font-semibold text-end pl-2 pt-1 pb-2 pr-2 w-1/12">
                                    {{ __('column.balance') }}</th>
                        <th class="bg-neutral-200 font-semibold text-start pl-2 pt-1 pb-2 pr-2 w-3/12">
                                    {{ __('column.description') }}</th>
                        <th class="bg-neutral-200 font-semibold text-center pl-2 pt-1 pb-2 pr-2 w-1/12">
                                        {{ __('column.cnt') }}</th>
                    </tr>
                    @php
                        $sum=0;
                    @endphp
                    @foreach($shipments as $shipment)
                        <tr>
                            <td class="text-start pl-2 pt-1 pb-2 pr-2 overflow-auto w-4/12"><a href="https://online.moysklad.ru/app/#Company/edit?id={{$shipment->ms_id}}" target="__blank">{{ $shipment->name }}</a></td>
                            <td class="text-end pl-2 pt-1 pb-2 pr-2 w-2/12">{{ $shipment->moment}}</td>
                            <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12">{{ $shipment->days }}</td>
                            <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12">{{ $shipment->balance }}</td>
                            <td class="text-start pl-2 pt-1 pb-2 pr-2 overflow-auto w-3/12"><div class="comment">{{ $shipment->description }}</div></td>
                            <td class="text-center pl-2 pt-1 pb-2 pr-2 w-1/12">
                                @if (!is_null($shipment->ship))
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="mx-auto" viewBox="0,0,256,256" width="32px" height="32px"><g fill-opacity="1" fill="#B3B3B3" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(8,8)"><path d="M28.28125,6.28125l-17.28125,17.28125l-7.28125,-7.28125l-1.4375,1.4375l8,8l0.71875,0.6875l0.71875,-0.6875l18,-18z"></path></g></g></svg>
                                @endif
                            </td>
                        </tr>
                        @php
                            $sum+=$shipment->balance;
                        @endphp
                    @endforeach
                    <tr>
                        <td class="text-end pl-2 pt-1 pb-2 pr-2 w-4/12"></td>
                        <td class="text-end pl-2 pt-1 pb-2 pr-2 w-2/12"></td>
                        <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12"></td>
                        <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12"><b>{{ $sum}}</b></td>
                        <td class="text-end pl-2 pt-1 pb-2 pr-2 w-3/12"></td>
                        <td class="text-end pl-2 pt-1 pb-2 pr-2 w-1/12"></td>
                    </tr>

                </table>
            </div>
        @endif
