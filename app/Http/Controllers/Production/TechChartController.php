<?php

namespace App\Http\Controllers\Production;

use App\Models\TechChart;
use App\Http\Controllers\Controller;
use App\Models\TechChartMaterial;
use App\Models\TechChartProduct;
use Illuminate\Http\Request;

class TechChartController extends Controller
{
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

        $tech_chart_products = TechChartProduct::with('tech_chart', 'products')
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(100);

        return view('production.techchart.products', compact("needMenuForItem", "entity", 'tech_chart_products'));
    }

    public function materials(Request $request)
    {
        $needMenuForItem = true;
        $entity = 'techcharts';

        $tech_chart_materials = TechChartMaterial::with('tech_chart', 'products')
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(100);

        return view('production.techchart.materials', compact("needMenuForItem", "entity", 'tech_chart_materials'));
    }
}
