<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Models\Incoming;
use App\Http\Requests\IncomingRequest;
use App\Models\Contact;
use App\Models\IncomingProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class IncomingController extends Controller
{
    public function index()
    {
        $entity = 'Приход';
        $entityCreate = 'incomings.create';

        $incomings = Incoming::orderByDesc('id')->paginate(50);

        return view('goods.incoming.index', compact(
            "entity",
            'incomings',
            'entityCreate'
        ));
    }

    public function products(Request $request)
    {
        $entity = 'Приход';
        $entityCreate = 'incomings.create';

        $incoming_products = IncomingProduct::with('incoming', 'products')->orderByDesc('id')->paginate(100);

        return view('goods.incoming.products', compact("entity", 'incoming_products', 'entityCreate'));
    }

    public function create()
    {
        $entity = 'Новый приход';
        $action = "incomings.store";

        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();

        $products = Product::select('id', 'name', 'price', 'weight_kg')
            ->where('type', Product::MATERIAL)
            ->orWhere('type', Product::PRODUCTS)
            ->orderBy('name')
            ->get();

        $materials_block = Product::select('id', 'name', 'price', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('building_material', 'бетон')
            ->orderBy('name')
            ->get();
        $materials_concrete = Product::select('id', 'name', 'price', 'weight_kg')
            ->where('type', Product::PRODUCTS)
            ->where('building_material', 'блок')
            ->orderBy('name')
            ->get();
        $products_block = Product::select('id', 'name', 'price', 'weight_kg')
            ->where('type', Product::MATERIAL)
            ->where('building_material', 'бетон')
            ->orderBy('name')
            ->get();
        $products_concrete = Product::select('id', 'name', 'price', 'weight_kg')
            ->where('type', Product::MATERIAL)
            ->where('building_material', 'блок')
            ->orderBy('name')
            ->get();

        return view(
            'goods.incoming.create',
            compact(
                'action',
                'entity',
                'contacts',
                'products',
                'materials_block',
                'materials_concrete',
                'products_block',
                'products_concrete',
            )
        );
    }

    public function store(IncomingRequest $request)
    {
        try {
            $incoming = new Incoming();

            $incoming->contact_id = $request->contact_id;

            if (isset($request->description)) {
                $incoming->description = $request->description;
            }

            $sum = 0;
            $incoming->sum = $sum;

            $incoming->save();

            // Add Order position
            if ($request->products) {

                foreach ($request->products as $product) {
                    if (isset($product['product'])) {

                        $incomingProduct = new IncomingProduct();

                        $product_bd = Product::select('id', 'price', 'balance')->find($product['product']);

                        $incomingProduct->incoming_id = $incoming->id;
                        $incomingProduct->product_id = $product_bd->id;
                        $incomingProduct->quantity = $product['count'];

                        $incomingProduct->price = $product_bd->price;
                        $incomingProduct->sum = $product_bd->price * $product['count'];
                        
                        $sum +=  $product_bd->price * $product['count'];
                        $incoming->sum = $sum;

                        $product_bd->balance += $product['count'];
                        
                        DB::transaction(function () use ($incomingProduct, $product_bd, $incoming) {
                            $incoming->update();
                            $product_bd->update();
                            $incomingProduct->save();
                        });
                    }
                }
            }

            $incoming->sum = $sum;

            $incoming->update();

            return redirect()->route('incomings.index')->with('succes', 'Приход' . $incoming->id . ' добавлен');
            
        } catch (Throwable $e) {
            return redirect()->route('incomings.index')->with('danger', 'Ошибка');
        }
    }

    public function show($incoming)
    {
        $entity = 'Приход';

        $incoming = Incoming::with('contact', 'products')->find($incoming);

        return view('goods.incoming.show', compact("entity", 'incoming'));
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
