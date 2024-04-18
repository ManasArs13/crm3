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
            $entityItems = (new OrderFilter($builder, $request))->apply()->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new OrderFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new OrderFilter($builder, $request))->apply()->orderBy('id')->paginate(50);
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
                'status_shipped',
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
            [
                'type' => 'checkbox',
                'name' => 'status',
                'name_rus' => 'Статус',
                'values' => $statuses,
                //    'checked_value' => $queryMaterial,
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

        $products_block = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('building_material', 'бетон')
            ->orderBy('name')
            ->get();
        $products_concrete = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('building_material', 'блок')
            ->orderBy('name')
            ->get();
        $products_delivery = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('building_material', 'доставка')
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
                'products_block',
                'products_concrete',
                'products_delivery',
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

        return redirect()->route("order.index")->with('success', 'Заказ №' . $order->id . ' добавлен');
    }

    public function show(string $id)
    {
        $entityItem = Order::with('positions', 'status', 'contact', 'transport')->find($id);
        $entity = 'Заказ №';
        $action = "order.update";
        $newContact = 'contact.store';

        $statuses = Status::all();
        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();
        $deliveries = Delivery::orderBy('name')->get();
        $products = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->orderBy('name')
            ->get();

        $products_block = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('building_material', 'бетон')
            ->orderBy('name')
            ->get();
        $products_concrete = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('building_material', 'блок')
            ->orderBy('name')
            ->get();
        $products_delivery = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('building_material', 'доставка')
            ->orderBy('name')
            ->get();

        $transports = TransportType::orderBy('name')->get();
        $date = Carbon::now()->format('Y-m-d');
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');

        return view("order.show", compact(
            'entityItem',
            'entity',
            'action',
            'newContact',
            'statuses',
            'contacts',
            'transports',
            'deliveries',
            'products',
            'products_block',
            'products_concrete',
            'products_delivery',
            'date',
            'dateNow'
        ));
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
        $order = Order::find($id);

        if(!$order) {
            return redirect()->route('order.index')->with('warning', 'Заказ ' . $id .  ' не найден!');
        }

        $order->name = $request->name;
        $order->status_id = $request->status;
        $order->contact_id = $request->contact;
        $order->delivery_id = $request->delivery;
        $order->transport_type_id = $request->transport_type;
        $order->date_plan = $request->date;
        $order->date_moment = $request->date_created;

        if ($request->comment) {
            $order->comment = $request->comment;
        }

        $sum = 0;
        $weight = 0;

        $order->positions()->delete();

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

        return redirect()->route("order.show", ['order' => $order->id])->with('success', 'Заказ №' . $order->id . ' обновлён');
    }

    public function destroy(string $id)
    {
        $entityItem = Order::find($id);
        $entityItem->delete();

        return redirect()->route('order.index');
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
