<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $entityItems = Delivery::query();
        $needMenuForItem = true;
        $urlEdit = "delivery.edit";
        $urlShow = "delivery.show";
        $urlDelete = "delivery.destroy";
        $urlCreate = "delivery.create";
        $urlFilter = 'delivery.filter';
        $entity = 'deliveries';
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

        /* Колонки */
        $columns = Schema::getColumnListing('deliveries');
        $selectedColumns = [];
        $resColumns = [];
        $resColumnsAll = [];

        /* Колонки для меню */
        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        /*  Фильтры */
        $minCreated = Delivery::query()->min('created_at');
        $maxCreated = Delivery::query()->max('created_at');
        $minUpdated = Delivery::query()->min('updated_at');
        $maxUpdated = Delivery::query()->max('updated_at');

        list($minCreatedCheck, $maxCreatedCheck, $minUpdatedCheck, $maxUpdatedCheck) = '';

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
                'maxChecked' => $maxCreatedCheck,
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => 'Дата обновления',
                'min' => substr($minUpdated, 0, 10),
                'minChecked' => $minUpdatedCheck,
                'max' => substr($maxUpdated, 0, 10),
                'maxChecked' => $maxUpdatedCheck,
            ],
        ];

        return view("own.index", compact(
            'selectedColumns',
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
        $entityItem = new Delivery();
        $columns = Schema::getColumnListing('deliveries');


        $entity = 'delivery';
        $action = "delivery.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        Delivery::create($request->post());
        return redirect()->route("delivery.index");
    }

    public function show(string $id)
    {
        $entityItem = Delivery::findOrFail($id);
        $columns = Schema::getColumnListing('deliveries');

        return view("own.show", compact('entityItem', "columns"));
    }


    public function edit(string $id)
    {
        $entityItem = Delivery::find($id);
        $columns = Schema::getColumnListing('deliveries');

        $entity = 'delivery';
        $action = "delivery.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = Delivery::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('delivery.index');
    }

    public function destroy(string $id)
    {
        $entityItem = Delivery::find($id);
        $entityItem->delete();

        return redirect()->route('delivery.index');
    }
    public function filter(FilterRequest $request)
    {
        $needMenuForItem = true;
        $urlEdit = "delivery.edit";
        $urlShow = "delivery.show";
        $urlDelete = "delivery.destroy";
        $urlCreate = "delivery.create";
        $urlFilter = 'delivery.filter';
        $urlReset = 'delivery.index';
        $entity = 'deliveries';
        $selectColumn = $request->column;
        $entityItems = Delivery::query();

        /* Колонки */
        $columns = Schema::getColumnListing('deliveries');
        $selectedColumns = [];
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

        /* Фильтры для отображения */
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
        if (isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems =  $entityItems->orderBy('id')->paginate(50);
        }

         /*  Фильтры */
         $minCreated = Delivery::query()->min('created_at');
         $maxCreated = Delivery::query()->max('created_at');
         $minUpdated = Delivery::query()->min('updated_at');
         $maxUpdated = Delivery::query()->max('updated_at');

        list($minCreatedCheck, $maxCreatedCheck, $minUpdatedCheck, $maxUpdatedCheck) = '';

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
         ];

        return view("own.index", compact(
            'selectedColumns',
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
