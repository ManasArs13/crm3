<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutgoingRequest;
use App\Models\Outgoing;
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
        $urlDelete = "outgoings.destroy";

        $outgoings = Outgoing::with('products')->orderByDesc('id')->paginate(100);

        return view('goods.outgoing.index', compact(
            "entity",
            'outgoings',
            'entityCreate',
            'urlDelete'
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

            // Add Order position
            if ($request->products) {

                $summa = 0;
                $outgoing->summa = $summa;

                $outgoing->save();

                foreach ($request->products as $product) {
                    if (isset($product['product'])) {

                        $outgoingProduct = new OutgoingProduct();

                        $product_bd = Product::select('id', 'price', 'balance')->find($product['product']);

                        $outgoingProduct->outgoing_id = $outgoing->id;
                        $outgoingProduct->product_id = $product_bd->id;
                        $outgoingProduct->quantity = $product['count'];

                        $outgoingProduct->price = $product_bd->price;
                        $outgoingProduct->summa = $product_bd->price * $product['count'];

                        $summa +=  $product_bd->price * $product['count'];
                        $outgoing->summa = $summa;

                        $product_bd->balance -= $product['count'];

                        DB::transaction(function () use ($outgoingProduct, $product_bd, $outgoing) {
                            $outgoing->update();
                            $product_bd->update();
                            $outgoingProduct->save();
                        });
                    }
                }
            } else {
                return redirect()->route('outgoings.index')->with('danger', 'Вы не добавили продукты');
            }

            return redirect()->route('outgoings.index')->with('success', 'Расход ' . $outgoing->id . ' добавлен');
        } catch (Throwable $e) {
            return redirect()->route('outgoings.index')->with('danger', $e->getMessage());
        }
    }

    public function show($outgoing)
    {
        $entity = 'Расход';
        $action = 'outgoings.update';

        $outgoing = Outgoing::with('contact', 'products')->find($outgoing);

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


        return view('goods.outgoing.show', compact(
            "entity",
            'outgoing',
            'action',
            'products',
            'materials_block',
            'materials_concrete',
            'products_block',
            'products_concrete',
        ));
    }

    public function update(OutgoingRequest $request, $outgoing)
    {
        try {
            $outgoing = Outgoing::with('contact', 'products')->find($outgoing);

            if (isset($request->description)) {
                $outgoing->description = $request->description;
            }

            $outgoing->update();

            $outgoing_summa = $outgoing->summa;

            //Delete products in bd
            foreach ($outgoing->products as $product) {

                $product->balance += $product->pivot->quantity;

                $outgoing_summa -=  $product->pivot->summa;

                DB::transaction(function () use ($product, $outgoing, $outgoing_summa) {
                    $product->update();
                    $product->pivot->delete();
                    $outgoing->update(['sum' => $outgoing_summa]);
                });
            }

            if ($request->products) {

                $summa = 0;

                foreach ($request->products as $product) {
                    if (isset($product['product'])) {

                        $outgoingProduct = new OutgoingProduct();

                        $product_bd = Product::select('id', 'price', 'balance')->find($product['product']);

                        $outgoingProduct->outgoing_id = $outgoing->id;
                        $outgoingProduct->product_id = $product_bd->id;
                        $outgoingProduct->quantity = $product['count'];

                        $outgoingProduct->price = $product_bd->price;
                        $outgoingProduct->summa = $product_bd->price * $product['count'];

                        $summa +=  $product_bd->price * $product['count'];
                        $outgoing->summa = $summa;

                        $product_bd->balance -= $product['count'];

                        DB::transaction(function () use ($outgoingProduct, $product_bd, $outgoing) {
                            $outgoing->update();
                            $product_bd->update();
                            $outgoingProduct->save();
                        });
                    }
                }
            }

            return redirect()->route('outgoings.show', ['outgoing' => $outgoing->id])->with('success', 'Расход обновлён');
        } catch (Throwable $e) {
            return redirect()->route('outgoings.show', ['outgoing' => $outgoing->id])->with('danger', $e->getMessage());
        }
    }

    public function destroy($outgoing)
    {
        try {
            $outgoing = Outgoing::with('contact', 'products')->find($outgoing);

            $outgoing_summa = $outgoing->summa;

            //Delete products in bd
            if ($outgoing->products) {

                //Delete products in bd
                foreach ($outgoing->products as $product) {

                    $product->balance = $product->balance + $product->pivot->quantity;
                    $outgoing_summa -=  $product->pivot->summa;

                    DB::transaction(function () use ($product, $outgoing, $outgoing_summa) {
                        $product->update();
                        $product->pivot->delete();
                        $outgoing->update(['sum' => $outgoing_summa]);
                    });
                }
            }

            $outgoing->delete();

            return redirect()->route('outgoings.index')->with('success', 'Расход успешно удалён');
        } catch (Throwable $e) {
            return redirect()->route('outgoings.show', ['outgoing' => $outgoing->id])->with('danger', $e->getMessage());
        }
    }
}
