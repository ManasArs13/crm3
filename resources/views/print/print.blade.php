<!DOCTYPE html>
<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>

        @media print {
            @page {
                size: A4;
            }

            body {
                font-family: "Open Sans", sans-serif;
                font-optical-sizing: auto;
                font-style: normal;
                font-size: 12pt;
                margin: 0;
                padding: 21mm;
            }

            .container {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .header, .footer {
                text-align: left;
                border-bottom: 2px solid #000;
            }

            .footer {
                border-top: 1px solid #ddd;
                position: fixed;
                bottom: 0;
                width: 100%;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 0.4mm solid #000;
                padding: 8px;
                text-align: left;
            }

            th {
                font-weight: 600;
            }

            .no-print {
                display: none;
            }
        }

    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Заявка №
            @if(isset($order))
                {{ $order->id . ' от ' . Carbon\Carbon::parse($order->created_at)->format("d.m.Y") . ' г.' }}
            @elseif(isset($shipment))
                {{ $shipment->id . ' от ' . Carbon\Carbon::parse($shipment->created_at)->format("d.m.Y") . ' г.' }}
            @endif
        </h2>
    </div>
    <div class="content">
        <div style="margin-top: 15px;">
            <div style="float: left;">Поставщик</div>
            <div style="float: left;margin-left: 43px;font-weight: 600;">ООО ЕВРОГРУПП</div>
            <br>
            <div>(Исполнитель):</div>
        </div>
        <div style="margin-top: 19px;">
            <div style="float: left;">Покупатель</div>
            <div style="float: left;margin-left: 43px;font-weight: 600;">@if(isset($order)) {{ $order->contact->name }} @elseif(isset($shipment)) {{ $shipment->contact->name }} @endif</div>
            <br>
            <div>(Заказчик):</div>
        </div>
        <table style="margin-top: 7px;">

            <thead>
            <tr>
                <th>№</th>
                <th>Наименование товара</th>
                <th>Цена</th>
                <th>Кол-во</th>
                <th>Ед. изм.</th>
                <th>Сумма</th>
            </tr>
            </thead>

            <tbody>
            @if(isset($order))
                @foreach($order->positions as $key => $position)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $position->product->name }}</td>
                        <td>{{ $position->price }}</td>
                        <td>{{ $position->quantity }}</td>
                        <td>шт</td>
                        <td>{{ $position->price * $position->quantity }}</td>
                    </tr>
                @endforeach
            @elseif(isset($shipment))
                @foreach($shipment->products as $key => $product)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $product->product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>шт</td>
                        <td>{{ $product->price * $product->quantity }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>

        </table>
        <div style="float: right;font-size: 17px;font-weight: 600;margin-top: 10px;">
            <div style="float: right;">@if(isset($order)) {{ $order->sum }} @elseif(isset($shipment)) {{ $totalSuma }} @endif</div>
            <div style="float: right;margin-right: 15px;">Итого:</div>
        </div>
        <div style="width: 100%;height: 30mm;margin-top: 50mm;">
            <div style="width: 55mm;float: left;">
                <div style="float: left;border-bottom: 0.7mm solid #000;width: 100%;"></div>
                <div style="font-weight: 600;">Поставщик</div>
            </div>
            <div style="width: 55mm;float: right;">
                <div style="float: right;border-bottom: 0.7mm solid #000;width: 100%;"></div>
                <div style="font-weight: 600;float: right;">Заказчик</div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
