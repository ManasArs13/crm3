<?php

namespace App\Http\Controllers;

use App\Filters\OrderFilter;
use App\Filters\ShipmentFilter;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\ShipmentRequest;
use App\Models\Option;
use App\Models\Order;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OperatorController extends Controller
{
    public function orders(OrderRequest $request)
    { {
            $urlEdit = "order.edit";
            $urlShow = "order.show";
            $urlDelete = "order.destroy";
            $urlCreate = "order.create";
            $urlFilter = 'order.index';
            $entityName = 'Заказы';

            // Orders
            $builder = Order::query()->with('contact:id,name', 'delivery:id,name', 'transport_type:id,name', 'positions:order_id,id,quantity', 'transport:id,name');

            if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
                $entityItems = (new OrderFilter($builder, $request))->apply()->whereDate('date_plan', Carbon::now()->format('Y-m-d'))->orderBy($request->column)->paginate(50);
                $orderBy = 'desc';
                $selectColumn = $request->column;
            } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
                $entityItems = (new OrderFilter($builder, $request))->apply()->whereDate('date_plan', Carbon::now()->format('Y-m-d'))->orderByDesc($request->column)->paginate(50);
                $orderBy = 'asc';
                $selectColumn = $request->column;
            } else {
                $orderBy = 'desc';
                $entityItems = (new OrderFilter($builder, $request))->apply()->whereDate('date_plan', Carbon::now()->format('Y-m-d'))->orderByDesc('id')->paginate(50);
                $selectColumn = null;
            }

            // Columns
            $all_columns = [
                "id",
                "name",
                "date_moment",
                "contact_id",
                "sum",
                "date_plan",
                "positions_count",
                "shipped_count",
                "residual_count",
                "status_id",
                "comment",
                "delivery_id",
                "transport_type_id",
                "delivery_price",
                "date_fact",
                "payed_sum",
                "shipped_sum",
                "reserved_sum",
                "weight",
                "count_pallets",
                "norm1_price",
                "norm2_price",
                "transport_id",
                "is_demand",
                "is_made",
                "status_shipped",
                "debt",
                "ms_link",
                "order_amo_id",
                "delivery_price_norm",
                "created_at",
                "updated_at",
                "ms_id"
            ];

            if (isset($request->columns)) {
                $selected = $request->columns;
            } else {
                $selected = [
                    "contact_id",
                    "date_plan",
                    "status_id",
                    "positions_count",
                    "residual_count",
                    "delivery_id",
                    "transport_id",
                ];
            }

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
            $queryPlan = 'all';

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

                            switch ($value['max']) {
                                case $dateToday:
                                    $queryPlan = 'today';
                                    break;
                                case $dateThreeDay:
                                    $queryPlan = 'threeday';
                                    break;
                                case $dateWeek:
                                    $queryPlan = 'week';
                                    break;
                                case $dateAll:
                                    $queryPlan = 'all';
                                    break;
                            }
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

            $filters = [
                [
                    'type' => 'select',
                    'name' => 'material',
                    'name_rus' => 'Материал',
                    'values' => [['value' => 'index', 'name' => 'Все'], ['value' => 'block', 'name' => 'Блок'], ['value' => 'concrete', 'name' => 'Бетон']],
                    'checked_value' => $queryMaterial,
                ],
                [
                    'type' => 'checkbox',
                    'name' => 'status',
                    'name_rus' => 'Статус',
                    'values' => $statuses,
                    //    'checked_value' => $queryMaterial,
                ],
            ];

            return view("operator.orders", compact(
                'entityItems',
                "resColumns",
                "resColumnsAll",
                "urlShow",
                "urlDelete",
                "urlEdit",
                //   "urlCreate",
                'urlFilter',
                'filters',
                'orderBy',
                'selectColumn',
                'dateToday',
                'dateThreeDay',
                'dateWeek',
                'dateAll',
                'queryMaterial',
                'queryPlan'
            ));
        }
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
        $builder = Shipment::query()->with('order:id,name', 'contact:id,name', 'transport:id,name', 'transport_type:id,name', 'delivery:id,name', 'products');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->whereDate('created_at', Carbon::now()->format('Y-m-d'))->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->whereDate('created_at', Carbon::now()->format('Y-m-d'))->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->whereDate('created_at', Carbon::now()->format('Y-m-d'))->orderByDesc('id')->paginate(50);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "name",
            "created_at",
            "contact_id",
            "suma",
            "status",
            "products_count",
            "delivery_id",
            "order_id",
            "counterparty_link",
            "service_link",
            "description",
            "paid_sum",
            "shipment_address",
            "delivery_price",
            "delivery_price_norm",
            "delivery_fee",
            "transport_id",
            "transport_type_id",
            "updated_at",
            "weight",
            'ms_link',
            "ms_id"
        ];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = [
                //   "name",
                "created_at",
                //     "counterparty_link",
                "contact_id",
                //    "suma",
                //  "status",
                "products_count",
                //   "description",
                //    "delivery_price",
                //    "delivery_fee",
                "delivery_id",
                //    "transport_type_id",
                "transport_id",
                //    'ms_link',
            ];
        }

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

        $queryMaterial = 'index';

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at') {
                    if ($value['max']) {
                        $maxCreatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minCreatedCheck = $value['min'];
                    }
                }
                if ($key == 'updated_at') {
                    if ($value['max']) {
                        $maxUpdatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minUpdatedCheck = $value['min'];
                    }
                }

                if ($key == 'material') {
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
        ];

        return view("operator.shipments", compact(
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
