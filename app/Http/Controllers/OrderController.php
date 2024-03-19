<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Contact;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function index()
    {
        $entityItems = Order::query()->paginate(50);
        $columns = Schema::getColumnListing('orders');

        $urlEdit = "order.edit";
        $urlShow = "order.show";
        $urlDelete = "order.destroy";
        $urlCreate = "order.create";
        $urlFilter = 'order.filter';
        $entity = 'orders';
        $needMenuForItem = true;

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
            'filters'
        ));
    }

    public function create()
    {
        $entity = 'order';
        $action = "order.store";

        $statuses = Status::all();
        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();
        $deliveries = Delivery::orderBy('name')->get();

        return view('order.create', compact('action', 'entity', 'statuses', 'contacts', 'deliveries'));
    }

    public function store(Request $request)
    {
        Order::create($request->post());
        return redirect()->route("order.index");
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
