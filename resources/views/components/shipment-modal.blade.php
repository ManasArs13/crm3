@foreach($shipments as $shipment)
<div class="relative z-10 hidden hide-get" id="shipments_group_{{ $shipment->first()->id }}">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-center font-semibold leading-6 text-2xl text-gray-900" id="modal-title">{{ $title }}</h3>
                            <div class="mt-2 flex space-x-4">
                                <table class="w-full">
                                    <thead>
                                        <tr class="font-light border-b-2 text-sm bg-neutral-200">
                                            <th class="border-l-2 py-2 px-1 text-center">Рейсы</th>
                                            <th class="border-l-2 py-2 px-1 text-center">Отгружен</th>
                                            <th class="border-r-2 py-2 px-1 text-center">На объекте</th>
                                            <th class="border-r-2 py-2 px-1 text-center">Возврат</th>
                                            <th class="border-r-2 py-2 px-1 text-center">На базе</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($shipment as $num => $transport)
                                        @php
                                            $createdAt = \Carbon\Carbon::parse($transport->created_at);
                                            $timeToCome = \Carbon\Carbon::parse($transport->time_to_come);
                                            $timeToOut = \Carbon\Carbon::parse($transport->time_to_out);
                                            $timeToReturn = \Carbon\Carbon::parse($transport->time_to_return);
                                        @endphp
                                        <tr class="border-y-2">
                                            <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $num + 1 }}</td>
                                            <td class="px-1 m-2 border-r-2 py-3 text-center">{{ $createdAt->format('H:i') }}</td>
                                            <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $timeToCome->format('H:i') }}</td>
                                            <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $timeToOut->format('H:i') }}</td>
                                            <td class="px-1 m-2 border-x-2 py-3 text-center">{{ $timeToReturn->format('H:i') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2 flex space-x-4">
                                <div class="mt-1 w-full">
                                    <label for="shift_description" class="block text-sm font-medium leading-6 text-gray-900 text-left">Комментарий</label>
                                    <div class="mt-2 w-full">
                                        <textarea readonly class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" rows="5">{{ $shipment->first()->transport->shifts[0]->description ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="shift_success" class="text-green-500 text-sm hidden">Изменения сохранены</div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    @if(isset($shipment[0]['transport_id']))
                        <form action="{{ route('api.get.shift_change', ['id' => $shipment[0]['transport_id'], 'date' => $date]) }}" method="post">
                            @if(isset($shipment->first()->transport->shifts[0]->end_shift))
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto">Открыть смену</button>
                            @else
                                <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto">Закрыть смену</button>
                            @endif
                        </form>
                    @endif
                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto shifts-hide">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
