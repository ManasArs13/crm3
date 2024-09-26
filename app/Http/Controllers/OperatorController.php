<?php

namespace App\Http\Controllers;

use App\Filters\OrderFilter;
use App\Filters\ShipmentFilter;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\ShipmentRequest;
use App\Models\Delivery;
use App\Models\Option;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\Transport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OperatorController extends Controller
{
    public function orders(OrderRequest $request)
    {
        $urlEdit = "order.edit";
        $urlShow = "order.show";
        $urlDelete = "order.destroy";
        $urlCreate = "order.create";
        $urlFilter = 'order.index';
        $entityName = 'Заказы';

        // Orders
        $builder = Order::query()->with('contact:id,name', 'delivery:id,name', 'transport_type:id,name', 'positions', 'shipments', 'transport');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new OrderFilter($builder, $request))->apply()->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new OrderFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new OrderFilter($builder, $request))->apply()->orderByDesc('id')->paginate(50);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "created_at",
            "contact_id",
            "sostav",
            "positions_count",
            "transport_id",
            "count_pallets",
            "car_number",
            "driver",
            "date_moment",
            "name",
            "sum",
            "shipped_count",
            "residual_count",
            "status_id",
            "comment",
            "delivery_id",
            "transport_type_id",
            "delivery_price",
            // "date_fact",
            "payed_sum",
            "shipped_sum",
            "reserved_sum",
            "weight",
            "norm1_price",
            "norm2_price",
            "is_demand",
            "is_made",
            // "status_shipped",
            "debt",
            "ms_link",
            "order_amo_id",
            "delivery_price_norm",
            "date_plan",
            "updated_at",
            "ms_id"
        ];

        $select = [
            "created_at",
            "contact_id",
            "sostav",
            "positions_count",
            "transport_id",
            "car_number",
            "driver",
        ];

        $selected = $request->columns ?? $select;

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Order::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Order::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Order::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Order::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $minDatePlan = Order::query()->min('date_plan');
        $minDatePlanCkeck = '';
        $maxDatePlan = Order::query()->max('date_plan');
        $maxDatePlanCheck = '';

        $dateToday = Carbon::now()->format('Y-m-d');
        $dateThreeDay = Carbon::now()->addDays(3)->format('Y-m-d');
        $dateWeek = Carbon::now()->addDays(7)->format('Y-m-d');
        $dateAll = Carbon::now()->addDays(30)->format('Y-m-d');

        $statuses = [
            ['value' => 1, 'name' => '[N] Новый', 'checked' => true],
            ['value' => 2, 'name' => 'Думают', 'checked' => true],
            ['value' => 3, 'name' => '[DN] Подтвержден', 'checked' => true],
            ['value' => 4, 'name' => 'На брони', 'checked' => true],
            ['value' => 5, 'name' => '[DD] Отгружен с долгом', 'checked' => true],
            ['value' => 6, 'name' => '[DF] Отгружен и закрыт', 'checked' => true],
            ['value' => 7, 'name' => '[C] Отменен', 'checked' => true],
        ];

        if (isset($request->status)) {
            foreach ($statuses as $key => $status) {
                if (!in_array($status['value'], $request->status)) {
                    $statuses[$key]['checked'] = false;
                }
            }
        }

        $queryMaterial = 'index';
        $queryPlan = 'today';

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at') {
                    if ($value['max']) {
                        $maxCreatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minCreatedCheck = $value['min'];
                    }
                } else if ($key == 'updated_at') {
                    if ($value['max']) {
                        $maxUpdatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minUpdatedCheck = $value['min'];
                    }
                } else if ($key == 'date_plan') {
                    if ($value['min']) {
                        $minDatePlanCkeck = $value['min'];
                    }
                    if ($value['max']) {
                        $maxDatePlanCheck = $value['max'];
                    }
                } else if ($key == 'material') {
                    switch ($value) {
                        case 'concrete':
                            $queryMaterial = 'concrete';
                            break;
                        case 'block':
                            $queryMaterial = 'block';
                            break;
                    }
                }
            }
        }

        return view("operator.orders", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'orderBy',
            'selectColumn',
            'dateToday',
            'dateThreeDay',
            'dateWeek',
            'dateAll',
            'queryMaterial',
            'queryPlan',
            'select',
            'entityName'
        ));
    }

    public function shipments(ShipmentRequest $request)
    {
        $urlEdit = "shipment.edit";
        $urlShow = "shipment.show";
        $urlDelete = "shipment.destroy";
        $urlCreate = "shipment.create";
        $urlFilter = 'shipment.index';
        $entityName = 'Отгрузки';

        // Shipments
        $builder = Shipment::query()->with('order:id,name', 'contact:id,name', 'transport:id,name,car_number,driver', 'transport_type:id,name', 'delivery:id,name', 'products');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderByDesc('id')->paginate(50);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "created_at",
            "contact_id",
            "sostav",
            "products_count",
            "transport_id",
            "car_number",
            "driver",
            "name",
            "ms_id",
            "id",
            "counterparty_link",
            "suma",
            "status",
            "shipment_address",
            "description",
            "delivery_price",
            "delivery_price_norm",
            "saldo",
            "delivery_fee",
            "delivery_id",
            "transport_type_id",
            "order_id",
            "service_link",
            "paid_sum",
            "updated_at",
            "weight",
            'ms_link'
        ];


        $select = [
            "created_at",
            "contact_id",
            "sostav",
            "products_count",
            "transport_id",
            "car_number",
            "driver",
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
                }
            }
        }

        return view("operator.shipments", compact(
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
            'orderBy',
            'selectColumn'
        ));
    }
}
