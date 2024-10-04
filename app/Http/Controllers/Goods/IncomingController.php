<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use App\Models\Incoming;
use App\Http\Requests\IncomingRequest;
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
        $urlDelete = "incomings.destroy";

        $incomings = Incoming::with('products')->orderByDesc('id')->paginate(100);

        return view('goods.incoming.index', compact(
            "entity",
            'incomings',
            'entityCreate',
            'urlDelete'
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

            // Add Order position
            if ($request->products) {

                $summa = 0;
                $incoming->summa = $summa;
                $incoming->save();

                foreach ($request->products as $product) {
                    if (isset($product['product'])) {

                        $incomingProduct = new IncomingProduct();

                        $product_bd = Product::select('id', 'price', 'balance')->find($product['product']);

                        $incomingProduct->incoming_id = $incoming->id;
                        $incomingProduct->product_id = $product_bd->id;
                        $incomingProduct->quantity = $product['count'];

                        $incomingProduct->price = $product_bd->price;
                        $incomingProduct->summa = $product_bd->price * $product['count'];

                        $summa +=  $product_bd->price * $product['count'];
                        $incoming->summa = $summa;

                        $product_bd->balance += $product['count'];

                        DB::transaction(function () use ($incomingProduct, $product_bd, $incoming) {
                            $incoming->update();
                            $product_bd->update();
                            $incomingProduct->save();
                        });
                    }
                }
            } else {
                return redirect()->route('incomings.index')->with('danger', 'Вы не добавили продукты');
            }

            return redirect()->route('incomings.index')->with('success', 'Приход ' . $incoming->id . ' добавлен');
        } catch (Throwable $e) {
            return redirect()->route('incomings.index')->with('danger', $e->getMessage());
        }
    }

    public function show($incoming)
    {
        $entity = 'Приход';
        $action = 'incomings.update';

        $incoming = Incoming::with('contact', 'products')->find($incoming);

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


        return view('goods.incoming.show', compact(
            "entity",
            'incoming',
            'action',
            'products',
            'materials_block',
            'materials_concrete',
            'products_block',
            'products_concrete',
        ));
    }

    public function update(IncomingRequest $request, $incoming)
    {
        try {
            $incoming = Incoming::with('contact', 'products')->find($incoming);

            if (isset($request->description)) {
                $incoming->description = $request->description;
            }

            $incoming->update();

            $incoming_summa = $incoming->summa;

            //Delete products in bd
            foreach ($incoming->products as $product) {

                $product->balance -= $product->pivot->quantity;

                $incoming_summa -=  $product->pivot->summa;

                DB::transaction(function () use ($product, $incoming, $incoming_summa) {
                    $product->update();
                    $product->pivot->delete();
                    $incoming->update(['summa' => $incoming_summa]);
                });
            }

            if ($request->products) {

                $summa = 0;

                foreach ($request->products as $product) {
                    if (isset($product['product'])) {

                        $incomingProduct = new IncomingProduct();

                        $product_bd = Product::select('id', 'price', 'balance')->find($product['product']);

                        $incomingProduct->incoming_id = $incoming->id;
                        $incomingProduct->product_id = $product_bd->id;
                        $incomingProduct->quantity = $product['count'];

                        $incomingProduct->price = $product_bd->price;
                        $incomingProduct->summa = $product_bd->price * $product['count'];

                        $summa +=  $product_bd->price * $product['count'];
                        $incoming->summa = $summa;

                        $product_bd->balance += $product['count'];

                        DB::transaction(function () use ($incomingProduct, $product_bd, $incoming) {
                            $incoming->update();
                            $product_bd->update();
                            $incomingProduct->save();
                        });
                    }
                }
            }

            return redirect()->route('incomings.show', ['incoming' => $incoming->id])->with('success', 'Приход обновлён');
        } catch (Throwable $e) {
            return redirect()->route('incomings.show', ['incoming' => $incoming->id])->with('danger', $e->getMessage());
        }
    }

    public function destroy($incoming)
    {
        try {
            $incoming = Incoming::with('contact', 'products')->find($incoming);

            $incoming_quantity = $incoming->quantity;
            $incoming_summa = $incoming->summa;

            //Delete products in bd
            if ($incoming->products) {

                //Delete products in bd
                foreach ($incoming->products as $product) {

                    $product->balance = $product->balance - $product->pivot->quantity;
                    $incoming_quantity -= $product->pivot->quantity;
                    $incoming_summa -=  $product->pivot->summa;

                    DB::transaction(function () use ($product, $incoming, $incoming_quantity, $incoming_summa) {
                        $product->update();
                        $product->pivot->delete();
                        $incoming->update(['quantity' => $incoming_quantity, 'sum' => $incoming_summa]);
                    });
                }
            }

            $incoming->delete();

            return redirect()->route('incomings.index')->with('success', 'Приход успешно удалён');
        } catch (Throwable $e) {
            return redirect()->route('incomings.show', ['incoming' => $incoming->id])->with('danger', $e->getMessage());
        }
    }
}
