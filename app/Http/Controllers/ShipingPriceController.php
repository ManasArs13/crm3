<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\ShipingPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ShipingPriceController extends Controller
{
    public function index(Request $request)
    {
        $entityItems = ShipingPrice::query();
        $columns = Schema::getColumnListing('shiping_prices');
        $needMenuForItem = true;
        $urlEdit = "shiping_price.edit";
        $urlShow = "shiping_price.show";
        $urlDelete = "shiping_price.destroy";
        $urlCreate = "shiping_price.create";
        $urlFilter = 'shiping_price.filter';
        $entity = 'shiping_prices';
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

        $filters = [];

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
        $entityItem = new ShipingPrice();
        $columns = Schema::getColumnListing('shiping_prices');


        $entity = 'shiping_prices';
        $action = "shiping_price.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        ShipingPrice::create($request->post());
        return redirect()->route("shiping_price.index");
    }

    public function show(string $id)
    {
        $entityItem = ShipingPrice::findOrFail($id);
        $columns = Schema::getColumnListing('shiping_prices');

        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = ShipingPrice::find($id);
        $columns = Schema::getColumnListing('shiping_prices');
        $entity = 'shiping_prices';
        $action = "shiping_price.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = ShipingPrice::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('shiping_price.index');
    }

    public function destroy(string $id)
    {
        $entityItem = ShipingPrice::find($id);
        $entityItem->delete();

        return redirect()->route('shiping_price.index');
    }

    public function filter(FilterRequest $request)
    {
        $orderBy  = $request->orderBy;
        $needMenuForItem = true;
        $urlEdit = "shiping_price.edit";
        $urlShow = "shiping_price.show";
        $urlDelete = "shiping_price.destroy";
        $urlCreate = "shiping_price.create";
        $urlFilter = 'shiping_price.filter';
        $urlReset = 'shiping_price.index';
        $entity = 'shiping_prices';
        $selectColumn = $request->column;
        $entityItems = ShipingPrice::query();

        /* Колонки */
        $columns = Schema::getColumnListing('shiping_prices');
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
