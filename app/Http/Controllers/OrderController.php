<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Contact;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderPosition;
use App\Models\Product;
use App\Models\Status;
use App\Models\Transport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function index(FilterRequest $request)
    {
        $entityItems = Order::query();
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
        ];

        return view("own.index", compact(
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
        $entity = 'order';
        $action = "order.store";

        $statuses = Status::all();
        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();
        $deliveries = Delivery::orderBy('name')->get();
        $products = Product::select('id', 'name', 'price', 'residual', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->orderBy('name')
            ->get();
        $transports = Transport::orderBy('name')->get();
        $date = Carbon::now();

        return view(
            'order.create',
            compact(
                'action',
                'entity',
                'statuses',
                'contacts',
                'transports',
                'deliveries',
                'products',
                'date'
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
        $order->transport_id = $request->transport;
        $order->date_plan = $request->date;

        if($request->comment) {
            $order->comment = $request->comment;
        }

        $order->save();

        $sum = 0;
        $weight = 0;

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

        return redirect()->route("order.index")->with('succes', 'Заказ №' .$order->id. ' добавлен');
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
        $entityItems = Order::query();
        $columns = Schema::getColumnListing('orders');
        $resColumns = [];
        $resColumnsAll = [];

        /* Колонки для меню */
        foreach ($columns as $column) {
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
            $entityItems = Order::query()->select($requestColumns);
        }

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        /* Фильтры для отображения */
        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at' || $key == 'updated_at') {
                    $entityItems = Order::query()
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
        ];

        return view("own.index", compact(
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
}
