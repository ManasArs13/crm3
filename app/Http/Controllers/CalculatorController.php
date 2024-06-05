<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Product;
use App\Models\ShipingPrice;
use App\Models\TransportType;
use App\Models\Date;
use App\Models\Time;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

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

        $contacts = Contact::where('name', '<>', null)->OrderBy('name')->get();

        $dateNow=(new \DateTime())->format("Y-m-d");
        $dateFinish=(new \DateTime())->modify('+10 day')->format("Y-m-d");

        $dateNowQuery=$dateNow.' 00:00.000';
        $dateFinishQuery=$dateFinish.'  23:59.000';

        $datesCalc= DB::table('orders')
        ->join('order_positions', 'orders.id', '=', 'order_positions.order_id')
        ->join('products', 'products.id', '=', 'order_positions.product_id')
        ->select(DB::raw('distinct(orders.name) as name'),
                 DB::raw('DATE_FORMAT(orders.date_plan, "%d.%m.%Y") as date'),
                 DB::raw("CONCAT(DATE_FORMAT(orders.date_plan, '%h'),':00:00.000') as time")
        )
        ->whereIn("category_id",['6','12','21','15','11'])
        ->whereBetween("orders.date_plan",[$dateNowQuery, $dateFinishQuery])
        ->get();

        $datesBlock= DB::table('orders')
        ->join('order_positions', 'orders.id', '=', 'order_positions.order_id')
        ->join('products', 'products.id', '=', 'order_positions.product_id')
        ->select(DB::raw('distinct(orders.name) as name'),
                 DB::raw('DATE_FORMAT(orders.date_plan, "%d.%m.%Y") as date'),
                 DB::raw("CONCAT(DATE_FORMAT(orders.date_plan, '%h'),':00:00.000') as time")
        )
        ->whereNotNull("products.color_id")
        ->whereBetween("orders.date_plan",[$dateNowQuery, $dateFinishQuery])
        ->get();

        $datesBeton= DB::table('orders')
        ->join('order_positions', 'orders.id', '=', 'order_positions.order_id')
        ->join('products', 'products.id', '=', 'order_positions.product_id')
        ->select(
                 DB::raw('distinct(orders.name) as name'),
                 DB::raw('DATE_FORMAT(orders.date_plan, "%d.%m.%Y") as date'),
                 DB::raw("CONCAT(DATE_FORMAT(orders.date_plan, '%h'),':00:00.000') as time")
        )
        ->where("category_id",'4')
        ->whereBetween("orders.date_plan",[$dateNowQuery, $dateFinishQuery])
        ->get();

        $datesCalcFinish=[];
        foreach($datesCalc as $date){
            $datesCalcFinish[$date->date][$date->time][]=$date->name;
        }

        $datesBlockFinish=[];
        foreach($datesBlock as $date){
            $datesBlockFinish[$date->date][$date->time][]=$date->name;
        }

        $datesBetonFinish=[];
        foreach($datesBeton as $date){
            $datesBetonFinish[$date->date][$date->time][]=$date->name;
        }


        $dates=Date::where("is_active", 1)->where("date",">=",$dateNow)->where("date","<=",$dateFinish)->orderBy("date","asc")->get();
        $times=Time::where("is_active", 1)->get();
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

        $idBeton = 0;
        foreach($betonProducts as $product){
            if ($idBeton==0)
                $idBeton = $product->id;

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
                'dates',
                'times',
                'idBeton',
                'contacts',
                'datesBlockFinish',
                'datesBetonFinish'
            )
        );
    }
}
