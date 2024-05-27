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

        $deliveries = Delivery::whereNot('ms_id', '28803b00-5c8f-11ea-0a80-02ed000b1ce1')->orderBy('distance', 'asc')->get();
        $vehicleTypes = TransportType::whereNot('ms_id', '5c2ad6bd-3dcf-11ee-0a80-105c001170bb')
                    ->whereNot('ms_id', '8caf01fa-34f2-11ee-0a80-139c002ba64a')
                    ->whereNot('ms_id', 'c518da75-a146-11ec-0a80-0da500133bca')
                    ->get();
        $shippingPrices = json_encode(ShipingPrice::get());

        $dekor_gray = Product::query()->where('name', '=', 'Декор (серый)')->first()?->price;
        $dekor_color = Product::query()->where('name', '=', 'Декор (красный)')->first()?->price;

        $parapet_gray = Product::query()->where('name', '=', 'Парапет 390*190*60 (серый)')->first()?->price;
        $parapet_color = Product::query()->where('name', '=', 'Парапет 390*190*60 (красный)')->first()?->price;

        $cap_gray = Product::query()->where('name', '=', 'Крышка на колонну 390*390*60 (серая)')->first()?->price;
        $cap_color = Product::query()->where('name', '=', 'Крышка на колонну 390*390*60 (красная)')->first()?->price;

        $column_gray = Product::query()->where('name', '=', 'Колонна 280*190*280 (серая)')->first()?->price;
        $column_color = Product::query()->where('name', '=', 'Колонна 280*190*280 (красная)')->first()?->price;

        $block12_gray = Product::query()->where('name', '=', 'Заборный блок  120*190*390 (серый)')->first()?->price;
        $block12_color = Product::query()->where('name', '=', 'Заборный блок 120*190*390 (красный)')->first()?->price;

        return view(
            "calculator.block",
            compact(
                "needMenuForItem",
                "entity",
                "dekor_gray",
                "dekor_color",
                "parapet_gray",
                "parapet_color",
                "cap_gray",
                "cap_color",
                "column_gray",
                "column_color",
                "block12_gray",
                "block12_color",
                'deliveries',
                'vehicleTypes',
                'shippingPrices'
            )
        );
    }

    public function concrete()
    {
        $deliveries = Delivery::select('id', 'ms_id', 'name', 'distance')
                                ->whereNot('ms_id', '28803b00-5c8f-11ea-0a80-02ed000b1ce1')
                                ->orderBy('name', 'asc')
                                ->get();

        $vehicleTypes = TransportType::select('id', 'ms_id', 'name')
                                ->whereNot('ms_id', '5c2ad6bd-3dcf-11ee-0a80-105c001170bb')
                                ->whereNot('ms_id', '8caf01fa-34f2-11ee-0a80-139c002ba64a')
                                ->whereNot('ms_id', 'c518da75-a146-11ec-0a80-0da500133bca')
                                ->orderBy('name', 'asc')
                                ->get();

        $shippingPrices = ShipingPrice::select('distance', 'tonnage', 'price', 'transport_type_id')->get();
        $products = Product::select("id", "ms_id", "price", "category_id", 'color_id', "weight_kg")->whereNotNull("color_id")->orderBy("name","asc")->get();

        $productsByGroup=[];

        foreach($products as $product){
            $productsByGroup[$product->category_id]["name"] = $product->category->name;
            $productsByGroup[$product->category_id]["id"] = $product->category_id;
            $productsByGroup[$product->category_id]["colors"][] = [
                "id" => $product->color_id,
                "name" => $product->color->name,
                "hex"=>$product->color->hex,
                "font_color"=>$product->color->font_color,
                "price" => $product->price,
                "product" => $product->ms_id,
                "weight" => ceil($product->weight_kg)
            ];
        }

        return view(
            "calculator.concrete",
            compact(
                'productsByGroup',
                'deliveries',
                'vehicleTypes',
                'shippingPrices'
            )
        );
    }
}
