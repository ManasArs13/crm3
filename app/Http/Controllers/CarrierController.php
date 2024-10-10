<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipmentRequest;
use App\Models\Contact;
use App\Models\Shipment;
use App\Models\Transport;

class CarrierController extends Controller
{
    public function index(ShipmentRequest $request){

        $id = $request->id;
        $hash = $request->hash;
        $signature = 'b8b89f347cdf8fb9915d4452b43101';
        if($hash !== hash('sha256', $id . $signature)){
            abort(404);
        }

        $urlEdit = "shipment.edit";
        $urlShow = "shipment.show";
        $urlDelete = "shipment.destroy";
        $urlCreate = "shipment.create";
        $urlFilter = 'carrier.index';
        $entityName = 'Перевозки';

        $contact = Contact::findOrFail($id);

        // Shipments
        $builder = Shipment::query()
            ->with('order:id,name', 'carrier:id,name', 'contact:id,name', 'transport:id,name', 'transport_type:id,name', 'delivery:id,name', 'products')
            ->where('contact_id', $contact->id)
            ->when(isset($request->filters['transport']), function ($query) use ($request) {
                return $query->where('transport_id', $request->filters['transport']);
            });

        $positions_count = $builder->clone()->Join('shipment_products', 'shipments.id', '=', 'shipment_products.shipment_id')
            ->sum('quantity');

        $totals = [
            'totalCount' => $positions_count,
            'totalDeliveryPrice' => $builder->sum('delivery_price'),
            'totalDeliveryPriceNorm' => $builder->sum('delivery_price_norm')
        ];


        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = $builder->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = $builder->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = $builder->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }


        // Columns
        $all_columns = [
            "id",
            "contact_id",
            "delivery_id",
            "transport_id",
            "products_count",
            "delivery_price_norm",
            "delivery_price",
        ];


        $select = [
            "id",
            "contact_id",
            "delivery_id",
            "transport_id",
            "products_count",
            "delivery_price_norm",
            "delivery_price",
        ];

        $selected = $request->columns ?? $select;

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        $transports = Shipment::query()
            ->with('transport')
            ->whereNotNull('transport_id')
            ->where('contact_id', $contact->id)
            ->groupBy('transport_id')
            ->get();

        $transportValues[] = ['value' => '', 'name' => 'Весь транспорт'];

        foreach ($transports as $shipment) {
            if ($shipment->transport) {
                $transportValues[] = [
                    'value' => $shipment->transport->id,
                    'name' => $shipment->transport->name
                ];
            }
        }

        $queryTransport = 'index';

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                switch ($key) {
                    case 'transport':
                        $queryTransport = $value;
                        break;

                }
            }
        }

        $filters = [
            [
                'type' => 'select',
                'name' => 'transport',
                'name_rus' => 'Транспорт',
                'values' => $transportValues,
                'checked_value' => $queryTransport,
            ],

        ];


        return view("carrier.index", compact(
            'select',
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entityName",
            'urlFilter',
            "filters",
            'orderBy',
            'selectColumn',
            'contact',
            'totals'
        ));
    }
}
