<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TransportController extends Controller
{
    public function index()
    {
        $entityItems = Transport::query()->paginate(50);
        $columns = Schema::getColumnListing('transports');
        $needMenuForItem = true;
        $urlEdit = "transport.edit";
        $urlShow = "transport.show";
        $urlDelete = "transport.destroy";
        $urlCreate = "transport.create";
        $urlFilter = 'transport.filter';
        $entity = 'transports';

        $resColumns = [];
        $resColumnsAll = [];

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        $filters = [];

        return view("own.index", compact(
            'entityItems',
            'filters',
            "resColumns",
            "resColumnsAll",
            "needMenuForItem",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entity",
            'urlFilter'
        ));
    }

    public function create()
    {
        $entityItem = new Transport();
        $columns = Schema::getColumnListing('transports');


        $entity = 'transports';
        $action = "transport.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        Transport::create($request->post());
        return redirect()->route("transport.index");
    }

    public function show(string $id)
    {
        $entityItem = Transport::findOrFail($id);
        $columns = Schema::getColumnListing('transports');
        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = Transport::find($id);
        $columns = Schema::getColumnListing('transports');
        $entity = 'transports';
        $action = "transport.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = Transport::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('transport.index');
    }

    public function destroy(string $id)
    {
        $entityItem = Transport::find($id);
        $entityItem->delete();

        return redirect()->route('transports.index');
    }
    public function filter(FilterRequest $request)
    {
        $needMenuForItem = true;
        $urlEdit = "transport.edit";
        $urlShow = "transport.show";
        $urlDelete = "transport.destroy";
        $urlCreate = "transport.create";
        $urlFilter = 'transport.filter';
        $urlReset = 'transport.index';
        $entity = 'transports';
        $entityItems = Transport::query();
        $orderBy  = $request->orderBy;

        /* Колонки */
        $columns = Schema::getColumnListing('transports');
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
        $categoryFilterValue = 'all';

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'category_id') {
                    if ($value !== 'all') {
                        $entityItems
                            ->where($key, $value);
                    }
                    $categoryFilterValue = $value;
                } else if ($key == 'created_at' || $key == 'updated_at') {
                    $entityItems
                        ->where($key, '>=', $value['min'] . ' 00:00:00')
                        ->where($key, '<=', $value['max'] . ' 23:59:59');
                } else if ($key == 'weight_kg' || $key == 'price') {
                    $entityItems
                        ->where($key, '>=', $value['min'])
                        ->where($key, '<=', $value['max']);
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
            $entityItems =   $entityItems->paginate(50);
        }
        

        $filters = [];

        return view("own.index", compact(
            'entityItems',
            'filters',
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
            'orderBy'
        ));
    }
}
