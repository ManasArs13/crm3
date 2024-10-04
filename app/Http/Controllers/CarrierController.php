<?php

namespace App\Http\Controllers;

use App\Filters\ShipmentFilter;
use App\Models\Contact;
use App\Models\Carrier;
use App\Models\Delivery;
use App\Models\Shipment;
use App\Models\Transport;
use Illuminate\Http\Request;

class CarrierController extends Controller
{
    public function index(Request $request){

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

        // Shipments
        $builder = Shipment::query()
            ->with('order:id,name', 'carrier:id,name', 'contact:id,name', 'transport:id,name', 'transport_type:id,name', 'delivery:id,name', 'products')
            ->where('carrier_id', 102);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "name",
            "order_id",
            "ms_id",
            "id",
            "created_at",
            "contact_id",
            //            "counterparty_link",
            "suma",
            "status",
            "products_count",
            //            "shipment_address",
            "description",
            "delivery_price",
            "delivery_price_norm",
            "saldo",
            "delivery_fee",
            "delivery_id",
            "transport_type_id",
            "transport_id",
            "sostav",
            "service_link",
            "paid_sum",
            "updated_at",
            "weight",
            'ms_link'
        ];


        $select = [
            "name",
            "order_id",
            "created_at",
            "contact_id",
            "suma",
            "status",
            "products_count",
            "description",
            "delivery_price",
            "delivery_price_norm",
            "delivery_fee",
            "delivery_id",
            "transport_type_id",
            "transport_id",
        ];

        $selected = $request->columns ?? $select;

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Shipment::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Shipment::query()->max('created_at');
        $maxCreatedCheck = '';
        $minUpdated = Shipment::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Shipment::query()->max('updated_at');
        $maxUpdatedCheck = '';

        // Значения фильтра доставки
        $deliveries = Delivery::select('id', 'name')->orderBy('distance')->get();
        $deliveryValues[] = ['value' => 'index', 'name' => 'Все доставки'];

        foreach ($deliveries as $delivery) {
            $deliveryValues[] = ['value' => $delivery->id, 'name' => $delivery->name];
        }

        // Значения фильтра статуса
        $statuses = Shipment::select('status')->groupBy('status')->distinct('status')->orderByDesc('status')->get();
        $statusValues[] = ['value' => 'index', 'name' => 'Все статусы'];

        foreach ($statuses as $status) {
            if ($status->status) {
                $statusValues[] = ['value' => $status->status, 'name' => $status->status];
            } else {
                $statusValues[] = ['value' => $status->status, 'name' => 'Не указано'];
            }
        }

        // Значение фильтра транспорта
        $transports = Transport::select('id', 'name')->orderBy('name')->get();
        $transportValues[] = ['value' => 'index', 'name' => 'Весь транспорт'];

        foreach ($transports as $transport) {
            $transportValues[] = ['value' => $transport->id, 'name' => $transport->name];
        }

        $queryMaterial = 'index';
        $queryDelivery = 'index';
        $queryStatus = 'index';
        $queryTransport = 'index';
        $contacts = [];
        $carriers = [];

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                switch ($key) {
                    case 'created_at':
                        if ($value['max']) {
                            $maxCreatedCheck = $value['max'];
                        }
                        if ($value['min']) {
                            $minCreatedCheck = $value['min'];
                        }
                        break;
                    case 'updated_at':
                        if ($value['max']) {
                            $maxUpdatedCheck = $value['max'];
                        }
                        if ($value['min']) {
                            $minUpdatedCheck = $value['min'];
                        }
                        break;
                    case 'material':
                        switch ($value) {
                            case 'concrete':
                                $queryMaterial = 'concrete';
                                break;
                            case 'block':
                                $queryMaterial = 'block';
                                break;
                        }
                        break;
                    case 'delivery':
                        $queryDelivery = $value;
                        break;
                    case 'status':
                        $queryStatus = $value;
                        break;
                    case 'transport':
                        $queryTransport = $value;
                        break;
                    case 'contacts':
                        $contact_names_get = Contact::WhereIn('id', $value)->get(['id', 'name']);
                        if (isset($value)) {
                            $contacts = [];
                            foreach ($contact_names_get as $val){
                                $contacts[] = [
                                    'value' => $val->id,
                                    'name' => $val->name
                                ];
                            }
                        }
                        break;
                    case 'carriers':
                        $carrier_names_get = Carrier::WhereIn('id', $value)->get(['id', 'name']);
                        if (isset($value)) {
                            $carriers = [];
                            foreach ($carrier_names_get as $val){
                                $carriers[] = [
                                    'value' => $val->id,
                                    'name' => $val->name
                                ];
                            }
                        }
                        break;
                }
            }
        }

        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => 'Дата',
                'min' => substr($minCreated, 0, 10),
                'minChecked' => $minCreatedCheck,
                'max' => substr($maxCreated, 0, 10),
                'maxChecked' => $maxCreatedCheck
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
        return view('transport.carrier');
    }
}
