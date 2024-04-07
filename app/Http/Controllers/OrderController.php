<?php

namespace App\Http\Controllers;

use App\Filters\OrderFilter;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Contact;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderPosition;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use App\Models\Status;
use App\Models\TransportType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function index(OrderRequest $request)
    {
        $urlEdit = "order.edit";
        $urlShow = "order.show";
        $urlDelete = "order.destroy";
        $urlCreate = "order.create";
        $urlFilter = 'order.index';
        $entityName = 'Заказы';

        // Orders
        $builder = Order::query()->with('contact', 'delivery', 'transport_type', 'positions');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new OrderFilter($builder, $request, $request->column, $request->orderBy))->apply();
            $selectColumn = $request->column;
            $orderBy = 'desc';
        } else {
            $entityItems = (new OrderFilter($builder, $request))->apply();
            $selectColumn = null;
            $orderBy = 'asc';
        }


        // Columns
        $all_columns = [
            "id",
            "name",
            "date_moment",
            "contact_id",
            "sum",
            "date_plan",
            "status_id",
            "comment",
            "delivery_id",
            "transport_type_id",
            "positions_count",
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
            "order_amo_link",
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
                "id",
                "name",
                "date_moment",
                "contact_id",
                "sum",
                "date_plan",
                "status_id",
                "comment",
                "positions_count",
                "delivery_id",
                "transport_type_id",
                "delivery_price",
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
        $minCreatedCheck = ' ';
        $maxCreated = Order::query()->max('created_at');
        $maxCreatedCheck = ' ';

        $minUpdated = Order::query()->min('updated_at');
        $minUpdatedCheck = ' ';
        $maxUpdated = Order::query()->max('updated_at');
        $maxUpdatedCheck = ' ';

        $minDatePlan = Order::query()->min('date_plan');
        $minDatePlanCkeck = ' ';
        $maxDatePlan = Order::query()->max('date_plan');
        $maxDatePlanCheck = ' ';

        $dateToday = Carbon::now()->format('Y-m-d');
        $dateThreeDay = Carbon::now()->addDays(3)->format('Y-m-d');
        $dateWeek = Carbon::now()->addDays(7)->format('Y-m-d');
        $dateAll = Carbon::now()->addDays(30)->format('Y-m-d');

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
                }
                if ($key == 'updated_at') {
                    if ($value['max']) {
                        $maxUpdatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minUpdatedCheck = $value['min'];
                    }
                }
                if ($key == 'date_plan') {
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
                'type' => 'date',
                'name' =>  'date_plan',
                'name_rus' => 'Плановая дата',
                'min' => substr($minDatePlan, 0, 10),
                'minChecked' => $minDatePlanCkeck,
                'max' => substr($maxDatePlan, 0, 10),
                'maxChecked' => $maxDatePlanCheck
            ],
            [
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => [['value' => 'index', 'name' => 'Все'], ['value' => 'block', 'name' => 'Блок'], ['value' => 'concrete', 'name' => 'Бетон']],
                'checked_value' => $queryMaterial,
            ],
        ];

        return view("order.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
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

    public function create()
    {
        $entity = 'new order';
        $action = "order.store";
        $newContact = 'contact.store';

        $statuses = Status::all();
        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();
        $deliveries = Delivery::orderBy('name')->get();
        $products = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->orderBy('name')
            ->get();

        $transports = TransportType::orderBy('name')->get();
        $date = Carbon::now()->format('Y-m-d');
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');

        return view(
            'order.create',
            compact(
                'action',
                'entity',
                'newContact',
                'statuses',
                'contacts',
                'transports',
                'deliveries',
                'products',
                'date',
                'dateNow'
            )
        );
    }

    public function store(Request $request)
    {
        $order = new Order();
        $order->name = 'CRM-' . $request->name;
        $order->status_id = $request->status;
        $order->contact_id = $request->contact;
        $order->delivery_id = $request->delivery;
        $order->transport_type_id = $request->transport_type;
        $order->date_plan = $request->date . ' ' . $request->time;
        $order->date_moment = $request->date_created;

        if ($request->comment) {
            $order->comment = $request->comment;
        }

        $order->save();

        $sum = 0;
        $weight = 0;

        // Add Order position
        foreach ($request->products as $product) {

            $position = new OrderPosition();

            $product_bd = Product::find($product['product']);
            $position->product_id = $product_bd->id;
            $position->order_id = $order->id;
            $position->quantity = $product['count'];
            $position->price = $product_bd->price;
            $position->weight_kg = $product_bd->price * $product['count'];
            $position->shipped = 0;
            $position->reserve = 0;

            $position->save();

            $sum += $position->price;
            $weight += $position->weight_kg;
        }

        $order->sum = $sum;
        $order->weight = $weight;

        $order->update();


        // Add shipment
        if ($request->shipment_need) {

            $shipment = new Shipment();
            $shipment->name = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
            $shipment->order_id = $order->id;
            $shipment->delivery_id = $request->delivery;
            $shipment->transport_type_id = $request->transport_type;
            $shipment->weight = $weight;
            $shipment->paid_sum = 0;
            $shipment->suma = 0;

            $shipment->save();

            foreach ($request->products as $product) {

                $shipmentproduct = new ShipmentProduct();

                $product_bd = Product::find($product['product']);
                $shipmentproduct->product_id = $product_bd->id;
                $shipmentproduct->shipment_id = $shipment->id;
                $shipmentproduct->quantity = $product['count'];

                $shipmentproduct->save();
            }
        }

        return redirect()->route("order.index")->with('succes', 'Заказ №' . $order->id . ' добавлен');
    }

    public function show(string $id)
    {
        $entityItem = Order::findOrFail($id);
        $columns = Schema::getColumnListing('orders');
        $entity = 'order';

        return view("own.show", compact('entityItem', 'columns', 'entity'));
    }

    public function edit(string $id)
    {
        $entityItem = Order::find($id);
        $columns = Schema::getColumnListing('orders');
        $entity = 'order';
        $action = "order.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = Order::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('order.index');
    }

    public function destroy(string $id)
    {
        $entityItem = Order::find($id);
        $entityItem->delete();

        return redirect()->route('order.index');
    }
    public function filter(FilterRequest $request)
    {
        $urlEdit = "order.edit";
        $urlShow = "order.show";
        $urlDelete = "order.destroy";
        $urlCreate = "order.create";
        $urlFilter = 'order.filter';
        $urlReset = 'order.index';
        $entity = 'orders';
        $selectColumn = $request->column;
        $needMenuForItem = true;
        $dateToday = Carbon::now()->format('Y-m-d');
        $dateThreeDay = Carbon::now()->addDays(3)->format('Y-m-d');
        $dateWeek = Carbon::now()->addDays(7)->format('Y-m-d');
        $dateAll = Carbon::now()->addDays(30)->format('Y-m-d');
        $queryFilter = 'index';
        $queryPlan = 'all';

        $orderBy  = $request->orderBy;
        $entityItems = Order::query()->with('contact', 'delivery', 'transport_type');

        $columns = [
            "id",
            "name",
            "date_moment",
            "contact_id",
            "sum",
            "date_plan",
            "status_id",
            "comment",
            "delivery_id",
            "transport_type_id",
            "positions_count",
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
            "order_amo_link",
            "order_amo_id",
            "delivery_price_norm",
            "created_at",
            "updated_at",
            "ms_id",
        ];

        $resColumns = [];
        $resColumnsAll = [];

        /* Колонки для меню */
        foreach ($columns as $column) {
            if ($column == 'name') {
                $resColumnsAll[$column] = [
                    'name_rus' => trans("column." . $column),
                    'checked' => in_array($column, $request->columns ? $request->columns : []) ? true : false
                ];

                $resColumnsAll['contact_id'] = [
                    'name_rus' => trans("column." . 'contact_id'),
                    'checked' => in_array('contact_id', $request->columns ? $request->columns : []) ? true : false
                ];
            }
            $resColumnsAll[$column] = [
                'name_rus' => trans("column." . $column),
                'checked' => in_array($column, $request->columns ? $request->columns : []) ? true : false
            ];
        }

        /* Колонки для отображения */
        if (isset($request->columns)) {
            $requestColumns = $request->columns;
            $requestColumns[] = "id";
            $columns = $requestColumns;

            if (in_array('positions_count', $requestColumns)) {
                unset($requestColumns[array_search('positions_count', $requestColumns, true)]);
            }

            $entityItems = $entityItems->select($requestColumns)->withCount('positions');
        }

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        /* Фильтры для отображения */
        if ($request->filters['material'] == 'concrete') {

            $entityItems = $entityItems
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                });
            $material = 'concrete';
            $queryFilter = 'concrete';
        } else if ($request->filters['material'] == 'block') {

            $entityItems = $entityItems
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                });
            $material = 'block';
            $queryFilter = 'block';
        } else {

            $material = 'index';
        }

        /* Фильтры для отображения */
        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at' || $key == 'updated_at' || $key == 'date_plan') {
                    if ($key == 'date_plan') {
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
                    $entityItems = $entityItems
                        ->where($key, '>=', $value['min'] . ' 00:00:00')
                        ->where($key, '<=', $value['max'] . ' 23:59:59');
                }
            }
        }

        /* Сортировка */
        if (isset($request->orderBy)  && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems = $entityItems->paginate(50);
        }

        $minCreated = Order::query()->min('created_at');
        $maxCreated = Order::query()->max('created_at');
        $minUpdated = Order::query()->min('updated_at');
        $maxUpdated = Order::query()->max('updated_at');
        $minDatePlan = Order::query()->min('date_plan');
        $maxDatePlan = Order::query()->max('date_plan');

        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'max' => substr($maxCreated, 0, 10)
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => 'Дата обновления',
                'min' => substr($minUpdated, 0, 10),
                'max' => substr($maxUpdated, 0, 10)
            ],
            [
                'type' => 'date',
                'name' =>  'date_plan',
                'name_rus' => 'Плановая дата',
                'min' => substr($minDatePlan, 0, 10),
                'max' => substr($maxDatePlan, 0, 10)
            ],
            [
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => [['value' => 'index', 'name' => 'Все'], ['value' => 'block', 'name' => 'Блок'], ['value' => 'concrete', 'name' => 'Бетон']],
                'checked_value' =>  $material,
            ],
        ];

        return view("order.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entity",
            'urlFilter',
            'urlReset',
            'orderBy',
            'filters',
            'needMenuForItem',
            'selectColumn',
            'dateToday',
            'dateThreeDay',
            'dateWeek',
            'dateAll',
            'queryFilter',
            'queryPlan'
        ));
    }

    public function get_api(Request $request)
    {
        $orders = Order::query()
            ->where('name', 'LIKE', '%' . $request->query('term') . '%')
            ->orWhere('id', 'LIKE',  '%' . $request->query('term') . '%')
            ->orWhere('date_plan', 'LIKE',  '%' . $request->query('term') . '%')
            ->orWhere('date_moment', 'LIKE',  '%' . $request->query('term') . '%')
            ->latest()->take(5)->get();

        return response()->json($orders);
    }
}
