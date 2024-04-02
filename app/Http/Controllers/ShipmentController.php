<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentProduct;
use App\Models\Transport;
use App\Models\TransportType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ShipmentController extends Controller
{
    public function index(FilterRequest $request)
    {
        $entityItems = Shipment::query()->with('order.contact');
        $columns = Schema::getColumnListing('shipments');
        $needMenuForItem = true;
        $urlEdit = "shipment.edit";
        $urlShow = "shipment.show";
        $urlDelete = "shipment.destroy";
        $urlCreate = "shipment.create";
        $urlFilter = 'shipment.filter';
        $entity = 'shipments';
        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;

        /* Сортировка */
        if (isset($request->orderBy)  && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
        } elseif (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems = $entityItems->paginate(50);
        }

        $resColumns = [];
        $resColumnsAll = [];

        foreach ($columns as $column) {
            if ($column == 'name') {
                $resColumns[$column] = trans("column." . $column);
                $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];

                $resColumns['contact_id'] = trans("column." . 'contact_id');
                //   $resColumnsAll['contact_id'] = ['name_rus' => trans("column." . 'contact_id'), 'checked' => false];
            }
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        $minCreated = Shipment::query()->min('created_at');
        $maxCreated = Shipment::query()->max('created_at');
        $minUpdated = Shipment::query()->min('updated_at');
        $maxUpdated = Shipment::query()->max('updated_at');

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
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => [['id' => 0, 'name' => 'Блок'], ['id' => 1, 'name' => 'Бетон']],
                'checked_value' => 'all',
            ],
        ];

        return view("shipment.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "needMenuForItem",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entity",
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
        $date = Carbon::now()->format('Y-m-d');
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $products = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
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
            'date',
            'dateNow',
            'order',
            'products',
            'positions'
        ));
    }

    public function createWithOrder(Request $request)
    {
        if ($request->order_id == null) {
            return response()->redirectToRoute('shipment.create');
        }

        $order = Order::select('id', 'name')->with('positions')->find($request->order_id);

        if ($order == null) {
            return response()->redirectToRoute('shipment.create');
        }

        $entity = 'new shipment';
        $action = "shipment.store";
        $actionWithOrder = "shipment.createWithOrder";
        $searchOrders = "api.get.order";

        $deliveries = Delivery::orderBy('name')->get();
        $transports = Transport::orderBy('name')->get();
        $date = Carbon::now()->format('Y-m-d');
        $dateNow = Carbon::now()->format('Y-m-d H:i:s');
        $products = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
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
            'date',
            'dateNow',
            'order',
            'products',
            'positions'
        ));
    }

    public function store(Request $request)
    {
        $shipment = new Shipment();

        $shipment->name = 'CRM_' .$request->name;
        $shipment->status = $request->status;
        
        $shipment->paid_sum = 0;
        $shipment->suma = 0;
        $shipment->delivery_id = $request->delivery;
        $shipment->transport_id = $request->transport;
        $shipment->weight = $request->weight;

        if ($request->order_id) {
            $shipment->order_id = $request->order_id;
        }

        if ($request->transport_type_id) {
            $shipment->tranport_type_id = $request->tranport_type;
        }

        if ($request->comment) {
            $shipment->description = $request->comment;
        }

        if ($request->address) {
            $shipment->shipment_address = $request->address;
        }

        $shipment->save();

        // Add shipment position
        foreach ($request->products as $product) {

            $position = new ShipmentProduct();

            $product_bd = Product::find($product['product']);

            $position->product_id = $product_bd->id;
            $position->shipment_id = $shipment->id;
            $position->quantity = $product['count'];

            $position->save();
        }

        return redirect()->route("shipment.index")->with('succes', 'Отгрузка №' . $shipment->id . ' добавлена');
    }

    public function show(string $id)
    {
        $entityItem = Shipment::findOrFail($id);
        $columns = Schema::getColumnListing('shipments');

        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = Shipment::find($id);
        $columns = Schema::getColumnListing('shipments');
        $entity = 'shipments';
        $action = "shipment.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = Shipment::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('shipments.index');
    }

    public function destroy(string $id)
    {
        $entityItem = Shipment::find($id);
        $entityItem->delete();

        return redirect()->route('shipments.index');
    }

    public function filter(FilterRequest $request)
    {
        $needMenuForItem = true;
        $urlEdit = "shipment.edit";
        $urlShow = "shipment.show";
        $urlDelete = "shipment.destroy";
        $urlCreate = "shipment.create";
        $urlFilter = 'shipment.filter';
        $urlReset = 'shipment.index';
        $entity = 'shipments';

        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;
        $entityItems = Shipment::query()->with('order.contact');
        $columns = Schema::getColumnListing('shipments');
        $resColumns = [];
        $resColumnsAll = [];

        /* Колонки для меню */
        foreach ($columns as $column) {
            if ($column == 'name') {
                $resColumnsAll[$column] = [
                    'name_rus' => trans("column." . $column),
                    'checked' => in_array($column, $request->columns ? $request->columns : []) ? true : false
                ];

                // $resColumnsAll['contact_id'] = [
                //     'name_rus' => trans("column." . 'contact_id'),
                //     'checked' => in_array('contact_id', $request->columns ? $request->columns : []) ? true : false
                // ];
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
            if ($column == 'name') {
                $resColumns[$column] = trans("column." . $column);
                $resColumns['contact_id'] = trans("column." . 'contact_id');
            }
            $resColumns[$column] = trans("column." . $column);
        }

        /* Фильтры для отображения */
        if ($request->filters['material'] == '1') {
            $entityItems = $entityItems
                ->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                });
            $material = '1';
        } else if ($request->filters['material'] == '0') {

            $entityItems = $entityItems
                ->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                });
            $material = '0';
        } else {
            $entityItems = $entityItems;
            $material = 'all';
        }

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at' || $key == 'updated_at') {
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
        } elseif (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems = $entityItems->paginate(50);
        }

        $minCreated = Shipment::query()->min('created_at');
        $maxCreated = Shipment::query()->max('created_at');
        $minUpdated = Shipment::query()->min('updated_at');
        $maxUpdated = Shipment::query()->max('updated_at');

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
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => [['id' => 0, 'name' => 'Блок'], ['id' => 1, 'name' => 'Бетон']],
                'checked_value' =>  $material,
            ],
        ];

        return view("shipment.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "needMenuForItem",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entity",
            'urlFilter',
            'urlReset',
            'orderBy',
            'filters',
            'selectColumn'
        ));
    }
}
