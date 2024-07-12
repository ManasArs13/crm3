<div class="CEB_flex">
    <textarea name="description" class="input" placeholder = "{{ __('column.comment') }}"></textarea>

    <div class="CEB_block">
        <div class="address-field open">
            <input type="text" name="shipmentAddressFull[addInfo]" class="address input"
                placeholder="{{ __('column.shipment_address') }}">
            <div class="address-popup">
                <span class="select2-results">
                    <ul class="select2-results__options" role="listbox">

                    </ul>
                </span>
            </div>
        </div>
        <input type="text" class="distance input" placeholder="{{ __('calculator.km') }}">
    </div>

    <input type="hidden" name="agent[id]">

    <div class="select-popup">
        <select class="input agent_change change_name" data-change="change_phone" style="width: 100%">
            <option value="" selected disabled>{{ __('column.name') }}</option>
        </select>
    </div>
    <input type="hidden" name="agent[name]">
    <input type="hidden" name="agent[phone]">


    <div class="select-popup">
        <select class="input agent_change change_phone" data-change="change_name" style="width: 100%">
            <option value="" selected disabled>{{ __('column.phone') }}</option>
        </select>
    </div>


    <div class="datetime">
        <input type="text" class="input plan deliveryPlannedMoment" readonly
            placeholder='{{ __('column.delivery_date') }}'>
        <input type="hidden" name="deliveryPlannedMoment" placeholder='{{ __('column.delivery_date') }}'>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-calendar2-check" viewBox="0 0 16 16">
            <path
                d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0" />
            <path
                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z" />
            <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5z" />
        </svg>
    </div>

    <div class="select-popup">
        <input type="hidden" name="state">
        <select class="change_state input" style="width: 100%">
            <option value="" selected disabled>{{ __('column.status') }}</option>
        </select>
    </div>
</div>
<div>
    <button type="submit"
        class="rounded bg-green-400 px-6 text-xs font-medium uppercase leading-normal text-white hover:bg-green-700 submit">Отправить
        в мс</button>
</div>
<div class="flex flex-row ">
    <div class="flex basis-1/2">
        <button id="exportButton"
            class="rounded bg-green-600 uppercase px-1 text-xs font-semibold text-white hover:bg-green-700 w-full text-center py-1">
            скачать PDF
        </button>
    </div>
    <script>
        function exportHTMLtoPDF() {
            let htmlElement = document.getElementById('content_to_pdf');

            html2pdf().from(htmlElement).save('exported_file.pdf');
        }

        document.getElementById('exportButton').addEventListener('click', exportHTMLtoPDF);
    </script>
    <div class="flex basis-1/2">
        <a href="{{ route('calculator.createPDF') }}" target="_blank"
            class="rounded bg-green-600 uppercase px-1 text-xs font-semibold text-white hover:bg-green-700 w-full text-center py-1">
            скопировать в буфер обмена
        </a>
    </div>
</div>
