<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Models\Outgoing;
use App\Http\Requests\StoreOutgoingRequest;
use App\Http\Requests\UpdateOutgoingRequest;
use Illuminate\Http\Request;

class OutgoingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entity = 'Расход';

        $outgoings = Outgoing::orderByDesc('id')->paginate(50);

        return view('goods.outgoing.index', compact("entity", 'outgoings'));
    }

    public function products(Request $request)
    {
        $entity = 'Расход';

        $outgoing_products = Outgoing::with('outgoing', 'products')->orderByDesc('id')->paginate(100);

        return view('goods.outgoing.products', compact("entity", 'outgoing_products'));
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
    public function store(StoreOutgoingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Outgoing $outgoing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outgoing $outgoing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutgoingRequest $request, Outgoing $outgoing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outgoing $outgoing)
    {
        //
    }
}
