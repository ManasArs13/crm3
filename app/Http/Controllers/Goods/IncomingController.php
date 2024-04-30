<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Models\Incoming;
use App\Http\Requests\StoreIncomingRequest;
use App\Http\Requests\UpdateIncomingRequest;
use App\Models\IncomingProduct;
use Illuminate\Http\Request;

class IncomingController extends Controller
{
    public function index()
    {
        $entity = 'Приход';

        $incomings = Incoming::orderByDesc('id')->paginate(50);

        return view('goods.incoming.index', compact("entity", 'incomings'));
    }

    public function products(Request $request)
    {
        $entity = 'Приход';

        $incoming_products = IncomingProduct::with('incoming', 'products')->orderByDesc('id')->paginate(100);

        return view('goods.incoming.products', compact("entity", 'incoming_products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Incoming $incoming)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Incoming $incoming)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomingRequest $request, Incoming $incoming)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incoming $incoming)
    {
        //
    }
}
