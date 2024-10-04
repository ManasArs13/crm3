<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OptionController extends Controller
{
    public function index()
    {
        $entityItems = Option::query()->paginate(100);
        $columns = Schema::getColumnListing('options');

        $needMenuForItem = true;
        $urlEdit = "option.edit";
        $urlShow = "option.show";
        $urlDelete = "option.destroy";
        $urlCreate = "option.create";
        $urlFilter = 'option.filter';
        $entity = 'options';

        $resColumns = [];
        $resColumnsAll = [];
        $selectedColumns = [];

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        $filters = [];

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
            'filters'
        ));
    }

    public function create()
    {
        $entityItem = new Option();
        $columns = Schema::getColumnListing('options');

        $entity = 'options';
        $action = "option.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        Option::create($request->post());
        return redirect()->route("option.index");
    }

    public function show(string $id)
    {
        $entityItem = Option::findOrFail($id);
        $columns = Schema::getColumnListing('options');

        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = Option::find($id);
        $columns = Schema::getColumnListing('options');

        $entity = 'options';
        $action = "option.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = Option::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('option.index');
    }

    public function destroy(string $id)
    {
        $entityItem = Option::find($id);
        $entityItem->delete();

        return redirect()->route('option.index');
    }

    public function filter(FilterRequest $request)
    {
        $needMenuForItem = true;
        $urlEdit = "option.edit";
        $urlShow = "option.show";
        $urlDelete = "option.destroy";
        $urlCreate = "option.create";
        $urlFilter = 'option.filter';
        $urlReset = 'option.index';
        $entity = 'options';
        $selectColumn = $request->column;
        $entityItems = Option::query();

        /* Колонки */
        $columns = Schema::getColumnListing('options');
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

        if (isset($request->orderBy)  && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->getColumn())->paginate(100);
            $orderBy = 'desc';
        } elseif (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->getColumn())->paginate(100);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems =   $entityItems->paginate(100);
        }

        /* Фильтры для меню */
        $filters = [];

        return view("own.index", compact(
            'selectedColumns',
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
            'orderBy',
            'selectColumn'
        ));
    }
}
