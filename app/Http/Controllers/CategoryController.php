<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $needMenuForItem = true;
        $urlEdit = "category.edit";
        $urlShow = "category.show";
        $urlDelete = "category.destroy";
        $urlCreate = "category.create";
        $urlFilter = 'category.filter';
        $entity = 'products_categories';

        if ($request->type == 'products') {
            $entityItems = Category::query()->where('type', Category::PRODUCTS)->orderByDesc('sort')->paginate(50);
        } else if ($request->type == 'materials') {
            $entity = 'products_categories_materials';
            $entityItems = Category::query()->where('type', Category::MATERIAL)->orderByDesc('sort')->paginate(50);
        } else {
            $entityItems = Category::query()->orderByDesc('sort')->paginate(50);
        }

        /* Колонки */
        $columns = Schema::getColumnListing('categories');
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
            'filters'
        ));
    }

    public function create()
    {
        $entityItem = new Category();
        $columns = Schema::getColumnListing('categories');

        $entity = 'products_categories';
        $action = "category.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        Category::create($request->post());
        return redirect()->route("categor.index");
    }

    public function show(string $id)
    {
        $entityItem = Category::findOrFail($id);
        $columns = Schema::getColumnListing('categories');

        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = Category::find($id);
        $columns = Schema::getColumnListing('categories');
        $entity = 'products_categories';

        if ($entityItem->type == Category::MATERIAL) {
            $entity = 'products_categories_materials';
        }

        $action = "category.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = Category::find($id);
        $entityItem->fill($request->post())->save();

        if ($entityItem->type == Category::MATERIAL) {
            $action = '/admin/categories?type=materials';
        } else if ($entityItem->type == Category::PRODUCTS) {
            $action = '/admin/products_categories?type=products';
        } else {
            $action = '/admin/categories';
        }

        return redirect()->to($action);
    }

    public function destroy(string $id)
    {
        $entityItem = Category::query()->find($id);
        $entityItem->delete();

        if ($entityItem->type == Category::MATERIAL) {
            $action = '/admin/categories?type=materials';
        } else if ($entityItem->type == Category::PRODUCTS) {
            $action = '/admin/products_categories?type=products';
        } else {
            $action = '/admin/categories';
        }

        return redirect()->to($action);
    }
    public function filter(FilterRequest $request)
    {
        $needMenuForItem = true;
        $urlEdit = "category.edit";
        $urlShow = "category.show";
        $urlDelete = "category.destroy";
        $urlCreate = "category.create";
        $urlFilter = 'category.filter';
        $urlReset = 'category.index';
        $entity = 'products_categories';

        if ($request->type == 'products') {
            $entity = 'products_categories';
            $entityItems = Category::query()->where('type', Category::PRODUCTS);
        } else if ($request->type == 'materials') {
            $entity = 'products_categories_materials';
            $entityItems = Category::query()->where('type', Category::MATERIAL);
        } else {
            $entity = 'products_categories';
            $entityItems = Category::query();
        }

        /* Колонки */
        $columns = Schema::getColumnListing('products_categories');
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
            $entityItems = Category::query()->select($requestColumns);
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
                } else if ($key == 'weight_kg' || $key == 'price') {
                    $entityItems
                        ->where($key, '>=', $value['min'])
                        ->where($key, '<=', $value['max']);
                }
            }
        }

        /* Сортировка */
        if (isset($request->orderBy)  && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column)->orderByDesc('sort')->paginate(50);
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column)->orderByDesc('sort')->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems =   $entityItems->paginate(50);
        }

        /* Фильтры для меню */
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
            'filters'
        ));
    }
}
