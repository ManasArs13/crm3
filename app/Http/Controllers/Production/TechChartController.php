<?php

namespace App\Http\Controllers\Production;

use App\Models\TechChart;
use App\Http\Controllers\Controller;
use App\Models\TechChartMaterial;
use App\Models\TechChartProduct;
use Illuminate\Http\Request;

class TechChartController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:techchart')->only(['index', 'show', 'products', 'materials']);
    }

    public function index()
    {
        $needMenuForItem = true;
        $entity = 'techcharts';

        $techcharts = TechChart::get();

        return view('production.techchart.index', compact("needMenuForItem", "entity", 'techcharts'));
    }

    public function show(Request $request, $techchart)
    {
        $needMenuForItem = true;
        $entity = 'techchart';

        $tech_chart = TechChart::with('materials', 'products')->find($techchart);

        return view('production.techchart.show', compact("needMenuForItem", "entity", 'tech_chart'));
    }

    public function products(Request $request)
    {
        $needMenuForItem = true;
        $entity = 'techcharts';

        $tech_chart_products = TechChartProduct::with('tech_chart', 'products');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $tech_chart_products = $tech_chart_products->orderBy($request->column);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $tech_chart_products = $tech_chart_products->orderByDesc($request->column);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $tech_chart_products = $tech_chart_products->orderBy('created_at', 'desc');
            $selectColumn = null;
        }
        $tech_chart_products = $tech_chart_products->paginate(100);

        $selectedColumns = [
            'id',
            'tech_chart_id',
            'product_id',
            'quantity',
            'created_at',
            'updated_at'
        ];

        return view('production.techchart.products', compact("needMenuForItem", "entity", 'tech_chart_products', 'selectedColumns', 'selectColumn', 'orderBy'));
    }

    public function materials(Request $request)
    {
        $needMenuForItem = true;
        $entity = 'techcharts';

        $tech_chart_materials = TechChartMaterial::with('tech_chart', 'products');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $tech_chart_materials = $tech_chart_materials->orderBy($request->column);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $tech_chart_materials = $tech_chart_materials->orderByDesc($request->column);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $tech_chart_materials = $tech_chart_materials->orderBy('created_at', 'desc');
            $selectColumn = null;
        }

        $tech_chart_materials = $tech_chart_materials->paginate(100);

        $selectedColumns = [
            'id',
            'tech_chart_id',
            'product_id',
            'quantity',
            'created_at',
            'updated_at'
        ];

        return view('production.techchart.materials', compact("needMenuForItem", "entity", 'tech_chart_materials', 'orderBy', 'selectColumn', 'selectedColumns'));
    }
}
