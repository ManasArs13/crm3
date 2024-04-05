<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
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
    public function index(FilterRequest $request)
    {
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
            "delivery_price",
        ];

        $entityItems = Order::query()->with('contact')->select($columns);

        $urlEdit = "order.edit";
        $urlShow = "order.show";
        $urlDelete = "order.destroy";
        $urlCreate = "order.create";
        $urlFilter = 'order.filter';
        $entity = 'orders';
        $needMenuForItem = true;
        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;
        $dateToday = Carbon::now();
        $dateThreeDay = Carbon::now()->addDays(3);
        $dateWeek = Carbon::now()->addDays(7);
        $dateAll = Carbon::now()->addDays(30);
        $queryFilter = 'index';
        $queryPlan = 'index';

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

        $resColumns = [];

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        $resColumnsAll = [
            "id" => ['name_rus' => trans("column.id"), 'checked' => true],
            "name" => ['name_rus' => trans("column.name"), 'checked' => true],
            "date_moment" => ['name_rus' => trans("column.date_moment"), 'checked' => true],
            "contact_id" => ['name_rus' => trans("column.contact_id"), 'checked' => true],
            "sum" => ['name_rus' => trans("column.sum"), 'checked' => true],
            "date_plan" => ['name_rus' => trans("column.date_plan"), 'checked' => true],
            "status_id" => ['name_rus' => trans("column.status_id"), 'checked' => true],
            "comment" => ['name_rus' => trans("column.comment"), 'checked' => true],
            "delivery_id" => ['name_rus' => trans("column.delivery_id"), 'checked' => true],
            "transport_type_id" => ['name_rus' => trans("column.transport_type_id"), 'checked' => true],
            "delivery_price" => ['name_rus' => trans("column.delivery_price"), 'checked' => true],
            "date_fact" => ['name_rus' => trans("column.date_fact"), 'checked' => false],
            "payed_sum" => ['name_rus' => trans("column.payed_sum"), 'checked' => false],
            "shipped_sum" => ['name_rus' => trans("column.shipped_sum"), 'checked' => false],
            "reserved_sum" => ['name_rus' => trans("column.reserved_sum"), 'checked' => false],
            "weight" => ['name_rus' => trans("column.weight"), 'checked' => false],
            "count_pallets" => ['name_rus' => trans("column.count_pallets"), 'checked' => false],
            "norm1_price" => ['name_rus' => trans("column.norm1_price"), 'checked' => false],
            "norm2_price" => ['name_rus' => trans("column.norm2_price"), 'checked' => false],
            "transport_id" => ['name_rus' => trans("column.transport_id"), 'checked' => false],
            "is_demand" => ['name_rus' => trans("column.is_demand"), 'checked' => false],
            "is_made" => ['name_rus' => trans("column.is_made"), 'checked' => false],
            "status_shipped" => ['name_rus' => trans("column.status_shipped"), 'checked' => false],
            "debt" => ['name_rus' => trans("column.debt"), 'checked' => false],
            "order_amo_link" => ['name_rus' => trans("column.order_amo_link"), 'checked' => false],
            "order_amo_id" => ['name_rus' => trans("column.order_amo_id"), 'checked' => false],
            "delivery_price_norm" => ['name_rus' => trans("column.delivery_price_norm"), 'checked' => false],
            "created_at" => ['name_rus' => trans("column.created_at"), 'checked' => false],
            "updated_at" => ['name_rus' => trans("column.updated_at"), 'checked' => false],
            "ms_id" => ['name_rus' => trans("column.ms_id"), 'checked' => false]
        ];

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
                'checked_value' => 'all',
            ],
        ];

        return view("order.index", compact(
            'entityItems',
            'needMenuForItem',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entity",
            'urlFilter',
            'filters',
            'orderBy',
            'selectColumn',
            'dateToday',
            'dateThreeDay',
            'dateWeek',
            'dateAll',
            'queryFilter',
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
        $dateThreeDay = Carbon::now()->addDays(3);
        $dateWeek = Carbon::now()->addDays(7);
        $dateAll = Carbon::now()->addDays(30);
        $queryFilter = 'index';
        $queryPlan = 'all';

        $orderBy  = $request->orderBy;
        $entityItems = Order::query()->with('contact');

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
            $entityItems = $entityItems->select($requestColumns);
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
