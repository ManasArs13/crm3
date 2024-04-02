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
        $entityItems = Order::query()->with('contact');
        $columns = Schema::getColumnListing('orders');

        $urlEdit = "order.edit";
        $urlShow = "order.show";
        $urlDelete = "order.destroy";
        $urlCreate = "order.create";
        $urlFilter = 'order.filter';
        $entity = 'orders';
        $needMenuForItem = true;
        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;

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
        $resColumnsAll = [];

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        $minCreated = Order::query()->min('created_at');
        $maxCreated = Order::query()->max('created_at');
        $minUpdated = Order::query()->min('updated_at');
        $maxUpdated = Order::query()->max('updated_at');

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
            'selectColumn'
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
        if($request->shipment_need) {

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

        $orderBy  = $request->orderBy;
        $entityItems = Order::query()->with('contact');
        $columns = Schema::getColumnListing('orders');
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
        if ($request->filters['material'] == '1') {
            $entityItems = $entityItems
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                });
                $material = '1';
        } else if ($request->filters['material'] == '0') {

            $entityItems = $entityItems
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                });
                $material = '0';
        } else {
            $entityItems = $entityItems;
            $material = 'all';
        } 

        /* Фильтры для отображения */
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
            'selectColumn'
        ));
    }

    public function get_api(Request $request)
    {
        $orders = Order::query()
                ->where('name', 'LIKE', '%' .$request->query('term'). '%')
                ->orWhere('id', 'LIKE',  '%' .$request->query('term'). '%')
                ->orWhere('date_plan', 'LIKE',  '%' .$request->query('term'). '%')
                ->orWhere('date_moment', 'LIKE',  '%' .$request->query('term'). '%')
                ->latest()->take(5)->get();

        return response()->json($orders);
    }   
}
