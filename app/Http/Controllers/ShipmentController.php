<?php

namespace App\Http\Controllers;

use AmoCRM\EntitiesServices\Contacts;
use App\Filters\ShipmentFilter;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\ShipmentRequest;
use App\Models\Contact;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use App\Models\Transport;
use App\Services\EntityMs\ShipmentMsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function index(ShipmentRequest $request)
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
            "name",
            "order_id",
            "ms_id",
            "id",
            "created_at",
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
            "contact_id",
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

        return view("shipment.index", compact(
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

    public function index2(ShipmentRequest $request)
    {
        $urlEdit = "shipment.edit";
        $urlShow = "shipment.show";
        $urlDelete = "shipment.destroy";
        $urlCreate = "shipment.create";
        $urlFilter = 'shipment.index2';
        $entityName = 'Отгрузки';

        // Shipments
        $builder = Shipment::query()->with('order:id,name', 'contact:id,name', 'transport:id,name', 'transport_type:id,name', 'delivery:id,name', 'products');

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
            $entityItems = (new ShipmentFilter($builder, $request))->apply()->orderByDesc('shipments.id')->paginate(50);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "name",
            "created_at",
            "contact_id",
            "sostav",
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
                "name",
                "created_at",
                "counterparty_link",
                "contact_id",
                "sostav",
                "suma",
                "status",
                "products_count",
                "description",
                "delivery_id",
                "delivery_price",
                "delivery_fee",
                "delivery_id",
                "transport_type_id",
                "transport_id",
                'ms_link',
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
        $queryShipment = false;


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
                if ($key == 'shipments_debt') {
                    $queryShipment = $value;
                }
            }
        }

        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => trans('column.created_at'),
                'min' => substr($minCreated, 0, 10),
                'minChecked' => $minCreatedCheck,
                'max' => substr($maxCreated, 0, 10),
                'maxChecked' => $maxCreatedCheck
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => trans('column.updated_at'),
                'min' => substr($minUpdated, 0, 10),
                'minChecked' => $minUpdatedCheck,
                'max' => substr($maxUpdated, 0, 10),
                'maxChecked' => $maxUpdatedCheck
            ],
            [
                'type' => 'select',
                'name' => 'material',
                'name_rus' => trans('column.material'),
                'values' => [['value' => 'index', 'name' => 'Все'], ['value' => 'block', 'name' => 'Блок'], ['value' => 'concrete', 'name' => 'Бетон']],
                'checked_value' => $queryMaterial,
            ],

            [
                'type' => 'checkbox',
                'name' => 'shipments_debt',
                'name_rus' => trans('filter.filter1'),
                'value' => $queryShipment,
            ],
        ];




        return view("shipment.index2", compact(
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

    public function create(Request $request)
    {
        $entity = 'new shipment';
        $action = "shipment.store";
        $newContact = 'contact.store';
        $actionWithOrder = "shipment.createWithOrder";
        $searchOrders = "api.get.order";
        $order = [];

        $deliveries = Delivery::orderBy('name')->get();
        $transports = Transport::orderBy('name')->get();
        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();
        $date = Carbon::now()->format('Y-m-d');
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');

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

        $positions = "[{
            id: 0,
            product: '',
            count: 0,
            residual: 0,
            weight_kg: 0,
            weight: 0,
            price: 0,
            sum: 0
        }]";

        return view('shipment.create', compact(
            'action',
            'actionWithOrder',
            'searchOrders',
            'entity',
            'deliveries',
            'newContact',
            'transports',
            'contacts',
            'date',
            'dateNow',
            'order',
            'products',
            'products_block',
            'products_concrete',
            'products_delivery',
            'products_another',
            'positions'
        ));
    }

    public function createWithOrder(Request $request)
    {
        if ($request->order_id == null) {
            return response()->redirectToRoute('shipment.create');
        }

        $order = Order::with('positions', 'contact', 'delivery:id,name')->find($request->order_id);

        if ($order == null) {
            return response()->redirectToRoute('shipment.create');
        }

        $entity = 'new shipment';
        $action = "shipment.store";
        $actionWithOrder = "shipment.createWithOrder";
        $searchOrders = "api.get.order";

        $deliveries = Delivery::orderBy('name')->get();
        $transports = Transport::orderBy('name')->get();
        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();
        $date = Carbon::now()->format('Y-m-d');
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');

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

        $positions = $order->positions;


        return view('shipment.create', compact(
            'action',
            'actionWithOrder',
            'searchOrders',
            'entity',
            'deliveries',
            'transports',
            'contacts',
            'date',
            'dateNow',
            'order',
            'products',
            'products_block',
            'products_concrete',
            'products_delivery',
            'products_another',
            'positions'
        ));
    }

    public function createFromOrder(Request $request, $orderId)
    {
        if ($orderId == null) {
            return response()->redirectToRoute('shipment.create');
        }

        $order = Order::with('positions', 'contact', 'delivery:id,name')->find($orderId);

        if ($order == null) {
            return response()->redirectToRoute('shipment.create');
        }

        $entity = 'new shipment';
        $action = "shipment.store";
        $searchOrders = "api.get.order";
        $newContact = 'contact.store';

        $deliveries = Delivery::orderBy('name')->get();
        $transports = Transport::orderBy('name')->get();
        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();
        $date = Carbon::now()->format('Y-m-d');
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');

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

        $positions = $order->positions;

        return view('shipment.create', compact(
            'action',
            'searchOrders',
            'entity',
            'deliveries',
            'transports',
            'contacts',
            'date',
            'dateNow',
            'order',
            'products',
            'products_block',
            'products_concrete',
            'products_delivery',
            'products_another',
            'positions',
            'newContact'
        ));
    }

    public function store(Request $request)
    {
        $shipment = new Shipment();

        $shipment->status = $request->status;

        $shipment->paid_sum = 0;
        $shipment->suma = 0;
        $shipment->delivery_id = $request->delivery;
        $shipment->transport_id = $request->transport;
        $shipment->weight = $request->weight;

        $contact = Contact::where('id', $request->contact_name)->first();
        $shipment->contact_id = $contact->id;

        if ($request->order_id) {
            $shipment->order_id = $request->order_id;
        }

        if ($request->comment) {
            $shipment->description = $request->comment;
        }

        if ($request->address) {
            $shipment->shipment_address = $request->address;
        }

        $weight = 0;
        $shipment->weight = $weight;

        $shipment->save();

        // Add shipment position
        if ($request->products) {
            foreach ($request->products as $product) {
                if (isset($product['product'])) {

                    $position = new ShipmentProduct();

                    $product_bd = Product::find($product['product']);

                    $position->product_id = $product_bd->id;
                    $position->price = $product['price'];
                    $position->shipment_id = $shipment->id;
                    $position->quantity = $product['count'];

                    $weight +=  $product_bd->weight_kg;

                    $position->save();
                }
            }
        }

        $shipment->weight = $weight;
        $shipment->save();

        return redirect()->route("shipment.show", ["shipment" => $shipment->id])->with('success', 'Отгрузка №' . $shipment->id . ' добавлена');
    }

    public function show(string $id)
    {
        $urlDelete = "shipment.destroy";
        $entityItem = Shipment::with('order', 'contact', 'delivery', 'transport', 'transport_type', 'products')->find($id);
        $entity = 'Отгрузка №';
        $action = "shipment.update";
        $positions = $entityItem->products;
        $searchOrders = "api.get.order";

        $deliveries = Delivery::orderBy('name')->get();
        $transports = Transport::orderBy('name')->get();
        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();
        $statuses = Shipment::select('status')->groupBy('status')->OrderBy('status')->get();

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

        return view("shipment.show", compact(
            'entityItem',
            'entity',
            'searchOrders',
            'action',
            'transports',
            'deliveries',
            'contacts',
            'products',
            'products_block',
            'products_concrete',
            'products_delivery',
            'products_another',
            'positions',
            'statuses',
            'urlDelete'
        ));
    }

    public function update(Request $request, string $id, ShipmentMsService $shipmentMsService)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return redirect()->route('order.index')->with('warning', 'Отгрузка ' . $id .  ' не найдена!');
        }


        $shipment->status = $request->status;

        $shipment->paid_sum = 0;
        $shipment->suma = 0;
        $shipment->delivery_id = $request->delivery;
        $shipment->transport_id = $request->transport;
        $shipment->weight = $request->weight;

        if ($request->order_id) {
            $shipment->order_id = $request->order_id;
        }

        $contact = Contact::where('id', $request->contact_name)->first();
        $shipment->contact_id = $contact->id;

        if ($request->comment) {
            $shipment->description = $request->comment;
        }

        if ($request->address) {
            $shipment->shipment_address = $request->address;
        }

        $shipment->update();

        $shipment->products()->cursor()->each->delete();

        // Add shipment position
        if ($request->products) {
            foreach ($request->products as $product) {
                if (isset($product['product'])) {

                    $position = new ShipmentProduct();

                    $product_bd = Product::find($product['product']);

                    $position->product_id = $product_bd->id;
                    $position->shipment_id = $shipment->id;
                    $position->quantity = $product['count'];
                    $position->price = $product['price'];

                    $position->save();
                }
            }
        }


        // $req = $shipmentMsService->createChipmentToMs($id);
        // $result = json_decode(json_encode($req), true);

        // if (isset($result['id']) && $result['id'] !== null) {
        //     $shipment->ms_id=$result['id'];
        //     $shipment->name=$result['name'];
        //     $shipment->save();

            return redirect()->route("shipment.show", ['shipment' => $shipment->id])->with('success', 'Отгрузка №' . $shipment->id . ' обновлена и отправлена');
        // } else {
        //     info($result);
        //     return redirect()->route("shipment.show", ['shipment' => $shipment->id])->with('warning', 'Ошибка');
        // }

    }

    public function destroy(string $id)
    {
        $entityItem = Shipment::find($id);
        $entityItem->delete();

        return redirect()->route('shipment.index');
    }

    public function print(Request $request)
    {
        $shipment = Shipment::with('products.product', 'contact')->find($request->id);
        $totalSuma = $shipment->products->map(function ($product) {
            return $product->quantity * $product->price;
        })->sum();
        return view('print.print', compact('shipment', 'totalSuma'));
    }
}
