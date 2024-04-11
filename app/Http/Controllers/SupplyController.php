<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\SupplyPosition;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index()
    {
        $entity = 'supplies';

        $supplies = Supply::with('contact')->orderBy('moment', 'desc')->paginate(100);

        return view('supply.index', compact("entity", 'supplies'));
    }

    public function show(Request $request, $processing)
    {
        $needMenuForItem = true;
        $entity = 'supply';

        $supply = Supply::with('contact', 'products')->find($processing);

        return view('supply.show', compact("entity", 'supply'));
    }

    public function products(Request $request)
    {
        $needMenuForItem = true;
        $entity = 'supplies';

        $supply_products = SupplyPosition::with('supply', 'products')->orderBy('created_at', 'desc')->paginate(100);

        return view('supply.products', compact("entity", 'supply_products'));
    }
}
