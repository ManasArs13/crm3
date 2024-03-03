<?php

namespace App\Http\Controllers\Production;

use App\Http\Controllers\Controller;
use App\Models\TechProcess;
use App\Models\TechProcessMaterial;
use App\Models\TechProcessProduct;
use Illuminate\Http\Request;

class ProcessingController extends Controller
{
    public function index()
    {
        $needMenuForItem = true;
        $entity = 'processings';

        $processings = TechProcess::with('tech_chart')->orderBy('moment', 'desc')->paginate(100);

        return view('production.processing.index', compact("needMenuForItem", "entity", 'processings'));
    }

    public function show(Request $request, $processing)
    {
        $needMenuForItem = true;
        $entity = 'processing';

        $processing = TechProcess::with('materials', 'products')->find($processing);

        return view('production.processing.show', compact("needMenuForItem", "entity", 'processing'));
    }

    public function products(Request $request)
    {
        $needMenuForItem = true;
        $entity = 'processings';

        $processing_products = TechProcessProduct::with('processing', 'product')
                                                ->orderBy('created_at', 'desc')
                                                ->paginate(100);

        return view('production.processing.products', compact("needMenuForItem", "entity", 'processing_products'));
    }

    public function materials(Request $request)
    {
        $needMenuForItem = true;
        $entity = 'processings';

        $processing_materials = TechProcessMaterial::with('processing', 'product')
                                                ->orderBy('created_at', 'desc')
                                                ->paginate(100);

        return view('production.processing.materials', compact("needMenuForItem", "entity", 'processing_materials'));
    }
}
