<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\TechProcess;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'welcome.index';
        $column = $request->column;

        $products = Product::query()
            ->where('residual_norm', '<>', null)
            ->where('type', Product::PRODUCTS)
            ->orWhere('type', Product::MATERIAL);

        /* Сортировка */
        if (isset($request->orderBy) && $request->orderBy == 'asc') {
            $products = $products->orderBy($column)->get();
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $products = $products->orderByDesc($column)->get();
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $products = $products->get();
        }

        return view("welcome", compact("entity", 'products', 'urlFilter', 'orderBy', 'column'));
    }

    public function blocksMaterials(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'welcome.blocksMaterials';
        $column = $request->column;

        $products = Product::query()
            ->where('residual_norm', '<>', null)
            ->where('type', Product::MATERIAL)
            ->where('building_material', Product::BLOCK);

        /* Сортировка */
        if (isset($request->orderBy) && $request->orderBy == 'asc') {
            $products = $products->orderBy($column)->get();
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $products = $products->orderByDesc($column)->get();
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $products = $products->get()->sortBy('sort');
        }

        return view("welcome", compact("entity", 'products', 'urlFilter', 'orderBy', 'column'));
    }

    public function blocksCategories(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'welcome.blocksCategories';
        $column = $request->column;

        $products = Category::query()
            ->where('building_material', Category::BLOCK)->get();

        foreach ($products as $product) {
            $product->residual =  Product::query()->where('type', Product::PRODUCTS)->where('category_id', $product->id)->get()->sum('residual');
            $product->residual_norm = Product::query()->where('type', Product::PRODUCTS)->where('category_id', $product->id)->get()->sum('residual_norm');
            $product->making_day = 0;

            $goods = Product::query()->where('type', Product::PRODUCTS)->where('category_id', $product->id)->get();


            foreach ($goods as $good) {
                if ($good->residual && $good->residual_norm && $good->release) {
                    if ($good->residual - $good->residual_norm < 0) {
                        $product->making_day += abs(($good->residual - $good->residual_norm) / $good->release);
                    }
                }
            }

            $product->making_day = round($product->making_day, 0);
        }

        $entity = 'residuals';

        return view("welcome", compact("entity", "products", 'urlFilter'));
    }

    public function blocksProducts(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'welcome.blocksProducts';
        $column = $request->column;

        $products = Product::query()
            ->where('residual_norm', '<>', null)
            ->where('type', Product::PRODUCTS)
            ->where('building_material', Product::BLOCK);

        if (isset($request->type) && $request->type == 'columns') {
            $products = $products->where('name', 'LIKE', '%Колонна%');
        } else if (isset($request->type) && $request->type == 'covers') {
            $products = $products->where('name', 'LIKE', '%Крышка на колонну%');
        } else if (isset($request->type) && $request->type == 'parapets') {
            $products = $products->where('name', 'LIKE', '%Парапет%');
        } else if (isset($request->type) && $request->type == 'blocks') {
            $products = $products->where('name', 'LIKE', '%Блок%');
        } else if (isset($request->type) && $request->type == 'dekors') {
            $products = $products->where('name', 'LIKE', '%Декор%');
        }

        /* Сортировка */
        if (isset($request->orderBy) && $request->orderBy == 'asc') {
            $products = $products->orderBy($column)->get();
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $products = $products->orderByDesc($column)->get();
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $products = $products->get()->sortBy('sort');
        }

        return view("welcome", compact("entity", "products", 'urlFilter', 'orderBy', 'column'));
    }

    public function concretesMaterials(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'welcome.concretesMaterials';
        $column = $request->column;

        $products = Product::query()
            ->where('residual_norm', '<>', null)
            ->where('building_material', Product::CONCRETE);

        /* Сортировка */
        if (isset($request->orderBy) && $request->orderBy == 'asc') {
            $products = $products->orderBy($column)->get();
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $products = $products->orderByDesc($column)->get();
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $products = $products->get()->sortBy('sort');
        }

        return view("welcome", compact("entity", "products", 'urlFilter', 'orderBy', 'column'));
    }

    public function paint(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'welcome.concretesMaterials';
        $column = $request->column;

        $products = Product::query()
            ->where('type', Product::MATERIAL)
            ->where('name', 'LIKE', '%Краска%');

        /* Сортировка */
        if (isset($request->orderBy) && $request->orderBy == 'asc') {
            $products = $products->orderBy($column)->get();
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $products = $products->orderByDesc($column)->get();
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $products = $products->get()->sortBy('sort');
        }

        return view("welcome", compact("entity", "products", 'urlFilter', 'orderBy', 'column'));
    }

    public function processing(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'welcome.processing';
        $column = $request->column;

        $products = TechProcess::with('tech_chart')
            ->where('moment', '<', Carbon::now()->setTime(0, 0)->subDays(5));
            
        /* Сортировка */
        if (isset($request->orderBy) && $request->orderBy == 'asc') {
            $products = $products->orderBy($column)->paginate(50);
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $products = $products->orderByDesc($column)->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $products = $products->orderBy('moment', 'desc')->paginate(50);
        }

        return view("welcome", compact("entity", "products", 'urlFilter', 'orderBy', 'column'));
    }
}
