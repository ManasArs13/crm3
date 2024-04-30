<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Models\Incoming;
use App\Http\Requests\IncomingRequest;
use App\Models\IncomingProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class IncomingController extends Controller
{
    public function index()
    {
        $entity = 'Приход';
        $searchContacts = 'api.get.contact';
        $searchProducts = 'api.get.product';
        $incomingCreate = 'incomings.store';

        $incomings = Incoming::orderByDesc('id')->paginate(50);

        return view('goods.incoming.index', compact(
            "entity",
            'incomings',
            'searchContacts',
            'searchProducts',
            'incomingCreate'
        ));
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

    public function store(IncomingRequest $request)
    {
        $incoming = new Incoming();

        $incoming->contact_id = $request->contact_id;

        if (isset($request->description)) {
            $incoming->description = $request->description;
        }

        $sum = 0;
        $incoming->sum = $sum;

        $incoming->save();

        if (isset($request->product_id)) {

            $incominhProduct = new IncomingProduct();

            $incominhProduct->incoming_id = $incoming->id;
            $incominhProduct->product_id = $request->product_id;
            $incominhProduct->quantity = $request->quantity;

            $product_price = Product::select('price')->where('id', $request->product_id)->first()->price;

            $incominhProduct->price = $product_price;
            $incominhProduct->sum = $product_price * $request->quantity;
            $sum += $product_price * $request->quantity;

            $incominhProduct->save();
        }

        $incoming->sum = $sum;

        $incoming->update();

        return redirect()->route('incomings.index')->with('succes', 'Приход' . $incoming->id . ' добавлен');
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
    public function update(IncomingRequest $request, Incoming $incoming)
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
