<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutgoingRequest;
use App\Models\Outgoing;
use App\Models\Contact;
use App\Models\OutgoingProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OutgoingController extends Controller
{
    public function index()
    {
        $entity = 'Расход';
        $entityCreate = 'outgoings.create';

        $outgoings = Outgoing::orderByDesc('id')->paginate(50);

        return view('goods.outgoing.index', compact(
            "entity",
            'outgoings',
            'entityCreate'
        ));
    }

    public function products(Request $request)
    {
        $entity = 'Расход';
        $entityCreate = 'outgoings.create';

        $outgoing_products = OutgoingProduct::with('outgoing', 'products')->orderByDesc('id')->paginate(100);

        return view('goods.outgoing.products', compact("entity", 'outgoing_products', 'entityCreate'));
    }

    public function create()
    {
        $entity = 'Новый расход';
        $action = "outgoings.store";

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
            'goods.outgoing.create',
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

    public function store(OutgoingRequest $request)
    {
        try {
            $outgoing = new Outgoing();

            $outgoing->contact_id = $request->contact_id;

            if (isset($request->description)) {
                $outgoing->description = $request->description;
            }

            $sum = 0;
            $outgoing->sum = $sum;

            $outgoing->save();

            // Add Order position
            if ($request->products) {

                foreach ($request->products as $product) {
                    if (isset($product['product'])) {

                        $outgoingProduct = new OutgoingProduct();

                        $product_bd = Product::select('id', 'price', 'balance')->find($product['product']);

                        $outgoingProduct->outgoing_id = $outgoing->id;
                        $outgoingProduct->product_id = $product_bd->id;
                        $outgoingProduct->quantity = $product['count'];

                        $outgoingProduct->price = $product_bd->price;
                        $outgoingProduct->sum = $product_bd->price * $product['count'];
                        
                        $sum +=  $product_bd->price * $product['count'];
                        $outgoing->sum = $sum;

                        $product_bd->balance -= $product['count'];                      

                        DB::transaction(function () use ($outgoingProduct, $product_bd, $outgoing) {
                            $outgoing->update();
                            $product_bd->update();
                            $outgoingProduct->save();
                        });
                    }
                }
            }

            return redirect()->route('outgoings.index')->with('succes', 'Приход' . $outgoing->id . ' добавлен');
            
        } catch (Throwable $e) {
            return redirect()->route('outgoings.index')->with('danger', 'Ошибка');
        }
    }

    public function show($outgoing)
    {
        $entity = 'Расход';

        $outgoing = Outgoing::with('contact', 'products')->find($outgoing);

        return view('goods.outgoing.show', compact("entity", 'outgoing'));
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
    public function update(OutgoingRequest $request, Outgoing $outgoing)
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
