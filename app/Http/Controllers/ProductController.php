<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $needMenuForItem = true;
        $urlEdit = "product.edit";
        $urlShow = "product.show";
        $urlDelete = "product.destroy";
        $urlCreate = "product.create";
        $urlFilter = 'product.filter';
        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;

        if ($request->type == 'products') {
            $entity = 'products';
            $entityItems = Product::query()->where('type', Product::PRODUCTS);
        } else if ($request->type == 'materials') {
            $entity = 'materials';
            $entityItems = Product::query()->where('type', Product::MATERIAL);
        } else {
            $entity = 'products';
            $entityItems = Product::query();
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
            $entityItems = $entityItems->orderByDesc('sort')->paginate(50);
        }

        /* Колонки */
        $columns = Schema::getColumnListing('products');
        //    $columns[] = 'remainder';
        $resColumns = [];
        $resColumnsAll = [];

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        /*  Фильтры */
        $minCreated = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->min('created_at');
        $maxCreated = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->max('created_at');
        $minUpdated = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->min('updated_at');
        $maxUpdated = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->max('updated_at');
        $minWeight = Product::query()->where('type',  $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->min('weight_kg');
        $maxWeigth = Product::query()->where('type',  $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->max('weight_kg');
        $categories = Product::query()
            ->where('products.type',  $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)
            ->groupBy('category_id')
            ->select('category_id', 'categories.name', 'products.category_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->get();
        $minPrices = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->min('price');
        $maxPrices = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->max('price');

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
            [
                'type' => 'number',
                'name' =>  'weight_kg',
                'name_rus' => 'Вес',
                'min' => $minWeight,
                'max' => $maxWeigth
            ],
            [
                'type' => 'select',
                'name' => 'category_id',
                'name_rus' => 'Категория',
                'values' => $categories,
                'checked_value' => 'all',
            ],
            [
                'type' => 'number',
                'name' =>  'price',
                'name_rus' => 'Цена',
                'min' => $minPrices,
                'max' => $maxPrices
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
        $entityItem = new Product();
        $columns = Schema::getColumnListing('products');


        $entity = 'products';
        $action = "product.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        Product::query()->create($request->post());

        return redirect()->route("products.index");
    }

    public function show(string $id)
    {
        $entityItem = Product::findOrFail($id);
        $columns = Schema::getColumnListing('products');

        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = Product::find($id);
        $columns = Schema::getColumnListing('products');
        $entity = 'products';

        if ($entityItem->type == Product::MATERIAL) {
            $entity = 'materials';
        }

        $action = "product.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = Product::find($id);
        $entityItem->fill($request->post())->save();
        $action = '/admin/product?type=products';

        if ($entityItem->type == Product::MATERIAL) {
            $action = '/admin/product?type=materials';
        }
        
        return redirect()->to($action);
    }

    public function destroy(string $id)
    {
        $entityItem = Product::query()->find($id);
        $entityItem->delete();
        $action = '/admin/product?type=products';

        if ($entityItem->type == Product::MATERIAL) {
            $action = '/admin/product?type=materials';
        }
        
        return redirect()->to($action);
    }

    public function filter(FilterRequest $request)
    {
        $needMenuForItem = true;
        $urlEdit = "product.edit";
        $urlShow = "product.show";
        $urlDelete = "product.destroy";
        $urlCreate = "product.create";
        $urlFilter = 'product.filter';
        $urlReset = 'product.index';
        $selectColumn = $request->column;

        if ($request->type == 'products') {
            $entity = 'products';
            $entityItems = Product::query()->where('type', Product::PRODUCTS);
        } else if ($request->type == 'materials') {
            $entity = 'materials';
            $entityItems = Product::query()->where('type', Product::MATERIAL);
        } else {
            $entity = 'products';
            $entityItems = Product::query();
        }

        /* Колонки */
        $columns = Schema::getColumnListing('products');
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
                            ->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)
                            ->where($key, $value);
                    } else {
                        $entityItems
                            ->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS);
                    }
                    $categoryFilterValue = $value;
                } else if ($key == 'created_at' || $key == 'updated_at') {
                    $entityItems
                        ->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)
                        ->where($key, '>=', $value['min'] . ' 00:00:00')
                        ->where($key, '<=', $value['max'] . ' 23:59:59');
                } else if ($key == 'weight_kg' || $key == 'price') {
                    $entityItems
                        ->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)
                        ->where($key, '>=', $value['min'])
                        ->where($key, '<=', $value['max']);
                } else {
                    $entityItems
                        ->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS);
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
            $entityItems =  $entityItems->orderBy('sort')->paginate(50);
        }

        /* Фильтры для меню */
        $minCreated = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->min('created_at');
        $maxCreated = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->max('created_at');
        $minUpdated = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->min('updated_at');
        $maxUpdated = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->max('updated_at');
        $minWeight = Product::query()->where('type',  $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->min('weight_kg');
        $maxWeigth = Product::query()->where('type',  $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->max('weight_kg');
        $categories = Product::query()
            ->where('products.type',  $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)
            ->groupBy('category_id')
            ->select('category_id', 'categories.name', 'products.category_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->get();
        $minPrices = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->min('price');
        $maxPrices = Product::query()->where('type', $request->type == 'materials' ? Product::MATERIAL : Product::PRODUCTS)->max('price');

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
            [
                'type' => 'number',
                'name' =>  'weight_kg',
                'name_rus' => 'Вес',
                'min' => $minWeight,
                'max' => $maxWeigth
            ],
            [
                'type' => 'select',
                'name' => 'category_id',
                'name_rus' => 'Категория',
                'values' => $categories,
                'checked_value' => $categoryFilterValue,
            ],
            [
                'type' => 'number',
                'name' =>  'price',
                'name_rus' => 'Цена',
                'min' => $minPrices,
                'max' => $maxPrices
            ],
        ];

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
            'orderBy',
            'selectColumn'
         ));
    }
}
