<?php
namespace App\Http\Controllers\Api\Site;
use App\Http\Controllers\Controller;
use App\Models\ShipingPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class ShipingPriceController extends Controller
{
    public function getPrice(Request $request)
    {
        $distance = (int)$request->post()["distance"];
        $vehicleType = (int)$request->post()["vehicleType"];
        $weightTn = (float)$request->post()["weightTn"];

        $price=0;
        $deliveryPrice=0;

        $innerQuery=DB::table('shiping_prices')
                ->selectRaw('transport_type_id, min(distance) as minDistance')
                ->where('transport_type_id', $vehicleType)
                ->where('distance','>=',$distance);

        $mainQuery0 = DB::table(DB::raw('(' .$innerQuery->toSql() . ') as tab'))
                ->mergeBindings($innerQuery)
                ->selectRaw("sh.distance, min(sh.tonnage) as tonnage, sh.transport_type_id")
                ->join("shiping_prices as sh", function($join){
                    $join->on('tab.minDistance', '=', 'sh.distance');
                    $join->on("tab.transport_type_id","=","sh.transport_type_id");
                })
                ->where("sh.tonnage",'>=',$weightTn);

        $mainQuery = DB::table(DB::raw('(' .$mainQuery0->toSql() . ') as tab0'))
                ->mergeBindings($mainQuery0)
                ->selectRaw("sh2.price, sh2.tonnage as ton, sh2.distance")
                ->join("shiping_prices as sh2", function($join){
                    $join->on('tab0.distance', '=', 'sh2.distance');
                    $join->on("tab0.transport_type_id","=","sh2.transport_type_id");
                    $join->on("tab0.tonnage","=","sh2.tonnage");
                })->first();


        if($mainQuery==null){
            $mainQuery = DB::table(DB::raw('(' .$innerQuery->toSql() . ') as tab'))
                ->mergeBindings($innerQuery)
                ->selectRaw("sh2.tonnage as ton, sh2.distance, sh2.price")
                ->join("shiping_prices as sh2", function($join){
                    $join->on('tab.minDistance', '=', 'sh2.distance');
                    $join->on("tab.transport_type_id","=","sh2.transport_type_id");
                })
                ->orderBy("sh2.tonnage","desc")
                ->first();

            if ($mainQuery==null){
                return response()->json(["error"=>Lang::get("error.noPrice")], 504);
            }else{
                $price = $mainQuery->price;
            }
        }else{
            $price = $mainQuery->price;
        }

        if ($mainQuery->ton>$weightTn){
            $weightTn=$mainQuery->ton;
        }

        $deliveryPrice = $price * $weightTn;

        if ($vehicleType==5 || $vehicleType==3)
            $deliveryPrice=round($deliveryPrice/100)*100;
        else if ($vehicleType==4 && $weightTn<=14)
            $deliveryPrice=round($deliveryPrice/500)*500;

        return response()->json(["price"=>$price, "deliveryPrice"=>$deliveryPrice, 'weightTn'=>$weightTn], 200);
    }
}
