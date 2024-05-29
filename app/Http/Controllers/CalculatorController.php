<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Product;
use App\Models\ShipingPrice;
use App\Models\TransportType;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function block()
    {
        $entity = 'calculator';
        $needMenuForItem = true;

        $deliveries = Delivery::whereNot('ms_id', '28803b00-5c8f-11ea-0a80-02ed000b1ce1')->orderBy('name', 'asc')->get();
        $vehicleTypes = TransportType::whereNot('ms_id', '5c2ad6bd-3dcf-11ee-0a80-105c001170bb')
                    ->whereNot('ms_id', '8caf01fa-34f2-11ee-0a80-139c002ba64a')
                    ->whereNot('ms_id', 'c518da75-a146-11ec-0a80-0da500133bca')
                    ->orderBy('name', 'asc')
                    ->get();

        $vehicleTypesBeton = TransportType::where('ms_id', '8caf01fa-34f2-11ee-0a80-139c002ba64a')
                    ->orderBy('name', 'asc')
                    ->get();

        $shippingPrices = json_encode(ShipingPrice::get());

        $products = Product::select("id", "ms_id", "name", "price", "category_id", 'color_id', "weight_kg")->whereNotNull("color_id")->orderBy("name","asc")->get();
        $betonProducts =  Product::select("id", "ms_id", "name", "price", "category_id", 'color_id', "weight_kg")->Where("category_id","4a3126bc-262d-11ee-0a80-011a00246492")->orderBy("name","asc")->get();

        $productsByGroup=[];
        $productsByBeton=[];
        $idCategory=0;

        foreach($products as $product){
            if ($product->ms_id=="a656eb95-be75-11ee-0a80-15e100320243"){
                $product->category_id=$product->category_id."_1";
                $product->category->name=$product->name;
                $idCategory=$product->category_id;
            }

            $productsByGroup[$product->category_id]["name"] = $product->category->name;
            $productsByGroup[$product->category_id]["id"] = $product->category_id;

            if ($product->color!=null){
                $productsByGroup[$product->category_id]["colors"][] = [
                        "id" => $product->color_id,
                        "hex"=>$product->color->hex,
                        "name"=>$product->color->name,
                        "font_color"=>$product->color->font_color,
                        "price" => $product->price,
                        "product" => $product->ms_id,
                        "weight" => ceil($product->weight_kg),
                        "selected"=>($product->color_id==5)?"selected":''
                ];
            }
        }

        $productsByFence=$productsByGroup;
        unset($productsByFence[$idCategory]);
        unset($productsByFence[7]);
        unset($productsByFence[18]);
        unset($productsByFence[5]);

        foreach($betonProducts as $product){
            $productsByBeton[$product->id]["name"] = $product->name;
            $productsByBeton[$product->id]["id"] = $product->id;
            $productsByBeton[$product->id]["price"] = $product->price;
            $productsByBeton[$product->id]["weight"] = ceil($product->weight_kg);
            $productsByBeton[$product->id]["product"] = $product->ms_id;
        }

        return view(
            "calculator.calculator",
            compact(
                "needMenuForItem",
                "entity",
                "productsByFence",
                'deliveries',
                'vehicleTypes',
                'shippingPrices',
                'productsByGroup',
                'productsByBeton',
                'vehicleTypesBeton',
            )
        );
    }
}
