<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\OrderPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrderPositionController extends Controller
{
    public function index(FilterRequest $request)
    {
        $entityItems = OrderPosition::query();

        $needMenuForItem = true;
        $urlEdit = "order_positions.edit";
        $urlShow = "order_positions.show";
        $urlDelete = "order_positions.destroy";
        $urlCreate = "order_positions.create";
        $urlFilter = 'orderposition.filter';
        $entity = 'order_positions';
        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;

        // Колонки
        $columns = Schema::getColumnListing('order_positions');
        $resColumns = [];
        $resColumnsAll = [];

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

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        /* Фильтры для меню */
        $minCreated = OrderPosition::query()->min('created_at');
        $maxCreated = OrderPosition::query()->max('created_at');
        $minUpdated = OrderPosition::query()->min('updated_at');
        $maxUpdated = OrderPosition::query()->max('updated_at');

        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'max' => substr($maxCreated, 0, 10),
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
            "needMenuForItem",
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
        $entityItem = new OrderPosition();
        $columns = Schema::getColumnListing('order_positions');


        $entity = 'order_positions';
        $action = "orderPosition.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        OrderPosition::create($request->post());
        return redirect()->route("transportType.index");
    }

    public function show(string $id)
    {
        $entityItem = OrderPosition::findOrFail($id);
        $columns = Schema::getColumnListing('transport_types');
        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = OrderPosition::find($id);
        $columns = Schema::getColumnListing('transport_types');
        $entity = 'vehicle_types';
        $action = "transportType.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = OrderPosition::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('transportType.index');
    }

    public function destroy(string $id)
    {
        $entityItem = OrderPosition::find($id);
        $entityItem->delete();

        return redirect()->route('transportType.index');
    }

    public function filter(FilterRequest $request)
    {
        $needMenuForItem = true;
        $urlEdit = "order_positions.edit";
        $urlShow = "order_positions.show";
        $urlDelete = "order_positions.destroy";
        $urlCreate = "order_positions.create";
        $urlFilter = 'orderposition.filter';
        $entity = 'order_positions';
        $selectColumn = $request->column;

        $entityItems = OrderPosition::query();

        /* Колонки */
        $columns = Schema::getColumnListing('transport_types');
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
            $entityItems = $entityItems->select($requestColumns);
        }

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        /* Фильтры */
        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at' || $key == 'updated_at') {
                    $entityItems
                        ->where($key, '>=', $value['min'] . ' 00:00:00')
                        ->where($key, '<=', $value['max'] . ' 23:59:59');
                }
            }
        }

        /* Сортировка */
        if (isset($request->orderBy)  && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderByDesc('sort')->paginate(50);
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc('sort')->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems =  $entityItems->orderByDesc('sort')->paginate(50);
        }

        /* Фильтры для меню */
        $minCreated = OrderPosition::query()->min('created_at');
        $maxCreated = OrderPosition::query()->max('created_at');
        $minUpdated = OrderPosition::query()->min('updated_at');
        $maxUpdated = OrderPosition::query()->max('updated_at');

        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'max' => substr($maxCreated, 0, 10),
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
            'filters',
            "needMenuForItem",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entity",
            'urlFilter',
            'urlReset',
            'orderBy'
        ));
    }
}
