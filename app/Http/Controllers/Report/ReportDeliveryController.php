<?php

namespace App\Http\Controllers\report;

use App\Filters\ShipmentFilter;
use App\Http\Controllers\Controller;
use App\Models\Carrier;
use App\Models\Contact;
use App\Models\Delivery;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use App\Models\Transport;
use App\Http\Requests\ShipmentRequest;
use Illuminate\Http\Request;

class ReportDeliveryController extends Controller
{
    public function index(ShipmentRequest $request)
    {
        $urlEdit = "shipment.edit";
        $urlShow = "shipment.show";
        $urlDelete = "shipment.destroy";
        $urlCreate = "shipment.create";
        $urlFilter = 'report.delivery';
        $entityName = 'Все доставки';

        // Shipments
        $builder = Shipment::query()->with('order:id,name', 'carrier:id,name', 'contact:id,name', 'transport:id,name,car_number', 'transport_type:id,name', 'delivery:id,name', 'products');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderBy($request->column);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderByDesc($request->column);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderByDesc('id');
            $selectColumn = null;
        }

        // Итоги в таблице
        $totals = $this->total($entityItems);

        $entityItems = $entityItems->paginate(100);

        // Columns
        $all_columns = [
            "id",
            "created_at",
            "contact_id",
            "products_count",
            "delivery_price",
            "delivery_address",
            "shipment_address",
            "carrier",
            "car_number",
            "transport_id",
            "name",
            "order_id",
            "ms_id",
            //            "counterparty_link",
            "suma",
            "status",
            "description",
            "delivery_price_norm",
            "saldo",
            "delivery_fee",
            "delivery_id",
            "transport_type_id",
            "sostav",
            "service_link",
            "paid_sum",
            "updated_at",
            "weight",
            'ms_link'
        ];


        $select = [
            "id",
            "created_at",
            "contact_id",
            "products_count",
            "delivery_price",
            "delivery_address",
            "carrier",
            "car_number",
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
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'minChecked' => $minCreatedCheck,
                'max' => substr($maxCreated, 0, 10),
                'maxChecked' => $maxCreatedCheck
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => 'Дата обновления',
                'min' => substr($minUpdated, 0, 10),
                'minChecked' => $minUpdatedCheck,
                'max' => substr($maxUpdated, 0, 10),
                'maxChecked' => $maxUpdatedCheck
            ],
            [
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => [['value' => 'index', 'name' => 'Все'], ['value' => 'block', 'name' => 'Блок'], ['value' => 'concrete', 'name' => 'Бетон']],
                'checked_value' => $queryMaterial,
            ],
            [
                'type' => 'select',
                'name' => 'delivery',
                'name_rus' => 'Доставка',
                'values' => $deliveryValues,
                'checked_value' => $queryDelivery,
            ],
            [
                'type' => 'select2',
                'name' => 'contacts',
                'name_rus' => 'Контакты',
                'values' => $contacts,
            ],
            [
                'type' => 'select2',
                'name' => 'carriers',
                'name_rus' => 'Перевозчики',
                'values' => $carriers,
            ],
            [
                'type' => 'select',
                'name' => 'status',
                'name_rus' => "Статус",
                'values' => $statusValues,
                'checked_value' => $queryStatus,
            ],
            [
                'type' => 'select',
                'name' => 'transport',
                'name_rus' => 'Транспорт',
                'values' => $transportValues,
                'checked_value' => $queryTransport,
            ],
        ];


        return view("report.delivery", compact(
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
            'totals'
        ));
    }

    public function deviations(ShipmentRequest $request){
        $urlEdit = "shipment.edit";
        $urlShow = "shipment.show";
        $urlDelete = "shipment.destroy";
        $urlCreate = "shipment.create";
        $urlFilter = 'report.delivery_deviations';
        $entityName = 'Сводка - Отклонения доставок';

        // Shipments
        $builder = Shipment::query()->with('order:id,name', 'carrier:id,name', 'contact:id,name', 'transport:id,name,car_number', 'transport_type:id,name', 'delivery:id,name', 'products');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderBy($request->column);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderByDesc($request->column);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderByDesc('id');
            $selectColumn = null;
        }

        // Итоги в таблице
        $totals = $this->total($entityItems);

        $entityItems = $entityItems->paginate(100);

        // Columns
        $all_columns = [
            "id",
            "created_at",
            "contact_id",
            "delivery_address",
            "delivery_price_norm",
            "delivery_price",
            "saldo",
            "delivery_fee",
            "suma",
            "products_count",
            "shipment_address",
            "carrier",
            "car_number",
            "transport_id",
            "name",
            "order_id",
            "ms_id",
            //            "counterparty_link",
            "status",
            "description",
            "delivery_id",
            "transport_type_id",
            "sostav",
            "service_link",
            "paid_sum",
            "updated_at",
            "weight",
            'ms_link'
        ];


        $select = [
            "id",
            "created_at",
            "contact_id",
            "delivery_address",
            "delivery_price_norm",
            "delivery_price",
            "saldo",
            "delivery_fee",
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
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'minChecked' => $minCreatedCheck,
                'max' => substr($maxCreated, 0, 10),
                'maxChecked' => $maxCreatedCheck
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => 'Дата обновления',
                'min' => substr($minUpdated, 0, 10),
                'minChecked' => $minUpdatedCheck,
                'max' => substr($maxUpdated, 0, 10),
                'maxChecked' => $maxUpdatedCheck
            ],
            [
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => [['value' => 'index', 'name' => 'Все'], ['value' => 'block', 'name' => 'Блок'], ['value' => 'concrete', 'name' => 'Бетон']],
                'checked_value' => $queryMaterial,
            ],
            [
                'type' => 'select',
                'name' => 'delivery',
                'name_rus' => 'Доставка',
                'values' => $deliveryValues,
                'checked_value' => $queryDelivery,
            ],
            [
                'type' => 'select2',
                'name' => 'contacts',
                'name_rus' => 'Контакты',
                'values' => $contacts,
            ],
            [
                'type' => 'select2',
                'name' => 'carriers',
                'name_rus' => 'Перевозчики',
                'values' => $carriers,
            ],
            [
                'type' => 'select',
                'name' => 'status',
                'name_rus' => "Статус",
                'values' => $statusValues,
                'checked_value' => $queryStatus,
            ],
            [
                'type' => 'select',
                'name' => 'transport',
                'name_rus' => 'Транспорт',
                'values' => $transportValues,
                'checked_value' => $queryTransport,
            ],
        ];


        return view("report.delivery_deviations", compact(
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
            'totals'
        ));
    }
    public function total($entityItems){
        $shipment_totals = [
            'total_sum' => $entityItems->sum('suma'),
            'total_delivery_price' => $entityItems->sum('delivery_price'),
            'total_delivery_price_norm' => $entityItems->sum('delivery_price_norm'),
            'total_delivery_sum' => $entityItems->sum('delivery_fee'),
            'total_payed_sum' => $entityItems->sum('paid_sum'),
            'total_saldo' => $entityItems->sum('saldo'),
        ];

        $shipment_position_total = ShipmentProduct::query()
            ->selectRaw('SUM(shipment_products.quantity) as positions_count')
            ->whereIn('shipment_id', $entityItems->pluck('id'))
            ->first();


        return array_merge(
            $shipment_totals +
            $shipment_position_total->toArray()
        );
    }

}