<div class="flex flex-col">
    <table class="text-left text-sm font-light text-nowrap">
        <thead class="bg-neutral-200 font-semibold">
            <tr>
                @foreach ($resColumns as $key => $column)
                    <th scope="col" class="px-6 py-4">
                        @if ($key !== 'weight')
                             {{ $column }}
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody id="orderTable">
            @foreach ($entityItems as $entityItem)
                <tr class="border-b">
                    @foreach ($resColumns as $column => $title)
                        <td class="break-all max-w-38 overflow-hidden px-6 py-4">
                            @if (preg_match('/_id\z/u', $column))
                                @php
                                    $column = substr($column, 0, -3);
                                @endphp
                                @if ($entityItem->$column != null)
                                    @if ($column == 'status')
                                        @switch($entityItem->$column->name)
                                            @case('[DN] Подтвержден')
                                                <div id="status" style="background-color: #b5f8e3">
                                                    <span class="px-4">{{ $entityItem->$column->name }}</span>
                                                </div>
                                            @break

                                            @case('На брони')
                                                <div id="status" style="background-color: #ae96e3">
                                                    <span class="px-4">{{ $entityItem->$column->name }}</span>
                                                </div>
                                            @break

                                            @case('[C] Отменен')
                                                <div id="status" style="background-color: #f3a3a3">
                                                    <span class="px-4">{{ $entityItem->$column->name }}</span>
                                                </div>
                                            @break

                                            @case('Думают')
                                                <div id="status" style="background-color: #6f6ffd">
                                                    <span class="px-4">{{ $entityItem->$column->name }}</span>
                                                </div>
                                            @break

                                            @case('[DD] Отгружен с долгом')
                                                <div id="status" style="background-color: #e5bf7e">
                                                    <span class="px-4">{{ $entityItem->$column->name }}</span>
                                                </div>
                                            @break

                                            @case('[DF] Отгружен и закрыт')
                                                <div id="status" style="background-color: #55c455">
                                                    <span class="px-4">{{ $entityItem->$column->name }}</span>
                                                </div>
                                            @break

                                            @case('[N] Новый')
                                                <div id="status" style="background-color: #f5f590">
                                                    <span class="px-4">{{ $entityItem->$column->name }}</span>
                                                </div>
                                            @break
                                        @endswitch
                                    @else
                                        {{ $entityItem->$column->name }}
                                    @endif
                                @endif
                            @elseif(preg_match('/_link/u', $column) && $entityItem->$column !== null && $entityItem->$column !== '')
                                <a href="{{ $entityItem->$column }}" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z">
                                        </path>
                                        <path fill-rule="evenodd"
                                            d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z">
                                        </path>
                                    </svg>
                                </a>
                            @elseif($column !== 'weight')
                                {{ $entityItem->$column }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const datePicker = document.getElementById("datepicker");
            const ordersTable = document.querySelector("#orderTable");
            const paginate = document.querySelector("#paginate");
            const columnsData = {!! json_encode($resColumns) !!};
            datePicker.addEventListener("change", (event) => {
                showLoadingIndicator()
                const selectedDate = event.target.value;
                $.ajax({
                    url: '{{ route('filter.orders', ['filter' => $filter]) }}',
                    method: 'GET',
                    data: {
                        date: selectedDate
                    },
                    success: function(response) {
                        const columnsData = {!! json_encode($resColumns) !!};
                        ordersTable.innerHTML = '';
                        response.entityItems.forEach(function(order) {
                            const orderRow = document.createElement('tr');
                            for (const columnName in columnsData) {
                                if (!Object.prototype.hasOwnProperty.call(columnsData,
                                        columnName)) {
                                    continue;
                                }
                                const cell = document.createElement('td');
                                if (columnName.match(/_id$/)) {
                                    const fieldName = columnName.replace(/_id$/, '');
                                    if (order[fieldName] && columnName ===
                                        'status_ms_id') {
                                        const statusDiv = document.createElement('div');
                                        statusDiv.id = 'status';
                                        statusDiv.style.border = 'solid';
                                        switch (order[fieldName].name) {
                                            case '[DN] Подтвержден':
                                                statusDiv.style.borderColor = '#3ce0af';
                                                statusDiv.style.backgroundColor =
                                                    '#b5f8e3';
                                                break;
                                            case 'На брони':
                                                statusDiv.style.borderColor = '#5b35a0';
                                                statusDiv.style.backgroundColor =
                                                    '#ae96e3';
                                                break;
                                            case '[C] Отменен':
                                                statusDiv.style.borderColor = '#fc0202';
                                                statusDiv.style.backgroundColor =
                                                    '#f3a3a3';
                                                break;
                                            case 'Думают':
                                                statusDiv.style.borderColor = '#0000ff';
                                                statusDiv.style.backgroundColor =
                                                    '#6f6ffd';
                                                break;
                                            case '[DD] Отгружен с долгом':
                                                statusDiv.style.borderColor = '#fda102';
                                                statusDiv.style.backgroundColor =
                                                    '#e5bf7e';
                                                break;
                                            case '[DF] Отгружен и закрыт':
                                                statusDiv.style.borderColor = '#00ec00';
                                                statusDiv.style.backgroundColor =
                                                    '#55c455';
                                                break;
                                            case '[N] Новый':
                                                statusDiv.style.borderColor = '#ffff00';
                                                statusDiv.style.backgroundColor =
                                                    '#f5f590';
                                                break;
                                            default:
                                                statusDiv.style.borderColor =
                                                    'black'; // стандартные стили
                                        }
                                        const statusSpan = document.createElement(
                                            'span');
                                        statusSpan.textContent = order[fieldName].name;
                                        statusDiv.appendChild(statusSpan);
                                        cell.appendChild(statusDiv);
                                    } else if (order[fieldName] && order[fieldName]
                                        .name) {
                                        cell.textContent = order[fieldName].name;
                                    }
                                } else if (columnName === 'order_amo_link' && order[
                                        columnName]) {
                                    const link = document.createElement('a');
                                    link.href = order[columnName];
                                    link.target = "_blank";
                                    link.innerHTML =
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-up-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6.364 13.5a.5.5 0 0 0 .5.5H13.5a1.5 1.5 0 0 0 1.5-1.5v-10A1.5 1.5 0 0 0 13.5 1h-10A1.5 1.5 0 0 0 2 2.5v6.636a.5.5 0 1 0 1 0V2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5H6.864a.5.5 0 0 0-.5.5z"/><path fill-rule="evenodd" d="M11 5.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793l-8.147 8.146a.5.5 0 0 0 .708.708L10 6.707V10.5a.5.5 0 0 0 1 0v-5z"/></svg>';
                                    cell.appendChild(link);
                                } else if (columnName === 'created_at' || columnName ===
                                    'updated_at') {
                                    const isoDate = order[columnName];
                                    const date = new Date(isoDate);
                                    cell.textContent = date.toLocaleString();
                                } else {
                                    cell.textContent = order[columnName];
                                }
                                orderRow.appendChild(cell);
                            }
                            ordersTable.appendChild(orderRow);
                        });
                        hideLoadingIndicator()
                    },
                    error: function(error) {
                        console.log(error);
                        hideLoadingIndicator()
                    }
                });
            });
        });
    </script>
</div>
