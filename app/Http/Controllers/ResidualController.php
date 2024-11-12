<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Category;
use App\Models\OrderPosition;
use App\Models\Product;
use App\Models\TechChartMaterial;
use App\Models\TechChartProduct;
use App\Models\TechProcess;
use Carbon\Carbon;

class ResidualController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:residual')->only(['blocksMaterials', 'blocksCategories', 'blocksProducts', 'concretesMaterials']);
    }

    public function index(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'residual.index';
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

        return view("residual.index", compact("entity", 'products', 'urlFilter', 'orderBy', 'column'));
    }

    public function blocksMaterials(FilterRequest $request)
    {

        $entity = 'residuals';
        $urlFilter = 'residual.concretesMaterials';
        $column = $request->column;

        $products = Product::query()
            ->where('residual_norm', '<>', null)
            ->where('building_material', Product::CONCRETE)->get();

        $products2 = Product::query()
            ->where('residual_norm', '<>', null)
            ->where('type', Product::MATERIAL)
            ->where('building_material', Product::BLOCK)->get();

        $orderBy = 'desc';

        return view("residual.materials", compact("entity", "products", "products2", "urlFilter", 'orderBy', 'column'));

//        $urlFilter = 'residual.blocksMaterials';



    }

    public function blocksCategories(FilterRequest $request)
    {
        $urlFilter = 'residual.blocksCategories';

        $products = Category::query()
            ->where('building_material', Category::BLOCK)->orwhere('building_material', Category::CONCRETE)->get();

        foreach ($products as $product) {
            $product->residual =  Product::query()->where('type', Product::PRODUCTS)->where('category_id', $product->id)->get()->sum('residual');
            $product->residual_norm = Product::query()->where('type', Product::PRODUCTS)->where('category_id', $product->id)->get()->sum('residual_norm');
            $product->pre_products = Product::query()->where('type', Product::PRODUCTS)->where('category_id', $product->id)->get();
            $product->making_day = 0;

            $goods = Product::query()->where('type', Product::PRODUCTS)->where('category_id', $product->id)->get();

            $product->totalOrderQuantity = OrderPosition::whereIn('product_id', $goods->pluck('id'))->sum('quantity');
            $product->totalOrderSum = OrderPosition::whereIn('product_id', $goods->pluck('id'))->distinct('order_id')->count('order_id');

            foreach ($goods as $good) {
                if ($good->residual && $good->residual_norm && $good->release) {
                    if ($good->residual - $good->residual_norm < 0) {
                        $product->making_day += abs(($good->residual - $good->residual_norm) / $good->release);
                    }
                }
            }

            $product->making_day = round($product->making_day, 0);

            $preProductIds = $product->pre_products->pluck('id');

            $preProductOrders = OrderPosition::whereIn('product_id', $preProductIds)
                ->selectRaw('product_id, SUM(quantity) as totalOrderQuantity')
                ->selectRaw('COUNT(DISTINCT order_id) as totalOrderSum')
                ->groupBy('product_id')
                ->get()
                ->keyBy('product_id');

            foreach ($product->pre_products as $preProduct) {
                $preProduct->totalOrderQuantity = $preProductOrders[$preProduct->id]->totalOrderQuantity ?? 0;
                $preProduct->totalOrderSum = $preProductOrders[$preProduct->id]->totalOrderSum ?? 0;
            }
        }

        $entity = 'residuals';

        return view("residual.categories", compact("entity", "products", 'urlFilter'));
    }

    public function blocksProducts(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'residual.blocksProducts';
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

        return view("residual.index", compact("entity", "products", 'urlFilter', 'orderBy', 'column'));
    }

    public function concretesMaterials(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'residual.concretesMaterials';
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

        return view("residual.index", compact("entity", "products", 'urlFilter', 'orderBy', 'column'));
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

        $goods = Product::query()
            ->where('residual_norm', '<>', null)
            ->where('type', Product::PRODUCTS)
            ->where('building_material', Product::BLOCK)
            ->get();

        foreach ($products as $product) {
            $product->setAttribute('need_from_tc', 0);

            foreach ($goods as $good) {

                if ($good->residual && $good->residual_norm) {
                    if ($good->residual - $good->residual_norm < 0) {

                        $tech_chart_product = TechChartProduct::where('product_id', '=', $good->id)->first();

                        if ($tech_chart_product) {

                            $tech_chart_material =
                                TechChartMaterial::where('tech_chart_id', '=', $tech_chart_product->tech_chart_id)
                                ->where('product_id', '=', $product->id)
                                ->First();

                            if ($tech_chart_material) {
                                $product->need_from_tc += $tech_chart_material->quantity * abs($good->residual - $good->residual_norm);
                            }
                        }
                    }
                }
            }
        }

        return view("residual.index", compact("entity", "products", 'urlFilter', 'orderBy', 'column'));
    }

    public function processing(FilterRequest $request)
    {
        $entity = 'residuals';
        $urlFilter = 'welcome.processing';
        $column = $request->column;

        $products = TechProcess::with('tech_chart')
            ->whereDate('moment', '>=', Carbon::now()->subDays(5));

        /* Сортировка */
        if (isset($request->orderBy) && $request->orderBy == 'asc') {
            $products = $products->orderBy($column)->paginate(100);
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $products = $products->orderByDesc($column)->paginate(100);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $products = $products->orderBy('moment', 'desc')->paginate(100);
        }

        return view("residual.index", compact("entity", "products", 'urlFilter', 'orderBy', 'column'));
    }
}
