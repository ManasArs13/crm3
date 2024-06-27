<?php

namespace App\Http\Controllers;

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




        return view("shipment.index", compact(
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
                'value'=> $queryShipment,
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

    public function store(Request $request)
    {
        $shipment = new Shipment();

        $shipment->name = 'CRM_' . $request->name;
        $shipment->status = $request->status;

        $shipment->paid_sum = 0;
        $shipment->suma = 0;
        $shipment->delivery_id = $request->delivery;
        $shipment->transport_id = $request->transport;
        $shipment->weight = $request->weight;
        $shipment->contact_id = $request->contact;

        if ($request->order_id) {
            $shipment->order_id = $request->order_id;
        }

        // if ($request->transport_type_id) {
        //     $shipment->tranport_type_id = $request->transport_type;
        // }

        if ($request->comment) {
            $shipment->description = $request->comment;
        }

        if ($request->address) {
            $shipment->shipment_address = $request->address;
        }

        $shipment->save();

        // Add shipment position
        if ($request->products) {
            foreach ($request->products as $product) {
                if (isset($product['product'])) {

                    $position = new ShipmentProduct();

                    $product_bd = Product::find($product['product']);

                    $position->product_id = $product_bd->id;
                    $position->shipment_id = $shipment->id;
                    $position->quantity = $product['count'];

                    $position->save();
                }
            }
        }

        return redirect()->route("shipment.index")->with('succes', 'Отгрузка №' . $shipment->id . ' добавлена');
    }

    public function show(string $id)
    {
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
        ));
    }

    // public function edit(string $id)
    // {
    //     $entityItem = Shipment::find($id);
    //     $columns = Schema::getColumnListing('shipments');
    //     $entity = 'shipments';
    //     $action = "shipment.update";

    //     return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    // }

    public function update(Request $request, string $id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return redirect()->route('order.index')->with('warning', 'Отгрузка ' . $id .  ' не найдена!');
        }

        $shipment->name = $request->name;
        $shipment->status = $request->status;

        $shipment->paid_sum = 0;
        $shipment->suma = 0;
        $shipment->delivery_id = $request->delivery;
        $shipment->transport_id = $request->transport;
        $shipment->weight = $request->weight;
        $shipment->contact_id = $request->contact;

        if ($request->order_id) {
            $shipment->order_id = $request->order_id;
        }

        // if ($request->transport_type_id) {
        //     $shipment->tranport_type_id = $request->tranport_type;
        // }

        if ($request->comment) {
            $shipment->description = $request->comment;
        }

        if ($request->address) {
            $shipment->shipment_address = $request->address;
        }

        $shipment->update();

        $shipment->products()->delete();

        // Add shipment position
        if ($request->products) {
            foreach ($request->products as $product) {
                if (isset($product['product'])) {

                    $position = new ShipmentProduct();

                    $product_bd = Product::find($product['product']);

                    $position->product_id = $product_bd->id;
                    $position->shipment_id = $shipment->id;
                    $position->quantity = $product['count'];

                    $position->save();
                }
            }
        }

        return redirect()->route("shipment.show", ['shipment' => $shipment->id])->with('success', 'Отгрузка №' . $shipment->id . ' обновлена');
    }

    public function destroy(string $id)
    {
        $entityItem = Shipment::find($id);
        $entityItem->delete();

        return redirect()->route('shipment.index');
    }

    public function getDebtors(){
        $shipments=Shipment::where('status',Shipment::NOT_PAID)
        ->selectRaw('contacts.name, contacts.ms_id, max(shipments.created_at) as moment, DATEDIFF(CURDATE(), max(shipments.created_at)) as days')
        ->join('contacts','shipments.contact_id','=','contacts.id')
        ->groupBy('contact_id')
        ->orderBy('days','asc')
        ->get();

        return view('shipment.debtors',compact('shipments'));
    }
}
