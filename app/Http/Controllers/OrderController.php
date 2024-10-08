<?php

namespace App\Http\Controllers;

use App\Filters\OrderFilter;
use App\Http\Controllers\Api\Ms\OrderController as MsOrderController;
use App\Http\Requests\OrderRequest;
use App\Models\Contact;
use App\Models\Delivery;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderPosition;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use App\Models\Status;
use App\Models\TransportType;
use App\Services\EntityMs\OrderMsService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

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
        $builder = Order::query()
            ->with('contact:id,name', 'delivery:id,name', 'transport_type:id,name', 'positions', 'shipments', 'shipment_products')
            ->withSum('positions', 'quantity')
            ->withSum('shipment_products', 'quantity');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new OrderFilter($builder, $request))->apply()->orderBy($request->column);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new OrderFilter($builder, $request))->apply()->orderByDesc($request->column);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new OrderFilter($builder, $request))->apply()->orderByDesc('id');
            $selectColumn = null;
        }

        // итоги в таблице
        $totals = $this->total($entityItems);

        $entityItems = $entityItems->paginate(100);

        // Columns
        $all_columns = [
            "id",
            "name",
            "date_moment",
            "contact_id",
            "sostav",
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
            // "date_fact",
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
            // "status_shipped",
            "debt",
            "ms_link",
            "order_amo_id",
            "delivery_price_norm",
            "created_at",
            "updated_at",
            "ms_id"
        ];

        $select = [
            "name",
            "contact_id",
            "sostav",
            'status_shipped',
            "sum",
            "date_plan",
            "status_id",
            "comment",
            "positions_count",
            "residual_count",
            "delivery_id",
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

        $contacts = [];

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
                } else if ($key == 'contacts') {
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
                'type' => 'select2',
                'name' => 'contacts',
                'name_rus' => 'Контакты',
                'values' => $contacts,
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
            'queryPlan',
            'select',
            'entityName',
            'totals'
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
        $products_another = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('category_id', 16)
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
                'products_another',
                'date',
                'dateNow'
            )
        );
    }

    public function store(Request $request)
    {
        $order = new Order();

        $order->status_id = $request->status;
        $order->contact_id = $request->contact;
        $order->delivery_id = $request->delivery;
        $order->transport_type_id = $request->transport_type;
        $order->date_plan = $request->date . ' ' . $request->time;
        $order->date_moment = $request->date_created;
        $order->address = $request->address;
        $order->sum = 0;
        $order->weight = 0;

        if ($request->contact_name == $request->contact_phone) {
            $contact = Contact::where('id', $request->contact_name)->first();
            $order->contact_id = $contact->id;
        } else {
            $contact = Contact::create([
                'name' => $request->contact_name,
                'phone' => $request->contact_phone
            ]);
            $order->contact_id = $contact->id;
        }

        if ($request->comment) {
            $order->comment = $request->comment;
        }

        $order->save();

        // Add Order position
        if ($request->products) {

            $sum = 0;
            $weight = 0;

            foreach ($request->products as $product) {
                if (isset($product['product'])) {
                    $position = new OrderPosition();

                    $product_bd = Product::find($product['product']);
                    $position->product_id = $product_bd->id;
                    $position->order_id = $order->id;
                    $position->quantity = $product['count'];
                    $position->price = $product['price'];
                    $position->weight_kg = $product_bd->weight_kg * $product['count'];
                    $position->shipped = 0;
                    $position->reserve = 0;

                    $position->save();

                    $sum += $product['sum'];
                    $weight += $position->weight_kg;
                }
            }

            $order->sum = $sum;
            $order->weight = $weight;

            $order->update();
        }

        return redirect()->route("order.show", ["order" => $order->id])->with('success', 'Заказ №' . $order->id . ' добавлен');
    }

    public function show(string $id)
    {
        $entityItem = Order::with('positions', 'status', 'contact', 'transport')->find($id);
        $entity = 'Заказ №';
        $action = "order.update";
        $urlDelete = "order.destroy";
        $newContact = 'contact.store';

        $statusesAll = Status::all();
        $statuses = $statusesAll->whereNotIn('id', $entityItem->status_id);


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
        $products_another = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('category_id', 16)
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
            'products_another',
            'date',
            'dateNow',
            'urlDelete'
        ));
    }

    public function edit(string $id) {}

    public function update(Request $request, string $id, OrderMsService $orderMsService)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect()->route('order.index')->with('warning', 'Заказ ' . $id .  ' не найден!');
        }

        if ($request->action == "create_shipment") {

            $shipment = new Shipment();

            $shipment->order_id = $order->id;
            $shipment->delivery_id = $request->delivery;
            $shipment->contact_id = $request->contact;
            $shipment->shipment_address = $request->address;

            $shipment->weight = 0;
            $shipment->paid_sum = 0;
            $shipment->suma = 0;


            if ($request->comment) {
                $shipment->description = $request->comment;
            }

            $shipment->save();

            if ($request->products) {
                foreach ($request->products as $product) {
                    if (isset($product['product'])) {
                        $shipmentproduct = new ShipmentProduct();

                        $product_bd = Product::find($product['product']);
                        $shipmentproduct->product_id = $product_bd->id;
                        $shipmentproduct->shipment_id = $shipment->id;
                        $shipmentproduct->quantity = $product['count'];
                        $shipmentproduct->price = $product['price'];

                        $shipment->suma += $product['sum'];
                        $shipment->weight += $product_bd->weight_kg * $product['count'];
                        $shipmentproduct->save();
                    }
                }
                $shipment->save();
            }

            return redirect()->route("shipment.show", ['shipment' => $shipment->id])->with('success', 'Отгрузка №' . $shipment->name . '(' . $shipment->id . ') создана');
        } elseif ($request->action == "save") {

            $order->status_id = $request->status;
            $order->delivery_id = $request->delivery;
            $order->transport_type_id = $request->transport_type;
            $order->address = $request->address;
            $order->date_plan = $request->date . ' ' . $request->time;
            $order->date_moment = $request->date_created;

            if ($request->contact_name == $request->contact_phone) {
                $contact = Contact::where('id', $request->contact_name)->first();
                $order->contact_id = $contact->id;
            } else {
                $contact = Contact::create([
                    'name' => $request->contact_name,
                    'phone' => $request->contact_phone
                ]);
                $order->contact_id = $contact->id;
            }

            if ($request->comment) {
                $order->comment = $request->comment;
            }

            $sum = 0;
            $weight = 0;

            $order->positions()->delete();

            // Add Order position
            if ($request->products) {
                foreach ($request->products as $product) {
                    if (isset($product['product'])) {
                        $position = new OrderPosition();

                        $product_bd = Product::find($product['product']);
                        $position->product_id = $product_bd->id;
                        $position->order_id = $order->id;
                        $position->quantity = $product['count'];
                        $position->price = $product['price'];
                        $position->weight_kg = $product_bd->weight_kg * $product['count'];
                        $position->shipped = 0;
                        $position->reserve = 0;

                        $position->save();

                        $sum += $product['sum'];
                        $weight += $position->weight_kg;
                    }
                }
            }

            $order->sum = $sum;
            $order->weight = $weight;

            $order->update();

            $req = $orderMsService->createOrderToMs($order->id);
            $result = json_decode(json_encode($req), true);

            if (isset($result['id']) && $result['id'] !== null) {
                $order->ms_id = $result['id'];
                $order->name = $result['name'];
                $order->update();

                return redirect()->route("order.show", ['order' => $order->id])->with('success', 'Заказ №' . $order->id . ' обновлён и отправлен');
            } else {
                return redirect()->route("order.show", ['order' => $order->id])->with('warning', $result);
            }
        }
    }

    public function destroy(string $id)
    {
        $order = Order::with('shipments')->find($id);
        $order->positions()->delete();

        if ($order->shipments()) {
            foreach ($order->shipments() as $shipment) {
                $shipment->order_id = null;
                $shipment->update();
            }
        }

        $order->delete();

        return redirect()->route('order.index')->with('success', 'Заказ удалён');
    }

    public function total($entityItems){
        $itemCursor = $entityItems->cursor();

        $order_totals = [
            'total_sum' => $entityItems->sum('sum'),
            'total_delivery_price' => $entityItems->sum('delivery_price'),
            'total_payed_sum' => $entityItems->sum('payed_sum'),
            'total_shipped_sum' => $entityItems->sum('shipped_sum'),
            'total_reserved_sum' => $entityItems->sum('reserved_sum'),
            'total_debt' => $entityItems->sum('debt'),
            'shipped_count' => $itemCursor->sum('shipment_products_sum_quantity'),
            'positions_count' => $itemCursor->sum('positions_sum_quantity'),
        ];




        return $order_totals;
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

    public function print(Request $request)
    {
        $order = Order::with('positions.product', 'contact')->find($request->id);
        return view('print.print', compact('order'));
    }
}
