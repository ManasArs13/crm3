<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\TransportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TransportTypeController extends Controller
{
    public function index()
    {
        $entityItems = TransportType::query()->paginate(50);

        $needMenuForItem = true;
        $urlEdit = "transportType.edit";
        $urlShow = "transportType.show";
        $urlDelete = "transportType.destroy";
        $urlCreate = "transportType.create";
        $urlFilter = 'transportType.filter';
        $entity = 'vehicle_types';

        // Колонки
        $columns = Schema::getColumnListing('transport_types');
        $resColumns = [];
        $resColumnsAll = [];

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        /* Фильтры для меню */
        $minCreated = TransportType::query()->min('created_at');
        $maxCreated = TransportType::query()->max('created_at');
        $minUpdated = TransportType::query()->min('updated_at');
        $maxUpdated = TransportType::query()->max('updated_at');

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
            'filters'
        ));
    }

    public function create()
    {
        $entityItem = new TransportType();
        $columns = Schema::getColumnListing('transport_types');


        $entity = 'vehicle_types';
        $action = "transportType.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        TransportType::create($request->post());
        return redirect()->route("transportType.index");
    }

    public function show(string $id)
    {
        $entityItem = TransportType::findOrFail($id);
        $columns = Schema::getColumnListing('transport_types');
        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = TransportType::find($id);
        $columns = Schema::getColumnListing('transport_types');
        $entity = 'vehicle_types';
        $action = "transportType.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = TransportType::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('transportType.index');
    }

    public function destroy(string $id)
    {
        $entityItem = TransportType::find($id);
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

        $entityItems = TransportType::query();

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
        $minCreated = TransportType::query()->min('created_at');
        $maxCreated = TransportType::query()->max('created_at');
        $minUpdated = TransportType::query()->min('updated_at');
        $maxUpdated = TransportType::query()->max('updated_at');

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
