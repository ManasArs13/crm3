<?php

namespace App\Http\Controllers;

use App\Filters\ShipmentFilter;
use App\Http\Requests\ShipmentRequest;
use App\Models\Contact;
use App\Models\Carrier;
use App\Models\Delivery;
use App\Models\Shipment;
use App\Models\Transport;
use Illuminate\Http\Request;

class CarrierController extends Controller
{
    public function index(ShipmentRequest $request){

        $id = $request->id;
        $hash = $request->hash;
        $signature = 'b8b89f347cdf8fb9915d4452b43101';
        if($hash !== hash('sha256', $id . $signature)){
            abort(404);
        }

        $transport_filter = isset($request->filters['transport']) && $request->filters['transport'] != 'index' ? $request->filters['transport'] : 'index';
        $urlEdit = "shipment.edit";
        $urlShow = "shipment.show";
        $urlDelete = "shipment.destroy";
        $urlCreate = "shipment.create";
        $urlFilter = 'carrier.index';
        $entityName = 'Перевозки';

        // Shipments
        $builder = Shipment::query()
            ->with('order:id,name', 'carrier:id,name', 'contact:id,name', 'transport:id,name', 'transport_type:id,name', 'delivery:id,name', 'products')
            ->where('carrier_id', $id)
            ->when(isset($request->filters['transport']) && $request->filters['transport'] != 'index', function($query) use ($request) {
                return $query->where('transport_id', $request->filters['transport']);
            });


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


        // Значение фильтра транспорта
        $transports = Transport::select('id', 'name')->orderBy('name')->get();
        $transportValues[] = ['value' => 'index', 'name' => 'Весь транспорт'];

        foreach ($transports as $transport) {
            $transportValues[] = ['value' => $transport->id, 'name' => $transport->name];
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
            'selectColumn'
        ));
    }
}
