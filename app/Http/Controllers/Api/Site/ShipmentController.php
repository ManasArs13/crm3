<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;


class ShipmentController extends Controller
{

    public function getShipmentsByMonthAndCategory()
    {
        $date = Carbon::now()->format('Y-m-d');

        $labels = [
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12',
        ];
        $charts= [];

        $year = Carbon::now()->format('Y');


        $dbResults=Shipment::selectRaw('building_material as material,
        DATE_FORMAT(`shipments`.created_at, "%m") as month1,
        sum(shipment_products.price*shipment_products.quantity) as sum1')
        ->join('shipment_products','shipments.id','=','shipment_products.shipment_id')
        ->join('products','products.id','=','shipment_products.product_id')
        ->whereNotNull('building_material')->where('building_material','<>', Product::NOT_SELECTED)
         ->where('type','=', Product::PRODUCTS)
        ->where('shipments.created_at','>=', $year.'-01-01 00:00:00')
        ->groupBy('month1',"material")->orderBy("month1")->get();

        $datasets=[];

        foreach ($dbResults as $dbRow) {

            if($dbRow->material == 'блок'){
                $color = '#86EFAC';
            }elseif($dbRow->material == 'бетон'){
                $color = '#FFCD56';
            }else{
                $color = '#FCA5A5';
            }

            if (!isset($charts[$dbRow->material])) {
                $charts[$dbRow->material] = array_fill_keys($labels, null); // Заполняем null для всех месяцев
            }

            $charts[$dbRow->material][$dbRow->month1] = $dbRow->sum1;

            $datasets[$dbRow->material] = [
                "label" => $dbRow->material,
                "hidden" => false,
                "data" => array_values($charts[$dbRow->material]),
                "backgroundColor" => $color,
                "borderColor" => $color,
                "borderWidth" => 4
            ];
        }
        return response()->json([
            'charts'=>$charts,
            'labels'=>$labels,
            'datasets'=>$datasets
        ]);
    }

}
