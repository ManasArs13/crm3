<?php

namespace App\Http\Controllers;

use App\Filters\ShipmentPositionFilter;
use App\Http\Requests\FilterRequest;
use App\Models\ShipmentProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ShipmentProductController extends Controller
{
    public function index(FilterRequest $request)
    {
        $urlEdit = "shipment_products.edit";
        $urlShow = "shipment_products.show";
        $urlDelete = "shipment_products.destroy";
        $urlCreate = "shipment_products.create";
        $urlFilter = 'shipment_products.index';
        $entity = 'shipment_products';
        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;

        // Shipment-product
        $builder = ShipmentProduct::query()->with('product');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ShipmentPositionFilter($builder, $request))->apply()->orderBy($request->column);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ShipmentPositionFilter($builder, $request))->apply()->orderByDesc($request->column);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ShipmentPositionFilter($builder, $request))->apply()->orderBy('id');
            $selectColumn = null;
        }

        // итоги в таблицах
        $totals = $this->total($entityItems);

        $entityItems = $entityItems->paginate(100);

        // Колонки
        $all_columns = [
            "id",
            "shipment_id",
            "product_id",
            "created_at",
            "updated_at",
            "ms_id",
            "price",
            "quantity",
            "deviation_price",
        ];

        $select = [
            "id",
            "shipment_id",
            "product_id",
            "price",
            "quantity",
            "deviation_price",
        ];

        $selected = $request->columns ?? $select;


        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        /* Фильтры для меню */
        $minCreated = ShipmentProduct::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = ShipmentProduct::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = ShipmentProduct::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = ShipmentProduct::query()->max('updated_at');
        $maxUpdatedCheck = '';

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

        return view("shipment.position", compact(
            'all_columns',
            'entityItems',
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
            'selectColumn',
            'totals'
        ));
    }

    public function create()
    {
        $entityItem = new ShipmentProduct();
        $columns = Schema::getColumnListing('shipment_products');


        $entity = 'shipment_products';
        $action = "shipmentproduct.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        ShipmentProduct::create($request->post());
        return redirect()->route("transportType.index");
    }

    public function show(string $id)
    {
        $entityItem = ShipmentProduct::findOrFail($id);
        $columns = Schema::getColumnListing('transport_types');
        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = ShipmentProduct::find($id);
        $columns = Schema::getColumnListing('transport_types');
        $entity = 'vehicle_types';
        $action = "transportType.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = ShipmentProduct::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('transportType.index');
    }

    public function destroy(string $id)
    {
        $entityItem = ShipmentProduct::find($id);
        $entityItem->delete();

        return redirect()->route('transportType.index');
    }

    public function filter(FilterRequest $request)
    {
        $needMenuForItem = true;
        $urlEdit = "transportType.edit";
        $urlShow = "transportType.show";
        $urlDelete = "transportType.destroy";
        $urlCreate = "transportType.create";
        $urlFilter = 'transportType.filter';
        $urlReset = 'transportType.index';
        $entity = 'vehicle_types';
        $selectColumn = $request->column;

        $entityItems = ShipmentProduct::query();

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
            $entityItems = $entityItems->orderByDesc('sort')->paginate(100);
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc('sort')->paginate(100);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems =  $entityItems->orderByDesc('sort')->paginate(100);
        }

        /* Фильтры для меню */
        $minCreated = ShipmentProduct::query()->min('created_at');
        $maxCreated = ShipmentProduct::query()->max('created_at');
        $minUpdated = ShipmentProduct::query()->min('updated_at');
        $maxUpdated = ShipmentProduct::query()->max('updated_at');

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

    public function total($entityItems){
        return [
            'total_price' => $entityItems->sum('price'),
        ];
    }
}
