<?php
namespace App\Http\Controllers\Api\Site;
use App\Http\Controllers\Controller;
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

        $innerQuery=DB::table('shiping_prices as tab')
                ->select('distance','tonnage as ton','price', DB::raw('min(distance) as minDistance'))
                ->where('transport_type_id', $vehicleType)
                ->where('distance','>=',$distance)
                ->groupBy('tonnage')
                ->havingRaw('distance=minDistance');

        $mainQuery = DB::table(DB::raw('(' .$innerQuery->toSql() . ') as tab'))
                ->mergeBindings($innerQuery)
                ->select("ton", "distance", "price", DB::raw('min(ton) as minTon'))
                ->where("ton",'>=',$weightTn)
                ->havingRaw('ton=minTon')
                ->first();

        if($mainQuery==null){
            $mainQuery = DB::table(DB::raw('(' .$innerQuery->toSql() . ') as tab'))
                ->mergeBindings($innerQuery)
                ->select("ton", "distance", "price")
                ->orderBy("ton","desc")
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
