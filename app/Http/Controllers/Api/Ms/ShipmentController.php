<?php
namespace App\Http\Controllers\Api\Ms;

use App\Http\Controllers\Controller;
use App\Services\EntityMs\ShipmentMsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Shipment;


class ShipmentController extends Controller
{
    public function setShipmentToMs(Request $request,ShipmentMsService $shipmentMsService ): Response
    {
        $id=$request->id;

        $result = $shipmentMsService->createChipmentToMs($id);
        $shipment=Shipment::find($id);

        $text=" cоздана";
        if ($shipment->ms_id!=null){
            $text=" отредактирована";
        }

        if (isset($result->id)) {
            $shipment=Shipment::find($id);
            $shipment->ms_id=$result->id;
            $shipment->save();

            return new Response("<a href='" . 'https://online.moysklad.ru/app/#demand/edit?id=' . $result->id . "' class='font-medium text-blue-600 dark:text-blue-500 hover:underline'  target='_blank'>" . 'Отгрузка №' . $result->name . $text . " !</a>", 200);
        } else {
            return new Response(trans("error.Error").$result);
        }

    }

}
